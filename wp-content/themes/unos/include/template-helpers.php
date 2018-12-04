<?php
/**
 * Miscellaneous template tags and template utility functions
 * 
 * These functions are for use throughout the theme's various template files.
 * This file is loaded via the 'after_setup_theme' hook at priority '10'
 *
 * @package    Unos
 * @subpackage Theme
 */
function test_branding(){
	echo 'partial'; exit; unos_branding();
}
/**
 * Display the branding area
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'unos_branding' ) ):
function unos_branding() {
	?>
	<div <?php hoot_attr( 'branding' ); ?>>
		<div id="site-logo" class="<?php
			echo 'site-logo-' . esc_attr( hoot_get_mod( 'logo' ) );
			if ( hoot_get_mod('logo_background_type') == 'accent' )
				echo ' accent-typo with-background';
			elseif ( hoot_get_mod('logo_background_type') == 'background' )
				echo ' with-background';
			?>">
			<?php
			// Display the Image Logo or Site Title
			unos_logo();
			?>
		</div>
	</div><!-- #branding -->
	<?php
}
endif;

/**
 * Displays the logo
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'unos_logo' ) ):
function unos_logo() {

	$display = '';
	$unos_logo = hoot_get_mod( 'logo' );

	if ( ( is_front_page() ) ) {
		$tag_h1 = 'h1';
		$tag_h2 = 'h2';
	} else {
		$tag_h1 = $tag_h2 = 'div';
	}

	if ( 'text' == $unos_logo || 'custom' == $unos_logo ) {
		$display .= unos_get_text_logo( $unos_logo, $tag_h1, $tag_h2 );
	} elseif ( 'mixed' == $unos_logo || 'mixedcustom' == $unos_logo ) {
		$display .= unos_get_mixed_logo( $unos_logo, $tag_h1, $tag_h2 );
	} elseif ( 'image' == $unos_logo ) {
		$display .= unos_get_image_logo( $unos_logo, $tag_h1, $tag_h2 );
	}

	echo wp_kses( apply_filters( 'unos_logo', $display, $unos_logo, $tag_h1, $tag_h2 ), hoot_data( 'hootallowedtags' ) );
}
endif;

/**
 * Return the text logo
 *
 * @since 1.0
 * @access public
 * @param string $unos_logo text|custom
 * @param string $tag_h1
 * @param string $tag_h2
 * @return void
 */
if ( !function_exists( 'unos_get_text_logo' ) ):
function unos_get_text_logo( $unos_logo, $tag_h1 = 'div', $tag_h2 = 'div' ) {
	$display = '';
	$title_icon = hoot_sanitize_fa( hoot_get_mod( 'site_title_icon', NULL ) );

	$class = $id = 'site-logo-' . esc_attr( $unos_logo );
	$class .= ( $title_icon ) ? ' site-logo-with-icon' : '';
	$class .= ( 'text' == $unos_logo && !function_exists( 'unos_premium' ) ) ? ' site-logo-text-' . hoot_get_mod( 'logo_size' ) : '';

	// Start Logo
	$display .= '<div id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '">';

		// Site Title with Icon
		$display .= "<{$tag_h1} " . hoot_get_attr( 'site-title' ) . '>';
			$display .= '<a href="' . esc_url( home_url() ) . '" rel="home" itemprop="url">';
				$display .= ( $title_icon ) ? '<i class="' . $title_icon . '"></i>' : '';
				$title = ( 'custom' == $unos_logo ) ? unos_get_custom_text_logo() : get_bloginfo( 'name' );
				$display .= apply_filters( 'unos_site_title', $title );
			$display .= "</a>";
		$display .= "</{$tag_h1}>";

		// Site Description
		if ( hoot_get_mod( 'show_tagline' ) && $desc = get_bloginfo( 'description' ) ) {
			$display .= "<{$tag_h2} " . hoot_get_attr( 'site-description' ) . '>';
				$display .= $desc;
			$display .= "</{$tag_h2}>";
		}

	$display .= '</div>';

	return apply_filters( 'unos_get_text_logo', $display, $unos_logo, $tag_h1, $tag_h2 );
}
endif;

