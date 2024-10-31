<?php
 	$agent_id    = get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'agent_id', true );
	$job         = '';
	$user  		 = get_userdata( $user_id );
	$user  		 = $user->data; 
	$description = get_user_meta( $user_id, 'description', true );
	$picture     = OpalEstate_User::get_author_picture( $user_id );

	if( $agent_id ){  
		$post = get_post( $agent_id );
		$facebook 	 = get_post_meta( $agent_id, OPALESTATE_AGENT_PREFIX . 'facebook', true );
		$twitter 	 = get_post_meta( $agent_id, OPALESTATE_AGENT_PREFIX . 'twitter', true );
		$pinterest   = get_post_meta( $agent_id, OPALESTATE_AGENT_PREFIX . 'pinterest', true );
		$google 	 = get_post_meta( $agent_id, OPALESTATE_AGENT_PREFIX . 'google', true );
		$instagram	 = get_post_meta( $agent_id, OPALESTATE_AGENT_PREFIX . 'instagram', true );
		$linkedIn 	 = get_post_meta( $agent_id, OPALESTATE_AGENT_PREFIX . 'linkedIn', true );
		$job 		 = get_post_meta( $agent_id, OPALESTATE_AGENT_PREFIX . 'job', true );
		$description = $post->post_excerpt;
		$title 		 = $post->post_title;
		$author_link = get_permalink( $agent_id );
		wp_reset_query();
	}else {


		$title 		= $user->display_name;
		$facebook 	= get_post_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'facebook', true );
		$twitter 	= get_post_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'twitter', true );
		$pinterest  = get_post_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'pinterest', true );
		$google 	= get_post_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'google', true );
		$instagram	= get_post_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'instagram', true );
		$linkedIn 	= get_post_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'linkedIn', true );
		$job = get_post_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'job', true );
		$author_link = get_author_posts_url( $user_id );
 	}
?>
<article <?php post_class(); ?>>
	<div class="team-v1">
	
	    <div class="team-header">
	        <div class="agent-box-image">
	        	<img src="<?php echo $picture ; ?>">
	    	</div>
	    </div>     
	    <div class="team-body">
	        
	        <div class="team-body-content">
	            <h5 class="agent-box-title text-uppercase">
		            <a href="<?php echo $author_link; ?>"><?php echo $title; ?></a>
		        </h5><!-- /.agent-box-title -->
	            <h3 class="team-name hide"><?php echo $title; ?></h3>
	            <?php
	            	
	            ?>
	            <p class="agent-job"><?php echo esc_html($job); ?></p>
	        
	        </div>      


	         <div class="agent-box-meta">

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
	        <?php echo opalestate_fnc_get_words( $description, 15, "..." ); ?>
	    </p>                                      
	</div>
</article>	