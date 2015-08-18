<?php
/*
Template Name: Archives
*/
 	get_header(); 
	do_action( 'before_content' ); 
	$theme_options = get_option(supreme_prefix().'_theme_settings');
	$supreme_show_breadcrumb = $theme_options['supreme_show_breadcrumb'];
do_action( 'templ_before_container_breadcrumb' ); 
?>
<!--  CONTENT AREA START -->
<section id="content" class="multiple large-9 small-12 columns">
  <?php
  do_action( 'templ_inside_container_breadcrumb' );
  do_action( 'open_content' );  ?>
	<div class="hfeed">
    <h1 class="loop-title">
      <?php the_title(); ?>
    </h1>
    <div class="loop-description">
      <?php 
          $content = $post->post_content;
          $content = apply_filters('the_content', $content);	
          echo $content;
          ?>
    </div>
    <!-- .entry-content -->
    <?php 
		global $post;
		$archives_post=$post;
		$templatic_catelog_post_type = get_post_meta($post->ID,'template_post_type',true);
		if(isset($templatic_catelog_post_type) && $templatic_catelog_post_type!=""){
			$templatic_catelog_post_type = $templatic_catelog_post_type;
		}else{
			$templatic_catelog_post_type = "post";
		}
		$years = $wpdb->get_results("SELECT DISTINCT MONTH(post_date) AS month, YEAR(post_date) as year
			FROM $wpdb->posts WHERE post_status = 'publish' and post_date <= now( ) and 
			post_type = '$templatic_catelog_post_type' ORDER BY post_date DESC");
		remove_action('pre_get_posts','location_pre_get_posts',12);
		if($years)
		{
			foreach($years as $years_obj)
			{
				$year = $years_obj->year;	
				$month = $years_obj->month; 
				if($templatic_catelog_post_type != '') {
					query_posts("post_type=$templatic_catelog_post_type&showposts=1000&year=$year&monthnum=$month");
				} else {
					query_posts("post_type='product'&showposts=1000&year=$year&monthnum=$month");
				}	?>
				<div class="arclist">
				  <h2><?php echo $year; ?> <?php echo  date_i18n('F', mktime(0,0,0,$month,1)); ?></h2>
				  <ul>
					<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
					<li> <a href="<?php the_permalink() ?>">
					  <?php the_title(); ?>
					  </a><br />
					  <span class="arclist_date">
					  <?php _e('by',THEME_DOMAIN);?>
					  <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>" title="Posts by <?php the_author(); ?>">
					  <?php the_author(); ?>
					  </a>
					  <?php _e('on',THEME_DOMAIN);?>
					  <?php the_time(__(get_option('date_format'),THEME_DOMAIN)) ?>
					  <?php comments_popup_link(__('| No Comments',THEME_DOMAIN), __('| 1 Comment',THEME_DOMAIN), __('| % Comments',THEME_DOMAIN), '', __('| Comments Closed',THEME_DOMAIN)); ?>
					  </span> </li>
					<?php endwhile; endif; ?>
				  </ul>
				</div>
		<?php
			}
		}
		$post=$archives_post;
	 ?>
	</div>
  <?php do_action( 'close_content' ); // supreme_close_content ?>
</section>
<?php do_action( 'after_content' ); // supreme_after_content 
	get_sidebar();
	get_footer(); ?>