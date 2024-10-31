<?php 
/**
 *
 */
function opalestate_clean_attachments( $user_id ){
    
  
    $query = new WP_Query( 
        array( 
            'post_type'   => 'attachment', 
            'post_status' => 'inherit', 
            'author'      => $user_id , 
            'meta_query' => array(
                array(
                    'key' => '_pending_to_use_',
                     'value' => 1,
                     'compare' => '>=',
                )
            )    
        ) 
    );

    if( $query->have_posts() ){   
        while( $query->have_posts() ){ $query->the_post();
            wp_delete_attachment( get_the_ID() );
        }
    }
    wp_reset_postdata(); 
}

/// /
add_filter( 'pre_get_posts', 'opalestate_archives_office_query', 1 );
function opalestate_archives_office_query( $query ) {
    if( $query->is_main_query() && is_post_type_archive( 'opalestate_office' ) ){
        if( isset($_GET['location']) ){
            $tax_query = array();
           
            $tax_query[] = array(
                'taxonomy' => 'opalestate_location',
                'field'    => 'slug',
                'terms'    => $_GET['location'],
            );
            $args['tax_query'] = array('relation' => 'AND');
            $args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
            $query->set( 'tax_query' , $tax_query );
        }
        if( isset($_GET['search_text']) ){
            $query->set( 's' , $_GET['search_text'] );
        }
    }
    return $query;
}


/// /
add_filter( 'pre_get_posts', 'opalestate_archives_agent_query', 1 );
function opalestate_archives_agent_query( $query ) {
    if( $query->is_main_query() && is_post_type_archive( 'opalestate_agent' ) ){

        $args = array() ;

        $min = opalestate_options( 'search_agent_min_price',0 ); 
        $max = opalestate_options( 'search_agent_max_price',10000000 ); 
        

        $search_min_price = isset($_GET['min_price']) ? sanitize_text_field($_GET['min_price']) : '';
        $search_max_price = isset($_GET['max_price']) ? sanitize_text_field($_GET['max_price']) : '';

        $search_min_area = isset($_GET['min_area']) ? sanitize_text_field($_GET['min_area']) : '';
        $search_max_area = isset($_GET['max_area']) ? sanitize_text_field($_GET['max_area']) : '';
        $s = isset($_GET['search_text']) ? sanitize_text_field($_GET['search_text']) : null;


        $paged = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
        $default = array(
            'post_type'         => 'opalestate_agent',
            'posts_per_page'    => apply_filters('opalestate_agent_per_page' , 12 ),
            'paged'             => $paged,
        );
        $args = array_merge( $default, $args );

        $tax_query = array();

         
        if( isset( $_GET['location']) &&  $_GET['location'] !=-1 ){
            $tax_query[] = 
                array(
                    'taxonomy' => 'opalestate_location',
                    'field'    => 'slug',
                    'terms'    => $_GET['location'],
                );
        }
        
        if( isset( $_GET['types']) && $_GET['types'] !=-1 ){ 
            $tax_query[] =
                array(
                    'taxonomy' => 'opalestate_types',
                    'field'    => 'slug',
                    'terms'    => $_GET['types'],
                )
            ;
        }
     
        if( $tax_query  ){
            $args['tax_query'] = array('relation' => 'AND');
            $args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
        }

        $args['meta_query'] = array('relation' => 'AND');
         
        if($search_min_price != $min && is_numeric($search_min_price)) {
            array_push($args['meta_query'], array(
                'key'     => OPALESTATE_AGENT_PREFIX.'target_min_price',
                'value'   => $search_min_price,
                'compare' => '>=',
                'type' => 'NUMERIC'
            ));
        } 
        if( is_numeric($search_max_price) && $search_max_price != $max ) {
            array_push($args['meta_query'], array(
                'key'     => OPALESTATE_AGENT_PREFIX.'target_max_price',
                'value'   => $search_max_price,
                'compare' => '<=',
                'type' => 'NUMERIC'
            ));
        }

        if( isset($args['tax_query']) && $args['tax_query'] ){
            $query->set( 'tax_query', $args['tax_query'] );
        }
        if( isset($args['meta_query']) && $args['meta_query'] ){
            $query->set( 'meta_query', $args['meta_query'] );
        }

    }
    return $query;
}

/****/
add_filter( 'pre_get_posts', 'opalestate_archives_property_query', 1 );
function opalestate_archives_property_query( $query ){

    if( $query->is_main_query() && ( is_post_type_archive( 'opalestate_property' ) || is_tax('property_category') || is_tax('opalestate_amenities') || is_tax('opalestate_location') || is_tax('opalestate_types') ) ){

        $args = array();
        $ksearchs = array();
        if( isset($_REQUEST['opalsortable']) && !empty($_REQUEST['opalsortable']) ){
            $ksearchs = explode( "_", $_REQUEST['opalsortable'] );  
        } elseif( isset($_SESSION['opalsortable']) && !empty($_SESSION['opalsortable']) ){
            $ksearchs = explode( "_", $_SESSION['opalsortable'] );
        }
    
        if( !empty($ksearchs) && count($ksearchs) == 2 ){
            $args['meta_key'] = OPALESTATE_PROPERTY_PREFIX.$ksearchs[0];
            $args['orderby']  = 'meta_value_num';
            $args['order']    = $ksearchs[1];   
        }

        if( isset( $_GET['status']) &&  !empty($_GET['status']) && $_GET['status'] != 'all' ){
            $tax_query = array(
                array(
                    'taxonomy' => 'opalestate_status',
                    'field'    => 'slug',
                    'terms'    =>  $_GET['status'],
                ),
            );
            $args['tax_query'] = array('relation' => 'AND');
            $args['tax_query'] = array_merge( $args['tax_query'], $tax_query );
        }

        if( $args ){
            foreach( $args as $key => $value ){
                $query->set( $key, $value );
            }
        }

    }
}