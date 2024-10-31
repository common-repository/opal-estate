<div class="wrap" id="opalestate-add-ons">
	<h1><?php esc_html_e( 'Opal Estate Add-ons', 'opalestate' ); ?>
		&nbsp;&mdash;&nbsp;<a href="https://opalestatewp.com/addons/" class="button-primary opalestate-view-addons-all" title="<?php esc_attr_e( 'Browse All Add-ons', 'opalestate' ); ?>" target="_blank"><?php esc_html_e( 'View All Add-ons', 'opalestate' ); ?>
			<span class="dashicons dashicons-external"></span></a>
	</h1>

	<p><?php esc_html_e( 'The following Add-ons extend the functionality of Opal Estate.', 'opalestate' ); ?></p>
	 <?php 
		$addons_debug = false; //set to true to debug
		$cache        = get_transient( 'opalestate_add_ons_feed' );

		if ( $cache === false || $addons_debug === true && WP_DEBUG === true ) {
			$feed = wp_remote_get( 'http://www.wpopal.com/opal-estate-addons-feed/', array( 'sslverify' => false ) );

			if ( ! is_wp_error( $feed ) ) {
				if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
					$cache = wp_remote_retrieve_body( $feed );
					set_transient( 'opalestate_add_ons_feed', $cache, 3600 );
				}
			} else {
				$cache = '<div class="error"><p>' . esc_html__( 'There was an error retrieving the Give Add-ons list from the server. Please try again later.', 'opalestate' ) . '</div>';
			}
		}
		echo $cache;
	?>
</div>