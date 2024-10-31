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
function opalestate_get_admin_view( $file ) {
	return OPALESTATE_PLUGIN_DIR . 'inc/admin/views/' . $file;
}

/**
 * Hooks Give actions, when present in the $_GET superglobal. Every opalestate_action
 * present in $_GET is called using WordPress's do_action function. These
 * functions are called on init.
 *
 * @return void
 * @since  1.0
 *
 */
function opalestate_get_actions() {
	if ( isset( $_GET['opalestate_action'] ) ) {
		do_action( 'opalestate_' . $_GET['opalestate_action'], $_GET );
	}
}

add_action( 'init', 'opalestate_get_actions' );

/**
 * Hooks Give actions, when present in the $_POST superglobal. Every opalestate_action
 * present in $_POST is called using WordPress's do_action function. These
 * functions are called on init.
 *
 * @return void
 * @since  1.0
 *
 */
function opalestate_post_actions() {
	if ( isset( $_POST['opalestate_action'] ) ) {
		do_action( 'opalestate_' . $_POST['opalestate_action'], $_POST );
	}
}

add_action( 'init', 'opalestate_post_actions' );

/**
 *
 */
function opalestate_template_init() {
	if ( isset( $_GET['display'] ) && ( $_GET['display'] == 'list' || $_GET['display'] == 'grid' || $_GET['display'] == 'map' ) ) {
		setcookie( 'opalestate_displaymode', trim( $_GET['display'] ), time() + 3600 * 24 * 100, '/' );
		$_COOKIE['opalestate_displaymode'] = trim( $_GET['display'] );
	}
}

add_action( 'init', 'opalestate_template_init' );

function opalestate_get_current_url() {

	global $wp;
	$current_url = home_url( add_query_arg( [], $wp->request ) );

	return $current_url;
}

/**
 *
 */
function opalestate_get_loop_thumbnail( $size = 'property-thumbnail' ) { ?>

    <div class="property-box-image">
        <a href="<?php the_permalink(); ?>" class="property-box-image-inner">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( apply_filters( 'opalestate_loop_property_thumbnail', $size ) ); ?>
			<?php else: ?>
				<?php echo opalestate_get_image_placeholder( $size ); ?>
			<?php endif; ?>
        </a>
    </div>
	<?php
}

/**
 *
 */
function opalestate_get_loop_agent_thumbnail( $size = 'agent-thumbnail' ) { ?>

    <div class="agent-box-image">
        <a href="<?php the_permalink(); ?>">
			<?php if ( has_post_thumbnail() ) : ?>
				<?php the_post_thumbnail( apply_filters( 'opalestate_loop_agent_thumbnail', $size ) ); ?>
			<?php else: ?>
				<?php echo opalestate_get_image_placeholder( $size ); ?>
			<?php endif; ?>
        </a>
    </div>
	<?php
}

function opalestate_get_loop_short_meta() {
	global $property;
	$meta_content = apply_filters( 'opalestate_loop_meta_info', '' );
	if ( empty( $meta_content ) ) {
		$meta = $property->get_meta_shortinfo();
		?>
        <div class="property-meta">
            <ul class="property-meta-list list-inline">
				<?php if ( $meta ) : ?>
					<?php foreach ( $meta as $key => $info ) : ?>
						<?php if ( trim( $info['value'] ) ) : ?>
                            <li class="property-label-<?php echo $key; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $info['label']; ?>"><i
                                        class="icon-property-<?php echo $key; ?>"></i><span class="label-property"><?php echo $info['label']; ?></span> <span
                                        class="label-content"><?php echo apply_filters( 'opalestate' . $key . '_unit_format', trim( $info['value'] ) ); ?></span></li>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
            </ul>
        </div>
		<?php
	} else {
		echo $meta_content;
	}
}

