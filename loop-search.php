<?php
/**
 * Loop Template
 *
 * Displays the entire post content.
 */
global $posts,$wpdb, $wp_query,$htmlvar_name;
$post_query = $wp_query;
// reset wp_query
if(strpos($_SERVER['REQUEST_URI'], 'admin-ajax.php'))
{
	if(!isset($cus_post_type ) && is_search()){
		if(function_exists('tmpl_get_category_list_customfields') && function_exists('tmpl_get_category_list_customfields'))
		{
			$heading_type = tmpl_fetch_heading_post_type($wp_query->query_vars['post_type']);
			$wp_query = $post_query;
			$htmlvar_name = tmpl_get_category_list_customfields($wp_query->query_vars['post_type'],$heading,$key);
			$wp_query = $post_query;			
		}
	}
} ?>
<div id="tmpl-search-results" class="list">
<?php 
if ( have_posts() ) : 
 while ( have_posts() ) : the_post();
	do_action( 'before_entry' ); // supreme_before_entry 
	$format = get_post_format( $post->ID ); ?>
	<div id="post-<?php echo $post->ID; ?>" <?php post_class(); ?>>
	<?php	
	
	if($post->post_type =='post'){
		get_template_part( 'content', 'blog'); 
	}else{ 
		/* when it's go with wordpress search no custom fields will be display - because not get the custom post type */
		if(!isset($cus_post_type ) && is_search() && !strpos($_SERVER['REQUEST_URI'], 'admin-ajax.php') ){
			if(function_exists('tmpl_get_category_list_customfields') && function_exists('tmpl_get_category_list_customfields'))
			{
						$heading_type = tmpl_fetch_heading_post_type($post->post_type);
						
						/* custom fields for custom post type.. */
						$htmlvar_name = tmpl_get_category_list_customfields($post->post_type,$heading,$key);

			}
		}
		if(file_exists(get_template_directory().'/content-'.$post->post_type.".php")){
			get_template_part( 'content', $post->post_type); 
		}else{
			get_template_part( 'content', get_post_format()); 
		}
	} ?>
	</div>
	<?php
	do_action( 'after_entry' ); 
	endwhile; 
	wp_reset_query();
else:
	apply_filters('supreme-loop-error',get_template_part( 'loop-error' )); // Loads the loop-error.php template. 
endif; ?>
</div>
