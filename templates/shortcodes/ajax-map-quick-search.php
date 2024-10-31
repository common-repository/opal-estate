<?php 
	$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	$rowcls =  apply_filters('opalestate_row_container_class', 'row opal-row'); 


	$slocation  = isset($_GET['location'])?$_GET['location']: opalestate_get_session_location_val();  
	$stypes 	= isset($_GET['types'])?$_GET['types']:-1;
	$sstatus 	= isset($_GET['status'])?$_GET['status']:-1;

	$search_min_price = isset($_GET['min_price']) ? $_GET['min_price'] :  opalestate_options( 'search_min_price',0 );
	$search_max_price = isset($_GET['max_price']) ? $_GET['max_price'] : opalestate_options( 'search_max_price',10000000 );
	

	$showareasize = opalestate_options(OPALESTATE_PROPERTY_PREFIX.'areasize_opt', 1 );
	$showprice 	  = opalestate_options(OPALESTATE_PROPERTY_PREFIX.'price_opt' , 1 );  


?>
<div class="ajax-map-search full-width"><div class="inner">
	<div class="ajax-search-form">
		<form id="opalestate-search-form" class="opalestate-search-form opalestate-rows" action="" method="get">
			<div class="<?php echo $rowcls;?>">
				<div class="col-lg-3 col-sm-3">
					<input class="form-control" name="search_text">
				</div>
				<div class="col-lg-2 col-sm-3">
					<?php Opalestate_Taxonomy_Location::dropdownList( $slocation );?>
				</div>
				<div class="col-lg-2">
					<?php  Opalestate_Taxonomy_Type::dropdownList( $stypes ); ?>
				</div>	
				<div class="col-lg-2">
					<button type="submit" class="btn btn-danger btn-sm btn-search">
						<?php _e('Search', 'opalestate' ); ?>
					</button>
				</div>
			</div>	
		</form>	
	</div>
	<hr>	
	<div class="<?php echo $rowcls;?>">
		
		<div class="col-lg-6 col-md-6">
			<div class="opalesate-properties-ajax">
				<?php echo Opalestate_Template_Loader::get_template_part( 'shortcodes/ajax-map-search-result' ); ?>
			</div>
		</div>	

		<div class="col-lg-6 col-md-6">
			<div id="opalestate-map-preview" style="height:500px;" data-page="<?php echo $paged; ?>">
				 <div id="mapView">
			        <div class="mapPlaceholder"><!-- <span class="fa fa-spin fa-spinner"></span> <?php //esc_html_e( 'Loading map...', 'opalestate' ); ?> -->
			        	<div class="sk-folding-cube">
							<div class="sk-cube1 sk-cube"></div>
						  	<div class="sk-cube2 sk-cube"></div>
						  	<div class="sk-cube4 sk-cube"></div>
						  	<div class="sk-cube3 sk-cube"></div>
						</div>
			        </div>
			    </div>
			</div>
		</div>	


	</div>	

</div></div>	