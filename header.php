<?php
/**
 * The Header for our theme.
 *
 * The header template is generally used on every page of your site. Nearly all other templates call it 
 * somewhere near the top of the file. It is used mostly as an opening wrapper, which is closed with the 
 * footer.php file. It also executes key functions needed by the theme, child themes, and plugins. 
 */
?>
<!DOCTYPE html>
<!--[if lt IE 7 ]> <html class="ie6"> <![endif]-->  
<!--[if IE 7 ]>    <html class="ie7"> <![endif]-->  
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->  
<!--[if IE 9 ]>    <html class="ie9"> <![endif]--> 
<!--[if (gt IE 9)|!(IE)]><!--> 
	<html data-useragent="Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Trident/6.0)" <?php language_attributes(); ?>>
<!--<![endif]--> 
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">
<!-- <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> -->
<meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- Specially to make clustering work in IE -->
<title>
	<?php wp_title();?>
</title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php 
/* get favicon icon */
$supreme2_theme_settings = get_option(supreme_prefix().'_theme_settings');
if(function_exists('supreme_get_favicon')){
  if(supreme_get_favicon()){ 
    echo '<link rel="shortcut icon" href="'.supreme_get_favicon().'" />';
  }
}

wp_head();

if(isset($supreme2_theme_settings['enable_sticky_header_menu']) && $supreme2_theme_settings['enable_sticky_header_menu']==1){
  wp_enqueue_script('header-sticky-menu',get_template_directory_uri().'/js/sticky_menu.js',array( 'jquery' ));
}
if ( file_exists(get_template_directory()."/custom.css") && file_get_contents(get_template_directory()."/custom.css") !='') {
  echo '<link href="'.get_template_directory_uri().'/custom.css" rel="stylesheet" type="text/css" />';    
}
do_action('supreme_enqueue_script');
?>
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<!--[if IE]>
<style>
    body{word-wrap:inherit!important;}
</style>
<![endif]-->
<script type="text/javascript">
  jQuery( document ).ready(function() {
    if(jQuery( window ).width() < 980 ){
      jQuery('.preview_submit_from_data .b_getdirection.getdir').html("<i class='fa fa-map-marker'></i>");
      jQuery('.preview_submit_from_data .b_getdirection.large_map').html("<i class='fa fa-retweet'></i>");
    }
    jQuery( window ).resize(function() {
      if(jQuery( window ).width() < 980 ){
        jQuery('.preview_submit_from_data .b_getdirection.getdir').html("<i class='fa fa-map-marker'></i>");
        jQuery('.preview_submit_from_data .b_getdirection.large_map').html("<i class='fa fa-retweet'></i>");
      }
    });
    
  });
  
</script>
</head>


<body class="<?php supreme_body_class(); ?>">  
<?php do_action('after_body');?>
<div class="supreme_wrapper">
<?php 
	do_action( 'open_body' ); // supreme_open_body 
	$theme_name = get_option('stylesheet');
	$nav_menu = get_option('theme_mods_'.strtolower($theme_name));
	remove_action('pre_get_posts', 'home_page_feature_listing');
?>
<div class="off-canvas-wrap" data-offcanvas> <!-- off-canvas-wrap start -->
	<!-- inner-wrap start -->
	<div class="inner-wrap">
	
	<!-- Navigation  - Contain logo and site title -->
    <nav class="tab-bar hide-for-large-up">
      <section class="left-small">
        <a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a> <!-- off canvas icon -->
      </section>
    <?php do_action('tmpl_after_logo'); ?>
      <section class="middle tab-bar-section">
        <a href="<?php echo home_url(); ?>/" title="<?php echo bloginfo( 'name' ); ?>" rel="Home">
			<img class="logo" src="<?php echo supreme_get_settings( 'supreme_logo_url' ); ?>" alt="<?php echo bloginfo( 'name' ); ?>" />
		</a>
      </section>
    </nav>

        
    <aside class="left-off-canvas-menu"> <!-- off canvas side menu -->
    <?php
	apply_filters('tmpl_supreme_header_primary',supreme_header_primary_navigation());
	if(is_active_sidebar('mega_menu')){
		if(function_exists('dynamic_sidebar')){
		  echo '<div id="nav" class="nav_bg">
			  <div id="menu-mobi-secondary" class="menu-container">
				<nav role="navigation" class="wrap">
				  <div id="menu-mobi-secondary-title">';
					_e( 'Menu', THEME_DOMAIN );
				echo '</div>';
				dynamic_sidebar('mega_menu'); // jQuery mega menu
		  echo "</nav></div></div>";    
		} 
	}
   	elseif(isset($nav_menu['nav_menu_locations'])  && isset($nav_menu['nav_menu_locations']['secondary']) && $nav_menu['nav_menu_locations']['secondary'] != 0){
        echo '<div id="nav" class="nav_bg">';   
          apply_filters('tmpl_supreme_header_secondary',supreme_header_secondary_mobile_navigation()); // Loads the menu-secondary template.
        echo "</div>";    
      }else{
    ?>
		<ul class="off-canvas-list">
		<?php wp_list_pages('title_li=&depth=0&child_of=0&number=5&show_home=1&sort_column=ID&sort_order=DESC');?>
		</ul>
		<?php } ?>
    </aside>

