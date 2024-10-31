<?php 

	$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
	extract( $atts );

?>
<?php if( $description ): ?>
	<div class="search-agent-form-description"><?php echo $description; ?></div>
<?php endif; ?>	
<?php if( class_exists("Opalestate_Template_Loader") ) :  ?>
 
	<?php echo Opalestate_Template_Loader::get_template_part( 'parts/search-office-form' ); ?>
 
<?php endif; ?>