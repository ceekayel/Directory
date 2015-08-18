<?php
/**
 * Index Template
 *
 * This is the default template.  It is used when a more specific template can't be found to display
 * posts.  It is unlikely that this template will ever be used, but there may be rare cases.
 */
get_header(); // Loads the header.php template.

do_action( 'before_content' ); // supreme_before_content 
do_action( 'templ_before_container_breadcrumb' ); 
?>
<section id="content" class="large-9 small-12 columns">
<?php do_action( 'open_content' ); 
do_action( 'templ_inside_container_breadcrumb' );   ?>
	<div class="hfeed">
	<?php 
	get_template_part( 'loop-meta' );
	apply_filters('tmpl_before-content',supreme_sidebar_before_content() );
	if ( have_posts() ) :
		while ( have_posts() ) : the_post(); 
			do_action( 'before_entry' ); ?>
               <div id="post-<?php the_ID(); ?>" class="<?php supreme_entry_class(); ?>">
               <?php 
				if($post->post_type =='post'){
					get_template_part( 'content', 'blog'); 
				}else{
				do_action( 'open_entry' );
				do_action( 'entry-title' ); 
				$theme_options = get_option(supreme_prefix().'_theme_settings');
				$supreme_archive_display_excerpt = $theme_options['supreme_archive_display_excerpt'];
				apply_filters('supreme-front-post-info',supreme_front_post_info());
				apply_filters( 'tmpl-entry',supreme_sidebar_entry() ); ?>
                <section class="entry-content">
                <?php
				if( $supreme_archive_display_excerpt) { ?>

					<div class="entry-summary">
					<?php the_excerpt($templatic_excerpt_link  );  ?>
					<?php do_action('single_post_custom_fields'); ?>
					</div>
					<!-- .entry-summary -->

				<?php }else{ 
					if(is_tevolution_active() && tmpl_donot_display_description()){ ?>
					<?php }else{ ?>
					<section class="entry-content">
					<?php 
					the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', THEME_DOMAIN ) ); 
					wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', THEME_DOMAIN ), 'after' => '</div>' ) ); 
					do_action('single_post_custom_fields'); ?>
					</section>
					<!-- .entry-content -->
					<?php	}
				} 
				
				wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', THEME_DOMAIN ), 'after' => '</p>' ) ); ?>
                </section>
                <!-- .entry-content -->
                <?php if(supreme_get_settings( 'display_post_terms' )){ 
			 		apply_filters('supreme_index_post_categories',supreme_get_categories('Categories: ','category','','Tags: ','post_tag'));// 1- category label, 2- category slug,3- class name of div, 3- tags label,4- tags slug
                      }
              		do_action( 'close_entry' ); 
				} ?>
               </div>
               <!-- .hentry -->
               <?php do_action( 'after_entry' );
		endwhile;
		
	else : ?>
     <div class="<?php supreme_entry_class(); ?>">
          <h2 class="entry-title"><?php _e( 'No Entries', THEME_DOMAIN ); ?></h2>
          <section class="entry-content">
               <p><?php _e( 'Apologies, but no entries were found.', THEME_DOMAIN ); ?></p>
     	</section>
     </div>
     <?php endif;?>
    <!-- .hentry .error -->
    <?php apply_filters('tmpl_after-content',supreme_sidebar_after_content()); // afetr-content-sidebar use remove filter to dont display it ?>
	</div>
  <!-- .hfeed -->
	<?php do_action( 'close_content' );
	
	 apply_filters('supreme_index_loop_navigation',supreme_loop_navigation($post)); // Loads the loop-navigation . ?>
</section>
<!-- #content -->
<?php do_action( 'after_content' ); 
$page_for_posts=get_option('page_for_posts');
if($page_for_posts!='' )
	apply_filters('supreme-post-listing-sidebar',supreme_post_listing_sidebar());// load the side bar of listing page
else
	get_sidebar();

get_footer();
?>