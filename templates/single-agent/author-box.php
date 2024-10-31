<?php $agent = new OpalEstate_Agent(); ?>
<div class="property-agent-contact ">
	<?php $is_sticky = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'sticky', true ); ?>
	<div class="agent-box  <?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
		<div  class="agent-preview">
	    <?php if ( has_post_thumbnail() ) : ?>
			<div class="col-lg-6  col-sm-6 team-header<?php if ( ! has_post_thumbnail() ) { echo 'without-image'; } ?>">
		        <a href="<?php the_permalink(); ?>" class="agent-box-image-inner <?php if ( ! empty( $agent ) ) : ?>has-agent<?php endif; ?>">
                    <?php the_post_thumbnail( apply_filters('opalestate_single_agent_thumbnail', 'full') ); ?>
		        </a>
		        <?php $agent->render_level(); ?>
		        <?php if( $agent->is_featured() ): ?>
				<span class="property-label" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Featured Agent', 'opalestate'); ?>">
					<i class="fa fa-star"></i>
				</span>
				<?php endif; ?>
			</div><!-- /.agent-box-image -->
	    <?php endif; ?>

		</div>
	    <div class="col-lg-6 col-sm-6 agent-box-meta">
	        <h3 class="agent-box-title">
	        <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
	        </h3><!-- /.agent-box-title -->

	        <?php
            	$job = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'job', true );
            ?>
            <p class="agent-job text-uppercase"><?php echo esc_html($job); ?></p>

	        <?php $email = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'email', true ); ?>
	        <?php if ( ! empty( $email ) ) : ?>
	            <div class="agent-box-email">
		            <a href="mailto:<?php echo esc_attr( $email ); ?>">
	                   <i class="fa fa-envelope"></i> <span><?php echo esc_attr( $email ); ?></span>
		            </a>
	            </div><!-- /.agent-box-email -->
	        <?php endif; ?>


	        <?php $phone = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'phone', true ); ?>
	        <?php if ( ! empty( $phone ) ) : ?>
	            <div class="agent-box-phone">
	               <i class="fa fa-phone"></i><span><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_attr( $phone ); ?></a></span>
	            </div><!-- /.agent-box-phone -->
	        <?php endif; ?>

	        <?php $mobile = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'mobile', true ); ?>
	        <?php if ( ! empty( $mobile ) ) : ?>
	            <div class="agent-box-mobile">
	                <i class="fa fa-mobile"></i><span><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_attr( $mobile ); ?></a></span>
	            </div><!-- /.agent-box-phone -->
	        <?php endif; ?>

	        <?php $fax = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'fax', true ); ?>
	        <?php if ( ! empty( $fax ) ) : ?>
	            <div class="agent-box-fax">
	                <i class="fa fa-fax"></i><span><?php echo esc_attr( $fax ); ?></span>
	            </div><!-- /.agent-box-phone -->
	        <?php endif; ?>

		    <?php $web = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'web', true ); ?>
		    <?php if ( ! empty( $web ) ) : ?>
			    <div class="agent-box-web">
				    <a href="<?php echo esc_attr( $web ); ?>" rel="nofollow" target="_blank">
				        <i class="fa fa-globe"></i> <span><?php echo esc_attr( $web ); ?></span>
				    </a>
			    </div><!-- /.agent-box-web -->
		    <?php endif; ?>

		   	<?php
				$facebook 	= get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'facebook', true );
				$twitter 	= get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'twitter', true );
				$pinterest  = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'pinterest', true );
				$google 	= get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'google', true );
				$instagram	= get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'instagram', true );
				$linkedIn   = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'linkedIn', true );
			?>
		        <div class="bo-social-icons">
		        	<?php if( $facebook && $facebook != "#" && !empty($facebook) ){  ?>
					<a class="bo-social-white radius-x" rel="nofollow" href="<?php echo esc_url( $facebook ); ?>"> <i  class="fa fa-facebook"></i> </a>
						<?php } ?>
					<?php if( $twitter && $twitter != "#" && !empty($twitter) ){  ?>
					<a class="bo-social-white radius-x" rel="nofollow" href="<?php echo esc_url( $twitter ); ?>"><i  class="fa fa-twitter"></i> </a>
					<?php } ?>
					<?php if( $pinterest && $pinterest != "#" && !empty($pinterest)){  ?>
					<a class="bo-social-white radius-x" rel="nofollow" href="<?php echo esc_url( $pinterest ); ?>"><i  class="fa fa-pinterest"></i> </a>
					<?php } ?>
					<?php if( $google && $google != "#" && !empty($google) ){  ?>
					<a class="bo-social-white radius-x" rel="nofollow" href="<?php echo esc_url( $google ); ?>"> <i  class="fa fa-google"></i></a>
					<?php } ?>

					<?php if( $instagram && $instagram != "#" && !empty($instagram) ){  ?>
					<a class="bo-social-white radius-x" rel="nofollow" href="<?php echo esc_url( $instagram ); ?>"> <i  class="fa fa-instagram"></i></a>
					<?php } ?>

					<?php if( $linkedIn && $linkedIn != "#" && !empty($linkedIn) ){  ?>
					<a class="bo-social-white radius-x" rel="nofollow" href="<?php echo esc_url( $linkedIn ); ?>"> <i  class="fa fa-linkedIn"></i></a>
					<?php } ?>

		        </div>

	    </div><!-- /.agent-box-content -->

	    <?php if( is_single() && get_post_type() == 'opalestate_agent' ): ?>
		 <?php else : ?>
		    <div class="agent-box-bio">
		    		<?php the_excerpt();?>
		    </div>
		     <p class="agent-box-readmore">
		    	 <a href="<?php the_permalink(); ?>">
		    		<?php _e( 'View Profile', 'opalestate' ); ?>
		    	</a>
		    </p>
		<?php endif; ?>
	</div><!-- /.agent-box-->
</div>