/**
 * Return the mixed logo
 *
 * @since 1.0
 * @access public
 * @param string $unos_logo mixed|mixedcustom
 * @param string $tag_h1
 * @param string $tag_h2
 * @return void
 */
if ( !function_exists( 'unos_get_mixed_logo' ) ):
function unos_get_mixed_logo( $unos_logo, $tag_h1 = 'div', $tag_h2 = 'div' ) {
	$display = '';
	$has_logo = ( function_exists( 'get_custom_logo' ) ) ? has_custom_logo() : hoot_get_mod( 'logo_image' );

	$class = $id = 'site-logo-' . esc_attr( $unos_logo );
	$class .= ( !empty( $has_logo ) ) ? ' site-logo-with-image' : '';
	$class .= ( 'mixed' == $unos_logo && !function_exists( 'unos_premium' ) ) ? ' site-logo-text-' . hoot_get_mod( 'logo_size' ) : '';

	// Start Logo
	$display .= '<div id="' . esc_attr( $id ) . '" class="' . esc_attr( $class ) . '">';

		// Logo Image
		if ( $has_logo ) {
			$display .= '<div class="site-logo-mixed-image">';
				$logo_image = ( function_exists( 'get_custom_logo' ) ) ?
								get_custom_logo() :
								'<a href="' . esc_url( home_url() ) . '" rel="home" itemprop="url">' .
									'<img src="' . esc_url( hoot_get_mod( 'logo_image' ) ) . '" />' .
								'</a>';
				$display .= $logo_image;
			$display .= '</div>';
		}

		$display .= '<div class="site-logo-mixed-text">';

			// Site Title (No Icon)
			$display .= "<{$tag_h1} " . hoot_get_attr( 'site-title' ) . '>';
				$display .= '<a href="' . esc_url( home_url() ) . '" rel="home" itemprop="url">';
					$title = ( 'mixedcustom' == $unos_logo ) ? unos_get_custom_text_logo() : get_bloginfo( 'name' );
					$display .= apply_filters( 'unos_site_title', $title );
				$display .= "</a>";
			$display .= "</{$tag_h1}>";

			// Site Description
			if ( hoot_get_mod( 'show_tagline' ) && $desc = get_bloginfo( 'description' ) ) {
				$display .= "<{$tag_h2} " . hoot_get_attr( 'site-description' ) . '>';
					$display .= $desc;
				$display .= "</{$tag_h2}>";
			}

		$display .= '</div>';

	$display .= '</div>';

	return apply_filters( 'unos_get_mixed_logo', $display, $unos_logo, $tag_h1, $tag_h2 );
}
endif;

/**
 * Return the image logo
 *
 * @since 1.0
 * @access public
 * @param string $unos_logo
 * @param string $tag_h1
 * @param string $tag_h2
 * @return void
 */
if ( !function_exists( 'unos_get_image_logo' ) ):
function unos_get_image_logo( $unos_logo = 'image', $tag_h1 = 'div', $tag_h2 = 'div' ) {
	$display = '';
	$has_logo = ( function_exists( 'get_custom_logo' ) ) ? has_custom_logo() : hoot_get_mod( 'logo_image' );

	if ( !empty( $has_logo ) ) {
		$display .= '<div id="site-logo-image" class="site-logo-image">';

			// Logo Image
			$display .= "<{$tag_h1} " . hoot_get_attr( 'site-title' ) . '>';
				$logo_image = ( function_exists( 'get_custom_logo' ) ) ?
								get_custom_logo() :
								'<a href="' . esc_url( home_url() ) . '" rel="home" itemprop="url">' .
									'<img src="' . esc_url( hoot_get_mod( 'logo_image' ) ) . '" />' .
								'</a>';
				$display .= $logo_image;
			$display .= "</{$tag_h1}>";

			// Site Description
			if ( hoot_get_mod( 'show_tagline' ) && $desc = get_bloginfo( 'description' ) ) {
				$display .= "<{$tag_h2} " . hoot_get_attr( 'site-description' ) . '>';
					$display .= $desc;
				$display .= "</{$tag_h2}>";
			}

		$display .= '</div>';
	}

	return apply_filters( 'unos_get_image_logo', $display, $unos_logo, $tag_h1, $tag_h2 );
}
endif;

