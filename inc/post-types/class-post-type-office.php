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

/**
 * Class Opalestate_PostType_Office
 * 
 * @version 1.0
 */
class Opalestate_PostType_Office{

	/**
	 *
	 */
	public static function init(){
		add_action( 'init', array( __CLASS__, 'definition' ) );
		if( is_admin() ){
			add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes_target' ) );
			add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
		} 
		define( 'OPALESTATE_OFFICE_PREFIX', 'opalestate_ofe_' );
		
		add_action('opalestate_on_set_role_office', array( __CLASS__ , 'on_set_role'), 1, 9 );

		add_filter( 'opalestate_before_render_profile_office_form' , array( __CLASS__ , 'render_front_form') , 2 , 2 );	

		add_action( 'save_post',  array( __CLASS__ , 'on_save_post'), 13, 2 );
	}

	/**
	 *
	 */
	public static function on_save_post( $post_id ){
		$post_type = get_post_type($post_id);
		if( $post_type == 'opalestate_office' ){
			if( isset($_POST[OPALESTATE_OFFICE_PREFIX.'user_id']) && $_POST[OPALESTATE_OFFICE_PREFIX.'user_id'] ){  
				update_user_meta( $_POST[OPALESTATE_OFFICE_PREFIX.'user_id'] , OPALESTATE_USER_PROFILE_PREFIX . 'office_id', $post_id ); 
			}
		}
	}

