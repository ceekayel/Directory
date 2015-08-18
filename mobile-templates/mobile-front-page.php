<?php
/**
 * Home page template for app like mobile view.

 */
get_header(); // Loads the header.php template. ?>

<section id="content" class="large-9 small-12 columns">
  
    <div class="hfeed">
		
		<?php 
		
		if ( have_posts() ) :

			while ( have_posts() ) : the_post();	
				the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', THEME_DOMAIN ) );
				wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', THEME_DOMAIN ), 'after' => '</p>' ) );
			endwhile;

		endif;
		
		dynamic_sidebar( 'before-content' ); // Loads the supreme_sidebar_before_content();  template. ?>
			  <div ID="tmpl-search-results" class="list">
				<?php 
				dynamic_sidebar('home-page-content'); 
				 ?>
			  </div>
			<?php 
			dynamic_sidebar( 'after-content' ); // Loads the sidebar-after-content.php template. ?>
     </div>
  	<!-- .hfeed -->
	<?php 
  	do_action( 'close_content' );
	apply_filters('supreme_custom_front_loop_navigation',supreme_loop_navigation($post)); // Loads the loop-navigation .
	?>
</section>
<!-- #content -->
<?php 
do_action( 'after_content' );
get_footer(); // Loads the footer.php template. ?>