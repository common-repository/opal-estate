<?php if( $author ): ?>
<div class="property-agent-contact ">
	<?php 	 
		$user_id   = $author->ID;
		$is_sticky = get_user_meta( $user_id, OPALESTATE_USER_PROFILE_PREFIX . 'sticky', true ); 
		$picture    = OpalEstate_User::get_author_picture( $user_id );
		$author_name = $author->display_name;
		$desciption = get_user_meta( $user_id, 'description', true ); 
	?>
	<?php if( isset($hide_description) && $hide_description == true ) : ?>
		<?php else : ?>
		    <div class="agent-box-bio">
        	<?php echo $desciption; ?>
        </div>	
 		<?php  endif; ?>
</div>
<?php endif; ?>