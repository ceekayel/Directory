<?php
/**
 * Search Template
 *
 * The search template is loaded when a visitor uses the search form to search for something
 * on the site.
 */
get_header(); // Loads the header.php template.

	do_action( 'before_content' );
   
	/* to show the breadcrumb */
	do_action( 'templ_before_container_breadcrumb' );  ?>
	<section id="content" class="search_result_listing large-9 small-12 columns">
		<?php 

		do_action( 'open_content' );

		do_action( 'templ_inside_container_breadcrumb' );	

		?>
		<div class="hfeed">
			<?php 
			get_template_part( 'loop-meta' ); // Loads the loop-meta.php template. 
			if(have_posts())
				do_action('directory_after_search_title'); 
				?>
		<div class="twp_search_cont">
			<?php get_template_part('searchform');  ?>
		</div>
			<?php
				global $wp_query,$htmlvar_name;
				$cus_post_type = get_post_type();
				if(!$cus_post_type){ $cus_post_type = $_REQUEST['post_type'][0]; }

				if(function_exists('tmpl_get_category_list_customfields') && function_exists('tmpl_get_category_list_customfields'))
				{
							$heading_type = tmpl_fetch_heading_post_type($cus_post_type);
							
							/* custom fields for custom post type.. */
							$htmlvar_name = tmpl_get_category_list_customfields($cus_post_type,$heading,$key);

				}
				/* Loads the sidebar-before-content. */
				apply_filters('tmpl_before-content',supreme_sidebar_before_content() ); 
				
				/* Loads the loop.php template. */
				get_template_part( 'loop','search' ); 
				
				/* after-content-sidebar use remove filter to don't display it */
				apply_filters('tmpl_after-content',supreme_sidebar_after_content());
			?>
		</div>
		<!-- hfeed -->
		<?php do_action( 'close_content' ); 
			/* Loads the loop-navigation */
			apply_filters('supreme_search_loop_navigation',supreme_loop_navigation($post));
		?>
	</section>
	<!-- #content -->
<?php
	do_action( 'after_content' ); 
	get_sidebar();
	get_footer();  
?>