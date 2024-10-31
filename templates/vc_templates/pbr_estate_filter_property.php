<?php
$show_description = true;
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$property_category   = explode( ', ',  $property_category );
$property_category = array_filter($property_category);
$opalestate_location = explode( ', ',  $opalestate_location );
$opalestate_location = array_filter($opalestate_location);
$opalestate_types    = explode( ', ',  $opalestate_types );
$opalestate_types    = array_filter($opalestate_types);

$opalestate_status    = explode( ', ',  $opalestate_status );
$opalestate_status    = array_filter($opalestate_status);

$relation = 'AND';

if( isset($type_filter) && $type_filter == 'OR' ){
    $relation = $type_filter;
}

if( $limit < $column){
    $limit = $column;
}

if( class_exists("Opalestate_Query") ):

    if(is_front_page()){
        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
    }
    else{
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
    }
    $args =  array("posts_per_page"=>$limit, 'paged' => $paged);

    $args['tax_query'] = array(
        'relation' => $relation
    );

    if ( !empty($property_category) ) {
        array_push($args['tax_query'], array(
            'taxonomy' => 'property_category',
            'terms' => $property_category,
            'field' => 'slug',
        ));
    }

    if( !empty( $opalestate_location ) ) {
        array_push($args['tax_query'], array(
            'taxonomy' => 'opalestate_location',
            'terms' =>  ($opalestate_location),
            'field' => 'slug',
        ));
    }

    if ( !empty($opalestate_types) ) {
        array_push($args['tax_query'], array(
            'taxonomy' => 'opalestate_types',
            'terms' =>  ($opalestate_types),
            'field' => 'slug',
        ));
    }

    if ( !empty($opalestate_status) ) {
        array_push($args['tax_query'], array(
            'taxonomy' => 'opalestate_status',
            'terms' =>  ($opalestate_status),
            'field' => 'slug',
        ));
    }

    $query = Opalestate_Query::get_property_query( $args );

$colclass = floor(12/$column);  
?>
<div class="widget widget-estate-property">
    <?php if(!empty($title)){ ?>
        <h4 class="widget-title text-center">
            <span><?php echo trim($title); ?></span>
            <?php if( !empty($description) && $show_description){ ?>
                <span class="widget-desc">
                    <?php echo trim($description); ?>
                </span>
            <?php } ?>
        </h4>
    <?php } ?>
    
    <div class="widget-content">
        <?php if( isset($showsortby) && $showsortby ): ?>
            <?php echo Opalestate_Template_Loader::get_template_part( 'collection-navigator', array('mode'=>'list' ) ); ?>
        <?php endif; ?>
        <div class="opalesate-recent-property opalestate-rows">
            <?php if( $query->have_posts() ): ?> 
                <div class="row">
                    <?php $cnt=0; while ( $query->have_posts() ) : $query->the_post(); 
                        $cls = '';
                        if( $cnt++%$column==0 ){
                            $cls .= ' first-child';
                        }
                        $display = opalestate_get_display_mode();
                    ?>
                    <?php if ( $display == 'list' && $showsortby ): ?>
                        <?php if( isset($description) && $description ): ?>
                            <div class="col-md-12 none-description">
                                <?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list' ); ?> 
                            </div>
                        <?php else: ?>
                            <div class="col-md-12">
                                <?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list' ); ?> 
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="col-lg-<?php echo esc_attr($colclass); ?> col-md-<?php echo esc_attr($colclass); ?> col-sm-<?php echo esc_attr($colclass); ?> <?php echo esc_attr($cls); ?>">
                            <?php echo Opalestate_Template_Loader::get_template_part( 'content-property-grid' ); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php   endwhile; ?>
                </div>
                <?php if( isset($pagination) && $pagination ): ?>
                    <div class="w-pagination"><?php opalestate_pagination(  $query->max_num_pages ); ?></div>
                <?php endif; ?> 
            <?php else: ?>
                <?php get_template_part( 'content', 'none' ); ?>
            <?php endif; ?> 
        </div>
    </div>  
</div>  
<?php endif; ?>
<?php wp_reset_query(); ?>