<?php
/**
 * Opalestate_PostType_Property
 *
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
class Opalestate_PostType_Property {

	/**
	 * init action and filter data to define property post type
	 */
	public static function init() {
		add_action( 'init', [ __CLASS__, 'definition' ] );

		add_filter( 'cmb2_meta_boxes', [ __CLASS__, 'metaboxes' ] );
		add_filter( 'cmb2_meta_boxes', [ __CLASS__, 'fields_front' ], 9999 );

		add_action( 'transition_opalestate_property_status', [ __CLASS__, 'process_publish_property' ], 10, 1 );
		define( 'OPALESTATE_PROPERTY_PREFIX', 'opalestate_ppt_' );

		/* property column */
		add_filter( 'manage_opalestate_property_posts_columns', [ __CLASS__, 'columns' ] );
		add_action( 'manage_opalestate_property_posts_custom_column', [ __CLASS__, 'custom_columns' ], 10, 2 );

		add_action( 'save_post', [ __CLASS__, 'save_post' ], 1, 2 );

	}

	/**
	 *
	 */
	public static function save_post( $post_id, $post ) {
		if ( empty( $post_id ) || empty( $post ) ) {
			return;
		}

		// Dont' save meta boxes for revisions or autosaves
		if ( defined( 'DOING_AUTOSAVE' ) || is_int( wp_is_post_revision( $post ) ) || is_int( wp_is_post_autosave( $post ) ) ) {
			return;
		}

		if ( $post->post_type == 'opalestate_property' ) {

		}
	}

	/**
	 *
	 */
	public static function definition() {

		$labels = [
			'name'               => __( 'Properties', 'opalestate' ),
			'singular_name'      => __( 'Property', 'opalestate' ),
			'add_new'            => __( 'Add New Property', 'opalestate' ),
			'add_new_item'       => __( 'Add New Property', 'opalestate' ),
			'edit_item'          => __( 'Edit Property', 'opalestate' ),
			'new_item'           => __( 'New Property', 'opalestate' ),
			'all_items'          => __( 'All Properties', 'opalestate' ),
			'view_item'          => __( 'View Property', 'opalestate' ),
			'search_items'       => __( 'Search Property', 'opalestate' ),
			'not_found'          => __( 'No Properties found', 'opalestate' ),
			'not_found_in_trash' => __( 'No Properties found in Trash', 'opalestate' ),
			'parent_item_colon'  => '',
			'menu_name'          => __( 'Properties', 'opalestate' ),
		];

		$labels = apply_filters( 'opalestate_postype_property_labels', $labels );

		register_post_type( 'opalestate_property',
			apply_filters( 'opalestate_property_post_type_parameters', [
				'labels'              => $labels,
				'supports'            => [ 'title', 'editor', 'thumbnail', 'comments', 'author' ],
				'public'              => true,
				'has_archive'         => true,
				'rewrite'             => [ 'slug' => __( 'property', 'opalestate' ) ],
				'menu_position'       => 51,
				'categories'          => [],
				'menu_icon'           => 'dashicons-admin-home',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'query_var'           => true,
				'hierarchical'        => false, // Hierarchical causes memory issues - WP loads all records!
				'show_in_nav_menus'   => true,
			] )
		);

	}

	/**
	 *
	 */
	public static function metaboxes( array $metaboxes ) {
		$prefix = OPALESTATE_PROPERTY_PREFIX;


		$metaboxes[ $prefix . 'management' ] = [
			'id'           => $prefix . 'management',
			'title'        => __( 'Property Management', 'opalestate' ),
			'object_types' => [ 'opalestate_property' ],
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => self::metaboxes_management_fields(),
		];
		$metaboxes[ $prefix . 'pricing' ]    = [
			'id'           => $prefix . 'price',
			'title'        => __( 'Property Prices', 'opalestate' ),
			'type'         => 'text_money',
			'object_types' => [ 'opalestate_property' ],
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => self::metaboxes_price_fields(),
		];
		$metaboxes[ $prefix . 'info' ]       = [
			'id'           => $prefix . 'info',
			'title'        => __( 'Property Information', 'opalestate' ),
			'object_types' => [ 'opalestate_property' ],
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => self::metaboxes_info_fields(),
		];

		$metaboxes[ $prefix . 'public_facilities' ] = [
			'id'           => $prefix . 'public_facilities',
			'title'        => __( 'Public facilities', 'opalestate' ),
			'object_types' => [ 'opalestate_property' ],
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => self::metaboxes_public_facilities_fields(),
		];

		$metaboxes[ $prefix . 'agent' ] = [
			'id'           => $prefix . 'agent',
			'title'        => __( 'Agents Information', 'opalestate' ),
			'object_types' => [ 'opalestate_property' ],
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => self::metaboxes_agent_fields(),
		];

		$metaboxes[ $prefix . 'layout_template' ] = [
			'id'           => $prefix . 'layout',
			'title'        => __( 'Layout Template', 'opalestate' ),
			'object_types' => [ 'opalestate_property' ],
			'context'      => 'normal',
			'priority'     => 'high',
			'show_names'   => true,
			'fields'       => self::metaboxes_layout_fields(),
		];

		return $metaboxes;
	}

	/**
	 *
	 */
	public static function metaboxes_management_fields() {
		$prefix = OPALESTATE_PROPERTY_PREFIX;
		$fields = [
			[
				'name'    => __( 'Featured', 'opalestate' ),
				'id'      => $prefix . 'featured',
				'type'    => 'radio_inline',
				'options' => [
					0 => __( 'No', 'opalestate' ),
					1 => __( 'Yes', 'opalestate' ),
				],
				'default' => 0,
			],
			[
				'name'        => __( 'Property SKU', 'opalestate' ),
				'id'          => $prefix . 'sku',
				'type'        => 'text',
				'description' => __( 'Please Enter Your Property SKU', 'opalestate' ),
			],
			[
				'id'              => $prefix . 'map',
				'name'            => __( 'Location', 'opalestate' ),
				'type'            => 'opal_map',
				'sanitization_cb' => 'opal_map_sanitise',
				'split_values'    => true,
			],

			[
				'name' => __( 'Postal Code / Zip', 'opalestate' ),
				'id'   => $prefix . 'zipcode',
				'type' => 'text',

			],
			[
				'name'    => __( 'Google Map View', 'opalestate' ),
				'id'      => $prefix . 'enablemapview',
				'type'    => 'select',
				'options' => [
					1 => esc_html__( 'Yes', 'opalestate' ),
					0 => esc_html__( 'No', 'opalestate' ),
				],
			],

			[
				'name'       => esc_html__( 'Address', 'opalestate' ),
				'id'         => $prefix . 'address',
				'type'       => 'textarea_small',
				'attributes' => [
					'required' => 'required',
				],
			],

			[
				'id'          => "{$prefix}gallery",
				'name'        => esc_html__( 'Images Gallery', 'opalestate' ),
				'type'        => 'file_list',
				'description' => esc_html__( 'Select one or more images to show as gallery', 'opalestate' ),
			],

			[
				'id'          => "{$prefix}video",
				'name'        => esc_html__( 'Video', 'opalestate' ),
				'type'        => 'text_url',
				'description' => esc_html__( 'Input for videos, audios from Youtube, Vimeo and all supported sites by WordPress. It has preview feature.', 'opalestate' ),
			],

			[
				'id'          => "{$prefix}virtual",
				'name'        => esc_html__( '360° Virtual Tour', 'opalestate' ),
				'type'        => 'textarea_small',
				'description' => esc_html__( 'Input iframe to show 360° Virtual Tour.', 'opalestate' ),
			],

			[
				'id'          => "{$prefix}attachments",
				'name'        => esc_html__( 'Attachments', 'opalestate' ),
				'type'        => 'file_list',
				'options'     => [
					'url' => true,
				],
				'description' => esc_html__( 'Select one or more files to allow download', 'opalestate' ),
			],

		];

		return apply_filters( 'opalestate_postype_property_metaboxes_fields_management', $fields );
	}

	/**
	 *
	 */
	public static function metaboxes_price_fields() {
		$prefix   = OPALESTATE_PROPERTY_PREFIX;
		$currency = opalestate_currency_symbol() ? ' (' . opalestate_currency_symbol() . ')' : ' ($)';

		$fields = [
			[
				'id'          => $prefix . 'price',
				'name'        => esc_html__( 'Price', 'opalestate' ) . $currency,
				'type'        => 'text',
				'description' => esc_html__( 'Enter amount without currency', 'opalestate' ),
				'attributes'  => opalestate_get_option( 'require_input_price' ) ? [ 'required' => 'required' ] : '',
				'before_row'  => '<div class="row-group-features group-has-three group-price   clearfix">' . ( is_admin() ? '' : '<h3>' . esc_html__( 'Price', 'opalestate' ) . '</h3>' ), // callback
			],
			[
				'id'          => $prefix . 'saleprice',
				'name'        => esc_html__( 'Sale Price', 'opalestate' ) . $currency,
				'type'        => 'text',
				'description' => esc_html__( 'Enter amount without currency', 'opalestate' ),
			],
			[
				'id'          => $prefix . 'pricelabel',
				'name'        => esc_html__( 'Price Label', 'opalestate' ),
				'type'        => 'text',
				'attributes'  => opalestate_get_option( 'require_input_price' ) ? [ 'required' => 'required' ] : '',
				'description' => esc_html__( 'Price Label (e.g. "per month")', 'opalestate' ),
				'after_row'   => '</div>', // callback
			],

		];

		return apply_filters( 'opalestate_postype_property_metaboxes_fields_price', $fields );
	}

	/**
	 *
	 */
	public static function metaboxes_info_fields() {
		$prefix = OPALESTATE_PROPERTY_PREFIX;

		$fields = [
			[
				'name'        => __( 'Built year', 'opalestate' ),
				'id'          => $prefix . 'builtyear',
				'type'        => 'text_date',
				'date_format' => apply_filters( 'opalestate_build_year_date_format', 'yy' ),
				'description' => __( 'Enter built year', 'opalestate' ),
				'before_row'  => '<div class="row-group-features group-has-three group-property-info clearfix"><h3>' . ( is_admin() ? "" : __( 'Property Information', 'opalestate' ) ) . '</h3>',
			],

			[
				'name'        => __( 'Parking', 'opalestate' ),
				'id'          => $prefix . 'parking',
				'type'        => 'text',
				'description' => __( 'Enter number of Parking', 'opalestate' ),
			],

			[
				'name'        => __( 'Bedrooms', 'opalestate' ),
				'id'          => $prefix . 'bedrooms',
				'type'        => 'text',
				'description' => __( 'Enter number of bedrooms', 'opalestate' ),
			],
			[
				'name'        => __( 'Bathrooms', 'opalestate' ),
				'id'          => $prefix . 'bathrooms',
				'type'        => 'text',
				'description' => __( 'Enter number of bathrooms', 'opalestate' ),
			],
			[
				'name'        => __( 'Plot Size', 'opalestate' ),
				'id'          => $prefix . 'plotsize',
				'type'        => 'text',
				'description' => __( 'Enter size of Plot as 20x30, 20x30x40, 20x30x40x50', 'opalestate' ),
			],
			[
				'name'        => __( 'Area Size', 'opalestate' ),
				'id'          => $prefix . 'areasize',
				'type'        => 'text',
				'description' => __( 'Enter size of area in sqft', 'opalestate' ),
			],
			[
				'name'        => __( 'Orientation', 'opalestate' ),
				'id'          => "{$prefix}orientation",
				'type'        => 'text',
				'description' => __( 'Enter Orientation of property', 'opalestate' ),
			],

			[
				'name'        => __( 'Living Rooms', 'opalestate' ),
				'id'          => "{$prefix}livingrooms",
				'type'        => 'text',
				'description' => __( 'Enter Number of Living Rooms', 'opalestate' ),
			],

			[
				'name'        => __( 'Kitchens', 'opalestate' ),
				'id'          => "{$prefix}kitchens",
				'type'        => 'text',
				'description' => __( 'Enter Number of Kitchens', 'opalestate' ),
			],

			[
				'name'        => __( 'Rooms', 'opalestate' ),
				'id'          => "{$prefix}amountrooms",
				'type'        => 'text',
				'description' => __( 'Enter Number of Amount Rooms', 'opalestate' ),
				'after_row' => '</div>',
			],
		];

		return apply_filters( 'opalestate_postype_property_metaboxes_fields_info', $fields );
	}

	/**
	 *
	 */
	public static function metaboxes_public_facilities_fields() {
		$prefix = OPALESTATE_PROPERTY_PREFIX;
		$fields = [
			[
				'id'      => $prefix . 'public_facilities_group',
				'type'    => 'group',
				'fields'  => [
					[
						'id'   => $prefix . 'public_facilities_key',
						'name' => __( 'Label', 'opalestate' ),
						'type' => 'text',
					],
					[
						'id'   => $prefix . 'public_facilities_value',
						'name' => __( 'Content', 'opalestate' ),
						'type' => 'text',
					],
				],
				'options' => [
					'group_title'   => __( 'Facility {#}', 'opalestate' ),
					'add_button'    => __( 'Add Another Entry', 'opalestate' ),
					'remove_button' => __( 'Remove Entry', 'opalestate' ),
					'sortable'      => true,
					'closed'        => false,
				],
			],
		];

		return apply_filters( 'opalestate_postype_property_metaboxes_fields_public_facilities', $fields );
	}

	/**
	 *
	 */
	public static function metaboxes_agent_fields() {
		$prefix         = OPALESTATE_PROPERTY_PREFIX;
		$agents_objects = Opalestate_Query::get_agents( [
			'posts_per_page' => -1,
		] );
		$agents         = [ 0 => __( 'Use Author Information', 'opalestate' ) ];
		if ( ! empty( $agents_objects->posts ) && is_array( $agents_objects->posts ) ) {
			foreach ( $agents_objects->posts as $object ) {
				$agents[ $object->ID ] = $object->post_title;
			}
		}

		$fields = [
			[
				'name'    => __( 'Agent', 'opalestate' ),
				'id'      => "{$prefix}agent",
				'type'    => 'select',
				'options' => $agents,
			],
		];

		return apply_filters( 'opalestate_postype_property_metaboxes_fields_agent', $fields );
	}

	/**
	 *
	 */
	public static function metaboxes_layout_fields() {

		$prefix = OPALESTATE_PROPERTY_PREFIX;

		$fields = [
			[
				'name'        => __( 'Layout Display', 'opalestate' ),
				'id'          => "{$prefix}layout",
				'type'        => 'select',
				'options'     => apply_filters( 'opalestate_single_layout_templates', [ '' => __( 'Inherit', 'opalestate' ) ] ),
				'description' => __( 'Select a layout to display full information of this property', 'opalestate' ),
			],
		];

		return apply_filters( 'opalestate_postype_property_metaboxes_fields_agent', $fields );
	}

	/**
	 * Defines custom front end fields
	 *
	 * @access public
	 * @param array $metaboxes
	 * @return array
	 */
	public static function fields_front( array $metaboxes ) {

		$prefix = OPALESTATE_PROPERTY_PREFIX;

		if ( ! is_admin() ) {
			$management = [
				[
					'name'       => __( 'Address', 'opalestate' ),
					'id'         => $prefix . 'address',
					'type'       => 'text',
					'attributes' => [
						'required' => 'required',
					],
					'before_row' => '<div class="row-group-features group-has-two clearfix"><h3>' . __( 'Property Location', 'opalestate' ) . '</h3>', // callback

				],


				[
					'name'       => __( 'Location', 'opalestate' ),
					'id'         => $prefix . 'location',
					'type'       => 'taxonomy_select',
					'taxonomy'   => 'opalestate_location',
					'attributes' => [
						'required' => 'required',
					],

				],

				[
					'name' => __( 'Postal Code / Zip', 'opalestate' ),
					'id'   => $prefix . 'zipcode',
					'type' => 'text',

				],
				[
					'name'      => __( 'Google Map View', 'opalestate' ),
					'id'        => $prefix . 'enablemapview',
					'type'      => 'select',
					'options'   => [
						0 => __( 'No', 'opalestate' ),
						1 => __( 'Yes', 'opalestate' ),
					],
					'after_row' => '</div>',
				],
				[
					'id'              => $prefix . 'map',
					'name'            => __( 'Google Map', 'opalestate' ),
					'type'            => 'opal_map',
					'sanitization_cb' => 'opal_map_sanitise',
					'split_values'    => true,
				],

			];

			$fields = array_merge(
				self::metaboxes_general_fields_front(),
				self::metaboxes_price_fields(),
				$management,
				self::metaboxes_info_fields(),
				self::metaboxes_taxonomies_fields(),
				self::metaboxes_public_facilities_fields()

			);

			$metaboxes[ $prefix . 'front' ] = [
				'id'           => $prefix . 'front',
				'title'        => __( 'Name and Description', 'opalestate' ),
				'object_types' => [ 'opalestate_property' ],
				'context'      => 'normal',
				'priority'     => 'high',
				'show_names'   => true,
				'fields'       => $fields,
			];


		}

		return $metaboxes;
	}

	/**
	 *
	 */
	public static function metaboxes_general_fields_front() {
		$prefix = OPALESTATE_PROPERTY_PREFIX;
		if ( ! empty( $_GET['id'] ) ) {
			$post           = get_post( $_GET['id'] );
			$featured_image = wp_get_attachment_image_src( get_post_thumbnail_id( $_GET['id'] ) );
		}

		$fields = [
			[
				'id'      => $prefix . 'post_type',
				'type'    => 'hidden',
				'default' => 'opalestate_property',
			],
			[
				'name'       => __( 'Title', 'opalestate' ),
				'id'         => $prefix . 'title',
				'type'       => 'text_medium',
				'default'    => ! empty( $post ) ? $post->post_title : '',
				'attributes' => [
					'required' => 'required',
				],
			],
			[
				'name'       => __( 'Description', 'opalestate' ),
				'id'         => $prefix . 'text',
				'type'       => 'wysiwyg',
				'default'    => ! empty( $post ) ? $post->post_content : '',
				'attributes' => [
					'required' => 'required',
				],
			],


			[
				'name'       => __( 'Statuses', 'opalestate' ),
				'id'         => $prefix . 'status',
				'type'       => 'taxonomy_select',
				'taxonomy'   => 'opalestate_status',
				'class'      => 'form-control',
				'attributes' => [
					'required' => 'required',
				],
				'before_row' => '<div class="group-has-two clearfix">', // callback

			],
			[
				'name'       => __( 'Types', 'opalestate' ),
				'id'         => $prefix . 'type',
				'type'       => 'taxonomy_select',
				'taxonomy'   => 'opalestate_types',
				'class'      => 'form-control',
				'attributes' => [
					'required' => 'required',
				],
				'after_row'  => '</div>', // callback
			],

			[
				'id'          => "{$prefix}gallery",
				'name'        => __( 'Images Gallery', 'opalestate' ),
				'type'        => 'opal_upload',
				'description' => __( 'Select one or more images to show as gallery', 'opalestate' ),
				'before_row'  => '<div class="row-group-features clearfix"><h3>' . __( 'Media', 'opalestate' ) . '</h3>', // callback
			],

			[
				'id'          => "{$prefix}video",
				'name'        => __( 'Video', 'opalestate' ),
				'type'        => 'text',
				'description' => __( 'Input for videos, audios from Youtube, Vimeo and all supported sites by WordPress. It has preview feature.', 'opalestate' ),
				'before_row'  => '</div>',
			],

			[
				'id'          => "{$prefix}virtual",
				'name'        => __( '360° Virtual Tour', 'opalestate' ),
				'type'        => 'textarea_small',
				'description' => __( 'Input iframe to show 360° Virtual Tour.', 'opalestate' ),
			],
		];

		if ( is_admin() ) {
			$fields[] = [
				'name'    => __( 'Featured Image', 'opalestate' ),
				'id'      => $prefix . 'featured_image',
				'type'    => 'file',
				'default' => ! empty( $featured_image ) ? $featured_image[0] : '',
			];
		}

		return apply_filters( 'opalestate_postype_property_metaboxes_fields_general', $fields );
	}

	/**
	 * @return mixed|void
	 */
	public static function metaboxes_taxonomies_fields() {
		$prefix = OPALESTATE_PROPERTY_PREFIX;
		$fields = [
			[
				'name'       => __( 'Amenities', 'opalestate' ),
				'id'         => $prefix . 'amenity',
				'type'       => 'taxonomy_multicheck',
				'taxonomy'   => 'opalestate_amenities',
				'before_row' => '<div class="row-group-features group-td-full group-amenities clearfix"><h3>' . __( 'Property Amenities', 'opalestate' ) . '</h3>', // callback
				'after_row'  => '</div>',
			],
		];

		return apply_filters( 'opalestate_postype_property_metaboxes_fields_taxonomies', $fields );
	}

	/**
	 * @param $post
	 * @return bool
	 */
	public static function process_publish_property( $post ) {
		if ( $old_status == 'pending' && $new_status == 'publish' ) {
			$user_id = $post->post_author;
			$user    = get_user_by( 'id', $user_id );
			if ( ! is_object( $user ) ) {
				$from_name  = opalestate_get_option( 'from_name' );
				$from_email = opalestate_get_option( 'from_email' );
				$subject    = opalestate_get_option( 'publish_submission_email_subject' );

				$headers = sprintf( "From: %s <%s>\r\n Content-type: text/html", $from_name, $from_email );

				$property_link = get_permalink( $post );
				$tags          = [ "{first_name}", "{last_name}", "{property_link}" ];
				$values        = [ $user->first_name, $user->last_name, $property_link ];

				$body    = opalestate_get_option( 'publish_submission_email_body' );
				$body    = html_entity_decode( $body );
				$message = str_replace( $tags, $values, $body );

				return wp_mail( $user->user_email, $subject, $message, $headers );
			}
		}
	}

	/**
	 *
	 */
	public static function columns( $columns ) {
		$comments = $columns['comments'];
		unset( $columns['author'], $columns['date'], $columns['comments'] );
		$columns['featured'] = __( 'Featured', 'opalestate' );
		$columns['sku']      = __( 'Sku', 'opalestate' );
		$columns['address']  = __( 'Address', 'opalestate' );
		$columns['comments'] = $comments;
		$columns['author']   = __( 'Author', 'opalestate' );
		$columns['date']     = __( 'Date', 'opalestate' );

		return $columns;
	}

	/**
	 *
	 */
	public static function custom_columns( $column, $post_id ) {
		$property = new Opalestate_Property( $post_id );
		$nonce    = wp_create_nonce( 'opalestate_property' );
		switch ( $column ) {
			case 'featured':
				if ( $property->featured ) {
					$url = add_query_arg( [
						'action'      => 'opalestate_remove_feature_property',
						'property_id' => $post_id,
						'nonce'       => $nonce,
					], admin_url( 'admin-ajax.php' ) );
					echo '<a href="' . esc_url( $url ) . '">';
					echo '<i class="dashicons dashicons-star-filled"></i>';
					echo '</a>';
				} else {
					$url = add_query_arg( [
						'action'      => 'opalestate_set_feature_property',
						'property_id' => $post_id,
						'nonce'       => $nonce,
					], admin_url( 'admin-ajax.php' ) );
					echo '<a href="' . esc_url( $url ) . '">';
					echo '<i class="dashicons dashicons-star-empty"></i>';
					echo '</a>';
				}
				break;

			case 'sku':
				if ( $property->sku ) {
					echo sprintf( '%s', $property->sku );
				}
				break;

			case 'address':
				if ( $property->address ) {
					echo sprintf( '%s', $property->address );
				}
				break;

			default:
				# code...
				break;
		}
	}

}

Opalestate_PostType_Property::init();
