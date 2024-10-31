<?php
/**
 * Plugin Name: Opal Estate
 * Plugin URI: http://www.wpopal.com/product/opal-estate-wordpress-plugin/
 * Description: Opal Real Estate Plugin is an ideal solution and brilliant choice for you to set up a professional estate website.
 * Version: 1.6.11
 * Author: WPOPAL
 * Author URI: http://www.wpopal.com
 * Requires at least: 4.6
 * Tested up to: 5.2.3
 * Text Domain: opalestate
 * Domain Path: languages/
 *
 * @package  opalestate
 * @category Plugins
 * @author   WPOPAL
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'OpalEstate' ) ) {

	final class OpalEstate {

		/**
		 * @var Opalestate The one true Opalestate
		 * @since 1.0
		 */
		private static $instance;

		/**
		 * Opalestate Roles Object
		 *
		 * @var object
		 * @since 1.0
		 */
		public $roles;

		/**
		 * Opalestate Settings Object
		 *
		 * @var object
		 * @since 1.0
		 */
		public $opalestate_settings;

		/**
		 * Opalestate Session Object
		 *
		 * This holds donation data for user's session
		 *
		 * @var object
		 * @since 1.0
		 */
		public $session;

		/**
		 * Opalestate HTML Element Helper Object
		 *
		 * @var object
		 * @since 1.0
		 */
		public $html;


		/**
		 * Opalestate Emails Object
		 *
		 * @var object
		 * @since 1.0
		 */
		public $emails;

		/**
		 * Opalestate Email Template Tags Object
		 *
		 * @var object
		 * @since 1.0
		 */
		public $email_tags;

		/**
		 * Opalestate Customers DB Object
		 *
		 * @var object
		 * @since 1.0
		 */
		public $customers;

		/**
		 * Opalestate API Object
		 *
		 * @var object
		 * @since 1.1
		 */
		public $api;

		/**
		 *
		 */
		public function __construct() {

		}

		/**
		 * Main Opalestate Instance
		 *
		 * Insures that only one instance of Opalestate exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @return    Opalestate
		 * @uses      Opalestate::setup_constants() Setup the constants needed
		 * @uses      Opalestate::includes() Include the required files
		 * @uses      Opalestate::load_textdomain() load the language files
		 * @see       OpalEstate()
		 * @since     1.0
		 * @static
		 * @staticvar array $instance
		 */
		public static function getInstance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Opalestate ) ) {
				self::$instance = new OpalEstate;
				self::$instance->setup_constants();

				add_action( 'plugins_loaded', [ self::$instance, 'load_textdomain' ] );

				self::$instance->includes();
				self::$instance->roles = new Opalestate_Roles();
				self::$instance->html  = new Opalestate_HTML_Elements();
				self::$instance->api   = new Opalestate_API();
				/**
				 *
				 */

				add_filter( 'opalestate_google_map_api', [ __CLASS__, 'load_google_map_api' ] );
			}

			return self::$instance;
		}

		public static function load_google_map_api( $key ) {
			if ( opalestate_options( 'google_map_api_keys' ) ) {
				$key = '//maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places&amp;key=' . opalestate_options( 'google_map_api_keys' );
			}

			return $key;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object, therefore we don't want the object to be cloned.
		 *
		 * @return void
		 * @since  1.0
		 * @access protected
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'opalestate' ), '1.6.11' );
		}

		/**
		 *
		 */
		public function setup_constants() {
			// Plugin version
			if ( ! defined( 'OPALESTATE_VERSION' ) ) {
				define( 'OPALESTATE_VERSION', '1.6.11' );
			}

			// Plugin Folder Path
			if ( ! defined( 'OPALESTATE_PLUGIN_DIR' ) ) {
				define( 'OPALESTATE_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'OPALESTATE_PLUGIN_URL' ) ) {
				define( 'OPALESTATE_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'OPALESTATE_PLUGIN_FILE' ) ) {
				define( 'OPALESTATE_PLUGIN_FILE', __FILE__ );
			}

			// Plugin Root File
			if ( ! defined( 'OPALESTATE_THEMER_WIDGET_TEMPLATES' ) ) {
				define( 'OPALESTATE_THEMER_WIDGET_TEMPLATES', get_stylesheet_directory() . '/' );
			}

			if ( ! defined( 'OPALMEMBERSHIP_PACKAGES_PREFIX' ) ) {
				define( 'OPALMEMBERSHIP_PACKAGES_PREFIX', 'opalestate_package_' );
			}

			if ( ! defined( "OPALESTATE_CLUSTER_ICON_URL" ) ) {
				define( 'OPALESTATE_CLUSTER_ICON_URL', apply_filters( 'opalestate_cluster_icon_url', OPALESTATE_PLUGIN_URL . 'assets/cluster-icon.png' ) );
			}
		}

		public function setup_cmb2_url() {
			return OPALESTATE_PLUGIN_URL . 'inc/vendors/cmb2/libraries';
		}

		public function includes() {
			global $opalestate_options;

			/**
			 * Get the CMB2 bootstrap!
			 *
			 * @description: Checks to see if CMB2 plugin is installed first the uses included CMB2; we can still use it even it it's not active. This prevents fatal error conflicts with other themes and users of the CMB2 WP.org plugin
			 *
			 */

			if ( file_exists( WP_PLUGIN_DIR . '/cmb2/init.php' ) ) {
				require_once WP_PLUGIN_DIR . '/cmb2/init.php';
			} elseif ( file_exists( OPALESTATE_PLUGIN_DIR . 'inc/vendors/cmb2/libraries/init.php' ) ) {
				require_once OPALESTATE_PLUGIN_DIR . 'inc/vendors/cmb2/libraries/init.php';
				//Customize CMB2 URL
				add_filter( 'cmb2_meta_box_url', [ $this, 'setup_cmb2_url' ] );
			}
			// cmb2 custom field
			if ( file_exists( OPALESTATE_PLUGIN_DIR . 'inc/vendors/cmb2/custom-fields/map/map.php' ) ) {
				require_once OPALESTATE_PLUGIN_DIR . 'inc/vendors/cmb2/custom-fields/map/map.php';
				require_once OPALESTATE_PLUGIN_DIR . 'inc/vendors/cmb2/custom-fields/upload/upload.php';
				require_once OPALESTATE_PLUGIN_DIR . 'inc/vendors/cmb2/custom-fields/user/user.php';
				require_once OPALESTATE_PLUGIN_DIR . 'inc/vendors/cmb2/custom-fields/user_upload/user_upload.php';
			}

			require_once OPALESTATE_PLUGIN_DIR . 'inc/admin/register-settings.php';
			if ( is_admin() ) {
				require_once OPALESTATE_PLUGIN_DIR . 'inc/admin/functions.php';
			}

			$opalestate_options = opalestate_get_settings();

			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-template-loader.php';

			require_once OPALESTATE_PLUGIN_DIR . 'inc/query-functions.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-query.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/mixes-functions.php';


			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-roles.php';

			opalestate_includes( OPALESTATE_PLUGIN_DIR . 'inc/post-types/*.php' );
			//opalestate_includes( OPALESTATE_PLUGIN_DIR . 'inc/taxonomies/*.php' );
			require_once OPALESTATE_PLUGIN_DIR . 'inc/taxonomies/class-taxonomy-categories.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/taxonomies/class-taxomony-amenities.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/taxonomies/class-taxonomy-labels.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/taxonomies/class-taxonomy-locations.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/taxonomies/class-taxonomy-status.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/taxonomies/class-taxonomy-types.php';

			require_once OPALESTATE_PLUGIN_DIR . 'inc/api/class-opalestate-api.php';

			require_once OPALESTATE_PLUGIN_DIR . 'inc/template-functions.php';

			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-property.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-agent.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-office.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-user.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-user-search.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-favorite.php';

			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-scripts.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-shortcodes.php';

			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-metabox.php';


			//// enable or disable submission ////	
			if ( opalestate_options( 'enable_submission', 'on' ) == 'on' ) {
				require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-submission.php';
			}

			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-no-captcha-recaptcha.php';

			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-search.php';

			require_once OPALESTATE_PLUGIN_DIR . 'install.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-email.php';


			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-vc.php';
			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-html.php';

			require_once OPALESTATE_PLUGIN_DIR . 'inc/function-search-fields.php';


			add_action( 'widgets_init', [ $this, 'widgets_init' ] );

			add_action( 'init', [ $this, 'set_location_actived' ] );
			require_once OPALESTATE_PLUGIN_DIR . 'inc/ajax-functions.php';

			// Support multilingual.	
			require_once OPALESTATE_PLUGIN_DIR . 'inc/class-opalestate-multilingual.php';

			add_action( 'plugins_loaded', [ $this, 'load_exts' ] );
		}

		public function load_exts() {
			if ( class_exists( 'OpalMembership' ) ) {
				require_once OPALESTATE_PLUGIN_DIR . 'inc/vendors/opalmembership/membership.php';
			}
		}

		public static function set_location_actived() {
			if ( isset( $_GET['set_location'] ) && ! empty( $_GET['set_location'] ) ) {
				$_SESSION['set_location'] = trim( $_GET['set_location'] );
				wp_redirect( home_url( '/' ) );
				exit;
			}
			if ( isset( $_GET['clear_location'] ) && ! empty( $_GET['clear_location'] ) ) {
				$_SESSION['set_location'] = null;
				wp_redirect( home_url( '/' ) );
				exit;
			}
			if ( isset( $_SESSION['set_location'] ) && ! empty( $_SESSION['set_location'] ) ) {
				Opalestate_Query::$LOCATION = $_SESSION['set_location'];
			}

		}

		/**
		 *
		 */
		public function load_textdomain() {
			// Set filter for Opalestate's languages directory
			$lang_dir = dirname( plugin_basename( OPALESTATE_PLUGIN_FILE ) ) . '/languages/';
			$lang_dir = apply_filters( 'opalestate_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale = apply_filters( 'plugin_locale', get_locale(), 'opalestate' );
			$mofile = sprintf( '%1$s-%2$s.mo', 'opalestate', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/opalestate/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/opalestate folder
				load_textdomain( 'opalestate', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/opalestate/languages/ folder
				load_textdomain( 'opalestate', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'opalestate', false, $lang_dir );
			}
		}

		public function widgets_init() {
			opalestate_includes( OPALESTATE_PLUGIN_DIR . 'inc/widgets/*.php' );
		}
	}
}

if ( ! function_exists( 'OpalEstate' ) ) {
	function OpalEstate() {
		return OpalEstate::getInstance();
	}

	OpalEstate();
}
