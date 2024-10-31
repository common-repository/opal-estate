 <?php if( !opalesate_check_has_add_listing( $user_id ) ): ?>
<div class="alert alert-warning">
	<p><?php _e( 'Your package has 0 left listing, you could not add any more. Please upgrade now', 'opalestate' );?></p>
	<p><a href="<?php echo opalmembership_get_membership_page_uri();?>"><?php _e( 'Click to this link to see plans', 'opalestate' );?></a></p>
</div>
<?php endif; ?>
