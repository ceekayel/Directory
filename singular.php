<?php
/**
 * Singular Template
 *
 * This is the default singular template.  It is used when a more specific template can't be found to display
 * singular views of posts (any post type).
 */
get_header(); 
	do_action( 'before_content' );  ?>
<section id="content" class="large-9 small-12 columns">
	<?php do_action( 'open_content' );  ?>
	<div class="hfeed">
	<?php apply_filters('tmpl_before-content',supreme_sidebar_before_content() ); // Loads the sidebar-before-content.
			if ( have_posts() ) :
			while ( have_posts() ) : the_post(); 
				do_action( 'before_entry' ); ?>
				<div id="post-<?php the_ID(); ?>" class="<?php supreme_entry_class(); ?>">
				  <?php do_action( 'open_entry' );
						do_action('entry-title');
						do_action('supreme-single-post-info');
						
						apply_filters( 'tmpl-entry',supreme_sidebar_entry() );  ?>
				  <section class="entry-content">
					<?php 
						do_action('open-post-content');
						the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', THEME_DOMAIN ) );
						wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', THEME_DOMAIN ), 'after' => '</p>' ) ); 
						do_action('close-post-content');
						?>
				  </section>
				  <!-- .entry-content -->
				  <?php 
					apply_filters('supreme_singular_post_categories',supreme_get_categories('Categories: ','category','','Tags: ','post_tag')); // 1- category label, 2- category slug,3- class name of div, 3- tags label,4- tags slug
					do_action( 'close_entry' );  ?>
				</div>
				<!-- .hentry -->
			<?php
			do_action( 'after_entry' );
			apply_filters('tmpl_after-singular',supreme_sidebar_after_singular()); // Loads the sidebar-after-singular.
			do_action( 'after_singular' );
			do_action( 'before_comments' );
			comments_template( '/comments.php', true );
			do_action( 'after_comments' );
	endwhile; 
	endif;
	apply_filters('tmpl_after-content',supreme_sidebar_after_content()); ?>
	</div>
  <!-- .hfeed -->
  <?php do_action( 'close_content' ); 
		apply_filters('supreme_singular_loop_navigation',supreme_loop_navigation($post)); // Loads the loop-navigation . ?>
</section>
<!-- #content -->
<?php do_action( 'after_content' );
get_sidebar();
get_footer(); // Loads the footer.php template. ?>