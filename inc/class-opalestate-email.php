<?php
/**
 * $Desc$
 *
 * @version    $Id$
 * @package    $package$
 * @author     Opal  Team <info@wpopal.com >
 * @copyright  Copyright (C) 2014 wpopal.com. All Rights Reserved.
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * @website  http://www.wpopal.com
 * @support  http://www.wpopal.com/support/forum.html
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * @class OpalMembership_Checkout
 *
 * @version 1.0
 */
class Opalestate_Emails {

	
	/**
	 * init action to automatic send email when user edit or submit a new submission and init setting form in plugin setting of admin
	 */
	public static function init() {

	  	add_action(  'opalestate_processed_new_submission' , array( __CLASS__ , 'new_submission_email'), 10, 2 );
	  	//add_action(  'opalestate_processed_edit_submission' , array( __CLASS__ , 'new_submission_email'), 10, 2 );
	  	if( is_admin() ){
	  		add_filter( 'opalestate_settings_tabs', array( __CLASS__, 'setting_email_tab'), 1 );
	  		add_filter( 'opalestate_registered_emails_settings', array( __CLASS__, 'setting_email_fields'), 10, 1   );
	  	}

        $enable_approve_property_email = opalestate_get_option( 'enable_approve_property_email' );

	  	if ( $enable_approve_property_email == 'on' ) {
            add_action(  'transition_post_status', array( __CLASS__, 'send_email_when_publish_property') , 10, 3 );
            add_action(  'opalestate_processed_approve_publish_property', array( __CLASS__, 'approve_publish_property_email') , 10, 1 );
        }

	}

    public static function send_email_when_publish_property( $new_status, $old_status, $post ) {

	    if( is_object( $post ) ){
            if ( $post->post_type == 'opalestate_property' ) {
                if ( $new_status != $old_status ) {
                    if ($new_status == 'publish' ) {
                        if ($old_status == 'draft' || $old_status == 'pending') {
                            // Send email
                            $post_id = $post->ID;
                            do_action( "opalestate_processed_approve_publish_property", $post_id  );
                        }
                    }
                }
            }
        }

    }

	/**
	 * add new tab Email in opalestate -> setting
	 */
	public static function setting_email_tab( $tabs ){

		$tabs['emails'] = __( 'Email', 'opalestate' );
		return $tabs;
	}	

