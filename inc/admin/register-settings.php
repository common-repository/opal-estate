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

class Opalestate_Plugin_Settings {

	/**
	 * Option key, and option page slug
	 * @var string
	 */
	private $key = 'opalestate_settings';

	/**
	 * Array of metaboxes/fields
	 * @var array
	 */
	protected $option_metabox = array();

	/**
	 * Options Page title
	 * @var string
	 */
	protected $title = '';

	/**
	 * Options Page hook
	 * @var string
	 */
	protected $options_page = '';

	/**
	 * Constructor
	 * @since 1.0
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ) , 10 );

		add_action( 'admin_init', array( $this, 'init' ) );

		//Custom CMB2 Settings Fields
		add_action( 'cmb2_render_opalestate_title', 'opalestate_title_callback', 10, 5 );

		// add_action( 'cmb2_render_api_keys', 'opalestate_api_keys_callback', 10, 5 );
		add_action( 'cmb2_render_license_key', 'opalestate_license_key_callback', 10, 5 );
		add_action( "cmb2_save_options-page_fields", array( $this, 'settings_notices' ), 10, 3 );


		add_action( 'cmb2_render_api_keys', 'opalestate_api_keys_callback', 10, 5 );

		// Include CMB CSS in the head to avoid FOUC
		add_action( "admin_print_styles-opalestate_properties_page_opalestate-settings", array( 'CMB2_hookup', 'enqueue_cmb_css' ) );
	}

	public function admin_menu() {
		//Settings
	 	$opalestate_settings_page = add_submenu_page( 'edit.php?post_type=opalestate_property', __( 'Settings', 'opalestate' ), __( 'Settings', 'opalestate' ), 'manage_opalestate_settings', 'opalestate-settings',
	 		array( $this, 'admin_page_display' ) );
	 	
	 	// addons setting
	 	$opalestate_settings_page = add_submenu_page( 'edit.php?post_type=opalestate_property', __( 'Addons', 'opalestate' ), __( 'Addons', 'opalestate' ), 'manage_options', 'opalestate-addons',
	 		array( $this, 'admin_addons_page_display' ) );

	}

	/**
	 * Register our setting to WP
	 * @since  1.0
	 */
	public function init() {
		register_setting( $this->key, $this->key );

	}

	/**
	 * Retrieve settings tabs
	 *
	 * @since 1.0
	 * @return array $tabs
	 */
	public function opalestate_get_settings_tabs() {

		$settings = $this->opalestate_settings( null );

		$tabs             	  = array();
		$tabs['general']  	  = __( 'General', 'opalestate' );
	 
		$tabs['searcharea']   = __( 'Search', 'opalestate' );
	 
		if ( ! empty( $settings['addons']['fields'] ) ) {
			$tabs['addons']   = __( 'Add-ons', 'opalestate' );
		}

		if ( ! empty( $settings['licenses']['fields'] ) ) {
			$tabs['licenses'] = __( 'Licenses', 'opalestate' );
		}

		$tabs['api_keys']     = esc_html__( 'API', 'opalestate' );

		return apply_filters( 'opalestate_settings_tabs', $tabs );
	}

	public function admin_addons_page_display(){   
		require_once opalestate_get_admin_view( 'addons/list.php' );
	}

	/**
	 * Admin page markup. Mostly handled by CMB2
	 * @since  1.0
	 */
	public function admin_page_display() {

		$active_tab = isset( $_GET['tab'] ) && array_key_exists( $_GET['tab'], $this->opalestate_get_settings_tabs() ) ? $_GET['tab'] : 'general';

		?>

		<div class="wrap opalestate_settings_page cmb2_options_page <?php echo $this->key; ?>">
			<h2 class="nav-tab-wrapper">
				<?php
				foreach ( $this->opalestate_get_settings_tabs() as $tab_id => $tab_name ) {

					$tab_url = esc_url( add_query_arg( array(
						'settings-updated' => false,
						'tab'              => $tab_id
					) ) );

					$active = $active_tab == $tab_id ? ' nav-tab-active' : '';

					echo '<a href="' . esc_url( $tab_url ) . '" title="' . esc_attr( $tab_name ) . '" class="nav-tab' . $active . '">';
					echo esc_html( $tab_name );

					echo '</a>';
				}
				?>
			</h2>

			<?php cmb2_metabox_form( $this->opalestate_settings( $active_tab ), $this->key ); ?>

		</div><!-- .wrap -->

		<?php
	}

