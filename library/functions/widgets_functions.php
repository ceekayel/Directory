<?php
/*
	Displays widgets for the After Content dynamic sidebar if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_sidebar_after_content(){
		
	if ( is_active_sidebar( 'after-content' ) ) : ?>
	<?php do_action( 'after_sidebar_after_content' ); ?>
	<aside id="sidebar-after-content" class="sidebar sidebar-inter-content large-3 small-12 columns">
	  <?php do_action( 'open_sidebar_after_content' ); 
			dynamic_sidebar( 'after-content' ); 
			do_action( 'close_sidebar_after_content' ); ?>
	</aside>
	<!-- #sidebar-after-content -->
	<?php do_action( 'after_sidebar_after_content' );
		
	endif; 
}
/*
	Displays widgets for the After header dynamic sidebar if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_sidebar_after_header(){
		
	if ( is_active_sidebar( 'after-header' ) ) : 
		do_action( 'before_sidebar_after_header' ); ?>
	<aside id="sidebar-after-header" class="sidebar sidebar-1c sidebar-after-header clearfix large-3 small-12 columns">
	  <div class="sidebar-wrap">
		<?php do_action( 'open_sidebar_after_header' );
				dynamic_sidebar( 'after-header' ); 
				do_action( 'close_sidebar_after_header' ); ?>
	  </div>
	  <!-- .sidebar-wrap -->
	</aside>
	<!-- #sidebar-after-header -->
	<?php 
	do_action( 'after_sidebar_after_header' ); 
	endif; 
}

/*
	Displays widgets for the After singular post dynamic sidebar if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_sidebar_after_singular(){
		
	if ( is_active_sidebar( 'after-singular' ) ) : ?>
<?php do_action( 'after_sidebar_after_singular' ); ?>
<aside id="sidebar-after-singular" class="sidebar sidebar-inter-content large-3 small-12 columns">
  <?php do_action( 'open_sidebar_after_singular' ); 
		dynamic_sidebar( 'after-singular' );
		do_action( 'close_sidebar_after_singular' ); ?>
</aside>
<!-- #sidebar-after-singular -->
<?php do_action( 'after_sidebar_after_singular' ); 
	endif; 
}
/*
	Displays widgets for the before content dynamic sidebar if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_sidebar_before_content(){
		
	if ( is_active_sidebar( 'before-content' ) ) : ?>
	<?php do_action( 'before_sidebar_before_content' ); ?>
	<aside id="sidebar-before-content" class="sidebar sidebar-inter-content large-3 small-12 columns">
	  <?php do_action( 'open_sidebar_before_content' ); 
			 dynamic_sidebar( 'before-content' ); 
			 do_action( 'close_sidebar_before_content' ); ?>
	</aside>
	<!-- #sidebar-before-content -->
	<?php 
	do_action( 'after_sidebar_before_content' ); 
	endif; 
}
/*
	Displays widgets for the before content and after title on first listing of listing page dynamic sidebar if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_sidebar_entry(){
		
	if ( is_active_sidebar( 'entry' ) ) :
	do_action( 'before_sidebar_entry' );
	?>
	<aside id="sidebar-entry" class="sidebar large-3 small-12 columns">
	  <?php do_action( 'open_sidebar_entry' );
			dynamic_sidebar( 'entry' );
			do_action( 'close_sidebar_entry' ); ?>
	</aside>
	<!-- #sidebar-entry -->
	<?php 
	do_action( 'after_sidebar_entry' );
	endif; 
}
/*
	Displays widgets for header dynamic sidebar if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_header_sidebar(){
		
	if ( is_active_sidebar( 'header' ) ) : ?>
	<!-- #sidebar-header right start -->
	<?php 
	do_action( 'before_sidebar_header' ); ?>
	<aside id="sidebar-header" class="sidebar">
	  <?php do_action( 'open_sidebar_header' ); 
					
					dynamic_sidebar( 'header' );
					
			do_action( 'close_sidebar_header' ); ?>
	</aside>
	<!-- #sidebar-header right end -->
	<?php 
	do_action( 'after_sidebar_header' );
	endif;
}
/*
	Displays widgets in post listing  page sidebar area if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_front_page_sidebar(){
	
	if ( is_active_sidebar( 'front-page-sidebar' ) ) : ?>
	<?php 
	do_action( 'before_front-page-sidebar' );  ?>
	<aside id="sidebar-front_page" class="front-page-sidebar sidebar large-3 small-12 columns">
	  <?php do_action( 'open_front-page-sidebar' ); 
			dynamic_sidebar( 'front-page-sidebar' ); 
			do_action( 'close_front-page-sidebar' ); ?>
	</aside>
	<!-- #sidebar-front-page-sidebar -->
	<?php 
	do_action( 'after_front-page-sidebar' );
	else:
		if(!supreme_is_layout1c())
				get_sidebar();
	endif;
}
/*
	Displays widgets in post detail page sidebar area if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_post_detail_sidebar(){
	
	if ( is_active_sidebar( 'post-detail-sidebar' ) ) : ?>
	<?php
	do_action( 'before_post-detail-sidebar' ); ?>
	<aside id="sidebar-post-detail" class="post-detail-sidebar sidebar large-3 small-12 columns">
	  <?php do_action( 'open_post-detail-sidebar' ); 
				dynamic_sidebar( 'post-detail-sidebar' ); 
				do_action( 'close_post-detail-sidebar' );  ?>
	</aside>
	<!-- #sidebar-front-page-sidebar -->
	<?php 
	do_action( 'after_post-detail-sidebar' );
	else:
		if(!supreme_is_layout1c())
		 get_sidebar();
	endif;
}
/*
	Displays widgets in Post/Blog Listing page sidebar area if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_post_listing_sidebar(){
	
	if ( is_active_sidebar( 'post-listing-sidebar' ) ) : ?>
	<?php 
	do_action( 'before_post-lisitng-sidebar' );  ?>
	<aside id="sidebar-post-listing" class="post-listing-sidebar sidebar large-3 small-12 columns">
	  <?php do_action( 'open_post-listing-sidebar' ); 
				dynamic_sidebar( 'post-listing-sidebar' ); 
				do_action( 'close_post-listing-sidebar' );  ?>
	</aside>
	<!-- #sidebar-front-page-sidebar -->
	<?php 
	do_action( 'after_post-listing-sidebar' );
	else:
		if(!supreme_is_layout1c())
			get_sidebar();
	endif;
}

/*
	Displays widgets in sidebar area if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_primary_sidebar(){
	if ( is_active_sidebar( 'primary-sidebar' ) ) : ?>
	<?php 
		do_action( 'before_sidebar_primary' );  ?>
	<aside id="sidebar-primary" class="sidebar large-3 small-12 columns">
	  <?php do_action( 'open_sidebar_primary' );
				dynamic_sidebar( 'primary-sidebar' ); 
				do_action( 'close_sidebar_primary' ); ?>
	</aside>
	<!-- #sidebar-primary -->
	<?php do_action( 'after_sidebar_primary' );
	endif;
}

/*
	Displays widgets in subsidiary area ,above footer if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_subsidiary_sidebar(){
	
	if ( is_active_sidebar( 'subsidiary' ) ) :
	do_action( 'before_sidebar_subsidiary' ); // supreme_before_sidebar_subsidiary ?>
	<aside id="sidebar-subsidiary" class="sidebar sidebar-1c sidebar-subsidiary clearfix large-3 small-12 columns">
	  <div class="sidebar-wrap">
		<?php 
			do_action( 'open_sidebar_subsidiary' );
			dynamic_sidebar( 'subsidiary' );
			do_action( 'close_sidebar_subsidiary' ); ?>
	  </div>
	  <!-- .sidebar-wrap -->
	</aside>
	<!-- #sidebar-subsidiary -->
<?php do_action( 'after_sidebar_subsidiary' );
	endif;
}

/*
	Displays widgets in subsidiary two column ,above footer if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_subsidiary_2c_sidebar(){
	
	if ( is_active_sidebar( 'subsidiary-2c' ) ) :
	do_action( 'before_sidebar_subsidiary_2c' ); ?>
	<aside id="sidebar-subsidiary-2c" class="sidebar sidebar-2c sidebar-subsidiary clearfix large-3 small-12 columns">
	  <div class="sidebar-wrap">
		<?php 
			do_action( 'open_sidebar_subsidiary_2c' );
			dynamic_sidebar( 'subsidiary-2c' );
			do_action( 'close_sidebar_subsidiary_2c' ); ?>
	  </div>
	  <!-- .sidebar-wrap -->
	</aside>
	<!-- #sidebar-subsidiary-2c -->
<?php do_action( 'after_sidebar_subsidiary_2c' );
	endif;
}

/*
	Displays widgets in subsidiary three column ,above footer if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_subsidiary_3c_sidebar(){
	
	if ( is_active_sidebar( 'subsidiary-3c' ) ) :
	do_action( 'before_sidebar_subsidiary_3c' ); // supreme_before_sidebar_subsidiary_3c ?>
	<aside id="sidebar-subsidiary-3c" class="sidebar sidebar-3c sidebar-subsidiary clearfix large-3 small-12 columns">
	  <div class="sidebar-wrap">
		<?php 
			do_action( 'open_sidebar_subsidiary_3c' ); // supreme_open_sidebar_subsidiary_3c 
			dynamic_sidebar( 'subsidiary-3c' );
			do_action( 'close_sidebar_subsidiary_3c' ); // supreme_close_sidebar_subsidiary_3c ?>
	  </div>
	  <!-- .sidebar-wrap -->
	</aside>
	<!-- #sidebar-subsidiary-3c -->
	<?php do_action( 'after_sidebar_subsidiary_3c' ); // supreme_after_sidebar_subsidiary_3c
	endif;
}

/*
	Displays widgets in subsidiary four column ,above footer if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_subsidiary_4c_sidebar(){
	
	if ( is_active_sidebar( 'subsidiary-4c' ) ) :
	do_action( 'before_sidebar_subsidiary_4c' ); ?>
	<aside id="sidebar-subsidiary-4c" class="sidebar sidebar-4c sidebar-subsidiary large-3 small-12 columns">
	  <div class="sidebar-wrap">
		<?php 
			do_action( 'open_sidebar_subsidiary_4c' );
			dynamic_sidebar( 'subsidiary-4c' );
			do_action( 'close_sidebar_subsidiary_4c' );  ?>
	  </div>
	  <!-- .sidebar-wrap -->
	</aside>
	<!-- #sidebar-subsidiary-4c -->
<?php do_action( 'after_sidebar_subsidiary_4c' ); 
	endif;
}
/*
	Displays widgets in subsidiary four column ,above footer if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_widget_template(){
	
if ( is_active_sidebar( 'widgets-template' ) ) :
	
	do_action( 'before_sidebar_widgets_template' );  ?>
	<aside id="sidebar-widgets-template" class="sidebar large-3 small-12 columns">
	  <?php 
			do_action( 'open_sidebar_widgets_template' ); 
			dynamic_sidebar( 'widgets-template' );
			do_action( 'close_sidebar_widgets_template' ); // supreme_close_sidebar_widgets_template ?>
	</aside>
	<!-- #sidebar-widgets-template -->
	<?php do_action( 'after_sidebar_widgets_template' ); // supreme_after_sidebar_widgets_template 
	endif;
}
/*
	Displays widgets in contact page sidebar, if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_contact_page_sidebar(){
	
	if ( is_active_sidebar( 'contact_page_sidebar' ) ) :
	do_action( 'before_contact_page_sidebar' );  ?>
	<aside id="sidebar-contact_page_sidebar" class="sidebar large-3 small-12 columns">
	  <?php do_action( 'open_sidebar_widgets_template' );
			dynamic_sidebar('contact_page_sidebar' );
			do_action( 'close_sidebar_widgets_template' ); ?>
	</aside>
	<!-- #sidebar-widgets-template -->
	<?php do_action( 'after_contact_page_sidebar' );
	else:
		get_sidebar();
	endif;
}
/*
	Displays widgets in subsidiary four column ,above footer if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_contact_page_widget(){
	
if ( is_active_sidebar( 'contact_page_widget' ) ) : ?>
<div class="cont_wid_area clearfix">
	<?php
	do_action( 'before_contact_page_widget' );
		
		do_action( 'open_contact_page_widget' ); 
		dynamic_sidebar( 'contact_page_widget' );
		do_action( 'close_contact_page_widget' ); 
		
	do_action( 'after_contact_page_widget' );
	?>
</div>
<?php
	endif;
} 
/*
	Displays widgets in post listing  page sidebar area if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_woocommerce_sidebar(){
	
	if ( is_active_sidebar( 'supreme_woocommerce' ) && !supreme_is_layout1c() ) : ?>
	<?php 
	do_action( 'before_woo-sidebar' );  ?>
	<aside id="sidebar-woo_page" class="woo-page-sidebar sidebar large-3 small-12 columns">
	  <?php do_action( 'open_front-page-sidebar' );
				dynamic_sidebar( 'supreme_woocommerce' ); 
				do_action( 'close_woo-sidebar' ); ?>
	</aside>
	<!-- #sidebar-front-page-sidebar -->
	<?php 
	do_action( 'after_woo-sidebar' );
	else:
		if(!supreme_is_layout1c())
				get_sidebar();
	endif;
}
/*
	Displays widgets in footer.
*/
function supreme_footer_widgets(){
	
	if ( is_active_sidebar( 'footer' ) ) : 
		do_action( 'before_footer' ); // supreme_before_sidebar_primary 
			dynamic_sidebar( 'footer' ); 
		do_action( 'after_footer' ); // supreme_after_sidebar_primary 
	endif;
}
/*
	Displays widgets in Post/Blog Listing page sidebar area if any have been added to the sidebar through the widgets screen in the admin by the user.  Otherwise, nothing is displayed.
*/
function supreme_author_page_sidebar(){
	
	if ( is_active_sidebar( 'author-page-sidebar' ) ) : ?>
	<?php do_action( 'before_author-page-sidebar' ); ?>
	<aside id="sidebar-post-listing" class="post-listing-sidebar sidebar large-3 small-12 columns">
	  <?php do_action( 'open_author-page-sidebar' );
			dynamic_sidebar( 'author-page-sidebar' ); 
			do_action( 'close_author-page-sidebar' ); ?>
	</aside>
	<!-- #sidebar-front-page-sidebar -->
	<?php
	do_action( 'after_author-page-sidebar' );
	else:
		if(!supreme_is_layout1c())
			get_sidebar();
	endif;
}


