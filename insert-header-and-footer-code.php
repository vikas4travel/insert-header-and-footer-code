<?php
/**
 * Plugin Name:       Insert Header and Footer Code
 * Plugin URI:        https://caketech.in/
 * Description:       Allows you to insert CSS, Javascript code or any other script required by google analytics, tracking and marketing services to the header or footer of your WordPress website.
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Vikas Sharma
 * Author URI:        https://caketech.in/vikas/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       insert-header-and-footer-code
 *
 * Insert Header and Footer Code
 * Copyright (C) 2021, Vikas Sharma <vikas@caketech.in>
 *
 * Insert Header and Footer Code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Insert Header and Footer Code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Insert Header and Footer Code. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 *
 */

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

class Insert_Header_And_Footer_Code {

	static $plugin_name     = 'Insert Header and Footer Code';
	static $plugin_slug     = 'insert-header-and-footer-code';
	static $text_domain     = 'IHAFC';
	public $error_message   = '';
	public $success_message = '';

	public function __construct() {

		// Activation and Deactivation hooks
		register_activation_hook( __FILE__, [ $this, 'plugin_activation' ] );
		register_deactivation_hook( __FILE__, [ $this, 'plugin_deactivation' ] );
		add_action( 'admin_init', [ $this, 'do_activation_redirect' ] );

		add_action( 'admin_menu', [ $this, 'create_admin_menu' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts_and_styles' ] );
		add_action( 'admin_notices', [ $this, 'notice_welcome' ] );

		add_action( 'wp_head', [ $this, 'insert_header' ] );
		add_action( 'wp_footer', [ $this, 'insert_footer' ] );

		// Check if function exists, for backward compatibility
		if ( function_exists( 'wp_body_open' ) ) {
			add_action( 'wp_body_open', [ $this, 'insert_body' ], 1 );
		}
	}

	/**
	 * Activate the plugin
	 */
	public function plugin_activation() : void {
		set_transient( '_plugin_activation_redirect', true, 30 );
	}

	/**
	 * Deactivate the plugin
	 */
	public function plugin_deactivation() : void {
		delete_option( 'ihafc_header' );
		delete_option( 'ihafc_footer' );
		delete_option( 'ihafc_body' );
		delete_option( 'ihafc_welcome' );
	}

	public function do_activation_redirect() {
		// Bail if no activation redirect
		if ( ! get_transient( '_plugin_activation_redirect' ) ) {
			return;
		}

		// Delete the redirect transient
		delete_transient( '_plugin_activation_redirect' );

		// Bail if activating from network, or bulk
		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		// Redirect to plugin page
		wp_safe_redirect( add_query_arg( array( 'page' => self::$plugin_slug ), admin_url( 'options-general.php' ) ) );
	}

	/**
	 * Add menu item in the admin area.
	 */
	public function create_admin_menu() {
		add_submenu_page( 'options-general.php', self::$plugin_name, self::$plugin_name, 'manage_options', self::$plugin_slug, [ $this, 'admin_panel' ] );
	}

	/**
	 * Enqueue CSS for ou plugin in admin area.
	 */
	public function enqueue_admin_scripts_and_styles(){
		wp_enqueue_style('ihfc_admin_style', plugin_dir_url(__FILE__) . '/css/style.css');
		wp_enqueue_script( 'ihfc_admin_script', plugin_dir_url(__FILE__) . '/js/scripts.js', array(), '1.0.0', true );

		if ( function_exists( 'wp_enqueue_code_editor' ) ) {
			$settings = wp_enqueue_code_editor( ['type' => 'text/html'] );
			if ( false !== $settings ) {
				wp_localize_script( 'jquery', 'editor_settings', $settings );
				wp_enqueue_script( 'wp-theme-plugin-editor' );
				wp_enqueue_style( 'wp-codemirror' );
			}
		}
	}

	/**
	 * Display welcome messages
	 */
	public function notice_welcome() {
		global $pagenow;

		if ( 'options-general.php' === $pagenow && isset( $_GET['page'] ) && self::$plugin_slug === $_GET['page'] ) {
			if ( ! get_option( 'ihafc_welcome' ) ) {
				?>
				<div class="notice notice-success is-dismissible">
					<p><?php echo __('Thank you for installing Insert Header and Footer Code.', self::$text_domain) ?></p>
				</div>
				<?php
				update_option( 'ihafc_welcome', 1 );
			}

		}
	}

	public function admin_panel(){
		if ( ! current_user_can( 'administrator' ) ) {
			echo '<p>' . __( 'Sorry, you are not allowed to access this page.', self::$text_domain ) . '</p>';
			return;
		}

		$active_tab = 1;

		// if the form was submitted
		if ( isset( $_POST[ self::$plugin_slug . '-nonce' ] ) ) { // Input var okay.

			// Verify the nonce before proceeding.
			if ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::$plugin_slug . '-nonce' ] ) ), self::$plugin_slug ) ) { // Input var okay.

				// Not sanitizing the $_POST here, we expecting admin to submit code snippet as input.
				$ihafc_header = ! empty( $_POST['ihafc_header'] ) ? wp_unslash( $_POST['ihafc_header'] ) : ''; // @codingStandardsIgnoreLine
				$ihafc_footer = ! empty( $_POST['ihafc_footer'] ) ? wp_unslash( $_POST['ihafc_footer'] ) : ''; // @codingStandardsIgnoreLine
				$ihafc_body   = ! empty( $_POST['ihafc_body'] ) ? wp_unslash( $_POST['ihafc_body'] ) : ''; // @codingStandardsIgnoreLine
				$active_tab   = ! empty( $_POST['ihafc_current_tab'] ) ? intval( $_POST['ihafc_current_tab'] ) : ''; // Input var okay.

				update_option( 'ihafc_header', $ihafc_header );
				update_option( 'ihafc_footer', $ihafc_footer );
				update_option( 'ihafc_body',  $ihafc_body );

				echo '<div class="notice notice-success is-dismissible"><p>' . __( 'Success! data saved successfully.', self::$text_domain ) . '</p></div>';

			} else {
				echo '<div class="notice notice-error is-dismissible"><p>' . __( 'Error: Invalid nonce, data not saved, please try again!', self::$text_domain ) . '</p></div>';
			}
		}

		// Display the plugin page
		include_once( __DIR__ . '/templates/admin-panel.php' );
	}

	/**
	 * Insert Header Code
	 */
	public function insert_header() {
		self::print_code( get_option( 'ihafc_header' ) );
	}

	/**
	 * Insert Footer Code
	 */
	public function insert_footer() {
		self::print_code( get_option( 'ihafc_footer' ) );
	}

	/**
	 * Insert code in <body></body>
	 */
	public function insert_body() {
		self::print_code( get_option( 'ihafc_body' ) );
	}

	/**
	 * Output code
	 * @param $code
	 */
	public function print_code( $code ) {
		if ( empty( $code ) || is_admin() || is_feed() || is_robots() || is_trackback() ) {
			return;
		}
		echo PHP_EOL . $code . PHP_EOL;
	}
}

new Insert_Header_And_Footer_Code();
