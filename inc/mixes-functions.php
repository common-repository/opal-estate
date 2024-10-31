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

function opalestate_user_roles_by_user_id( $user_id ) {
	$user = get_userdata( $user_id );

	return empty( $user ) ? [] : $user->roles;
}

/**
 * Featured Image Sizes
 *
 * Outputs an array for the "Featured Image Size" option found under Settings > Display Options.
 *
 * @since 1.4
 */
function opalestate_get_featured_image_sizes() {
	global $_wp_additional_image_sizes;
	$sizes = [ 'full' => __( 'Orginal Size', 'opalestate' ) ];

	foreach ( get_intermediate_image_sizes() as $_size ) {

		if ( in_array( $_size, [ 'thumbnail', 'medium', 'medium_large', 'large' ] ) ) {
			$sizes[ $_size ] = $_size . ' - ' . get_option( "{$_size}_size_w" ) . 'x' . get_option( "{$_size}_size_h" );
		} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
			$sizes[ $_size ] = $_size . ' - ' . $_wp_additional_image_sizes[ $_size ]['width'] . 'x' . $_wp_additional_image_sizes[ $_size ]['height'];
		}

	}

	return apply_filters( 'opalestate_get_featured_image_sizes', $sizes );
}

/**
 *
 */
function opalestate_search_office_uri() {
	return get_post_type_archive_link( 'opalestate_office' );
}

/**
 *
 */
function opalestate_get_current_uri() {
	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );

	return $current_url;
}

/**
 *
 */
function opalestate_search_agent_uri() {
	global $opalestate_options;

	//return get_post_type_archive_link( 'opalestate_agent' );

	$search_agents = isset( $opalestate_options['search_agents'] ) ? get_permalink( absint( $opalestate_options['search_agents'] ) ) : opalestate_get_current_uri();

	return apply_filters( 'opalestate_get_search_agents_page_uri', $search_agents );
}

/**
 *
 */
function opalestate_get_session_location_val() {
	return isset( $_SESSION['set_location'] ) ? $_SESSION['set_location'] : 0;
}


function opalestate_get_location_active() {
	$location = opalestate_get_session_location_val();
	if ( ! is_numeric( $location ) ) {
		$term = get_term_by( 'slug', $location, 'opalestate_location' );
		$name = is_object( $term ) ? $term->name : __( 'Your Location', 'opalestate' );

		return $name;
	} else {
		return __( 'Your Location', 'opalestate' );
	}
}


function opalestate_get_upload_image_url( $attach_data ) {
	$upload_dir       = wp_upload_dir();
	$image_path_array = explode( '/', $attach_data['file'] );
	$image_path_array = array_slice( $image_path_array, 0, count( $image_path_array ) - 1 );
	$image_path       = implode( '/', $image_path_array );
	$thumbnail_name   = null;
	if ( isset( $attach_data['sizes']['user-image'] ) ) {
		$thumbnail_name = $attach_data['sizes']['user-image']['file'];
	} elseif ( isset( $attach_data['sizes']['thumbnail']['file'] ) ) {
		$thumbnail_name = $attach_data['sizes']['thumbnail']['file'];
	} else {
		return $upload_dir['baseurl'] . '/' . $attach_data['file'];
	}

	return $upload_dir['baseurl'] . '/' . $image_path . '/' . $thumbnail_name;
}

function opalestate_update_attachement_used( $attachment_id ) {

}

/**
 * batch including all files in a path.
 *
 * @param String $path : PATH_DIR/*.php or PATH_DIR with $ifiles not empty
 */
function opalestate_includes( $path, $ifiles = [] ) {

	if ( ! empty( $ifiles ) ) {
		foreach ( $ifiles as $key => $file ) {
			$file = $path . '/' . $file;
			if ( is_file( $file ) ) {
				require( $file );
			}
		}
	} else {
		$files = glob( $path );
		foreach ( $files as $key => $file ) {
			if ( is_file( $file ) ) {
				require( $file );
			}
		}
	}
}

/**
 *
 */
function opalesetate_property( $id ) {
	global $property;

	$property = new Opalestate_Property( $id );

	return $property;
}

function opalestate_options( $key, $default = '' ) {

	global $opalestate_options;

	$value = isset( $opalestate_options[ $key ] ) ? $opalestate_options[ $key ] : $default;
	$value = apply_filters( 'opalestate_option_', $value, $key, $default );

	return apply_filters( 'opalestate_option_' . $key, $value, $key, $default );
}