function opalestate_get_single_short_meta() {
	global $property;
	$meta_content = apply_filters( 'opalestate_single_meta_info', '' );
	if ( empty( $meta_content ) ) {
		$meta = $property->get_meta_shortinfo();
		?>

        <ul class="property-meta-list list-inline">
			<?php if ( $meta ) : ?>
				<?php foreach ( $meta as $key => $info ) : ?>
					<?php if ( trim( $info['value'] ) ) : ?>
                        <li class="property-label-<?php echo $key; ?>"><i class="icon-property-<?php echo $key; ?>"></i><span
                                    class="label-content"><?php echo apply_filters( 'opalestate' . $key . '_unit_format', trim( $info['value'] ) ); ?></span> <span
                                    class="label-property"><?php echo $info['label']; ?></span></li>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
        </ul>

	<?php } else {
		echo $meta_content;
	}
}

/**
 *
 */
function opalestate_render_sortable_dropdown( $selected = '', $class = '' ) {

	$output = '';
	$modes  = [
		'featured_desc' => __( 'Featured Desending', 'opalestate' ),
		'price_asc'     => __( 'Price Ascending', 'opalestate' ),
		'price_desc'    => __( 'Price Desending', 'opalestate' ),
		'areasize_asc'  => __( 'Area Ascending', 'opalestate' ),
		'areasize_desc' => __( 'Area Desending', 'opalestate' ),
	];
	$modes  = apply_filters( 'opalestate_sortable_modes', $modes );

	$modes  = array_merge( [ '' => __( 'Sort By', 'opalestate' ) ], $modes );
	$output = '<form id="opalestate-sortable-form" action="" method="POST"><select name="opalsortable" class="form-control sortable-dropdown" >';
	if ( empty( $selected ) && isset( $_REQUEST['opalsortable'] ) ) {
		$selected = $_REQUEST['opalsortable'];
	}
	foreach ( $modes as $key => $mode ) {

		$sselected = $key == $selected ? 'selected="selected"' : "";
		$output    .= '<option ' . $sselected . ' value="' . $key . '">' . $mode . '</option>';
	}

	$output .= '</select></form>';

	return $output;
}


function opalestate_management_user_menu() {
	global $opalestate_options;
	$menu = [];

	$menu['profile'] = [
		'icon'  => 'fa fa-user',
		'link'  => opalestate_get_profile_page_uri(),
		'title' => __( 'My Profile', 'opalestate' ),
		'id'    => isset( $opalestate_options['profile_page'] ) ? $opalestate_options['profile_page'] : 0,
	];

	$menu['favorite'] = [
		'icon'  => 'fa fa-star',
		'link'  => opalestate_get_favorite_page_uri(),
		'title' => __( 'My Favorite', 'opalestate' ),
		'id'    => isset( $opalestate_options['favorite_page'] ) ? $opalestate_options['favorite_page'] : 0,
	];

	$menu['submission'] = [
		'icon'  => 'fa fa-upload',
		'link'  => opalestate_submssion_page(),
		'title' => __( 'Submit Property', 'opalestate' ),
		'id'    => isset( $opalestate_options['submission_page'] ) ? $opalestate_options['submission_page'] : 0,
	];

	$menu['myproperties'] = [
		'icon'  => 'fa fa-building',
		'link'  => opalestate_submssion_list_page(),
		'title' => __( 'My Properties', 'opalestate' ),
		'id'    => isset( $opalestate_options['submission_list_page'] ) ? $opalestate_options['submission_list_page'] : 0,
	];

	$menu = apply_filters( 'opalestate_management_user_menu', $menu );

	$output = '<ul class="account-links nav nav-pills nav-stacked">';

	global $post;

	foreach ( $menu as $key => $item ) {
		$output .= '<li class="' . ( is_object( $post ) && $post->ID == $item['id'] ? 'active' : '' ) . '"><a href="' . $item['link'] . '"><i class="' . $item['icon'] . '"></i>' . $item['title'] . '</a></li>';
	}

	$output .= '<li><a href="' . wp_logout_url( home_url( '/' ) ) . '"> <i class="fa fa-unlock"></i>' . esc_html__( 'Log out', 'opalestate' ) . '</a></li>';

	$output .= '</ul>';

	echo $output;
}