/**
 * Returns the custom text logo
 *
 * @since 1.0
 * @access public
 * @return string
 */
if ( !function_exists( 'unos_get_custom_text_logo' ) ):
function unos_get_custom_text_logo() {
	$title = '';
	$logo_custom = apply_filters( 'unos_logo_custom_text', hoot_sortlist( hoot_get_mod( 'logo_custom' ) ) );

	if ( is_array( $logo_custom ) && !empty( $logo_custom ) ) {
		$lcount = 1;
		foreach ( $logo_custom as $logo_custom_line ) {
			if ( !$logo_custom_line['sortitem_hide'] && !empty( $logo_custom_line['text'] ) ) {
				$line_class = 'site-title-line site-title-line' . $lcount;
				$line_class .= ( $logo_custom_line['font'] == 'standard' ) ? ' site-title-body-font' : '';
				$line_class .= ( $logo_custom_line['font'] == 'heading2' ) ? ' site-title-heading-font' : '';
				$title .= '<span class="' . $line_class . '">' . wp_kses_decode_entities( $logo_custom_line['text'] ) . '</span>';
			}
			$lcount++;
		}

	}
	return apply_filters( 'unos_get_custom_text_logo', $title, $logo_custom );
}
endif;

/**
 * Display the primary menu area
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'unos_header_aside' ) ):
function unos_header_aside() {
	$area = esc_attr( hoot_get_mod( 'primary_menuarea' ) );
	if ( $area == 'none' )
		return;

	$class = ( $area == 'menu' ) ? 'header-aside-menu-' . hoot_get_mod( 'mobile_menu' ) : '';
	$class .= ( $area == 'search' ) ? ' js-search' : '';
	?><div <?php hoot_attr( 'header-aside', '', "header-aside-{$area} {$class}" ); ?>><?php

		if ( $area == 'menu' ):
			?><div class="menu-area-wrap"><?php
				// Loads the template-parts/menu-primary.php template.
				hoot_get_menu( 'primary' );
				// Loads the template-parts/sidebar-menu-side.php template.
				hoot_get_sidebar( 'menu-side' );
			?></div><?php
		endif;

		if ( $area == 'custom' ):
			echo wp_kses_post( hoot_get_mod( 'primary_menuarea_custom' ) );
		endif;

		if ( $area == 'search' ):
			get_search_form();
		endif;

		if ( $area == 'widget-area' ):
			hoot_get_sidebar( 'header' );
		endif;

	?></div><?php

}
endif;

/**
 * Display the secondary menu
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'unos_secondary_menu' ) ):
function unos_secondary_menu( $location ) {
	$menu_location = hoot_get_mod( 'secondary_menu_location' );
	if ( $location == $menu_location ) {
		?>
		<div <?php hoot_attr( 'header-part', 'supplementary' ); ?>>
			<div class="hgrid">
				<div class="hgrid-span-12">
					<div class="menu-area-wrap">
						<?php
						// Loads the template-parts/menu-secondary.php template.
						hoot_get_menu( 'secondary' );
						// Loads the template-parts/sidebar-menu-side.php template.
						hoot_get_sidebar( 'menu-side' );
						?>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
endif;

/**
 * Get the top level menu items array
 * @NU (@U mag hoot)
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unos_nav_menu_toplevel_items( $theme_location = 'hoot-primary-menu' ) {
	static $location_items;
	if ( !isset( $location_items[$theme_location] ) && ($theme_locations = get_nav_menu_locations()) && isset( $theme_locations[$theme_location] ) ) {
		$menu_obj = get_term( $theme_locations[$theme_location], 'nav_menu' );
		if ( !empty( $menu_obj->term_id ) ) {
			$menu_items = wp_get_nav_menu_items($menu_obj->term_id);
			if ( $menu_items )
				foreach( $menu_items as $menu_item )
					if ( empty( $menu_item->menu_item_parent ) )
						$location_items[$theme_location][] = $menu_item;
		}
	}
	if ( !empty( $location_items[$theme_location] ) )
		return $location_items[$theme_location];
	else
		return array();
}

/**
 * Display Menu Nav Item Description
 *
 * @since 1.0
 * @param string   $title The menu item's title.
 * @param WP_Post  $item  The current menu item.
 * @param stdClass $args  An object of wp_nav_menu() arguments.
 * @param int      $depth Depth of menu item. Used for padding.
 * @return string
 */
