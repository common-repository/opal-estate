<?php global $property, $post; 
 
	$email = get_post_meta(  get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'email', true );
// 	echo '<Pre>'.print_r( $property ,1 );die; 
	$args = array( 'post_id' => get_the_ID(), 'email' => $email  );

	$maps     = get_post_meta(  get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'map', true );
	$address  = get_post_meta(  get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'address', true );
	
	$rowcls = apply_filters('opalestate_row_container_class', 'row opal-row'); 
?> 
<?php $office = OpalEstate_Office::getInstance(); ?>
<article id="post-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/RealEstateOffice" <?php post_class(); ?>>
	
	<?php if( $office->is_featured() ): ?>
	<span class="property-label" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Featured Office', 'opalestate'); ?>">
		<i class="fa fa-star"></i>
	</span>
	<?php endif; ?>
								
	<div class="<?php echo $rowcls;?>">

		<div class="col-lg-9 col-md-9 col-sm-12">
			<header>
				<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
			</header>  
			
			<div class="office-box">
				<div class="<?php echo $rowcls;?>">
					<div class="col-lg-8 col-md-8 col-sm-8 office-thumb">
						
						<?php if ( has_post_thumbnail() ) : ?>
						        <a href="<?php the_permalink(); ?>" class="office-box-image-inner <?php if ( ! empty( $office ) ) : ?>has-office<?php endif; ?>">
					                <?php the_post_thumbnail( opalestate_get_option('loop_image_size', 'office-thumbnail')  ); ?>
						        </a>
						        <?php $office->render_level(); ?>
						        
					    <?php endif; ?>
					</div>
					<div class="col-lg-4 col-md-4 col-sm-4"> 
						<?php echo Opalestate_Template_Loader::get_template_part( 'single-office/author-box' ); ?> 
					</div>
				</div>
			</div> 	
			<div class="entry-content">
				<h4 class="box-heading"><?php _e( 'Seller\'s Notes', 'opalestate' ) ?></h4>
				<?php
					/* translators: %s: Name of current post */
					the_content( sprintf(
						__( 'Continue reading %s', 'opalestate' ),
						the_title( '<span class="screen-reader-text">', '</span>', false )
					) );

					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'opalestate' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					) );
				?>
			</div><!-- .entry-content -->
			<div class="content-bottom">
				<?php do_action( 'opalestate_single_office_content_bottom' ); ?>	
			</div>
			 <?php echo Opalestate_Template_Loader::get_template_part( 'single-office/tabs' ); ?> 
			<meta itemprop="url" content="<?php the_permalink(); ?>" />
		</div>
		<div class="col-lg-3 col-md-3 col-sm-12">
			<?php if( isset($maps) ): ?>
			<div class="opalestate-box office-address-map">
				<h3><?php _e( 'Office Address' , 'opalestate' ); ?></h3>
			 	<div class="office-google-map-content">
			 		<?php if( $address ): ?>
			 		<p>
			 			<i class="fa fa-map-marker"></i> <span><?php _e('Head Office:','opalestate'); ?></span> <br> <?php echo $address; ?>. 
			 			<?php 
			 				$terms = wp_get_post_terms( get_the_ID(), 'opalestate_office_location' );
							if( $terms && !is_wp_error($terms) ){
								
								echo '<strong>'.__('Location:','opalestate').'</strong>';

								$output = '<span class="property-locations">';
								foreach( $terms as $term  ){
									$output .= $term->name;
								}
								$output .= '</span>';
								echo $output;
							}

			 			?>
			 		</p>

			 		<?php endif; ?>
			 		<div id="property-map" style="height:400px" data-latitude="<?php echo (isset($maps['latitude']) ? $maps['latitude'] : ''); ?>" data-longitude="<?php echo (isset($maps['longitude']) ? $maps['longitude'] : ''); ?>" data-icon="<?php echo esc_url(OPALESTATE_CLUSTER_ICON_URL);?>"></div>
			 	</div>	
			</div>	 	
			<?php endif ?>
			<?php 

				$email 		 = get_post_meta( get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'email', true ); 
				$args   = array( 
						'post_id' 	=> get_the_ID(),
						'id' 		=> get_the_ID(), 
						'email'   	=> $email,
						'message' 	=>sprintf( __( 'Hi %s. I saw your profile and wanted to see if you could help me.', 'opalestate') , get_the_title() ), 
						'type'		=> 'office',
				);			 
				$form 	  	 = apply_filters( 'opalestate_render_contact_form', Opalestate_Template_Loader::get_template_part( 'parts/contact-form', $args ), $args );
				echo $form; 
			?>
		</div>	
	</div>	
</article><!-- #post-## -->

<div class="opalestate-box office-customer-review hide">
	<h3><?php _e( 'Customer Review', 'opalestate'  ); ?></h3>
	<?php
							
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
						
	?>	
</div>	
<?php do_action( 'opalestate_single_content_office_after' ); ?>