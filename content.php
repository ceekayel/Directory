<?php 
global $htmlvar_name;
do_action('directory_before_post_loop');
		/* Hook to display before image */	
		do_action('directory_before_category_page_image');
			
		/* Hook to Display Listing Image  */
		do_action('directory_category_page_image');
		 
		/* Hook to Display After Image  */						 
		do_action('directory_after_category_page_image'); 
		   
		/* Before Entry Div  */	
		do_action('directory_before_post_entry');	?> 
		
		<!-- Entry Start -->
		<div class="entry"> 
		   
			<?php  
			do_action('tmpl_open_entry');    
			/* do action for before the post title.*/ 
			do_action('directory_before_post_title');    
			do_action('templ_before_title_'.$post->post_type);     ?>
			<div class="<?php echo $post->post_type; ?>-wrapper">
				<!-- Entry title start -->
				<div class="entry-title-wrapper">
			   
				<?php 
				/* do action for display the single post title */ 
				do_action('templ_post_title');                
				
				/* do action for display the price */
				do_action('templ_'.$post->post_type.'_post_title'); 
				
				/* do action for display the single post title */
				do_action('tevolution_title_text');          
				?>
			   
				</div>
				
				<?php do_action('directory_after_post_title');          /* do action for after the post title.*/?>
			   
				<!-- Entry title end -->
				
				<!-- Entry details start -->
				<div class="entry-details">
				
				<?php
				/* Hook to get Entry details - Like address,phone number or any static field  */  
				
				$post_types = $_REQUEST['custom_post'];
				
				$exclide_post_type = array('listing','event','property','classified');
				
				if(!in_array($post_types,$exclide_post_type)){
					$post_types = 'listing';
				}
				
				echo do_action($post_types.'_post_info');   ?>
				
				</div>
				<!-- Entry details end -->
			</div>
			<!--Start Post Content -->
			<?php /* Hook for before post content . */ 
		   
			do_action('directory_before_post_content'); 
			
			/* Hook to display post content . */ 
			do_action('templ_taxonomy_content');
		   
			/* Hook for after the post content. */
			do_action('directory_after_post_content'); 
			?>
			<!-- End Post Content -->
		   <?php 
			/* Hook for before listing categories     */
			do_action('directory_before_taxonomies');
			
			/* Display listing categories     */
			do_action('templ_the_taxonomies'); 

			/* Hook to display the listing comments, add to favourite and pinpoint   */						
			do_action('directory_after_taxonomies');
			
			do_action('tmpl_before_entry_end');    
			?>
		</div>
		<!-- Entry End -->
		<?php 
		if(!is_author())
		{
			apply_filters( 'tmpl-after-entry',supreme_sidebar_entry() ); // Loads the sidebar-entry
		}
		do_action('directory_after_post_entry');?>

<?php do_action('directory_after_post_loop'); ?>
<!-- .entry-header -->