if ( !function_exists( 'unos_menu_description' ) ):
function unos_menu_description( $title, $item, $args, $depth ) {

	$return = '';
	$return .= '<span class="menu-title">' . $title . '</span>';
	if ( !empty( $item->description ) )
		$return .= '<span class="menu-description enforce-body-font">' . $item->description . '</span>';

	return $return;
}
endif;
add_filter( 'nav_menu_item_title', 'unos_menu_description', 5, 4 );

/**
 * Display title area content
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'unos_add_custom_title_content' ) ):
function unos_add_custom_title_content( $location = 'pre', $context = '' ) {

	$pre_title_content_post = apply_filters( 'unos_pre_title_content_post', '', $location, $context );
	if ( ( $location == 'pre' && !$pre_title_content_post ) ||
		 ( $location == 'post' && $pre_title_content_post ) ) : 

		$pre_title_content = apply_filters( 'unos_pre_title_content', '', $location, $context );
		if ( !empty( $pre_title_content ) ) :

			$pre_title_content_stretch =  apply_filters( 'unos_pre_title_content_stretch', '', $location, $context ); ?>
			<div id="custom-content-title-area" class="<?php
				echo sanitize_html_class( $location . '-content-title-area ' );
				echo ( ($pre_title_content_stretch) ? 'content-title-area-stretch' : 'content-title-area-grid' );
				?>">
				<div class="<?php echo ( ($pre_title_content_stretch) ? 'hgrid-stretch' : 'hgrid' ); ?>">
					<div class="hgrid-span-12">
						<?php echo wp_kses_post( do_shortcode( $pre_title_content ) ); ?>
					</div>
				</div>
			</div>
			<?php

		endif;

	endif;
}
endif;

/**
 * Display 404 content
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'unos_display_404_content' ) ):
function unos_display_404_content() {
	echo esc_html( __( 'Apologies, but no entries were found.', 'unos' ) );
}
endif;
add_action( 'unos_404_content', 'unos_display_404_content', 5 );

/**
 * Utility function to map footer sidebars structure to CSS span architecture.
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'unos_footer_structure' ) ):
function unos_footer_structure() {
	$footers = hoot_get_mod( 'footer' );
	$structure = array(
				'1-1' => array( 12, 12, 12, 12 ),
				'2-1' => array(  6,  6, 12, 12 ),
				'2-2' => array(  4,  8, 12, 12 ),
				'2-3' => array(  8,  4, 12, 12 ),
				'3-1' => array(  4,  4,  4, 12 ),
				'3-2' => array(  6,  3,  3, 12 ),
				'3-3' => array(  3,  6,  3, 12 ),
				'3-4' => array(  3,  3,  6, 12 ),
				'4-1' => array(  3,  3,  3,  3 ),
				);
	if ( isset( $structure[ $footers ] ) )
		return $structure[ $footers ];
	else
		return array( 12, 12, 12, 12 );
}
endif;

/**
 * Get footer column option.
 *
 * @since 1.0
 * @access public
 * @return int
 */
