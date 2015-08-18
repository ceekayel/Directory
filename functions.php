<?php
ob_start();
if (defined('WP_DEBUG') and WP_DEBUG == true){
    error_reporting(E_ALL ^ E_NOTICE);
} else {
    error_reporting(0);
}

if(file_exists(trailingslashit ( get_template_directory() ) . 'library/supreme.php'))
	require_once( trailingslashit ( get_template_directory() ) . 'library/supreme.php' ); // contain all classes and core function pf the framework
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		
	define( 'THEME_DOMAIN', 'templatic');
	define( 'ADMINDOMAIN', 'templatic-admin' );
	
define('TEMPLATE_URI',trailingslashit(get_template_directory_uri()));
define('TEMPLATE_DIR',trailingslashit(get_template_directory()));
$theme = new Supreme(); /* Part of the framework. */

if(is_admin() && strstr($_SERVER['REQUEST_URI'],'/wp-admin/')){
	require_once(get_template_directory().'/library/functions/admin-functions.php'); // framework functions file 
}else{
	if(file_exists(get_template_directory().'/library/functions/functions.php')){
		require_once(get_template_directory().'/library/functions/functions.php'); // framework functions file 
	}
}

if(!function_exists('tmpl_wp_is_mobile')){
	/*
	Check if device is mobile or not. Return true if mobile devie is detected
	*/
	if(function_exists('supreme_prefix')){
		$pref = supreme_prefix();
	}else{
		$pref = sanitize_key( apply_filters( 'hybrid_prefix', get_template() ) );
	}
	$theme_options = get_option($pref.'_theme_settings');
	$is_mobile_enabled= @$theme_options['tmpl_mobile_view'];
	if($is_mobile_enabled !=0){
		$is_mobile_enabled=1;
	}
	function tmpl_wp_is_mobile(){
		if ( (wp_is_mobile() && $is_mobile_enabled == 1  || (isset($_REQUEST['device']) && $_REQUEST['device']=='mobile')) && (!preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT'])) && !strstr('windows phone',$_SERVER['HTTP_USER_AGENT'])) && $is_mobile_enabled == 1){ /* if not desktop then return true */	
			return true;
		}else{	
			return false;
		}	
	}
}

add_action( 'after_setup_theme', 'supreme_theme_setup',11 );
function supreme_theme_setup() {

	/* Get action/filter hook prefix. */
	$prefix = supreme_prefix(); // Part of the framework, cannot be changed or prefixed.
	/* Begin localization */
	
	
	$locale = get_locale();

	if(is_admin()){
		
		if(file_exists(get_stylesheet_directory().'/languages/'.$locale.'.mo'))
		{ 
			load_textdomain( ADMINDOMAIN, get_stylesheet_directory().'/languages/admin-'.$locale.'.mo');
		}else{
			load_textdomain( ADMINDOMAIN, get_template_directory().'/languages/admin-'.$locale.'.mo');
		}
	}else{
		if(file_exists(get_stylesheet_directory().'/languages/'.$locale.'.mo'))
		{
			load_textdomain(THEME_DOMAIN, get_stylesheet_directory().'/languages/'.$locale.'.mo');
		}else{
			load_textdomain(THEME_DOMAIN, get_template_directory().'/languages/'.$locale.'.mo');
		}
	}
	/* End localization */
	
	/* Add framework menus. */
	add_theme_support( 'supreme-core-menus', array( // Add core menus.
		'primary',
		'secondary',
		'subsidiary'
		) );
	/* Register additional menus */
	
	/* Add framework sidebars */
	/* add sidebar support in theme , want to remove from child theme as remove theme support from child theme's functions file */
	
	add_theme_support( 'supreme-core-sidebars', array(
				'header',
				'mega_menu',
				'secondary_navigation_right',
				'home-page-banner',
				'home-page-above-content',
				'home-page-content',
				'home-page-below-content',
				'before-content',
				'entry',
				'after-content',
				'front-page-sidebar',
				'author-page-sidebar',
				'post-listing-sidebar',
				'post-detail-sidebar',
				'primary-sidebar',
				'after-singular',
				'subsidiary',
				'subsidiary-2c',
				'subsidiary-3c',
				'contact_page_widget',
				'advance_search_sidebar',
				'contact_page_sidebar',
				'supreme_woocommerce',
				'home-page-above-footer',
				'footer'
				) );
	/* add theme support for menu */
	
	/* Add framework menus. */
	add_theme_support( 'supreme-core-menus', array( // Add core menus. 
				'primary',
				'secondary',
				'footer',		
	) );
	add_theme_support( 'post-formats', array(
		'aside',
		'audio',
		'gallery',
		'image',
		'link',
		'quote',
		'video'
		) );
	add_post_type_support( 'post', 'post-formats' ); // support post format
	add_post_type_support( 'portfolio', 'post-formats' ); // for portfolio slides option in slider
	add_theme_support( 'supreme_banner_slider' ); // work with home page banner slider
	add_theme_support( 'supreme-show-commentsonlist' ); // to show comments counting on listing
	add_theme_support( 'tmpldir-core-widgets' ); // to support widgets 
	add_theme_support( 'supreme-core-shortcodes' ); // to support short codes
	add_theme_support("home_listing_type_value");
	add_theme_support("taxonomy_sorting");
	add_theme_support("google_map"); // Show gogole map if location manager active
	add_theme_support("tevolution_my_favourites"); // Show my favourites & add to favourites with tevolution
	add_theme_support("tevolution_author_listing"); // show author listing widget with tevolution
	add_theme_support("map_fullwidth_support");
	add_theme_support("slider-post-inslider");
	add_theme_support("slider-post-content");
	
	/* theme support for default page views */
	add_theme_support("tmpl_show_pageviews");
	/* Home page settings to show the different post types listings on home page */
	add_theme_support("theme_home_page");

	add_action('init','remove_home_page_feature_listing_filter');
	/* Add theme support for framework layout extension. */
	add_theme_support( 'theme-layouts', array( // Add theme layout options.
		'1c',
		'2c-l',
		'2c-r',
		) );
	/* Add theme support for other framework extensions */
	
	//add_theme_support( 'custom-header' );
	add_theme_support( 'post-stylesheets' );
	add_theme_support( 'loop-pagination' );
	add_theme_support( 'breadcrumb-trail' );
	add_theme_support( 'supreme-core-theme-settings', array( 'footer' ) );
    if ( !current_user_can('edit_posts') ) {
		//show_admin_bar(false);
	}
	/* Add theme support for WordPress features. */
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'add-listing-to-favourite' );
	add_theme_support("tev_taxonomy_sorting_opt");
	//add_theme_support("tev_taxonomy_excerpt_opt");
	/* Add theme support for WordPress background feature */
	add_theme_support( 'custom-background', array (
		'default-color' => '',
		'default-image' => '',
		'wp-head-callback' => 'supreme_custom_background_callback',
		'admin-head-callback' => '',
		'admin-preview-callback' => ''
	));
	
		
	/* to provide a option of posts per slide */
	add_theme_support('postperslide');	
	/*

	/* Changes tev general settings and options should be stay same in new version */

	
	/* Modify excerpt more */
	add_filter('excerpt_length', 'supreme_excerpt_length',11);
	add_filter('excerpt_more', 'new_excerpt_more');
	/* Wraps <blockquote> around quote posts. */
	add_filter( 'the_content', 'supreme_quote_post_content' );
	add_filter( 'embed_defaults', 'supreme_embed_defaults' ); // Set default widths to use when inserting media files
	add_filter( 'sidebars_widgets', 'supreme_disable_sidebars' );
	/* Add additional layouts */
	add_filter( 'theme_layouts_strings', 'supreme_theme_layouts' );
	/* Load resources into the theme. */
	add_action( 'wp_enqueue_scripts', 'tmpl_theme_css_scripts' ,20);
	/* Register new image sizes. */
	add_action( 'init', 'supreme_register_image_sizes' );
	
	/* Set theme specific options */
	add_action( 'admin_init', 'tmpl_set_themesettings' );
	/* Assign specific layouts to pages based on set conditions and disable certain sidebars based on layout choices. */
	add_action( 'template_redirect', 'supreme_layouts' );
	/* WooCommerce Functions. */
	if ( function_exists( 'is_woocommerce' ) ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
	}
	/* Set content width. */
	supreme_set_content_width( 600 );
	/****** Theme related files ******/
	
	/* add action to save tev general settings for theme design */

	if(file_exists(get_stylesheet_directory()."/functions/childtheme-functions.php")){
		include_once(get_stylesheet_directory()."/functions/childtheme-functions.php"); //child theme directory functions file
	}
	if ( get_header_textcolor()=='blank') { ?>
	<style type="text/css">
	#site-title, #site-description {
		text-indent: -99999px;
	}
	</style>
	<?php }
	remove_action("wp_head", "supreme2_view_counter");
	add_filter('tev_gravtar_size','tev_gravtar_size_hook');
	global $pagenow;
	
	if(is_admin() && is_writable(WP_CONTENT_DIR."/plugins") && is_readable(get_template_directory()) && ($pagenow=='themes.php' || $pagenow=='theme-install.php') ){
		$tev_zip = get_template_directory_uri()."/Tevolution.zip";
		$tev_zip_path = get_template_directory()."/Tevolution.zip";
		$dir_zip = get_template_directory_uri()."/Tevolution-Directory.zip";
		$dir_zip_path = get_template_directory()."/Tevolution-Directory.zip";
		$loc_zip = get_template_directory_uri()."/Tevolution-LocationManager.zip";
		$loc_zip_path = get_template_directory()."/Tevolution-LocationManager.zip";
		
		$target_path1 = get_tmpl_plugin_directory()."Tevolution.zip";  // change this to the correct site path
		$target_path2 = get_tmpl_plugin_directory()."Tevolution-Directory.zip";  // change this to the correct site path
		$target_path3 = get_tmpl_plugin_directory()."Tevolution-LocationManager.zip";  // change this to the correct site path
		
		$plug_path1 = "Tevolution/templatic.php";  // change this to the correct site path
		$plug_path2 = "Tevolution-Directory/directory.php";  // change this to the correct site path
		$plug_path3 = "Tevolution-LocationManager/location-manager.php";  // change this to the correct site path		
	
		$on_go = get_option('tev_on_go');
		if(!$on_go){ $on_go =0; }
		/* get current theme name */
		$theme_name = wp_get_theme();

		if ( is_admin() && isset($_GET['activated'] ) && $pagenow == 'themes.php' && $on_go == 0 && $theme_name == 'Directory 2') {
		/*hide listing install sample listing tab when directroy theme is activated*/
		update_option( 'hide_listing_ajax_notification', true );
		if(file_exists($tev_zip_path))
			zip_copy( $tev_zip, $target_path1, $plug_path1 );
		if(file_exists($dir_zip_path))
			zip_copy( $dir_zip, $target_path2, $plug_path2 );
		if(file_exists($loc_zip_path))
			zip_copy( $loc_zip, $target_path3, $plug_path3, $add_msg =1 );
		}
	}
	/* code to auto extract plugins END*/
	add_filter( 'sidebars_widgets', 'directory_disable_sidebars' );
} 
/* change gravatra size on author page */
function tev_gravtar_size_hook(){
	return 352;
}
/* Show authentication message */
function dir_one_click_install(){
	echo "<div id='ajax-notification' class='updated'><p><span style='color:red;'>AUTHENTICATION REQUIRED:</span>".__('Your server does not allow automated plugin activation so you will have to activate the plugins manually one by one.',ADMINDOMAIN)."</p>  </div>";
}

