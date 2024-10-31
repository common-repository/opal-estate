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
if ( ! session_id() ) {
	@session_start();
}

/**
 * @class OpalEstate_Submission
 *
 * @version 1.0
 */
class OpalEstate_Submission{

	/**
	 * Shortcodes
	 */
	static $shortcodes;

	/**
	 *
	 */
	public static function init(){

	 	self::$shortcodes = array(
	 		'submission' 		   	=> array(
	 			'code' 			=> 'submission',
	 			'label'			=> __( 'Submission Form', 'opalestate' )
	 		),
	 		'submission_list' 		=> array(
	 			'code' 			=> 'submission_list',
	 			'label' 		=> __( 'My Properties', 'opalestate' )
	 		),
	 		'submission_require_login' 		=> array(
	 			'code' 			=> 'submission_require_login',
	 			'label' 		=> __( 'Submission Require Login', 'opalestate' )
	 		),
	 	);

	 	foreach( self::$shortcodes as $shortcode ){  
	 		add_shortcode( 'opalestate_' . $shortcode['code'], array( __CLASS__, $shortcode['code'] ) );
	 	}

	 	/**
	 	 * Can not use self::is_submission_page() || use 'wp_enqueue_scripts' here
	 	 * because inside this hook global $post == null
	 	 */
	 	add_action( 'wp_head', array( __CLASS__, 'head_check_page' ) );

	 	add_filter( 'pre_get_posts', array( __CLASS__, 'show_current_user_attachments') );

	 	add_action( 'init', array( __CLASS__, 'process_submission' ), 10000 );

	 	add_action( 'opalestate_single_property_before', array(__CLASS__,'render_button_edit') );

	 	if( is_admin() ){
	 		add_filter( 'opalestate_settings_tabs',  array(__CLASS__,'setting_content_tab')  );
	 		add_filter( 'opalestate_registered_submission_page_settings',  array(__CLASS__,'setting_content_fields')  );
	 	}

	 

		
	}



	public static function setting_content_tab( $tabs ){ 

		$tabs['submission_page'] =	__( 'Submission Page', 'opalestate' );
		return $tabs; 
	}

