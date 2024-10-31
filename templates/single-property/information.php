<?php
global $property;
$infos = $property->get_meta_fullinfo();

$taxs = $property->get_types_tax();
if ( ! $infos && ! $taxs ) {
	return;
}
?>
<div class="property-information opalestate-box">
    <h3 class="box-heading"><?php _e( 'Property Information', 'opalestate' ); ?></h3>
    <div class="box-content">
        <ul class="list-info">
			<?php if ( $taxs ): ?>
                <li class="property-label-type">
                    <span><?php _e( 'Type:', 'opalestate' ); ?></span>
					<?php foreach ( $taxs as $tax ): ?>
                        <a href="<?php echo get_term_link( $tax->term_id ); ?>"><?php echo $tax->name; ?></a>
					<?php endforeach; ?>
                </li>
			<?php endif; ?>
			<?php if ( $infos ): ?>
				<?php foreach ( $infos as $key => $info ) : ?>
					<?php if ( $info['value'] ) : ?>
                        <li class="property-label-<?php echo $key; ?>"><span><?php echo $info['label']; ?> :</span> <?php echo apply_filters( 'opalestate_' . $key . '_unit_format',
								trim( $info['value'] ) ); ?></li>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
        </ul>
    </div>
</div>
