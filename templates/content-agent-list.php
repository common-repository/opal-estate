<?php
$agent = new OpalEstate_Agent();
$job='';?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="team-v2">
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="team-header">
			       <?php opalestate_get_loop_agent_thumbnail( opalestate_get_option('agent_image_size','large') ); ?>
			        <?php $agent->render_level(); ?>
			        <?php if( $agent->is_featured() ): ?>
					<span class="property-label" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Featured Agent', 'opalestate'); ?>">
						<i class="fa fa-star"></i>
					</span>
					<?php endif; ?>
			
			    </div>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
				<div class="team-body">
			        <div class="team-body-content">
			            <h5 class="agent-box-title text-uppercase">
				            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
				        </h5><!-- /.agent-box-title -->
			            <h3 class="team-name hide"><?php the_title(); ?></h3>
			            <?php
			            	$job = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'job', true );
			            ?>
			            <p class="agent-job"><?php echo esc_html($job); ?></p>
			        </div>      
			         <div class="agent-box-meta">
				        
				        <?php 
							$facebook 	= get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'facebook', true );
							$twitter 	= get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'twitter', true );
							$pinterest  = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'pinterest', true );
							$google 	= get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'google', true );
							$instagram	= get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'instagram', true );
							$linkedIn = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'linkedIn', true );
						?>

				        <div class="bo-social-icons">
				        	<?php if( $facebook && $facebook != "#" && !empty($facebook) ){  ?>
							<a class="bo-social-white radius-x" href="<?php echo esc_url( $facebook ); ?>"> <i  class="fa fa-facebook"></i> </a>
								<?php } ?>
							<?php if( $twitter && $twitter != "#" && !empty($twitter) ){  ?>
							<a class="bo-social-white radius-x" href="<?php echo esc_url( $twitter ); ?>"><i  class="fa fa-twitter"></i> </a>
							<?php } ?>
							<?php if( $pinterest && $pinterest != "#" && !empty($pinterest)){  ?>
							<a class="bo-social-white radius-x" href="<?php echo esc_url( $pinterest ); ?>"><i  class="fa fa-pinterest"></i> </a>
							<?php } ?>
							<?php if( $google && $google != "#" && !empty($google) ){  ?>
							<a class="bo-social-white radius-x" href="<?php echo esc_url( $google ); ?>"> <i  class="fa fa-google"></i></a>
							<?php } ?>

							<?php if( $instagram && $instagram != "#" && !empty($instagram) ){  ?>
							<a class="bo-social-white radius-x" href="<?php echo esc_url( $instagram ); ?>"> <i  class="fa fa-instagram"></i></a>
							<?php } ?>

							<?php if( $linkedIn && $linkedIn != "#" && !empty($linkedIn) ){  ?>
							<a class="bo-social-white radius-x" href="<?php echo esc_url( $linkedIn ); ?>"> <i  class="fa fa-linkedIn"></i></a>
							<?php } ?>
				                              
				        </div> 

					   
				    </div><!-- /.agent-box-content -->                     
			    </div>  
			    <p class="team-info">
			        <?php echo opalestate_fnc_excerpt( 15, "..." ); ?>
			    </p>
			</div>
		</div>         
	                                          
	</div>
</article>	