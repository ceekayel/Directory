<?php
/**
 * Template Name: Full Width Map
 *
 * This is the Map template.  Technically, it is the "Full Width page" template. Its specially for directory add-on compatible template.
 * @subpackage Template
 */
remove_action( 'wp_enqueue_scripts', 'tmpl_theme_css_scripts' );
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta http-equiv="Content-Type" content="<?php bloginfo( 'html_type' ); ?>; charset=<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title>
<?php
if(is_plugin_active('all-in-one-seo-pack/all_in_one_seo_pack.php')){
	wp_title();
}else{
 	supreme_document_title(); 
}?>
</title>
<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="all" />

<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php 
	$supreme2_theme_settings = get_option(supreme_prefix().'_theme_settings');
	if ( file_exists(get_template_directory()."/custom.css") && file_get_contents(get_template_directory()."/custom.css") !='' ) {?>
<link href="<?php echo get_template_directory_uri(); ?>/custom.css" rel="stylesheet" type="text/css" />
<?php } 

	if(function_exists('supreme_get_favicon')){
		if(supreme_get_favicon()){ ?>
			<link rel="shortcut icon" href="<?php  echo supreme_get_favicon(); ?>" />
<?php 	}
	}
wp_head(); // wp_head 

do_action('supreme_enqueue_script');
?>
<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<link rel="stylesheet" href="<?php echo get_template_directory_uri()."/css/admin_style.css"; ?>" type="text/css" media="all" />
</head>
<body class="<?php supreme_body_class(); ?>">
<?php do_action('after_body');?>
<div class="supreme_wrapper">
<?php do_action( 'open_body' ); 
	//if(wpmd_is_phone()){
	$theme_name = get_option('stylesheet');
	$nav_menu = get_option('theme_mods_'.strtolower($theme_name));
	remove_action('pre_get_posts', 'home_page_feature_listing');
	remove_all_actions('posts_where');
	?>


	<div class="off-canvas-wrap" data-offcanvas> <!-- off-canvas-wrap start -->
	<div class="inner-wrap"> <!-- inner-wrap start -->
    <nav class="tab-bar hide-for-large-up">
      <section class="left-small">
        <a class="left-off-canvas-toggle menu-icon" href="#"><span></span></a> <!-- offcanvas icon -->
      </section>

      <section class="middle tab-bar-section">
        <a href="<?php echo site_url(); ?>"><h1 class="title"><?php echo get_option('blogname'); ?></h1></a>
      </section>
    </nav>

    <aside class="left-off-canvas-menu"> <!-- offcanvas side menu -->
    <?php
		apply_filters('tmpl_supreme_header_primary',supreme_header_primary_navigation()); // Loads the menu-primary template. 
		if(isset($nav_menu['nav_menu_locations'])  && @$nav_menu['nav_menu_locations']['secondary'] != 0){
				echo '<div id="nav" class="nav_bg">';		
					apply_filters('tmpl_supreme_header_secondary',supreme_header_secondary_mobile_navigation()); // Loads the menu-secondary template.
				echo "</div>";		
			}elseif(is_active_sidebar('mega_menu')){
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
			}else{
		?>
		<ul class="off-canvas-list">
			<?php wp_list_pages('title_li=&depth=0&child_of=0&number=5&show_home=1&sort_column=ID&sort_order=DESC');?>
		</ul>
      <?php }
	  
       if ( is_active_sidebar( 'header' ) ) : 
        apply_filters( 'tmpl-header',supreme_header_sidebar() ); // Loads the sidebar-header. 
       endif; 
       do_action( 'header' );
       ?>
    </aside>


	<div id="container" class="container-wrap">
		<!-- Theme header container -->
		<div class="header_container clearfix">
			<div class="header_strip">
			
			<div class="primary_menu_wrapper clearfix">
			
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
                        endif; 
			  
                            do_action( 'before_desk_menu_primary' );
                            if(has_nav_menu('primary')){
                                    supreme_primary_navigation();
                            }else{
                                    supreme_secondary_navigation();
                            } 
                            do_action( 'after_desk_menu_primary' ); 
                        ?>
			</div>
			<?php 
				do_action( 'before_header' ); 
				$header_image = get_header_image();
				if(function_exists('get_header_image_location')){
					$header_image_location = get_header_image_location(); // 0 = before secondary navigation menu, 1 = after secondary navigation menu
				}else{
					$header_image_location = 1;
				}
			?>
			</div>
		</div>
		<!-- Theme header container end -->
		<?php
		$tmpdata = get_option('templatic_settings');					
		$map_class=(isset($tmpdata['google_map_full_width']) && $tmpdata['google_map_full_width']=='yes')?'clearfix map_full_width':'map_fixed_width';
		if((!is_page() && !is_author() && !is_404() && !is_singular()) || (is_front_page() || is_home())):
		?>
		<div class="home_page_banner clear clearfix <?php echo $map_class;?>">
		  <?php
				if(!empty($header_image) && $header_image_location == 1){ ?>
		  <div class="templatic_header_image"><img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></div>
		  <?php }
		  
			do_action( 'before_main' ); ?>
		</div>
		<?php 
		endif;?>
		<div id="main" class="clearfix">
			<div class="wrap">
			<?php 
			do_action( 'open_main' ); 

			do_action( 'before_content' ); ?>
			<section id="content" style="padding-bottom:0;">
				<?php do_action( 'open_content' ); // supreme_open_content ?>

				<div class="hfeed">
				<?php 
					if ( have_posts() ) :
					while ( have_posts() ) : the_post(); 
					do_action( 'before_entry' ); ?>
						<div id="post-<?php the_ID(); ?>" class="<?php supreme_entry_class(); ?>">
						  <?php do_action( 'open_entry' );  ?>
						  <section class="entry-content">
							<?php 
								do_action('open-post-content');
								
								the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', THEME_DOMAIN ) );
								wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', THEME_DOMAIN ), 'after' => '</p>' ) );
								
								do_action('close-post-content');
							?>
						  </section>
						  <!-- .entry-content -->
						  <?php do_action( 'close_entry' ); ?>
						</div>
						<?php	 
							do_action( 'after_entry' ); 
							//apply_filters('tmpl_after-singular',supreme_sidebar_after_singular()); 
							do_action( 'after_singular' );
							do_action( 'before_comments' );
								// If comments are open or we have at least one comment, load the comments template.
								if ( supreme_get_settings( 'enable_comments_on_page' )) {
									comments_template( '/comments.php', true );
								}
							do_action( 'after_comments' );
								
					endwhile; 
					endif; 
						
					apply_filters('tmpl_after-content',supreme_sidebar_after_content()); // afetr-content-sidebar use remove filter to dont display it ?>
				</div>
				<?php do_action( 'close_content' ); ?>
			</section>
		<!-- #content -->
		<?php do_action( 'after_content' );	?>
			</div>
		</div>
	</div> 
		<!-- container end -->
	<a class="exit-off-canvas"></a> <!-- exit-off-canvas - overlay to exit offcanvas -->
	<a class="exit-selection"></a>
	<div class="exit-sorting"></div>
	</div> <!-- inner-wrap start -->
	</div> <!-- off-canvas-wrap end -->
