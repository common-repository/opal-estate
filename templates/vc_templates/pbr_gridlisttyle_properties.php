<?php 
$column = '2';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if( $limit < $column){
	$limit = $column;
}

$title_color = isset($title_color) && $title_color?'style="color:'. $title_color .';"' : "";  

if( class_exists("Opalestate_Query") ):

	if(is_front_page()){
	    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
	}
	else{
	    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	}
	
	$args =  array("posts_per_page"=>$limit, 'paged' => $paged);
	if( $onlyfeatured ){
		$args['meta_key']   = OPALESTATE_PROPERTY_PREFIX . 'featured';
		$args['meta_value']	= 1;
	}
	$query = Opalestate_Query::get_property_query( $args );

	$colclass = !empty($column) ? floor(12/$column) : 4;
?>
<div class="widget widget-estate-property">
	<?php if(!empty($title)){ ?>
		<h4 class="widget-title text-center" >
			<span <?php echo trim($title_color); ?>><?php echo trim($title); ?></span>
			<?php if(trim($description)!=''){ ?>
	            <span class="widget-desc">
	                <?php echo trim($description); ?>
	            </span>
	        <?php } ?>
		</h4>
	<?php } ?>
	
	<div class="widget-content">
		 
		<div class="opalestate-rows">
			<?php if( $query->have_posts() ): ?> 
				<div class="row">
					<?php $cnt=0; while ( $query->have_posts() ) : $query->the_post(); 
						$cls = '';
						if( $cnt++%$column==0 ){
							$cls .= ' first-child';
						}
						$display = opalestate_get_display_mode('list');
					?>
            	 	<div class="col-lg-<?php echo esc_attr($colclass);?> col-md-<?php echo esc_attr($colclass);?> col-sm-<?php echo esc_attr($colclass);?> <?php echo esc_attr($cls); ?> none-description">
	                	<div class="space-padding-tb-10"><?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list' ); ?></div>
	            	</div>
					<?php endwhile; ?>
				</div>
				<?php if( isset($pagination) && $pagination ): ?>
					<div class="w-pagination"><?php opalestate_pagination(  $query->max_num_pages ); ?></div>
				<?php endif; ?> 
			<?php else: ?>
				<?php get_template_part( 'content', 'none' ); ?>
			<?php endif; ?>	
		</div>
	</div>	
</div>	
<?php endif; ?>
<?php wp_reset_query(); ?>