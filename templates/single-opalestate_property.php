<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header(); ?>

	<section id="main-container" class="site-main container" role="main">
		<main id="primary" class="content content-area">
			<div class="single-opalestate-container">
				<?php if ( have_posts() ) : ?>
				
						<?php while ( have_posts() ) : the_post();  ?>
		                    <?php echo Opalestate_Template_Loader::get_template_part( 'content-single-property', array(), opalestate_single_the_property_layout() ); ?>
						<?php endwhile; ?>
					
					<?php the_posts_pagination( array(
						'prev_text'          => __( 'Previous page', 'opalestate' ),
						'next_text'          => __( 'Next page', 'opalestate' ),
						'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'opalestate' ) . ' </span>',
					) ); ?>
					
					<?php 
						
						// If comments are open or we have at least one comment, load up the comment template.
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
											
					?>

				<?php else : ?>
					<?php get_template_part( 'content', 'none' ); ?>
				<?php endif; ?>
			</div>
		</main><!-- .site-main -->
	</section><!-- .content-area -->

<?php get_footer(); ?>
