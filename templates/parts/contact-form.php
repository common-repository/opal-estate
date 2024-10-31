<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( !isset($id) ){
    $id = 0;
}
 
if( !isset($type) ){
    $type = 'property';
} 
?>

<?php if ( ! empty( $email ) ) : ?>
    <div class="agent-contact-form-container">
        <h3><?php echo __( 'Contact Form', 'opalestate' ); ?></h3>

        <div class="box-content agent-contact-form">

            <form method="post" action="" class="opalestate-contact-form">
                <?php do_action('opalestate_agent_contact_form_before'); ?>
              
                <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
                <input type="hidden" name="id" value="<?php echo $id; ?>"> 
                <input type="hidden" name="type" value="<?php echo $type; ?>">
          
            
                <div class="form-group">
                    <input class="form-control" name="name" type="text" placeholder="<?php echo __( 'Name', 'opalestate' ); ?>" required="required">
                </div><!-- /.form-group -->

                <div class="form-group">
                    <input class="form-control" name="email" type="email" placeholder="<?php echo __( 'E-mail', 'opalestate' ); ?>" required="required">
                </div><!-- /.form-group -->

                <div class="form-group">
                    <textarea class="form-control" name="message" placeholder="<?php echo __( 'Message', 'opalestate' ); ?>" style="overflow: hidden; word-wrap: break-word; height: 68px;"><?php echo $message
                    ; ?></textarea>
                </div><!-- /.form-group -->
                <?php do_action('opalestate_agent_contact_form_after'); ?>
                <button class="button btn btn-primary btn-3d" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> <?php echo __( ' Processing', 'opalestate' ); ?>" type="submit" name="contact-form"><?php echo __( 'Send message', 'opalestate' ); ?></button>
            </form>
        </div><!-- /.agent-contact-form -->
    </div><!-- /.agent-contact-->
<?php endif; ?>
