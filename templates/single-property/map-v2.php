<?php
global $property;
$maps = $property->getMap();

if ( !empty($maps) ):
?>
<div class="property-map-section">
	
	<div class="box-content v2">
        <div class="google-map-tabs">
            <div class="clearfix">
                <ul class="nav nav-tabs pull-right" role="tablist">
                    <li class="active">
                        <a aria-expanded="true" href="#tab-picture" role="tab" data-toggle="tab"><i class="fa fa-picture-o"></i> <span><?php _e( 'Picture', 'opalestate' ); ?></span></a>
                    </li>
                    <li >
                        <a aria-expanded="false" href="#tab-google-map" role="tab" data-toggle="tab"><i class="fa fa-map"></i> <span><?php _e( 'Map', 'opalestate' ); ?></span></a>
                    </li>
                    <?php if( $property->enable_google_mapview() ) : ?>
                    <li >
                        <a aria-expanded="true" href="#property-street-view-map" class="tab-google-street-view-btn" role="tab" data-toggle="tab"><i class="fa fa-street-view"></i> <span><?php _e( 'Street View', 'opalestate' ); ?></span></a>
                    </li>
                     <?php endif; ?>
                </ul>
            </div>                    

            <div class="tab-content">
                <div class="tab-pane fade active in" id="tab-picture">
                    <?php
                        /**
                         * opalestate_before_single_property_summary hook
                         *
                         * @hooked opalestate_show_product_sale_flash - 10
                         * @hooked opalestate_show_product_images - 20
                         */
                        do_action( 'opalestate_single_property_preview' ); 
                    ?>
                </div>
                <div class="tab-pane fade" id="tab-google-map">
                   <div class="property-map-section">
                        <div id="property-map" style="height:400px" data-latitude="<?php echo (isset($maps['latitude']) ? $maps['latitude'] : ''); ?>" data-longitude="<?php echo (isset($maps['longitude']) ? $maps['longitude'] : ''); ?>" data-icon="<?php echo esc_url(OPALESTATE_CLUSTER_ICON_URL);?>"></div>
                        <div id="property-search-places">
                            <div class="place-buttons">
                                
                                <div class="nearby-container">
                                    <div class="btn-map-search" data-group="hospital" data-type="hospital" data-icon="hospital.png">
                                        <i class="fa fa-hospital-o" aria-hidden="true"></i>
                                        <span><?php _e( 'Hospital', 'opalestate' ); ?></span>
                                    </div>
                                </div>
                                <div class="nearby-container">
                                    <div class="btn-map-search" data-group="library" data-type="library" data-icon="libraries.png">
                                        <i class="fa fa-bank" aria-hidden="true"></i>
                                        <span><?php _e( 'Library', 'opalestate' ); ?></span>
                                    </div>
                                </div>
                                <div class="nearby-container">    
                                    <div class="btn-map-search" data-group="pharmacy" data-type="pharmacy" data-icon="pharmacy.png">
                                        <i class="fa fa-plus-square" aria-hidden="true"></i>
                                        <span><?php _e( 'Pharmacy', 'opalestate' ); ?></span>
                                    </div>
                               </div>
                                <div class="nearby-container">    
                                 
                                    <div class="btn-map-search" data-group="school" data-type="school, university" data-icon="school.png">
                                        <i class="fa fa-graduation-cap" aria-hidden="true"></i>
                                        <span><?php _e( 'School', 'opalestate' ); ?></span>
                                    </div>    
                                </div>

                                <div class="nearby-container">
                                    <div class="btn-map-search" data-group="shopping" data-type="grocery_or_supermarket, shopping_mall" data-icon="supermarket.png">
                                        <i class="fa fa-shopping-basket" aria-hidden="true"></i>
                                        <span><?php _e( 'Shopping', 'opalestate' ); ?></span>
                                    </div>    
                                </div>
                                <div class="nearby-container">    
                                    <div class="btn-map-search" data-group="trainstation" data-type="bus_station', subway_station, train_station, airport" data-icon="transportation.png">
                                        <i class="fa fa-subway" aria-hidden="true"></i>
                                        <span><?php _e( 'Trainstation', 'opalestate' ); ?></span>
                                    </div>    
                                </div> 
                            </div>    
                        </div>    

                  
                    </div>

                </div>
                <?php if( $property->enable_google_mapview() ) : ?>
                <div class="tab-pane fade" id="property-street-view-map" style="height:500px;" >
                </div>  
               
                <?php endif; ?>
            </div>
          

        </div>
    </div>
</div>

 
<?php endif;?>
