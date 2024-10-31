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
class Opalestate_Taxonomy_Status{

	/**
	 *
	 */
	public static function init(){
		add_action( 'init', array( __CLASS__, 'definition' ) );
		add_filter( 'opalestate_taxomony_status_metaboxes', array( __CLASS__, 'metaboxes' ) );

		add_action( 'cmb2_admin_init', array( __CLASS__, 'taxonomy_metaboxes' ) );
	}

	/**
	 *
	 */
	public static function definition(){
		$labels = array(
			'name'              => __( 'Status', 'opalestate' ),
			'singular_name'     => __( 'Properties By Status', 'opalestate' ),
			'search_items'      => __( 'Search Status', 'opalestate' ),
			'all_items'         => __( 'All Status', 'opalestate' ),
			'parent_item'       => __( 'Parent Status', 'opalestate' ),
			'parent_item_colon' => __( 'Parent Status:', 'opalestate' ),
			'edit_item'         => __( 'Edit Status', 'opalestate' ),
			'update_item'       => __( 'Update Status', 'opalestate' ),
			'add_new_item'      => __( 'Add New Status', 'opalestate' ),
			'new_item_name'     => __( 'New Status', 'opalestate' ),
			'menu_name'         => __( 'Status', 'opalestate' ),
		);
		register_taxonomy( 'opalestate_status', 'opalestate_property'  , array(
			'labels'            => apply_filters( 'opalestate_status_labels', $labels ),
			'hierarchical'      => true,
			'query_var'         => 'property-status',
			'rewrite'           => array( 'slug' => __( 'property-status', 'opalestate' ) ),
			'public'            => true,
			'show_ui'           => true,
		) );
	}


	/**
	 * Hook in and add a metabox to add fields to taxonomy terms
	 */
	public static function taxonomy_metaboxes() {

		$prefix = 'opalestate_status_';
		/**
		 * Metabox to add fields to categories and tags
		 */
		$cmb_term = new_cmb2_box( array(
			'id'               => $prefix . 'edit',
			'title'            => __( 'Category Metabox', 'opalestate' ), // Doesn't output for term boxes
			'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
			'taxonomies'       => array( 'opalestate_status' ), // Tells CMB2 which taxonomies should have these fields
			// 'new_term_section' => true, // Will display in the "Add New Category" section
		) );
		$cmb_term->add_field( array(
			'name' 				=> __( 'Background', 'opalestate' ),
			'desc' 				=> __( 'Set background of label', 'opalestate' ),
			'id'   				=> $prefix . 'lb_bg',
			'type'              => 'colorpicker',
		) );
		$cmb_term->add_field( array(
			'name' 				=> __( 'Color', 'opalestate' ),
			'desc' 				=> __( 'Set background of text', 'opalestate' ),
			'id'   				=> $prefix . 'lb_color',
			'type'              => 'colorpicker',
		) );
		$cmb_term->add_field( array(
			'name' 				=> __( 'Order', 'opalestate' ),
			'desc' 				=> __( 'Set a priority to display', 'opalestate' ),
			'id'   				=> $prefix . 'order',
			'type'              => 'text_small',
			'attributes'        => array(
				'type' => 'number',
			),
			'default'           => 0,
		) );
	}

	public static function metaboxes(){

	}

	public static function getList() {
		$args = apply_filters( 'opalestate_status_args', [
			'taxonomy'     => 'opalestate_status',
			'hide_empty'   => false,
			'hierarchical' => false,
			'parent'       => 0,
			'order'        => 'ASC',
			'orderby'      => 'meta_value_num',
			'meta_query'   => [
				[
					'key'  => 'opalestate_status_order',
					'type' => 'NUMERIC',
				],
			],
		] );

		return get_terms( $args );
	}

	public static function dropdownList( $selected=0){

		$id = "opalestate_status".rand();
		
		$args = array( 
				'show_option_none' => __( 'Select Status', 'opalestate' ),
				'id' => $id,
				'class' => 'form-control',
				'show_count' => 0,
				'hierarchical'	=> '',
				'name'	=> 'status',
				'value_field'	=> 'slug',
				'selected'	=> $selected,
				'taxonomy'	=> 'opalestate_status'
		);		

		return wp_dropdown_categories( $args );
	}
}

Opalestate_Taxonomy_Status::init();
