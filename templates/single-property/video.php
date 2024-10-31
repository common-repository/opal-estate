<?php
	global $property;

	$videoURL =  $property->getVideoURL();
?>
<?php if( $videoURL  ) : ?>
<div class="property-video-session opalestate-box">
	<h3><?php _e( 'Video', 'opalestate'  ); ?></h3>

	<div class="box-info">
		<?php echo wp_oembed_get( $videoURL ); ?>
	</div>
</div>
<?php endif; ?>