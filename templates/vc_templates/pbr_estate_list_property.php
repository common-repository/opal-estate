<?php
$show_description = true;
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

if( $limit < $column){
	$limit = $column;
}

if( class_exists("Opalestate_Query") ):

	if(is_front_page()){
	    $paged = (get_query_var('page')) ? get_query_var('page') : 1;
	}
	else{
	    $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	}
	
	$args =  array("posts_per_page"=>$limit, 'paged' => $paged);

	$query = Opalestate_Query::get_property_query( $args );

	$colclass = !empty($column) ? 12/$column : 4;
?>
<div class="widget widget-estate-property">
	<?php if(!empty($title)){ ?>
		<h4 class="widget-title text-center">
			<span><?php echo trim($title); ?></span>
            <?php if( !empty($description) && $show_description){ ?>
                <span class="widget-desc">
                    <?php echo trim($description); ?>
                </span>
            <?php } ?>
		</h4>
	<?php } ?>
	
	<div class="widget-content">
		<?php if( isset($showsortby) && $showsortby ): ?>
			<?php echo Opalestate_Template_Loader::get_template_part( 'collection-navigator', array('mode'=>'list' ) ); ?>
		<?php endif; ?>
		<div class="opalesate-recent-property opalestate-rows">
			<?php if( $query->have_posts() ): ?> 
				<div class="row">
					<?php $cnt=0; while ( $query->have_posts() ) : $query->the_post(); 
						$cls = '';
						if( $cnt++%$column==0 ){
							$cls .= ' first-child';
						}
						$display = opalestate_get_display_mode('list');
					?>
                	<?php if ( $display == 'grid' && $showsortby ): ?>
                		<div class="col-lg-<?php echo esc_attr($colclass); ?> col-md-<?php echo esc_attr($colclass); ?> col-sm-6">
	                		<?php echo Opalestate_Template_Loader::get_template_part( 'content-property-grid' ); ?>
	                	</div>
                	<?php else: ?>
                		<?php if( isset($description) && $description ): ?>
							<div class="col-lg-12 col-md-12 col-sm-12 <?php echo esc_attr($cls); ?> none-description">
			                	<?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list' ); ?>
			            	</div>
		            	<?php else:  $colclass = 12; ?>
		            		<div class="col-lg-12 col-md-12 col-sm-12 <?php echo esc_attr($cls); ?>">
			                	<?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list' ); ?>
			            	</div>
			            <?php endif; ?>	
					<?php endif; ?>
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