<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if( opalestate_options( 'enable_saved_usersearch', 'on' ) == 'on' ): 
$args = array(); 

$message = sprintf( __( 'Hey there! I saved this search on %s, please check out these homes that are listed. Remember to save this search to be first to catch any new listings.', 'opalestate' ) , get_bloginfo( 'name' ) );

?>
<div class="opalestate-popup">
    <div class="popup-head <?php if( !is_user_logged_in() ): ?> opalestate-need-login <?php endif; ?>"><span><i class="fa fa-star" aria-hidden="true"></i> <?php _e('Save search', 'opalestate') ?></span></div>
    <div class="popup-body">
        <div class="popup-close"><i class="fa fa-times" aria-hidden="true"></i></div>
      
            <div class="contact-share-form-container">
               
                <h6><?php echo __( 'Name this search.', 'opalestate' ); ?></h6>

                <div class="box-content agent-contact-form">

                    <form method="post" action="" id="opalestate-save-search-form">
                        <?php do_action('opalestate_contact_share_form_before'); ?>

                        <div class="form-group">
                            <input class="form-control" name="name" type="text" placeholder="<?php echo __( 'Name', 'opalestate' ); ?>" required="required">
                        </div><!-- /.form-group -->

                        <?php do_action('opalestate_contact_share_form_after'); ?>
                        <button class="button btn btn-primary btn-3d btn-block" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo __( ' Processing', 'opalestate' ); ?>" type="submit" name="contact-form"><?php echo __( 'Save', 'opalestate' ); ?></button>
                    </form>
                </div><!-- /.agent-contact-form -->
            </div><!-- /.agent-contact-->
      
    </div>    
</div>
<?php endif; ?>