/*
	To update option , is theme is support woocommerce or not 
*/
function tmpl_set_themesettings(){
    
	/* update woo commerce options */
	$currrent_theme_name = wp_get_theme();
	$templatic_woocommerce_themes = get_option('templatic_woocommerce_themes');
	$templatic_woocommerce_ = str_replace(',','',get_option('templatic_woocommerce_themes'));
	if(!strstr(trim($templatic_woocommerce_) ,trim($currrent_theme_name))):
		update_option('templatic_woocommerce_themes',$templatic_woocommerce_themes.",".$currrent_theme_name);
	endif;	

	/* update full width map settings */
	$templatic_settings = get_option('templatic_settings');
	$tmpdata = get_option('templatic_settings');
	$map_settngs = get_option('map_settngs');
	
	if((!isset($tmpdata['google_map_full_width']) || !isset($tmpdata['direction_map']) || @$map_settngs['google_map_full_width'] =='yes' || @$map_settngs['direction_map'] =='yes') && !$_POST){
		$tmpdata['google_map_full_width'] = 'yes';
		
		if(@$tmpdata['direction_map'] !='No' || @$tmpdata['direction_map'] =='' || @$map_settngs['direction_map'] =='yes'){ 
			$tmpdata['direction_map'] = 'yes';
			$map_data['direction_map'] ='';
			if(is_array($map_settngs) && !empty($map_settngs)){
				update_option('map_settngs',array_merge( $map_settngs, $map_data ) );
			}else{
				update_option('map_settngs',$map_data);
			}
		}
		
		if(!empty($templatic_settings)):
			update_option('templatic_settings',array_merge( $templatic_settings, $tmpdata ));
		else:
			update_option('templatic_settings',$tmpdata );
		endif;
		if(function_exists('supreme_prefix')){
			$pref = supreme_prefix();
		}else{
			$pref = sanitize_key( apply_filters( 'hybrid_prefix', get_template() ) );
		}
		
		$theme_options = get_option($pref.'_theme_settings');
		$theme_options['enable_comments_on_post'] = 1; 
		update_option($pref.'_theme_settings',$theme_options);
	}
	
	global $pagenow;
	/*change auto install text*/
	if($pagenow == 'themes.php')
	{
		add_action('admin_footer','auto_install_text');
	}
	
}
/*
	Load theme's styles and scripts
*/
function tmpl_theme_css_scripts() {
	
	/* Load themes style.css*/
	wp_enqueue_style( 'directory-css', get_stylesheet_uri());
	
	if ( !tmpl_wp_is_mobile()) {
		wp_enqueue_style( 'tmp-responsive',  trailingslashit ( get_template_directory_uri() ) . 'css/responsive.css',array('directory-css'));
	}
	
	/* load light box js on detail page and on pages only */	
	if(is_single() || (is_page() && !is_front_page()))
		wp_enqueue_script( 'templatic_colorbox', trailingslashit ( get_template_directory_uri() ) . 'js/jquery.colorbox-min.js', array( 'jquery' ), '20120606', true );

	/* for WooCommerce */
	if(is_search()){
		wp_enqueue_script( 'tmpl_search_view', trailingslashit ( get_template_directory_uri() ) . 'js/search-view.js', array( 'jquery' ), '20120606', true );
	}
	
	/* add mobile css */
	
	/* include parent css in child theme*/
	if( file_exists(get_stylesheet_directory().'/theme-style.css'))
	{
		wp_enqueue_style( 'tmpl_dir_css', trailingslashit ( get_template_directory_uri() ) . 'style.css');
	}
	if(tmpl_wp_is_mobile()){
		wp_enqueue_style( 'tmpl_mobile_view', trailingslashit ( get_template_directory_uri() ) . 'css/search-view.js',array('directory-css'));
		
		if( file_exists(get_stylesheet_directory().'/theme-mobile-style.css'))
		{
			wp_enqueue_style( 'tmpl_child_mobile_view', trailingslashit ( get_stylesheet_directory_uri() ) . 'theme-mobile-style.css');
		}
	}
	else
	{
		if( file_exists(get_stylesheet_directory().'/theme-style.css'))
		{
			wp_enqueue_style( 'tmpl_childtheme_view', trailingslashit ( get_stylesheet_directory_uri() ) . 'theme-style.css');
		}
	}
	
	/* rtl.css */
	$supreme2_theme_settings = get_option(supreme_prefix().'_theme_settings');
	if ( isset($supreme2_theme_settings['rtlcss']) && $supreme2_theme_settings['rtlcss']==1 && !is_admin() ) { 
		/* include right to left css */
		if(file_exists(get_stylesheet_directory().'/theme-style.css')){
			wp_enqueue_style( 'tmpl_rtl_css', get_template_directory_uri().'/rtl.css',array('tmpl_dir_css') );
			if(file_exists(get_stylesheet_directory().'/rtl.css')){
				wp_enqueue_style( 'tmpl_child_rtl_css', get_stylesheet_directory_uri().'/rtl.css',array('tmpl_rtl_css') );
			}
		}else{
			wp_enqueue_style( 'tmpl_rtl_css', get_template_directory_uri().'/rtl.css',array('tmp-responsive') );
		}
	 
	}
	   
	/* mobile view - gallery work only with un minified js*/
	
	if(tmpl_wp_is_mobile()){
		wp_enqueue_script( 'tmpl-scripts', trailingslashit ( get_template_directory_uri() ) . 'js/_supreme.js', array( 'jquery' ), '20120606', true );
		//Detect special conditions devices
		$iPod    = stripos($_SERVER['HTTP_USER_AGENT'],"iPod");
		$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
		$iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");

		//do something with this information
		if( !$iPod && !$iPhone){
			wp_enqueue_script( 'templatic-faskclick-js', '//cdnjs.cloudflare.com/ajax/libs/fastclick/1.0.0/fastclick.min.js', array( 'jquery' ), '20120606', true );
		}
	}else{
		wp_enqueue_script( 'tmpl-scripts', trailingslashit ( get_template_directory_uri() ) . 'js/_supreme.min.js', array( 'jquery' ), '20120606', true );
	}
	
	
	if( function_exists( 'is_woocommerce') ) {
		wp_dequeue_style( 'woocommerce_frontend_styles' );
	}
}
/**
 * This is a fix for when a user sets a custom background color with no custom background image.
 */
