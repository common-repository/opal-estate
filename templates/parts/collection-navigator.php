<?php 

	if( isset($_POST['opalsortable']) ){
		if( !empty($_POST['opalsortable']) ){
			$_SESSION['opalsortable'] = $_POST['opalsortable'];
		}else if( isset( $_SESSION['opalsortable'] ) ) {
			unset( $_SESSION['opalsortable'] );
		}	
	}

	$selected = '';

	if( isset($_SESSION['opalsortable']) ){
		$args['sortable'] = $_SESSION['opalsortable']; 	
		$selected = $args['sortable'];  
	}

	$mode  = isset($mode)?$mode:'';
?>
<div class="opalesate-archive-top">
	<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
		<div class="col-lg-8 col-md-7 col-sm-6">
			
			<?php opalestate_show_display_status();
 
			?>
			 
		</div>

		<div class="col-lg-4 col-md-5 col-sm-6 space-margin-top-10p">
			<div class="opalestate-sortable pull-right">
				<?php echo opalestate_render_sortable_dropdown( $selected ); ?>
			</div>
			<div class="pull-right">
				 <?php opalestate_show_display_modes( $mode ); ?>
			</div>
		</div>
	</div>
</div>