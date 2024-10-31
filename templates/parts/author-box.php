<?php if( $author ): ?>
<div class="property-agent-contact ">
	<?php 	 
		$user_id   = $author->ID;  
		$is_sticky = get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'sticky', true ); 
		$picture    = OpalEstate_User::get_author_picture( $user_id );
		$author_name = $author->display_name;
		$desciption = get_user_meta( $user_id, 'description', true ); 
	?>
	<div class="agent-box <?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
	   
		<div class="col-lg-6  col-sm-6 agent-box-image">
            <img src="<?php echo esc_url($picture);?>" title="<?php echo $author_name; ?>" >
           
		</div><!-- /.agent-box-image -->
	

	    <div class="col-lg-6 col-sm-6 agent-box-meta">
	        <h3 class="agent-box-title">
	        <a href="<?php echo get_author_posts_url( $user_id ); ?>"><?php echo $author_name; ?></a>
	        </h3><!-- /.agent-box-title -->

	        <?php
            	$job = get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'company', true );
            ?>
            <p class="agent-job text-uppercase"><?php echo esc_html($job); ?></p>

	        <?php $email = get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'email', true ); ?>
	        <?php if ( ! empty( $email ) ) : ?>
	            <div class="agent-box-email">
		            <a href="mailto:<?php echo esc_attr( $email ); ?>">
	                   <i class="fa fa-envelope"></i> <span><?php echo esc_attr( $email ); ?></span>
		            </a>
	            </div><!-- /.agent-box-email -->
	        <?php endif; ?>

	        <?php $phone = get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'phone', true ); ?>
	        <?php if ( ! empty( $phone ) ) : ?>
	            <div class="agent-box-phone">
	               <i class="fa fa-phone"></i><span><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_attr( $phone ); ?></a></span>
	            </div><!-- /.agent-box-phone -->
	        <?php endif; ?>

		    <?php $web = get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'web', true ); ?>
		    <?php if ( ! empty( $web ) ) : ?>
			    <div class="agent-box-web">
				    <a href="<?php echo esc_attr( $web ); ?>">
				        <i class="fa fa-globe"></i> <span><?php echo esc_attr( $web ); ?></span>
				    </a>
			    </div><!-- /.agent-box-web -->
		    <?php endif; ?>

		   	<?php
				$facebook 	= get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'facebook', true );
				$twitter 	= get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'twitter', true );
				$pinterest  = get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'pinterest', true );
				$google 	= get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'googleplus', true );
				$instagram	= get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'instagram', true );
				$linkedIn   = get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'linkedIn', true );
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
					<a class="bo-social-white radius-x" href="<?php echo esc_url( $google ); ?>"> <i  class="fa fa-google-plus"></i></a>
					<?php } ?>

					<?php if( $instagram && $instagram != "#" && !empty($instagram) ){  ?>
					<a class="bo-social-white radius-x" href="<?php echo esc_url( $instagram ); ?>"> <i  class="fa fa-instagram"></i></a>
					<?php } ?>

					<?php if( $linkedIn && $linkedIn != "#" && !empty($linkedIn) ){  ?>
					<a class="bo-social-white radius-x" href="<?php echo esc_url( $linkedIn ); ?>"> <i  class="fa fa-linkedIn"></i></a>
					<?php } ?>

		        </div>

	    </div><!-- /.agent-box-content -->

	</div><!-- /.agent-box-->
</div>
<?php endif; ?>