/**
 *
 */
function opalestate_show_display_modes( $default = 'list' ) {
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );


	$op_display = opalestate_get_display_mode( $default );

	echo '<form action="' . $current_url . '" class="display-mode" method="get">';
	echo '<button title="' . esc_html__( 'Grid',
			'opalestate' ) . '" class="btn ' . ( $op_display == 'grid' ? 'active' : '' ) . '" value="grid" name="display" type="submit"><i class="fa fa-th"></i></button>';
	echo '<button title="' . esc_html__( 'List',
			'opalestate' ) . '" class="btn ' . ( $op_display == 'list' ? 'active' : '' ) . '" value="list" name="display" type="submit"><i class="fa fa-th-list"></i></button>';
	//	echo '<button title="'.esc_html__( 'Map', 'opalestate' ).'" class="btn '.($op_display == 'map' ? 'active' : '').'" value="map" name="display" type="submit"><i class="fa fa-map-marker"></i></button>';
	echo '</form>';
}

function opalestate_show_display_status() {
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', preg_replace( '#page\/\d+#', '', home_url( $wp->request ) ) );
	$current_url = remove_query_arg( [ 'paged', 'page' ], $current_url );
	$gstatus     = isset( $_GET['status'] ) ? $_GET['status'] : "";

	// echo $current_url;die;
	?>
    <div id="property-filter-status" class="clearfix">

		<?php
		$statuses = Opalestate_Taxonomy_Status::getList();
		if ( $statuses ):
			echo '<form action="' . $current_url . '" id="display-by-status" method="get">';

			echo '<input type="hidden" name="status" value="">';
			echo '</form>';
			?>
            <ul class="list-inline clearfix list-property-status pull-left">
                <li class="status-item <?php if ( $gstatus == "" ): ?>active<?php endif; ?>" data-id="all">
                    <span><?php _e( 'All', 'opalestate' ); ?></span>
                </li>
				<?php foreach ( $statuses as $status ): ?>

                    <li class="status-item <?php if ( $status->slug == $gstatus ): ?> active <?php endif; ?>" data-id="<?php echo $status->slug; ?>">
                        <span><?php echo $status->name; ?> </span>
                    </li>
				<?php endforeach; ?>
            </ul>
		<?php endif; ?>
    </div>
	<?php
}


function opalestate_get_display_mode( $default = '' ) {
	$op_display = $default ? $default : opalestate_options( 'displaymode', 'grid' );
	if ( isset( $_COOKIE['opalestate_displaymode'] ) ) {
		$op_display = $_COOKIE['opalestate_displaymode'];
	}

	return $op_display;
}

/**
 *
 */
function opalestate_get_search_link() {
	global $opalestate_options;
	$pages = get_pages( [
		'meta_key'   => '_wp_page_template',
		'meta_value' => 'page-templates/page-property-search-results.php',
	] );

	if ( $pages ) {
		$pages         = reset( $pages );
		$search_submit = get_permalink( $pages->ID );
	} else {
		$search_submit = isset( $opalestate_options['search_map_properties_page'] ) ? get_permalink( absint( $opalestate_options['search_map_properties_page'] ) ) : get_bloginfo( 'url' );

	}

	return apply_filters( 'opalestate_search_link', $search_submit );
}

function opalestate_user_savedsearch_page( $args = [] ) {

	$uri = get_permalink( opalestate_get_option( 'saved_link_page', '/' ) );

	if ( ! empty( $args ) ) {
		// Check for backward compatibility
		if ( is_string( $args ) ) {
			$args = str_replace( '?', '', $args );
		}
		$args = wp_parse_args( $args );
		$uri  = add_query_arg( $args, $uri );
	}

	return $uri;
}

