<?php
/**
 * About/Welcome page
 *
 * @package    Unos
 * @subpackage Theme
 */

/**
 * Sets up the Appearance Subpage
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unos_add_appearance_subpage() {

	add_theme_page(
		__( 'Unos', 'unos' ), // Page Title
		__( 'Unos Theme', 'unos' ), // Menu Title
		'edit_theme_options', // capability
		'unos-welcome', // menu-slug
		'unos_appearance_subpage' // function name
		);

	add_action( 'admin_enqueue_scripts', 'unos_admin_enqueue_about_styles' );

}
/* Add the admin setup function to the 'admin_menu' hook. */
add_action( 'admin_menu', 'unos_add_appearance_subpage' );

/**
 * Add a welcome notice if not already dismissed
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unos_welcome_notice(){
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'unos-welcome' )
		return;
	if ( ! get_option( 'unos-dismiss-welcome' ) ) {
		add_action( 'admin_notices', 'unos_add_welcome_notice' );
	}
}
add_action( 'admin_head', 'unos_welcome_notice' );

/**
 * Display admin notice
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unos_add_welcome_notice() {
	?>
	<div id="hoot-welcome-msg" class="notice notice-success is-dismissible">
		<p><?php
			/* Translators: 1 is the link start markup, 2 is link markup end */
			printf( esc_html__( 'Thank you for choosing Unos! To get started and fully take advantage of our theme, please make sure you visit the welcome page for the %1$sQuick Start Guide%2$s.', 'unos' ), '<a href="' . esc_url( admin_url( 'themes.php?page=unos-welcome&tab=qstart' ) ) . '">', '</a>' );
			?></p>
		<p><?php
			/* Translators: 1 is the link start markup, 2 is link markup end */
			printf( esc_html__( '%1$sGet started with Unos%2$s', 'unos' ), '<a class="button-secondary" href="' . esc_url( admin_url( 'themes.php?page=unos-welcome' ) ) . '">', '</a>' );
			?></p>
	</div>
	<?php
}

/**
 * Ajax callback to set dismissable notice
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unos_dismiss_welcome_notice() {
	check_ajax_referer( 'dismiss-hoottheme-welcome', 'nonce' );
	update_option( 'unos-dismiss-welcome', 1 ); // echo 'dismiss test';
	wp_die();
}
add_action( 'wp_ajax_unos_dismiss_welcome_notice', 'unos_dismiss_welcome_notice' );

/**
 * Enqueue CSS
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unos_admin_enqueue_about_styles( $hook ) {
	if ( $hook == 'appearance_page_unos-welcome' )
		wp_enqueue_style( 'hoot-admin-about', hoot_data()->incuri . 'admin/css/about.css', array(),  hoot_data()->hoot_version );
	wp_enqueue_script( 'hoot-admin-about', hoot_data()->incuri . 'admin/js/about.js', array( 'jquery' ),  hoot_data()->hoot_version, true );
	wp_localize_script( 'hoot-admin-about', 'hoot_dismissible_notice', array(
							'ajax_url' => esc_url( admin_url( 'admin-ajax.php' ) ),
							'nonce' => wp_create_nonce( 'dismiss-hoottheme-welcome' ),
						) );
}

/**
 * Display the Appearance Subpage
 *
 * @since 1.0
 * @access public
 * @return void
 */
