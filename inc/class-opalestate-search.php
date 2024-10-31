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

class OpalEstate_Search{

	/**
	 * Get search query base on user request to filter collection of Agents
	 */
	public static function get_search_agents_query(  $args=array()  ){
		$min = opalestate_options( 'search_agent_min_price',0 );
		$max = opalestate_options( 'search_agent_max_price',10000000 );


		$search_min_price = isset($_GET['min_price']) ? sanitize_text_field($_GET['min_price']) : '';
        $search_max_price = isset($_GET['max_price']) ? sanitize_text_field($_GET['max_price']) : '';

        $search_min_area = isset($_GET['min_area']) ? sanitize_text_field($_GET['min_area']) : '';
        $search_max_area = isset($_GET['max_area']) ? sanitize_text_field($_GET['max_area']) : '';
        $s = isset($_GET['search_text']) ? sanitize_text_field($_GET['search_text']) : null;


		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
		$default = array(
			'post_type'         => 'opalestate_agent',
			'posts_per_page'    => apply_filters('opalestate_agent_per_page' , 12 ),
			'paged'				=> $paged,
		);
		$args = array_merge( $default, $args );

		$tax_query = array();


		if( isset( $_GET['location']) &&  $_GET['location'] !=-1 ){
			$tax_query[] =
			    array(
			        'taxonomy' => 'opalestate_location',
			        'field'    => 'slug',
			        'terms'    => $_GET['location'],
			    );
		}

		if( isset( $_GET['types']) && $_GET['types'] !=-1 ){
			$tax_query[] =
			    array(
			        'taxonomy' => 'opalestate_types',
			        'field'    => 'slug',
			        'terms'    => $_GET['types'],
			    )
			;
		}

		if( $tax_query  ){
			$args['tax_query'] = array('relation' => 'AND');
			$args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
		}

		$args['meta_query'] = array('relation' => 'AND');

 		if($search_min_price != $min && is_numeric($search_min_price)) {
            array_push($args['meta_query'], array(
                'key'     => OPALESTATE_AGENT_PREFIX.'target_min_price',
                'value'   => $search_min_price,
                'compare' => '>=',
                // 'type' => 'NUMERIC'
            ));
        }
        if( is_numeric($search_max_price) && $search_max_price != $max ) {
            array_push($args['meta_query'], array(
                'key'     => OPALESTATE_AGENT_PREFIX.'target_max_price',
                'value'   => $search_max_price,
                'compare' => '<=',
                // 'type' => 'NUMERIC'
            ));
        }

 		return new WP_Query( $args );
	}