	/**
	 * Define General Settings Metabox and field configurations.
	 *
	 * Filters are provided for each settings section to allow add-ons and other plugins to add their own settings
	 *
	 * @param $active_tab active tab settings; null returns full array
	 *
	 * @return array
	 */
	public function opalestate_settings( $active_tab ) {

		$pages = opalestate_cmb2_get_post_options( array(
								'post_type'   => 'page',
								'numberposts' => - 1
		) );


		$opalestate_settings = array(
			/**
			 * General Settings
			 */
			'general'     => array(
				'id'         => 'options_page',
				'opalestate_title' => __( 'General Settings', 'opalestate' ),
				'show_on'    => array( 'key' => 'options-page', 'value' => array( $this->key, ), ),
				'fields'     => apply_filters( 'opalestate_settings_general', array(
						array(
							'name' => __( 'General Settings', 'opalestate' ),
 
							'type' => 'opalestate_title',
							'id'   => 'opalestate_title_general_settings_1',
							'before_row' => '<hr>',
							'after_row'  => '<hr>'
						),
					
						array(
							'name'    => __( 'Profile Page', 'opalestate' ),
							'desc'    => __( 'This is page to display form for editing user information. The <code>[opalestate_user_profile]</code> shortcode should be on this page.', 'opalestate' ),
							'id'      => 'profile_page',
							'type'    => 'select',
							'options' => $pages,
						),

						array(
							'name'    => __( 'Favorite Page', 'opalestate' ),
							'desc'    => __( 'This is page to display list of favorite properties. The <code>[opalestate_user_favious_properties]</code> shortcode should be on this page.', 'opalestate' ),
							'id'      => 'favorite_page',
							'type'    => 'select',
							'options' => $pages,
						),
						array(
							'name'    => __( 'Enable User Extra Profile', 'opalestate' ),
							'desc'    => __( 'Enable to edit agent profile or office profile with user having agent/office role. And Show menu on dashboad links', 'opalestate' ),
							'id'      => 'enable_extra_profile',
							'type'    => 'select',
							'options' => array(
								'on' 	=> __( 'Enable', 'opalestate' ),
								'off'   => __( 'Disable', 'opalestate' ),
							),
							'default' => 'on',
						),

		
						array(
							'name'    => esc_html__( 'Agent Image Size', 'opalestate' ),
							'desc'    => esc_html__( 'The Loop Image is an Agent that is chosen as the representative Agent in grid and list.', 'opalestate' ),
							
							'id'      => 'agent_image_size',
							'type'    => 'select',
							'default' => 'medium',
							'options' => opalestate_get_featured_image_sizes(),

						),


						array(
							'name'    => esc_html__( 'Loop Image Size', 'opalestate' ),
							'desc'    => esc_html__( 'The Loop Image is an image that is chosen as the representative image in grid and list.', 'opalestate' ),
							
							'id'      => 'loop_image_size',
							'type'    => 'select',
							'default' => 'large',
							'options' => opalestate_get_featured_image_sizes()
						),


						array(
							'name'     => esc_html__( 'Featured Image Size', 'opalestate' ),
							'desc'     => esc_html__( 'The Featured Image is an image that is chosen as the representative image in single page. .', 'opalestate' ),
							'id'       => 'featured_image_size',
							'type'     => 'select',
							'default'  => 'full',
							'options'  => opalestate_get_featured_image_sizes(),
							'after_row' => '<em>'.__( 'To generate images with new image sizes, you can use this <a href="https://goo.gl/FuXFex" target="_blank">Force Regenerate Thumbnails</a>', 'opalestate').'</em>'
						),


						array(
							'name'    => __( 'Minimun of Target Price For Agent', 'opalestate' ),
							'desc'    => __( 'Enter min  of price for starting search agent by target', 'opalestate' ),
							'id'      => 'search_agent_min_price',
							'type'    => 'text',
							'default' => 0,
						),
						
					 
						array(
							'name'    => __( 'Maximun of Target Price For Agent', 'opalestate' ),
							'desc'    => __( 'Enter maximun of price for starting search agent by target', 'opalestate' ),
							'id'      => 'search_agent_max_price',
							'type'    => 'text',
							'default' => 1000000,
						),


						array(
							'name'    => __( 'Single Layout Page', 'opalestate' ),
							'desc'    => __( 'Choose layout for single property.', 'opalestate' ),
							'id'      => 'layout',
							'type'    => 'select',
							'options' => apply_filters('opalestate_single_layout_templates', array( '' => __('Inherit','opalestate') ) ),
						),		
						
						array(
							'name'    => __( 'Enable User Submission', 'opalestate' ),
							'desc'    => __( 'Enable to allow user post properties', 'opalestate' ),
							'id'      => 'enable_submission',
							'type'    => 'select',
							'options' => array(
								'on' 	=> __( 'Enable', 'opalestate' ),
								'off'   => __( 'Disable', 'opalestate' ),
							),
							'default' => 'on',
						),

						array(
							'name'    => __( 'Show Office Properties', 'opalestate' ),
							'desc'    => __( 'Enable to allow displying properties with team or office only', 'opalestate' ),
							'id'      => 'show_properties_team',
							'type'    => 'select',
							'options' => array(
								'on' 	=> __( 'With Members In Office', 'opalestate' ),
								'off'   => __( 'Only In Office', 'opalestate' ),
							),
							'default' => 'on',
						),
						array(
							'name' => __( 'Currency Settings', 'opalestate' ),
							'desc' => '',
							'type' => 'opalestate_title',
							'id'   => 'opalestate_title_general_settings_2',
							'before_row' => '<hr>',
							'after_row'  => '<hr>'
						),

						array(
							'name'    => __( 'Currency', 'opalestate' ),
							'desc'    => 'Choose your currency. Note that some payment gateways have currency restrictions.',
							'id'      => 'currency',
							'type'    => 'select',
							'options' => opalestate_get_currencies(),
							'default' => 'USD',
						),
						array(
							'name'    => __( 'Currency Position', 'opalestate' ),
							'desc'    => 'Choose the position of the currency sign.',
							'id'      => 'currency_position',
							'type'    => 'select',
							'options' => array(
								'before' => __( 'Before - $10', 'opalestate' ),
								'after'  => __( 'After - 10$', 'opalestate' )
							),
							'default' => 'before',
						),
						array(
							'name'    => __( 'Thousands Separator', 'opalestate' ),
							'desc'    => __( 'The symbol (typically , or .) to separate thousands', 'opalestate' ),
							'id'      => 'thousands_separator',
							'type'    => 'text_small',
							'default' => ',',
						),
						array(
							'name'    => __( 'Decimal Separator', 'opalestate' ),
							'desc'    => __( 'The symbol (usually , or .) to separate decimal points', 'opalestate' ),
							'id'      => 'decimal_separator',
							'type'    => 'text_small',
							'default' => '.',
						),
						array(
							'name'            => __( 'Number of Decimals', 'opalestate' ),
							'desc'            => __( 'This sets the number of decimal points shown in displayed prices.', 'opalestate' ),
							'id'              => 'number_decimals',
							'type'            => 'text_small',
							'default'         => 2,
						),
						array(
							'name'    => __( 'Measurement Unit', 'opalestate' ),
							'desc'    => __( 'Measurement Unit', 'opalestate' ),
							'id'      => 'measurement_unit',
							'type'    => 'select',
							'options' => opalestate_measurement_unit(),
							'default' => 'sq ft',
						),

						array(
							'name'    => __( 'Google Map API', 'opalestate' ),
							'desc'    => __( 'You need register <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">Google API Key</a>, then put the key in this setting.', 'opalestate' ),
							'id'      => 'google_map_api_keys',
							'type'    => 'text',
							'default' => 'AIzaSyCfMVNIa7khIqYHCw6VBn8ShUWWm4tjbG8',
						),

					)
				)
			),			 
		);
	

		$opalestate_settings['api_keys'] = array(
			'id'         => 'api_keys',
			'opalestate_title' => esc_html__( 'API', 'opalestate' ),
			'show_on'    => array( 'key' => 'options-page', 'value' => array( $this->key, ), ),
			'show_names' => false, // Hide field names on the left
			'fields'     => apply_filters( 'opalestate_settings_system', array(
					array(
						'id'   => 'api_keys',
						'name' => esc_html__( 'API', 'opalestate' ),
						'type' => 'api_keys'
					)
				)
			)
		);

		$opalestate_settings['searcharea'] = array(
				'id'         => 'options_page',
				'opalestate_title' => __( 'Search', 'opalestate' ),
				'show_on'    => array( 'key' => 'options-page', 'value' => array( $this->key, ), ),
				'fields'     =>  array()

		);
	 
		$metas = Opalestate_PostType_Property::metaboxes_info_fields();

		$fields = array();

		if( $metas ){
			$fields[] = array(
							'name'    => __( 'User Share Search', 'opalestate' ),
							'desc'    => __( 'Display Save Search Link Management', 'opalestate' ),
							'id'      => 'enable_share_earch',
							'type'    => 'select',
							'options' => array(
								'on' 	=> __( 'Enable', 'opalestate' ),
								'off'   => __( 'Disable', 'opalestate' ),
							),
							'default' => 'on',
			);

			$fields[] = array(
							'name'    => __( 'User Saved Search', 'opalestate' ),
							'desc'    => __( 'Display Save Search Link Management', 'opalestate' ),
							'id'      => 'enable_saved_usersearch',
							'type'    => 'select',
							'options' => array(
								'on' 	=> __( 'Enable', 'opalestate' ),
								'off'   => __( 'Disable', 'opalestate' ),
							),
							'default' => 'on',
			);
			$fields[] = 		array(
						'name'    => __( 'Saved Link Page', 'opalestate' ),
						'desc'    => __( 'Display Save Search Link Management. This is page to display form for editing user information. The [opalestate_user_saved_search] shortcode should be on this page.', 'opalestate' ),
						'id'      => 'saved_link_page',
						'type'    => 'select',
						'options' => $pages,
						'default' => 'on',
					);

			$fields[] = array(
					'name'    => __( 'Search Properties Page', 'opalestate' ),
					'desc'    => __( 'This is page to display result of properties after user searching via form. <br>Or create custom search page :page-templates/page-property-search-results.php inside actived theme to override this.The [opalestate_search_map_properties] shortcode should be on this page', 'opalestate' ),
					'id'      => 'search_map_properties_page',
					'type'    => 'select',
					'options' => 	opalestate_cmb2_get_post_options( array(
						'post_type'   => 'page',
						'numberposts' => - 1
					) ),
					'default' => ''
			);

			$fields[] = array(
				'name'    => __( 'Properties Per Page', 'opalestate' ),
				'desc'    => __( 'Enter min of properties display in search page', 'opalestate' ),
				'id'      => 'search_property_per_page',
				'type'    => 'text',
				'default' => 9,
			); 


			$fields[] = array( 
				'name'    =>  __( 'Show Featured First', 'opalestate' ),
				'id'      => 'show_featured_first',
				'desc'    => __( 'Show featured first in page result, as default Newest is showed', 'opalestate' ),
				'type'    => 'select',
				'options' => array(
					0 => __( 'Disable', 'opalestate' ),
					1  => __( 'Enable', 'opalestate' )
				),
				'default' => 0,
			);
			$fields[] = array(
				'name'    => __( 'Minimun of Search Price', 'opalestate' ),
				'desc'    => __( 'Enter min of price for starting search', 'opalestate' ),
				'id'      => 'search_min_price',
				'type'    => 'text',
				'default' => 0,
			); 
			$fields[] = array(
				'name'    => __( 'Maximun of Search Price', 'opalestate' ),
				'desc'    => __( 'Enter max of price for starting search', 'opalestate' ),
				'id'      => 'search_max_price',
				'type'    => 'text',
				'default' => 10000000,
			); 


			$fields[] = array(
				'name'    => __( 'Min of Search Aea', 'opalestate' ),
				'desc'    => __( 'Enter min of area for starting search', 'opalestate' ),
				'id'      => 'search_min_area',
				'type'    => 'text',
				'default' => 0,
			); 
			$fields[] = array(
				'name'    => __( 'Max of Search Aea', 'opalestate' ),
				'desc'    => __( 'Enter max of area for starting search', 'opalestate' ),
				'id'      => 'search_max_area',
				'type'    => 'text',
				'default' => 1000,
			); 

			$fields[] = array(
				'name' => __( 'Horizontal Search Fields', 'opalestate' ),
				'desc' => __('Disable or enable fields appearing in search form','opalestate').'<hr>',
				'type' => 'opalestate_title',
				'id'   => 'opalestate_title_general_settings_1'
			);
			$fields[] = array(
				'name'    =>  __( 'Show Price', 'opalestate' ),
				'id'      => OPALESTATE_PROPERTY_PREFIX.'price_opt',
				'type'    => 'select',
				'options' => array(
					0 => __( 'Disable', 'opalestate' ),
					1  => __( 'Enable', 'opalestate' )
				),
				'default' => 1,
			);
			foreach( $metas as $key => $meta ){
				$fields[] = array(
					'name'    => $meta['name'],
					'id'      => $meta['id'].'_opt',
					'type'    => 'select',
					'options' => array(
						0 => __( 'Disable', 'opalestate' ),
						1  => __( 'Enable', 'opalestate' )
					),
					'default' => 0,
				);
			}
			$fields[] = array(
				'name' => __( 'Vertical Search Fields', 'opalestate' ),
				'desc' => '<hr>',
				'type' => 'opalestate_title',
				'id'   => 'opalestate_title_general_settings_2'
			);
			$fields[] = array(
				'name'    =>  __( 'Show Price', 'opalestate' ),
				'id'      => OPALESTATE_PROPERTY_PREFIX.'price_opt_v',
				'type'    => 'select',
				'options' => array(
					0 => __( 'Disable', 'opalestate' ),
					1  => __( 'Enable', 'opalestate' )
				),
				'default' => 1,
			);
			foreach( $metas as $key => $meta ){
				$fields[] = array(
					'name'    => $meta['name'],
					'id'      => $meta['id'].'_opt_v',
					'type'    => 'select',
					'options' => array(
						0 => __( 'Disable', 'opalestate' ),
						1  => __( 'Enable', 'opalestate' )
					),
					'default' => 0,
				);
			}

			$opalestate_settings['searcharea']['fields'] = 	$fields;

		}
		//Return all settings array if necessary

		if ( $active_tab === null   ) {  
			return apply_filters( 'opalestate_registered_settings', $opalestate_settings );
		}

		// Add other tabs and settings fields as needed
		return apply_filters( 'opalestate_registered_'.$active_tab.'_settings', isset($opalestate_settings[ $active_tab ])?$opalestate_settings[ $active_tab ]:array() );

	}

