<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $property, $post;
$property = opalesetate_property( get_the_ID() );
?>
<article itemscope itemtype="http://schema.org/Property" <?php post_class(); ?>><div class="property-list-style">
	
	<div class="property-list container-cols-2">
			<header>

				<div class="property-group-label"><?php do_action( 'opalestate_before_property_loop_item' ); ?></div>

				<?php echo $property->render_statuses(); ?>

				<?php opalestate_get_loop_thumbnail( opalestate_get_option('loop_image_size','large') ); ?>
				 
				<?php opalestate_get_loop_short_meta(); ?>

			</header>
				<div class="abs-col-item">
					<div class="entry-content">
						
						<?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>
							
					  	<div class="property-address">
							<?php echo $property->get_address(); ?>
						</div>

						<?php opalestate_property_loop_price(); ?>

						<div class="property-abs-bottom">
						 	<div class="property-meta-bottom">
								<div class="property-meta">
									<div class="hide"><?php echo apply_filters( 'opalestate_areasize_unit_format', $property->get_metabox_value( 'areasize' ) );?></div>
								</div>
							</div>

							<div class="entry-content-bottom clearfix">
								<div class="pull-left">
								<?php echo $property->render_author_link(); ?>
								</div>
								<div class="pull-right">
									<a class="btn-view-more text-primary hide" href="<?php the_permalink(); ?>"><i class="fa fa-angle-right"></i></a>
									<?php do_shortcode('[opalestate_favorite_button property_id='.get_the_ID() .']'); ?>
								</div>
							</div>
						</div>

					</div><!-- .entry-content -->
	 			</div>
	</div>	

	<?php do_action( 'opalestate_after_property_loop_item' ); ?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div></article><!-- #post-## -->