function supreme_custom_background_callback() {
	/* Get the background image. */
	$image = get_background_image();
	/* If there's an image, just call the normal WordPress callback. We won't do anything here. */
	if ( !empty( $image ) ) {
		_custom_background_cb();
		return;
	}
	/* Get the background color. */
	$color = get_background_color();
	/* If no background color, return. */
	if ( empty( $color ) )
		return;
	/* Use 'background' instead of 'background-color'. */
	$style = "background: #{$color};";
?>
<style type="text/css">
body.custom-background {
<?php echo trim( $style );
?>
}
</style>
<?php
}

/*
	Registers additional image size 'supreme-thumbnail'.
*/
function supreme_register_image_sizes() {
	
	if(get_option('tmpl_added_default_image_sizes') != 1){
		
		if(get_option('medium_size_w')!=0)
			update_option('medium_size_w',0);
		if(get_option('medium_size_h')!=0)
			update_option('medium_size_h',0);
			
		if(get_option('large_size_w')!=0)
			update_option('large_size_w',0);
		if(get_option('large_size_h')!=0)
			update_option('large_size_h',0);
		
		update_option('tmpl_added_default_image_sizes',1);
	}		
		
	add_image_size( 'slider-thumbnail', '350', '350', true );
}
/*
	Overwrites the default widths for embeds.  This is especially useful for making sure videos properly expand the full width on video pages. 
*/
function supreme_embed_defaults( $args ) {
	$args['width'] = 600;
	if ( current_theme_supports( 'theme-layouts' ) ) {
		$layout = theme_layouts_get_layout();
		if ( 'layout-3c-l' == $layout || 'layout-3c-r' == $layout || 'layout-3c-c' == $layout || 'layout-hl-2c-l' == $layout || 'layout-hl-2c-r' == $layout || 'layout-hr-2c-l' == $layout || 'layout-hr-2c-r' == $layout )
		
			$args['width'] = 280;
			
		elseif ( 'layout-1c' == $layout )
		
			$args['width'] = 920;
	}
	return $args;
}

if(!function_exists('reverse_strrchr')){
	function reverse_strrchr($haystack, $needle, $trail) {
		return strrpos($haystack, $needle) ? substr($haystack, 0, strrpos($haystack, $needle) + $trail) : false;
	}
}
	
/*
	check if woo commerce is active or not 
*/
if(!function_exists('check_if_woocommerce_active')){
	function check_if_woocommerce_active(){
		$plugins = wp_get_active_and_valid_plugins();
		$flag ='';
		foreach($plugins as $plugins){
			if (strpos($plugins,'woocommerce.php') !== false) {
				$flag = 'true';
				break;
			}else{
				 $flag = 'false';
			}
		}
		return $flag;
	}
}
/* add theme support of woo-commerce */
if(function_exists('check_if_woocommerce_active')){
	$is_woo_active = check_if_woocommerce_active();
	if($is_woo_active == 'true'){
		add_theme_support( 'woocommerce' );
	}
}