	/**
	 * Show Settings Notices
	 *
	 * @param $object_id
	 * @param $updated
	 * @param $cmb
	 */
	public function settings_notices( $object_id, $updated, $cmb ) {

		//Sanity check
		if ( $object_id !== $this->key ) {
			return;
		}

		if ( did_action( 'cmb2_save_options-page_fields' ) === 1 ) {
			settings_errors( 'opalestate-notices' );
		}

		add_settings_error( 'opalestate-notices', 'global-settings-updated', __( 'Settings updated.', 'opalestate' ), 'updated' );

	}


	/**
	 * Public getter method for retrieving protected/private variables
	 *
	 * @since  1.0
	 *
	 * @param  string $field Field to retrieve
	 *
	 * @return mixed          Field value or exception is thrown
	 */
	public function __get( $field ) {

		// Allowed fields to retrieve
		if ( in_array( $field, array( 'key', 'fields', 'opalestate_title', 'options_page' ), true ) ) {
			return $this->{$field};
		}
		if ( 'option_metabox' === $field ) {
			return $this->option_metabox();
		}

		throw new Exception( 'Invalid property: ' . $field );
	}


}

// Get it started
$Opalestate_Settings = new Opalestate_Plugin_Settings();

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 *
 * @param  string $key Options array key
 *
 * @return mixed        Option value
 */
