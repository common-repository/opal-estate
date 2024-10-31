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

class Opalestate_Taxonomy_Type {

	/**
	 *
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'definition' ] );
		add_filter( 'opalestate_taxomony_types_metaboxes', [ __CLASS__, 'metaboxes' ] );
		add_action( 'cmb2_admin_init', [ __CLASS__, 'taxonomy_metaboxes' ] );


	}

	/**
	 * Hook in and add a metabox to add fields to taxonomy terms
	 */
	public static function taxonomy_metaboxes() {

		$prefix = 'opalestate_type_';
		/**
		 * Metabox to add fields to categories and tags
		 */
		$cmb_term = new_cmb2_box( [
			'id'           => $prefix . 'edit',
			'title'        => __( 'Type Metabox', 'opalestate' ), // Doesn't output for term boxes
			'object_types' => [ 'term' ], // Tells CMB2 to use term_meta vs post_meta
			'taxonomies'   => [ 'opalestate_types' ], // Tells CMB2 which taxonomies should have these fields
			// 'new_term_section' => true, // Will display in the "Add New Category" section
		] );

		$cmb_term->add_field( [
			'name' => __( 'Custom Icon Marker', 'opalestate' ),
			'desc' => __( 'This image will display in google map', 'opalestate' ),
			'id'   => $prefix . 'iconmarker',
			'type' => 'file',
		] );

		$cmb_term->add_field( [
			'name' => __( 'Image', 'opalestate' ),
			'desc' => __( 'Type image', 'opalestate' ),
			'id'   => $prefix . 'image',
			'type' => 'file',
		] );
	}

	/**
	 *
	 */
	public static function definition() {

		$labels = [
			'name'              => __( 'Types', 'opalestate' ),
			'singular_name'     => __( 'Properties By Type', 'opalestate' ),
			'search_items'      => __( 'Search Types', 'opalestate' ),
			'all_items'         => __( 'All Types', 'opalestate' ),
			'parent_item'       => __( 'Parent Type', 'opalestate' ),
			'parent_item_colon' => __( 'Parent Type:', 'opalestate' ),
			'edit_item'         => __( 'Edit Type', 'opalestate' ),
			'update_item'       => __( 'Update Type', 'opalestate' ),
			'add_new_item'      => __( 'Add New Type', 'opalestate' ),
			'new_item_name'     => __( 'New Type', 'opalestate' ),
			'menu_name'         => __( 'Types', 'opalestate' ),
		];

		register_taxonomy( 'opalestate_types', [ 'opalestate_property' ], [
			'labels'       => apply_filters( 'opalestate_taxomony_types_labels', $labels ),
			'hierarchical' => true,
			'query_var'    => 'property-type',
			'rewrite'      => [ 'slug' => __( 'property-type', 'opalestate' ) ],
			'public'       => true,
			'show_ui'      => true,
		] );
	}

	public static function metaboxes() {

	}

	public static function dropdownList( $selected = 0 ) {

		$id = "opalestate_types" . rand();

		$args = apply_filters( 'opalestate_types_dropdown_args', [
			'show_option_none' => __( 'Select Types', 'opalestate' ),
			'id'               => $id,
			'class'            => 'form-control',
			'show_count'       => 0,
			'hierarchical'     => '',
			'name'             => 'types',
			'selected'         => $selected,
			'value_field'      => 'slug',
			'taxonomy'         => 'opalestate_types',
			'orderby'          => 'name',
			'order'            => 'ASC',
		] );

		return wp_dropdown_categories( $args );
	}

}

Opalestate_Taxonomy_Type::init();
