<?php
/*
Template Name: Advance Search
*/
include_once(ABSPATH.'wp-admin/includes/plugin.php');
$captcha=supreme_get_settings( 'supreme_global_contactus_captcha' );
$a = get_option("recaptcha_options");
get_header();
do_action( 'templ_before_container_breadcrumb' );

$theme_options = get_option(supreme_prefix().'_theme_settings');
$supreme_show_breadcrumb = $theme_options['supreme_show_breadcrumb'];
?>
<section id="content" class="multiple large-9 small-12 columns">
  <?php
  do_action( 'templ_inside_container_breadcrumb' ); 
  do_action( 'after_content' );
  ?>
  
  <div class="hfeed">
	<?php apply_filters('tmpl_before-content',supreme_sidebar_before_content() );
     while ( have_posts() ) : the_post(); 
		do_action( 'before_entry' ); ?>
		<div id="post-<?php the_ID(); ?>" class="<?php supreme_entry_class(); ?>">
			<?php do_action( 'open_entry' ); ?>
               <h1 class="loop-title"><?php the_title(); ?></h1>
               <div class="loop-description">
               <?php the_content(); 
					dynamic_sidebar('advance_search_sidebar');
			   ?>
               </div>
               <!-- .entry-content -->
               <?php  do_action( 'close_entry' );  ?>
		</div>
		<!-- .hentry -->
		<?php
		do_action( 'after_entry' );
		apply_filters('tmpl_after-singular',supreme_sidebar_after_singular()); // Loads the sidebar-after-singular.
		do_action( 'after_singular' );
     endwhile;
	
	apply_filters('tmpl_after-content',supreme_sidebar_after_content());  ?>
  </div>
  <?php do_action( 'close_content' ); ?>
  <!--  CONTENT AREA END -->
</section>
<?php do_action( 'after_content' );
get_sidebar();
get_footer();?>