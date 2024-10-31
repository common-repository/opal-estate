<?php
$agent = new OpalEstate_Agent();
 
$agent_id = get_the_ID();
$job='';?>
<article <?php post_class( 'agent-grid-style'); ?>>
	<div class="team-v2 agent-inner">
	
	    <div class="team-header agent-header">
	        <?php opalestate_get_loop_agent_thumbnail( opalestate_get_option('agent_image_size','large') ); ?>
	        <?php if( $agent->is_featured() ): ?>
			<span class="property-label" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Featured Agent', 'opalestate'); ?>">
				<i class="fa fa-star"></i>
			</span>
			<?php endif; ?>
	    </div>     
	    <div class="team-body agent-body">
	        
	        <div class="team-body-content">
	            <h5 class="agent-box-title">
		            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
		        </h5><!-- /.agent-box-title -->
	            <h3 class="team-name hide"><?php the_title(); ?></h3>
	        
	        </div>

	    </div>

	</div>
</article>	