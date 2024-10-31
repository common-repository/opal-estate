<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$property = opalesetate_property( get_the_ID() );

global $property, $post;

$meta   = $property->get_meta_shortinfo();
 
?>
<article itemscope itemtype="http://schema.org/Property" <?php post_class(); ?>><div class="property-list-style-v2">
	
	<div class="property-list container-cols-3">
		<header>
			<div class="property-group-label">
				<?php do_action( 'opalestate_before_property_loop_item' ); ?>
			</div>
			<?php opalestate_get_loop_thumbnail( opalestate_get_option('loop_image_size','large') ); ?>
			 
			<?php echo $property->render_statuses(); ?>

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
				 	<div class="property-meta-bottom clearfix">
						
				 	<div class="pull-left"><?php echo $property->render_author_link(); ?></div>
						<div class="pull-right">
							<?php do_shortcode('[opalestate_favorite_button property_id='.get_the_ID() .']'); ?>
						</div>
						
					</div>
				</div>	

			</div><!-- .entry-content -->
		</div> 
		
		<div class="entry-summary">
			<h5><?php echo __( 'Description', 'opalestate' ); ?></h5>
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->
	</div>	
	
	<?php do_action( 'opalestate_after_property_loop_item' ); ?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div></article><!-- #post-## -->
