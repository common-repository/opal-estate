<?php
$office = new OpalEstate_Office();
 
 $office_id = get_the_ID();  
 $address   = get_post_meta(  get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'address', true );
$job='';?>
<article <?php post_class( 'office-grid-style'); ?>>
	<div class="office-inner">
	
	    <div class="office-header">
	        <?php opalestate_get_loop_agent_thumbnail(); ?>
	 
	        <?php if( $office->is_featured() ): ?>
			<span class="text-label property-label" data-toggle="tooltip" data-placement="top" title="<?php esc_html_e('Featured Office', 'opalestate'); ?>">
				<i class="fa fa-star"></i>
			</span>
			<?php endif; ?>
	    </div>     
	   
        <div class="office-body-content clearfix">
    		<div class="office-logo">
    			<?php echo $office->get_link( $office_id ); ?>
    		</div>
    		<div class="office-content">
    			<h5 class="office-box-title text-uppercase">
		            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
		        </h5><!-- /.office-box-title -->
	            <h3 class="office-name hide"><?php the_title(); ?></h3>
	            <p><?php echo esc_html($address); ?></p>
    		</div>
        </div>      

         <div class="office-box-meta">
	      	
			<?php $email = get_post_meta( get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'email', true ); ?>
			<?php if ( ! empty( $email ) ) : ?>
				<div class="office-box-email ">
				    <a href="mailto:<?php echo esc_attr( $email ); ?>">
				       <i class="fa fa-envelope"></i> <span><?php echo esc_attr( $email ); ?></span>
				    </a>
				</div><!-- /.office-box-email -->
			<?php endif; ?>
			<?php $phone = get_post_meta( get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'phone', true ); ?>
			<?php if ( ! empty( $phone ) ) : ?>
				<div class="office-box-phone">
				   <i class="fa fa-phone"></i><span><a href="tel:<?php echo esc_attr( $phone ); ?>"><?php echo esc_attr( $phone ); ?></a></span>
				</div><!-- /.office-box-phone -->
			<?php endif; ?>
		   
	    </div><!-- /.office-box-content -->                     
	                                 
	</div>

</article>	