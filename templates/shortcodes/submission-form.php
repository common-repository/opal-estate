<div class="panel panel-default">
	<div class="panel-body">
		<div class="property-submission-form metabox-fields-front space-padding-lr-40 space-padding-tb-30">

			<?php if ( ! empty( $_SESSION['messages'] ) ) : ?>

				<div class="opalesate_messages">
					<?php foreach ( $_SESSION['messages'] as $message ) : ?>

						<?php $status = isset( $message[0] ) ? $message[0] : 'success'; ?>
						<?php $msg = isset( $message[1] ) ? $message[1] : ''; ?>
						<div class="opalesate_message_line <?php echo esc_attr( $status ) ?>">
							<?php printf( '%s', $msg ) ?>
						</div>

					<?php endforeach; unset( $_SESSION['messages'] ); ?>
				</div>

			<?php endif; ?>

			<?php if( ! $post_id ) : ?>
				<h1><?php _e( 'Add New Property', 'opalestate' ); ?></h1>
			<?php else : ?>
				<h1>
					<?php _e( 'Edit Property', 'opalestate' ) ; ?>
				
				</h1>
			<?php endif; ?>

			<?php
				do_action( 'opalestate_submission_form_before' );

				echo cmb2_get_metabox_form( $metaboxes[ OPALESTATE_PROPERTY_PREFIX . 'front' ], $post_id, array(
					'form_format' => '<form action="//' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '" class="cmb-form" method="post" id="%1$s" enctype="multipart/form-data" encoding="multipart/form-data"><input type="hidden" name="object_id" value="%2$s">%3$s<input type="submit" name="submit-cmb" value="%4$s" class="button-primary btn btn-primary btn-submit-cmb"></form>',
					'save_button' => __( 'Save property', 'opalestate' ),
				) );

				do_action( 'opalestate_submission_form_after' );
			?>

		</div>
	</div>
</div>
