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
class Opalestate_Taxonomy_Amenities{

	/**
	 *
	 */
	public static function init(){
		add_action( 'init', array( __CLASS__, 'definition' ) );
		add_filter( 'opalestate_taxomony_amenities_metaboxes', array( __CLASS__, 'metaboxes' ) );
	}

	/**
	 *
	 */
	public static function definition(){
		
		$labels = array(
			'name'              => __( 'Amenities', 'opalestate' ),
			'singular_name'     => __( 'Properties By Amenity', 'opalestate' ),
			'search_items'      => __( 'Search Amenities', 'opalestate' ),
			'all_items'         => __( 'All Amenities', 'opalestate' ),
			'parent_item'       => __( 'Parent Amenity', 'opalestate' ),
			'parent_item_colon' => __( 'Parent Amenity:', 'opalestate' ),
			'edit_item'         => __( 'Edit Amenity', 'opalestate' ),
			'update_item'       => __( 'Update Amenity', 'opalestate' ),
			'add_new_item'      => __( 'Add New Amenity', 'opalestate' ),
			'new_item_name'     => __( 'New Amenity', 'opalestate' ),
			'menu_name'         => __( 'Amenities', 'opalestate' ),
		);

		register_taxonomy( 'opalestate_amenities', 'opalestate_property', array(
			'labels'            => apply_filters( 'opalestate_taxomony_amenities_labels', $labels ),
			'hierarchical'      => true,
			'query_var'         => 'property-amenity',
			'rewrite'           => array( 'slug' => _x( 'opal-property-amenity', 'slug', 'opalestate' ), 'with_front' => false, 'hierarchical' => true ),
			'public'            => true,
			'show_ui'           => true,
		) );
	}

	public static function metaboxes(){

	}


}

Opalestate_Taxonomy_Amenities::init();