function opalestate_get_option( $key = '', $default = false ) {
	global $opalestate_options;
	$value = ! empty( $opalestate_options[ $key ] ) ? $opalestate_options[ $key ] : $default;
	$value = apply_filters( 'opalestate_get_option', $value, $key, $default );

	return apply_filters( 'opalestate_get_option_' . $key, $value, $key, $default );
}


/**
 * Update an option
 *
 * Updates an opalestate setting value in both the db and the global variable.
 * Warning: Passing in an empty, false or null string value will remove
 *          the key from the opalestate_options array.
 *
 * @since 1.0
 *
 * @param string          $key   The Key to update
 * @param string|bool|int $value The value to set the key to
 *
 * @return boolean True if updated, false if not.
 */
function opalestate_update_option( $key = '', $value = false ) {

	// If no key, exit
	if ( empty( $key ) ) {
		return false;
	}

	if ( empty( $value ) ) {
		$remove_option = opalestate_delete_option( $key );

		return $remove_option;
	}

	// First let's grab the current settings
	$options = get_option( 'opalestate_settings' );

	// Let's let devs alter that value coming in
	$value = apply_filters( 'opalestate_update_option', $value, $key );

	// Next let's try to update the value
	$options[ $key ] = $value;
	$did_update      = update_option( 'opalestate_settings', $options );

	// If it updated, let's update the global variable
	if ( $did_update ) {
		global $opalestate_options;
		$opalestate_options[ $key ] = $value;
	}

	return $did_update;
}

