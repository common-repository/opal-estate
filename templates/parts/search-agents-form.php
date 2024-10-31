<?php
	$fields = OpalEstate_Search::get_setting_search_fields(); 
	$slocation  = isset($_GET['location'])?$_GET['location']: opalestate_get_session_location_val();  
	$stypes 	= isset($_GET['types'])?$_GET['types']:-1;
	$sstatus 	= isset($_GET['status'])?$_GET['status']:-1;

	$search_min_price = isset($_GET['min_price']) ? $_GET['min_price'] :  opalestate_options( 'search_agent_min_price',0 );
	$search_max_price = isset($_GET['max_price']) ? $_GET['max_price'] : opalestate_options( 'search_agent_max_price',10000000 );

?>
<form id="opalestate-search-agents-form" class="opalestate-search-agents-form" action="<?php echo opalestate_search_agent_uri(); ?>" method="get">
	
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
				<p class="search-agent-title"><?php esc_html_e( 'Find an experienced agent with:' ,'opalestate'); ?></p>
			</div>
			<div class="col-lg-6 col-md-6 hidden-sm hidden-xs">
				<p class="search-agent-title hide"><?php esc_html_e( 'Who sale between:' ,'opalestate'); ?></p>
			</div>
		</div>
		
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<div class="col-lg-3 col-md-3 col-sm-3">
				<?php Opalestate_Taxonomy_Location::dropdownList( $slocation );?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3">
				<?php  Opalestate_Taxonomy_Type::dropdownList( $stypes ); ?>
			</div>
 
			<div class="col-lg-4 col-md-4 col-sm-4">
				    <?php

				 	 	$data = array(
							'id' 	 => 'price',
							'unit'   => '$ ',
							'ranger_min' => opalestate_options( 'search_agent_min_price',0 ),
							'ranger_max' => opalestate_options( 'search_agent_max_price',10000000 ),
							'input_min'  => $search_min_price,
							'input_max'	 => $search_max_price
						);
						opalesate_property_slide_ranger_template( __("Price:",'opalestate'), $data );

					?>
			</div>

			<div class="col-lg-2 col-md-2 col-sm-2">
				<button type="submit" class="btn btn-primary btn-block btn-search btn-3d">
					<i class="fa fa-search"></i>
					<span><?php esc_html_e('Search','opalestate'); ?></span>
				</button>
			</div>
		</div>
</form>