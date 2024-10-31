<?php $team = get_post_meta( get_the_ID(), OPALESTATE_OFFICE_PREFIX . 'team', true ); ?>
<div class="office-tabs">
	<ul class="nav nav-tabs" role="tablist">
		<li class="active">
			<a aria-expanded="false" href="#office-properties" role="tab" data-toggle="tab">
				<span><?php _e( 'Properties', 'opalestate'  ); ?></span>
			</a>
		</li>
		<li>
			<a aria-expanded="true" href="#office-team" class="tab-google-street-view-btn" role="tab" data-toggle="tab">
				<span><?php _e('Team','opalestate'); ?></span>
			</a>
		</li>

		<li >
			<a aria-expanded="true" href="#office-review" class="tab-google-street-view-btn" role="tab" data-toggle="tab">
				<span><?php _e('Review','opalestate'); ?></span>
			</a>
		</li>

	</ul>
	<div class="tab-content">
		<div class="tab-pane fade out active in" id="office-properties">
			 <?php echo Opalestate_Template_Loader::get_template_part( 'single-office/properties' ); ?>
		</div>

		<?php if(  $team ): ?> 
		<div class="tab-pane fade out" id="office-team">
			 <?php echo Opalestate_Template_Loader::get_template_part( 'single-office/team' ); ?>
		</div>
		<?php endif; ?>
		<?php if ( comments_open() || get_comments_number() ) : ?>
		<div class="tab-pane fade out" id="office-review">
			 <?php echo Opalestate_Template_Loader::get_template_part( 'single-office/review' ); ?>
		</div>
		<?php endif; ?>
	</div>	

</div>