function opalestate_get_image_placeholder( $size, $url = false ) {

	global $_wp_additional_sizes;

	$width    = 0;
	$height   = 0;
	$img_text = get_bloginfo( 'name' );

	if ( in_array( $size, [ 'thumbnail', 'medium', 'large' ] ) ) {
		$width  = get_option( $size . '_size_w' );
		$height = get_option( $size . '_size_h' );

	} elseif ( isset( $_wp_additional_sizes[ $size ] ) ) {

		$width  = $_wp_additional_sizes[ $size ]['width'];
		$height = $_wp_additional_sizes[ $size ]['height'];
	}

	if ( intval( $width ) > 0 && intval( $height ) > 0 ) {
		if ( $url ) {
			return 'http://placehold.it/' . $width . 'x' . $height . '&text=' . urlencode( $img_text );
		}

		return '<img src="http://placehold.it/' . $width . 'x' . $height . '&text=' . urlencode( $img_text ) . '" />';
	}
}


/**
 * Get User IP
 *
 * Returns the IP address of the current visitor
 *
 * @return string $ip User's IP address
 * @since 1.0
 */
function opalestate_get_ip() {

	$ip = '127.0.0.1';

	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		//check ip from share internet
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		//to check ip is pass from proxy
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return apply_filters( 'opalestate_get_ip', $ip );
}


/**
 *
 */
