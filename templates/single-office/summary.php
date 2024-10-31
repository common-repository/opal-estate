<div class="property-office-contact">

	<div class="col-sm-3">
		<div class="property-office-contact">
			<div class="office-box">
			    <?php if ( has_post_thumbnail() ) : ?>
					<div class="office-box-image <?php if ( ! has_post_thumbnail() ) { echo 'without-image'; } ?>">
				        <a href="<?php the_permalink(); ?>" class="office-box-image-inner <?php if ( ! empty( $office ) ) : ?>has-office<?php endif; ?>">
			                <?php the_post_thumbnail( 'office-thumbnail' ); ?>
				        </a>
					</div><!-- /.office-box-image -->
			    <?php endif; ?>

			    <div class="office-box-meta">

			        <?php $email = get_post_meta( get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'email', true ); ?>
			        <?php if ( ! empty( $email ) ) : ?>
			            <div class="office-box-email">
				            <a href="mailto:<?php echo esc_attr( $email ); ?>">
			                   <i class="fa fa-email"></i> <?php echo esc_attr( $email ); ?>
				            </a>
			            </div><!-- /.office-box-email -->
			        <?php endif; ?>

			        <?php $phone = get_post_meta( get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'phone', true ); ?>
			        <?php if ( ! empty( $phone ) ) : ?>
			            <div class="office-box-phone">
			                <?php echo esc_attr( $phone ); ?>
			            </div><!-- /.office-box-phone -->
			        <?php endif; ?>

				    <?php $web = get_post_meta( get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'web', true ); ?>
				    <?php if ( ! empty( $web ) ) : ?>
					    <div class="office-box-web">
						    <a href="<?php echo esc_attr( $web ); ?>">
						        <?php echo esc_attr( $web ); ?>
						    </a>
					    </div>
				    <?php endif; ?>
			    </div>
			</div>

		</div>
	</div>
	<div class="col-sm-9">
		<h3 class="office-box-title">
            <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
        </h3>
        <div class="content">
        	<?php the_content(); ?>
        </div>
	</div>
</div>