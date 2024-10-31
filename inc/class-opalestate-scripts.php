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
class OpalEstate_Scripts{

	public static function init(){
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'load_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'load_admin_styles') );

		add_action('wp_head',  array( __CLASS__, 'add_custom_styles') );
	}

	public static function load_scripts(){
		$key = opalestate_options('google_map_api_keys') ? opalestate_options('google_map_api_keys') : 'AIzaSyCw3OomrCm0jcQO18LtR2gUIxRcUpNwjIk';
		$api = apply_filters( 'opalestate_google_map_api_uri',  '//maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places&amp;key='.$key   );

		wp_enqueue_script("opalestate-google-maps", $api, null, "0.0.1", false);
		wp_enqueue_script("infobox", OPALESTATE_PLUGIN_URL . 'assets/js/infobox.js', array( 'jquery' ), "1.3", false);
		wp_enqueue_script("markerclusterer", OPALESTATE_PLUGIN_URL . 'assets/js/markerclusterer.js', array( 'jquery' ), "1.3", false);
		
 	

	

		wp_enqueue_script("opalestate-scripts", OPALESTATE_PLUGIN_URL . 'assets/js/scripts.js', array( 'jquery' ), "1.0.0", true);
		wp_enqueue_script("noUiSlider", OPALESTATE_PLUGIN_URL . 'assets/js/nouislider.min.js', array( 'jquery' ), "1.0.0", true);
		wp_enqueue_script("fitvids", OPALESTATE_PLUGIN_URL . 'assets/js/jquery.fitvids.js', array( 'jquery' ), "1.0.0", true);

		if( file_exists(get_template_directory().'/css/opalestate.css') ){
		//	wp_enqueue_style( 'opalestate-style', get_template_directory_uri() . '/css/opalestate.css');
		}else {
			wp_enqueue_script("owl-carousel", OPALESTATE_PLUGIN_URL . 'assets/js/owl-carousel/owl.carousel.min.js', null, "1.3", false);
			wp_enqueue_style("owl-carousel", OPALESTATE_PLUGIN_URL . 'assets/js/owl-carousel/owl.carousel.css', null, "1.3", false);
			wp_enqueue_style( 'opalestate-style', OPALESTATE_PLUGIN_URL . '/assets/opalestate.css');
			wp_enqueue_style("font-awesome", OPALESTATE_PLUGIN_URL . 'assets/font-awesome.min.css', null, "1.3", false);
		}

		wp_localize_script( 'opalestate-scripts', 'opalesateJS', 
			array( 'ajaxurl' 	 => admin_url( 'admin-ajax.php' ),
				 	'siteurl' 	 => get_template_directory_uri(),
				 	'mapiconurl' => OPALESTATE_PLUGIN_URL.'assets/map/',
				 	'rtl' => is_rtl() ? 'true' : 'false',
		) );

		// add inline style taxonomy
		
		
	}

	public static function add_custom_styles(){
		$statuses = Opalestate_Taxonomy_Status::getList();
		$custom = '';

		if( $statuses ){
		
			foreach( $statuses as $status ){
				$bg    = get_term_meta( $status->term_id, 'opalestate_status_lb_bg' , true );
				$color = get_term_meta( $status->term_id, 'opalestate_status_lb_color', true );
				if( $bg || $color ){  
					$custom .= '.property-status-'.trim($status->slug).' { ';
						if( $bg ){
							$custom .= 'background-color:'.$bg.' !important;';
						}
						if( $color ){
							$custom .= 'color:'.$color.'!important';
						}
					$custom .= ' } ';
				}
			}
		} 
		if( $custom ){
			echo '<style type="text/css">'.$custom.'</style>';
		}
	}


    public static function load_admin_styles() { 
        wp_enqueue_style( 'opalestate-admin', OPALESTATE_PLUGIN_URL . 'assets/admin.css', array(), '3.0.3' );
    }

}

OpalEstate_Scripts::init();