function opalestate_get_currencies() {
	$currencies = [
		'AED' => __( 'United Arab Emirates dirham', 'opalestate' ),
		'AFN' => __( 'Afghan afghani', 'opalestate' ),
		'ALL' => __( 'Albanian lek', 'opalestate' ),
		'AMD' => __( 'Armenian dram', 'opalestate' ),
		'ANG' => __( 'Netherlands Antillean guilder', 'opalestate' ),
		'AOA' => __( 'Angolan kwanza', 'opalestate' ),
		'ARS' => __( 'Argentine peso', 'opalestate' ),
		'AUD' => __( 'Australian dollar', 'opalestate' ),
		'AWG' => __( 'Aruban florin', 'opalestate' ),
		'AZN' => __( 'Azerbaijani manat', 'opalestate' ),
		'BAM' => __( 'Bosnia and Herzegovina convertible mark', 'opalestate' ),
		'BBD' => __( 'Barbadian dollar', 'opalestate' ),
		'BDT' => __( 'Bangladeshi taka', 'opalestate' ),
		'BGN' => __( 'Bulgarian lev', 'opalestate' ),
		'BHD' => __( 'Bahraini dinar', 'opalestate' ),
		'BIF' => __( 'Burundian franc', 'opalestate' ),
		'BMD' => __( 'Bermudian dollar', 'opalestate' ),
		'BND' => __( 'Brunei dollar', 'opalestate' ),
		'BOB' => __( 'Bolivian boliviano', 'opalestate' ),
		'BRL' => __( 'Brazilian real', 'opalestate' ),
		'BSD' => __( 'Bahamian dollar', 'opalestate' ),
		'BTC' => __( 'Bitcoin', 'opalestate' ),
		'BTN' => __( 'Bhutanese ngultrum', 'opalestate' ),
		'BWP' => __( 'Botswana pula', 'opalestate' ),
		'BYR' => __( 'Belarusian ruble', 'opalestate' ),
		'BZD' => __( 'Belize dollar', 'opalestate' ),
		'CAD' => __( 'Canadian dollar', 'opalestate' ),
		'CDF' => __( 'Congolese franc', 'opalestate' ),
		'CHF' => __( 'Swiss franc', 'opalestate' ),
		'CLP' => __( 'Chilean peso', 'opalestate' ),
		'CNY' => __( 'Chinese yuan', 'opalestate' ),
		'COP' => __( 'Colombian peso', 'opalestate' ),
		'CRC' => __( 'Costa Rican col&oacute;n', 'opalestate' ),
		'CUC' => __( 'Cuban convertible peso', 'opalestate' ),
		'CUP' => __( 'Cuban peso', 'opalestate' ),
		'CVE' => __( 'Cape Verdean escudo', 'opalestate' ),
		'CZK' => __( 'Czech koruna', 'opalestate' ),
		'DJF' => __( 'Djiboutian franc', 'opalestate' ),
		'DKK' => __( 'Danish krone', 'opalestate' ),
		'DOP' => __( 'Dominican peso', 'opalestate' ),
		'DZD' => __( 'Algerian dinar', 'opalestate' ),
		'EGP' => __( 'Egyptian pound', 'opalestate' ),
		'ERN' => __( 'Eritrean nakfa', 'opalestate' ),
		'ETB' => __( 'Ethiopian birr', 'opalestate' ),
		'EUR' => __( 'Euro', 'opalestate' ),
		'FJD' => __( 'Fijian dollar', 'opalestate' ),
		'FKP' => __( 'Falkland Islands pound', 'opalestate' ),
		'GBP' => __( 'Pound sterling', 'opalestate' ),
		'GEL' => __( 'Georgian lari', 'opalestate' ),
		'GGP' => __( 'Guernsey pound', 'opalestate' ),
		'GHS' => __( 'Ghana cedi', 'opalestate' ),
		'GIP' => __( 'Gibraltar pound', 'opalestate' ),
		'GMD' => __( 'Gambian dalasi', 'opalestate' ),
		'GNF' => __( 'Guinean franc', 'opalestate' ),
		'GTQ' => __( 'Guatemalan quetzal', 'opalestate' ),
		'GYD' => __( 'Guyanese dollar', 'opalestate' ),
		'HKD' => __( 'Hong Kong dollar', 'opalestate' ),
		'HNL' => __( 'Honduran lempira', 'opalestate' ),
		'HRK' => __( 'Croatian kuna', 'opalestate' ),
		'HTG' => __( 'Haitian gourde', 'opalestate' ),
		'HUF' => __( 'Hungarian forint', 'opalestate' ),
		'IDR' => __( 'Indonesian rupiah', 'opalestate' ),
		'ILS' => __( 'Israeli new shekel', 'opalestate' ),
		'IMP' => __( 'Manx pound', 'opalestate' ),
		'INR' => __( 'Indian rupee', 'opalestate' ),
		'IQD' => __( 'Iraqi dinar', 'opalestate' ),
		'IRR' => __( 'Iranian rial', 'opalestate' ),
		'ISK' => __( 'Icelandic kr&oacute;na', 'opalestate' ),
		'JEP' => __( 'Jersey pound', 'opalestate' ),
		'JMD' => __( 'Jamaican dollar', 'opalestate' ),
		'JOD' => __( 'Jordanian dinar', 'opalestate' ),
		'JPY' => __( 'Japanese yen', 'opalestate' ),
		'KES' => __( 'Kenyan shilling', 'opalestate' ),
		'KGS' => __( 'Kyrgyzstani som', 'opalestate' ),
		'KHR' => __( 'Cambodian riel', 'opalestate' ),
		'KMF' => __( 'Comorian franc', 'opalestate' ),
		'KPW' => __( 'North Korean won', 'opalestate' ),
		'KRW' => __( 'South Korean won', 'opalestate' ),
		'KWD' => __( 'Kuwaiti dinar', 'opalestate' ),
		'KYD' => __( 'Cayman Islands dollar', 'opalestate' ),
		'KZT' => __( 'Kazakhstani tenge', 'opalestate' ),
		'LAK' => __( 'Lao kip', 'opalestate' ),
		'LBP' => __( 'Lebanese pound', 'opalestate' ),
		'LKR' => __( 'Sri Lankan rupee', 'opalestate' ),
		'LRD' => __( 'Liberian dollar', 'opalestate' ),
		'LSL' => __( 'Lesotho loti', 'opalestate' ),
		'LYD' => __( 'Libyan dinar', 'opalestate' ),
		'MAD' => __( 'Moroccan dirham', 'opalestate' ),
		'MDL' => __( 'Moldovan leu', 'opalestate' ),
		'MGA' => __( 'Malagasy ariary', 'opalestate' ),
		'MKD' => __( 'Macedonian denar', 'opalestate' ),
		'MMK' => __( 'Burmese kyat', 'opalestate' ),
		'MNT' => __( 'Mongolian t&ouml;gr&ouml;g', 'opalestate' ),
		'MOP' => __( 'Macanese pataca', 'opalestate' ),
		'MRO' => __( 'Mauritanian ouguiya', 'opalestate' ),
		'MUR' => __( 'Mauritian rupee', 'opalestate' ),
		'MVR' => __( 'Maldivian rufiyaa', 'opalestate' ),
		'MWK' => __( 'Malawian kwacha', 'opalestate' ),
		'MXN' => __( 'Mexican peso', 'opalestate' ),
		'MYR' => __( 'Malaysian ringgit', 'opalestate' ),
		'MZN' => __( 'Mozambican metical', 'opalestate' ),
		'NAD' => __( 'Namibian dollar', 'opalestate' ),
		'NGN' => __( 'Nigerian naira', 'opalestate' ),
		'NIO' => __( 'Nicaraguan c&oacute;rdoba', 'opalestate' ),
		'NOK' => __( 'Norwegian krone', 'opalestate' ),
		'NPR' => __( 'Nepalese rupee', 'opalestate' ),
		'NZD' => __( 'New Zealand dollar', 'opalestate' ),
		'OMR' => __( 'Omani rial', 'opalestate' ),
		'PAB' => __( 'Panamanian balboa', 'opalestate' ),
		'PEN' => __( 'Peruvian nuevo sol', 'opalestate' ),
		'PGK' => __( 'Papua New Guinean kina', 'opalestate' ),
		'PHP' => __( 'Philippine peso', 'opalestate' ),
		'PKR' => __( 'Pakistani rupee', 'opalestate' ),
		'PLN' => __( 'Polish z&#x142;oty', 'opalestate' ),
		'PRB' => __( 'Transnistrian ruble', 'opalestate' ),
		'PYG' => __( 'Paraguayan guaran&iacute;', 'opalestate' ),
		'QAR' => __( 'Qatari riyal', 'opalestate' ),
		'RON' => __( 'Romanian leu', 'opalestate' ),
		'RSD' => __( 'Serbian dinar', 'opalestate' ),
		'RUB' => __( 'Russian ruble', 'opalestate' ),
		'RWF' => __( 'Rwandan franc', 'opalestate' ),
		'SAR' => __( 'Saudi riyal', 'opalestate' ),
		'SBD' => __( 'Solomon Islands dollar', 'opalestate' ),
		'SCR' => __( 'Seychellois rupee', 'opalestate' ),
		'SDG' => __( 'Sudanese pound', 'opalestate' ),
		'SEK' => __( 'Swedish krona', 'opalestate' ),
		'SGD' => __( 'Singapore dollar', 'opalestate' ),
		'SHP' => __( 'Saint Helena pound', 'opalestate' ),
		'SLL' => __( 'Sierra Leonean leone', 'opalestate' ),
		'SOS' => __( 'Somali shilling', 'opalestate' ),
		'SRD' => __( 'Surinamese dollar', 'opalestate' ),
		'SSP' => __( 'South Sudanese pound', 'opalestate' ),
		'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra', 'opalestate' ),
		'SYP' => __( 'Syrian pound', 'opalestate' ),
		'SZL' => __( 'Swazi lilangeni', 'opalestate' ),
		'THB' => __( 'Thai baht', 'opalestate' ),
		'TJS' => __( 'Tajikistani somoni', 'opalestate' ),
		'TMT' => __( 'Turkmenistan manat', 'opalestate' ),
		'TND' => __( 'Tunisian dinar', 'opalestate' ),
		'TOP' => __( 'Tongan pa&#x2bb;anga', 'opalestate' ),
		'TRY' => __( 'Turkish lira', 'opalestate' ),
		'TTD' => __( 'Trinidad and Tobago dollar', 'opalestate' ),
		'TWD' => __( 'New Taiwan dollar', 'opalestate' ),
		'TZS' => __( 'Tanzanian shilling', 'opalestate' ),
		'UAH' => __( 'Ukrainian hryvnia', 'opalestate' ),
		'UGX' => __( 'Ugandan shilling', 'opalestate' ),
		'USD' => __( 'United States dollar', 'opalestate' ),
		'UYU' => __( 'Uruguayan peso', 'opalestate' ),
		'UZS' => __( 'Uzbekistani som', 'opalestate' ),
		'VEF' => __( 'Venezuelan bol&iacute;var', 'opalestate' ),
		'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng', 'opalestate' ),
		'VUV' => __( 'Vanuatu vatu', 'opalestate' ),
		'WST' => __( 'Samoan t&#x101;l&#x101;', 'opalestate' ),
		'XAF' => __( 'Central African CFA franc', 'opalestate' ),
		'XCD' => __( 'East Caribbean dollar', 'opalestate' ),
		'XOF' => __( 'West African CFA franc', 'opalestate' ),
		'XPF' => __( 'CFP franc', 'opalestate' ),
		'YER' => __( 'Yemeni rial', 'opalestate' ),
		'ZAR' => __( 'South African rand', 'opalestate' ),
		'ZMW' => __( 'Zambian kwacha', 'opalestate' ),
	];

	return apply_filters( 'opalestate_currencies', $currencies );
}

