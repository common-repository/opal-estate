<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header(); ?>
	<section id="main-container" class="site-main container" role="main">
		
		<?php do_shortcode( '[opalestate_search_properties]' ) ;?>

		<main id="primary" class="content content-area">
			<?php if ( have_posts() ) : ?>
				<header class="page-header">
					<?php
						the_archive_title( '<h1 class="page-title">', '</h1>' );
						the_archive_description( '<div class="taxonomy-description">', '</div>' );
					?>
				</header><!-- .page-header -->

				<div class="opaleslate-archive-container">
					<div class="opalesate-archive-top"><div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
						<div class="col-lg-6 col-md-6 col-sm-6">
							 <?php opalestate_show_display_modes(); ?>
						</div>

						<div class="col-lg-6 col-md-6 col-sm-6">

								<div class="opalestate-sortable pull-right">
									<?php echo opalestate_render_sortable_dropdown(); ?>
								</div>
						</div>
					</div></div>

					<div class="opalesate-archive-bottom opalestate-rows">
						<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
							<?php if ( (isset($_COOKIE['opalestate_displaymode']) && $_COOKIE['opalestate_displaymode'] == 'list') || (!isset($_COOKIE['opalestate_displaymode']) && opalestate_get_display_mode('list') == 'list') ):?>
								<?php while ( have_posts() ) : the_post(); ?>
									<div class="col-lg-12 col-md-12 col-sm-12">
				                    	<?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list' ); ?>
				                	</div>
				                <?php endwhile; ?>
							<?php else : ?>
								<?php
								$column = apply_filters('opalestate_properties_column_row', 3 );
								$clscol = floor(12/$column);
								$cnt = 0;
								while ( have_posts() ) : the_post();
								$cls = '';
								if( $cnt++%$column==0 ){
									$cls .= ' first-child';
								}
								?>
									<div class="<?php echo $cls; ?> col-lg-<?php echo $clscol; ?> col-md-<?php echo $clscol; ?> col-sm-6">
				                    	<?php echo Opalestate_Template_Loader::get_template_part( 'content-property-grid' ); ?>
				                	</div>
								<?php endwhile; ?>
							<?php endif; ?>
						</div>
					</div>	

				</div>
				<?php the_posts_pagination( array(
					'prev_text'          => __( 'Previous page', 'opalestate' ),
					'next_text'          => __( 'Next page', 'opalestate' ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'opalestate' ) . ' </span>',
				) ); ?>
				
			<?php else : ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>

		</main><!-- .site-main -->
	</section><!-- .content-area -->


<?php get_footer(); ?>
