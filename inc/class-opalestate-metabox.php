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
 
class Opalestate_Property_MetaBox {

		/**
		 * Create meta box based on given data
		 *
		 * @see demo/demo.php file for details
		 *
		 * @param array $meta_box Meta box definition
		 *
		 * @return RW_Meta_Box
		 */
		public function __construct( $meta_box )
		{
			


			// Run script only in admin area
			//if ( ! is_admin() )
			//	return;
			
			// Assign meta box values to local variables and add it's missed values
			$this->meta_box = self::normalize( $meta_box );
			$this->fields   = &$this->meta_box['fields'];

		 
			// Enqueue common styles and scripts
			

			// Add additional actions for fields
			$fields = self::get_fields( $this->fields );
			foreach ( $fields as $field )
			{
			// 	call_user_func( array( self::get_class_name( $field ), 'add_actions' ) );
			}
		}


		
}