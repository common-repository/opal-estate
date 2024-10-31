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
 
/**
 * @class OpalEstate_Nocaptcha_Recaptcha
 *
 * @version 1.0
 */
class OpalEstate_Nocaptcha_Recaptcha{
	 
	/**
	 *
	 */
	public function __construct(){
		
		

		if( is_admin() ){

			add_filter( 'opalestate_settings_tabs',  array( $this,'admin_tab_setting')  );
	 		add_filter( 'opalestate_registered_google_captcha_page_settings',  array( $this,'admin_content_setting')  );
		} 

		if( opalestate_options('show_captcha') == 'on'  ){	 
			define( 'WPOPAL_CAPTCHA_LOAED', true );
			$this->theme 	= opalestate_options( 'captcha_theme' ,'light' ); 
			add_action('wp_head',  array( $this, 'add_custom_styles') );		
			
	 	
			
			add_action('opalestate_agent_contact_form_after' , array( __CLASS__, 'show_captcha') );
			add_action( 'opalestate_process_send_email_before', array( __CLASS__ ,'ajax_verify_captcha') );
		}
	}

	/**
	 *
	 */
	public function add_custom_styles(){
		$lang = null;
		echo '<script src="https://www.google.com/recaptcha/api.js' . $lang . '" async defer></script>' . "\r\n";
	}

	/**
	 *
	 */
	public static function show_captcha(){

		if ( isset( $_GET['captcha'] ) && $_GET['captcha'] == 'failed' ) {
			 
		}
		echo '<div style="transform:scale(0.77);-webkit-transform:scale(0.77);transform-origin:0 0;-webkit-transform-origin:0 0;" class="g-recaptcha" data-sitekey="' . opalestate_options( 'site_key' ) . '" data-theme="' . opalestate_options( 'captcha_theme' ,'light' ) . '"></div>';
	
	}

	/**
	 *
	 */	
	public static function ajax_verify_captcha(){


		$response = isset( $_POST['g-recaptcha-response'] ) ? esc_attr( $_POST['g-recaptcha-response'] ) : '';

		$remote_ip = $_SERVER["REMOTE_ADDR"];

		// make a GET request to the Google reCAPTCHA Server
		$request = wp_remote_get(
			'https://www.google.com/recaptcha/api/siteverify?secret=' .opalestate_options( 'secret_key' )   . '&response=' . $response . '&remoteip=' . $remote_ip
		);

		// get the request response body
		$response_body = wp_remote_retrieve_body( $request );

		$result = json_decode( $response_body, true );
 		
 		if( isset($result['hostname'])  && !empty($result['hostname']) && empty($result['success']) ){
 			$result['success'] = 1;
 		} 
 		if( !$result['success'] ){
 			$return = array( 'status' => false , 'msg' => __( 'Please try to enter captcha.', 'opalestate' ) );	
 			echo json_encode($return); die();
 		}
	}

	/**
	 *
	 */
	public function admin_content_setting( $fields ){
		$fields = array(
			'id'         => 'google_captcha_page',
			'title' 	 => __( 'Google Captcha Settings', 'opalestate' ),
			'show_on'    => array( 'key' => 'options-page', 'value' => array( 'opalestate_settings' ) ),
			'fields'     => apply_filters( 'opalestate_settings_google_captcha', array(

					array(
						'name'    => __( 'Show Captcha In Form', 'opalestate' ),
						'desc'    => __( 'Enable google captch in contact , register form.. After Set yes, you change setting in Google Captcha Tab', 'opalestate' ),
						'id'      => 'show_captcha',
						'type'    => 'select',
						'options' => array(
							'off' 	=> __( 'No', 'opalestate' ),
							'on'   => __( 'Yes', 'opalestate' ),
						),
						'default' => 'on',
					),
					
					array(
						'name' => __( 'Google Captcha page Settings', 'opalestate' ),
						'desc' => '<hr>',
						'id'   => 'opalestate_title_google_captcha_settings_1',
						'type' => 'title'
					),

					array(
						'name'    => __( 'Site Key', 'opalestate' ),
						'desc'    => __( 'Used for displaying the CAPTCHA. Grab it %s', 'opalestate' ),
						'id'      => 'site_key',
						'type'    => 'text'
					),

					array(
						'name'    => __( 'Secret key', 'opalestate' ),
						'desc'    => __( 'Used for communication between your site and Google. Grab it.', 'opalestate' ),
						'id'      => 'secret_key',
						'type'    => 'text'
					),

					array(
						'name'    => __( 'Theme', 'opalestate' ),
						'desc'    => __( 'Display captcha box with color style.', 'opalestate' ),
						'id'      => 'captcha_theme',
						'type'    => 'select',
						'options' =>  array(
							'light' => __( 'Light', 'opalestate' ),
							'dark'  => __( 'Dark', 'opalestate' ),
						) ,
					)
				 
				)
			)
		);
  
		return $fields;
	}

	/**
	 *
	 */
	public function admin_tab_setting( $tabs ){

		$tabs['google_captcha_page'] =	__( 'Google Captcha', 'opalestate' );

		return $tabs; 
	}
}

new OpalEstate_Nocaptcha_Recaptcha();