	/**
	 * Get Query Object to display collection of property with user request which submited via search form
	 */
	public static function get_search_results_query( $limit = 9 ){

		// global $paged;
		global $wp_query;

		$show_featured_first = opalestate_options( 'show_featured_first' , 1 );
		$search_min_price 	 = isset($_GET['min_price']) ? sanitize_text_field($_GET['min_price']) : '';
        $search_max_price 	 = isset($_GET['max_price']) ? sanitize_text_field($_GET['max_price']) : '';

        $search_min_area 	 = isset($_GET['min_area']) ? sanitize_text_field($_GET['min_area']) : '';
        $search_max_area 	 = isset($_GET['max_area']) ? sanitize_text_field($_GET['max_area']) : '';
        $s 				     = isset($_GET['search_text']) ? sanitize_text_field($_GET['search_text']) : null;

		$posts_per_page 	 = apply_filters('opalestate_search_property_per_page' , opalestate_options( 'search_property_per_page' , $limit  ) );

        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $paged = isset( $wp_query->query['paged'] ) ? $wp_query->query['paged'] : $paged;
        $paged = ! empty( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : $paged;

        if( isset($_GET['paged']) && intval($_GET['paged']) > 0 ){
        	$paged = intval($_GET['paged']);
        }

		$infos = array();

		$args = array(
            'posts_per_page' => $posts_per_page,
            'paged' 		 => $paged,
            'post_type' 	 => 'opalestate_property',
            'post_status' 	 => 'publish',
            's'		=> $s
        );

		$tax_query = array();

		if( isset( $_GET['location']) &&  $_GET['location'] !=-1 ){
			$tax_query[] =
			    array(
			        'taxonomy' => 'opalestate_location',
			        'field'    => 'slug',
			        'terms'    => $_GET['location'],
			    );
		}

		if( isset( $_GET['types']) && $_GET['types'] !=-1 ){
			$tax_query[] =
			    array(
			        'taxonomy' => 'opalestate_types',
			        'field'    => 'slug',
			        'terms'    => $_GET['types'],
			    )
			;
		}
		if( isset( $_GET['status']) &&  $_GET['status'] !=-1 ){
			$tax_query[] =
			    array(
			        'taxonomy' => 'opalestate_status',
			        'field'    => 'slug',
			        'terms'    => $_GET['status'],

			);
		}

		if( $tax_query  ){
			$args['tax_query'] = array('relation' => 'AND');
			$args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
		}

		$args['meta_query'] = array('relation' => 'AND');
		if( isset($_GET['info']) ){

			$metaquery = array();
			foreach( $_GET['info'] as $key => $value ){
				if( trim($value) ){
					if( is_numeric(trim($value)) ){
						$fieldquery = array(
							'key'     => OPALESTATE_PROPERTY_PREFIX.$key,
		                	'value'   => sanitize_text_field(trim($value)),
		                	'compare' => apply_filters( 'opalestate_info_numeric_compare', '>=' ),
		                	'type' => 'NUMERIC'
						);
					} else {
						$fieldquery = array(
							'key'     => OPALESTATE_PROPERTY_PREFIX.$key,
		                	'value'   => sanitize_text_field(trim($value)) ,
		                	'compare' => 'LIKE'
						);
					}
					$sarg = apply_filters('opalestate_search_field_query_'.$key, $fieldquery );
					$metaquery[] = $sarg;

				}
			}
	 		$args['meta_query'] = array_merge( $args['meta_query'], $metaquery );
		}

		if($search_min_price != '' && $search_min_price != '' && is_numeric($search_min_price) && is_numeric($search_max_price)) {
            if ( $search_min_price ) {

				array_push($args['meta_query'], array(
					'key'     => OPALESTATE_PROPERTY_PREFIX.'price',
					'value'   => array($search_min_price, $search_max_price),
					'compare' => 'BETWEEN',
					'type' => 'NUMERIC'
				));
			} else {
				array_push( $args['meta_query'], array(
					array(
						array(
							'key'     => OPALESTATE_PROPERTY_PREFIX.'price',
							'compare'   => 'NOT EXISTS'
						),
						'relation'  => 'OR',
						array(
							'key'     => OPALESTATE_PROPERTY_PREFIX.'price',
							'value'   => $search_max_price,
							'compare' => '<=',
							'type' => 'NUMERIC'
						)
					)
				) );
			}

        } else if($search_min_price != '' && is_numeric($search_min_price)) {
            array_push($args['meta_query'], array(
                'key'     => OPALESTATE_PROPERTY_PREFIX.'price',
                'value'   => $search_min_price,
                'compare' => '>=',
                'type' => 'NUMERIC'
            ));
        } else if($search_max_price != '' && is_numeric($search_max_price)) {
            array_push($args['meta_query'], array(
                'key'     => OPALESTATE_PROPERTY_PREFIX.'price',
                'value'   => $search_max_price,
                'compare' => '<=',
                'type' => 'NUMERIC'
            ));
        }

        if($search_min_area != '' && $search_min_area != '' && is_numeric($search_min_area) && is_numeric($search_max_area)) {
            array_push($args['meta_query'], array(
                'key'     => OPALESTATE_PROPERTY_PREFIX.'areasize',
                'value'   => array($search_min_area, $search_max_area),
                'compare' => 'BETWEEN',
                'type' => 'NUMERIC'
            ));
        } else if($search_min_area != '' && is_numeric($search_min_area)) {
            array_push($args['meta_query'], array(
                'key'     => OPALESTATE_PROPERTY_PREFIX.'areasize',
                'value'   => $search_min_area,
                'compare' => '>=',
                'type' => 'NUMERIC'
            ));
        } else if($search_max_area != '' && is_numeric($search_max_area)) {
            array_push($args['meta_query'], array(
                'key'     => OPALESTATE_PROPERTY_PREFIX.'areasize',
                'value'   => $search_max_area,
                'compare' => '<=',
                'type' => 'NUMERIC'
            ));
        }

    	$ksearchs = array();

	 	if( isset($_REQUEST['opalsortable']) && !empty($_REQUEST['opalsortable']) ){
			$ksearchs = explode( "_", $_REQUEST['opalsortable'] );
    	} elseif( isset($_SESSION['opalsortable']) && !empty($_SESSION['opalsortable']) ){
			$ksearchs = explode( "_", $_SESSION['opalsortable'] );
    	}

		if( !empty($ksearchs) && count($ksearchs) == 2 ){
			$args['meta_key'] = OPALESTATE_PROPERTY_PREFIX.$ksearchs[0];
			$args['orderby']  = 'meta_value_num';
			$args['order'] 	  = $ksearchs[1];
		}

     	$args = apply_filters( 'opalestate_get_search_results_query_args', $args );

		$query = new WP_Query($args);

        wp_reset_postdata();

        return $query;
	}

	/**
	 *
	 */
	public static function get_setting_search_fields( $option='' ){
		$options = array(
			OPALESTATE_PROPERTY_PREFIX.'bedrooms' => __('Bed Rooms', 'opalestate'),
			OPALESTATE_PROPERTY_PREFIX.'parking' =>  __('Parking', 'opalestate'),
			OPALESTATE_PROPERTY_PREFIX.'bathrooms' => __('Bath Rooms', 'opalestate'),
		);

		$default = apply_filters( 'opalestate_default_fields_setting', $options );

		$metas = Opalestate_PostType_Property::metaboxes_info_fields();


		$esettings = array();

		$found = false;
		foreach( $metas as $key => $meta ){
			$value = opalestate_options($meta['id'].'_opt'.$option) ;

			if( preg_match("#areasize#", $meta['id']) ){
				continue;
			}

			if( $value ){
				$id = str_replace( OPALESTATE_PROPERTY_PREFIX, "", $meta['id'] );
				$esettings[$id] = $meta['name'];
			}
			if( $value == 0 ){
				$found = true;
			}
		}


		if( !empty($esettings) ){
			return $esettings;
		}elseif( $found ){
			return array();
		}

		return $default;
	}

	/**
	 * add action to ajax search to display query data results with json format.
	 */
	public static function init(){
		add_action( 'wp_ajax_opalestate_ajx_get_properties', array( __CLASS__, 'get_search_json' ) );
		add_action( 'wp_ajax_nopriv_opalestate_ajx_get_properties', array( __CLASS__, 'get_search_json' ) );

		add_action( 'wp_ajax_opalestate_render_get_properties', array( __CLASS__, 'render_get_properties' ) );
		add_action( 'wp_ajax_nopriv_opalestate_render_get_properties', array( __CLASS__, 'render_get_properties' ) );
	//	add_filter( "pre_get_posts",   array( __CLASS__, 'change_archive_query' )   );
	}

	/**
	 * Get Json data by action ajax filter
	 */
	public static function get_search_json(){

		$query = self::get_search_results_query();

		$output = array();

		while( $query->have_posts() ) {

	        $query->the_post();
			$property = opalesetate_property( get_the_ID() );
			$output[] = $property->get_meta_search_objects();
	    }

	    wp_reset_query();

	    echo json_encode( $output ); exit;
	}

	public static function render_get_properties(){
		// $_GET = $_POST;
		echo Opalestate_Template_Loader::get_template_part( 'shortcodes/ajax-map-search-result' ); die;
	}
	/**
	 * Render search property form in horizontal
	 */
	public static function render_horizontal_form( $atts=array() ){
		echo Opalestate_Template_Loader::get_template_part( 'parts/search-form-h', $atts );
	}

	/**
	 * Render search property form in vertical
	 */
	public static function render_vertical_form(  $atts=array() ){

		echo Opalestate_Template_Loader::get_template_part( 'parts/search-form-v' , $atts );
	}

	/**
	 *
	 */
	public static function render_field_price(){

	}

	/**
	 *
	 */
	public static function render_field_area(){

	}
}

OpalEstate_Search::init();
?>
