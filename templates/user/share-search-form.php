<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$args = array(); 

$message = sprintf( __( 'Hey there! I saved this search on %s, please check out these homes that are listed. Remember to save this search to be first to catch any new listings.', 'opalestate' ) , get_bloginfo( 'name' ) );

if( opalestate_options('enable_share_earch','on') == 'on' ): 
?>

<div class="opalestate-popup">
    <div class="popup-head <?php if( !is_user_logged_in() ): ?> opalestate-need-login <?php endif; ?>"><span class="text-primary"><i class="fa fa-envelope" aria-hidden="true"></i> <?php _e('Share this Search', 'opalestate') ?></span> <?php _e('with Friends & Family', 'opalestate') ?></div>
    <?php if( is_user_logged_in() ):
    global $current_user;
    ?>
    <div class="popup-body">
        <div class="popup-close"><i class="fa fa-times" aria-hidden="true"></i></div>
        
            <div class="share-content-form-container">
               
                <h6><?php echo __( 'Are you searching with anyone? Share this search.', 'opalestate' ); ?></h6>

                <div class="box-content share-content-form">

                    <form method="post" action="" class="opalestate-share-content-form">
                        <?php do_action('opalestate_contact_share_form_before'); ?>

                        <div class="form-group">
                            <input class="form-control inputs-emails" name="friend_email[]" type="email" placeholder="<?php echo __( 'Friend Email', 'opalestate' ); ?>" value="" required="required">
                        </div><!-- /.form-group -->

                        <div class="form-group">
                            <input class="form-control" name="name" type="text" placeholder="<?php echo __( 'Name', 'opalestate' ); ?>" value="<?php echo $current_user->data->display_name; ?>" required="required">
                        </div><!-- /.form-group -->

                        <div class="form-group">
                            <input class="form-control" name="email" type="email" placeholder="<?php echo __( 'E-mail', 'opalestate' ); ?>" required="required" value="<?php echo $current_user->data->user_email; ?>">
                        </div><!-- /.form-group -->

                        <div class="form-group">
                            <textarea class="form-control" name="message" placeholder="<?php echo __( 'Message', 'opalestate' ); ?>" style="overflow: hidden; word-wrap: break-word; min-height: 108px;"><?php echo $message
                            ; ?></textarea>
                        </div><!-- /.form-group -->

                        <?php do_action('opalestate_contact_share_form_after'); ?>
                        <button class="button btn btn-primary btn-3d btn-block"  data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo __( ' Processing', 'opalestate' ); ?>" type="submit" name="contact-form"><?php echo __( 'Send message', 'opalestate' ); ?></button>
                    </form>
                </div><!-- /.agent-contact-form -->
            </div><!-- /.agent-contact-->
  
    </div>   
    <?php endif ;  ?> 
</div>
<?php endif; ?>