function opalestate_submssion_page( $id = false ) {
	$page = get_permalink( opalestate_get_option( 'submission_page', '/' ) );
	if ( $id ) {
		$edit_page_id = opalestate_get_option( 'submission_edit_page' );
		$page         = $edit_page_id ? get_permalink( $edit_page_id ) : $page;
		$page         = add_query_arg( 'id', $id, $page );
	}

	return $page;
}


function opalestate_get_user_properties_uri( $args = [] ) {

	global $opalestate_options;

	$uri = isset( $opalestate_options['submission_list_page'] ) ? get_permalink( absint( $opalestate_options['submission_list_page'] ) ) : get_bloginfo( 'url' );

	if ( ! empty( $args ) ) {
		// Check for backward compatibility
		if ( is_string( $args ) ) {
			$args = str_replace( '?', '', $args );
		}
		$args = wp_parse_args( $args );
		$uri  = add_query_arg( $args, $uri );
	}

	$scheme = defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN ? 'https' : 'admin';

	$ajax_url = admin_url( 'admin-ajax.php', $scheme );

	if ( ( ! preg_match( '/^https/', $uri ) && preg_match( '/^https/', $ajax_url ) ) ) {
		$uri = preg_replace( '/^http:/', 'https:', $uri );
	}

	return apply_filters( 'opalestate_get_user_properties_uri', $uri );
}


function opalestate_submssion_list_page( $args = [] ) {

	global $opalestate_options;

	$uri = isset( $opalestate_options['submission_list_page'] ) ? get_permalink( absint( $opalestate_options['submission_list_page'] ) ) : get_bloginfo( 'url' );

	if ( ! empty( $args ) ) {
		// Check for backward compatibility
		if ( is_string( $args ) ) {
			$args = str_replace( '?', '', $args );
		}
		$args = wp_parse_args( $args );
		$uri  = add_query_arg( $args, $uri );
	}

	$scheme = defined( 'FORCE_SSL_ADMIN' ) && FORCE_SSL_ADMIN ? 'https' : 'admin';

	$ajax_url = admin_url( 'admin-ajax.php', $scheme );

	if ( ( ! preg_match( '/^https/', $uri ) && preg_match( '/^https/', $ajax_url ) ) ) {
		$uri = preg_replace( '/^http:/', 'https:', $uri );
	}

	return apply_filters( 'opalestate_get_user_properties_uri', $uri );
}

function opalestate_get_profile_page_uri( $args = [] ) {

	global $opalestate_options;

	$uri = isset( $opalestate_options['profile_page'] ) ? get_permalink( absint( $opalestate_options['profile_page'] ) ) : get_bloginfo( 'url' );

	if ( ! empty( $args ) ) {
		// Check for backward compatibility
		if ( is_string( $args ) ) {
			$args = str_replace( '?', '', $args );
		}
		$args = wp_parse_args( $args );
		$uri  = add_query_arg( $args, $uri );
	}

	return apply_filters( 'opalestate_get_register_page_uri', $uri );
}


function opalestate_get_favorite_page_uri() {

	global $opalestate_options;

	$favorite_page = isset( $opalestate_options['favorite_page'] ) ? get_permalink( absint( $opalestate_options['favorite_page'] ) ) : get_bloginfo( 'url' );

	return apply_filters( 'opalestate_get_favorite_page_uri', $favorite_page );
}

function opalestate_single_layout_templates( $layout ) {
	$layout['v2'] = __( 'Vesion 2', 'opalestate' );

	return $layout;
}

add_filter( 'opalestate_single_layout_templates', 'opalestate_single_layout_templates' );

function opalestate_single_the_property_layout() {

	global $opalestate_options;

	$layout = get_post_meta( get_the_ID(), OPALESTATE_PROPERTY_PREFIX . 'layout', true );

	if ( ! $layout ) {
		$layout = isset( $opalestate_options['layout'] ) ? $opalestate_options['layout'] : '';
	}

	return $layout;
}