/**
 * Remove an option
 *
 * Removes an opalestate setting value in both the db and the global variable.
 *
 * @since 1.0
 *
 * @param string $key The Key to delete
 *
 * @return boolean True if updated, false if not.
 */
function opalestate_delete_option( $key = '' ) {

	// If no key, exit
	if ( empty( $key ) ) {
		return false;
	}

	// First let's grab the current settings
	$options = get_option( 'opalestate_settings' );

	// Next let's try to update the value
	if ( isset( $options[ $key ] ) ) {

		unset( $options[ $key ] );

	}

	$did_update = update_option( 'opalestate_settings', $options );

	// If it updated, let's update the global variable
	if ( $did_update ) {
		global $opalestate_options;
		$opalestate_options = $options;
	}

	return $did_update;
}


/**
 * Get Settings
 *
 * Retrieves all Opalestate plugin settings
 *
 * @since 1.0
 * @return array Opalestate settings
 */
function opalestate_get_settings() {

	$settings = get_option( 'opalestate_settings' );

	return (array) apply_filters( 'opalestate_get_settings', $settings );

}

/**
 * Gateways Callback
 *
 * Renders gateways fields.
 *
 * @since 1.0
 *
 * @global $opalestate_options Array of all the Opalestate Options
 * @return void
 */
