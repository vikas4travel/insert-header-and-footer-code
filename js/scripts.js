(function($) {
	$(document).on( 'click', '.ihafc-tabs a', function() {

		// Hide All sections
		$('.ihafc-section').hide();

		// Show the selcted one
		$('.ihafc-section').eq($(this).index()).show();

		// Highlight the Tab
		$('.ihafc-tabs a').attr( 'class', 'nav-tab' );
		$(this).attr( 'class', 'nav-tab nav-tab-active' );

		// Update current tab number, to highlight it after the form is submitted.
		$('#ihafc_current_tab').val( $(this).index() + 1 );

		if ( 3 === $(this).index() ) {
			$('#ihafc_submit').hide();
		} else {
			$('#ihafc_submit').show();
		}

		return false;
	})

	// Initiate code editor
	wp.codeEditor.initialize( $('#ihafc_header'), editor_settings );
	wp.codeEditor.initialize( $('#ihafc_footer'), editor_settings );
	wp.codeEditor.initialize( $('#ihafc_body'), editor_settings );

	// Click first Tab
	$('.nav-tab-active').click();

})( jQuery );