function unos_get_footer_columns() {
	$footers = hoot_get_mod( 'footer' );
	$columns = ( $footers ) ? intval( substr( $footers, 0, 1 ) ) : false;
	$columns = ( is_numeric( $columns ) && 0 < $columns ) ? $columns : false;
	return $columns;
}

/**
 * Utility function to map 2 column widths to CSS span architecture.
 *
 * @since 1.0
 * @access public
 * @return void
 */
if ( !function_exists( 'unos_get_column_span' ) ):
function unos_get_column_span( $col_width ) {
	$return = array();
	switch( $col_width ):
		case '100':
			$return[0] = 'hgrid-span-12';
			break;
		case '50-50': default:
			$return[0] = 'hgrid-span-6';
			$return[1] = 'hgrid-span-6';
			break;
		case '33-66':
			$return[0] = 'hgrid-span-4';
			$return[1] = 'hgrid-span-8';
			break;
		case '66-33':
			$return[0] = 'hgrid-span-8';
			$return[1] = 'hgrid-span-4';
			break;
		case '25-75':
			$return[0] = 'hgrid-span-3';
			$return[1] = 'hgrid-span-9';
			break;
		case '75-25':
			$return[0] = 'hgrid-span-9';
			$return[1] = 'hgrid-span-3';
			break;
		case '33-33-33':
			$return[0] = 'hgrid-span-4';
			$return[1] = 'hgrid-span-4';
			$return[2] = 'hgrid-span-4';
			break;
		case '25-25-50':
			$return[0] = 'hgrid-span-3';
			$return[1] = 'hgrid-span-3';
			$return[2] = 'hgrid-span-6';
			break;
		case '25-50-25':
			$return[0] = 'hgrid-span-3';
			$return[1] = 'hgrid-span-6';
			$return[2] = 'hgrid-span-3';
			break;
		case '50-25-25':
			$return[0] = 'hgrid-span-6';
			$return[1] = 'hgrid-span-3';
			$return[2] = 'hgrid-span-3';
			break;
		case '25-25-25-25':
			$return[0] = 'hgrid-span-3';
			$return[1] = 'hgrid-span-3';
			$return[2] = 'hgrid-span-3';
			$return[3] = 'hgrid-span-3';
			break;
	endswitch;
	return $return;
}
endif;

/**
 * Wrapper function for unos_layout() to get the class names for current context.
 * Can only be used after 'posts_selection' action hook i.e. in 'wp' hook or later.
 *
 * @since 1.0
 * @access public
 * @param string $context content|primary-sidebar|sidebar|sidebar-primary
 * @return string
 */
if ( !function_exists( 'unos_layout_class' ) ):
function unos_layout_class( $context ) {
	return unos_layout( $context, 'class' );
}
endif;

/**
 * Utility function to return layout size or classes for the context.
 * Can only be used after 'posts_selection' action hook i.e. in 'wp' hook or later.
 *
 * @since 1.0
 * @access public
 * @param string $context content|primary-sidebar|sidebar|sidebar-primary
 * @param string $return  class|size return class name or just the span size integer
 * @return string
 */
if ( !function_exists( 'unos_layout' ) ):
function unos_layout( $context, $return = 'size' ) {

	// Set layout if not already set
	$layout = hoot_data( 'currentlayout' );
	if ( empty( $layout ) )
		unos_set_layout();

	// Get layout
	$layout = hoot_data( 'currentlayout' );
	$span_sidebar = $layout['sidebar'];
	$span_content = $layout['content'];
	$layout_class = ' layout-' . $layout['layout'];

	// Return Class or Span Size for the Content/Sidebar
	if ( $context == 'content' ) {

		if ( $return == 'class' ) {
			$extra_class = ( empty( $span_sidebar ) ) ? ' no-sidebar' : ' has-sidebar';
			return ' hgrid-span-' . $span_content . $extra_class . $layout_class . ' ';
		} elseif ( $return == 'size' ) {
			return intval( $span_content );
		}

	} elseif ( $context == 'sidebar' ||  $context == 'sidebar-primary' || $context == 'primary-sidebar' || $context == 'secondary-sidebar' || $context == 'sidebar-secondary' ) {

		if ( $return == 'class' ) {
			if ( !empty( $span_sidebar ) )
				return ' hgrid-span-' . $span_sidebar . $layout_class . ' ';
			else
				return '';
		} elseif ( $return == 'size' ) {
			return intval( $span_sidebar );
		}

	}

	return '';

}
endif;

