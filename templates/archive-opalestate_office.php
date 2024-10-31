<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}    
get_header(); ?>

	<section id="main-container" class="site-main container" role="main">

		<main id="primary" class="content content-area">
			<?php echo Opalestate_Template_Loader::get_template_part( 'parts/search-office-form' ); ?>
			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

			

				<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
					<?php 
						$cnt = 0 ; 
						$column = 3; 
						$colclass = floor( 12/ $column );

						while ( have_posts() ) : the_post(); 
							$cls ='';
							if( $cnt++%$column==0 ){
								$cls .= ' first-child';
							}
					?>
						<div class="col-lg-<?php echo $colclass ?> <?php echo esc_attr($cls); ?>">
	                    	<?php echo Opalestate_Template_Loader::get_template_part( 'content-office-grid' ); ?>
	                	</div>
					<?php  endwhile; ?>
				</div>	
				<?php the_posts_pagination( array(
					'prev_text'          => __( 'Previous page', 'opalestate' ),
					'next_text'          => __( 'Next page', 'opalestate' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'opalestate' ) . ' </span>',
				) ); ?>
				
			<?php else : ?>
				<?php echo Opalestate_Template_Loader::get_template_part( 'content-no-results' ); ?>
			<?php endif; ?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->


<?php get_footer(); ?>
