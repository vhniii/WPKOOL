<!DOCTYPE html>
<html <?php language_attributes( 'html' ); ?> class="no-js">

<head>
<?php
// Fire the wp_head action required for hooking in scripts, styles, and other <head> tags.
wp_head();
?>
</head>

<body <?php hoot_attr( 'body' ); ?>>

	<?php
	// Display Topbar
	get_template_part( 'template-parts/topbar' );
	?>

	<div <?php hoot_attr( 'page-wrapper' ); ?>>

		<div class="skip-link">
			<a href="#content" class="screen-reader-text"><?php esc_html_e( 'Skip to content', 'unos' ); ?></a>
		</div><!-- .skip-link -->

		<?php
		// Template modification Hook
		do_action( 'unos_site_start' );
		?>

		<header <?php hoot_attr( 'header' ); ?>>

			<?php
			// Display Secondary Menu
			unos_secondary_menu( 'top' );
			?>

			<div <?php hoot_attr( 'header-part', 'primary' ); ?>>
				<div class="hgrid">
					<div class="table hgrid-span-12">
						<?php
						// Display Branding
						unos_branding();

						// Display Menu
						unos_header_aside();
						?>
					</div>
				</div>
			</div>

			<?php
			// Display Secondary Menu
			unos_secondary_menu( 'bottom' );
			?>

		</header><!-- #header -->

		<?php hoot_get_sidebar( 'below-header' ); // Loads the template-parts/sidebar-below-header.php template. ?>

		<div <?php hoot_attr( 'main' ); ?>>
			<?php
			// Template modification Hook
			do_action( 'unos_main_wrapper_start' );