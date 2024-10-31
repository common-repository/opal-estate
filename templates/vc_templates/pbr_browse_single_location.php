<?php
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );

$def_atts = array(
    'title' => '',
    'description' => '',
    'opalestate_location' => '',
    'image_size' => 'large'
);

$atts = array_merge($def_atts, $atts);

extract( $atts );

if ( $opalestate_location ) {
    $location = get_term_by( 'slug', $opalestate_location , 'opalestate_location' );
} else {
    $location = false;
}

?>
<div class="widget widget-estate-browse-single-location">
    <?php if(!empty($title)) : ?>
        <h4 class="widget-title text-center">
            <span><?php echo trim($title); ?></span>
            <?php if(trim($description)!='') : ?>
                <span class="widget-desc">
	                <?php echo trim($description); ?>
	            </span>
            <?php endif; ?>
        </h4>
    <?php endif; ?>

    <div class="widget-content">

        <?php if ( $location ) :  ?>
            <div class="opaleatate-browse-single-location">
                <?php
                    $tax_link = get_term_link( $location->term_id);
                    $image = wp_get_attachment_image_src( get_term_meta( $location->term_id, 'opalestate_location_image_id', true ), $image_size );
                    ?>

                    <div class="property-category" >
                        <?php if ( isset($image[0]) && $image[0]) : ?>
                        <div class="property-category-image">
                            <a href="<?php echo $tax_link ?>"><img src="<?php echo $image[0] ?>" alt="<?php echo $location->name ?>-image" /></a>
                        </div>
                        <?php endif; ?>
                        <div class="static-content">
                            <h5><a href="<?php echo $tax_link ?>"><?php echo $location->name ?></a></h5>

                            <div class="property-category-count"><?php echo $location->count . ' ' . __('properties', 'opalestate') ?></div>
                        </div>
                    </div>
            </div>
        <?php endif; ?>

    </div>
</div>

