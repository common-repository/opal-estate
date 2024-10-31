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
class Opalestate_Taxonomy_Categories{

    /**
     *
     */
    public static function init(){

        add_action( 'init', array( __CLASS__, 'definition' ) );
        add_filter( 'opalestate_taxomony_category_metaboxes', array( __CLASS__, 'metaboxes' ) );

        add_action( 'cmb2_admin_init', array( __CLASS__, 'taxonomy_metaboxes' ), 999 );


    }

    public static function metaboxes(){

    }



    /**
     *
     */
    public static function definition(){

        register_taxonomy('property_category', 'opalestate_property', apply_filters( 'opalestate_taxonomy_args_property_category', array(
            'labels' => array(
                'name'              => __('Property Categories','opalestate'),
                'add_new_item'      => __('Add New Property Category','opalestate'),
                'new_item_name'     => __('New Property Category','opalestate')
            ),
            'public'            => true,
            'hierarchical'  => true,
            'show_ui'               => true,
            'query_var'             => true,
            'rewrite'       => array('slug' => _x( 'property-category', 'slug', 'opalestate' ), 'with_front' => false, 'hierarchical' => true )
        )) );
    }



    /**
     * Hook in and add a metabox to add fields to taxonomy terms
     */
    public static function taxonomy_metaboxes() {

        $prefix = 'opalestate_category_';
        /**
         * Metabox to add fields to categories and tags
         */
        $cmb_term = new_cmb2_box( array(
            'id'               => $prefix . 'edit',
            'title'            => __( 'Category Metabox', 'opalestate' ), // Doesn't output for term boxes
            'object_types'     => array( 'term' ), // Tells CMB2 to use term_meta vs post_meta
            'taxonomies'       => array( 'property_category' ), // Tells CMB2 which taxonomies should have these fields
            // 'new_term_section' => true, // Will display in the "Add New Category" section
        ) );

        $cmb_term->add_field( array(
            'name' 				=> __( 'Image', 'opalestate' ),
            'desc' 				=> __( 'Category image', 'opalestate' ),
            'id'   				=> $prefix . 'image',
            'type'              => 'file',
        ) );
    }

    public static function getList(){
        return get_terms('opalestate_label', array('hide_empty'=> false));
    }

    public static function dropdownList( $selected=0){

        $id = "opalestate_category".rand();

        $args = array(
            'show_option_none' => __( 'Select category', 'opalestate' ),
            'id' => $id,
            'class' => 'form-control',
            'show_count' => 0,
            'hierarchical'	=> '',
            'name'	=> 'label',
            'value_field'	=> 'slug',
            'selected'	=> $selected,
            'taxonomy'	=> 'opalestate_category'
        );

        return wp_dropdown_categories( $args );
    }

}

Opalestate_Taxonomy_Categories::init();