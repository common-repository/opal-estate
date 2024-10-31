<?php
$statuses = opalestate_get_property_statuses();

$gstatus = isset( $_GET['status'] ) ? $_GET['status'] : 'all';
?>
<?php do_action( "opalestate_submission_listing_before" ); ?>
<div class="property-listing my-properties">
    <div class="list-tabs">
        <div class="tabs">
            <ul class="clearfix">
				<?php foreach ( $statuses as $status => $label ): ?>
                    <li<?php if ( $status == $gstatus ): ?> class="active" <?php endif; ?>>
                        <a href="<?php echo esc_url( opalestate_get_user_properties_uri( [ 'status' => $status ] ) ); ?>"><?php echo esc_attr( $label ); ?></a>
                    </li>
				<?php endforeach; ?>
            </ul>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
			<?php if ( $loop->have_posts() ): ?>
                <div class="opalestate-rows">
                    <div class="<?php echo apply_filters( 'opalestate_row_container_class', 'row opal-row' ); ?>">
						<?php $cnt = 0;
						while ( $loop->have_posts() ) : $loop->the_post();
							global $post; ?>


                            <div class="col-lg-12 col-md-12 col-sm-12">
								<?php echo Opalestate_Template_Loader::get_template_part( 'user/content-property' ); ?>
                            </div>

						<?php endwhile; ?>
                    </div>
                </div>
				<?php opalestate_pagination( $loop->max_num_pages ); ?>

			<?php else : ?>
                <div class="opalestate-message">
					<?php esc_html_e( 'You have not submited any property.', 'opalestate' ); ?>
                </div>
			<?php endif; ?>
        </div>
    </div>
</div>
<?php wp_reset_postdata(); ?>
<?php do_action( "opalestate_submission_listing_after" ); ?>