</div>     
<!-- footer start for front page template -->
<footer id="footer" class="clearfix">
	<div class="footer_bottom clearfix">
    <div class="footer-wrap clearfix">
      <?php apply_filters('tmpl_supreme_footer_nav',supreme_footer_navigation()); // Loads the menu-footer. 
            if(supreme_get_settings('footer_insert')){
			   $footer_insert=supreme_get_settings( 'footer_insert' ) ;
			   if (function_exists('icl_register_string')) {			   		
					icl_register_string('supreme-footer_insert', 'footer_insert',$footer_insert);
					$footer_insert = icl_t('supreme-footer_insert', 'footer_insert',$footer_insert);
			   }
				
            ?>
      <div class="footer-content "> <?php echo apply_atomic_shortcode( 'footer_content', $footer_insert); ?> </div>
      <!-- .footer-content -->
      <?php }else{ 
            if(!is_active_sidebar('footer')):
            ?>
      <div class="footer-content"> <?php echo '<p class="copyright">&copy; '.date('Y').' <a href="http://templatic.com/demos/directory">'.__('Directory 2',THEME_DOMAIN).'</a>. &nbsp;'.__('Designed by',THEME_DOMAIN).' <a href="http://templatic.com" class="footer-logo"><img src="'.get_template_directory_uri().'/library/images/templatic-wordpress-themes.png" alt="'.__('WordPress Directory Theme',THEME_DOMAIN).'" /></a></p>'; ?> </div>
      <!-- .footer-content -->
      <?php	endif; }	
          do_action( 'footer' ); // supreme_footer ?>
    </div>
    <!-- .wrap -->
  </div>
</footer>
<?php
/* Add heights in maps */
add_action('wp_footer','add_height_map');
function add_height_map()
{?>
	<script>
		outerHeight = window.screen.height;
		jQuery('.full_map_page').css('height',(((outerHeight*84)/100)  )+'px');
		jQuery('.top_banner_section_in').css('height',(((outerHeight*84)/100) )+'px');
		jQuery('#map_canvas').css('height',(((outerHeight*84)/100))+'px');
		jQuery('#map_loading_div').css('height',(((outerHeight*84)/100) )+'px');
	</script>
<?php
}
wp_footer(); // Loads the footer.php template. ?>