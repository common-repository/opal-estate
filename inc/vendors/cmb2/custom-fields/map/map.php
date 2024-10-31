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
class Opalestate_Field_Map {

	/**
	 * Current version number
	 */
	const VERSION = '1.0.0';

	/**
	 * Initialize the plugin by hooking into CMB2
	 */
	public static function init() {
		add_filter( 'cmb2_render_opal_map', array( __CLASS__, 'render_map' ), 10, 5 );
		add_filter( 'cmb2_sanitize_opal_map', array( __CLASS__, 'sanitize_map' ), 10, 4 );
	}

	/**
	 * Render field
	 */
	public static function render_map( $field, $field_escaped_value, $field_object_id, $field_object_type, $field_type_object ) {
		self::setup_admin_scripts();
		echo '<div class="'.apply_filters('opalestate_row_container_class', 'row opal-row').'">
			<div class="col-sm-6">
				<div class="opal-map"></div>
			</div>
			<div class="col-sm-6">
					<div  class="form-group">
						<label>'.__( 'Map Address', 'opalestate' ).'</label>
						<input type="text" class="large-text regular-text opal-map-search  form-control" id="' . $field->args( 'id' ) . '" 
						name="'.$field->args( '_name' ).'[addess]" value="'.(isset( $field_escaped_value['address'] ) ? $field_escaped_value['address'] : '').'"/>';
				echo '</div>';
		 
				$field_type_object->_desc( true, true );

					echo ' <div class="form-group">';
					echo '<label>'.__( 'Latitude', 'opalestate' ).'</label>';
					echo $field_type_object->input( array(
						'type'       => 'text',
						'name'       => $field->args( '_name' ) . '[latitude]',
						'value'      => isset( $field_escaped_value['latitude'] ) ? $field_escaped_value['latitude'] : '',
						'class'      => 'opal-map-latitude form-control',
						'desc'       => '',
					) );
					echo '</div>';
					echo ' <div class="form-group">';
					echo '<label>'.__( 'Longitude',  'opalestate' ).'</label>';
					echo $field_type_object->input( array(
						'type'       => 'text',
						'name'       => $field->args( '_name' ) . '[longitude]',
						'value'      => isset( $field_escaped_value['longitude'] ) ? $field_escaped_value['longitude'] : '',
						'class'      => 'opal-map-longitude form-control',
						'desc'       => '',
					) );
					echo '</div>';	
					
					
		echo '<p>You need register<a href="https://developers.google.com/maps/documentation/javascript/reference#Data.StyleOptions"> Google API Key </a>, then put the key in setting inside customizer,</p>';
		
			echo '</div>';
		echo '</div>';	
	}

	/**
	 * Optionally save the latitude/longitude values into two custom fields
	 */
	public static function sanitize_map( $override_value, $value, $object_id, $field_args ) {
		if ( isset( $field_args['split_values'] ) && $field_args['split_values'] ) {
			if ( ! empty( $value['latitude'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_latitude', $value['latitude'] );
			}

			if ( ! empty( $value['longitude'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_longitude', $value['longitude'] );
			}

			if ( ! empty( $value['address'] ) ) {
				update_post_meta( $object_id, $field_args['id'] . '_address', $value['address'] );
			}
		}

		return $value;
	}

	/**
	 * Enqueue scripts and styles
	 */
	public static function setup_admin_scripts() {
		$key = opalestate_options('google_map_api_keys') ? opalestate_options('google_map_api_keys') : 'AIzaSyDRVUZdOrZ1HuJFaFkDtmby0E93eJLykIk';
		$api = apply_filters( 'opalestate_google_map_api_uri',  '//maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places&amp;key='.$key   );
		wp_enqueue_script("opalestate-google-maps", $api, null, "0.0.1", false);

		wp_enqueue_script( 'opalestate-google-maps-js', plugins_url( 'js/script.js', __FILE__ ), array(  ), self::VERSION );
		wp_enqueue_style( 'opalestate-google-maps', plugins_url( 'css/style.css', __FILE__ ), array(), self::VERSION );
 

	}
}

Opalestate_Field_Map::init();