function unos_appearance_subpage() {
	$activetab = 'upsell';
	if ( !empty( $_GET['tab'] ) )
		$activetab = ( $_GET['tab'] == 'import' ) ? 'import' : ( ( $_GET['tab'] == 'qstart' ) ? 'qstart' : $activetab );
	?>
	<div class="wrap">

		<h1 class="hoot-about-title"><?php printf( esc_html__( 'About %1$s %2$s', 'unos' ),  hoot_data( 'template_name' ), hoot_data( 'template_version' ) ) ?></h1>

		<div id="hoot-about-sub" class="hoot-about-sub">
			<div class="hoot-about-ss"><img src="<?php echo esc_url( hoot_data()->template_uri . 'screenshot.jpg' )  ?>"></div>
			<div class="hoot-about-text">
				<p class="hoot-about-intro"><?php esc_html_e( 'Unos is a multipurpose highly flexible WordPress theme built on a SEO friendly framework with fast loading speed. It comes with a lot of Customizer options, multiple blog layouts, WooCommerce support etc among many other features.', 'unos' ) ?></p>
				<p class="hoot-about-textlinks">
					<a class="button button-primary" href="https://wphoot.com/themes/unos/" target="_blank"><span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'View Premium', 'unos' ) ?></a>
					<a class="button" href="https://demo.wphoot.com/unos/" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e( 'View Demo', 'unos' ) ?></a>
					<a class="button" href="https://wphoot.com/support/unos/" target="_blank"><span class="dashicons dashicons-editor-aligncenter"></span> <?php esc_html_e( 'Documentation', 'unos' ) ?></a>
					<a class="button" href="https://wphoot.com/support/" target="_blank"><span class="dashicons dashicons-sos"></span> <?php esc_html_e( 'Get Support', 'unos' ) ?></a>
					<a class="button" href="https://wordpress.org/support/theme/unos/reviews/?filter=5#new-post" target="_blank"><span class="dashicons dashicons-thumbs-up"></span> <?php esc_html_e( 'Rate Us', 'unos' ) ?></a>
				</p>
				<?php if ( apply_filters( 'unos_support_ocdi', true ) ) : ?>
				<div class="hoot-about-notice2">
					<h3><?php esc_html_e( '1 Click Demo Installation', 'unos' ) ?></h3>
					<p><?php
						/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
						printf( esc_html__( 'Use the HootKit plugin to install demo content with a single click to make your site look exactly like the %1$sDemo Site%2$s.%3$sNew users often find it easier to edit existing content rather than starting from scratch.', 'unos' ), '<a href="https://demo.wphoot.com/unos/" target="_blank">', '</a>', '<br />' );
					?></p>
					<p class="hoot-about-textlinks">
						<?php if ( class_exists( 'HootKit' ) ) {
							$ocdilink = admin_url( 'themes.php?page=hootkit-content-install' );
							echo '<a class="button" href="' . esc_url( $ocdilink ) . '"><span class="dashicons dashicons-art"></span> ' . esc_html__( 'Install Demo Content', 'unos' ) . '</a>';
						} else {
							echo '<a class="button linkto-tab" data-tabid="import" href="#hoot-import"><span class="dashicons dashicons-art"></span> ' . esc_html__( 'Install Demo Content', 'unos' ) . '</a>';
						} ?>
					</p>
				</div>
				<?php endif; ?>
			</div>
			<div class="clear"></div>
		</div><!-- .hoot-about-sub -->

		<div class="hoot-abouttabs">

			<h2 id="nav-tabs" class="nav-tab-wrapper">
				<span class="nav-tab nav-upsell <?php if ( $activetab == 'upsell' ) echo 'nav-tab-active'; ?>" data-tabid="upsell"><?php esc_html_e( 'Premium Options', 'unos' ) ?></span>
				<?php if ( apply_filters( 'unos_support_ocdi', true ) ) : ?>
				<span class="nav-tab nav-import <?php if ( $activetab == 'import' ) echo 'nav-tab-active'; ?>" data-tabid="import"><?php esc_html_e( '1 Click Installation', 'unos' ) ?></span>
				<?php endif; ?>
				<span class="nav-tab nav-qstart <?php if ( $activetab == 'qstart' ) echo 'nav-tab-active'; ?>" data-tabid="qstart"><?php esc_html_e( 'Quick Start Guide', 'unos' ) ?></span>
			</h2>

			<div id="hoot-upsell" class="hoot-upsell hoot-tab <?php if ( $activetab == 'upsell' ) echo 'hoot-tab-active'; ?>">
				<h2 class="centered allcaps"><?php
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					printf( esc_html__( 'Upgrade to %1$sUnos %2$sPremium%3$s%4$s', 'unos' ), '<span>', '<strong>', '</strong>', '</span>' );
					?></h2>
				<p class="hoot-tab-intro centered"><?php
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					printf( esc_html__( 'If you have enjoyed using Unos, you are going to love %1$sUnos Premium%2$s.%3$sIt is a robust upgrade to Unos that gives you many useful features.', 'unos' ), '<strong>', '</strong>', '<br />' );
					?></p>
				<p class="hoot-tab-cta centered">
					<a class="button button-secondary secondary-cta" href="https://demo.wphoot.com/unos/" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e( 'View Demo Site', 'unos' ) ?></a>
					<a class="button button-primary primary-cta" href="https://wphoot.com/themes/unos/" target="_blank"><span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'Buy Unos Premium', 'unos' ) ?></a>
				</p>
				<div class="hoot-tab-sub"><div class="hoot-tab-subinner">
					<?php unos_tabsections( 'features' ); ?>
					<div class="tabsection hoot-tab-cta centered">
						<a class="button button-secondary secondary-cta" href="https://demo.wphoot.com/unos/" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e( 'View Demo Site', 'unos' ) ?></a>
						<a class="button button-primary primary-cta" href="https://wphoot.com/themes/unos/" target="_blank"><span class="dashicons dashicons-dashboard"></span> <?php esc_html_e( 'Buy Unos Premium', 'unos' ) ?></a>
					</div>
				</div></div><!-- .hoot-tab-sub -->
			</div><!-- #hoot-upsell -->

			<?php if ( apply_filters( 'unos_support_ocdi', true ) ) : ?>
			<div id="hoot-import" class="hoot-import hoot-tab <?php if ( $activetab == 'import' ) echo 'hoot-tab-active'; ?>">
				<h2 class="centered allcaps"><span class="dashicons dashicons-upload"></span><?php
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					printf( esc_html__( '%1$s1 Click%2$s Installation', 'unos' ), '<strong>', '</strong>' );
					?></h2>
				<p class="hoot-tab-intro centered"><?php
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					printf( esc_html__( 'Get your site up and running in 1 click.%1$sMake your site look like the %4$sDemo Site%5$s and use it as a starting point.%1$s%2$s(New users often find it easier to edit existing content instead of starting from scratch)%3$s', 'unos' ), '<br />', '<em>', '</em>', '<a href="https://demo.wphoot.com/unos/" target="_blank">', '</a>' );
					?></p>
				<p class="hoot-tab-cta centered">
					<a class="button button-secondary secondary-cta" href="https://demo.wphoot.com/unos/" target="_blank"><span class="dashicons dashicons-welcome-view-site"></span> <?php esc_html_e( 'View Demo Site', 'unos' ) ?></a>
					<?php if ( class_exists( 'HootKit' ) ) : $ocdilink = admin_url( 'themes.php?page=hootkit-content-install' ); ?>
						<a class="button button-primary primary-cta" href="<?php echo esc_url( $ocdilink ) ?>"><span class="dashicons dashicons-art"></span> <?php esc_html_e( 'Install Demo Content', 'unos' ) ?></a>
					<?php else : $tgmplink = admin_url( 'themes.php?page=tgmpa-install-plugins' ); ?>
						<a class="button button-primary primary-cta" href="<?php echo esc_url( $tgmplink ) ?>"><span class="dashicons dashicons-admin-plugins"></span> <?php esc_html_e( 'Install HootKit plugin', 'unos' ) ?></a>
					<?php endif; ?>
				</p>
				<div class="hoot-tab-sub"><div class="hoot-tab-subinner">
					<?php unos_tabsections( 'importsteps' ); ?>
				</div></div><!-- .hoot-tab-sub -->
			</div><!-- #hoot-import -->
			<?php endif; ?>

			<div id="hoot-qstart" class="hoot-qstart hoot-tab <?php if ( $activetab == 'qstart' ) echo 'hoot-tab-active'; ?>">
				<h2 class="centered allcaps"><span class="dashicons dashicons-clock"></span> <?php esc_html_e( 'Quick Start Guide', 'unos' ); ?></h2>
				<p class="hoot-tab-intro centered"><?php
					/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
					printf( esc_html__( 'Follow these steps to quickly start developing your site.%1$sTo read the full documentation, or to get support from one of our support ninjas, click the buttons below.', 'unos' ), '<br />' );
					?></p>
				<p class="hoot-tab-cta centered">
					<a class="button button-primary primary-cta" href="https://wphoot.com/support/unos/" target="_blank"><span class="dashicons dashicons-editor-aligncenter"></span> <?php esc_html_e( 'View Full Documentation', 'unos' ) ?></a>
					<a class="button button-secondary secondary-cta" href="https://wphoot.com/support/" target="_blank"><span class="dashicons dashicons-sos"></span> <?php esc_html_e( 'Get Support', 'unos' ) ?></a>
				</p>
				<div class="hoot-tab-sub hoot-qstart-sub"><div class="hoot-tab-subinner">
					<?php unos_tabsections( 'quickstart' ); ?>
					<div class="tabsection hoot-tab-cta centered">
						<a class="button button-primary primary-cta" href="https://wphoot.com/support/unos/" target="_blank"><span class="dashicons dashicons-editor-aligncenter"></span> <?php esc_html_e( 'View Full Documentation', 'unos' ) ?></a>
						<a class="button button-secondary secondary-cta" href="https://wphoot.com/support/" target="_blank"><span class="dashicons dashicons-sos"></span> <?php esc_html_e( 'Get Support', 'unos' ) ?></a>
					</div>
				</div></div><!-- .hoot-tab-sub -->
			</div><!-- #hoot-qstart -->


		</div><!-- .hoot-abouttabs -->
		<a class="hoot-abouttheme-top" href="#hoot-about-sub"><span class="dashicons dashicons-arrow-up-alt"></span></a>
	</div><!-- .wrap -->
	<?php
}