function opalestate_property_loop_price() {
	echo Opalestate_Template_Loader::get_template_part( 'parts/product-loop-price' );
}

function opalestate_property_featured_label() {
	echo Opalestate_Template_Loader::get_template_part( 'parts/featured-label' );
}

add_action( 'opalestate_before_property_loop_item', 'opalestate_property_featured_label' );

function opalestate_property_label() {
	echo Opalestate_Template_Loader::get_template_part( 'parts/property-label' );
}

add_action( 'opalestate_before_property_loop_item', 'opalestate_property_label' );

/**
 * Single property logic functions
 */

/**
 * Single property logic functions
 */
function opalestate_property_meta() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/meta' );
}

/**
 * Single property logic functions
 */
function opalestate_property_preview() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/preview' );
}

/**
 *
 */
function opalestate_property_content() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/content' );
}

/**
 *
 */
function opalestate_property_information() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/information' );
}

/**
 *
 */
function opalestate_property_amenities() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/amenities' );
}

function opalestate_property_attachments() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/attachments' );
}

function opalestate_property_tags() {
	return the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' );
}

function opalestate_property_map() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/map' );
}

function opalestate_property_map_v2() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/map-v2' );
}

function opalestate_property_author() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/author' );
}

function opalestate_property_video() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/video' );
}

function opalestate_property_virtual_tour() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/virtualtour' );
}

function opalestate_properties_same_agent() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/sameagent' );
}

function opalestate_property_location() {
	echo Opalestate_Template_Loader::get_template_part( 'single-property/location' );
}

add_action( 'opalestate_single_property_preview', 'opalestate_property_preview', 15 );

add_action( 'opalestate_single_property_summary', 'opalestate_property_information', 15 );

add_action( 'opalestate_after_single_property_summary', 'opalestate_property_amenities', 15 );
add_action( 'opalestate_after_single_property_summary', 'opalestate_property_attachments', 15 );

add_action( 'opalestate_after_single_property_summary', 'opalestate_property_video', 20 );
add_action( 'opalestate_after_single_property_summary', 'opalestate_property_virtual_tour', 25 );
add_action( 'opalestate_after_single_property_summary', 'opalestate_property_map', 30 );
add_action( 'opalestate_after_single_property_summary', 'opalestate_property_author', 35 );
add_action( 'opalestate_after_single_property_summary', 'opalestate_property_tags', 40 );

add_action( 'opalestate_after_single_property_summary_v2', 'opalestate_property_map_v2', 5 );

/**
 *
 */
add_action( 'opalestate_single_property_sameagent', 'opalestate_properties_same_agent', 5 );

function opalestate_agent_summary() {
	echo Opalestate_Template_Loader::get_template_part( 'single-agent/summary' );
}

function opalestate_agent_properties() {
	echo Opalestate_Template_Loader::get_template_part( 'single-agent/properties' );
}

function opalestate_agent_contactform() {
	global $post;
	$args = [ 'post_id' => $post->ID ];
	echo Opalestate_Template_Loader::get_template_part( 'single-agent/form', $args );
}

add_action( 'opalestate_single_agent_summary', 'opalestate_agent_summary', 5 );
add_action( 'opalestate_single_content_agent_after', 'opalestate_agent_properties', 15 );

/**
 *
 */
function opalestate_agent_navbar() {

}

add_action( 'opalestate_single_agent_summary', 'opalestate_agent_navbar', 5 );


/**
 * Search page:
 */

function opalestate_show_contact_share_search_link() {
	$args = [];
	echo Opalestate_Template_Loader::get_template_part( 'user/share-search-form', $args );
}

add_action( 'opalestate_before_render_search_properties_result', 'opalestate_show_contact_share_search_link' );