	public static function newproperty_email_body(){
		return trim(preg_replace('/\t+/', '','
						Hi {user_name},
						<br>
						Thanks you so much for submitting {property_name}  at  {site_name}:<br>
						 Give us a few moments to make sure that we are got space for you. You will receive another email from us soon.
						 If this request was made outside of our normal working hours, we may not be able to confirm it until we are open again.
						<br>
							You may review your property at any time by logging in to your client area.
						<br>
						<em>This message was sent by {site_link} on {current_time}.</em>'))	
		;
	}
 	
 	public static function approve_email_body(){
 		return trim(preg_replace('/\t+/', '', "Hi {user_name},<br>
				<br>
				Thank you so much for submitting to {site_name}.
				<br>
				 We have completed the auditing process for your property '{property_name}'  and are pleased to inform you that your submission has been accepted.
				 <br>
				<br>
				Thanks again for your contribution
				<br>
				&nbsp;<br>
				<br>
				<em>This message was sent by {site_link} on {current_time}.</em>"));
	 

 	}		

	/**
	 * render setting email fields with default values
	 */
	public static function setting_email_fields( $fields ){ 

		$contact_list_tags = '<td>
				<p class="tags-description">Use the following tags to automatically add property information to the emails. Tags labeled with an asterisk (*) can be used in the email subject as well.</p>
				
				<div class="rtb-template-tags-box">
					<strong>{receive_name}</strong> Name of the agent who made the property
				</div>

				<div class="rtb-template-tags-box">
					<strong>{property_link}</strong> Property of the user who made the property
				</div>
	
				<div class="rtb-template-tags-box">
					<strong>{name}</strong> Name of the user who contact via email form
				</div>

				<div class="rtb-template-tags-box">
					<strong>{email}</strong> Email of the user who contact via email form
				</div>

				<div class="rtb-template-tags-box">
					<strong>{property_link}</strong> * Link of the property
				</div>
			
				<div class="rtb-template-tags-box">
					<strong>{message}</strong> * Message content of who sent via form
				</div>

				</td>';

		$list_tags = '<td>
				<p class="tags-description">Use the following tags to automatically add property information to the emails. Tags labeled with an asterisk (*) can be used in the email subject as well.</p>
				
				<div class="rtb-template-tags-box">
					<strong>{property_name}</strong> Email of the user who made the property
				</div>

				<div class="rtb-template-tags-box">
					<strong>{property_link}</strong> Email of the user who made the property
				</div>
	
				<div class="rtb-template-tags-box">
					<strong>{user_email}</strong> Email of the user who made the property
				</div>

				<div class="rtb-template-tags-box">
					<strong>{submitted_date}</strong> Email of the user who made the property
				</div>

				<div class="rtb-template-tags-box">
					<strong>{user_name}</strong> * Name of the user who made the property
				</div>
			
				<div class="rtb-template-tags-box">
					<strong>{date}</strong> * Date and time of the property
				</div>

				<div class="rtb-template-tags-box">
					<strong>{site_name}</strong> The name of this website
				</div>
				<div class="rtb-template-tags-box">
					<strong>{site_link}</strong> A link to this website
				</div>
				<div class="rtb-template-tags-box">
					<strong>{current_time}</strong> Current date and time
				</div></td>';

		$list_tags = apply_filters( 'opalestate_email_tags', $list_tags );
				

		$fields = array(
			'id'         => 'options_page',
			'title' => __( 'Email Settings', 'opalestate' ),
			'show_on'    => array( 'key' => 'options-page', 'value' => array( 'opalestate_settings' ), ),
			'fields'     => apply_filters( 'opalestate_settings_emails', array(
					array(
						'name' => __( 'Email Settings', 'opalestate' ),
						'desc' => '<hr>',
						'id'   => 'opalestate_title_email_settings_1',
						'type' => 'title'
					),
					array(
						'id'      => 'from_name',
						'name'    => __( 'From Name', 'opalestate' ),
						'desc'    => __( 'The name donation receipts are said to come from. This should probably be your site or shop name.', 'opalestate' ),
						'default' => get_bloginfo( 'name' ),
						'type'    => 'text'
					),
					array(
						'id'      => 'from_email',
						'name'    => __( 'From Email', 'opalestate' ),
						'desc'    => __( 'Email to send donation receipts from. This will act as the "from" and "reply-to" address.', 'opalestate' ),
						'default' => get_bloginfo( 'admin_email' ),
						'type'    => 'text'
					),

				

			 		array(
						'name' => __( 'Email Submission Templates (Template Tags)', 'opalestate' ),
						'desc' => $list_tags.'<br><hr>',
						'id'   => 'opalestate_title_email_settings_2',
						'type' => 'title'
					),

				

					array(
						'name' => __( 'Notification For New Property Submission', 'opalestate' ),
						'desc' => '<hr>',
						'id'   => 'opalestate_title_email_settings_3',
						'type' => 'title'
					),
				

					array(
						'id'      			=> 'newproperty_email_subject',
						'name'    			=> __( 'Email Subject', 'opalestate' ),
						'type'    			=> 'text',
						'desc'				=> __( 'The email subject for admin notifications.', 'opalestate' ),
						'attributes'  		=> array(
	        										'placeholder' 		=> 'Your package is expired',
	        										'rows'       	 	=> 3,
	    										),
						'default'			=> __( 'New property submitted - {property_name}', 'opalestate' )	

					),
					array(
						'id'      => 'newproperty_email_body',
						'name'    => __( 'Email Body', 'opalestate' ),
						'type'    => 'wysiwyg',
						'desc'	=> __( 'Enter the email an admin should receive when an initial payment request is made.', 'opalestate' ),
						'default' => self::newproperty_email_body(),
					),	
					//------------------------------------------
					array(
						'name' => __( 'Approve property for publish', 'opalestate' ),
						'desc' => '<hr>',
						'id'   => 'opalestate_title_email_settings_4',
						'type' => 'title'
					),
                    array(
                        'name'    		=> __( 'Enable approve property email', 'opalestate' ),
                        'desc'			=> __( 'Enable approve property email.', 'opalestate' ),
                        'id'      => 'enable_approve_property_email',
                        'type'    => 'select',
                        'options' => array(
                            'on' 	=> __( 'Enable', 'opalestate' ),
                            'off'   => __( 'Disable', 'opalestate' ),
                        ),
                        'default' => 'off',
                    ),
					array(
						'id'      		=> 'approve_email_subject',
						'name'    		=> __( 'Email Subject', 'opalestate' ),
						'type'    		=> 'text',
						'desc'			=> __( 'The email subject a user should receive when they make an initial property request.', 'opalestate' ),
						'attributes'  	=> array(
	        									'placeholder' 		=> 'Your property at I Love WordPress is pending',get_bloginfo( 'name' ),
	        									'rows'       	 	=> 3,
	    									),
						'default'	=> __('Approve For Publish - {property_name}','opalestate')
					),
					array(
						'id'      	=> 'approve_email_body',
						'name'    	=> __( 'Email Body', 'opalestate' ),
						'type'    	=> 'wysiwyg',
						'desc'		=> __( 'Enter the email a user should receive when they make an initial payment request.', 'opalestate' ),
						'default' 	=> self::approve_email_body(),
					),	
					/// email contact template ////
					array(
						'name' => __( 'Email Contact Templates (Template Tags)', 'opalestate' ),
						'desc' => $contact_list_tags.'<br><hr>',
						'id'   => 'opalestate_title_email_settings_6',
						'type' => 'title'
					),
					array(
						'id'      		=> 'contact_email_subject',
						'name'    		=> __( 'Email Subject', 'opalestate' ),
						'type'    		=> 'text',
						'desc'			=> __( 'The email subject a user should receive when they make an initial property request.', 'opalestate' ),
						'attributes'  	=> array(
	        									'placeholder' 		=> 'Your property at I Love WordPress is pending',get_bloginfo( 'name' ),
	        									'rows'       	 	=> 3,
	    									),
						'default'	=> __('You got a message', 'opalestate') 
					),
					array(
						'id'      	=> 'contact_email_body',
						'name'    	=> __( 'Email Body', 'opalestate' ),
						'type'    	=> 'wysiwyg',
						'desc'		=>  trim(preg_replace('/\t+/', '', "Hi {receive_name},<br>
							You have got message from {name} with email {email}. Here is detail:
						 <br>
						<br>
						{message}
						<br>
						&nbsp;<br>
						<br>
						<em>This message was sent by {site_link} on {current_time}.</em>"))
					),
				)
			)
		);
		return $fields;
	}

	/**
	 * get data of newrequest email
	 *
	 * @var $args  array: property_id , $body 
	 * @return text: message
	 */
	public static function replace_shortcode( $args, $body ) {

		$tags =  array(
			'user_name' 	=> "",
			'user_mail' 	=> "",
			'submitted_date' => "",
			'property_name' => "",
			'site_name' => '',
			'site_link'	=> '',
			'property_link' => '',
		);
		$tags = array_merge( $tags, $args );

		extract( $tags );

		$tags 	 = array( "{user_mail}",
						  "{user_name}",
						  "{submitted_date}",
						  "{site_name}",
						  "{site_link}",
						  "{current_time}",
						  '{property_name}',
						  '{property_link}');

		$values  = array(   $user_mail, 
							$user_name ,
							$submitted_date ,
							get_bloginfo( 'name' ) ,
							get_home_url(), 
							date("F j, Y, g:i a"),
							$property_name,
							$property_link
		);

		$message = str_replace($tags, $values, $body);

		return $message;
	}

	/**
	 * general function to send email to agent with specify subject, body content
	 */
	public static function send( $emailto, $subject, $body ){

		$from_name 	= opalestate_get_option('from_name');
		$from_email = opalestate_get_option('from_email');
		$headers 	= sprintf( "From: %s <%s>\r\n Content-type: text/html", $from_name, $from_email );

		if( empty($emailto) || empty( $subject) || empty($body) ){
			return ;
		}

		wp_mail( @$emailto, @$subject, @$body, $headers );

	}

	/**
	 * get collection of key and value base on tags which using to replace custom tags
	 */
	public static function get_email_args_by_property(  $property_id ){
	 	
	 	$property 	   = get_post( $property_id );
		$user    	   = get_userdata( $property->post_author ); 
		$email 		   = get_user_meta( $property->post_author, OPALESTATE_USER_PROFILE_PREFIX . 'email', true ); 
 		$email  	   = $email ? $email : $user->data->user_email;

		$args = array(
			'user_mail' 		 => $email,
			'user_name'			 => $user->display_name,
			'submitted_date'	 => $property->post_date,
			'property_name'	 	 => $property->post_title,
			'property_link'		 => get_permalink( $property_id )
		); 

		return $args ;
	}

	/**
	 * send email if agent submit a new property
	 */
	public static function new_submission_email( $user_id,  $post_id ){

		 
		$args = self::get_email_args_by_property( $post_id );

		$subject = opalestate_get_option( 'newproperty_email_subject' );
		$body 	 = opalestate_get_option( 'newproperty_email_body', self::newproperty_email_body() );

		// repleace all custom tags
		$subject = self::replace_shortcode( $args, $subject );
		$body 	 = self::replace_shortcode( $args, $body );
 
		self::send( $args['user_mail'], $subject, $body );
	}


	public static function approve_publish_property_email( $post_id ) {

        $args = self::get_email_args_by_property( $post_id );

        $subject = opalestate_get_option( 'approve_email_subject' );
        $body 	 = opalestate_get_option( 'approve_email_body', self::approve_email_body() );

        // repleace all custom tags
        $subject = self::replace_shortcode( $args, $subject );
        $body 	 = self::replace_shortcode( $args, $body );

        self::send( $args['user_mail'], $subject, $body );

    }

}

Opalestate_Emails::init();