<?php 

	$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
	extract( $atts );

?>
<?php if( isset($description) ): ?>
	<div class="search-agent-form-description"><?php echo $description; ?></div>
<?php endif; ?>	
<?php if( class_exists("Opalestate_Template_Loader") ) :  ?>
 

 
<?php endif; ?>
 
<div class="opalestate-search-tabs">
	<ul class="nav nav-tabs tab-v8" role="tablist">
		<li class="active">
			<a aria-expanded="false" href="#search-agent" role="tab" data-toggle="tab">
				<span><?php _e( 'Find An Agent', 'opalestate'  ); ?></span>
			</a>
		</li>
		<li>
			<a aria-expanded="true" href="#search-office"   role="tab" data-toggle="tab">
				<span><?php _e('Find An Office','opalestate'); ?></span>
			</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade out active in" id="search-agent">
			<?php echo Opalestate_Template_Loader::get_template_part( 'parts/search-agents-form' ); ?>
		</div>
		<div class="tab-pane fade out" id="search-office">
			<?php echo Opalestate_Template_Loader::get_template_part( 'parts/search-office-form' ); ?>
		</div>		
	</div>	

</div>

