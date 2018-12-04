/**
 * Theme Customizer enhancements for a better user experience.
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {

	if( 'undefined' == typeof hootInlineStyles )
		window.hootInlineStyles = [ 'hoot-style', [] ];

	/*** Create placeholder style tags for each setting via postMessage ***/

	if ( $.isArray( hootInlineStyles ) && hootInlineStyles[1] && $.isArray( hootInlineStyles[1] ) ) {
		var csshandle = hootInlineStyles[0] + '-inline-css';
		for ( var hi = 0; hi < hootInlineStyles[1].length; hi++ ) {
			$( '#' + csshandle ).after( '<style id="hoot-customize-' + hootInlineStyles[1][hi] + '" type="text/css"></style>' );
			csshandle = 'hoot-customize-' + hootInlineStyles[1][hi];
		}
	}
	function hootUpdateCss( setting, value ) {
		var $target = $( '#hoot-customize-' + setting );
		if ( $target.length ) $target.html( value );
	}

	/*** Site title and description. ***/

	wp.customize( 'blogname', function( value ) {
		value.bind( function( newval ) {
			$( '#site-logo-text #site-title a' ).html( newval );
		} );
	} );

	wp.customize( 'blogdescription', function( value ) {
		value.bind( function( newval ) {
			$( '#site-description' ).html( newval );
		} );
	} );

	/** Theme Settings **/

	wp.customize( 'accent_color', function( value ) {
		value.bind( function( newval ) {
			var css = '';
			hootUpdateCss( 'accent_color', css );
		} );
	} );

} )( jQuery );