/**
 * About Page displat Tab's content sections
 *
 * @since 1.0
 * @access public
 * @return mixed
 */
function unos_tabsections( $string ) {
	if ( in_array( $string, array( 'features', 'importsteps', 'quickstart' ) ) ) :
		$features = unos_upstrings( $string );
		if ( !empty( $features ) && is_array( $features ) ) :
			foreach ( $features as $key => $feature ) :
				$style = empty( $feature['style'] ) ? 'std' : $feature['style'];
				?>
				<div class="tabsection <?php
					if ( $style == 'hero-top' || $style == 'hero-bottom' ) echo "tabsection-hero tabsection-{$style}";
					elseif ( $style == 'side' ) echo 'tabsection-sideinfo';
					elseif ( $style == 'aside' ) echo 'tabsection-asideinfo';
					else echo "tabsection-std";
					?>">

					<?php if ( $style == 'hero-top' || $style == 'hero-bottom' ) :
						if ( $style == 'hero-top' ) : ?>
							<h4 class="heading"><?php echo $feature['name']; ?></h4>
							<?php if ( !empty( $feature['desc'] ) ) echo '<div class="tabsection-hero-text">' . $feature['desc'] . '</div>'; ?>
						<?php endif; ?>
						<?php if ( !empty( $feature['img'] ) ) : ?>
							<div class="tabsection-hero-img">
								<img src="<?php echo esc_url( $feature['img'] ); ?>" />
							</div>
						<?php endif; ?>
						<?php if ( $style == 'hero-bottom' ) : ?>
							<h4 class="heading"><?php echo $feature['name']; ?></h4>
							<?php if ( !empty( $feature['desc'] ) ) echo '<div class="tabsection-hero-text">' . $feature['desc'] . '</div>'; ?>
						<?php endif; ?>

					<?php elseif ( $style == 'side' ) : ?>
						<div class="tabsection-side-wrap">
							<div class="tabsection-side-img">
								<img src="<?php echo esc_url( $feature['img'] ); ?>" />
							</div>
							<div class="tabsection-side-textblock">
								<?php if ( !empty( $feature['name'] ) ) : ?>
									<h4 class="heading"><?php echo $feature['name']; ?></h4>
								<?php endif; ?>
								<?php if ( !empty( $feature['desc'] ) ) echo '<div class="tabsection-side-text">' . $feature['desc'] . '</div>'; ?>
							</div>
							<div class="clear"></div>
						</div>

					<?php elseif ( $style == 'aside' ) : ?>
						<?php if ( !empty( $feature['blocks'] ) ) : ?>
							<div class="tabsection-aside-wrap">
							<?php foreach ( $feature['blocks'] as $key => $block ) {
								echo '<div class="tabsection-aside-block tabsection-aside-'.($key+1).'">';
									if ( !empty( $block['img'] ) ) : ?>
										<div class="tabsection-aside-img">
											<img src="<?php echo esc_url( $block['img'] ); ?>" />
										</div>
									<?php endif;
									if ( !empty( $block['name'] ) ) : ?>
										<h4 class="heading"><?php echo $block['name']; ?></h4>
									<?php endif;
									if ( !empty( $block['desc'] ) ) echo '<div class="tabsection-aside-text">' . $block['desc'] . '</div>';
								echo '</div>';
							} ?>
							<div class="clear"></div>
							</div>
						<?php endif; ?>

					<?php else : ?>
						<?php if ( $style != 'img-bottom' && !empty( $feature['img'] ) ) : ?>
							<div class="tabsection-std-img attop">
								<img src="<?php echo esc_url( $feature['img'] ); ?>" />
							</div>
						<?php endif; ?>
						<div class="tabsection-std-textblock <?php if ( $style == 'img-bottom' ) echo 'attop'; else echo 'atbottom'; ?>">
							<?php if ( !empty( $feature['name'] ) ) : ?>
								<div class="tabsection-std-heading"><h4 class="heading"><?php echo $feature['name']; ?></h4></div>
							<?php endif; ?>
							<?php if ( !empty( $feature['desc'] ) ) echo '<div class="tabsection-std-text">' . $feature['desc'] . '</div>'; ?>
							<div class="clear"></div>
						</div>
						<?php if ( $style == 'img-bottom' && !empty( $feature['img'] ) ) : ?>
							<div class="tabsection-std-img atbottom">
								<img src="<?php echo esc_url( $feature['img'] ); ?>" />
							</div>
						<?php endif; ?>
					<?php endif; ?>

				</div><!-- .tabsection -->
				<?php
			endforeach;
		endif;
	endif;
}