/* get popular post in widget */
add_action('wp_ajax_load_populer_post','tmpl_load_populer_post');
add_action('wp_ajax_nopriv_load_populer_post','tmpl_load_populer_post');

/* get popular posts for selected post type from back end in popular post widget */
/* previously this code was in Directory\library\functions\loadpopularpost.php */
function tmpl_load_populer_post()
{

	$_REQUEST['limitarr'] = explode(',',$_REQUEST['limitarr']);
	require_once(ABSPATH."wp-load.php");
	global $wpdb,$post;
	
	if(is_plugin_active('sitepress-multilingual-cms/sitepress.php'))
	{
		global $sitepress;
		$sitepress->switch_lang($_REQUEST['limitarr'][7]);
	}
	
	$ppost = get_option('widget_templatic_popular_post_technews');
	foreach($ppost as $key=>$value)
	{		
		$popular_per= @$value['popular_per'];
		$show_excerpt= @$value['show_excerpt'];
		$show_excerpt_length= @$value['show_excerpt_length'];
		$number= @$value['number'];		
		break;
	}
	$posthtml = '';		
	$start = $_REQUEST['limitarr'][0];
	$end = $_REQUEST['limitarr'][1];
	$total = $_REQUEST['limitarr'][2];
	$post_type = $_REQUEST['limitarr'][3];
	$num=$_REQUEST['limitarr'][4];
	$popular_per=$_REQUEST['limitarr'][5];
	$number=$_REQUEST['limitarr'][6];
	$show_excerpt=$_REQUEST['limitarr'][8];
	$show_excerpt_length=$_REQUEST['limitarr'][9];
	if(isset($number))
		$_SESSION['total'] = $number;		
		
		if(($start + $end) > $_SESSION['total'])
		{
			$end =   ($_SESSION['total'] - $start );
		}			
				
		if($popular_per == 'views'){		
			$args_popular=array(
						'post_type'=>$post_type,
						'post_status'=>'publish',
						'posts_per_page' => $end,
						'paged'=>$num,
						'meta_key'=>'viewed_count',
						'orderby' => 'meta_value_num',
						'meta_value_num'=>'viewed_count',
						'order' => 'DESC'
						);
		}elseif($popular_per == 'dailyviews'){
			$args_popular=array(
						'post_type'=>$post_type,
						'post_status'=>'publish',
						'posts_per_page' => $end,
						'paged'=>$num,
						'meta_key'=>'viewed_count_daily',
						'orderby' => 'meta_value_num',
						'meta_value_num'=>'viewed_count_daily',
						'order' => 'DESC'
						);
		}else{		
			$args_popular=array(
						'post_type'=>$post_type,
						'post_status'=>'publish',
						'posts_per_page' => $end,
						'paged'=>$num,					
						'orderby' => 'comment_count',					
						'order' => 'DESC'
						);
		}
		
		remove_all_actions('posts_orderby');
		

		$location_post_type = get_option('location_post_type');

		foreach($location_post_type as $location_post_types)
		{
			$post_types = explode(',',$location_post_types);
			$post_type1[] = $post_types[0];
		}


	if(is_plugin_active('Tevolution-LocationManager/location-manager.php') && in_array($post_type,$post_type1)){
		add_filter('posts_where', 'location_multicity_where');
	}
	$popular_post_query = new WP_Query($args_popular);
	if(is_plugin_active('Tevolution-LocationManager/location-manager.php')){
		remove_filter('posts_where', 'location_multicity_where');
	}
	$length = 0;
	if( @$show_excerpt_length){
		$length = $show_excerpt_length;
	}else{
		$length = 75;
	}

	if($popular_post_query):
	
		$post_excerpt = '';
		$post_content = '';
		while ($popular_post_query->have_posts()) : $popular_post_query->the_post();
			$post_title = (stripslashes($post->post_title));
			if($post->post_excerpt != ""){
				$post_excerpt = strip_tags(excerpt($length));
			}else{
				$post_excerpt = strip_tags(content($length));
			}	
			$guid = get_permalink($post->ID);
			
			$comments = $post->comment_count.' '.__('Comment',THEME_DOMAIN);
			if($post->comment_count > 1 || $post->comment_count ==0){
				$comments = $post->comment_count.' '.__('Comments',THEME_DOMAIN);
			}
			$posthtml .= '<li class="clearfix">';			
			$posthtml .= apply_filters('popular_post_thumb_image','');
			
			$meta_admin = apply_filters('load_popular_post_filter','');
			
			if($show_excerpt ==1){
				$post_content = "<p>".$post_excerpt."</p>";
			}
			
			if(isset($post->comment_date) && strtotime($post->comment_date) != 0) {
				$du = strtotime($post->comment_date);
			} else {
				$du = strtotime($post->post_date);
			}
			$fv = short_time_diff($du, current_time('timestamp')). " " . __('ago',THEME_DOMAIN);
			$fv = sprintf(__('%s',THEME_DOMAIN),$fv);
			if($popular_per == 'views' || $popular_per == 'dailyviews'){
				if($popular_per == 'dailyviews'): $views= get_post_meta($post->ID,'viewed_count_daily',true); else: $views= get_post_meta($post->ID,'viewed_count',true); endif;
				if($views <= 1){ $views = $views." ".__('view',THEME_DOMAIN); }else{ 
				
					$views = $views." ".__('views',THEME_DOMAIN);  
				
				}
				$posthtml .= '<div class="post_data"><h3><a href="'.$guid.'" title="'.$post_title.'">'.$post_title.'</a></h3><p>'.$meta_admin.'<span class="views">'.$views.'</span><span class="date">'.($fv).'</span></p>'.$post_content.'</div></li>';
			}else{
				$posthtml .= '<div class="post_data"><h3><a href="'.$guid.'" title="'.$post_title.'">'.$post_title.'</a></h3><p>'.$meta_admin.'<span class="views"> <a href="'.$guid.'#comments">'.($comments).'</a></span><span class="date">'.($fv).'</span></p>'.$post_content.'</div></li>';
			}	
		endwhile;
		echo $posthtml;exit;	
	else: ?>
	<p>
	  <?php _e('No Popular post fond.',THEME_DOMAIN);exit;?>
	</p>
	<?php
	endif;
	wp_reset_query();	
}