/**
 * Get the price format depending on the currency position
 *
 * @return string
 */
function opalestate_price_format_position() {
	global $opalestate_options;
	$currency_pos = opalestate_options( 'currency_position', 'before' );

	$format = '%1$s%2$s';
	switch ( $currency_pos ) {
		case 'before' :
			$format = '%1$s%2$s';
			break;
		case 'after' :
			$format = '%2$s%1$s';
			break;
		case 'left_space' :
			$format = '%1$s&nbsp;%2$s';
			break;
		case 'right_space' :
			$format = '%2$s&nbsp;%1$s';
			break;
	}

	return apply_filters( 'opalestate_price_format_position', $format, $currency_pos );
}

/**
 *
 */
function opalestate_price_format( $price, $args = [] ) {

	$price = opalestate_price( $price, $args );
	$price = sprintf( opalestate_price_format_position(), opalestate_currency_symbol(), $price );

	return apply_filters( 'opalestate_price_format', $price );
}

function opalestate_get_currency() {
	return opalestate_options( 'currency', 'USD' );
}

/**
 *
 */
function opalestate_currency_symbol( $currency = '' ) {
	if ( ! $currency ) {
		$currency = opalestate_get_currency();
	}

	$symbols = apply_filters( 'opalestate_currency_symbols', [
		'AED' => '&#x62f;.&#x625;',
		'AFN' => '&#x60b;',
		'ALL' => 'L',
		'AMD' => 'AMD',
		'ANG' => '&fnof;',
		'AOA' => 'Kz',
		'ARS' => '&#36;',
		'AUD' => '&#36;',
		'AWG' => '&fnof;',
		'AZN' => 'AZN',
		'BAM' => 'KM',
		'BBD' => '&#36;',
		'BDT' => '&#2547;&nbsp;',
		'BGN' => '&#1083;&#1074;.',
		'BHD' => '.&#x62f;.&#x628;',
		'BIF' => 'Fr',
		'BMD' => '&#36;',
		'BND' => '&#36;',
		'BOB' => 'Bs.',
		'BRL' => '&#82;&#36;',
		'BSD' => '&#36;',
		'BTC' => '&#3647;',
		'BTN' => 'Nu.',
		'BWP' => 'P',
		'BYR' => 'Br',
		'BZD' => '&#36;',
		'CAD' => '&#36;',
		'CDF' => 'Fr',
		'CHF' => '&#67;&#72;&#70;',
		'CLP' => '&#36;',
		'CNY' => '&yen;',
		'COP' => '&#36;',
		'CRC' => '&#x20a1;',
		'CUC' => '&#36;',
		'CUP' => '&#36;',
		'CVE' => '&#36;',
		'CZK' => '&#75;&#269;',
		'DJF' => 'Fr',
		'DKK' => 'DKK',
		'DOP' => 'RD&#36;',
		'DZD' => '&#x62f;.&#x62c;',
		'EGP' => 'EGP',
		'ERN' => 'Nfk',
		'ETB' => 'Br',
		'EUR' => '&euro;',
		'FJD' => '&#36;',
		'FKP' => '&pound;',
		'GBP' => '&pound;',
		'GEL' => '&#x10da;',
		'GGP' => '&pound;',
		'GHS' => '&#x20b5;',
		'GIP' => '&pound;',
		'GMD' => 'D',
		'GNF' => 'Fr',
		'GTQ' => 'Q',
		'GYD' => '&#36;',
		'HKD' => '&#36;',
		'HNL' => 'L',
		'HRK' => 'Kn',
		'HTG' => 'G',
		'HUF' => '&#70;&#116;',
		'IDR' => 'Rp',
		'ILS' => '&#8362;',
		'IMP' => '&pound;',
		'INR' => '&#8377;',
		'IQD' => '&#x639;.&#x62f;',
		'IRR' => '&#xfdfc;',
		'ISK' => 'Kr.',
		'JEP' => '&pound;',
		'JMD' => '&#36;',
		'JOD' => '&#x62f;.&#x627;',
		'JPY' => '&yen;',
		'KES' => 'KSh',
		'KGS' => '&#x43b;&#x432;',
		'KHR' => '&#x17db;',
		'KMF' => 'Fr',
		'KPW' => '&#x20a9;',
		'KRW' => '&#8361;',
		'KWD' => '&#x62f;.&#x643;',
		'KYD' => '&#36;',
		'KZT' => 'KZT',
		'LAK' => '&#8365;',
		'LBP' => '&#x644;.&#x644;',
		'LKR' => '&#xdbb;&#xdd4;',
		'LRD' => '&#36;',
		'LSL' => 'L',
		'LYD' => '&#x644;.&#x62f;',
		'MAD' => '&#x62f;. &#x645;.',
		'MAD' => '&#x62f;.&#x645;.',
		'MDL' => 'L',
		'MGA' => 'Ar',
		'MKD' => '&#x434;&#x435;&#x43d;',
		'MMK' => 'Ks',
		'MNT' => '&#x20ae;',
		'MOP' => 'P',
		'MRO' => 'UM',
		'MUR' => '&#x20a8;',
		'MVR' => '.&#x783;',
		'MWK' => 'MK',
		'MXN' => '&#36;',
		'MYR' => '&#82;&#77;',
		'MZN' => 'MT',
		'NAD' => '&#36;',
		'NGN' => '&#8358;',
		'NIO' => 'C&#36;',
		'NOK' => '&#107;&#114;',
		'NPR' => '&#8360;',
		'NZD' => '&#36;',
		'OMR' => '&#x631;.&#x639;.',
		'PAB' => 'B/.',
		'PEN' => 'S/.',
		'PGK' => 'K',
		'PHP' => '&#8369;',
		'PKR' => '&#8360;',
		'PLN' => '&#122;&#322;',
		'PRB' => '&#x440;.',
		'PYG' => '&#8370;',
		'QAR' => '&#x631;.&#x642;',
		'RMB' => '&yen;',
		'RON' => 'lei',
		'RSD' => '&#x434;&#x438;&#x43d;.',
		'RUB' => '&#8381;',
		'RWF' => 'Fr',
		'SAR' => '&#x631;.&#x633;',
		'SBD' => '&#36;',
		'SCR' => '&#x20a8;',
		'SDG' => '&#x62c;.&#x633;.',
		'SEK' => '&#107;&#114;',
		'SGD' => '&#36;',
		'SHP' => '&pound;',
		'SLL' => 'Le',
		'SOS' => 'Sh',
		'SRD' => '&#36;',
		'SSP' => '&pound;',
		'STD' => 'Db',
		'SYP' => '&#x644;.&#x633;',
		'SZL' => 'L',
		'THB' => '&#3647;',
		'TJS' => '&#x405;&#x41c;',
		'TMT' => 'm',
		'TND' => '&#x62f;.&#x62a;',
		'TOP' => 'T&#36;',
		'TRY' => '&#8378;',
		'TTD' => '&#36;',
		'TWD' => '&#78;&#84;&#36;',
		'TZS' => 'Sh',
		'UAH' => '&#8372;',
		'UGX' => 'UGX',
		'USD' => '&#36;',
		'UYU' => '&#36;',
		'UZS' => 'UZS',
		'VEF' => 'Bs F',
		'VND' => '&#8363;',
		'VUV' => 'Vt',
		'WST' => 'T',
		'XAF' => 'Fr',
		'XCD' => '&#36;',
		'XOF' => 'Fr',
		'XPF' => 'Fr',
		'YER' => '&#xfdfc;',
		'ZAR' => '&#82;',
		'ZMW' => 'ZK',
	] );

	$currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '';

	return apply_filters( 'opalestate_currency_symbol', $currency_symbol, $currency );
}

