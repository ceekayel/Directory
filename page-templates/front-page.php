<?php
/**
 * Template Name: Front Page
 *
 * This is the home template.  Technically, it is the "posts page" template.  It is used when a visitor is on the 
 * page assigned to show a site's latest blog posts.
 *
 * @package supreme
 * @subpackage Template
 */
get_header();	?>

<section id="content" class="large-9 small-12 columns">
  <?php do_action( 'open_front_content' );
	if ( have_posts() ) : 
		while ( have_posts() ) : the_post(); 
			do_action( 'before_entry' ); ?>
                 
               <div id="post-<?php the_ID(); ?>" class="<?php supreme_entry_class(); ?>">
				<?php do_action( 'open_entry' ); ?>
                    <section class="entry-content">
                    <?php 
					the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', THEME_DOMAIN ) );
					wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', THEME_DOMAIN ), 'after' => '</p>' ) );
                    ?>
                    </section>
                    <!-- .entry-content -->
                    <?php do_action( 'close_entry' ); ?>
               </div>
          	<!-- .hentry -->
  	<?php
		endwhile;
	endif; ?>

     <div class="hfeed">
		<?php 
			get_template_part( 'loop-meta' );
		
			dynamic_sidebar( 'before-content' );?>
			  <div class="home_page_content">
				<?php dynamic_sidebar('home-page-content'); ?>
			  </div>
			<?php 
			dynamic_sidebar( 'after-content' ); ?>
     </div>
     
  	<!-- .hfeed -->
	<?php 
  	do_action( 'close_content' );
	apply_filters('supreme_custom_front_loop_navigation',supreme_loop_navigation($post)); // Loads the loop-navigation .
	?>
</section>
<!-- #content -->
<?php 
apply_filters( 'tmpl-front_page_sidebar',supreme_front_page_sidebar() ); // Loads the front page sidebar.
do_action( 'after_content' );
get_footer(); ?>