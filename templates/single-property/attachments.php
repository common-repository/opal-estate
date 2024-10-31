<?php 
	global $property; 
 	$attachments = $property->get_attachments(); 
?>
<?php if( $attachments ) : ?>
<div class="list-group property-attachments opalestate-box">
	<h3 class="list-group-item-heading"><?php _e("Attachments", "opalestate"); ?></h3>
	<div class="list-group-item-text">
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<?php foreach( $attachments as $id => $attachment ):  
				$attachment_title = get_the_title($id)
			?>
				<div class="col-lg-3 col-md-3">
					<i class="text-primary fa fa-file-text-o"></i>  
					<strong><a href="<?php echo esc_url($attachment); ?>" target="_blank"><?php echo $attachment_title; ?></a></strong>
				</div>
			<?php endforeach; ?>
		</div>	
	</div>
</div>
<?php endif; ?>