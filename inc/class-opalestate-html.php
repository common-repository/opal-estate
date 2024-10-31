<?php
/**
 * HTML elements
 *
 * A helper class for outputting common HTML elements, such as product drop downs
 *
 * @package     Opalestate
 * @subpackage  Classes/HTML
 * @copyright   Copyright (c) 2015, Pippin Williamson
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since       1.5
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Opalestate_HTML_Elements Class
 *
 * @since 1.5
 */
class Opalestate_HTML_Elements {

	/**
	 * Renders an ajax user search field
	 *
	 * @since 2.0
	 *
	 * @param array $args
	 * @return string text field with ajax search
	 */
	public function ajax_user_search( $args = array() ) {

		$defaults = array(
			'name'        => 'user_id',
			'value'       => null,
			'placeholder' => __( 'Enter username', 'opalestate' ),
			'label'       => null,
			'desc'        => null,
			'class'       => '',
			'disabled'    => false,
			'autocomplete'=> 'off',
			'data'        => false
		);

		$args = wp_parse_args( $args, $defaults );

		$args['class'] = 'opalestate-ajax-user-search ' . $args['class'];

		$output  = '<span class="opalestate_user_search_wrap">';
			$output .= $this->text( $args );
			$output .= '<span class="opalestate_user_search_results hidden"><a class="opalestate-ajax-user-cancel" aria-label="' . __( 'Cancel', 'opalestate' ) . '" href="#">x</a><span></span></span>';
		$output .= '</span>';

		return $output;
	}
	
	/**
	 * Text Field
	 *
	 * Renders an HTML Text field.
	 *
	 * @since  1.0
	 * @access public
	 *
	 * @param  array $args Arguments for the text field.
	 *
	 * @return string      The text field.
	 */
	public function text( $args = array() ) {
		// Backwards compatibility
		if ( func_num_args() > 1 ) {
			$args = func_get_args();

			$name  = $args[0];
			$value = isset( $args[1] ) ? $args[1] : '';
			$label = isset( $args[2] ) ? $args[2] : '';
			$desc  = isset( $args[3] ) ? $args[3] : '';
		}

		$defaults = array(
			'name'         => isset( $name ) ? $name : 'text',
			'value'        => isset( $value ) ? $value : null,
			'label'        => isset( $label ) ? $label : null,
			'desc'         => isset( $desc ) ? $desc : null,
			'placeholder'  => '',
			'class'        => 'regular-text',
			'disabled'     => false,
			'autocomplete' => '',
			'data'         => false
		);

		$args = wp_parse_args( $args, $defaults );

		$disabled = '';
		if ( $args['disabled'] ) {
			$disabled = ' disabled="disabled"';
		}

		$data = '';
		if ( ! empty( $args['data'] ) ) {
			foreach ( $args['data'] as $key => $value ) {
				$data .= 'data-' . $key . '="' . $value . '" ';
			}
		}

		$output = '<span id="opalestate-' . sanitize_key( $args['name'] ) . '-wrap">';

		$output .= '<label class="opalestate-label" for="opalestate-' . sanitize_key( $args['name'] ) . '">' . esc_html( $args['label'] ) . '</label>';

		if ( ! empty( $args['desc'] ) ) {
			$output .= '<span class="opalestate-description">' . esc_html( $args['desc'] ) . '</span>';
		}

		$output .= '<input type="text" name="' . esc_attr( $args['name'] ) . '" id="' . esc_attr( $args['name'] ) . '" autocomplete="' . esc_attr( $args['autocomplete'] ) . '" value="' . esc_attr( $args['value'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" class="' . $args['class'] . '" ' . $data . '' . $disabled . '/>';

		$output .= '</span>';

		return $output;
	}
}
