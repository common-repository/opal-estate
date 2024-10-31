<?php
$fields    = OpalEstate_Search::get_setting_search_fields();
$slocation = isset( $_GET['location'] ) ? $_GET['location'] : opalestate_get_session_location_val();
$stypes    = isset( $_GET['types'] ) ? $_GET['types'] : -1;
$sstatus   = isset( $_GET['status'] ) ? $_GET['status'] : -1;

$search_min_price = isset( $_GET['min_price'] ) ? $_GET['min_price'] : opalestate_options( 'search_min_price', 0 );
$search_max_price = isset( $_GET['max_price'] ) ? $_GET['max_price'] : opalestate_options( 'search_max_price', 10000000 );


$showareasize = opalestate_options( OPALESTATE_PROPERTY_PREFIX . 'areasize_opt', 1 );
$showprice    = opalestate_options( OPALESTATE_PROPERTY_PREFIX . 'price_opt', 1 );

?>
<form id="opalestate-search-form" class="opalestate-search-form opalestate-search-form--h opalestate-rows" action="<?php echo opalestate_get_search_link(); ?>" method="get">
    <div class="<?php echo apply_filters( 'opalestate_row_container_class', 'row opal-row' ); ?>">
        <div class="col-lg-3 col-md-3 col-sm-3">
            <h3><?php echo isset( $title ) ? $title : esc_html__( 'Quick Search', 'opalestate' ); ?></h3>
        </div>
        <div class="col-lg-9 col-md-9 col-sm-9">
			<?php
			$statuses = Opalestate_Taxonomy_Status::getList();
			if ( $statuses ):
				?>
                <ul class="list-inline clearfix list-property-status">
                    <li class="status-item  <?php if ( $sstatus == -1 ): ?> active<?php endif; ?>" data-id="-1">
                        <span><?php esc_html_e( 'All', 'opalestate' ); ?></span>
                    </li>
					<?php foreach ( $statuses as $status ): ?>

                        <li class="status-item <?php if ( $sstatus == $status->slug ): ?> active<?php endif; ?>" data-id="<?php echo $status->slug; ?>">
                            <span><?php echo $status->name; ?> </span>
                        </li>
					<?php endforeach; ?>
                </ul>
                <input type="hidden" value="<?php echo $sstatus; ?>" name="status"/>
			<?php endif; ?>
        </div>
    </div>
    <div class="<?php echo apply_filters( 'opalestate_row_container_class', 'row opal-row' ); ?>">
        <div class="col-lg-10 col-md-10 col-sm-10">
            <div class="<?php echo apply_filters( 'opalestate_row_container_class', 'row opal-row' ); ?>">
                <div class="col-lg-3 col-md-3 col-sm-3 search-text-form-field">
                    <label><?php esc_html_e( 'Keyword', 'opalestate' ); ?></label>
                    <input class="form-control" name="search_text">
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4">
                    <label><?php esc_html_e( 'Location', 'opalestate' ); ?></label>
					<?php Opalestate_Taxonomy_Location::dropdownList( $slocation ); ?>
                </div>
                <div class="col-lg-8 col-md-8 col-sm-8">
                    <div class="<?php echo apply_filters( 'opalestate_row_container_class', 'row opal-row' ); ?>">
						<?php if ( $fields ): ?>
							<?php foreach ( $fields as $key => $label ): ?>
								<?php if ( $key == "areasize" ) : continue; endif; ?>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <label><?php echo $label; ?></label>
									<?php opalestate_property_render_field_template( $key, __( "Any", 'opalestate' ) ); ?>
                                </div>
							<?php endforeach; ?>
						<?php endif; ?>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4">
                    <label><?php esc_html_e( "Type", 'opalestate' ); ?></label>
					<?php Opalestate_Taxonomy_Type::dropdownList( $stypes ); ?>
                </div>
				<?php if ( $showprice ): ?>
                    <div class="col-lg-4 col-md-4 col-sm-4">
						<?php

						$data = [
							'id'            => 'price',
							'decimals'      => opalestate_get_price_decimals(),
							'unit'          => opalestate_currency_symbol() . ' ',
							'ranger_min'    => opalestate_options( 'search_min_price', 0 ),
							'ranger_max'    => opalestate_options( 'search_max_price', 10000000 ),
							'input_min'     => $search_min_price,
							'input_max'     => $search_max_price,
							'unit_thousand' => opalestate_options( 'thousands_separator', ',' ),
						];

						if ( opalestate_options( 'currency_position', 'before' ) == 'before' ) {
							$data['unit_position'] = 'prefix';
						}

						opalesate_property_slide_ranger_template( __( 'Price:', 'opalestate' ), $data );
						?>
                    </div>
				<?php endif; ?>
				<?php if ( $showareasize ): ?>
                    <div class="col-lg-4 col-md-4 col-sm-4">
						<?php opalestate_property_areasize_field_template(); ?>
                    </div>
				<?php endif; ?>
            </div>
        </div>
        <div class="col-lg-2 col-md-2  col-sm-2">
            <button type="submit" class="btn btn-primary btn-lg btn-search">
				<?php esc_html_e( 'Search', 'opalestate' ); ?>
            </button>
        </div>
    </div>

	<?php do_action( 'opalestate_after_search_properties_form' ); ?>
</form>
