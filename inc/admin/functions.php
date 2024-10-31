<?php 
register_activation_hook(__FILE__, 'opalestate_active_cron_jobs');

function opalestate_active_cron_jobs() {
    if (! wp_next_scheduled ( 'opalestate_cleanup' )) {
		wp_schedule_event(time(), 'daily', 'opalestate_cleanup' );
    }
}
register_deactivation_hook(__FILE__, 'opalestate_deactive_cron_jobs');

function opalestate_deactive_cron_jobs() {
	wp_clear_scheduled_hook('opalestate_cleanup');
}

function opalestate_cleanup(){
 	$query = new WP_Query( 
        array( 
            'post_type'   => 'attachment', 
            'post_status' => 'inherit', 
            'date_query'    => array(
		        'column'  => 'post_date',
		          'before' => date('Y-m-d', strtotime('-1 days')) 
		    ),
           	'meta_query' => array(
                array(
                    'key' => '_pending_to_use_',
                     'value' => 1,
                     'compare' => '>=',
                )
            )     
        ) 
    );
 	// clean up per day 
    if( $query->have_posts() ){   
        while( $query->have_posts() ){ $query->the_post();
            wp_delete_attachment( get_the_ID() );
        }
    }
    wp_reset_postdata(); 
 }  
/**
 *
 */
function opalestate_admin_enqueue_assets(){ 
	
	$suffix = "";

	wp_enqueue_script(
		'opalestate-admin',
		OPALESTATE_PLUGIN_URL . 'assets/js/admin' . $suffix . '.js',
		array( 'jquery' ),
		null,
		true
	);		
}
add_action( 'admin_enqueue_scripts', "opalestate_admin_enqueue_assets");

/**
 * Searches for users via ajax and returns a list of results
 *
 * @since  1.0
 *
 * @return void
 */
function opalestate_ajax_search_users() {

	if ( current_user_can( 'manage_opalestate_settings' ) ) {

		$search_query = trim( $_POST['user_name'] );
		$exclude      = trim( $_POST['exclude'] );

		$get_users_args = array(
			'number' => 9999,
			'search' => $search_query . '*'
		);

		if ( ! empty( $exclude ) ) {
			$exclude_array             = explode( ',', $exclude );
			$get_users_args['exclude'] = $exclude_array;
		}

		$get_users_args = apply_filters( 'opalestate_search_users_args', $get_users_args );

		$found_users = apply_filters( 'opalestate_ajax_found_users', get_users( $get_users_args ), $search_query );

		$user_list = '<ul>';
		if ( $found_users ) {
			foreach ( $found_users as $user ) {
				$user_list .= '<li><a href="#" data-userid="' . esc_attr( $user->ID ) . '" data-login="' . esc_attr( $user->user_login ) . '">' . esc_html( $user->user_login ) . '</a></li>';
			}
		} else {
			$user_list .= '<li>' . esc_html__( 'No users found', 'opalestate' ) . '</li>';
		}
		$user_list .= '</ul>';

		echo json_encode( array( 'results' => $user_list ) );

	}
	die();
}

add_action( 'wp_ajax_opalestate_search_users', 'opalestate_ajax_search_users' );

function opalestate_ajax_search_username(){

	$search_query = trim( $_POST['user_name'] );
	$user 		 = get_userdatabylogin( $search_query );

	$output = array(); 

	if( $user ){
		$data = $user->data; 
		$data->author_link = get_author_posts_url( $user->data->ID ); 
		$data->avatar = get_avatar_url( $user->data->ID ); 
		$output['message'] = __( 'We could find this user', 'opalestate' );
		$output['status']  = true; 
		$output['user']    = $data;
	}else {
		$output['message'] = __( 'We could not find this user', 'opalestate' );
		$output['status']  = false; 
 	}

	echo json_encode( $output ); exit;

}
add_action( 'wp_ajax_opalestate_ajax_search_username', 'opalestate_ajax_search_username' );