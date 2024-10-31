<?php 
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if( $limit < $column){
	$limit = $column;
}
if( class_exists("Opalestate_Query") ):
	$query = Opalestate_Query::get_property_query( array("posts_per_page"=>$limit) );

$id = rand();
$colclass = floor(12/$column);  
?>
<div class="widget widget-estate-property overlap-carousel-property">
	<?php if(!empty($title)){ ?>
		<h4 class="widget-title text-center">
			<span><?php echo trim($title); ?></span>
			<?php if(trim($description)!=''){ ?>
	            <span class="widget-desc">
	                <?php echo trim($description); ?>
	            </span>
	        <?php } ?>
		</h4>
	<?php } ?>
	
	<div class="widget-content">
		<div class="opalesate-recent-property opalestate-rows">
			<?php if( $query->have_posts() ): ?> 
			<div class="owl-carousel-play">
				<div class="owl-carousel" data-slide="<?php echo esc_attr($column); ?>"  data-singleItem="true" data-navigation="true">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
					<div class="item">
	                	<?php echo Opalestate_Template_Loader::get_template_part( 'content-property-grid-v3' ); ?>
	            	</div>
					<?php   endwhile; ?>
				</div>
				 <div class="carousel-controls carousel-controls-v4">
	                <a class="left carousel-control carousel-xs" href="#post-slide-<?php echo $id; ?>" data-slide="prev">
	                    <span class="fa fa-angle-left"></span>
	                </a>
	                <a class="right carousel-control carousel-xs" href="#post-slide-<?php echo $id; ?>" data-slide="next">
	                    <span class="fa fa-angle-right"></span>
	                </a>
	            </div>

			</div>	
			<?php else: ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>	
		</div>
	</div>	
</div>	
<?php endif; ?>
<?php wp_reset_query(); ?>