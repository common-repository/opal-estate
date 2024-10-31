<?php
global $post;

$limit = apply_filters( 'opalesate_agent_properties_limit', 6 ); 
$query = Opalestate_Query::get_agent_property( null, get_the_ID(), $limit );

if( $query->have_posts() ) :
?>
<div class="clearfix clear"></div>
<div class="opalestate-box property-same-agent-section">
	<h3><?php echo sprintf( __('My Properties', 'opalestate'),  $query->found_posts  );?></h3>
	<div class="ajax-load-properties" data-paged="1" data-type="agent" data-id="<?php echo get_the_ID(); ?>">
		<div class="opalestate-rows">
			<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
				<?php while( $query->have_posts() ) : $query->the_post(); ?>
				  	<div class="col-lg-12 col-md-12 col-sm-12">
				  	 <?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list' ); ?>
				  	</div> 
				<?php endwhile; ?>	
			</div>
		</div>	
		<?php if( $query->max_num_pages > 1 ): ?>
		<div class="w-pagination"><?php opalestate_pagination(  $query->max_num_pages ); ?></div>
		<?php endif; ?>
	</div>	
</div>	
<?php endif; 
	wp_reset_postdata();
?>
