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

class Opalestate_Query {
	
	/**
	 * Set active location 
	 */
	public static $LOCATION; 

	/**
	 * Get Query Object to display list of agents 
	 */
	public static function get_agents( $args = array(), $featured=false ){
	 	$default = array(
			'post_type'         => 'opalestate_agent',
			'posts_per_page'    => 10,
		);
		$args = array_merge( $default, $args );
		if( $featured ){
			$args['meta_key'] 	  = OPALESTATE_AGENT_PREFIX . 'featured';
			$args['meta_value']   = 1;
			$args['meta_compare'] = '=';
		}
 		return new WP_Query( $args );
	}

	/**
	 * Get Query Object to display list of agents 
	 */
	public static function get_offices( $args = array(), $featured=false ){
	 	$default = array(
			'post_type'         => 'opalestate_office',
			'posts_per_page'    => 10,
		);
		$args = array_merge( $default, $args );
		if( $featured ){
			$args['meta_key'] 	  = OPALESTATE_OFFICE_PREFIX . 'featured';
			$args['meta_value']   = 1;
			$args['meta_compare'] = '=';
		}
 		return new WP_Query( $args );
	}
	
	/**
	 * Get Query Object By post and agent with setting items per page.
	 */
 	public static function get_office_property( $post_id = null, $agent_id = null, $per_page = 10, $page=null ) {
		if ( null == $post_id ) {
			$post_id = get_the_ID();
		}
		$author = array( $agent_id ); //echo '<pre>'.print_r( $post_id, 1 );die;
		$team = get_post_meta( $post_id, OPALESTATE_OFFICE_PREFIX . 'team', true );   
		if( $team && opalestate_options('show_properties_team', 'on') == 'on' ){
			$author = array_merge( $author, $team );
		}
		$paged = $page ? $page: ( ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged') );
		$args = array(
			'post_type'         => 'opalestate_property',
			'author__in'       		=> $author,
			'posts_per_page'    => $per_page,
			'post__not_in' 		=> array($post_id),
			'paged'				=> $paged
		);

	//	echo '<Pre>'.print_r( $args ,1 );die;
		$query = new WP_Query( $args );
		return $query;
	}

	/**
	 * Get Query Object By post and agent with setting items per page.
	 */
 	public static function get_agent_property( $post_id = null, $agent_id = null, $per_page = 10 ) {
		if ( null == $post_id ) {
			$post_id = get_the_ID();
		}
		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');

		
		$args = array(
			'post_type'         => 'opalestate_property',
			'posts_per_page'    => $per_page,
			'post__not_in' 		=> array($post_id),
			'paged'				=> $paged,
			'meta_query'        => array(
				array(
					'key'       => OPALESTATE_PROPERTY_PREFIX . 'agent',
					'value'     => $agent_id,
					'compare'   => '=',
				),
			),
		);
		$query = new WP_Query( $args );
		return $query;
	}

	/**
	 * Get Query Object to show featured properties with custom setting via Arguments passing.
	 */
	public static function get_featured_properties_query( $args=array() ){
		$default = array(
			'post_type'         => 'opalestate_property',
			'posts_per_page'	=> 10,
			'meta_key' => OPALESTATE_PROPERTY_PREFIX . 'featured',
			'meta_value' => 1,
			'meta_compare' => '='
		 
		);

		$args = array_merge( $default, $args );
		return new WP_Query( $args );
	}

	/**
	 * Set filter location to query when user set his location as global filterable.
	 */
	public static function set_location( $args ){
		if( $args && self::$LOCATION ){
			$tax_query = array(
			    array(
			        'taxonomy' => 'opalestate_location',
			        'field'    => 'slug',
			        'terms'    => self::$LOCATION,
			    ),
			);
			$args['tax_query'] = array('relation' => 'AND');
			$args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
		}
		return $args;
	}

	/**
	 * Get WP Query Object with custom passing arguments and User request as get data.
	 */
	public static function get_property_query( $args=array() ){


		$default = array(
			'post_type'         => 'opalestate_property',
			'posts_per_page'	=> 10 ,
			
		);

		$args = array_merge( $default, $args );

		if( isset( $_GET['status']) &&  !empty($_GET['status']) && $_GET['status'] != 'all' ){
			$tax_query = array(
			    array(
			        'taxonomy' => 'opalestate_status',
			        'field'    => 'slug',
			        'terms'    =>  $_GET['status'],
			    ),
			);
			$args['tax_query'] = array('relation' => 'AND');
			$args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
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

	 	$args = self::set_location( $args );

		$query =  new WP_Query( $args );

		wp_reset_postdata();
		return $query;
	}

	/**
	 * Get Agent id by property id
	 */
	public static function get_agent_by_property( $post_id = null ) {
		if ( null == $post_id ) {
			$post_id = get_the_ID();
		}
		$agent_id = get_post_meta( $post_id, OPALESTATE_PROPERTY_PREFIX . 'agent', true );

		return $agent_id;
	}

	/**
	 * Get List of properties by user
	 */
	public static function get_properties_by_user( $oargs=array(), $user_id = null, $per_page = 9 ) {

		$paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');

		$args = array(
			'post_type'         => 'opalestate_property',
			'paged'				=> $paged,
			'post_status' 		=> 'any',
			'author'       		=> $user_id,
			'posts_per_page'	=> $per_page,
			
		);
		if ( !empty( $oargs ) || is_array($oargs) ) {
		 	$args = array_merge( $args, $oargs );
		}
		
		$query = new WP_Query( $args );
		wp_reset_postdata();
		return $query; 
	}

	/**
	 *
	 */
	public static function get_amenities(){
		return get_terms('opalestate_amenities', array('hide_empty'=> false));
	}
}