<div class="wrap">
	<h2><?php echo __( 'Insert Header and Footer Code', self::$text_domain ); ?></h2>

	<h2 class="nav-tab-wrapper ihafc-tabs">
		<a class="nav-tab <?php echo ( 1 === $active_tab ) ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=' . self::$plugin_slug ); ?>"><?php echo __( 'Header', self::$text_domain ); ?></a>
		<a class="nav-tab <?php echo ( 2 === $active_tab ) ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=' . self::$plugin_slug ); ?>"><?php echo __( 'Footer', self::$text_domain ); ?></a>
		<a class="nav-tab <?php echo ( 3 === $active_tab ) ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=' . self::$plugin_slug ); ?>"><?php echo __( 'Body', self::$text_domain ); ?></a>
		<a class="nav-tab <?php echo ( 4 === $active_tab ) ? 'nav-tab-active' : ''; ?>" href="<?php echo admin_url( 'options-general.php?page=' . self::$plugin_slug ); ?>"><?php echo __( 'About', self::$text_domain ); ?></a>
	</h2>

	<form action="options-general.php?page=<?php echo esc_attr( self::$plugin_slug ); ?>" method="post">

		<?php wp_nonce_field( self::$plugin_slug, self::$plugin_slug . '-nonce' ); ?>
		<input type="hidden" name="ihafc_current_tab" id="ihafc_current_tab" value="1">

		<section class="ihafc-section">
			<p>
				<b><?php echo __( 'Insert code in header section, between', self::$text_domain ); ?> <?php echo htmlspecialchars( '<head></head>' )?></b>
			</p>
			<textarea name="ihafc_header" id="ihafc_header" class="widefat" rows="20"><?php echo get_option( 'ihafc_header' ); ?></textarea>
		</section>

		<section class="ihafc-section">
			<p>
				<b><?php echo __( 'Insert code in footer section - above closing', self::$text_domain ); ?> <?php echo htmlspecialchars( '</body>' )?> tag</b>
			</p>
			<textarea name="ihafc_footer" id="ihafc_footer" class="widefat" rows="20"><?php echo get_option( 'ihafc_footer' ); ?></textarea>
		</section>

		<section class="ihafc-section">
			<p>
				<b><?php echo __( 'Insert code in body section - between', self::$text_domain ); ?> <?php echo htmlspecialchars( '<body></body>' )?>.</b>
			</p>
			<textarea name="ihafc_body" id="ihafc_body" class="widefat" rows="20"><?php echo get_option( 'ihafc_body' ); ?></textarea>
		</section>

		<section class="ihafc-section">
			<div class="ihafc-about">

				<p><b><?php echo __( 'Insert Header and Footer Code', self::$text_domain ); ?></b></p>

				<p><?php echo __( 'Version: 1.0.1', self::$text_domain ); ?></p>

				<p><a href="http://caketech.in/" target="_blank"><?php echo __( 'Author\'s Website', self::$text_domain ); ?></a></p>

				<p><?php echo __( 'If you have any feedback please tell us. We love to improve our service.', self::$text_domain ); ?></p>

				<p><a href="http://caketech.in/provide-feedback/" target="_blank"><?php echo __( 'Provide Feedback', self::$text_domain ); ?></a></p>
			</div>

		</section>

		<p>
			<input name="submit" type="submit" id="ihafc_submit" class="button button-primary" value="<?php echo __( 'Save', self::$text_domain ); ?>" />
		</p>
	</form>

</div>



