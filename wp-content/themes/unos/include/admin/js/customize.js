/**
 * Theme Customizer
 */


( function( api ) {

	// Extends our custom "hoot-theme" section. ( trt-customizer-pro - custom section )
	api.sectionConstructor['hoot-theme'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );


jQuery(document).ready(function($) {
	"use strict";


	/*** Hide and link module BG buttons ***/

	$('.frontpage_sections_modulebg .button').on('click',function(event){
		event.stopPropagation();
		var choice = $(this).closest('li.hoot-control-sortlistitem').data('choiceid');
		$('.hoot-control-id-frontpage_sectionbg_' + choice + ' .hoot-flypanel-button').trigger('click');
	});


});
