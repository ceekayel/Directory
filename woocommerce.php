<?php
/**
 * Page Template
 *
 * This is the default page template.  It is used when a more specific template can't be found to display 
 * singular views of pages.
 */
get_header(); // Loads the header.php template. ?>
<?php 
	do_action( 'before_content' );
	do_action( 'templ_before_container_breadcrumb' );	?>
	<section id="content" class="large-9 small-12 columns">
	   <?php do_action( 'open_content' ); 
	   do_action( 'templ_inside_container_breadcrumb' );	?>
	   <div class="hfeed">
		<?php apply_filters('tmpl_before-content',supreme_sidebar_before_content() ); // Loads the sidebar-before-content.
				if ( have_posts() ) :
					woocommerce_content(); 
				endif; 
				apply_filters('tmpl_after-content',supreme_sidebar_after_content()); // afetr-content-sidebar use remove filter to dont display it ?>
	  </div>
	  <!-- .hfeed -->
	  <?php do_action( 'close_content' ); ?>
	</section>
<!-- #content -->
<?php do_action( 'after_content' );
	apply_filters( 'tmpl-woo_sidebar',supreme_woocommerce_sidebar() ); // Loads the front page sidebar.
	get_footer(); // Loads the footer.php template. 
?>