<?php global $property, $post; 
	$property = opalesetate_property( get_the_ID() );

?> 
<article id="post-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/Property" <?php post_class(); ?>>
	<?php do_action( 'opalestate_single_property_before' );  ?>
	<header>
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<div class="col-lg-9">
					<?php the_title( '<h1 class="entry-title pull-left">', '</h1>' ); ?>
					<div class="pull-left">
					<?php echo $property->render_statuses(); ?>
					</div>
				 	
					<?php if( $property->is_featured() ): ?>
					<span class="property-label" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Featured Property', 'opalestate'); ?>">
						<i class="fa fa-star"></i>
					</span>
					<?php endif; ?>
			</div>
			<div class="col-lg-3">
				 <?php opalestate_property_loop_price(); ?>
			</div>
		</div>

		<div class="property-meta">
			<div class="property-address clearfix">

				<div class="pull-left"><?php _e('Address:','opalestate'); ?>
					<?php echo $property->get_address(); ?> 
					<?php if( $property->latitude && $property->longitude ) : ?>
					<span class="property-view-map"><a href="<?php echo $property->get_google_map_link(); ?>" rel="nofollow" target="_blank"> 
						<i class="fa fa-map-marker"></i></a>
					</span>	
					<?php endif ; ?>
				</div> 
				<div class="pull-left"><?php _e('Location:','opalestate'); ?>  <?php echo $property->render_locations(); ?></div>
			</div>	

			<div class="favorite-button  pull-left"><?php do_shortcode('[opalestate_favorite_button property_id='.get_the_ID() .']'); ?></div>
			<div class="pull-left"><?php opalestate_get_single_short_meta(); ?></div>
			<div class="pull-right hidden-xs" ><a href="javascript:window.print()"><i class="fa fa-print" aria-hidden="true"></i> <?php _e( 'Print', 'opalestate' ); ?></a></div>
		
		</div>	
		<div class="clearfix"></div>
	</header>		
	
	<?php
		/**
		 * opalestate_before_single_property_summary hook
		 *
		 * @hooked opalestate_show_product_sale_flash - 10
		 * @hooked opalestate_show_product_images - 20
		 */
		do_action( 'opalestate_single_property_preview' );
	?>

	<div class="summary entry-summary opalestate-rows">
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<div class="col-lg-4 col-md-5">
				<?php
					/**
					 * opalestate_single_property_summary hook
					 *
					 * @hooked opalestate_template_single_title - 5
					 * @hooked opalestate_template_single_rating - 10
					 * @hooked opalestate_template_single_price - 10
					 * @hooked opalestate_template_single_excerpt - 20
					 * @hooked opalestate_template_single_add_to_cart - 30
					 * @hooked opalestate_template_single_meta - 40
					 * @hooked opalestate_template_single_sharing - 50
					 */
					do_action( 'opalestate_single_property_summary' );
				?>		
			</div>
			<div class="col-lg-8 col-md-7">	
				<?php 
					echo opalestate_property_content();
				?>
				
			</div>
		</div>
		<div class="content-bottom">
			<?php do_action( 'opalestate_single_content_bottom' ); ?>
		</div>
	</div><!-- .summary -->
	<meta itemprop="url" content="<?php the_permalink(); ?>" />

	<?php 
		/**
		 * opalestate_after_single_property_summary hook
		 *
		 * @hooked opalestate_output_product_data_tabs - 10
		 * @hooked opalestate_upsell_display - 15
		 * @hooked opalestate_output_related_products - 20
		 */
		do_action( 'opalestate_after_single_property_summary' );

	?>
	<div class="clear clearfix"></div>

</article><!-- #post-## -->