function opalestate_enabled_gateways_callback( $field_object, $escaped_value, $object_id, $object_type, $field_type_object ) {

	$id                = $field_type_object->field->args['id'];
	$field_description = $field_type_object->field->args['desc'];
	$gateways          = opalestate_get_payment_gateways();

	echo '<ul class="cmb2-checkbox-list cmb2-list">';

	foreach ( $gateways as $key => $option ) :

		if ( is_array( $escaped_value ) && array_key_exists( $key, $escaped_value ) ) {
			$enabled = '1';
		} else {
			$enabled = null;
		}

		echo '<li><input name="' . $id . '[' . $key . ']" id="' . $id . '[' . $key . ']" type="checkbox" value="1" ' . checked( '1', $enabled, false ) . '/>&nbsp;';
		echo '<label for="' . $id . '[' . $key . ']">' . $option['admin_label'] . '</label></li>';

	endforeach;

	if ( $field_description ) {
		echo '<p class="cmb2-metabox-description">' . $field_description . '</p>';
	}

	echo '</ul>';


}

/**
 * Gateways Callback (drop down)
 *
 * Renders gateways select menu
 *
 * @since 1.0
 *
 * @param $field_object , $escaped_value, $object_id, $object_type, $field_type_object Arguments passed by CMB2
 *
 * @return void
 */
