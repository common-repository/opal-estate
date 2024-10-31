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
class OpalEstate_User_Search{

	/**
	 *
	 */
	protected $user_id = 0; 

	/**
	 *
	 */
	public static function getInstance(){
		static $_instance; 
		if( !$_instance ){
			$_instance = new self(); 
		}
		return $_instance;
	}
	
	/**
	 *
	 */	
	public function __construct(){

		
 	
		add_action( 'init',  array($this,'init') );
	}

	/**
	 * Set values when user logined in system
	 */
	public function init(){

		global $current_user;
		wp_get_current_user();
        $this->user_id =  $current_user->ID;

        add_filter( 'opalestate_management_user_menu', array( $this, 'dashboard_menu' )  );
		add_action( 'wp_ajax_opalestate_ajx_save_search', array($this,'do_save') );
		add_action( 'wp_ajax_nopriv_opalestate_ajx_save_search', array($this,'do_save') );

		add_shortcode( 'opalestate_user_saved_search' , array( $this,  'savedsearch_page' ) );
 		
		if( is_admin() ){

		}
	}

	public function add_setting( $setting ){

		$setting[] =  array(
			'name'    => __( 'Enable User Saved Search', 'opalestate' ),
			'desc'    => __( 'Display Save Search Link Management.', 'opalestate' ),
			'id'      => 'enable_saved_usersearch',
			'type'    => 'select',
			'options' => array(
				'on' 	=> __( 'With Members In Office', 'opalestate' ),
				'off'   => __( 'Only In Office', 'opalestate' ),
			),
			'default' => 'on',
		); 
		$setting[] =  array(
			'name'    => __( 'Saved Link Page', 'opalestate' ),
			'desc'    => __( 'Display Save Search Link Management. This is page to display form for editing user information. The [opalestate_user_saved_search] shortcode should be on this page.', 'opalestate' ),
			'id'      => 'saved_link_page',
			'type'    => 'select',
			'options' => array(
				'on' 	=> __( 'With Members In Office', 'opalestate' ),
				'off'   => __( 'Only In Office', 'opalestate' ),
			),
			'default' => 'on',
		); 
		return $setting; 
	}

	/**
	 *
	 */
 	public function get_search_by_code( $code ){

        global $wpdb;
       
        $query = " SELECT * FROM ".$wpdb->prefix."opalestate_usersearch WHERE code like %s  ";
 
        $items = $wpdb->get_results( $wpdb->prepare( $query, $code) );

        if( isset($items[0]) ){
            return $items[0];
        }
        return false;
    }

    /**
	 *
	 */
    public function has_existed( $params ){
    	return $this->get_search_by_code( md5($params) );
    }

	/**
	 *
	 */
	public function insert( $data ){
		global $wpdb;

		$args = array(
            'name' 	  => '' ,
            'params'  => '' ,
            'code'	  => '', 
            'user_id' => $this->user_id 
        );
        
        $args = array_merge( $args , $data ); 
        $args['code'] = md5( $data['params'] );
 		
        $id = $wpdb->insert( $wpdb->prefix.'opalestate_usersearch', $args );

        return $id; 
	}

	/**
	 *
	 */
	public static function install(){
	    try {
	        if ( ! function_exists('dbDelta') ) {
	            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	        }

	        global $wpdb;

	        $charset_collate = $wpdb->get_charset_collate();

	        $sql = 'CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'opalestate_usersearch' . ' (
						id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
						name VARCHAR(255),
						params VARCHAR(255),
						code VARCHAR(255),
						user_id INT(11) DEFAULT 0
					) ' . $charset_collate;
	        dbDelta( $sql );

	    }catch ( Exception $e ) {
	    
	    }
	}

	/**
	 *
	 */
	public function do_save(){

		OpalEstate_User_Search::install();

		if( $this->user_id > 0 && isset($_POST['params']) && isset($_POST['name']) && !empty($_POST['name'])  && !empty($_POST['params']) ){
			if( !$this->has_existed($_POST['params']) ){
				$this->insert( array('name' => $_POST['name'], 'params' => $_POST['params'] )  );
				$result = array( 'status' => true , 'message' => __( 'Saved this search successful.', 'opalestate' ) ); 
			}else {
				$result = array( 'status' => false , 'message' => __( 'You saved this search', 'opalestate' ) ); 
			}
		}else {
			$result = array( 'status' => false , 'message' => __( 'Please sign in to save this search.', 'opalestate' ) );	
		}
		 
		echo json_encode( $result );
		die; 
	}

	/**
	 *
	 */
	public function do_delete( $id ){
		
		global $wpdb;
		if(  $this->user_id ){
			$wpdb->delete( $wpdb->prefix."opalestate_usersearch", array("id" => $id ,'user_id' => $this->user_id ), array( '%d' ) ) ;
		}
	}

	/**
	 *
	 */
	public function get_list(){

		global $wpdb;
 		
        $query = " SELECT * FROM ".$wpdb->prefix."opalestate_usersearch where user_id=".$this->user_id;
        return $wpdb->get_results(  $query );
	}

	/**
	 *
	 */
	public function is_saved(){

	}

	/**
	 *
	 */
	public function dashboard_menu( $menu ){
		$menu['savedsearch'] = array(
			'icon' 	=> 'fa fa-search',
			'link'	=> opalestate_user_savedsearch_page(),
			'title' =>  __( 'Saved Search', 'opalestate'),
			'id'	=>   0
		);

		return $menu;
	}

	public function savedsearch_page(){
		if( isset($_GET['doaction'])  && $_GET['doaction'] == 'delete' && isset($_GET['id']) ){
			$this->do_delete( (int)$_GET['id'] );
		}
		echo Opalestate_Template_Loader::get_template_part( 'user-search/content-savedsearch' ); 	
	}
	/**
	 *
	 */
	public function render_button(){
		echo Opalestate_Template_Loader::get_template_part( 'user-search/render-form' ); 
	}
}
if( opalestate_options('enable_saved_usersearch','on') == 'on' ){
   OpalEstate_User_Search::getInstance();
}