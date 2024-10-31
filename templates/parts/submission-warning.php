<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$content = opalestate_options( 'submission_warning_content' );

if( !empty($content) ): 
?>
<div class="panel opalmembership-login-form-wrapper">
	<div class="panel-body">
		<?php echo do_shortcode( $content ); ?>
	</div>
</div>	
<?php else: ?>
<div class="alert alert-warning">
	<?php echo __( 'You are not allowed to access this page.', 'opalestate' ); ?>
</div>
<?php endif ?>