function opalestate_default_gateway_callback( $field_object, $escaped_value, $object_id, $object_type, $field_type_object ) {

	$id                = $field_type_object->field->args['id'];
	$field_description = $field_type_object->field->args['desc'];
	$gateways          = opalestate_get_enabled_payment_gateways();

	echo '<select class="cmb2_select" name="' . $id . '" id="' . $id . '">';

	//Add a field to the Opalestate Form admin single post view of this field
	if ( $field_type_object->field->object_type === 'post' ) {
		echo '<option value="global">' . __( 'Global Default', 'opalestate' ) . '</option>';
	}

	foreach ( $gateways as $key => $option ) :

		$selected = isset( $escaped_value ) ? selected( $key, $escaped_value, false ) : '';


		echo '<option value="' . esc_attr( $key ) . '"' . $selected . '>' . esc_html( $option['admin_label'] ) . '</option>';

	endforeach;

	echo '</select>';

	echo '<p class="cmb2-metabox-description">' . $field_description . '</p>';

}

/**
 * Opalestate Title
 *
 * Renders custom section titles output; Really only an <hr> because CMB2's output is a bit funky
 *
 * @since 1.0
 *
 * @param       $field_object , $escaped_value, $object_id, $object_type, $field_type_object
 *
 * @return void
 */
function opalestate_title_callback( $field_object, $escaped_value, $object_id, $object_type, $field_type_object ) {

	$id                = $field_type_object->field->args['id'];
	$title             = $field_type_object->field->args['name'];
	$field_description = $field_type_object->field->args['desc'];

	echo '<hr>';

}

/**
 * Gets a number of posts and displays them as options
 *
 * @param  array $query_args Optional. Overrides defaults.
 * @param  bool  $force      Force the pages to be loaded even if not on settings
 *
 * @see: https://github.com/WebDevStudios/CMB2/wiki/Adding-your-own-field-types
 * @return array An array of options that matches the CMB2 options array
 */
function opalestate_cmb2_get_post_options( $query_args, $force = false ) {

	$post_options = array( '' => '' ); // Blank option

	if ( ( ! isset( $_GET['page'] ) || 'opalestate-settings' != $_GET['page'] ) && ! $force ) {
		return $post_options;
	}

	$args = wp_parse_args( $query_args, array(
		'post_type'   => 'page',
		'numberposts' => 10,
	) );

	$posts = get_posts( $args );

	if ( $posts ) {
		foreach ( $posts as $post ) {

			$post_options[ $post->ID ] = $post->post_title;

		}
	}

	return $post_options;
}


/**
 * Modify CMB2 Default Form Output
 *
 * @param string @args
 *
 * @since 1.0
 */

add_filter( 'cmb2_get_metabox_form_format', 'opalestate_modify_cmb2_form_output', 10, 3 );