/* add_action('supreme_aftercontentevent');
add_action('supreme_aftercontentlisting'); */
function remove_home_page_feature_listing_filter()
{
	$show_on_front=get_option('show_on_front');
	if($show_on_front=='page'){
		remove_filter('pre_get_posts', 'home_page_feature_listing');
	}
}
add_filter('slider_image_thumb','slider_thumbnail');
function slider_thumbnail()
{
	return 'slider-thumbnail';
}	
/*
	To change the comment field title.
*/
add_filter( 'comment_form_defaults', 'comment_form_defaults_comment_title',11 );
function comment_form_defaults_comment_title( $arg ) {
	$arg['comment_field'] = '<p class="comment-form-comment"><label for="comment">'.__('Review',THEME_DOMAIN).'</label> <textarea aria-required="true" rows="8" cols="45" name="comment" id="comment"></textarea></p>';
	return $arg;
}
/*
	To fetch fields after comment box.
*/
add_filter( 'comment_form_defaults', 'comment_form_defaults',100 );
function comment_form_defaults( $arg ) {
	global $post,$current_user;
	if(!$current_user->ID)
	{
		$fields = $arg['fields'];
		$arg['fields'] = '';
		if(@$fields['author'] != '')
		{
			$arg['comment_field'] .= '<div class="comment_column2">'.$fields['author'].$fields['email'].$fields['url'].'</div>';
		}
	}
	if($post->post_type != 'post')
		$arg['label_submit'] = __('Post Review',THEME_DOMAIN);
	return $arg;
}
add_action( 'init', 'directory_register_image_sizes' );
function directory_register_image_sizes(){	
	add_image_size( 'thumbnail', 250, 165, true );
	if(get_option('thumbnail_size_w')!=250)
		update_option('thumbnail_size_w',250);
	if(get_option('thumbnail_size_h')!=165)
		update_option('thumbnail_size_h',165);
	
}
/**
 * Disables sidebars based on layout choices.
 *
 * @since 0.1
 */
function directory_disable_sidebars( $sidebars_widgets ) {	
	
	global $wpdb,$wp_query,$post;
	//fetch the current page taxonomy
	if(is_tax() || is_category())
		$current_term = $wp_query->get_queried_object();	
	
	
	if ( current_theme_supports( 'theme-layouts' ) && !is_admin() ) {
	
		if ( 'layout-1c' == theme_layouts_get_layout() ) {
				
				$taxonomy=get_query_var( 'taxonomy' );
				if(is_tax()){
					$sidebars_widgets[$taxonomy.'_listing_sidebar'] = false;
					$sidebars_widgets[$taxonomy.'_tag_listing_sidebar'] = false;
				}
				if(is_single()){
					$sidebars_widgets[get_post_type().'_detail_sidebar'] = false;
				}
				if(is_page())
				{
					$post_type=get_post_meta($post->ID,'submit_post_type',true);
					if($post_type!='')
					{
						$sidebars_widgets['add_'.$post_type.'_submit_sidebar'] = false;
					}
				}
				if(is_home())
				{
					$sidebars_widgets['front_sidebar'] = false;
				}
			
			
		}
	}
	return $sidebars_widgets;
}
add_filter('tev_review_text','review_text_hook',11); // filter to remove space.
/* fun to remove review text */
	function review_text_hook($review){
		$review ="&nbsp;";
		return $review;
	}
	
  /*
	include font awesome css.
    */
   add_action( 'wp_enqueue_scripts', 'theme_css_on_init' ); // include fonts awesome
   
   function theme_css_on_init() {
       /* Register our style sheet. */
	   if(is_ssl()){ $http = "https://"; }else{ $http ="http://"; }
	   
       wp_register_style( 'fontawesomecss', $http.'maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' );
	   wp_enqueue_style( 'fontawesomecss' );
	   $theme = wp_get_theme();
	  
   }
    /* Remove comment icon from wp-adminbar */
   function remove_comments(){
        global $wp_admin_bar;
        $wp_admin_bar->remove_menu('comments');
	}

add_action( 'wp_before_admin_bar_render', 'remove_comments' );
add_action('close-post-content','post_close_post_content');
function post_close_post_content(){
	global $post;
	if(is_single() && get_post_type()=='post' && function_exists('tevolution_socialmedia_sharelink')){
		tevolution_socialmedia_sharelink($post);	
	}
}

add_filter( 'attachment_link', 'attachment_link', 20, 2 );
add_action( 'wp_footer', 'templatic_wp_head' );

function templatic_wp_head() {
	
	/*include admin_style.css for color customizer for backend.*/
	if(isset($_REQUEST['wp_customize']) && $_REQUEST['wp_customize']=='on')
	{
		wp_enqueue_style( 'admin_style', trailingslashit ( get_template_directory_uri())."css/admin_style.css" );
	}
	if(!is_home() && !is_front_page() && !is_search() && !is_archive() && !is_plugin_active('Templatic-Shortcodes/templatic_shortcodes.php') && !is_404() && !isset($_REQUEST['page']) && !isset($_REQUEST['ptype']))
	{
	?>
	<script type="text/javascript">
	// <![CDATA[
		var $shorcode_gallery_popup = jQuery.noConflict();
		$shorcode_gallery_popup(document).ready(function($){
			$shorcode_gallery_popup(".gallery").each(function(index, obj){
				var galleryid = Math.floor(Math.random()*10000);
				$shorcode_gallery_popup(obj).find("a").colorbox({rel:galleryid, maxWidth:"95%", maxHeight:"95%"});
			});
			$shorcode_gallery_popup("a.lightbox").colorbox({maxWidth:"95%", maxHeight:"95%"});
		});
	// ]]>
	</script>
	<?php
	}

}
/*
	Return the plugin directory path
*/
if(!function_exists('get_tmpl_plugin_directory')){
function get_tmpl_plugin_directory() {
	 return WP_CONTENT_DIR."/plugins/";
}
}

/* 	Add foundation basic .js
	Here with different function because we needs to add in footer with no js conflicts,
	there should not same other script load from plug-in */
add_action( 'wp_footer', 'tmpl_foundation_script',99 );

/* add script in footer*/
if(!function_exists('tmpl_foundation_script')){
	function tmpl_foundation_script(){ ?>
	<script id="tmpl-foundation" src="<?php echo get_template_directory_uri(); ?>/js/foundation.min.js"> </script>	
<?php
	}
}

/* Include foundation js end */

function attachment_link( $link, $id ) {
	/* The light box doesn't function inside feeds obviously, so don't modify anything */
	if ( is_feed() || is_admin() )
		return $link;

	$post = get_post( $id );

	if ( 'image/' == substr( $post->post_mime_type, 0, 6 ) )
		return wp_get_attachment_url( $id );
	else
		return $link;
}
/* Show the Renew/Edit button on author page */
add_action('tmpl_before_entry_end','tmpl_author_page_editrenew_link');

