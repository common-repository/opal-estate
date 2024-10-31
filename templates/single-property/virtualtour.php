<?php
global $property;

$virtualTour =  $property->getVirtualTour();
?>
<?php if( $virtualTour  ) : ?>
    <div class="property-360-virtual-session opalestate-box">
        <h3><?php _e( '360° Virtual Tour', 'opalestate'  ); ?></h3>

        <div class="box-info">
            <?php echo do_shortcode( $virtualTour ); ?>
        </div>
    </div>
<?php endif; ?>