/**
 * Utility function to calculate and set main (content+aside) layout according to the sidebar layout
 * set by user for the current view.
 * Can only be used after 'posts_selection' action hook i.e. in 'wp' hook or later.
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'unos_set_layout' ) ):
function unos_set_layout() {

	// Apply full width for front page
	if ( is_front_page() && !is_home() ) {
		$sidebar = 'full-width';
	}
	// Apply Sidebar Layout for Posts
	elseif ( is_singular( 'post' ) ) {
		$sidebar = hoot_get_mod( 'sidebar_posts' );
	}
	// Check for attachment before page (to handle images attached to a page - true for is_page and is_attachment)
	// Apply 'Full Width'
	elseif ( is_attachment() ) {
		$sidebar = 'none';
	}
	// Apply Sidebar Layout for Pages
	elseif ( is_page() ) {
		$sidebar = hoot_get_mod( 'sidebar_pages' );
	}
	// Apply No Sidebar Layout for 404
	elseif ( is_404() ) {
		$sidebar = 'none';
	}
	// Apply Sidebar Layout for Site
	else {
		$sidebar = hoot_get_mod( 'sidebar' );
	}

	// Allow for custom manipulation of the layout by child themes
	$sidebar = esc_attr( apply_filters( 'unos_layout', $sidebar ) );

	// Save the layout for current view
	unos_set_layout_span( $sidebar );

}
endif;

/**
 * Utility function to calculate and set main (content+aside) layout according to the sidebar layout
 * set by user for the current view.
 * Can only be used after 'posts_selection' action hook i.e. in 'wp' hook or later.
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'unos_set_layout_span' ) ):
function unos_set_layout_span( $sidebar ) {
	$spans = apply_filters( 'unos_layout_spans', array(
		'none' => array(
			'content' => 9,
			'sidebar' => 0,
		),
		'full' => array(
			'content' => 12,
			'sidebar' => 0,
		),
		'full-width' => array(
			'content' => 12,
			'sidebar' => 0,
		),
		'narrow-right' => array(
			'content' => 9,
			'sidebar' => 3,
		),
		'wide-right' => array(
			'content' => 8,
			'sidebar' => 4,
		),
		'narrow-left' => array(
			'content' => 9,
			'sidebar' => 3,
		),
		'wide-left' => array(
			'content' => 8,
			'sidebar' => 4,
		),
		'narrow-left-left' => array(
			'content' => 6,
			'sidebar' => 3,
		),
		'narrow-left-right' => array(
			'content' => 6,
			'sidebar' => 3,
		),
		'narrow-right-left' => array(
			'content' => 6,
			'sidebar' => 3,
		),
		'narrow-right-right' => array(
			'content' => 6,
			'sidebar' => 3,
		),
		'default' => array(
			'content' => 8,
			'sidebar' => 4,
		),
	) );

	/* Set the layout for current view */
	$currentlayout['layout'] = $sidebar;
	if ( isset( $spans[ $sidebar ] ) ) {
		$currentlayout['content'] = $spans[ $sidebar ]['content'];
		$currentlayout['sidebar'] = $spans[ $sidebar ]['sidebar'];
	} else {
		$currentlayout['content'] = $spans['default']['content'];
		$currentlayout['sidebar'] = $spans['default']['sidebar'];
	}
	hoot_set_data( 'currentlayout', $currentlayout );

}
endif;

/**
 * Filter default content size for calculating image thumbnail size
 *
 * @since 2.7
 * @access public
 */