	/**
	 *
	 */
	public static function definition(){

		$labels = array(
			'name'                  => __( 'Offices', 'opalestate' ),
			'singular_name'         => __( 'Property', 'opalestate' ),
			'add_new'               => __( 'Add New Office', 'opalestate' ),
			'add_new_item'          => __( 'Add New Office', 'opalestate' ),
			'edit_item'             => __( 'Edit Office', 'opalestate' ),
			'new_item'              => __( 'New Office', 'opalestate' ),
			'all_items'             => __( 'All Offices', 'opalestate' ),
			'view_item'             => __( 'View Office', 'opalestate' ),
			'search_items'          => __( 'Search Office', 'opalestate' ),
			'not_found'             => __( 'No Offices found', 'opalestate' ),
			'not_found_in_trash'    => __( 'No Offices found in Trash', 'opalestate' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Offices', 'opalestate' ),
		);

		$labels = apply_filters( 'opalestate_postype_office_labels' , $labels );

		register_post_type( 'opalestate_office',
			array(
				'labels'            => $labels,
				'supports'          => array( 'title', 'editor', 'thumbnail', 'comments', 'author', 'excerpt' ),
				'public'            => true,
				'has_archive'       => true,
				'rewrite'           => array( 'slug' => _x( 'property-office', 'URL Slug', 'opalestate' ) ),
				'menu_position'     => 51,
				'categories'        => array(),
				'menu_icon'         => 'dashicons-groups',

			)
		);


		///
		$labels = array(
	        'name'              => __( 'Office Categories', 'opalestate' ),
	        'singular_name'     => __( 'Category', 'opalestate' ),
	        'search_items'      => __( 'Search Category', 'opalestate' ),
	        'all_items'         => __( 'All Categories', 'opalestate' ),
	        'parent_item'       => __( 'Parent Category', 'opalestate' ),
	        'parent_item_colon' => __( 'Parent Category:', 'opalestate' ),
	        'edit_item'         => __( 'Edit Category', 'opalestate' ),
	        'update_item'       => __( 'Update Category', 'opalestate' ),
	        'add_new_item'      => __( 'Add New Category', 'opalestate' ),
	        'new_item_name'     => __( 'New Category Name', 'opalestate' ),
	        'menu_name'         => __( 'Office Categories', 'opalestate' ),
	      );
		///
		register_taxonomy('opalestate_office_cat',array('opalestate_office'),
          array(
              'hierarchical'      => true,
              'labels'            => $labels,
              'show_ui'           => true,
              'show_admin_column' => true,
              'query_var'         => true,
              'show_in_nav_menus' =>true,
              'rewrite'           => array( 'slug' => 'office-category'
          ),
      ));
	}

	/**
	 *
	 */
	public static function metaboxes_target( $metaboxes ){
		$prefix = OPALESTATE_OFFICE_PREFIX;
		$fields = array(
			array(
				'id'   => "{$prefix}user_id",
				'name' => __( 'Link To User ID', 'opalestate' ),
				'type' => 'text',
				'description'  => __( 'Set relationship to existed user, allow user can edit Office profile in front-end and show account info in each property.', 'opalestate' ),
				 

			),
			array(
			    'name'     => __('Agent Team' ,'opalestate'),
			    'desc'     => __('Select one, to add new you create in location of estate panel','opalestate'),
			    'id'       => $prefix."team",
			    'type'     => 'adduser',
			)
		); 
	    $metaboxes[ $prefix . 'target' ] = array(
			'id'                        => $prefix . 'target',
			'title'                     => __( 'Office Team', 'opalestate' ),
			'object_types'              => array( 'opalestate_office' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    =>  $fields
		);

	    return $metaboxes;
	}
	
	/**
	 *
	 */
	public static function metaboxes_fields( $prefix = '' ){
		if ( ! $prefix ) {
			$prefix = OPALESTATE_OFFICE_PREFIX;
		}

		$fields =  array(
		

			array(
				'id'   => "{$prefix}featured",
				'name' => __( 'Is Featured', 'opalestate' ),
				'type' => 'select',
				'description'  => __( 'Set this office as featured', 'opalestate' ),
				 'options'          => array(
			        0 => __( 'No', 'opalestate' ),
			        1  => __( 'Yes', 'opalestate' )
			    ),

			),
			array(
			    'name'     => __('Gallery' ,'opalestate'),
			    'desc'     => __('Select one, to add new you create in location of estate panel','opalestate'),
			    'id'       => $prefix."gallery",
			    'type'     => 'file_list',
			) ,
			array(
				'id'   => "{$prefix}avatar",
				'name' => __( 'Logo/Avatar', 'opalestate' ),
				'type' => 'file',
				'description'  => __( 'Select one or more images to show as gallery', 'opalestate' ),
			),

			array(
				'name' => __( 'slogan', 'opalestate' ),
				'id'   => "{$prefix}slogan",
				'type' => 'text'
			),

			array(
				'name' => __( 'email', 'opalestate' ),
				'id'   => "{$prefix}email",
				'type' => 'text'
			),

			array(
				'name' => __( 'Phone', 'opalestate' ),
				'id'   => "{$prefix}phone",
				'type' => 'text'
			),

			array(
				'name' => __( 'Mobile', 'opalestate' ),
				'id'   => "{$prefix}mobile",
				'type' => 'text'
			),

			array(
				'name' => __( 'Fax', 'opalestate' ),
				'id'   => "{$prefix}fax",
				'type' => 'text'
			),
			array(
				'name' => __( 'Website', 'opalestate' ),
				'id'   => "{$prefix}web",
				'type' => 'text_url'
			),

			

			array(
				'name' => __( 'Twitter', 'opalestate' ),
				'id'   => "{$prefix}twitter",
				'type' => 'text_url'
			),

			array(
				'name' => __( 'Facebook', 'opalestate' ),
				'id'   => "{$prefix}facebook",
				'type' => 'text_url'
			),

			array(
				'name' => __( 'Google', 'opalestate' ),
				'id'   => "{$prefix}google",
				'type' => 'text_url'
			),

			array(
				'name' => __( 'LinkedIn', 'opalestate' ),
				'id'   => "{$prefix}linkedin",
				'type' => 'text_url'
			),

			array(
				'name' => __( 'Pinterest', 'opalestate' ),
				'id'   => "{$prefix}pinterest",
				'type' => 'text_url'
			),
			array(
				'name' => __( 'Instagram', 'opalestate' ),
				'id'   => "{$prefix}instagram",
				'type' => 'text_url'
			),

			array(
			    'name'     => __('Location' ,'opalestate'),
			    'desc'     => __('Select one, to add new you create in location of estate panel','opalestate'),
			    'id'       => $prefix."location",
			    'taxonomy' => 'opalestate_location', //Enter Taxonomy Slug
			    'type'     => 'taxonomy_select',
			) ,

			array(
				'name' => __( 'Address', 'opalestate' ),
				'id'   => "{$prefix}address",
				'type' => 'text'
			),
			
			array(
				'id'            => "{$prefix}map",
				'name'          => __( 'Map Location', 'opalestate' ),
				'type'              => 'opal_map',
				'sanitization_cb'   => 'opal_map_sanitise',
                'split_values'      => true,
			),
		);
	
		return apply_filters( 'opalestate_postype_office_metaboxes_fields' , $fields );
	}

	/**
	 *
	 */
	public static function metaboxes(array $metaboxes){
		$prefix = OPALESTATE_OFFICE_PREFIX;

	    $metaboxes[ $prefix . 'info' ] = array(
			'id'                        => $prefix . 'info',
			'title'                     => __( 'Office Information', 'opalestate' ),
			'object_types'              => array( 'opalestate_office' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_fields( $prefix )
		);

	    return $metaboxes;
	}


	public static function on_set_role( $user_id ){
		if( $user_id ){
			
			$args = array(
				'post_type'         => 'opalestate_office',
				'posts_per_page'    => 10,
			);

 
			$args['meta_key'] 	  = OPALESTATE_OFFICE_PREFIX . 'user_id';
			$args['meta_value']   = $user_id;
			$args['meta_compare'] = '=';
		 
	 		$post = get_posts( $args );

	 		if( empty($post) ){


				$office_id = self::create_office( array(), $user_id ); 
				update_post_meta( $office_id, OPALESTATE_OFFICE_PREFIX . 'user_id', $user_id ); 
				update_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'office_id', $office_id ); 
			}	
		}
	}

	/**
	 *
	 */
	public static function create_office( $args = array(), $user_id ) {
		$data =  get_user_by( 'id', $user_id );

        $args = wp_parse_args( $args, array(
            'first_name'    => $data->first_name,
            'last_name'     => $data->last_name,
            'avatar'    => '',
            'job'       => '',
            'email'     => '',
            'phone'     => '',
            'mobile'    => '',
            'fax'       => '',
            'web'       => '',
            'address'   => '',
            'twitter'   => '',
            'facebook'  => '',
            'google'    => '',
            'linkedin'  => '',
            'instagram' => '',
        ) );


        $office_id = wp_insert_post( array(
                'post_title'    => $args['first_name'] && $args['last_name'] ? $args['first_name'] . ' ' . $args['last_name'] : __('User ID','opalestate').': '.$user_id,
                'post_content'  => 'empty description',
                'post_excerpt'  => 'empty excerpt',
                'post_type'     => 'opalestate_office',
                'post_status'   => 'pending',
                'post_author'   => $user_id
            ), true );

        
        do_action( 'opalesate_insert_user_office', $office_id );
        return $office_id;
    }

    /**
	 *
	 */
	public static function metaboxes_front_fields( $prefix = '', $post_id=0 ){
		if ( ! $prefix ) {
			$prefix = OPALESTATE_OFFICE_PREFIX;
		}
		$post = get_post( $post_id );

		$fields =  array(

			array(
				'name'              => __( 'Title', 'opalestate' ),
				'id'                => $prefix . 'title',
				'type'              => 'text_medium',
				'default'           => ! empty( $post ) ? $post->post_title : '',
				'attributes' => array(
			        'required' => 'required',
			    ),
			),
			array(
				'id'                => $prefix . 'post_type',
				'type'              => 'hidden',
				'default'           => 'opalestate_office',
			),
			array(
				'name'              => __( 'Description', 'opalestate' ),
				'id'                => $prefix . 'text',
				'type'              => 'wysiwyg',
				'default'           => ! empty( $post ) ? $post->post_content : '',
				'attributes' => array(
			        'required' => 'required',
			    ),
			),

			array(
				'id'    => "{$prefix}featured_image",
				'name'  => __( 'Featured Image', 'opalestate' ),
				'type'  => 'user_upload',
				'is_featured' => true,
				'limit' => 1,
				'description'  => __( 'Select one or more images to show as gallery', 'opalestate' ),
			),

			array(
				'id'    => "{$prefix}avatar",
				'name'  => __( 'Avatar/Logo', 'opalestate' ),
				'type'  => 'user_upload',
				'limit' => 1,
				'description'  => __( 'Select one or more images to show as gallery', 'opalestate' ),
			),


			array(
				'id'   => "{$prefix}gallery",
				'name' => __( 'Gallery', 'opalestate' ),
				'type' => 'user_upload',
				'description'  => __( 'Select one or more images to show as gallery', 'opalestate' ),
			),

			array(
				'name' => __( 'Slogan', 'opalestate' ),
				'id'   => "{$prefix}slogan",
				'type' => 'text'
			),

			array(
				'name' => __( 'Email', 'opalestate' ),
				'id'   => "{$prefix}email",
				'type' => 'text'
			),

			array(
				'name' => __( 'Phone', 'opalestate' ),
				'id'   => "{$prefix}phone",
				'type' => 'text'
			),

			array(
				'name' => __( 'Mobile', 'opalestate' ),
				'id'   => "{$prefix}mobile",
				'type' => 'text'
			),

			array(
				'name' => __( 'Fax', 'opalestate' ),
				'id'   => "{$prefix}fax",
				'type' => 'text'
			),
			array(
				'name' => __( 'Website', 'opalestate' ),
				'id'   => "{$prefix}web",
				'type' => 'text_url'
			),

			

			array(
				'name' => __( 'Twitter', 'opalestate' ),
				'id'   => "{$prefix}twitter",
				'type' => 'text_url'
			),

			array(
				'name' => __( 'Facebook', 'opalestate' ),
				'id'   => "{$prefix}facebook",
				'type' => 'text_url'
			),

			array(
				'name' => __( 'Google', 'opalestate' ),
				'id'   => "{$prefix}google",
				'type' => 'text_url'
			),

			array(
				'name' => __( 'LinkedIn', 'opalestate' ),
				'id'   => "{$prefix}linkedin",
				'type' => 'text_url'
			),

			array(
				'name' => __( 'Pinterest', 'opalestate' ),
				'id'   => "{$prefix}pinterest",
				'type' => 'text_url'
			),
			array(
				'name' => __( 'Instagram', 'opalestate' ),
				'id'   => "{$prefix}instagram",
				'type' => 'text_url'
			),

			array(
			    'name'     => __('Location' ,'opalestate'),
			    'desc'     => __('Select one, to add new you create in location of estate panel','opalestate'),
			    'id'       => $prefix."location",
			    'taxonomy' => 'opalestate_location', //Enter Taxonomy Slug
			    'type'     => 'taxonomy_select',
			    'attributes' => array(
			        'required' => 'required',
			    ),
			) ,

			array(
				'name' => __( 'Address', 'opalestate' ),
				'id'   => "{$prefix}address",
				'type' => 'text',
				'attributes' => array(
			        'required' => 'required',
			    ),
			),
			
			array(
				'id'            => "{$prefix}map",
				'name'          => __( 'Map Location', 'opalestate' ),
				'type'              => 'opal_map',
				'sanitization_cb'   => 'opal_map_sanitise',
                'split_values'      => true,
			),

			array(
			    'name'     => __('Agent Team' ,'opalestate'),
			    'desc'     => __('Select one, to add new you create in location of estate panel','opalestate'),
			    'id'       => $prefix."team",
			    
			    'type'     => 'adduser',
			) ,

		);
	
		return apply_filters( 'opalestate_postype_office_metaboxes_fields' , $fields );
	}

	/**
	 *
	 */
    public static function render_front_form( $metaboxes, $post_id=0 ){
    	$prefix = OPALESTATE_OFFICE_PREFIX;
	    $metaboxes[ $prefix . 'front' ] = array(
			'id'                        => $prefix . 'front',
			'title'                     => __( 'Office Information', 'opalestate' ),
			'object_types'              => array( 'opalestate_office' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_front_fields( $prefix, $post_id )
		);

    	return $metaboxes;
    }
}

Opalestate_PostType_Office::init();