<div id="container" class="container-wrap">
<header class="header_container clearfix">
	
    <div class="primary_menu_wrapper clearfix">
      <div class="primary_menu_wrap row">
          <?php
		      do_action('before_desk_menu_primary');
          supreme_primary_navigation();
		      do_action( 'after_desk_menu_primary' ); ?>
      </div>
    </div>
    <?php do_action( 'before_header' );
    $header_image = get_header_image();
    if(function_exists('get_header_image_location')){
      $header_image_location = get_header_image_location(); // 0 = before secondary navigation menu, 1 = after secondary navigation menu
    }else{
      $header_image_location = 1;
    } ?>
     <div id="header" class="row clearfix">
      <?php do_action('header_container_start'); 
			do_action( 'open_header' ); ?>
          <div class="header-wrap">
              
                    <div id="branding" class="large-4 columns">
                        <hgroup>
                        <?php
						if(supreme_get_settings( 'display_header_text' )){
							if ( supreme_get_settings( 'supreme_logo_url' ) ) :?>
							<div id="site-title">
								<a href="<?php echo home_url(); ?>/" title="<?php echo bloginfo( 'name' ); ?>" rel="Home">
									<img class="logo" src="<?php echo supreme_get_settings( 'supreme_logo_url' ); ?>" alt="<?php echo bloginfo( 'name' ); ?>" />
								</a>
							</div>
							<?php else :
								supreme_site_title();
							endif;
						}
                        if ( supreme_get_settings( 'supreme_site_description' ) )  : // If hide description setting is un-checked, display the site description. 
                        	supreme_site_description(); 
                        endif; ?>
                        </hgroup>
                    </div>
                    <!-- #branding -->
    
                <div class="large-8 columns">
                    <div class="header-widget-wrap">
						<?php
                        if ( is_active_sidebar( 'header' ) && function_exists('supreme_header_sidebar')) : 
                        apply_filters( 'tmpl-header',supreme_header_sidebar() ); // Loads the sidebar-header. 
                        endif; 
                        do_action( 'header' ); ?>
                    </div>
                </div> <!-- large-8 columns -->
              
          </div>
          <!-- .wrap -->
          <?php if(!empty($header_image) && $header_image_location == 0){ ?>
            <div class="templatic_header_image"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></div>
          <?php }
    
          do_action( 'close_header' );
          /* Secondary navigation menu for desk top */
          supreme_secondary_navigation();           
          ?>
     </div>
    <!-- #header -->

</header>
<?php 
$tmpdata = get_option('templatic_settings');          
$map_class=(isset($tmpdata['google_map_full_width']) && $tmpdata['google_map_full_width']=='yes')?'clearfix map_full_width':'map_fixed_width';
/* get current page template */
$current_page_template = get_page_template_slug( $post->ID );
if((!is_page() && !is_author() && !is_404() && !is_singular()) || (is_front_page() || is_home()) || $current_page_template == 'page-templates/front-page.php'):?>
     <div class="home_page_banner clear clearfix <?php echo $map_class;?>">
       <?php if(!empty($header_image) && $header_image_location == 1){ ?>
          <div class="templatic_header_image"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></div>
       <?php } 
	   if(!is_home()){
       	do_action( 'before_main' ); 
	   }
	   ?>
     </div>
<?php endif;

if((is_singular() && !is_page()) || (isset($_REQUEST['page']) && $_REQUEST['page'] =='preview')){
	do_action( 'before_opening_main' ); 
}
?>
<section id="main" class="clearfix">

<?php 

  do_action('tmpl_before_open_main'); 
  do_action('tmpl_open_main'); 
  do_action('tmpl_after_open_main'); 

  do_action( 'open_main' ); ?>
<div class="wrap row">
<?php do_action('tmpl_open_wrap'); ?>