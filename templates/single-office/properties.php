<?php
global $post;

$office_id = get_the_ID(); 
$limit 	   = apply_filters( 'opalesate_office_properties_limit', 5 ); 
$user_id   = get_post_meta( $office_id, OPALESTATE_OFFICE_PREFIX . 'user_id', true ); 
$query 	   = Opalestate_Query::get_office_property( $office_id, $user_id , $limit );

 
if( $query->have_posts() ) :
	$id = rand(); 
?>
<div class="clearfix clear"></div>
<div class="opalestate-box property-office-section">
	<h3><?php echo sprintf( __('My Properties', 'opalestate'),  $query->found_posts  );?></h3>
	<div class="opalestate-rows">
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>" id="<?php echo $id; ?>">
			<?php while( $query->have_posts() ) : $query->the_post(); ?>
			  	<div class="col-lg-12 col-md-12 col-sm-12">
			  	 <?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list-v2' ); ?>
			  	</div> 
			<?php endwhile; ?>	
		</div>
		<?php if( $query->max_num_pages > 1 ):   ?>
		<div class="w-pagination"><?php // echo  $query->max_num_pages; // opalestate_pagination(  $query->max_num_pages ); ?></div>
		<div class="opalestate-load-more text-center" data-post_id="<?php echo  $office_id; ?>" data-action="get_office_property" data-related="<?php echo $id; ?>" data-numpage="<?php echo $query->max_num_pages; ?>" data-paged="2"> <button class="btn btn-primary btn-3d"> <?php _e('Load More', 'opalestate'); ?></button></div>
		<?php endif; ?>

	</div>	
</div>	
<?php else : ?>
<div class="opalestate-message">
<?php _e( 'My Office has not any property yet.', 'opalestate' ) ;?>
</div>
<?php endif;  ?>

<?php 	wp_reset_postdata(); ?>
