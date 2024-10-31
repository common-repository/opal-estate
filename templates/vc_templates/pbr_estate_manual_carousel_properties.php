<?php  

$enable_thumbnail = false; 
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

 
extract( $atts );

if( $limit < $column){
	$limit = $column;
}

if( class_exists("Opalestate_Query") ):


$args =  array();
$args['post_name__in'] = !empty($property_slugs)?explode(',', str_replace(" ","",$property_slugs) ):array('99999');
$query = Opalestate_Query::get_property_query( $args );
$colclass = floor(12/$column);  
?>
<div class="widget widget-estate-property manual-properties">
	<?php if(!empty($title)){ ?>
		<h4 class="widget-title text-center hide">
			<span><?php echo trim($title); ?></span>
		</h4>
	<?php } ?>
	
	<div class="widget-content">
 
		<div class="widget-content">
			<div class="opalesate-recent-property opalestate-rows">
				<?php if( $query->have_posts() ): ?> 
				<div class="owl-carousel-play">
					<div class="owl-carousel" data-slide="<?php echo esc_attr($column); ?>"  data-singleItem="true" data-navigation="true">
						<?php while ( $query->have_posts() ) : $query->the_post(); ?>
						<div class="item">
		                	<?php echo Opalestate_Template_Loader::get_template_part( 'content-property-grid', array( 'enable_thumbnail' => $enable_thumbnail ) ); ?>
		            	</div>
						<?php   endwhile; ?>
					</div>

					<?php if( $query->found_posts > $column ): ?>
					 <div class="carousel-controls carousel-controls-v3">
		                <a class="left carousel-control carousel-xs" href="#post-slide-<?php $id; ?>" data-slide="prev">
		                    <span class="fa fa-angle-left"></span>
		                </a>
		                <a class="right carousel-control carousel-xs" href="#post-slide-<?php $id; ?>" data-slide="next">
		                    <span class="fa fa-angle-right"></span>
		                </a>
		            </div>
		       	   <?php endif; ?>
				</div>	
				<?php else: ?>
					<?php get_template_part( 'content', 'none' ); ?>
				<?php endif; ?>	
			</div>
		</div>


	</div>	
</div>	
<?php endif; ?>
<?php wp_reset_query(); ?>