function tmpl_author_page_editrenew_link(){
	if(is_author() && (!isset($_REQUEST['sort']))){
		do_action('templ_show_edit_renew_delete_link');	
	}
}
/*filter to remove slider while submit a listing*/
add_filter('intermediate_image_sizes','unset_slider_thumnail_size');
function unset_slider_thumnail_size($image_sizes)
{
	foreach ($image_sizes as $key => $value){
		if ($value == apply_filters('slider_image_thumb','slider-thumbnail')) {
			unset($image_sizes[$key]);
		}
	}
	return $image_sizes;
}
/*generate thumbnail for slider*/
add_action('generate_slider_thumbnail','generate_slider_thumbnail');
function generate_slider_thumbnail($post)
{
	require_once(ABSPATH . 'wp-admin/includes/image.php');
	$post_id = $post->ID;
	$args = array( 'post_type' => 'attachment', 'numberposts' => -1, 'post_status' => 'attachment' , 'post_parent' => $post_id); 
	$attachments = get_posts( $args );
	if ($attachments) {
		foreach ( $attachments as $attach_post ) {
			$file = get_attached_file( $attach_post->ID );
			wp_update_attachment_metadata( $attach_post->ID, wp_generate_attachment_metadata( $attach_post->ID, $file ) );
		}
	}	
}
add_action('tmpl_open_main','tmpl_open_main_area_widget');
/* to show the widget area on front page only */
function tmpl_open_main_area_widget(){ 
	if(is_active_sidebar('home-page-above-content-all-pages')){ ?>
		 <div class="home_page_full_content">
          	<?php dynamic_sidebar('home-page-above-content-all-pages'); ?>
         </div>
	<?php }
}

/* above home page content widget area */
add_action('tmpl_open_wrap','tmpl_open_wrap_widget');

function tmpl_open_wrap_widget(){
	if(is_front_page() && is_active_sidebar('home-page-above-content')){ ?>
		 <div class="home_page_full_content columns">
          	<?php dynamic_sidebar('home-page-above-content'); ?>
         </div>
	<?php }
}
/*set contact us widget in widget area.*/
add_action('init','add_contact_us_widget');
function add_contact_us_widget()
{
	if(get_option('set_contact_widget') != 'save_contact_us_widget')
	{
		$sidebars_widgets = get_option('sidebars_widgets');
		$supreme_contact_widget = array();
		$supreme_contact_widget[1] = array(
							"title"			=>	'Send us a message',
							"address"		=>	'230 Vine Street And locations throughout Old City, Philadelphia, PA 19106',
							"map_height"	=>	400,
							);						
		$supreme_contact_widget['_multiwidget'] = '1';
		update_option('widget_supreme_contact_widget',$supreme_contact_widget);
		$supreme_contact_widget = get_option('widget_supreme_contact_widget');
		krsort($supreme_contact_widget);
		foreach($supreme_contact_widget as $key1=>$val1)
		{
			$supreme_contact_widget_key = $key1;
			if(is_int($supreme_contact_widget_key))
			{
				break;
			}
		}
		$sidebars_widgets["contact_page_widget"] = array_merge(array($sidebars_widgets["contact_page_widget"]),array("supreme_contact_widget-{$supreme_contact_widget_key}"));
		update_option('sidebars_widgets',$sidebars_widgets);  //save widget informations 
		update_option('set_contact_widget','save_contact_us_widget');
	}
}
/**
 * @return bool true|false
 */
 