function short_time_diff( $from, $to = '' ) {
    $diff = human_time_diff($from,$to);
    $replace = array(
        'min' => __('min',THEME_DOMAIN),
        'mins' => __('mins',THEME_DOMAIN),
        'hour' => __('hours',THEME_DOMAIN),
        'hours' => __('hours',THEME_DOMAIN),
        'day' => __('day',THEME_DOMAIN),
        'days' => __('days',THEME_DOMAIN),
        'week' => __('week',THEME_DOMAIN),
        'weeks' => __('weeks',THEME_DOMAIN),
        'month' => __('month',THEME_DOMAIN),
        'months' => __('months',THEME_DOMAIN),
        'year' => __('year',THEME_DOMAIN),
        'years' => __('years',THEME_DOMAIN),
    );
    return strtr($diff,$replace);
}

/* category dropdown structured as our theme's requirement  */
function tmpl_wp_dropdown_cats($output,$r){

	$option_none_value = $r['option_none_value'];

	if ( ! isset( $r['pad_counts'] ) && $r['show_count'] && $r['hierarchical'] ) {
			$r['pad_counts'] = true;
	}

	$tab_index = $r['tab_index'];

	$tab_index_attribute = '';
	if ( (int) $tab_index > 0 ) {
			$tab_index_attribute = " tabindex=\"$tab_index\"";
	}
	
	$total_args = $r;
	unset( $total_args['name'] ); /* Avoid clashes with the 'name' param of get_terms(). */
	$categories = get_terms( $r['taxonomy'], $total_args );
	
	$name = esc_attr( $r['name'] );
	$class = esc_attr( $r['class'] );
	$id = $r['id'] ? esc_attr( $r['id'] ) : $name;
	

	if ( ! $r['hide_if_empty'] || ! empty( $categories ) ) {
			$output = "<select name='$name' id='$id' class='$class' $tab_index_attribute>\n";
	} else {
			$output = '';
	}
	if ( empty( $categories ) && ! $r['hide_if_empty'] && ! empty( $r['show_option_none'] ) ) {

			/**
			 * Filter a taxonomy drop-down display element.
			 *
			 * A variety of taxonomy drop-down display elements can be modified
			 * just prior to display via this filter. Filterable arguments include
			 * 'show_option_none', 'show_option_all', and various forms of the
			 * term name.
			 *
			 * @since 1.2.0
			 *
			 * @see wp_dropdown_categories()
			 *
			 * @param string $element Taxonomy element to list.
			 */
			$output .= "\t<option data-val='test' value='" . esc_attr( $option_none_value ) . "' selected='selected'>".__('Select Category',"templatic")."</option>\n";
	}

	if ( ! empty( $categories ) ) {

			if ( $r['show_option_all'] ) {

					/** This filter is documented in wp-includes/category-template.php */
					$selected = ( '0' === strval($r['selected']) ) ? " selected='selected'" : '';
					$output .= "\t<option data-val='test' value='0'$selected>$show_option_all</option>\n";
			}

			if ( $r['show_option_none'] ) {

					/** This filter is documented in wp-includes/category-template.php */
					$selected = selected( $option_none_value, $r['selected'], false );
					$output .= "\t<option data-val='test' value='" . esc_attr( $option_none_value ) . "'$selected>".__('Select Category',"templatic")."</option>\n";
			}
			
			if ( $r['hierarchical'] ) {
				$depth = $r['depth'];  // Walk the full depth.
			} else {
					$depth = -1; // Flat.
		}
		$output .= walk_category_dropdown_tree1( $categories, $depth, $r );
	}

	if ( ! $r['hide_if_empty'] || ! empty( $categories ) ) {
			$output .= "</select>\n";
}
return $output;
}


