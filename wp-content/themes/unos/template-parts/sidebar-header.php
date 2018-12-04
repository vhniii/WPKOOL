<?php
// Dispay Sidebar if sidebar has widgets
if ( is_active_sidebar( 'hoot-header' ) ) :

	?>
	<div <?php hoot_attr( 'header-sidebar', '', 'inline-nav js-search hgrid-stretch' ); ?>>
		<?php

		// Template modification Hook
		do_action( 'unos_sidebar_start', 'header-sidebar' );

		?>
		<aside <?php hoot_attr( 'sidebar', 'header-sidebar' ); ?>>
			<?php dynamic_sidebar( 'hoot-header' ); ?>
		</aside>
		<?php

		// Template modification Hook
		do_action( 'unos_sidebar_end', 'header-sidebar' );

		?>
	</div>
	<?php

endif;