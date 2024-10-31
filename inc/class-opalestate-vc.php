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

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

if (class_exists('WPBakeryVisualComposerAbstract')) {
    function opalestate_vc_get_term_object($term) {
        $vc_taxonomies_types = vc_taxonomies_types();

        return array(
            'label'    => $term->name,
            'value'    => $term->slug,
            'group_id' => $term->taxonomy,
            'group'    => isset($vc_taxonomies_types[$term->taxonomy], $vc_taxonomies_types[$term->taxonomy]->labels, $vc_taxonomies_types[$term->taxonomy]->labels->name) ? $vc_taxonomies_types[$term->taxonomy]->labels->name : esc_html__('Taxonomies', 'opalestate'),
        );
    }

    function opalestate_category_field_search($search_string) {
        $data = array();
        $vc_taxonomies_types = array('property_category');
        $vc_taxonomies = get_terms($vc_taxonomies_types, array(
            'hide_empty' => false,
            'search'     => $search_string
        ));
        if (is_array($vc_taxonomies) && !empty($vc_taxonomies)) {
            foreach ($vc_taxonomies as $t) {
                if (is_object($t)) {
                    $data[] = opalestate_vc_get_term_object($t);
                }
            }
        }

        return $data;
    }

    function opalestate_category_render($query) {
        $category = get_term_by('slug', $query['value'], 'property_category');
        if (!empty($query) && !empty($category)) {
            $data = array();
            $data['value'] = $category->slug;
            $data['label'] = $category->name;
            return !empty($data) ? $data : false;
        }
        return false;
    }

    function opalestate_location_field_search($search_string) {

        $data = array();
        $vc_taxonomies_types = array('opalestate_location');
        $vc_taxonomies = get_terms($vc_taxonomies_types, array(
            'hide_empty' => false,
            'search'     => $search_string
        ));

        if (is_array($vc_taxonomies) && !empty($vc_taxonomies)) {
            foreach ($vc_taxonomies as $t) {
                if (is_object($t)) {
                    $data[] = opalestate_vc_get_term_object($t);
                }
            }
        }

        return $data;
    }

    function opalestate_location_render($query) {
        $category = get_term_by('slug', $query['value'], 'opalestate_location');
        if (!empty($query) && !empty($category)) {
            $data = array();
            $data['value'] = $category->slug;
            $data['label'] = $category->name;
            return !empty($data) ? $data : false;
        }
        return false;
    }

    function opalestate_types_field_search($search_string) {
        $data = array();
        $vc_taxonomies_types = array('opalestate_types');
        $vc_taxonomies = get_terms($vc_taxonomies_types, array(
            'hide_empty' => false,
            'search'     => $search_string
        ));
        if (is_array($vc_taxonomies) && !empty($vc_taxonomies)) {
            foreach ($vc_taxonomies as $t) {
                if (is_object($t)) {
                    $data[] = opalestate_vc_get_term_object($t);
                }
            }
        }

        return $data;
    }

    function opalestate_types_render($query) {
        $category = get_term_by('slug', $query['value'], 'opalestate_types');
        if (!empty($query) && !empty($category)) {
            $data = array();
            $data['value'] = $category->slug;
            $data['label'] = $category->name;
            return !empty($data) ? $data : false;
        }
        return false;
    }

    function opalestate_status_field_search($search_string) {
        $data = array();
        $vc_taxonomies_types = array('opalestate_status');
        $vc_taxonomies = get_terms($vc_taxonomies_types, array(
            'hide_empty' => false,
            'search'     => $search_string
        ));
        if (is_array($vc_taxonomies) && !empty($vc_taxonomies)) {
            foreach ($vc_taxonomies as $t) {
                if (is_object($t)) {
                    $data[] = opalestate_vc_get_term_object($t);
                }
            }
        }

        return $data;
    }

    function opalestate_status_render($query) {
        $category = get_term_by('slug', $query['value'], 'opalestate_status');
        if (!empty($query) && !empty($category)) {
            $data = array();
            $data['value'] = $category->slug;
            $data['label'] = $category->name;
            return !empty($data) ? $data : false;
        }
        return false;
    }


    $shortcodes = array('pbr_estate_filter_property', 'pbr_browse_single_category', 'pbr_browse_single_location', 'pbr_browse_single_type');

    foreach ($shortcodes as $shortcode) {

        add_filter('vc_autocomplete_' . $shortcode . '_property_category_callback', 'opalestate_category_field_search', 10, 1);
        add_filter('vc_autocomplete_' . $shortcode . '_property_category_render', 'opalestate_category_render', 10, 1);

        add_filter('vc_autocomplete_' . $shortcode . '_opalestate_location_callback', 'opalestate_location_field_search', 10, 1);
        add_filter('vc_autocomplete_' . $shortcode . '_opalestate_location_render', 'opalestate_location_render', 10, 1);

        add_filter('vc_autocomplete_' . $shortcode . '_opalestate_types_callback', 'opalestate_types_field_search', 10, 1);
        add_filter('vc_autocomplete_' . $shortcode . '_opalestate_types_render', 'opalestate_types_render', 10, 1);

        add_filter('vc_autocomplete_' . $shortcode . '_opalestate_status_callback', 'opalestate_status_field_search', 10, 1);
        add_filter('vc_autocomplete_' . $shortcode . '_opalestate_status_render', 'opalestate_status_render', 10, 1);
    }


    function opalestate_property_slugs_field_search($search_string) {
        $data = array();

        $args = array(
            'post_type'   => 'opalestate_property',
            'post_status' => 'publish',
            's'           => $search_string
        );

        $posts = get_posts($args);
        if (is_array($posts) && !empty($posts)) {
            foreach ($posts as $_post) {
                $t = array(
                    'label' => $_post->post_title,
                    'value' => $_post->post_name
                );
                $data[] = $t;
            }
        }
        wp_reset_query();
        return $data;
    }

    function opalestate_property_slugs_render($query) {

        $_post = get_page_by_path($query['value'], OBJECT, 'opalestate_property');

        if (!empty($query) && !empty($_post)) {
            $data = array();
            $data['value'] = $_post->post_name;
            $data['label'] = $_post->post_title;
            return !empty($data) ? $data : false;
        }
        return false;
    }

    add_filter('vc_autocomplete_pbr_estate_manual_carousel_properties_property_slugs_callback', 'opalestate_property_slugs_field_search', 10, 1);
    add_filter('vc_autocomplete_pbr_estate_manual_carousel_properties_property_slugs_render', 'opalestate_property_slugs_render', 10, 1);

    // search agents
    vc_map(array(

        "name"        => __("Office/Agent Tab Form Search", "opalestate"),
        "base"        => "pbr_office_agent_search",
        'icon'        => 'icon-wpb-estates-1',
        "class"       => "",
        "description" => 'Display form to search properties',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(

                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
        )
    ));

    // search agents
    vc_map(array(

        "name"        => __(" Property Search Form", "opalestate"),
        "base"        => "pbr_estate_searchbox",
        'icon'        => 'icon-wpb-estates-2',
        "class"       => "",
        "description" => 'Display form to search properties',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(

                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
        )
    ));

    // search agents
    vc_map(array(

        "name"        => __("Agents Search Form", "opalestate"),
        "base"        => "pbr_estate_search_agents",
        'icon'        => 'icon-wpb-estates-3',
        "class"       => "",
        "description" => 'Display form to search agents',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(

                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),

            array(

                "type"        => "textarea_html",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "admin_label" => false
            ),
        )
    ));

    // search Offices
    vc_map(array(
        "name"        => __("Offices Search Form", "opalestate"),
        "base"        => "pbr_estate_search_offices",
        'icon'        => 'icon-wpb-estates-4',
        "class"       => "",
        "description" => 'Display form to search Offices',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(

                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => __('Find the best of companies', 'opalestate'),
                "admin_label" => true
            ),
            array(
                "type"        => "attach_image",
                "description" => esc_html__("If you upload an image, icon will not show.", 'opalestate'),
                "param_name"  => "image",
                "value"       => '',
                'heading'     => esc_html__('Image', 'opalestate')
            ),
            array(

                "type"        => "textarea_html",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "admin_label" => false
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Single category", "opalestate"),
        "base"        => "pbr_browse_single_category",
        'icon'        => 'icon-wpb-estates-5',
        "class"       => "",
        "description" => 'Single category',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "value"       => '',
                'description' => ''
            ),
            array(
                'type'        => 'autocomplete',
                'heading'     => esc_html__('Filter by categories', 'opalestate'),
                'value'       => '',
                'param_name'  => 'property_category',
                "admin_label" => true,
                'description' => esc_html__('Filter by categories', 'opalestate'),
                'settings'    => array(
                    'multiple'      => false,
                    'unique_values' => true,
                    // In UI show results except selected. NB! You should manually check values in backend
                ),
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Single location", "opalestate"),
        "base"        => "pbr_browse_single_location",
        'icon'        => 'icon-wpb-estates-6',
        "class"       => "",
        "description" => 'Single location',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "value"       => '',
                'description' => ''
            ),
            array(
                'type'        => 'autocomplete',
                'heading'     => esc_html__('Filter by location', 'opalestate'),
                'value'       => '',
                'param_name'  => 'opalestate_location',
                "admin_label" => true,
                'description' => esc_html__('Filter by location', 'opalestate'),
                'settings'    => array(
                    'multiple'      => false,
                    'unique_values' => true,
                    // In UI show results except selected. NB! You should manually check values in backend
                ),
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Single type", "opalestate"),
        "base"        => "pbr_browse_single_type",
        'icon'        => 'icon-wpb-estates-7',
        "class"       => "",
        "description" => 'Single type',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "value"       => '',
                'description' => ''
            ),
            array(
                'type'        => 'autocomplete',
                'heading'     => esc_html__('Filter by type', 'opalestate'),
                'value'       => '',
                'param_name'  => 'opalestate_types',
                "admin_label" => true,
                'description' => esc_html__('Filter by type', 'opalestate'),
                'settings'    => array(
                    'multiple'      => false,
                    'unique_values' => true,
                    // In UI show results except selected. NB! You should manually check values in backend
                ),
            ),
        )
    ));

    ///
    vc_map(array(
        "name"        => __("Featured Property", "opalestate"),
        "base"        => "pbr_featured_property",
        'icon'        => 'icon-wpb-estates-8',
        "class"       => "",
        "description" => 'Get data from post type Team',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                'type'        => 'colorpicker',
                'heading'     => esc_html__('Title Color', 'opalestate'),
                'param_name'  => 'title_color',
                'description' => esc_html__('Select font color', 'opalestate')
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "value"       => '',
                'description' => ''
            ),


            array(
                "type"        => "textfield",
                "heading"     => __("Limit", "opalestate"),
                "param_name"  => "limit",
                "value"       => 6,
                'description' => __('Limit featured properties showing', 'opalestate')
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Overlap Carousel Property", "opalestate"),
        "base"        => "pbr_estate_overlap_carousel_property",
        'icon'        => 'icon-wpb-estates-9',
        "class"       => "",
        "description" => 'Overlap Carousel Property',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "value"       => '',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Column", "opalestate"),
                "param_name"  => "column",
                "value"       => '4',
                'description' => ''
            ),

            array(
                "type"        => "textfield",
                "heading"     => __("Limit", "opalestate"),
                "param_name"  => "limit",
                "value"       => 6,
                'description' => __('Limit featured properties showing', 'opalestate')
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Carousel Property", "opalestate"),
        "base"        => "pbr_estate_carousel_property",
        'icon'        => 'icon-wpb-estates-10',
        "class"       => "",
        "description" => 'Get data from post type Team',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "value"       => '',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Column", "opalestate"),
                "param_name"  => "column",
                "value"       => '4',
                'description' => ''
            ),

            array(
                "type"        => "textfield",
                "heading"     => __("Limit", "opalestate"),
                "param_name"  => "limit",
                "value"       => 6,
                'description' => __('Limit featured properties showing', 'opalestate')
            ),

            array(
                "type"       => "dropdown",
                "heading"    => esc_html__("Enable Thumbnail", 'opalestate'),
                "param_name" => "enable_thumbnail",
                'value'      => array(
                    esc_html__('Disable', 'opalestate') => 0,
                    esc_html__('Enable', 'opalestate')  => 1,
                ),
                'std'        => 0
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Manual Carousel Properties", "opalestate"),
        "base"        => "pbr_estate_manual_carousel_properties",
        'icon'        => 'icon-wpb-estates-11',
        "class"       => "",
        "description" => 'Get data from post type Team',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),

            array(
                'type'       => 'autocomplete',
                "heading"    => __("Properties", "opalestate"),
                'param_name' => 'property_slugs',
                "value"      => '',
                'settings'   => array(
                    'multiple'      => true,
                    'unique_values' => true,
                    // In UI show results except selected. NB! You should manually check values in backend
                ),
            ),

            array(
                "type"        => "textfield",
                "heading"     => __("Column", "opalestate"),
                "param_name"  => "column",
                "value"       => 1,
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Limit", "opalestate"),
                "param_name"  => "limit",
                "value"       => 6,
                'description' => __('Limit featured properties showing', 'opalestate')
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Grid Properties", "opalestate"),
        "base"        => "pbr_estate_grid_property",
        'icon'        => 'icon-wpb-estates-12',
        "class"       => "",
        "description" => 'Get data from post type Team',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Show Description", "opalestate"),
                "param_name" => "show_description"
            ),
            array(
                "type"       => "textfield",
                "heading"    => __("Description", "opalestate"),
                "param_name" => "description",
                'dependency' => array(
                    'element' => 'show_description',
                    'not_empty' => true,
                ),
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Show Sort By", "opalestate"),
                "param_name" => "showsortby"
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Column", "opalestate"),
                "param_name"  => "column",
                "value"       => '4',
                'description' => ''
            ),

            array(
                "type"        => "textfield",
                "heading"     => __("Limit", "opalestate"),
                "param_name"  => "limit",
                "value"       => 6,
                'description' => __('Limit featured properties showing', 'opalestate')
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Pagination", "opalestate"),
                "param_name" => "pagination"
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Filter Properties", "opalestate"),
        "base"        => "pbr_estate_filter_property",
        'icon'        => 'icon-wpb-estates-13',
        "class"       => "",
        "description" => __('Display Properties By Selected Categories or Selected Locations', "opalestate"),
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Show Description", "opalestate"),
                "param_name" => "show_description"
            ),
            array(
                "type"       => "textfield",
                "heading"    => __("Description", "opalestate"),
                "param_name" => "description",
                'dependency' => array(
                    'element' => 'show_description',
                    'not_empty' => true,
                ),
            ),
            array(
                'type'        => 'autocomplete',
                'heading'     => esc_html__('Categories', 'opalestate'),
                'value'       => '',
                'param_name'  => 'property_category',
                "admin_label" => true,
                'description' => esc_html__('Select Categories', 'opalestate'),
                'settings'    => array(
                    'multiple'      => true,
                    'unique_values' => true,
                    // In UI show results except selected. NB! You should manually check values in backend
                ),
            ),
            array(
                'type'        => 'autocomplete',
                'heading'     => esc_html__('Locations', 'opalestate'),
                'value'       => '',
                'param_name'  => 'opalestate_location',
                "admin_label" => true,
                'description' => esc_html__('Select Locations', 'opalestate'),
                'settings'    => array(
                    'multiple'      => true,
                    'unique_values' => true,
                    // In UI show results except selected. NB! You should manually check values in backend
                ),
            ),
            array(
                'type'        => 'autocomplete',
                'heading'     => esc_html__('Types', 'opalestate'),
                'value'       => '',
                'param_name'  => 'opalestate_types',
                "admin_label" => true,
                'description' => esc_html__('Select Types', 'opalestate'),
                'settings'    => array(
                    'multiple'      => true,
                    'unique_values' => true,
                    // In UI show results except selected. NB! You should manually check values in backend
                ),
            ),
            array(
                'type'        => 'autocomplete',
                'heading'     => esc_html__('Status', 'opalestate'),
                'value'       => '',
                'param_name'  => 'opalestate_status',
                "admin_label" => true,
                'description' => esc_html__('Select Status', 'opalestate'),
                'settings'    => array(
                    'multiple'      => true,
                    'unique_values' => true,
                    // In UI show results except selected. NB! You should manually check values in backend
                ),
            ),
            array(
                "type"       => "dropdown",
                "heading"    => esc_html__("Type filter search", 'opalestate'),
                "param_name" => "type_filter",
                'value'      => array(
                    esc_html__('OR', 'opalestate')  => 'OR',
                    esc_html__('AND', 'opalestate') => 'AND',
                ),
                'std'        => 'OR'
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Show Sort By", "opalestate"),
                "param_name" => "showsortby"
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Column", "opalestate"),
                "param_name"  => "column",
                "value"       => '4',
                'description' => ''
            ),

            array(
                "type"        => "textfield",
                "heading"     => __("Limit", "opalestate"),
                "param_name"  => "limit",
                "value"       => 6,
                'description' => __('Limit featured properties showing', 'opalestate')
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Pagination", "opalestate"),
                "param_name" => "pagination"
            ),
        )
    ));

    vc_map(array(
        "name"        => __("List Property", "opalestate"),
        "base"        => "pbr_estate_list_property",
        'icon'        => 'icon-wpb-estates-14',
        "class"       => "",
        "description" => 'Display Properties In List Style',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Show Description", "opalestate"),
                "param_name" => "show_description"
            ),
            array(
                "type"       => "textfield",
                "heading"    => __("Description", "opalestate"),
                "param_name" => "description",
                'dependency' => array(
                    'element' => 'show_description',
                    'not_empty' => true,
                ),
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Show Sort By", "opalestate"),
                "param_name" => "showsortby"
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Column", "opalestate"),
                "param_name"  => "column",
                "value"       => '4',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Limit per page", "opalestate"),
                "param_name"  => "limit",
                "value"       => 10,
                'description' => __('Limit featured properties showing', 'opalestate')
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Pagination", "opalestate"),
                "param_name" => "pagination"
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Grid Agent 2", "opalestate"),
        "base"        => "pbr_estate_grid_agent_2",
        'icon'        => 'icon-wpb-estates-15',
        "class"       => "",
        "description" => 'Get data from post type Team',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "value"       => '',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Column", "opalestate"),
                "param_name"  => "column",
                "value"       => '4',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Limit", "opalestate"),
                "param_name"  => "limit",
                "value"       => 6,
                'description' => __('Limit featured agents showing', 'opalestate')
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Show Featured Only", "opalestate"),
                "param_name" => "onlyfeatured"
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Grid Agent", "opalestate"),
        "base"        => "pbr_estate_grid_agent",
        'icon'        => 'icon-wpb-estates-16',
        "class"       => "",
        "description" => 'Get data from post type Team',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "value"       => '',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Column", "opalestate"),
                "param_name"  => "column",
                "value"       => '4',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Limit", "opalestate"),
                "param_name"  => "limit",
                "value"       => 6,
                'description' => __('Limit featured agents showing', 'opalestate')
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Show Featured Only", "opalestate"),
                "param_name" => "onlyfeatured"
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Pagination", "opalestate"),
                "param_name" => "pagination"
            ),
        )
    ));

    vc_map(array(
        "name"        => __("List Agent", "opalestate"),
        "base"        => "pbr_estate_list_agent",
        'icon'        => 'icon-wpb-estates-17',
        "class"       => "",
        "description" => 'Get data from post type Team',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "value"       => '',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Column", "opalestate"),
                "param_name"  => "column",
                "value"       => '4',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Limit", "opalestate"),
                "param_name"  => "limit",
                "value"       => 6,
                'description' => __('Limit featured properties showing', 'opalestate')
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Show Featured Only", "opalestate"),
                "param_name" => "onlyfeatured"
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Pagination", "opalestate"),
                "param_name" => "pagination"
            ),
        )
    ));

    vc_map(array(
        "name"        => __("Grid Office", "opalestate"),
        "base"        => "pbr_estate_grid_office",
        'icon'        => 'icon-wpb-estates-18',
        "class"       => "",
        "description" => 'Get data from post type Office',
        "category"    => __('OpalEstate', "opalestate"),
        "params"      => array(
            array(
                "type"        => "textfield",
                "heading"     => __("Title", "opalestate"),
                "param_name"  => "title",
                "value"       => '',
                "admin_label" => true
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Description", "opalestate"),
                "param_name"  => "description",
                "value"       => '',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Column", "opalestate"),
                "param_name"  => "column",
                "value"       => '4',
                'description' => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __("Limit", "opalestate"),
                "param_name"  => "limit",
                "value"       => 6,
                'description' => __('Limit featured offices showing', 'opalestate')
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Show Featured Only", "opalestate"),
                "param_name" => "onlyfeatured"
            ),
            array(
                "type"       => "checkbox",
                "heading"    => __("Pagination", "opalestate"),
                "param_name" => "pagination"
            ),
        )
    ));

    class OpalEstate_Shortcode_Base extends WPBakeryShortCode {
        public function __construct($settings) {
            parent::__construct($settings);
            if (!file_exists(get_template_directory() . '/vc_templates/' . $this->settings['base'])) {
                $this->html_template = OPALESTATE_PLUGIN_DIR . 'templates/vc_templates/' . $this->settings['base'] . '.php';
            }
        }
    }

    class WPBakeryShortCode_Pbr_office_agent_search extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_Pbr_featured_property extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_Pbr_browse_categories extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_Pbr_browse_single_category extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_Pbr_browse_single_location extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_Pbr_browse_single_type extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_Pbr_estate_searchbox extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_Pbr_estate_search_agents extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_Pbr_estate_search_offices extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_pbr_estate_grid_property extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_pbr_estate_list_property extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_pbr_estate_filter_property extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_pbr_estate_grid_office extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_pbr_estate_grid_agent extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_pbr_estate_grid_agent_2 extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_pbr_estate_list_agent extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_pbr_estate_carousel_property extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_pbr_estate_overlap_carousel_property extends OpalEstate_Shortcode_Base {
    }

    class WPBakeryShortCode_pbr_estate_manual_carousel_properties extends OpalEstate_Shortcode_Base {
    }
}