if ( !function_exists( 'unos_thumbnail_size_contentwidth' ) ):
function unos_thumbnail_size_contentwidth() {
	return 'span-' . unos_layout( 'content' );
}
endif;
add_filter( 'hoot_thumbnail_size_contentwidth', 'unos_thumbnail_size_contentwidth' );

/**
 * Utility function to determine the location of page header
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'unos_titlearea_top' ) ):
function unos_titlearea_top() {

	$full = array_map( 'trim', explode( ',', hoot_get_mod( 'page_header_full' ) ) );

	/* Override For Full Width Pages (including 404 page) */
	if ( in_array( 'no-sidebar', $full ) ) {
		$sidebar_size = unos_layout( 'primary-sidebar' );
		if ( empty( $sidebar_size ) )
			return apply_filters( 'unos_titlearea_top', true, 'no-sidebar', $full );
	}

	/* For Posts */
	if ( is_singular( 'post' ) ) {
		if ( in_array( 'posts', $full ) )
			return apply_filters( 'unos_titlearea_top', true, 'posts', $full );
		else
			return apply_filters( 'unos_titlearea_top', false, 'posts', $full );
	}

	/* For Pages */
	if ( is_page() ) {
		if ( in_array( 'pages', $full ) )
			return apply_filters( 'unos_titlearea_top', true, 'pages', $full );
		else
			return apply_filters( 'unos_titlearea_top', false, 'pages', $full );
	}

	/* Default */
	if ( in_array( 'default', $full ) )
		return apply_filters( 'unos_titlearea_top', true, 'default', $full );
	else
		return apply_filters( 'unos_titlearea_top', false, 'default', $full );

}
endif;

/**
 * Utility function to display featured image in loop meta header
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'unos_loopmeta_header_img' ) ):
function unos_loopmeta_header_img( $context, $display ) {
	$context = sanitize_html_class( $context );
	$location = ( $context == 'post' ) ? hoot_get_mod( 'post_featured_image' ) : hoot_get_mod( 'post_featured_image_page' );

	$view_id = $img_id = 0;

	if ( is_singular() ) {
		$view_id = null;
	} elseif ( is_home() && !is_front_page() ) {
		$view_id = get_option( 'page_for_posts' );
	} elseif ( current_theme_supports( 'woocommerce' ) ) {
		if ( is_shop() ) {
			$view_id = get_option( 'woocommerce_shop_page_id' );
		} elseif ( is_product_category() ) {
			global $wp_query;
			$cat = $wp_query->get_queried_object();
			$img_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
		}
	}

	$img_id = ( $view_id !== 0 && has_post_thumbnail( $view_id ) ) ? get_post_thumbnail_id( $view_id ) : $img_id;
	$img_id = apply_filters( 'unos_loopmeta_header_img_id', $img_id, $context, $location, $view_id );
	$img_id = absint( $img_id );

	if ( $location == 'header' && !empty( $img_id ) ) {
		$img_src = wp_get_attachment_image_src( $img_id, apply_filters( "unos_{$context}_imgsize", 'full', 'header' ) );
		if ( !empty( $img_src[0] ) ) {
			$wrap_attr = array(
				'data-parallax' => 'scroll',
				'data-image-src' => esc_url( $img_src[0] ),
			);
			if ( $display )
				echo '<div ' . hoot_get_attr( 'entry-featured-img-headerwrap', '', $wrap_attr ) . '></div>';
			else
				hoot_set_data( 'loop-meta-wrap', $wrap_attr );
		}
	}
}
endif;

/**
 * Display function to render posts for Jetpack's infinite scroll module
 *
 * @since 1.0
 * @access public
 */
if ( !function_exists( 'unos_jetpack_infinitescroll_render' ) ):
function unos_jetpack_infinitescroll_render(){
	while ( have_posts() ) : the_post();
		// Loads the template-parts/content-{$post_type}.php template.
		hoot_get_content_template();
	endwhile;
}
endif;