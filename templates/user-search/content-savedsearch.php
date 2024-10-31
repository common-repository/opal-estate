<?php
$data = OpalEstate_User_Search::getInstance()->get_list();
// echo '<pre>'.print_r( $data,  1 );die;
?>
<?php if( $data ): ?>
<div class="property-listing my-saved-search">
 	<div class="panel panel-default">
 		<div class="panel-body">
 			<h4><?php _e( 'My Saved Searches' , 'opalestate' ) ; ?></h4>			
			<table class="table table-condensed">
	 			<thead> 
	 			 	<tr> <th>#</th> <th><?php _e('Name','opalestate'); ?></th> <th><?php _e('View','opalestate'); ?></th> <th><?php _e('Delete','opalestate'); ?></th>  </tr>
	 			</thead> 
	 				<tbody> 
						
						<?php  foreach( $data as $key => $search ):  ?>

			 				<tr> 
			 					<th scope="row"><?php echo $key + 1; ?></th> 
			 					<td><?php echo $search->name; ?></td>
			 				 	<td><a target="_blank" href="<?php echo opalestate_get_search_link().'?'.$search->params; ?>"> <i class="fa fa-search"></i></a></td>  
			 				 	<td><a class="text-danger" onclick="return confirm('<?php _e( 'Are you sure to delete this?', 'opalestate' ); ?>')" href="<?php echo opalestate_user_savedsearch_page( array('id' => $search->id ,'doaction' =>'delete') ); ?>"> <i class="fa fa-close"></i></a></td>  
			 				</tr> 

					 	<?php endforeach; ?>
		 			</tbody> 
		 	</table>
 

		</div>	
 	</div>
</div>
<?php else : ?>
	<div class="panel panel-default">	
	 	<div class="panel-body">
		 	<div class="opalestate-message">
		 		<h3><?php _e( 'No Item In Saved Searches', 'opalestate' ); ?></h3>
				<p><?php _e( 'You have not added any search data.', 'opalestate' ) ;?></p>
			</div>
		</div>	
	</div>	
<?php endif; ?>
<?php wp_reset_postdata(); ?>