<?php 
	global $property, $post; 
 	
	$amenities =  $property->get_amenities();	

 	$facilities = $property->get_facilities();

 	// echo '<pre>'.print_r( $facilities , 1);
?>
<?php if( $amenities  ): ?>
<div class="list-group property-amenities opalestate-box">
	<h3 class="list-group-item-heading"><?php _e("Amenities", "opalestate"); ?></h3>
	<div class="list-group-item-text">
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<?php foreach( $amenities as $amenity ):  ?>
				<div class="col-lg-4 col-md-4 <?php if(  has_term( $amenity->term_id, 'opalestate_amenities', $post )  ) : ?>active<?php endif; ?>">
					<i class="fa fa-check"></i> <?php echo $amenity->name; ?>
				</div>
			<?php endforeach; ?>
		</div>	
	</div>
</div>
<?php endif; ?>

<?php if( $facilities && isset($facilities[0]) && !empty($facilities[0]) ): ?>
	
	<div class="list-group property-amenities opalestate-box">
		<h3 class="list-group-item-heading"><?php _e("Facilities", "opalestate"); ?></h3>
		<div class="list-group-item-text">
			<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
				<?php foreach( $facilities as $facility ):  ?>
					<div class="col-lg-6 col-md-6 active">
					
						<span><i class="fa fa-check"></i>  <?php echo $facility[OPALESTATE_PROPERTY_PREFIX.'public_facilities_key']; ?> : </span>
						
						<strong><?php echo $facility[OPALESTATE_PROPERTY_PREFIX.'public_facilities_value']; ?></strong>
					</div>
				<?php endforeach; ?>
			</div>	
		</div>
	</div>

<?php endif; ?>