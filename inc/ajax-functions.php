<?php 
/**
 * $Desc$
 *
 * @version    $Id$
 * @package    opalestate
 * @author     Opal  Team <info@wpopal.com >
 * @copyright  Copyright (C) 2016 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/support/forum.html
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if (!function_exists('opalestate_process_send_email')) {
	function opalestate_process_send_email() {

		do_action( 'opalestate_process_send_email_before' );


		$name    = sanitize_text_field( $_POST['name'] );
		$email    = sanitize_email( $_POST['email'] );
		$message  = sanitize_text_field( $_POST['message'] );
		$phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone']) : '';
		$post_id  = intval( $_POST['post_id'] );
	 	$id  	  = intval( $_POST['id'] );
		$cc_me 	  = isset( $_POST['cc_me'] ) ?  absint( $_POST['cc_me'] ) : 0;
		$type 	  =  isset( $_POST['type'] ) ?  $_POST['type']  : 'user';

		$subject = opalestate_get_option('contact_email_subject', __('You got a message', 'opalestate') );
		
		$default = trim(preg_replace('/\t+/', '', "Hi {receive_name},<br>
							You have got message from {name} with email {email}. Here is detail:
						 <br>
						<br>
						{message}
						<br>
						&nbsp;<br>
						<br>
						<em>This message was sent by {site_link} on {current_time}.</em>")); 

	 

		$from_name 	= (string)opalestate_get_option('from_name',  get_bloginfo( 'name' ) );
		$from_email = (string) opalestate_get_option('from_email' ,  get_bloginfo( 'admin_email' ) );
		$headers 	= sprintf( "From: %s <%s>\r\n Content-type: text/html", $from_name, $from_email );

	 
		$agent_email = $receive_name = '';
		
		switch ( $type ) {

			case 'office':
				$agent_email  = get_post_meta( $id, OPALESTATE_OFFICE_PREFIX . 'email', true ); 
				$agent 		  = get_post( $id );
				$receive_name = $agent->post_title;
				break;
			case 'agent':
				$agent_email  = get_post_meta( $id, OPALESTATE_AGENT_PREFIX . 'email', true );  
				$agent 		  = get_post( $id );
				$receive_name = $agent->post_title;
				break;
			case 'user':
				$user = get_user_by('id', $id );
				$agent_email = $user->data->user_email; 
				$receive_name = $user->data->display_name; 
				break;	
			default:
				# code...
				break;
		}	

		$site_link 	  = $post_id ? get_permalink( $post_id ) :  get_home_url();
		$current_time = date("F j, Y, g:i a");
		$tags 	= array("{receive_name}", "{name}", "{email}", "{property_link}", "{message}", "{property_name}", "{site_link}","{current_time}", "{phone}");
		$values = array($receive_name, $name, $email, $property_link, $message, $property_name, $site_link, $current_time, $phone );
		
		$body   = apply_filters('opalestate_email_contact_body_template', opalestate_get_option('contact_email_body', $default ), $_POST );

 		

		$subject = html_entity_decode($subject);
		$subject = str_replace($tags, $values, $subject);

		$body 	 = html_entity_decode($body);
		$message = str_replace($tags, $values, $body);

		if( $receive_name &&  $agent_email ) {

			if( $cc_me ){
				$status = @wp_mail( $email, $subject, $message, $headers );
		 	}
 	
		 	$status =  @wp_mail( $agent_email, $subject, $message, $headers );
			$return = array( 'status' => 'success', 'msg' => __( 'Message has been successfully sent.', 'opalestate' ) );
			 
			echo json_encode($return); die();
		 
		}
		$return = array( 'status' => 'danger', 'msg' => __( 'Unable to send a message.', 'opalestate' ) );
		echo json_encode($return); die();
	}
}

add_action( 'wp_ajax_send_email_contact', 'opalestate_process_send_email' );
add_action( 'wp_ajax_nopriv_send_email_contact', 'opalestate_process_send_email' );

/**
 * Share content form
 */
