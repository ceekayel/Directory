<?php
/**
 * Archive Template
 *
 * The archive template is the default template used for archives pages without a more specific template. 
 */
get_header(); // Loads the header.php template. 
	do_action( 'before_content' );
	do_action( 'templ_before_container_breadcrumb' ); 
	global $wp_query, $posts;	
	 ?>
<section id="content" class="large-9 small-12 columns">
  <?php do_action( 'open_content' ); 
	do_action( 'templ_inside_container_breadcrumb' ); 
  ?>
  
 <div class="infinite list hfeed">
    <?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template.
			
		apply_filters('tmpl_before-content-archive',supreme_sidebar_before_content() ); // Loads the sidebar-before-content.
		do_action( 'before_loop_archive' );
		if ( have_posts() ) : 
		 while ( have_posts() ) : the_post();
			do_action( 'before_entry' ); ?>
		<!-- article start -->
		<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php	
			if($post->post_type =='post'){
				get_template_part( 'content', 'blog'); 
			}else{
				get_template_part( 'content', get_post_format()); 
			} 
			?>
		</div>
		<!-- article end -->
		<?php 
		do_action( 'after_entry' );
					
		endwhile; 
		wp_reset_query();
		else:
			apply_filters('supreme-loop-error',get_template_part( 'loop-error' )); // Loads the loop-error.php template. 
		endif;
		do_action( 'after_loop_archive' );
		
		apply_filters('tmpl_after-content-archive',supreme_sidebar_after_content()); // after-content-sidebar use remove filter to don't display it ?>
	</div>
	<!-- .hfeed -->
	<?php 
		do_action( 'close_content' );
	    apply_filters('supreme_archive_loop_navigation',supreme_loop_navigation($post)); // Loads the loop-nav.php template. ?>
</section>
<!-- #content -->
<?php do_action( 'after_content' );
	apply_filters('supreme-post-listing-sidebar',supreme_post_listing_sidebar());// load the side bar of listing page
	
get_footer(); // Loads the footer.php template. ?>