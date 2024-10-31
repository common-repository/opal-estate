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
class Opalestate_PostType_Agent{

	/**
	 *
	 */
	public static function init(){
		add_action( 'init', array( __CLASS__, 'definition' ) );
		if( is_admin() ){
			add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes_target' ) );
			add_filter( 'cmb2_meta_boxes', array( __CLASS__, 'metaboxes' ) );
			add_action( 'save_post', array( __CLASS__, 'save_post' ) , 10, 3 );

			add_action('opalestate_on_set_role_agent', array( __CLASS__ , 'on_set_role'), 1, 9 );
		}
		add_filter( 'opalestate_before_render_profile_agent_form' , array( __CLASS__ , 'render_front_form') , 2 , 2 );	

		define( 'OPALESTATE_AGENT_PREFIX', 'opalestate_agt_' );
	}

	public static function on_set_role( $user_id ){
		if( $user_id ){
			
			$args = array(
				'post_type'         => 'opalestate_agent',
				'posts_per_page'    => 10,
			);

 
			$args['meta_key'] 	  = OPALESTATE_AGENT_PREFIX . 'user_id';
			$args['meta_value']   = $user_id;
			$args['meta_compare'] = '=';
		 
	 		$post = get_posts( $args );
	 	
	 		if( empty($post) ){
				$agent_id = self::create_agent( array(), $user_id ); 
				update_post_meta( $agent_id, OPALESTATE_AGENT_PREFIX . 'user_id', $user_id ); 
				update_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'agent_id', $agent_id ); 
			}	
		}
	}

	public static function create_agent( $args = array(), $user_id ) {

		$data =  get_user_by( 'id', $user_id );

        $args = wp_parse_args( $args, array(
            'first_name'    => $data->first_name,
            'last_name'     => $data->last_name,
            'post_author'	=> $user_id,
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

        $agent_id = wp_insert_post( array(
                'post_title'    => $args['first_name'] && $args['last_name'] ? $args['first_name'] . ' ' . $args['last_name'] : __('User ID','opalestate').': '.$user_id,
                'post_content'  => '',
                'post_excerpt'  => '',
                'post_type'     => 'opalestate_agent',
                'post_status'   => 'pending',
                'post_author'   => $user_id
        ), true );

        
        do_action( 'opalesate_insert_user_agent', $agent_id );
        return $agent_id;
    }


	/**
	 *
	 */
	public static function definition(){

		$labels = array(
			'name'                  => __( 'Agents', 'opalestate' ),
			'singular_name'         => __( 'Property', 'opalestate' ),
			'add_new'               => __( 'Add New Agent', 'opalestate' ),
			'add_new_item'          => __( 'Add New Agent', 'opalestate' ),
			'edit_item'             => __( 'Edit Agent', 'opalestate' ),
			'new_item'              => __( 'New Agent', 'opalestate' ),
			'all_items'             => __( 'All Agents', 'opalestate' ),
			'view_item'             => __( 'View Agent', 'opalestate' ),
			'search_items'          => __( 'Search Agent', 'opalestate' ),
			'not_found'             => __( 'No Agents found', 'opalestate' ),
			'not_found_in_trash'    => __( 'No Agents found in Trash', 'opalestate' ),
			'parent_item_colon'     => '',
			'menu_name'             => __( 'Agents', 'opalestate' ),
		);

		$labels = apply_filters( 'opalestate_postype_agent_labels' , $labels );

		register_post_type( 'opalestate_agent',
			array(
				'labels'            => $labels,
				'supports'          => array( 'title', 'editor', 'thumbnail', 'comments', 'author', 'excerpt' ),
				'public'            => true,
				'has_archive'       => true,
				'rewrite'           => array( 'slug' => _x( 'property-agent', 'URL Slug', 'opalestate' ) ),
				'menu_position'     => 51,
				'categories'        => array(),
				'menu_icon'         => 'dashicons-groups',

			)
		);


		///
		$labels = array(
	        'name'              => __( 'Agent Levels', 'opalestate' ),
	        'singular_name'     => __( 'Level', 'opalestate' ),
	        'search_items'      => __( 'Search Level', 'opalestate' ),
	        'all_items'         => __( 'All Levels', 'opalestate' ),
	        'parent_item'       => __( 'Parent Level', 'opalestate' ),
	        'parent_item_colon' => __( 'Parent Level:', 'opalestate' ),
	        'edit_item'         => __( 'Edit Level', 'opalestate' ),
	        'update_item'       => __( 'Update Level', 'opalestate' ),
	        'add_new_item'      => __( 'Add New Level', 'opalestate' ),
	        'new_item_name'     => __( 'New Level Name', 'opalestate' ),
	        'menu_name'         => __( 'Agent Levels', 'opalestate' ),
	      );
		///
		register_taxonomy('opalestate_agent_level',array('opalestate_agent'),
          array(
              'hierarchical'      => true,
              'labels'            => $labels,
              'show_ui'           => true,
              'show_admin_column' => true,
              'query_var'         => true,
              'show_in_nav_menus' =>true,
              'rewrite'           => array( 'slug' => 'agent-level'
          ),
      ));
	}

	public static function metaboxes_target( $metaboxes ){
		$prefix = OPALESTATE_AGENT_PREFIX;
		$fields = array(
			array(
				'id'   => "{$prefix}user_id",
				'name' => __( 'Link to User', 'opalestate' ),
				'type' => 'text',
				'description'  => __( 'Enter User ID to show information without using user info', 'opalestate' ),
			),

			array(
				'id'   => "{$prefix}target_min_price",
				'name' => __( 'Target Min Price', 'opalestate' ),
				'type' => 'text',
				'description'  => __( 'Enter min price of property which is for sale/rent...', 'opalestate' ),
			),

			array(
				'id'   => "{$prefix}target_max_price",
				'name' => __( 'Target Max Price', 'opalestate' ),
				'type' => 'text',
				'description'  => __( 'Enter max price of property which is for sale/rent...', 'opalestate' ),
			),

			array(
			    'name'     => __('Location' ,'opalestate'),
			    'desc'     => __('Select one, to add new you create in location of estate panel','opalestate'),
			    'id'       => $prefix."location",
			    'taxonomy' => 'opalestate_location', //Enter Taxonomy Slug
			    'type'     => 'taxonomy_select',
			) ,

			array(
			    'name'     => __('Types' ,'opalestate'),
			    'desc'     => __('Select one, to add new you create in location of estate panel','opalestate'),
			    'id'       => $prefix."type",
			    'taxonomy' => 'opalestate_types', //Enter Taxonomy Slug
			    'type'     => 'taxonomy_select',
			) ,

		); 
	    $metaboxes[ $prefix . 'target' ] = array(
			'id'                        => $prefix . 'target',
			'title'                     => __( 'Agent For Seachable', 'opalestate' ),
			'object_types'              => array( 'opalestate_agent' ),
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
			$prefix = OPALESTATE_AGENT_PREFIX;
		}

		$fields =  array(
			array(
				'id'   => "{$prefix}featured",
				'name' => __( 'Is Featured', 'opalestate' ),
				'type' => 'select',
				'description'  => __( 'Set this agent as featured', 'opalestate' ),
				 'options'          => array(
			        0 => __( 'No', 'opalestate' ),
			        1  => __( 'Yes', 'opalestate' )
			    ),

			),

			array(
				'id'   => "{$prefix}avatar",
				'name' => __( 'Avatar', 'opalestate' ),
				'type' => 'file',
				'description'  => __( 'Select one or more images to show as gallery', 'opalestate' ),
			),

			array(
				'name' => __( 'job', 'opalestate' ),
				'id'   => "{$prefix}job",
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
	
		return apply_filters( 'opalestate_postype_agent_metaboxes_fields' , $fields );
	}

	/**
	 *
	 */
	public static function metaboxes(array $metaboxes){
		$prefix = OPALESTATE_AGENT_PREFIX;

	    $metaboxes[ $prefix . 'info' ] = array(
			'id'                        => $prefix . 'info',
			'title'                     => __( 'Agent Information', 'opalestate' ),
			'object_types'              => array( 'opalestate_agent' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_fields( $prefix )
		);

	    return $metaboxes;
	}

	public static function save_post( $post_id, $post, $update ){
		$post_type = get_post_type($post_id);
		if( $post_type == 'opalestate_agent' ){
			if(  isset($_POST[OPALESTATE_AGENT_PREFIX.'user_id']) && $_POST[OPALESTATE_AGENT_PREFIX.'user_id'] ){

				update_user_meta( $_POST[OPALESTATE_AGENT_PREFIX.'user_id'] , OPALESTATE_USER_PROFILE_PREFIX . 'agent_id', $post_id ); 
			}
		}
	}

	/**
	 *
	 */
	public static function metaboxes_front_fields( $prefix = '', $post_id=0 ){
		if ( ! $prefix ) {
			$prefix = OPALESTATE_AGENT_PREFIX;
		}
		$post = get_post( $post_id );
 
		$fields =  array(


			array(
				'id'    => "{$prefix}avatar",
				'name'  => __( 'Avatar/Logo', 'opalestate' ),
				'type'  => 'user_upload',
				'limit' => 1,
				'description'  => __( 'Select one or more images to show as gallery', 'opalestate' ),
				'before_row' => '<div class="'.apply_filters('opalestate_row_container_class', 'row opal-row').'"> <div class="col-lg-4">',
				'after_row' => '</div>'
			),


			array(
				'name'              => __( 'Title', 'opalestate' ),
				'id'                => $prefix . 'title',
				'type'              => 'text_medium',
				'default'           => ! empty( $post ) ? $post->post_title : '',
				'attributes' => array(
			        'required' => 'required',
			    ),
			    'before_row' => '<div class="col-lg-8">',
			),
			array(
				'id'                => $prefix . 'post_type',
				'type'              => 'hidden',
				'default'           => 'opalestate_agent',
			),
			

			array(
				'id'   => $prefix. "excerpt",
				'name' => __( 'Biographical', 'opalestate' ),
				'type' => 'textarea',
				'default'           => ! empty( $post ) ? $post->post_excerpt : '',
				'attributes' => array(
			        'required' => 'required',
			    ),
				'description'  => __( 'Input for videos, audios from Youtube, Vimeo and all supported sites by WordPress. It has preview feature.', 'opalestate' ),
				
				'after_row' => '</div></div>'
			),

			array(
				'name'              => __( 'Information', 'opalestate' ),
				'id'                => $prefix. 'text',
				'type'              => 'wysiwyg',
				'default'           => ! empty( $post ) ? $post->post_content : '',
				'attributes' => array(
			        'required' => 'required',
			    ),
			),

			array(
				'id'    => "{$prefix}featured_image",
				'name'  => __( 'Banner', 'opalestate' ),
				'type'  => 'user_upload',
				'is_featured' => true,
				'limit' => 1,
				'description'  => __( 'Select one or more images to show as gallery', 'opalestate' ),
			),

			array(
			    'name'     => __('Types' ,'opalestate'),
			    'desc'     => __('Select one, to add new you create in location of estate panel','opalestate'),
			    'id'       => $prefix."type",
			    'taxonomy' => 'opalestate_types', //Enter Taxonomy Slug
			    'type'     => 'taxonomy_select',
			     'before_row'   => '<div class="row-group-features group-has-one group-price clearfix"><h3>'.__('Searchable','opalestate').'</h3>', // callback
			) ,
			array(
				'id'   => "{$prefix}target_min_price",
				'name' => __( 'Target Min Price', 'opalestate' ),
				'type' => 'text',
				'description'  => __( 'Enter min price of property which is for sale/rent...', 'opalestate' ),
			),

			array(
				'id'   => "{$prefix}target_max_price",
				'name' => __( 'Target Max Price', 'opalestate' ),
				'type' => 'text',
				'description'  => __( 'Enter max price of property which is for sale/rent...', 'opalestate' ),
				'after_row'	   => '</div>'
			),
			array(
				'name' => __( 'Job/Position', 'opalestate' ),
				'id'   => "{$prefix}job",
				'type' => 'text',
				'before_row'   => '<div class="row-group-features group-has-two group-price clearfix"><h3>'.__('Information','opalestate').'</h3>', // callback
			),

			array(
				'name' => __( 'Email', 'opalestate' ),
				'id'   => "{$prefix}email",
				'type' => 'text',
				'attributes' => array(
			        'required' => 'required',
			    ),
			),

			array(
				'name' => __( 'Phone', 'opalestate' ),
				'id'   => "{$prefix}phone",
				'type' => 'text',
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
				'type' => 'text_url',
				'after_row'   =>  '</div>'
			),

			

			array(
				'name' => __( 'Twitter', 'opalestate' ),
				'id'   => "{$prefix}twitter",
				'type' => 'text_url',
				'before_row'   => '<div class="row-group-features group-has-two group-price clearfix"><h3>'.__('Social','opalestate').'</h3>', // callback
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
				'type' => 'text_url',
				'after_row'   =>  '</div>'
			),

			array(
			    'name'     => __('Location' ,'opalestate'),
			    'desc'     => __('Select one, to add new you create in location of estate panel','opalestate'),
			    'id'       => $prefix."location",
			    'taxonomy' => 'opalestate_location', //Enter Taxonomy Slug
			    'type'     => 'taxonomy_select',
			    'before_row'   => '<div class="row-group-features group-has-one group-price clearfix"><h3>'.__('Address','opalestate').'</h3>', // callback
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
                'attributes' => array(
			        'required' => 'required',
			    ),
                'after_row'   =>  '</div>'
			)
		);
	
		return apply_filters( 'opalestate_postype_office_metaboxes_fields' , $fields );
	}

    public static function render_front_form( $metaboxes, $post_id=0 ){
    	$prefix = OPALESTATE_AGENT_PREFIX;
	    $metaboxes[ $prefix . 'front' ] = array(
			'id'                        => $prefix . 'front',
			'title'                     => __( 'Office Information', 'opalestate' ),
			'object_types'              => array( 'opalestate_agent' ),
			'context'                   => 'normal',
			'priority'                  => 'high',
			'show_names'                => true,
			'fields'                    => self::metaboxes_front_fields( $prefix, $post_id )
		);

    	return $metaboxes;
    }

}

Opalestate_PostType_Agent::init();