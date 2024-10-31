<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $post;
$show_author_box = apply_filters( 'opalestate_single_show_author_box' , 1 );

if( $show_author_box ):

	$office_id = get_user_meta( $post->post_author, OPALESTATE_USER_PROFILE_PREFIX . 'office_id', true ); 
	$agent_id  = get_user_meta( $post->post_author, OPALESTATE_USER_PROFILE_PREFIX . 'agent_id', true ); 
	$data 		 =  get_userdata( $post->post_author ); 
	$message =  sprintf(__('Hi, I am interested in %s (Property ID: %s)', 'opalestate'), get_the_title() , get_the_ID() );
	
	$roles = opalestate_user_roles_by_user_id(  $post->post_author   );
	
	if ( in_array( 'opalestate_office' , $roles) ) :
		$email 		 = get_post_meta( $office_id, OPALESTATE_OFFICE_PREFIX . 'email', true ); 
		$args   = array( 
				'post_id' 	=> get_the_ID(),
				'id' 		=> $office_id, 
				'email'   	=> $email,
				'message' 	=> $message, 
				'type'		=> 'office',
		);
		$heading  	 = __( 'Contact Office', 'opalestate' );
		$author_info =  OpalEstate_Office::render_box_info( $office_id  );
		$form 	  	 = apply_filters( 'opalestate_render_contact_form', Opalestate_Template_Loader::get_template_part( 'parts/contact-form', $args ), $args );

	else:

		$contact_id = (int)Opalestate_Query::get_agent_by_property( $post->ID );
		$contact_id = $contact_id ? $contact_id  : $agent_id; 
 		
 		if( $contact_id ):  
		 	$email 		 = get_post_meta( $contact_id, OPALESTATE_AGENT_PREFIX . 'email', true );
		 	
		 	$args   = array( 
				'post_id' 	=> get_the_ID(),
				'id'  		=> $contact_id, 
				'email'   	=> $email,
				'message' 	=> $message, 
				'type'		=> 'agent'
			);

		 	$form 	  	 = apply_filters( 'opalestate_render_contact_form', Opalestate_Template_Loader::get_template_part( 'parts/contact-form', $args ), $args );
		 	$heading 	 = __( 'Contact Agent', 'opalestate' ); 
		 	$author_info =  OpalEstate_Agent::render_box_info( $contact_id  );
		else :  
			$email 		 = get_user_meta( $post->post_author, OPALESTATE_USER_PROFILE_PREFIX . 'email', true );  
			
		 	$email  	 = $email ? $email : $data->data->user_email;
			
			$args   = array( 
				'post_id' 	=> get_the_ID(),
				'id'  		=> $post->post_author, 
				'email'   	=> $email,
				'message' 	=> $message, 
				'type'		=> 'user'
			);

			$heading 	 = _e( 'Contact Author', 'opalestate' ); 
			$author_info = Opalestate_Template_Loader::get_template_part( 'parts/author-box', array('author' => $data , 'hide_description' => true ) );
			$form 	  	 = apply_filters( 'opalestate_render_contact_form', Opalestate_Template_Loader::get_template_part( 'parts/contact-form', $args ), $args );
		endif; ?>
	<?php endif; ?>
	<div class="opalestate-box property-agent-section">
		<h3><?php echo $heading; ?></h3>
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<div class="col-lg-7 property-agent-info">
				<?php echo $author_info; ?>
			</div>
			<div class="col-lg-5 property-agent-contact">
				<?php echo $form; ?> 
			</div>	
		</div>	
	</div>
<?php endif; ?>

