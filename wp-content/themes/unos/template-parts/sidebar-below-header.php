<?php
// Get Content
global $hoot_data;
hoot_set_data( 'below_header_left', is_active_sidebar( 'hoot-below-header-left' ) );
hoot_set_data( 'below_header_right', is_active_sidebar( 'hoot-below-header-right' ) );

// Template modification Hook
do_action( 'unos_before_below_header' );

// Display Below Header
$hoot_below_header_left = hoot_data()->below_header_left;
$hoot_below_header_right = hoot_data()->below_header_right;
if ( !empty( $hoot_below_header_left ) || !empty( $hoot_below_header_right ) ) :

	?>
	<div <?php hoot_attr( 'below-header', '', 'inline-nav js-search hgrid-stretch' ); ?>>
		<div class="hgrid">
			<div class="hgrid-span-12">

				<div class="below-header-inner table<?php if ( !empty( $hoot_below_header_left ) && !empty( $hoot_below_header_right ) ) echo ' below-header-parts'; ?>">
					<?php
					if ( $hoot_below_header_left ):
						$below_headerid = ( $hoot_below_header_right ) ? 'left' : 'center';

						// Template modification Hook
						do_action( 'unos_sidebar_start', 'below-header-left', $below_headerid );
						?>

						<div id="below-header-<?php echo $below_headerid; ?>" class="below-header-part table-cell-mid">
							<?php dynamic_sidebar( 'hoot-below-header-left' ); ?>
						</div>

						<?php
						// Template modification Hook
						do_action( 'unos_sidebar_end', 'below-header-left', $below_headerid );

					endif;
					?>

					<?php
					if ( $hoot_below_header_right ):
						$below_headerid = ( $hoot_below_header_left ) ? 'right' : 'center';

						// Template modification Hook
						do_action( 'unos_sidebar_start', 'below-header-right', $below_headerid );
						?>

						<div id="below-header-<?php echo $below_headerid; ?>" class="below-header-part table-cell-mid">
							<?php dynamic_sidebar( 'hoot-below-header-right' ); ?>
						</div>

						<?php
						// Template modification Hook
						do_action( 'unos_sidebar_end', 'below-header-right', $below_headerid );

					endif;
					?>
				</div>

			</div>
		</div>
	</div>
	<?php

endif;

// Template modification Hook
do_action( 'unos_after_below_header' );