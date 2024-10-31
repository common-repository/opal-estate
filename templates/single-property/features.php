<?php 
	global $property;
	$infos = $property->getMetaboxInfo();

	$types = $property->getTypes();
	$status = $property->get_status();

?>
<div class="box-info">
	<h3 class="box-heading"><?php _e( 'Property Information', 'opalestate' ); ?></h3>
	<div class="box-content">
		<ul class="list-info">
			<?php if(  $infos ): ?>
			
				<?php foreach( $infos as $key => $info ) : ?>
					<?php if( $info['value'] ) : ?>
						<li class="icon-<?php echo $key; ?>"><span><?php echo $info['label']; ?></span> <?php echo apply_filters( 'opalestate'.$key.'_unit_format',  trim($info['value']) ); ?></li>
					<?php endif; ?>	
				<?php endforeach; ?>
				
			<?php endif;  ?>
			<?php if (!empty( $types )): ?>
				<li class="icon-type">
					<span>
						<?php _e('Type', 'opalestate'); ?>
					</span>
					<?php foreach ($types as $type) : ?>
						<a href="<?php echo esc_url( get_term_link( $type ) ); ?>" title="<?php echo esc_attr( $type->name ); ?>">
							<?php echo $type->name; ?>
						</a>
					<?php endforeach; ?>
				</li>
			<?php endif; ?>
			<?php if (!empty( $status )): ?>
				<li class="icon-status">
					<span>
						<?php _e('Status', 'opalestate'); ?>
					</span>
					<?php foreach ($status as $type) : ?>
						<a href="<?php echo esc_url( get_term_link( $type ) ); ?>" title="<?php echo esc_attr( $type->name ); ?>">
							<?php echo $type->name; ?>
						</a>
					<?php endforeach; ?>
				</li>
			<?php endif; ?>
		</ul>	
	</div>	
</div>		