<div class="property-preview">
	<?php
	global $property;
	$galleries = $property->getGallery();

 	$image_size = opalestate_get_option( 'opalestate_thumbnail_size' );
	if ( !empty($galleries[0]) && isset( $galleries[0]) ):  
	?>

	<div class="owl-carousel-play" data-ride="carousel">
		<div class="owl-carousel-wrapper">
			<div id="sync1" class="owl-carousel" data-slide="1"  data-singleItem="true" data-navigation="true" data-pagination="false">

				<?php if ( has_post_thumbnail() ): ?>
					<?php the_post_thumbnail( opalestate_get_option('featured_image_size','full')  ); ?>
				<?php endif; ?>

				<?php if( isset($galleries[0]) && is_array($galleries[0]) ): ?>
					<?php foreach ($galleries[0] as $src): ?>
						<img src="<?php echo $src; ?>" alt="gallery">
					<?php endforeach; ?>
				<?php endif; ?>
				
			</div>

			<a class="opalestate-left carousel-control carousel-md radius-x" data-slide="prev" href="#">
				<span class="fa fa-angle-left"></span>
			</a>
			<a class="opalestate-right carousel-control carousel-md radius-x" data-slide="next" href="#">
				<span class="fa fa-angle-right"></span>
			</a>

		</div>

		<div class="owl-thumb-wrapper">
			<div id="sync2" class="owl-carousel" data-items="5">
			  	<?php if ( has_post_thumbnail() ): ?>
					<img src="<?php echo  wp_get_attachment_thumb_url( get_post_thumbnail_id(), $image_size ); ?>" alt="gallery"> 
				<?php endif; ?>

				<?php if( isset($galleries[0]) && is_array($galleries[0]) ): ?>
					<?php foreach ($galleries[0] as $key => $src): ?>
						<img src="<?php echo  wp_get_attachment_thumb_url( $key, $image_size ); ?>" alt="gallery">
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>		
	 	
	</div>
	<?php else : ?>
	
	<?php if ( has_post_thumbnail() ): ?>
		<div class="property-thumbnail">
			<?php the_post_thumbnail( 'full' ); ?>
		</div>	
	<?php endif; ?>

	<?php endif; ?>

</div>
