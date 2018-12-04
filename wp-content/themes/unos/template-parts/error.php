<article <?php hoot_attr( 'post' ); ?>>

	<?php if ( apply_filters( 'unos_display_404_title', true ) ) : ?>
		<header class="entry-header">
			<?php
			$loop_meta_displayed = hoot_data( 'loop_meta_displayed' );
			$tag = ( $loop_meta_displayed ) ? 'h2' : 'h1';
			echo "<{$tag} class='entry-title'>" . esc_html__( 'Nothing found', 'unos' ) . "</{$tag}>";
			?>
		</header><!-- .entry-header -->
	<?php endif; ?>

	<div <?php hoot_attr( 'entry-content', '', 'no-shadow' ); ?>>
		<div class="entry-the-content">
			<?php do_action( 'unos_404_content' ); ?>
		</div>
	</div><!-- .entry-content -->

</article><!-- .entry -->