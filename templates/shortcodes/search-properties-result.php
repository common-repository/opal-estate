	<?php
		$rowcls = apply_filters('opalestate_row_container_class', 'row opal-row'); 
		if( class_exists("OpalEstate_Search") ): 
			$query = OpalEstate_Search::get_search_results_query();
		?>
		<div class="opaleslate-container">
				<div class="opalesate-archive-top"><div class="<?php echo $rowcls;?>">
					<div class="col-lg-6 col-md-6 col-sm-6">
						 <?php opalestate_show_display_modes(); ?>
					</div>

					<div class="col-lg-6 col-md-6 col-sm-6">
						<div class="opalestate-sortable pull-right">
							<?php echo opalestate_render_sortable_dropdown(); ?>
						</div>	
					</div>
				</div></div>	
				
				<div class="opalestate-results <?php echo $rowcls; ?>">

					<div class="col-lg-8"> 
						<span><?php echo sprintf( __( 'Found %s Properties', 'opalestate' ) , $query->found_posts ); ?> </span>
						<div class="pull-right"> <?php echo Opalestate_Template_Loader::get_template_part( 'user-search/render-form' );  ?>	</div>
					</div>
					<div class="col-lg-4 text-right"> 
						<?php do_action( 'opalestate_before_render_search_properties_result'); ?> 
					 </div>
				</div>	

				<div class="opalesate-archive-bottom opalestate-rows opalestate-collection">
					<?php if( $query->have_posts() ): ?> 
						<div class="<?php echo $rowcls; ?>">
							<?php if ( (isset($_COOKIE['opalestate_displaymode']) && $_COOKIE['opalestate_displaymode'] == 'list') || (!isset($_COOKIE['opalestate_displaymode']) && opalestate_options('displaymode', 'grid') == 'list') ):?>
								<?php while ( $query->have_posts() ) : $query->the_post(); ?>
									<div class="col-lg-12 col-md-12 col-sm-12">
				                    	<?php echo Opalestate_Template_Loader::get_template_part( 'content-property-list' ); ?>
				                	</div>
				                <?php endwhile; ?>
							<?php else : ?>
								<?php 
								$column = apply_filters('opalestate_properties_column_row', 3 ); 
								$clscol = floor(12/$column);
								$cnt = 0; while ( $query->have_posts() ) : $query->the_post(); 
								$cls = ''; 
								if( $cnt++%$column==0 ){
									$cls .= ' first-child';
								}
								?>
									<div class="<?php echo $cls; ?> col-lg-<?php echo $clscol; ?> col-md-<?php echo $clscol; ?> col-sm-6">
				                    	<?php echo Opalestate_Template_Loader::get_template_part( 'content-property-grid' ); ?>
				                	</div>
								<?php endwhile; ?>
							<?php endif; ?>
						</div>
					<?php else: ?>
						<?php echo Opalestate_Template_Loader::get_template_part( 'content-no-results' ); ?>
					<?php endif; ?>	
				</div>	
			</div>
			<?php if( $query->max_num_pages > 1 ): ?>
			<div class="w-pagination"><?php opalestate_pagination(  $query->max_num_pages ); ?></div>
			<?php endif; ?>
		<?php 
		wp_reset_postdata();
		endif; 	
	?>	
