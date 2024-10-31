<?php global $property, $post; 
 
	$email = get_post_meta(  get_the_ID(), OPALESTATE_AGENT_PREFIX . 'email', true );
// 	echo '<Pre>'.print_r( $property ,1 );die; 
	$args = array( 'post_id' => get_the_ID(), 'email' => $email  );

	$maps     = get_post_meta(  get_the_ID(), OPALESTATE_AGENT_PREFIX . 'map', true );
	$address  = get_post_meta(  get_the_ID(), OPALESTATE_AGENT_PREFIX . 'address', true );
	
?> 
<article id="post-<?php the_ID(); ?>" itemscope itemtype="http://schema.org/RealEstateAgent" <?php post_class(); ?>>
	
	<header class="hide">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
	</header>  
	
	<div class="agent-box">
		<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
			<div class="col-lg-6 col-md-6 col-sm-6">
				<?php echo Opalestate_Template_Loader::get_template_part( 'single-agent/author-box' ); ?> 
			</div>
			<div class="col-lg-6 col-md-6 col-sm-6"> <?php 

				$email 		 = get_post_meta( get_the_ID(), OPALESTATE_AGENT_PREFIX . 'email', true ); 
				$args   = array( 
						'post_id' 	=> get_the_ID(),
						'id' 		=> get_the_ID(), 
						'email'   	=> $email,
						'message' 	=> '', 
						'type'		=> 'agent',
				);
				$heading  	 = __( 'Contact Office', 'opalestate' );
			 
				$form 	  	 = apply_filters( 'opalestate_render_contact_form', Opalestate_Template_Loader::get_template_part( 'parts/contact-form', $args ), $args );
				echo $form; 
			?> </div>
		</div>
	</div> 	
	<div class="entry-content">
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
		<?php do_action( 'opalestate_single_agent_content_bottom' ); ?>	
	</div>

	<?php if( isset($maps) ): ?>
	<div class="opalestate-box agent-address-map">
		<h3><?php _e( 'My Address' , 'opalestate' ); ?></h3>
	 	<div class="agent-google-map-content">
	 		<?php if( $address ): ?>
	 		<p>
	 			<i class="fa fa-map-marker"></i> <strong><?php _e('Address:','opalestate'); ?></strong> <?php echo $address; ?>. 
	 			<?php 
	 				$terms = wp_get_post_terms( get_the_ID(), 'opalestate_agent_location' );
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
	 		<div id="agent-map" style="height:400px" data-latitude="<?php echo (isset($maps['latitude']) ? $maps['latitude'] : ''); ?>" data-longitude="<?php echo (isset($maps['longitude']) ? $maps['longitude'] : ''); ?>" data-icon="<?php echo esc_url(OPALESTATE_CLUSTER_ICON_URL);?>"></div>
	 	</div>	
	</div>	 	
	<?php endif ?>
	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</article><!-- #post-## -->

<div class="opalestate-box agent-customer-review hide">
	<h3><?php _e( 'Customer Review', 'opalestate'  ); ?></h3>
	<?php
							
	// If comments are open or we have at least one comment, load up the comment template.
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
						
	?>	
</div>	

<?php do_action( 'opalestate_single_content_agent_after' ); ?>