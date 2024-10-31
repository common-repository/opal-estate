<?php 
	function opalestate_membership_settings_submission( $fields ){
		$tmp = array(
			array(
				'name'    => __( 'Enable Free Submit', 'opalestate' ),
				'desc'    => __( 'This function works with opal membership > version 1.1. Allow  set automatic free package', 'opalestate' ),
				'id'      => 'enabel_free_submission',
				'type'    => 'select',
				'options' => array( 
					1 => __( 'Yes', 'opalestate' ),
					0 => __( 'No', 'opalestate' ),
				),
			),
			array(
				'name'    => __( 'Number Free Listing', 'opalestate' ),
				'desc'    => __( 'Maximun Free Listing User can submit', 'opalestate' ),
				'id'      => 'free_number_listing',
				'type'    => 'text',
				'default' => 3,
			),
			array(
				'name'    => __( 'Number Free Featured', 'opalestate' ),
				'desc'    => __( 'Maximun Free Featured user can set.', 'opalestate' ),
				'id'      => 'free_number_featured',
				'type'    => 'text',
				'default' => 3,
			),
		);
		return array_merge( $fields, $tmp );
	}	

	add_filter( 'opalestate_settings_submission', 'opalestate_membership_settings_submission' );

if( opalestate_options('enabel_free_submission') ){
	function opalestate_check_is_membership_valid( $status , $package_id, $user_id ){
		if( $package_id != -1 ){
			return false;
		}
		$package_expired = get_user_meta( $user_id, OPALMEMBERSHIP_USER_PREFIX_.'package_expired', true );
		    
		if( !is_numeric($package_expired) ){
			$package_expired = strtotime( $package_expired );
		}
	    if ( ! $package_expired ||  $package_expired <= time() ) {
	        return false;
	    }

		return true;
	}
	add_filter( 'opalmembership_check_is_membership_valid', 'opalestate_check_is_membership_valid', 3, 3 );

	/**
	 *
	 */ 
	function opalestate_get_freepackage_obj(){ 

		$object = new Opalmembership_Package();	 
		$object->post_title =  __( 'Free membership', 'opalestate' );
		return $object; 
	 
	}
	
	add_filter( 'opalmembership_get_object_membership', 'opalestate_get_freepackage_obj' );

	/// free account
	add_action( 'user_register', 'opalestate_on_create_user', 10, 1 );
	add_action( 'profile_update', 'opalestate_on_update_user');
	function opalestate_on_create_user( $user_id  ){
		if( $user_id ){  
			opalestate_reset_user_free_package( $user_id );
		}
	}
	function opalestate_on_update_user( $user_id ){
		$package_id = get_user_meta( $user_id, OPALMEMBERSHIP_USER_PREFIX_.'package_id' , true );  
		if( empty($package_id) ){
			opalestate_reset_user_free_package( $user_id );
		}
	}
}
?>