function opalestate_modify_cmb2_form_output( $form_format, $object_id, $cmb ) {

	//only modify the opalestate settings form
	if ( 'opalestate_settings' == $object_id && 'options_page' == $cmb->cmb_id ) {

		return '<form class="cmb-form" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%2$s">%3$s<div class="opalestate-submit-wrap"><input type="submit" name="submit-cmb" value="' . __( 'Save Settings', 'opalestate' ) . '" class="button-primary"></div></form>';
	}

	return $form_format;

}


/**
 * Opalestate License Key Callback
 *
 * @description Registers the license field callback for EDD's Software Licensing
 * @since       1.0
 *
 * @param array $field_object , $escaped_value, $object_id, $object_type, $field_type_object Arguments passed by CMB2
 *
 * @return void
 */
if ( ! function_exists( 'opalestate_license_key_callback' ) ) {
	function opalestate_license_key_callback( $field_object, $escaped_value, $object_id, $object_type, $field_type_object ) {

		$id                = $field_type_object->field->args['id'];
		$field_description = $field_type_object->field->args['desc'];
		$license_status    = get_option( $field_type_object->field->args['options']['is_valid_license_option'] );
		$field_classes     = 'regular-text opalestate-license-field';
		$type              = empty( $escaped_value ) ? 'text' : 'password';

		if ( $license_status === 'valid' ) {
			$field_classes .= ' opalestate-license-active';
		}

		$html = $field_type_object->input( array(
			'class' => $field_classes,
			'type'  => $type
		) );

		//License is active so show deactivate button
		if ( $license_status === 'valid' ) {
			$html .= '<input type="submit" class="button-secondary opalestate-license-deactivate" name="' . $id . '_deactivate" value="' . __( 'Deactivate License', 'opalestate' ) . '"/>';
		} else {
			//This license is not valid so delete it
			opalestate_delete_option( $id );
		}

		$html .= '<label for="opalestate_settings[' . $id . ']"> ' . $field_description . '</label>';

		wp_nonce_field( $id . '-nonce', $id . '-nonce' );

		echo $html;
	}
}


/**
 * Display the API Keys
 *
 * @since       2.0
 * @return      void
 */
function opalestate_api_keys_callback() {

	if ( ! current_user_can( 'manage_opalestate_settings' ) ) {
		return;
	}

	do_action( 'opalestate_tools_api_keys_keys_before' );

	require_once OPALESTATE_PLUGIN_DIR . 'inc/admin/class-api-keys-table.php';

		$keys_table_list = new Opalestate_API_Keys_Table();
		$keys_table_list->prepare_items();

		echo '<input type="hidden" name="page" value="wc-settings" />';
		echo '<input type="hidden" name="tab" value="api" />';
		echo '<input type="hidden" name="section" value="keys" />';

		$keys_table_list->views();
		$keys_table_list->search_box( __( 'Search Key', 'opalestate' ), 'key' );
		$keys_table_list->display();
	?>


	<p>
		<?php printf(
			__( 'API keys allow users to use the <a href="%s">Opalestate REST API</a> to retrieve donation data in JSON or XML for external applications or devices, such as <a href="%s">Zapi_keyser</a>.', 'opalestate' ),
			'https://wpopal.com/opalestate/documentation/opalestate-api_keys-reference/',
			'https://wpopal.com/addons/opalestate/'
		); ?>
	</p>

	<style>
		.opalestate_properties_page_opalestate-settings .opalestate-submit-wrap {
			display: none; /* Hide Save settings button on System Info Tab (not needed) */
		}
	</style>
	<?php

	do_action( 'opalestate_tools_api_keys_keys_after' );
}

add_action( 'opalestate_settings_tab_api_keys_keys', 'opalestate_api_keys_callback' );

/**
 * Hook Callback
 *
 * Adds a do_action() hook in place of the field
 *
 * @since 1.0
 *
 * @param array $args Arguments passed by the setting
 *
 * @return void
 */
function opalestate_hook_callback( $args ) {
	do_action( 'opalestate_' . $args['id'] );
}
