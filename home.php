<?php
/**
 * Home Template
 *
 * This is the home template.  Technically, it is the "posts page" template.  It is used when a visitor is on the 
 * page assigned to show a site's latest blog posts.
 */
get_header(); // Loads the header.php template.
do_action( 'before_content' ); 
do_action( 'templ_before_container_breadcrumb' );  ?>
<section id="content" class="large-9 small-12 columns">
  <?php do_action( 'open_content' ); 
do_action( 'templ_inside_container_breadcrumb' );   ?>
  
	<div class="hfeed list homepage">
    <?php get_template_part( 'loop-meta' ); // Loads the loop-meta.php template.
    
		apply_filters('tmpl_before-content-home',supreme_sidebar_before_content() ); // Loads the sidebar-before-content.
		remove_action('pre_get_posts', 'home_page_feature_listing');
		do_action('supreme_before_article_list');	
		/*fetch custom fields array*/
		global $wp_query,$htmlvar_name;
		$cus_post_type = get_post_type();
		if(function_exists('tmpl_get_category_list_customfields')){
			$tmpdata = get_option('templatic_settings');
			$home_listing_type_value = @$tmpdata['home_listing_type_value'];
			$htmlvar_name = array();
			if(!empty($home_listing_type_value)){
				for($i=0;$i<count($home_listing_type_value);$i++)
				{
					$var_htmlvar_name = tmpl_get_category_list_customfields($home_listing_type_value[$i]);
					$htmlvar_name = array_merge($htmlvar_name,$var_htmlvar_name);
				}
			}else{
				$htmlvar_name = tmpl_get_category_list_customfields(get_post_type());
			}
		}else{
			global $htmlvar_name;
		}		
		if ( have_posts() ) : 
			while ( have_posts() ) : the_post();
				do_action( 'before_entry' ); ?>
			    <!-- Article start -->
			    <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				 <?php 
				if($post->post_type =='post'){
					get_template_part( 'content', 'blog'); 
				}else{
					get_template_part('content',get_post_format());
				}				 ?>
			    </div>
			    <!-- Article end -->
			    <?php do_action( 'after_entry' ); 
						
			endwhile; 
			wp_reset_query();
		else:
			apply_filters('supreme-loop-error',get_template_part( 'loop-error' )); // Loads the loop-error.php template. 
		endif;
		do_action('supreme_after_article_list');				
		apply_filters('tmpl_after-content-home',supreme_sidebar_after_content()); // afetr-content-sidebar use remove filter to dont display it ?>
	</div>
	<!-- .hfeed -->
	<?php do_action( 'close_content' );
	
     if($wp_query->max_num_pages !=1):?>
             <div id="listpagi">
                  <div class="pagination pagination-position">
                        <?php if(function_exists('pagenavi_plugin')) { pagenavi_plugin(); } ?>
                  </div>
             </div>
         <?php endif; ?>
</section>
<!-- #content -->
<?php do_action( 'after_content' );

$page_for_posts=get_option('page_for_posts');
if($page_for_posts!='' )
	apply_filters('supreme-post-listing-sidebar',supreme_post_listing_sidebar());// load the side bar of listing page
else
	apply_filters( 'tmpl-front_page_sidebar',supreme_front_page_sidebar() ); // Loads the front page sidebar.

get_footer(); ?>