	public static function setting_content_fields( $fields=array() ){
			$fields = array(
			'id'         => 'submission_page',
			'title' => __( 'Email Settings', 'opalestate' ),
			'show_on'    => array( 'key' => 'options-page', 'value' => array( 'opalestate_settings' ), ),
			'fields'     => apply_filters( 'opalestate_settings_submission', array(
					array(
						'name' => __( 'Submission page Settings', 'opalestate' ),
						'desc' => '<hr>',
						'id'   => 'opalestate_title_email_settings_1',
						'type' => 'title'
					),
					array(
						'name'    => __( 'Property Submission Page', 'opalestate' ),
						'desc'    => __( 'This is the submission page. The <code>[opalestate_submission]</code> shortcode should be on this page.', 'opalestate' ),
						'id'      => 'submission_page',
						'type'    => 'select',
						'options' => opalestate_cmb2_get_post_options( array(
							'post_type'   => 'page',
							'numberposts' => - 1
						) ),
					),
					array(
						'name'    => __( 'Property Listings Page', 'opalestate' ),
						'desc'    => __( 'This is the submission list page with logined user. The <code>[opalestate_submission_list]</code> shortcode should be on this page.', 'opalestate' ),
						'id'      => 'submission_list_page',
						'type'    => 'select',
						'options' => opalestate_cmb2_get_post_options( array(
							'post_type'   => 'page',
							'numberposts' => - 1
						) ),
					),
					array(
						'name'    => __( 'Enable Admin Approve', 'opalestate' ),
						'desc'    => __( 'the Property will be auto approve when user submit, if you do not enable it.', 'opalestate' ),
						'id'      => 'admin_approve',
						'type'    => 'checkbox'
					),
					array(
						'name'    => __( 'Enable Require Price', 'opalestate' ),
						'desc'    => __( 'Enable or Disable require user enter price and price label.', 'opalestate' ),
						'id'      => 'require_input_price',
						'type'    => 'checkbox'
						 
					),
					array(
						'id'      	=> 'submission_warning_content',
						'name'    	=> __( 'Content Show User Not Login', 'opalestate' ),
						'type'    	=> 'wysiwyg',
						'desc'		=> __( 'Enter the email a user should receive when they make an initial payment request.', 'opalestate' ),
						'default' 	=> trim(preg_replace('/\t+/', '', " 
							<h3>Login to your account</h3> <br>
							Logining in allows you to edit your property or submit a property, save favorite real estate
							[opalmembership_login_form]
						 "))
					)
				)
			)
		);
	//		echo '<pre>'.print_r( $fields ,1 );die;
		return $fields;
	}

	public static function head_check_page() {
	 	if( self::is_submission_page() ){
	 		self::load_scripts();
	 	}
	}

	public static function render_button_edit(){

		global $post, $current_user;
		wp_get_current_user();

		if(  $current_user->ID == $post->post_author ){
			echo '<div class="property-button-edit">
				<a href="'.opalestate_submssion_page( $post->ID ).'">'.__( 'Edit My Property', 'opalestate' ).'</a>
				</div>';
		}
	}

	/* is submission page. 'submission_page' option in General Setting */
 	public static function is_submission_page(){
 		global $post;
 		if ( ! $post || ! isset( $post->ID ) || ! $post->ID ) { return false; }
 		return opalestate_get_option( 'submission_page' ) == $post->ID;
 	}

	/**
	 * FrontEnd Submission
	 */
	public static function show_current_user_attachments( $wp_query_obj ) {

	    global $current_user, $pagenow;

	    if( ! is_a( $current_user, 'WP_User' ) )
	        return;

	    if( ! in_array( $pagenow, array( 'upload.php', 'admin-ajax.php' ) ))
	        return;

	    if( ! empty($current_user->roles) ){
	    	if( in_array( 'opalestate_agent', $current_user->roles ) ){
	    		$wp_query_obj->set( 'author', $current_user->ID );
	    	}
	    }
	    return;
	}

	/**
	 * FrontEnd Submission
	 */
	public static function submission(){
		global $current_user;

		if ( ! is_user_logged_in() ) { 
		    echo Opalestate_Template_Loader::get_template_part( 'parts/submission-warning' );
		    return;
	    }

		$metaboxes = apply_filters( 'cmb2_meta_boxes', array() );

		if ( ! isset( $metaboxes[ OPALESTATE_PROPERTY_PREFIX . 'front' ] ) ) {
			return __( 'A metabox with the specified \'metabox_id\' doesn\'t exist.', 'opalestate' );
		}

		// CMB2 is getting fields values from current post what means it will fetch data from submission page
		// We need to remove all data before.
		$post_id = ! empty( $_GET['id'] ) ? absint( $_GET['id'] ) : false;
		if ( ! $post_id ) {
			unset( $_POST );
			foreach ( $metaboxes[ OPALESTATE_PROPERTY_PREFIX . 'front' ]['fields'] as $field_name => $field_value ) {
				delete_post_meta( get_the_ID(), $field_value['id'] );
			}
		}

		if ( ! empty( $post_id ) && ! empty( $_POST['object_id'] ) ) {
			$post_id = absint( $_POST['object_id'] );
			
		}

		if( $post_id && ! opalestate_is_own_property( $post_id, $current_user->ID ) ){
			echo Opalestate_Template_Loader::get_template_part( 'parts/has-warning' ); die;
		}
 		
	    return Opalestate_Template_Loader::get_template_part( 'shortcodes/submission-form', array( 'post_id' => $post_id, 'metaboxes' => $metaboxes, 'post_id' => $post_id) );
	}

	/**
	 * FrontEnd Submission
	 */
	public static function process_submission() {
		if ( ! isset( $_POST['submit-cmb'] ) && ! empty( $_POST['post_type'] ) && 'opalestate_property' == $_POST['post_type'] ) {
			return;
		}

		$edit = false;
		$blocked = OpalEstate_User::is_blocked();
		// Setup and sanitize data
		if ( isset( $_POST[ OPALESTATE_PROPERTY_PREFIX . 'title' ] ) && !$blocked ) {
			

			do_action( 'opalestate_process_submission_before' );
				
			if( isset($_POST['propperty_image_ids']) && !empty($_POST['propperty_image_ids'])  ){
				$output = array();
				foreach ($_POST['propperty_image_ids'] as $key => $value) {
					$_POST[OPALESTATE_PROPERTY_PREFIX . 'gallery'][$value] = wp_get_attachment_url($value);
					$output[] = $value;
				}

				if( !isset($_POST['featured_image_id']) || (int)$_POST['featured_image_id'] <= 0 ){ 
					$_POST[OPALESTATE_PROPERTY_PREFIX . 'featured_image'] = intval($output[0]); 
				}else {
					$_POST[OPALESTATE_PROPERTY_PREFIX . 'featured_image'] = intval($_POST['featured_image_id']);
				}
				$featured_image_id = intval($_POST[OPALESTATE_PROPERTY_PREFIX . 'featured_image']); 
			}	
	
			$post_id = ! empty( $_GET['id'] ) ? absint( $_GET['id'] ) : false;

			$review_before = opalestate_get_option( 'admin_approve' );
			
			$post_status = 'pending';

			if ( ! $review_before ) {
				$post_status = 'publish';
			}

			// If we are updating the post get old one. We need old post to set proper
			// post_date value because just modified post will at the top in archive pages.
			if ( ! empty( $post_id ) ) {
				$old_post = get_post( $post_id );
				$post_date = $old_post->post_date;
			} else {
				$post_date = '';
			}

			$user_id = get_current_user_id();
			$data = array(
				'post_title'     => sanitize_text_field( $_POST[ OPALESTATE_PROPERTY_PREFIX . 'title' ] ),
				'post_author'    => $user_id,
				'post_status'    => $post_status,
				'post_type'      => 'opalestate_property',
				'post_date'      => $post_date,
				'post_content'   => wp_kses( $_POST[ OPALESTATE_PROPERTY_PREFIX . 'text' ], '<b><strong><i><em><h1><h2><h3><h4><h5><h6><pre><code><span><p>' ),
			);
			unset(  $_POST[ OPALESTATE_PROPERTY_PREFIX . 'text' ] );
 
			if ( ! empty( $post_id ) ) {
				$edit = true;
				$data['ID'] = $post_id;
				do_action( 'opalestate_process_edit_submission_before' );
			}else{
				do_action( 'opalestate_process_add_submission_before' );
			}	

			if( empty($data['post_title']) || empty($data['post_author']) || empty($data['post_content']) ){
				$_SESSION['messages'][] = array( 'danger', __( 'An error occured when add new a property.', 'opalestate' ) );
				wp_redirect( opalestate_submssion_page() );
				exit;
			}
			

			$post_id = wp_insert_post( $data, true );
			// $agent_id = $_POST['opalestate_ppt_agent'];
			if ( ! empty( $post_id ) && ! empty( $_POST['object_id'] ) ) {
				$_POST['object_id'] = (int)$post_id;
				$metaboxes = apply_filters( 'cmb2_meta_boxes', array() );
				cmb2_get_metabox_form( $metaboxes[ OPALESTATE_PROPERTY_PREFIX . 'front' ], $post_id );

				// Create featured image
				$featured_image = get_post_meta( $post_id, OPALESTATE_PROPERTY_PREFIX . 'featured_image', true );
				if ( ! empty( $_POST[ OPALESTATE_PROPERTY_PREFIX . 'featured_image' ] ) && isset($_POST[ OPALESTATE_PROPERTY_PREFIX . 'featured_image' ]) ) {
					set_post_thumbnail( $post_id, $featured_image_id );
				} else {
					update_post_meta( $post_id, OPALESTATE_PROPERTY_PREFIX . 'featured_image', null );
					delete_post_thumbnail( $post_id );
				}
			 	
			 	

				//redirect
				$_SESSION['messages'][] = array( 'success', __( 'Property has been successfully updated.', 'opalestate' ) );

				do_action( "opalestate_process_submission_after", $user_id, $post_id, $edit );

				if( $edit ){
					do_action( "opalestate_processed_edit_submission", $user_id, $post_id );
				}else {
					do_action( "opalestate_processed_new_submission", $user_id, $post_id  );
				}

				wp_redirect( opalestate_submssion_list_page('doaction=updated') );

				exit();
			}
		}
		return;
	}

	/**
	 * FrontEnd Submission
	 */
	public static function submission_list() {
		if ( ! is_user_logged_in() ) {
		    echo Opalestate_Template_Loader::get_template_part( 'parts/not-allowed' );
		    return;
	    }

	    if ( isset($_GET['id']) && isset($_GET['remove']) ) {
	    	$is_allowed = Opalestate_Property::is_allowed_remove( get_current_user_id(), $_GET['id'] );
			if ( ! $is_allowed ) {
		        echo Opalestate_Template_Loader::get_template_part( 'parts/not-allowed' );
				return;
			}

			if ( wp_delete_post( $_GET['id'] ) ) {
				$_SESSION['messages'][] = array( 'success', __( 'Property has been successfully removed.', 'opalestate' ) );
			} else {
				$_SESSION['messages'][] = array( 'danger', __( 'An error occured when removing an item.', 'opalestate' ) );
			}

			wp_redirect( opalestate_submssion_list_page() );
	    }


	    $args = array();

	    if( isset($_GET['status']) && !empty($_GET['status']) ){
	    	$args['post_status'] = $_GET['status'];
	    }

	    $loop = Opalestate_Query::get_properties_by_user( $args, get_current_user_id() );

		echo Opalestate_Template_Loader::get_template_part( 'user/my-properties', array( 'loop' => $loop ) );
	}


	public static function submission_require_login(){
		echo Opalestate_Template_Loader::get_template_part( 'shortcodes/submission-login'  );
	}
	/**
	 *
	 */
	public static function load_scripts(){
		if ( ! is_user_logged_in() ) {	
			wp_enqueue_style( 'opalesate-submission', OPALESTATE_PLUGIN_URL . 'assets/submission.css'  );
		}

	}

}

OpalEstate_Submission::init();