function opalestate_share_content_form(){

	if( is_user_logged_in() ){
		$return = array( 'status' => 'danger', 'msg' => __( 'Unable to send a message.', 'opalestate' ) );
	}

	$friend_emails = $_POST['friend_email']; 

	$name     = sanitize_text_field( $_POST['name'] );
	$email    = sanitize_email( $_POST['email'] );
	$message  = sanitize_text_field( $_POST['message'] );
	$search_link = sanitize_text_field( $_POST['uri'] );
	$headers 	= sprintf( "From: %s <%s>\r\n Content-type: text/html", $name, $email );


	$default = trim(preg_replace('/\t+/', '', "You have got message from {name} with email {email}. Here is detail:
						 <br>
						<br>
						{message}
						<br>
						{search_link}
						&nbsp;<br>
						<br>
						<em>This message was sent by {site_link} on {current_time}.</em>")); 

	$site_link 	  = get_home_url();
	$current_time = date("F j, Y, g:i a");
	$tags 	= array(  "{name}", "{email}", "{search_link}", "{message}",  "{site_link}","{current_time}");
	$values = array(  $name, $email, $search_link, $message, $site_link, $current_time );
	
	$body   = apply_filters('opalestate_email_contact_body_template', opalestate_get_option('share_content_body', $default ), $_POST );


	$subject = sprintf( __('You sent a message from %s', 'opalestate'), $name ); 
	$subject = html_entity_decode($subject);
	$subject = str_replace($tags, $values, $subject);

	$body 	 = html_entity_decode($body);
	$message = str_replace($tags, $values, $body);


	if(   !empty($friend_emails) && is_array($friend_emails) ) {

	 	$friend_emails = array_chunk( $friend_emails,  5 );
	  	$friend_emails = $friend_emails[0];
	  	foreach ( $friend_emails as $key => $friend_email) {
  			$status = @wp_mail( $friend_email, $subject, $message, $headers );
	  	}
	 
		$return = array( 'status' => 'success', 'msg' => __( 'Message has been successfully sent.', 'opalestate' ) );
		echo json_encode($return); die();
	 
	}

	echo json_encode( $return ); die();
}

add_action( 'wp_ajax_share_content_form', 'opalestate_share_content_form' );
add_action( 'wp_ajax_nopriv_share_content_form', 'opalestate_share_content_form' );


/* set feature property */
add_action( 'wp_ajax_opalestate_set_feature_property', 'opalestate_set_feature_property' );
add_action( 'wp_ajax_nopriv_opalestate_set_feature_property', 'opalestate_set_feature_property' );
if ( ! function_exists( 'opalestate_set_feature_property' ) ) {
	function opalestate_set_feature_property() {

		if ( ! isset( $_REQUEST['nonce'] ) && ! wp_verify_nonce( $_REQUEST['nonce'], 'nonce' ) ) return;
		if ( ! isset( $_REQUEST['property_id'] ) ) return;
		update_post_meta( absint( $_REQUEST['property_id'] ), OPALESTATE_PROPERTY_PREFIX . 'featured', 1 );

		wp_redirect( admin_url( 'edit.php?post_type=opalestate_property' ) ); exit();
	}
}
/* remove feature property */
add_action( 'wp_ajax_opalestate_remove_feature_property', 'opalestate_remove_feature_property' );
add_action( 'wp_ajax_nopriv_opalestate_remove_feature_property', 'opalestate_remove_feature_property' );
if ( ! function_exists( 'opalestate_remove_feature_property' ) ) {
	function opalestate_remove_feature_property() {
		if ( ! isset( $_REQUEST['nonce'] ) && ! wp_verify_nonce( $_REQUEST['nonce'], 'nonce' ) ) return;

		if ( ! isset( $_REQUEST['property_id'] ) ) return;

		update_post_meta( absint( $_REQUEST['property_id'] ), OPALESTATE_PROPERTY_PREFIX . 'featured', '' );
		wp_redirect( admin_url( 'edit.php?post_type=opalestate_property' ) ); exit();
	}
}

/**
 * Set Featured Item Following user
 */
add_action( 'wp_ajax_opalestate_toggle_featured_property', 'opalestate_toggle_featured_property' );
add_action( 'wp_ajax_nopriv_opalestate_toggle_featured_property', 'opalestate_toggle_featured_property' );

function opalestate_toggle_featured_property(){
	 	
 	global $current_user;
    wp_get_current_user();
    $user_id =   $current_user->ID;

    $property_id = intval( $_POST['property_id'] );
    $post = get_post( $property_id );

    if( $post->post_author == $user_id ) {
   
      	$check = apply_filters( 'opalestate_set_feature_property_checked', false );
        if( $check ) {
            do_action( 'opalestate_toggle_featured_property_before', $user_id, $property_id );
            update_post_meta( $property_id, OPALESTATE_PROPERTY_PREFIX . 'featured', 1 );
            echo json_encode( array( 'status' => true, 'msg' => __('Could not set this as featured','opalestate') ) );
            wp_die();
        } 
    }  

    echo json_encode( array( 'status' => false, 'msg' => __('Could not set this as featured','opalestate') ) );
    wp_reset_query();
    wp_die();
 
}


/**
 * load more properties by office
 */
add_action( 'wp_ajax_get_office_property', 'opalestate_load_more_office_property' );
add_action( 'wp_ajax_nopriv_get_office_property', 'opalestate_load_more_office_property' );

function opalestate_load_more_office_property(){  

 	
 	$post = array(
 		'post_id' => 0,
 		'paged'   => 1,
 		'user_id' => 13,
 		'related' => '',
 		'limit'  => apply_filters( 'opalesate_office_properties_limit', 5 ) 
 	);

 	$post = array_merge( $post, $_POST );
 	extract( $post );

 	$user_id   = get_post_meta( $post_id, OPALESTATE_OFFICE_PREFIX . 'user_id', true ); 
 	$query 	   = Opalestate_Query::get_office_property( $post_id, $user_id , $limit , $paged );
	
	if( $query->have_posts() ) :
		while( $query->have_posts() ) : $query->the_post();  ?>
		<div class="col-lg-12 col-md-12 col-sm-12">
	  		 <?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list-v2' ); ?>
	  	</div> 
	<?php	endwhile; 
	endif; 	
	wp_reset_postdata();
	exit;
}


/**
 * load more properties by office
 */
add_action( 'wp_ajax_get_agent_property', 'opalestate_get_agent_property' );
add_action( 'wp_ajax_nopriv_get_agent_property', 'opalestate_get_agent_property' );

function opalestate_get_agent_property(){  

 	global $paged;
 	$post = array(
 
 		'paged'   => 1,
 		'id' => 13,
 		'limit'  => apply_filters( 'opalesate_agent_properties_limit', 1 ) 
 	);

 	$post = array_merge( $post, $_POST );
 	extract( $post );
 
 	set_query_var( 'paged', $post['paged'] );
	$query = Opalestate_Query::get_agent_property( null, $post['id'], $limit );
		
 	$paged = $post['paged']; 
	if( $query->have_posts() ) :  ?>
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
	<?php 
	endif; 	
	wp_reset_postdata();
	exit;
}