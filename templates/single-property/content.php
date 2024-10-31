<div class="entry-content">
	<h3 class="box-heading"><?php _e( 'Property Description', 'opalestate' ); ?></h3>
	<?php
		/* translators: %s: Name of current post */
		the_content( sprintf(
			__( 'Continue reading %s ', 'opalestate' ),
			the_title( '<span class="screen-reader-text">', '</span>', false )
		) );

		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'opalestate' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
		) );
	?>
</div><!-- .entry-content -->