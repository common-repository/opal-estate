<?php 
if(  $existed != false || $existed != '' ) {
    $fav_class = 'fa fa-heart';
} else {
    $fav_class = 'fa fa-heart-o';
}
if( !is_user_logged_in() ){
	$fav_class .= ' opalestate-need-login';
}
?>
<i class="property-toggle-favorite <?php echo esc_attr( $fav_class ); ?>" data-property-id="<?php echo intval( $property_id ); ?>" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Favorite', 'opalestate'); ?>"><span class="hide"><?php _e('Add Favorite', 'opalestate'); ?></span></i>