function walk_category_dropdown_tree1() {
		$args = func_get_args();
		// the user's options are the third parameter
		if ( empty($args[2]['walker']) || !is_a($args[2]['walker'], 'Walker') )
				$walker = new Tmpl_walker_cat_drop;
		else
				$walker = $args[2]['walker'];

		return call_user_func_array(array( &$walker, 'walk' ), $args );
}

class Tmpl_walker_cat_drop  extends Walker_CategoryDropdown
{

	public $tree_type = 'category';
	
	/**
	 * @see Walker::$db_fields
	 * @since 2.1.0
	 * @todo Decouple this
	 */
	public $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	public function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		$pad = str_repeat('&nbsp;', $depth * 3);

		/** This filter is documented in wp-includes/category-template.php */
		$cat_name = apply_filters( 'list_cats', $category->name, $category );
		
		$taxonomies = get_object_taxonomies( (object) array( 'post_type' => get_post_type(),'public'   => true, '_builtin' => true ));	
		
		/* get current category slug */
		$currentcat = get_query_var($taxonomies[0]);
		
		$output .= "\t<option data-val='test' class=\"level-$depth\" value=\"".$category->slug."\"";
		if ( $currentcat == $category->slug )
				$output .= ' selected="selected"';
		$output .= '>';
		$output .= $pad.$cat_name;
		if ( $args['show_count'] )
				$output .= '&nbsp;&nbsp;('. number_format_i18n( $category->count ) .')';
		$output .= "</option>\n";
	}
}
?>