if(!function_exists('twp_is_mobile')){
	function twp_is_mobile() {
		static $is_mobile;

		if ( isset($is_mobile) )
			return $is_mobile;

		if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
			$is_mobile = false;
		} elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
			|| strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
				$is_mobile = true;
		} else {
			$is_mobile = false;
		}

		return $is_mobile;
	}
}
if(!function_exists('tmpl_wp_is_mobile')){
	/*
	Check if device is mobile or not. Return true if mobile devie is detected
	*/
	function tmpl_wp_is_mobile(){
		if(function_exists('supreme_prefix')){  
			$pref = supreme_prefix();
		}else{
			$pref = sanitize_key( apply_filters( 'hybrid_prefix', get_template() ) );
		}
		
		$theme_options = get_option($pref.'_theme_settings');
		
		$is_mobile_enabled= @$theme_options['tmpl_mobile_view'];
		if($is_mobile_enabled !=0 || $is_mobile_enabled ==''){
			$is_mobile_enabled=1;
		}

		
			if($is_mobile_enabled==1){
				if ( (twp_is_mobile() || (isset($_REQUEST['device']) && $_REQUEST['device']=='mobile')) && (!preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT'])) && !strstr('windows phone',$_SERVER['HTTP_USER_AGENT']))){ /* if not desktop then return true */	
					return true;
				}else{
					return false;
				}	
			}else{
				return false;
			}
	}
}

/* function for adding meta tags for facebook */
add_action('wp_head','tmpl_directory_fb_metatags');
if(!function_exists('tmpl_directory_fb_metatags')){
	function tmpl_directory_fb_metatags(){
		
		if(is_single() && !is_plugin_active('wordpress-seo/wp-seo.php')){
			global $post;
			
			
			$permalink = get_permalink( $post->ID ); 
			
			 /* Pass the post image in facebook share url  - use "large" only */
			if(function_exists('bdw_get_images_plugin'))
			{
				$large_image_url = bdw_get_images_plugin($post->ID,'large');
				if (!empty($large_image_url) ) {
				?>
					<meta property="og:image" content="<?php echo $large_image_url[0]['file']; ?>" />
				<?php
					
				}
			}
			?>
			<meta property="og:title" content="<?php echo $post->post_title; ?>" />
			<meta property="og:url" content="<?php echo $permalink; ?>" />
			<meta property="og:description" content="<?php echo strip_tags($post->post_content); ?>" />
			
			<?php
		}
	}
}

/* to fetch the front page page template - v 1.1.2 theme-functions.php file */
global $wpdb;
$pageid='';
if(!get_option('directory_frontpage')){
	$wp_pages = get_pages(array(
	'meta_key' => '_wp_page_template',
	'meta_value' => 'page-templates/front-page.php'
	));
	foreach($wp_pages as $page){
		$pageid = $page->ID;
	}
	if(!$pageid){
		$page_meta = array('_wp_page_template'=>'page-templates/front-page.php','Layout'=>'2c-l'); 
		$page_info_arr[] = array('post_title'=>'Front page',
								'post_content'=>'',
								'post_meta'=> $page_meta);
		if(function_exists('set_page_info_autorun'))
			set_page_info_autorun(@$pages_array,$page_info_arr); /* function to save.autosave the pages */
		$wp_pages = get_pages(array(
		'meta_key' => '_wp_page_template',
		'meta_value' => 'page-templates/front-page.php'
		));
		foreach($wp_pages as $page){
		 $pageid = $page->ID;
		}
	}
	update_option('directory_frontpage',$pageid);
}else{
	$pageid = get_option('directory_frontpage');
}
/* show custom home page for this theme*/
if(get_option('show_on_front') && !get_option('page_update_first')){
	update_option('show_on_front','page');
	update_option('page_on_front', $pageid);
	update_option('page_update_first', 1);
}

/* Hook to change the height of croausal slider image  */
add_filter('carousel_slider_height', 'responsive_crousal_height');
/* return height for crausal slider image */
function responsive_crousal_height($height){
	$height = 400;
	return $height;
}
/* set default hight and width of slider images */
add_filter('supreme_slider_width','supreme_slider_width_',11);
add_filter('supreme_slider_height','supreme_slider_height',11);
function supreme_slider_height($height){
	return 300;
}
function supreme_slider_width_($width){
	return 300;
}

/* return the result of tevolution is activated or not */
if(!function_exists('is_tevolution_active')){
	function is_tevolution_active(){
		if(is_plugin_active('Tevolution/templatic.php')){
			return true;
		}else{
			return false;
		}
	}
}

if(!function_exists('templatic_get_theme_data')){
	/* return the theme data
	*/
	function templatic_get_theme_data( $theme_file ) {
		$theme = new WP_Theme( basename( dirname( $theme_file ) ), dirname( dirname( $theme_file ) ) );

		$theme_data = array(
			'Name' => $theme->get('Name'),
			'URI' => $theme->display('ThemeURI', true, false),
			'Description' => $theme->display('Description', true, false),
			'Author' => $theme->display('Author', true, false),
			'AuthorURI' => $theme->display('AuthorURI', true, false),
			'Version' => $theme->get('Version'),
			'Template' => $theme->get('Template'),
			'Status' => $theme->get('Status'),
			'Tags' => $theme->get('Tags'),
			'Title' => $theme->get('Name'),
			'AuthorName' => $theme->get('Author'),
		);

		foreach ( apply_filters( 'extra_theme_headers', array() ) as $extra_header ) {
			if ( ! isset( $theme_data[ $extra_header ] ) )
				$theme_data[ $extra_header ] = $theme->get( $extra_header );
		}

		return $theme_data;
	}
}
/* Add extra file headers for themes. */
add_filter( 'extra_theme_headers', 'supreme_extra_theme_headers' );
/**
 * Creates custom theme headers.  This is the information shown in the header block of a theme's 'style.css' 
 * file.  Themes are not required to use this information, but the framework does make use of the data for 
 * displaying additional information to the theme user.
 *
 */
function supreme_extra_theme_headers( $headers ) {
	/* Add support for 'Template Version'. This is for use in child themes to note the version of the parent theme. */
	if ( !in_array( 'Template Version', $headers ) )
		$headers[] = 'Template Version';
	/* Add support for 'License'.  Proposed in the guidelines for the WordPress.org theme review. */
	if ( !in_array( 'License', $headers ) )
		$headers[] = 'License';
	/* Add support for 'License URI'. Proposed in the guidelines for the WordPress.org theme review. */
	if ( !in_array( 'License URI', $headers ) )
		$headers[] = 'License URI';
	/* Add support for 'Support URI'.  This should be a link to the theme's support forums. */
	if ( !in_array( 'Support URI', $headers ) )
		$headers[] = 'Support URI';
	/* Add support for 'Documentation URI'.  This should be a link to the theme's documentation. */
	if ( !in_array( 'Documentation URI', $headers ) )
		$headers[] = 'Documentation URI';
	/* Return the array of custom theme headers. */
	return $headers;
}
add_action( 'widgets_init', 'tmpldir_register_sidebars' );

/**
	It will return the all sidebar/widget area of themes 
 */
function supreme_get_sidebars() {
	/* Set up an array of sidebars. */
	global $theme_sidebars,$plugin_sidebars;
	if(empty($theme_sidebars))
	{
		$theme_sidebars = array(''); 
	}
	if(empty($plugin_sidebars))
	{
		$plugin_sidebars = array(''); 
	}
	
	$sidebars = array(
		'header' => array(
			'name' =>	apply_filters('supreme_header_right_title',__( 'Header', ADMINDOMAIN )),
			'description' =>	apply_filters('supreme_header_right_description',__( "The area is located on the right side of your header (between primary and secondary navigation).", ADMINDOMAIN )),
		),
		'secondary_navigation_right' => array(
			'name' =>	__( 'Secondary Navigation', ADMINDOMAIN ),
			'description' =>	__( 'Widgets placed inside this area will appear on the right side of your secondary navigation bar (the one below the logo). The simple "Search" widget works best here.', ADMINDOMAIN ),
		),
		'home-page-banner' => array(
			'name' =>	apply_filters('supreme_home_page_banner_title',__( 'Homepage Banner', ADMINDOMAIN )),
			'description' =>	apply_filters('supreme_home_page_banner_description',__( "This area usually displays the big slider or the map. It's located between the header and the Homepage - Main Content area.", ADMINDOMAIN )),
		),	
		
		'home-page-content' => array(
			'name' =>	__( 'Homepage - Main Content', ADMINDOMAIN ),
			'description' =>	apply_filters('supreme_home_page_widget_area_description',__('This area appears alongside the homepage sidebar. It&#39;s located between the homepage slider and subsidiary areas.',ADMINDOMAIN)),
		),	
		'front-page-sidebar' => array(
		'name' =>  __( 'Homepage Sidebar', ADMINDOMAIN ),
		'description' => __( 'The area is located below the homepage slider and above subsidiary areas. It shows alongside Homepage - Main Content area.', ADMINDOMAIN )
		),
		
		
		'post-listing-sidebar' => array(
		'name' =>  __( 'Post Category Page Sidebar', ADMINDOMAIN ),
		'description' => __( 'This sidebar will show on category pages for the built-in WordPress Posts.', ADMINDOMAIN )
		),
		
		'post-detail-sidebar' => array(
		'name' =>  __( 'Post Detail Page Sidebar', ADMINDOMAIN ),
		'description' => __( 'This sidebar will show on detail (single) Post pages.', ADMINDOMAIN )
		),
		
		'after-content' => array(
			'name' =>	__( 'All Pages - Below Content',ADMINDOMAIN ),
			'description' =>	__( "The widget area is located below the main content on all pages. For example, on detail pages you'll find it below the comments and related posts.", ADMINDOMAIN ),
		),
		'before-content' => array(
			'name' =>	__( 'All Pages - Above Content', ADMINDOMAIN ),
			'description' =>	__( "The widget area is located above the main content on all pages. For example, on detail pages you'll find it above the title.", ADMINDOMAIN ),
		),
		'entry' => array(
			'name' =>	__( 'Post Detail Page - Before Description', ADMINDOMAIN ),
			'description' =>	__( 'Display widgets between the title and description on detail Post pages.', ADMINDOMAIN),
		),
		'after-singular' => array(
			'name' =>	__( 'Detail Pages - Below Content',ADMINDOMAIN ),
			'description' =>	__( 'The area shows below the main content on WordPress Posts and Pages.', ADMINDOMAIN ),
		),
		'primary-sidebar' => array(
			'name' => 	__( 'Primary Sidebar',ADMINDOMAIN ),
			'description' => 	__( 'This sidebar will show on pages that do not have a unique sidebar area assigned to them. It&#39;s essentially a backup sidebar..', ADMINDOMAIN )
		),
		
		'subsidiary' => array(
			'name' => 	__( 'Subsidiary - 1 Column',  ADMINDOMAIN ),
			'description' =>	__( 'Displays widgets in a single column on all pages. The area shows below the main content area.', ADMINDOMAIN),
		),
		'subsidiary-2c' => array(
			'name' =>	__( 'Subsidiary - 2 Column', ADMINDOMAIN ),
			'description' =>	__( 'Displays widgets in 2 columns on all pages. The area shows below the main content area.', ADMINDOMAIN),
		),
		
		'subsidiary-3c' => array(
			'name' =>	__( 'Subsidiary - 3 Column',ADMINDOMAIN ),
			'description' =>	__( 'Displays widgets in 3 columns on all pages. The area shows below the main content area.', ADMINDOMAIN),
		),
		'after-header' => array(
			'name' =>	__( 'After Header', ADMINDOMAIN ),
			'description' =>	__( 'A 1-column widget area loaded after the header of the site.', ADMINDOMAIN ),
		),		
		'contact_page_widget' => array(
			'name' =>	__( 'Contact Page - Main Content', ADMINDOMAIN ),
			'description' =>	__( 'The area displays widgets on the contact page. Use the "Contact Us" template to create a contact page.', ADMINDOMAIN ),
		),
		
		'contact_page_sidebar' => array(
			'name' =>	__( 'Contact Page Sidebar', ADMINDOMAIN ),
			'description' =>	__( 'Display widgets inside the Contact page sidebar area.', ADMINDOMAIN ),
		),
		'advance_search_sidebar' => array(
			'name' =>	__( 'Advance Search Content', ADMINDOMAIN ),
			'description' =>	__( 'Display widgets in content area of advanced search page template.', ADMINDOMAIN ),
		),
		'author-page-sidebar' => array(
			'name' =>	__( 'Author Page Sidebar',ADMINDOMAIN ),
			'description' =>	__( 'This sidebar will show on individual author pages. To visit your author page visit a URL like this one: your-domain.com/author/your-username.',ADMINDOMAIN ),
		),
		'footer' => array(
			'name' =>	__( 'Footer', ADMINDOMAIN ),
			'description' =>	__( 'Displays widgets below the subsidiary area.',ADMINDOMAIN ),
		),
		'home-page-above-content-all-pages' => array(
			'name' =>	__( 'Above Main Content - All Pages', ADMINDOMAIN ),
			'description' =>	__( 'Widgets in this area will display on all pages below menu/map and above content in full width.',ADMINDOMAIN ),
			'before_widget' =>	'',
			'after_widget' =>	'',
		),
		'home-page-above-content' => array(
			'name' =>	__( 'Above Home page content', ADMINDOMAIN ),
			'description' =>	__( 'Widgets in this area will be shown above home page content in full width.',ADMINDOMAIN ),
			'before_widget' =>	'',
			'after_widget' =>	'',
		),
		'home-page-below-content' => array(
			'name' =>	__( 'Below Home page content', ADMINDOMAIN ),
			'description' =>	__( 'Widgets in this area will be shown in full width below home page content.',ADMINDOMAIN ),
			'before_widget' =>	'',
			'after_widget' =>	'',
		),
		'home-page-above-footer' => array(
			'name' =>	__( 'Inside Home page Footer', ADMINDOMAIN ),
			'description' =>	__( 'Widgets in this area will be shown in full width inside the home page footer.',ADMINDOMAIN ),
			'before_widget' =>	'',
			'after_widget' =>	'',
		)
		
	
	);
	
	$sidebars = array_merge($sidebars,$theme_sidebars,$plugin_sidebars);
	/* Return the sidebars. */
	
	return $sidebars;
}

/*
 Registers the supreme supported sidebars 
 */
function tmpldir_register_sidebars() {
	unregister_widget('WP_Widget_Text');
	/* Get the theme-supported sidebars. */
	$supported_sidebars = get_theme_support( 'supreme-core-sidebars' );
	/* If the theme doesn't add support for any sidebars, return. */
	if ( !is_array( $supported_sidebars[0] ) )
		return;
	/* Get the available core framework sidebars. */
	$core_sidebars = supreme_get_sidebars();
	/* Loop through the supported sidebars. */
	foreach ( $supported_sidebars[0] as $sidebar ) {
		/* Make sure the given sidebar is one of the core sidebars. */
		if ( isset( $core_sidebars[$sidebar] ) ) {
			/* Set up some default sidebar arguments. */
			$defaults = array(
				'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-wrap widget-inside">',
				'after_widget' => 	'</div></div>',
				'before_title' => 	'<h3 class="widget-title">',
				'after_title' => 	'</h3>'
			);
			/* Parse the sidebar arguments and defaults. */
			$args = wp_parse_args( $core_sidebars[$sidebar], $defaults );
			/* If no 'id' was given, use the $sidebar variable and sanitize it. */
			$args['id'] = ( isset( $args['id'] ) ? sanitize_key( $args['id'] ) : sanitize_key( $sidebar ) );
			/* Register the sidebar. */
			
			register_sidebar($args);
		}
	}
	
	if(is_plugin_active('woocommerce/woocommerce.php')){
		$args = array(
			'name'          => __( 'WooCommerce Sidebar', ADMINDOMAIN ),
			'id'            => 'supreme_woocommerce',
			'description'   => apply_filters('supreme_woo_commerce_sidebar_description',__('This sidebar is specially for woocommerce product pages, whichever widgets you drop here will be shown in woocommerce product pages.',ADMINDOMAIN)),
			'class'         => '',
			'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-wrap widget-inside">',
			'after_widget'  => '</div></div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>' );
		register_sidebar( $args );
	}
}

if(!function_exists('tmpl_fatalErrorHandler')){
	/**
	 * Handling fatal error
	 *
	 * @return void
	 */
	function tmpl_fatalErrorHandler()
	{
		/* Getting last error */
		if(get_option('tmpl_chk_fatal_error_onupdate') !='done'){
		$error = error_get_last();
		
		# Checking if last error is a fatal error 
		if($error['type'] === E_ERROR)
		{
			$wp_plugins = get_plugins();
			$i = 0;
			$phpversion = phpversion();
			$weprefer = 5.3;
			if(version_compare($phpversion,$weprefer,'<')){
				$message1 .= "Your PHP version is not compatible update it to 5.3 or 5.3+";
			}else{
				$message1 ='';
			}
			/* get all active plug ins of templatic */
			foreach ( (array)$wp_plugins as $plugin_file => $plugin_data ) {
				if(is_plugin_active($plugin_file) || is_plugin_active_for_network( $plugin_file )){
					if($plugin_data['Author'] =='Templatic')
					{
						$plugins[$plugin_file] =  $plugin_data;
					}
				}
			}
			$theme_data = wp_get_theme();
		
			$messaeg = '';
			$message .= "<div class='tmpl_addon_message'>";
			$message .= "<p>".__("Oops! Site seems to be in trouble. We find some 'Templatic' Add-ons installed but not updated on your site. If any of the add-on is having older version, please download it from <a href='http://templatic.com/members'>members</a> area and <a href='http://templatic.com/docs/how-to-manage-and-handle-theme-updates/'>update</a> it manually. If this wan't work go to wp-config.php file located in root of WordPress installation. Change define('WP_DEBUG',false) to define('WP_DEBUG',true) and submit the ticket with error in <a href='http://templatic.com/docs/submit-a-ticket/'>helpdesk</a>.",THEME_DOMAIN)."</p>";
			$message .= $message1;
			?>
			<style>.dump_http{ display:none; }</style>
			<?php
			$response = wp_remote_get("http://templatic.com/updates/api/index.php?action=package_details");
			
			$responde_encode = json_decode($response['body']);
			$i =0;
			$message .= "<ul>";
			foreach($plugins as $key => $val){
				$keys = $responde_encode->$key->versions;
				foreach($keys as $k =>$v){
					$new_version =  $k;
				}
				
				if(version_compare($val['Version'], $new_version,'<')){
					$style ="style=color:red;";
					$message .= "<li><span class='tplugin_name'>".$val['Name']."</span> | <span class='tversion'>".$val['Version']."</span> | <span class='tlatest_version' $style>".$new_version."</span></li>";
					$i++;
				}else{
					$style ='';
				}
				
					
			}
			if($i >=1){
				if(!in_array('Tevolution/templatic.php',$plugins))
				{
					$message .= "<li><span class='tplugin_name'> It also seems that the base system ( Tevolution ) of all this add-ons and themes is not activated. Activate it Or If its want work upload it manually.</li>";
				}
			}
			
			$message .= "</ul>";
			$message .= "</div>";
			
			echo $message;
			/* Getting last error */
			
			$error = error_get_last();
			unset($plugins);
			update_option('tmpl_chk_fatal_error_onupdate','done');
		}
		}
	}
	 
	# Registering shut-down function
	register_shutdown_function('tmpl_fatalErrorHandler');
}
/* below home page content area */
add_action('after_wrapper','tmpl_home_page_below_content');


function tmpl_home_page_below_content(){
	if(is_front_page()){ ?>
		 <div class="home_page_below_content row">
		 	<div class="large-12 columns">
          		<?php dynamic_sidebar('home-page-below-content'); ?>
          	</div>
         </div>
	<?php }
}
/*change auto install text*/
function auto_install_text(){
?>
    <script>
        jQuery('.tmpl-auto-install-yb a.button-primary').click(function(){
			/* if button is disabled then do not allow to click again */
			if(jQuery(this).is('[disabled=disabled]')){
				return false;
			}	
                if(jQuery(this).parent().find('.delete-data-button').length <= 0 )
                { 
                        jQuery('span a.button-primary').html('Installing Sample Data...');
                        jQuery('.tmpl-auto-install-yb span').append('<span style="color:green">This <strong>could take up to 5-10 minutes</strong>. Sit back and relax while we install the sample data for you. Please do not close this window until it completes.</span>');
                        /* disable button during auto install is runing */
                        jQuery(this).attr("disabled","disabled");
                }
        });
        jQuery(document).ready(function(){
            <?php if(isset($_REQUEST['x']) && $_REQUEST['x'] == 'y'){ ?>
               jQuery('.tmpl-auto-install-yb span').append('<span style="color:green">All done. Your site is ready with sample data now. <a href="<?php echo site_url(); ?>">Visit your site</a>.</span>');
            <?php } ?>
        });
    </script>
<?php
}


/* this script for add notification message in pate template change time */
add_action('admin_footer','add_script_page_template_changes');
function add_script_page_template_changes()
{
          global $pagenow;
          /*change auto install text*/
          if($pagenow == 'post.php')
          {
          ?>
                    <script>
                              jQuery(function(){ 
                                        var pageTemplateWidgetUrl = "<?php echo  get_bloginfo( 'wpurl' )."/wp-admin/widgets.php" ; ?>";
                                        
                              jQuery('#page_template').change(function() {
                                        if(this.value == 'page-templates/advance-search.php' || this.value == 'page-templates/contact-us.php'){
                                                  if(this.value == 'page-templates/advance-search.php'){
                                                            var with_div_output = '<div id="page_template_notification" style="color:red;"><p>Note: Add Advance search widget to Advance search content area from Appearence-> <a href="'+pageTemplateWidgetUrl+'"><?php echo __('widgets',ADMINDOMAIN); ?></a></p></div>';
                                                            var without_div_output = '<p><?php echo __('Note: Add Advance search widget to Advance search content area from Appearence-> ',ADMINDOMAIN); ?>  <a href="'+pageTemplateWidgetUrl+'"><?php echo __('widgets',ADMINDOMAIN); ?></a></p>';
                                                  }else{
                                                            var with_div_output = '<div id="page_template_notification" style="color:red;"><p><?php echo __('Note: Add contact us widget to contact page - main content area from Appearence-> ',ADMINDOMAIN); ?><a href="'+pageTemplateWidgetUrl+'"><?php echo __('widgets',ADMINDOMAIN); ?></a></p></div>';
                                                            var without_div_output = '<p><?php echo __('Note: Add contact us widget to contact page - main content area from Appearence-> ',ADMINDOMAIN); ?> <a href="'+pageTemplateWidgetUrl+'"><?php echo __('widgets',ADMINDOMAIN); ?></a></p>';
                                                  }
                                                  
                                                  if (jQuery('#page_template_notification').length) {
                                                          jQuery( "#page_template_notification" ).html(without_div_output);
                                                  }else{
                                                          jQuery("#page_template").after(with_div_output);
                                                  }
                                       }else{
                                            jQuery( "#page_template_notification" ).html('');
                                       }
                              });

                                         if(jQuery('#page_template').val() == 'page-templates/advance-search.php' || jQuery('#page_template').val() == 'page-templates/contact-us.php'){
                                                      if(jQuery('#page_template').val() == 'page-templates/advance-search.php'){
                                                            var with_div_output = '<div id="page_template_notification" style="color:red;"><p>Note: Add Advance search widget to Advance search content area from Appearence-> <a href="'+pageTemplateWidgetUrl+'"><?php echo __('widgets',ADMINDOMAIN); ?></a></p></div>';
                                                            var without_div_output = '<p><?php echo __('Note: Add Advance search widget to Advance search content area from Appearence-> ',ADMINDOMAIN); ?>  <a href="'+pageTemplateWidgetUrl+'"><?php echo __('widgets',ADMINDOMAIN); ?></a></p>';
                                                  }else{
                                                            var with_div_output = '<div id="page_template_notification" style="color:red;"><p><?php echo __('Note: Add contact us widget to contact page - main content area from Appearence-> ',ADMINDOMAIN); ?><a href="'+pageTemplateWidgetUrl+'"><?php echo __('widgets',ADMINDOMAIN); ?></a></p></div>';
                                                            var without_div_output = '<p><?php echo __('Note: Add contact us widget to contact page - main content area from Appearence-> ',ADMINDOMAIN); ?> <a href="'+pageTemplateWidgetUrl+'"><?php echo __('widgets',ADMINDOMAIN); ?></a></p>';
                                                  }
                                                  
                                                  if (jQuery('#page_template_notification').length) {
                                                           jQuery( "#page_template_notification" ).html(without_div_output);
                                                  }else{
                                                          jQuery("#page_template").after(with_div_output);
                                                  }
                                       }else{
                                            jQuery( "#page_template_notification" ).html('');
                                       }
                              });
                    </script>
    <?php
          }
}
?>