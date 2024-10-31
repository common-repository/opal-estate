<?php if( class_exists("OpalEstate_Search") ) :  ?>
<div class="opalestate-search-properties">
	<div class="inner">
		<div class="search-properies-form">
			<?php OpalEstate_Search::render_horizontal_form( $atts ); ?> 
		</div>
	</div>
</div>	
<?php endif; ?>