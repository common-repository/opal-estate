<?php
global $post;
$agent_id = Opalestate_Query::get_agent_by_property($post->ID);
$properties = Opalestate_Query::get_agent_property( get_the_ID(), $agent_id, 3 );
if( $properties->have_posts() ) :
?>
<div class="box-info property-same-agent-section clearfix">
	<h3><?php printf( __( 'Properties by %s', 'opalestate' ), get_the_title( $agent_id ) ); ?></h3>
	<div class="box-content opalestate-rows">
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<?php while( $properties->have_posts() ) : $properties->the_post(); ?>
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			  	 <?php echo Opalestate_Template_Loader::get_template_part( 'content-property-grid' ); ?>
			  	</div>
			<?php endwhile; ?>	
		</div>		
	</div>	
</div>	
<?php wp_reset_postdata(); ?>
<?php endif; ?>

 