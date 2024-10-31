<?php
	$fields 		= OpalEstate_Search::get_setting_search_fields(); 
	$slocation  	= isset($_GET['location'])?$_GET['location']: opalestate_get_session_location_val();  
	$search_text 	= isset($_GET['search_text'])?$_GET['search_text']:'';



?>
<form id="opalestate-search-agents-form" class="opalestate-search-agents-form" action="<?php echo opalestate_search_office_uri(); ?>" method="get">
	
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
				<p class="search-agent-title"><?php esc_html_e( 'I am looking for Offices from' ,'opalestate'); ?></p>
			</div>
			<div class="col-lg-8 col-md-8 hidden-sm hidden-xs">
				<p class="search-agent-title hide"><?php esc_html_e( 'Offices Name' ,'opalestate'); ?></p>
			</div>
		</div>
		
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<div class="col-lg-4 col-md-4 col-sm-4">
				<?php Opalestate_Taxonomy_Location::dropdownList( $slocation );?>
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6">
				<input name="search_text" value="<?php echo esc_attr($search_text); ?>" maxlength="40" class="form-control input-large input-search" size="20" placeholder="<?php _e( 'Enter Office Name', 'opalestate' ); ?>" type="text">
			</div>
 
			<div class="col-lg-2 col-md-2 col-sm-2">
				<button type="submit" class="btn btn-primary btn-block btn-search btn-3d">
					<i class="fa fa-search"></i>
					<span><?php esc_html_e('Search','opalestate'); ?></span>
				</button>
			</div>
		</div>
</form>