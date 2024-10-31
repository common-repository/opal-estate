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
class Opalestate_Taxonomy_Label{

	/**
	 *
	 */
	public static function init(){
		add_action( 'init', array( __CLASS__, 'definition' ) );
		add_filter( 'opalestate_taxomony_label_metaboxes', array( __CLASS__, 'metaboxes' ) );

	 	add_action( 'cmb2_admin_init', array( __CLASS__, 'taxonomy_metaboxes' ), 999 );
	}
	
	/**
	 *
	 */
	public static function definition(){
		
		$labels = array(
			'name'              => __( 'Label', 'opalestate' ),
			'singular_name'     => __( 'Properties By Label', 'opalestate' ),
			'search_items'      => __( 'Search Label', 'opalestate' ),
			'all_items'         => __( 'All Label', 'opalestate' ),
			'parent_item'       => __( 'Parent Label', 'opalestate' ),
			'parent_item_colon' => __( 'Parent Label:', 'opalestate' ),
			'edit_item'         => __( 'Edit Label', 'opalestate' ),
			'update_item'       => __( 'Update Label', 'opalestate' ),
			'add_new_item'      => __( 'Add New Label', 'opalestate' ),
			'new_item_name'     => __( 'New Label', 'opalestate' ),
			'menu_name'         => __( 'Label', 'opalestate' ),
		);

		register_taxonomy( 'opalestate_label', 'opalestate_property'  , array(
			'labels'            => apply_filters( 'opalestate_label_labels', $labels ),
			'hierarchical'      => true,
			'query_var'         => 'property-label',
			'rewrite'           => array( 'slug' => __( 'property-label', 'opalestate' ) ),
			'public'            => true,
			'show_ui'           => true,
		) );
	}

	public static function metaboxes(){

	}

	/**
	 * Hook in and add a metabox to add fields to taxonomy terms
	 */
	public static function taxonomy_metaboxes() {

		$prefix = 'opalestate_label_';
		/**
		 * Metabox to add fields to categories and tags
		 */
		$cmb_term = new_cmb2_box( array(
			'id'               => $prefix . 'edit',
			'title'            => __( 'Category Metabox', 'opalestate' ), // Doesn't output for term boxes
			'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
			'taxonomies'       => array( 'opalestate_label' ), // Tells CMB2 which taxonomies should have these fields
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
			'desc' 				=> __( 'Set color of text', 'opalestate' ),
			'id'   				=> $prefix . 'lb_color',
			'type'              => 'colorpicker',
		) );

		$cmb_term->add_field( array(
			'name' 				=> __( 'Image Logo', 'opalestate' ),
			'desc' 				=> __( 'Or Using Image Logo without using text', 'opalestate' ),
			'id'   				=> $prefix . 'lb_img',
			'type'              => 'file',
		) );
	}

	public static function getList(){
		 return get_terms('opalestate_label', array('hide_empty'=> false));
	}

	public static function dropdownList( $selected=0){

		$id = "opalestate_label".rand();
		
		$args = array( 
				'show_option_none' => __( 'Select Label', 'opalestate' ),
				'id' => $id,
				'class' => 'form-control',
				'show_count' => 0,
				'hierarchical'	=> '',
				'name'	=> 'label',
				'value_field'	=> 'slug',
				'selected'	=> $selected,
				'taxonomy'	=> 'opalestate_label'
		);		

		return wp_dropdown_categories( $args );
	}

}

Opalestate_Taxonomy_Label::init();