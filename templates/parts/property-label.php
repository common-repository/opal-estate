<?php
	global $property;
	$labels = $property->get_labels();


?>
<?php if( !is_wp_error($labels) && $labels ) { ?>
   	<?php foreach( $labels as $label ):
        $image = get_term_meta( $label->term_id, 'opalestate_label_lb_img', true );
    ?>
   	<?php if( empty($image) ): 
        $bg    = get_term_meta( $label->term_id, 'opalestate_label_lb_bg' , true );
        $color = get_term_meta( $label->term_id, 'opalestate_label_lb_color', true );
    ?>
   	<div class="property-label label label-danger" style="background-color:<?php echo ($bg)?$bg:'inhertit'?>;color:<?php echo ($color)?$color:'inhertit'?>"><?php echo $label->name; ?></div>
    <?php else : ?>
    <div class="property-label label-image">
    	<img src="<?php echo $image;?>" alt="<?php echo $label->name; ?>">
    </div>
	<?php endif; ?>
    <?php endforeach; ?>
<?php } ?>
