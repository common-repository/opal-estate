<?php 
	$query = OpalEstate_Search::get_search_agents_query(); 
	$colum = apply_filters('opalestate_agent_grid_column' , 4 );
?>
<div class="search-agents-wrap">
	<?php echo Opalestate_Template_Loader::get_template_part( 'parts/search-agents-form' ); ?>
	<?php if( $query->have_posts() ): ?>
	<div class="agents-container">
 
			<div class="agent-result hide">
				<h3><?php echo sprintf( __('Found %s Agents', 'opalestate'),  '<span class="text-primary">'.$query->found_posts.'</span>' ) ?></h3>
			</div>	

			<div class="<?php echo apply_filters('opalestate_row_container_class', 'row opal-row');?>">
				<?php $cnt=0; while( $query->have_posts() ):  $query->the_post();  ?>
					<div class="col-lg-3 col-md-3 col-sm-3 <?php if( $cnt++%$colum == 0): ?>first-child<?php endif; ?>">
						<?php echo Opalestate_Template_Loader::get_template_part( 'content-agent-grid' ); ?>
					</div>
				<?php endwhile; ?>
			</div>
			<?php if( $query->max_num_pages ): ?>
			<div class="w-pagination">
				<?php opalestate_pagination( $query->max_num_pages ); ?>
			</div>
			<?php endif;  ?>
		</div>
 
	<?php else: ?>
	<div class="agents-results">
		<?php echo Opalestate_Template_Loader::get_template_part( 'content-no-results' ); ?>
	</div>
	<?php endif; ?>
</div>
<?php wp_reset_postdata(); ?>