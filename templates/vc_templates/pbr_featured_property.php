<?php 
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$title_color = $title_color?'style="color:'. $title_color .';"' : "";

if( class_exists("Opalestate_Query") ): 
	$query = Opalestate_Query::get_featured_properties_query( array("posts_per_page" => $limit ) );  
?>
<div class="widget widget-estate-property">
	<?php if(!empty($title)){ ?>
		<h4 class="widget-title text-center" <?php echo trim( $title_color); ?>>
			<span><?php echo trim($title); ?></span>
			<?php if(trim($description)!=''){ ?>
	            <span class="widget-desc">
	                <?php echo trim($description); ?>
	            </span>
	        <?php } ?>
		</h4>
	<?php } ?>
	
	<div class="widget-content">
		<div class="opalesate-featured-property owl-carousel-play opalestate-rows">
			<?php if( $query->have_posts() ): ?> 
				 <div class="owl-carousel" data-slide="1"  data-singleItem="true" data-navigation="true" data-pagination="false">
					<?php $cnt=0; while ( $query->have_posts() ) : $query->the_post(); ?>
						<div class="item">
		                	<?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list-v3' ); ?>
		            	</div>
					<?php $cnt++; endwhile; ?>
				</div>

				<?php if( $cnt > 1 ) : ?>
				<a class="left carousel-control style-3" href="#post-slide-<?php $id; ?>" data-slide="prev">
	                <span class="fa fa-angle-left"></span>
	            </a>
	            <a class="right carousel-control style-3" href="#post-slide-<?php $id; ?>" data-slide="next">
	                <span class="fa fa-angle-right"></span>
	            </a>
	       	 <?php endif; ?>
			<?php else: ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>	
		</div>
	</div>	
</div>	
<?php endif; ?>
<?php wp_reset_query(); ?>