/**
 * About Page Strings
 *
 * @since 1.0
 * @access public
 * @return mixed
 */
function unos_upstrings( $string ) {

	$features = $importsteps = $quickstart = array();
	$imagepath =  esc_url( hoot_data()->incuri . 'admin/images/' );
	$ocdilink = ( class_exists( 'HootKit' ) ) ? admin_url( 'themes.php?page=hootkit-content-install' ) : '';
	$tgmplink = ( class_exists( 'HootKit' ) ) ? '' : admin_url( 'themes.php?page=tgmpa-install-plugins' );

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Complete %1$sStyle %2$sCustomization%3$s', 'unos' ), '<br />', '<strong>', '</strong>' ),
		'desc' => esc_html__( 'Different looks and styles. Choose from an extensive range of customization features in Unos Premium to setup your own unique front page. Give youe site the personality it deserves.', 'unos' ),
		// 'img' => $imagepath . 'premium-style.jpg',
		'style' => 'hero-top',
		);

	$features[] = array(
		'name' => esc_html__( 'Custom Colors &amp; Backgrounds for Sections', 'unos' ),
		'desc' => esc_html__( 'Unos Premium lets you select custom colors and backgrounds for different sections of your site like header, footer, logo background, menu dropdown, content area, page title area etc.', 'unos' ),
		'img' => $imagepath . 'premium-colors.jpg',
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Fonts and %1$sTypography Control%2$s', 'unos' ), '<span>', '</span>' ),
		'desc' => esc_html__( 'Assign different typography (fonts, text size, font color) to menu, topbar, logo, content headings, sidebar, footer etc.', 'unos' ),
		'img' => $imagepath . 'premium-typography.jpg',
		);

	$features[] = array(
		'name' => esc_html__( '600+ Google Fonts', 'unos' ),
		'desc' => esc_html__( "With the integrated Google Fonts library, you can find the fonts that match your site's personality, and there's over 600 options to choose from.", 'unos' ),
		'img' => $imagepath . 'premium-googlefonts.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Unlimites Sliders, Unlimites Slides', 'unos' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Unos Premium allows you to create unlimited sliders with as many slides as you need using the awesome HootKit plugin.%1$s%2$sAdd as Shortcodes%3$sYou can use these sliders on your Frontpage, or add them anywhere using shortcodes - like in your Posts, Sidebars or Footer.', 'unos' ), '<hr>', '<h4>', '</h4>' ),
		'img' => $imagepath . 'premium-sliders.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Image Carousels', 'unos' ),
		'desc' => esc_html__( 'Add carousel widgets to your post, in your sidebar, on your frontpage or in your footer. A simple drag and drop interface allows you to easily create and manage carousels.', 'unos' ),
		'img' => $imagepath . 'premium-carousels.jpg',
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Floating %1$s%2$s\'Sticky\' Header%3$s &amp; %2$s\'Goto Top\'%3$s button (optional)', 'unos' ), '<br>', '<span>', '</span>' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'The floating header follows the user down your page as they scroll, which means they never have to scroll back up to access your navigation menu, improving user experience.%1$sOr, use the \'Goto Top\' button appears on the screen when users scroll down your page, giving them a quick way to go back to the top of the page.', 'unos' ), '<hr>' ),
		'img' => $imagepath . 'premium-header-top.jpg',
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'One Page %1$sScrolling Website /%2$s %1$sLanding Page%2$s', 'unos' ), '<span>', '</span>' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Make One Page websites with menu items linking to different sections on the page. Watch the scroll animation kick in when a user clicks a menu item to jump to a page section.%1$sCreate different landing pages on your site. Change the menu for each page so that the menu items point to sections of the page being displayed.', 'unos' ), '<hr>' ),
		'img' => $imagepath . 'premium-scroller.jpg',
		'style' => 'side',
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Additional Blog Layouts (including pinterest %1$stype mosaic)%2$s', 'unos' ), '<span>', '</span>' ),
		'desc' => esc_html__( 'Unos Premium gives you the option to display your post archives in different layouts including a mosaic type layout similar to pinterest.', 'unos' ),
		'img' => $imagepath . 'premium-blogstyles.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Custom Widgets', 'unos' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Additional Hootkit Custom widgets crafted and designed specifically for Unos Premium Theme to give you the flexibility of adding stylized content: %1$s Buttons, Carousel Sliders, Carousel Posts Slider, Contact Info, Icon Lists, Notices, Number Blocks, Tabs, Toggles and Vcards among others.', 'unos' ), '<hr>' ),
		'img' => $imagepath . 'premium-widgets.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Menu Icons', 'unos' ),
		'desc' => esc_html__( 'Select from over 900 icons for your main navigation menu links.', 'unos' ),
		'img' => $imagepath . 'premium-menuicons.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Premium Background Patterns (CC0)', 'unos' ),
		'desc' => esc_html__( 'Unos Premium comes with many additional premium background patterns. You can always upload your own background image/pattern to match your site design.', 'unos' ),
		// 'img' => $imagepath . 'premium-backgrounds.jpg',
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Automatic Image Lightbox and %1$sWordPress Gallery%2$s', 'unos' ), '<span>', '</span>' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Automatically open image links on your site with the integrates lightbox in Unos Premium.%1$sAutomatically convert standard WordPress galleries to beautiful lightbox gallery slider.', 'unos' ), '<hr>' ),
		'img' => $imagepath . 'premium-lightbox.jpg',
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Developers %1$slove {LESS}', 'unos' ), '<br />' ),
		'desc' => esc_html__( 'CSS is passe! Developers love the modularity and ease of using LESS, which is why Unos Premium comes with properly organized LESS files for the main stylesheet.', 'unos' ),
		'img' => $imagepath . 'premium-lesscss.jpg',
		);

	$features[] = array(
		'name' => esc_html__( 'Easy Import/Export', 'unos' ),
		'desc' => esc_html__( 'Moving to a new host? Or applying a new child theme? Easily import/export your customizer settings with just a few clicks - right from the backend.', 'unos' ),
		// 'img' => $imagepath . 'premium-import-export.jpg',
		);

	$features[] = array(
		'style' => 'aside',
		'blocks' => array(
			array(
				/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
				'name' => sprintf( esc_html__( 'Custom Javascript &amp; %1$sGoogle Analytics%2$s', 'unos' ), '<span>', '</span>' ),
				'desc' => esc_html__( 'Easily insert any javascript snippet to your header without modifying the code files. This helps in adding scripts for Google Analytics, Adsense or any other custom code.', 'unos' ),
				// 'img' => $imagepath . 'premium-customjs.jpg',
				),
			array(
				/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
				'name' => sprintf( esc_html__( 'Continued %1$sLifetime Updates', 'unos' ), '<br />' ),
				'desc' => esc_html__( 'You will help support the continued development of Unos - ensuring it works with future versions of WordPress for years to come.', 'unos' ),
				// 'img' => $imagepath . 'premium-updates.jpg',
				),
			),
		);

	$features[] = array(
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'name' => sprintf( esc_html__( 'Premium %1$sPriority Support', 'unos' ), '<br />' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Need help setting up Unos? Upgrading to Unos Premium gives you prioritized support. We have a growing support team ready to help you with your questions.%1$sNeed small modifications? If you are not a developer yourself, you can count on our support staff to help you with CSS snippets to get the look you are after. Best of all, your changes will persist across updates.', 'unos' ), '<hr>' ),
		'img' => $imagepath . 'premium-support.jpg',
		// 'style' => 'side',
		);

	if ( class_exists( 'HootKit' ) ) {
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		$idesc = sprintf( esc_html__( '%1$sInstall the HootKit plugin to activate the One Click Demo Install.%2$s%3$sInstalled%4$s', 'unos' ), '<span class="strikeout">', '</span>' ,'<hr><h4 class="okgreen"><span class="dashicons dashicons-yes"></span>', '</h4>' );
	} else {
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		$idesc = sprintf( esc_html__( 'Install the HootKit plugin to activate the One Click Demo Install.%1$sInstall HootKit plugin%2$s', 'unos' ), '<hr><a class="button button-primary" href="' . esc_url( $tgmplink ) . '">', '</a>' );
	}
	$importsteps[] = array(
		'name' => esc_html__( 'Step 1', 'unos' ),
		'desc' => $idesc,
		'style' => 'hero-top',
		);

	$ilink = ( $ocdilink ) ? '<a href="' . esc_url( $ocdilink ) . '">' : '<strong>';
	$ilinkend = ( $ocdilink ) ? '</a>' : '</strong>';
	$importsteps[] = array(
		'name' => esc_html__( 'Step 2', 'unos' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Go to the %1$sInstall Theme Content%2$s page under the %3$sAppearance Menu%4$s', 'unos' ), $ilink, $ilinkend, '<strong>', '</strong>' ),
		'style' => 'hero-top',
		);

	$importsteps[] = array(
		'name' => esc_html__( 'Step 3', 'unos' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Click the %1$sInstall Demo Content%2$s button and wait for the installation process to finish', 'unos' ), '<strong>', '</strong>' ),
		'img' => $imagepath . 'import-clickbutton.png',
		'style' => 'hero-top',
		);

	$settinglink = admin_url( 'options-reading.php' );
	$addpagelink = admin_url( 'post-new.php?post_type=page' );
	$quickstart[] = array(
		'name' => esc_html__( 'Setup Frontpage and Blog Page', 'unos' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( 'Users often want to create a landing Homepage/Frontpage to welcome their visitors, while a separate \'Blog\' page to list all their blog posts. To do this, follow these steps:%9$s%1$s
			%3$sIn your wp-admin area, click %11$sPages > Add New%12$s%4$s
			%3$sGive page a Title %7$s(lets call it "My Home Page")%8$s and %5$sPublish%6$s%4$s
			%3$sIn your wp-admin area, click %11$sPages > Add New%12$s%4$s
			%3$sGive page a Title %7$s(lets call it "My Blog")%8$s and %5$sPublish%6$s%4$s
			%3$sIn your wp-admin area, go to %10$sSettings > Reading%12$s%4$s
			%3$sSelect the %5$sStatic Page%6$s option.%4$s
			%3$sSelect the pages you created in Step 2 and 4 above.%4$s
			%3$s%5$sSave%6$s the Changes.%4$s
			%2$s', 'unos' ), '<ol>', '</ol>', '<li>', '</li>', '<strong>', '</strong>', '<em>', '</em>', '<br />',
										'<a href="' . esc_url( $settinglink ) . '">',
										'<a href="' . esc_url( $addpagelink ) . '">',
										'</a>'
				),
		'img' => $imagepath . 'qstart-staticpage.png',
		'style' => 'img-bottom',
		);

	$menulink = admin_url( 'nav-menus.php' );
	$quickstart[] = array(
		'name' => esc_html__( 'Setup Main Navigation Menu', 'unos' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( '%1$s
			%3$sIn your wp-admin, go to %10$sAppearance > Menus%12$s%4$s
			%3$sClick on %5$screate a new menu%6$s link. %9$s%7$s(If you already have an existing menu, jump to Step 6)%8$s%4$s
			%3$sGive your menu a name and click %5$sCreate Menu%6$s%4$s
			%3$sNow add pages, categories, custom links etc to this menu.%4$s
			%3$sClick %5$sSave Menu%6$s%4$s
			%3$sClick %11$sManage Locations%12$s tab at the top%4$s
			%3$sSelect the menu you just created in the dropdown options.%4$s
			%3$sClick %5$sSave Changes%6$s%4$s
			%2$s%7$sTip: You can add "My Home Page" and "My Blog" pages created in above section to your menu.%8$s
			', 'unos' ), '<ol>', '</ol>', '<li>', '</li>', '<strong>', '</strong>', '<em>', '</em>', '<br />',
										'<a href="' . esc_url( $menulink ) . '">',
										'<a href="' . esc_url( $menulink ) . '?action=locations">',
										'</a>'
				),
		);

	$widgetslink = admin_url( 'widgets.php' );
	$customizelink = admin_url( 'customize.php' );
	$quickstart[] = array(
		'name' => esc_html__( 'Add Content to Frontpage', 'unos' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( '%1$s
			%3$sIn your wp-admin, go to %10$sAppearance > Widgets%12$s%4$s
			%3$sAdd Widgets to the %5$sFrontpage Widget Areas%6$s%4$s
			%3$sYou can further manage Frontpage modules in your wp-admin by going to %11$sAppearance > Customizer%12$s and click %5$sFrontpage Modules%6$s section.%4$s
			%2$s
			%9$s%9$s
			%13$sExample: Display a full width slider%14$s
			To display a full width slider on your frontpage, set one of the Frontpage Module to full width in %11$sAppearance > Customizer > Frontpage Modules%12$s. Then add a %5$sHootKit Slider%6$s widget to this area from the %10$sWidgets%12$s screen.
			', 'unos' ), '<ol>', '</ol>', '<li>', '</li>', '<strong>', '</strong>', '<em>', '</em>', '<hr>',
										'<a href="' . esc_url( $widgetslink ) . '">',
										'<a href="' . esc_url( $customizelink ) . '">',
										'</a>', '<h4>', '</h4>'
				),
		'img' => $imagepath . 'qstart-fpmodule.png',
		'style' => 'img-bottom',
		);

	$quickstart[] = array(
		'name' => esc_html__( 'Install Demo Content', 'unos' ),
		/* Translators: The %s are placeholders for HTML, so the order can't be changed. */
		'desc' => sprintf( esc_html__( '%13$s1 Click Demo Content Installation%14$s%9$sThis step is %5$soptional%6$s but %5$shighly recommended%6$s.%9$sUse the HootKit plugin to install demo content with a single click to make your site look exactly like the %10$sDemo Site%12$s.%9$sNew users often find it easier to edit existing content rather than starting from scratch.%9$s%11$sHow To Install Demo Content%12$s', 'unos' ), '<ol>', '</ol>', '<li>', '</li>', '<strong>', '</strong>', '<em>', '</em>', '<hr>',
										'<a href="https://demo.wphoot.com/unos/" target="_blank">',
										'<a class="button linkto-tab" data-tabid="import" href="#hoot-import"><span class="dashicons dashicons-art"></span> ',
										'</a>', '<h4>', '</h4>'
					),
		);

	return ( !empty( $$string ) ) ? $$string : '';


}