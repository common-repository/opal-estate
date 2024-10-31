<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $property, $post;
$fomart = $property->get_format_price();
?>
<div class="property-price">
	<?php _e('Price:', 'opalestate'); ?> <?php echo $fomart . $property->get_price(); ?>
</div>
<div class="property-saleprice">
	<?php _e('Sale Price:', 'opalestate'); ?> <?php echo $fomart . $property->get_sale_price(); ?>
</div>