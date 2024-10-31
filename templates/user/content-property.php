<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$property = opalesetate_property( get_the_ID() );

global $property, $post;

$meta     = $property->get_meta_shortinfo();
$statuses = opalestate_get_property_statuses();
$status   = get_post_status( get_the_ID() );
?>
<article itemscope itemtype="http://schema.org/Property" <?php post_class(); ?>>
    <div class="property-list-style">

        <div class="<?php echo apply_filters( 'opalestate_row_container_class', 'row opal-row' ); ?>">
            <header class="col-lg-4 col-sm-4">
                <div class="property-box-image">
                    <div class="property-group-label">
						<?php do_action( 'opalestate_before_property_loop_item' ); ?>
						<?php if ( $property->featured != 1 ): ?>
                            <div class="property-toggle-featured hide" data-id="property-toggle-featured-<?php echo get_the_ID(); ?>">
                                <span class="label-featured label label-success"><?php esc_html_e( 'Featured', 'opalestate' ); ?></span>
                            </div>
						<?php endif; ?>
                    </div>

					<?php opalestate_get_loop_thumbnail( opalestate_get_option( 'loop_image_size', 'large' ) ); ?>


                </div>

                <div class="property-meta">
                    <ul class="property-meta-list list-inline">
						<?php if ( $meta ) : ?>
							<?php foreach ( $meta as $key => $info ) : ?>
                                <li class="property-label-<?php echo $key; ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $info['label']; ?>"><i
                                            class="icon-property-<?php echo $key; ?>"></i><span class="label-property"><?php echo $info['label']; ?></span> <span
                                            class="label-content"><?php echo apply_filters( 'opalestate' . $key . '_unit_format', trim( $info['value'] ) ); ?></span></li>
							<?php endforeach; ?>
						<?php endif; ?>
                    </ul>
                </div>
            </header>
            <div class="abs-col-item col-lg-8 col-sm-8">
                <div class="entry-content pull-left">
					<?php echo $property->render_statuses(); ?>



					<?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>

                    <div class="property-address">
						<?php echo $property->get_address(); ?>
                    </div>

                    <div class="property-price">
                        <span><?php echo opalestate_price_format( $property->get_price() ); ?></span>

						<?php if ( $property->get_sale_price() ): ?>
                            <span class="property-saleprice">
									<?php echo opalestate_price_format( $property->get_sale_price() ); ?>
								</span>
						<?php endif; ?>

						<?php if ( $property->get_price_label() ): ?>
                            <span class="property-price-label">
									/ <?php echo $property->get_price_label(); ?>
								</span>
						<?php endif; ?>
                    </div>

					<?php do_shortcode( '[opalestate_favorite_button property_id=' . get_the_ID() . ']' ); ?>

                    <div class="my-properties-bottom">
                        <span class="label-post-status label <?php if ( $post->post_status == 'pending' ): ?> label-danger <?php else : ?> label-info <?php endif; ?>">
                            <?php echo esc_html( isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status ); ?>
                        </span>
                    </div>

                </div><!-- .entry-content -->
                <div class="pull-right">
					<?php if ( $property->featured != 1 ): ?>
                        <a href="#" class="btn btn-warning btn-toggle-featured" data-property-id="<?php echo get_the_ID() ?>" data-toggle="tooltip" data-placement="top"
                           title="<?php _e( 'Set Featured', 'opalestate' ) ?>"><i class="fa fa-star"></i></a>
					<?php endif; ?>

                    <a href="<?php echo opalestate_submssion_page( get_the_ID() ) ?>" class="btn btn-info" data-toggle="tooltip" data-placement="top" title="<?php _e( 'Edit', 'opalestate' ); ?>"><i
                                class="fa fa-edit"></i></a>
					<?php
					/* Delete Post Link Bypassing Trash */
					if ( current_user_can( 'delete_posts' ) ) {
						$delete_post_link = get_delete_post_link( $post->ID, '', true );
						if ( ! empty( $delete_post_link ) ) {
							?>
                            <a onclick="return confirm('<?php esc_html_e( 'Are you sure you wish to delete?', 'opalestate' ); ?>')" href="<?php echo esc_url( $delete_post_link ); ?>"
                               class="btn btn-danger" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e( 'Delete Property', 'opalestate' ); ?>">
                                <i class="fa fa-close"></i>
                            </a>
							<?php
						}
					} ?>
                </div>
            </div>
        </div>

        <meta itemprop="url" content="<?php the_permalink(); ?>"/>
    </div>
</article><!-- #post-## -->