/**
 * Return the thousand separator for prices
 *
 * @return string
 * @since  2.3
 */
function opalestate_get_price_thousand_separator() {
	$separator = stripslashes( opalestate_options( 'thousands_separator' ) );

	return $separator;
}

/**
 * Return the decimal separator for prices
 *
 * @return string
 * @since  2.3
 */
function opalestate_get_price_decimal_separator() {
	$separator = stripslashes( opalestate_options( 'decimal_separator', '.' ) );

	return $separator ? $separator : '.';
}

/**
 * Return the number of decimals after the decimal point.
 *
 * @return int
 * @since  2.3
 */
function opalestate_get_price_decimals() {
	return absint( opalestate_options( 'number_decimals', 2 ) );
}


/**
 *
 */
function opalestate_price( $price, $args = [] ) {

	$negative = $price < 0;

	if ( $negative ) {
		$price = substr( $price, 1 );
	}


	extract( apply_filters( 'opalestate_price_args', wp_parse_args( $args, [
		'ex_tax_label'       => false,
		'decimal_separator'  => opalestate_get_price_decimal_separator(),
		'thousand_separator' => opalestate_get_price_thousand_separator(),
		'decimals'           => opalestate_get_price_decimals(),

	] ) ) );

	$negative = $price < 0;
	$price    = apply_filters( 'opalestate_raw_price', floatval( $negative ? $price * -1 : $price ) );
	$price    = apply_filters( 'opalestate_formatted_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

	return $price;
}

/**
 *
 *  Applyer function to show unit for property
 */

function opalestate_areasize_unit_format( $value = '' ) {
	return $value . ' ' . '<span>' . opalestate_options( 'measurement_unit', 'sq ft' ) . '</span>';
}

add_filter( 'opalestate_areasize_unit_format', 'opalestate_areasize_unit_format' );

/**
 *
 *  Applyer function to show unit for property
 */
if ( ! function_exists( 'opalestate_fnc_excerpt' ) ) {
	//Custom Excerpt Function
	function opalestate_fnc_excerpt( $limit, $afterlimit = '[...]' ) {
		$excerpt = get_the_excerpt();

		return opalestate_fnc_get_words( $excerpt, $limit, $afterlimit );
	}
}

function opalestate_fnc_get_words( $excerpt, $limit, $afterlimit = '...' ) {
	if ( $excerpt != '' ) {
		$excerpt = explode( ' ', strip_tags( $excerpt ), $limit );
	} else {
		$excerpt = explode( ' ', strip_tags( get_the_content() ), $limit );
	}
	if ( count( $excerpt ) >= $limit ) {
		array_pop( $excerpt );
		$excerpt = implode( " ", $excerpt ) . ' ' . $afterlimit;
	} else {
		$excerpt = implode( " ", $excerpt );
	}
	$excerpt = preg_replace( '`[[^]]*]`', '', $excerpt );

	return strip_shortcodes( $excerpt );
}

/**
 *
 */
function opalestate_is_own_property( $post_id, $user_id ) {
	$post = get_post( $post_id );
	wp_reset_postdata();
	if ( ! is_object( $post ) || ! $post->ID ) {
		return false;
	}

	return $user_id == $post->post_author;
}

function opalestate_pagination( $pages = '', $range = 2 ) {
	global $wp_query;

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
	$paged = isset( $wp_query->query['paged'] ) ? $wp_query->query['paged'] : $paged;
	$paged = isset( $_REQUEST['paged'] ) ? absint( $_REQUEST['paged'] ) : $paged;

	// if(empty($paged))$paged = 1;

	$prev      = $paged - 1;
	$next      = $paged + 1;
	$showitems = ( $range * 2 ) + 1;
	$range     = 2; // change it to show more links

	if ( $pages == '' ) {
		global $wp_query;

		$pages = $wp_query->max_num_pages;
		if ( ! $pages ) {
			$pages = 1;
		}
	}

	if ( 1 != $pages ) {

		echo '<div class="pbr-pagination pagination-main">';
		echo '<ul class="pagination">';
		echo ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) ? '<li><a aria-label="First" href="' . get_pagenum_link( 1 ) . '"><span aria-hidden="true"><i class="fa fa-angle-double-left"></i></span></a></li>' : '';
		echo ( $paged > 1 ) ? '<li><a aria-label="' . __( 'Previous',
				'opalestate' ) . '" href="' . get_pagenum_link( $prev ) . '"><span aria-hidden="true"><i class="fa fa-angle-left"></i></span></a></li>' : '<li class="disabled"><a aria-label="Previous"><span aria-hidden="true"><i class="fa fa-angle-left"></i></span></a></li>';
		for ( $i = 1; $i <= $pages; $i++ ) {
			if ( 1 != $pages && ( ! ( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
				if ( $paged == $i ) {
					echo '<li class="active" data-paged="' . $i . '"><a href="' . get_pagenum_link( $i ) . '">' . $i . ' <span class="sr-only"></span></a></li>';
				} else {
					echo '<li data-paged="' . $i . '"><a href="' . get_pagenum_link( $i ) . '">' . $i . '</a></li>';
				}
			}
		}
		echo ( $paged < $pages ) ? '<li data-paged="' . $next . '"><a aria-label="' . __( 'Next',
				'opalestate' ) . '" href="' . get_pagenum_link( $next ) . '"><span aria-hidden="true"><i class="fa fa-angle-right"></i></span></a></li>' : '';
		echo ( $paged < $pages - 1 && $paged + $range - 1 < $pages && $showitems < $pages ) ? '<li data-paged="' . $prev . '"><a aria-label="Last" href="' . get_pagenum_link( $pages ) . '"><span aria-hidden="true"><i class="fa fa-angle-double-right"></i></span></a></li>' : '';
		echo '</ul>';
		echo '</div>';

	}
}

if ( ! function_exists( 'opalesate_insert_user_agent' ) ) {

	function opalesate_insert_user_agent( $args = [] ) {
		$userdata = wp_parse_args( $args, [
			'first_name' => '',
			'last_name'  => '',
			'avatar'     => '',
			'job'        => '',
			'email'      => '',
			'phone'      => '',
			'mobile'     => '',
			'fax'        => '',
			'web'        => '',
			'address'    => '',
			'twitter'    => '',
			'facebook'   => '',
			'google'     => '',
			'linkedin'   => '',
			'instagram'  => '',
		] );

		$agent_id = wp_insert_post( [
			'post_title'   => $args['first_name'] && $args['last_name'] ? $args['first_name'] . ' ' . $args['last_name'] : $userdata['email'],
			'post_content' => 'empty description',
			'post_excerpt' => 'empty excerpt',
			'post_type'    => 'opalestate_agent',
			'post_status'  => 'publish',
			'post_author'  => 1,
		], true );

		foreach ( $userdata as $key => $value ) {
			if ( in_array( $key, [ 'first_name', 'last_name' ] ) ) {
				continue;
			}
			update_post_meta( $agent_id, OPALESTATE_AGENT_PREFIX . $key, $value );
		}
		do_action( 'opalesate_insert_user_agent', $agent_id );

		return $agent_id;
	}
}

/**
 * Returns the multilingual instance.
 *
 * @return Opalestate_Multilingual
 */
function opalestate_multilingual() {
	$multilingual = new Opalestate_Multilingual();

	return $multilingual;
}

/**
 * Is current WordPress is running on multi-languages.
 *
 * @return bool
 */
function opalestate_running_on_multilanguage() {
	return apply_filters( 'opalestate_is_running_multilanguage', Opalestate_Multilingual::is_polylang() || Opalestate_Multilingual::is_wpml() );
}

/**
 * Add hidden multilingual.
 *
 * @return void
 */
function opalestate_add_hidden_multilingual() {
	if ( ! opalestate_running_on_multilanguage() ) {
		return;
	}
	?>
    <input type="hidden" name="lang" value="<?php echo opalestate_multilingual()->get_current_language(); ?>">
	<?php
}

add_action( 'opalestate_after_search_properties_form', 'opalestate_add_hidden_multilingual' );

/**
 * Returns measurement unit.
 *
 * @return mixed|void
 */
function opalestate_measurement_unit() {
	return apply_filters( 'opalestate_measurement_unit', [
		'sq ft' => __( 'sq ft', 'opalestate' ),
		'sq m'  => __( 'sq m', 'opalestate' ),
		'mq'    => __( 'mq', 'opalestate' ),
		'm2'    => __( 'm2', 'opalestate' ),
	] );
}

/**
 * Returns property statuses.
 *
 * @return mixed|void
 */
function opalestate_get_property_statuses() {
	return apply_filters( 'opalestate_property_statuses', [
		'all'       => esc_html__( 'All', 'opalestate' ),
		'publish'   => esc_html__( 'Published', 'opalestate' ),
		'published' => esc_html__( 'Published', 'opalestate' ),
		'pending'   => esc_html__( 'Pending', 'opalestate' ),
		'expired'   => esc_html__( 'Expired', 'opalestate' ),
	] );
}
