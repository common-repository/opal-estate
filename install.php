<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit();
}


function opalestate_install(){

	global $opalestate_options;

	// Clear the permalinks
	flush_rewrite_rules( false );

	// Add Upgraded From Option
	$current_version = get_option( 'opalestate_version' );
	if ( $current_version ) {
		update_option( 'opalestate_version_upgraded_from', $current_version );
	}

	// Setup some default options
	$options = array();

	// Checks if the Success Page option exists AND that the page exists
	if ( ! get_post( opalestate_get_option( 'submission_page' ) ) ) {
		// Purchase Confirmation (Success) Page
		$submission_page = wp_insert_post(
			array(
				'post_title'     => __( 'Property Submission Page', 'opalestate' ),
				'post_content'   => __( '[opalestate_submission]', 'opalestate' ),
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		// Store our page IDs
		$options['submission_page'] = $submission_page;

		$options['submission_warning_content']     =   trim(preg_replace('/\t+/', '', " 
							<h3>Login to your account</h3> <br>
							Logining in allows you to edit your property or submit a property, save favorite real estate
							[opalmembership_login_form]
	 	"));

	}

	// Checks if the Success Page option exists AND that the page exists
	if ( ! get_post( opalestate_get_option( 'saved_link_page' ) ) ) {
		// Purchase Confirmation (Success) Page
		$saved_link_page = wp_insert_post(
			array(
				'post_title'     => __( 'My Saved Search', 'opalestate' ),
				'post_content'   => __( '[opalestate_user_saved_search]', 'opalestate' ),
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		// Store our page IDs
		$options['saved_link_page'] = $saved_link_page;
	}

	// Checks if the Success Page option exists AND that the page exists
	if ( ! get_post( opalestate_get_option( 'submission_list_page' ) ) ) {
		// Purchase Confirmation (Success) Page
		$submission_list_page = wp_insert_post(
			array(
				'post_title'     => __( 'My Properties', 'opalestate' ),
				'post_content'   => __( '[submission_list_page]', 'opalestate' ),
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		// Store our page IDs
		$options['submission_list_page'] = $submission_list_page;
	}

	// Checks if the Success Page option exists AND that the page exists
	if ( ! get_post( opalestate_get_option( 'profile_page' ) ) ) {
		// Purchase Confirmation (Success) Page
		$profile_page = wp_insert_post(
			array(
				'post_title'     => __( 'Profile Page', 'opalestate' ),
				'post_content'   => __( '[opalestate_user_profile]', 'opalestate' ),
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		// Store our page IDs
		$options['profile_page'] = $profile_page;
	}

	// Checks if the Success Page option exists AND that the page exists
	if ( ! get_post( opalestate_get_option( 'favorite_page' ) ) ) {
		// Purchase Confirmation (Success) Page
		$favorite_page = wp_insert_post(
			array(
				'post_title'     => __( 'Favorite Page', 'opalestate' ),
				'post_content'   => __( '[opalestate_user_favious_properties]', 'opalestate' ),
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		// Store our page IDs
		$options['favorite_page'] = $favorite_page;
	}


	// Checks if the Success Page option exists AND that the page exists
	if ( ! get_post( opalestate_get_option( 'search_agents' ) ) ) {
		// Purchase Confirmation (Success) Page
		$search_agents = wp_insert_post(
			array(
				'post_title'     => __( 'Favorite Page', 'opalestate' ),
				'post_content'   => __( '[opalestate_search_agents]', 'opalestate' ),
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		// Store our page IDs
		$options['search_agents'] = $search_agents;
	}
	

	// Checks if the Success Page option exists AND that the page exists
	if ( ! get_post( opalestate_get_option( 'search_map_properties_page' ) ) ) {
		// Purchase Confirmation (Success) Page
		$search_map_properties_page = wp_insert_post(
			array(
				'post_title'     => __( 'Search Map Properties Page', 'opalestate' ),
				'post_content'   => __( '[opalestate_search_map_properties]', 'opalestate' ),
				'post_status'    => 'publish',
				'post_author'    => 1,
				'post_type'      => 'page',
				'comment_status' => 'closed'
			)
		);

		// Store our page IDs
		$options['search_map_properties_page'] = $search_map_properties_page;
	}
	
	//Fresh Install? Setup Test Mode, Base Country (US), Test Gateway, Currency
	if ( empty( $current_version ) ) {
		$options['test_mode']          = 1;
		$options['currency']           = 'USD';
		$options['currency_position']  = 'before';
		$options['sq ft']              = 'sq ft';
		$options['google_map_api']     = 'AIzaSyDRVUZdOrZ1HuJFaFkDtmby0E93eJLykIk';

		$options['admin_approve']     = 'on';
		$options['require_input_price'] = 'on';

		$options['from_name']     =   get_bloginfo( 'name' );

		$options['from_email']     = get_bloginfo( 'admin_email' );

		$options[OPALESTATE_PROPERTY_PREFIX.'bedrooms_opt']  = 1;
		$options[OPALESTATE_PROPERTY_PREFIX.'parking_opt'] 	 = 1;
		$options[OPALESTATE_PROPERTY_PREFIX.'bathrooms_opt'] = 1;
		$options[OPALESTATE_PROPERTY_PREFIX.'areasize_opt']  = 1;
		$options[OPALESTATE_PROPERTY_PREFIX.'price_opt'] 	 = 1;

		$options[OPALESTATE_PROPERTY_PREFIX.'bedrooms_opt_v']  = 1;
		$options[OPALESTATE_PROPERTY_PREFIX.'parking_opt_v']   = 1;
		$options[OPALESTATE_PROPERTY_PREFIX.'bathrooms_opt_v'] = 1;
		$options[OPALESTATE_PROPERTY_PREFIX.'areasize_opt_v']  = 1;
		$options[OPALESTATE_PROPERTY_PREFIX.'price_opt_v'] 	   = 1;

	}


	// Populate some default values
	update_option( 'opalestate_settings', array_merge( $opalestate_options, $options ) );
	update_option( 'opalestate_version', OPALESTATE_VERSION );

	// Create Give roles
	$roles = new Opalestate_Roles();
	$roles->add_roles();
	$roles->add_caps();

	// Add a temporary option to note that Give pages have been created
	set_transient( '_opalestate_installed', $options, 30 );

	// Bail if activating from network, or bulk
	if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
		return;
	}
	// Add the transient to redirect
	set_transient( '_opalestate_activation_redirect', true, 30 );

	// Since 1.6.1
	opalestate_update_property_status_order();

	OpalEstate_User_Search::install(); 
	if ( function_exists( 'opalmembership_install' ) ) {
		opalmembership_install();
	}
}
register_activation_hook( OPALESTATE_PLUGIN_FILE, 'opalestate_install' );


function opalesate_create_account_pages(){

}
/**
 * Install user roles on sub-sites of a network
 *
 * Roles do not get created when Give is network activation so we need to create them during admin_init
 *
 * @since 1.0
 * @return void
 */
function opalestate_install_roles_on_network() {

	global $wp_roles;

	if ( ! is_object( $wp_roles ) ) {
		return;
	}

	if ( ! array_key_exists( 'opalestate_manager', $wp_roles->roles ) ) {
		$roles = new Opalestate_Roles;
		$roles->add_roles();
		$roles->add_caps();
	} else {
		// remove_role( 'opalestate_manager' );
		// remove_role( 'opalestate_manager' );
		// $roles = new Opalestate_Roles;
		// $roles->remove_caps();
	}
}

add_action( 'admin_init', 'opalestate_install_roles_on_network' );

/**
 * Get terms sql.
 *
 * @param $taxonomy
 * @return array|object|null
 */
function opalestate_get_terms_sql( $taxonomy ) {
	global $wpdb;
	$query = "SELECT DISTINCT 
                  t.term_id, t.name 
              FROM
                {$wpdb->prefix}terms t 
              INNER JOIN 
                {$wpdb->prefix}term_taxonomy tax 
              ON 
                tax.term_id = t.term_id
              WHERE 
                  ( tax.taxonomy = '{$taxonomy}' )";
	$result =  $wpdb->get_results($query , ARRAY_A);
	return $result;
}

/**
 * Update property status order.
 *
 * @since 1.6.1
 */
function opalestate_update_property_status_order() {
	$terms = opalestate_get_terms_sql( 'opalestate_status' );
	if ( $terms ) {
		foreach ( $terms as $term ) {
			$order = get_term_meta( $term['term_id'], 'opalestate_status_order', true );
			if ( '' !== $order  ) {
				continue;
			}

			update_term_meta( $term['term_id'], 'opalestate_status_order', '0' );
		}
	}
}
?>
