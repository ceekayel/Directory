<?php
/**
 * The menus functions deal with registering nav menus within WordPress for the core framework.  Theme 
 * developers may use the default menu(s) provided by the framework within their own themes, decide not
 * to use them, or register additional menus.
 */
/* Register nav menus. */
add_action( 'init', 'supreme_register_menus' );
/**
 * Registers the the framework's default menus based on the menus the theme has registered support for.
 *
 */
function supreme_register_menus() {
	/* Get theme-supported menus. */
	$menus = get_theme_support( 'supreme-core-menus' );
	/* If there is no array of menus IDs, return. */
	if ( !is_array( $menus[0] ) )
		return;
	/* Register the 'primary' menu. */
	if ( in_array( 'primary', $menus[0] ) )
		register_nav_menu( 'primary', _x( 'Primary', 'nav menu location', THEME_DOMAIN ) );
	/* Register the 'secondary' menu. */
	if ( in_array( 'secondary', $menus[0] ) )
		register_nav_menu( 'secondary', _x( 'Secondary', 'nav menu location', THEME_DOMAIN ) );
	/* Register the 'subsidiary' menu. */
	if ( in_array( 'subsidiary', $menus[0] ) )
		register_nav_menu( 'subsidiary', _x( 'Subsidiary', 'nav menu location', THEME_DOMAIN ) );
	if ( in_array( 'footer', $menus[0] ) )
		register_nav_menu( 'footer', _x( 'Footer', 'nav menu location', THEME_DOMAIN ) );
}
/**
	Display header primary navigation menu
**/
function supreme_header_primary_navigation(){
if ( has_nav_menu( 'primary' ) || isset($_REQUEST['ptype']) ) :
	do_action( 'before_menu_primary' ); ?>
	<!-- Primary Navigation Menu Start -->
	<div id="menu-mobi-primary" class="menu-container">
	  <nav role="navigation" class="wrap">
		<div id="menu-mobi-primary-title">
		  <?php _e( 'Menu', THEME_DOMAIN ); ?>
		</div>
		<!-- #menu-primary-title -->
		<?php do_action( 'open_menu_primary' ); 
				wp_nav_menu( array( 'theme_location' => 'primary', 'container_class' => 'menu', 'menu_class' => 'primary_menu clearfix', 'menu_id' => 'menu-mobi-primary-items', 'fallback_cb' => '' ) ); 
				do_action( 'close_menu_primary' ); ?>
	  </nav>
	</div>
	<!-- #menu-primary .menu-container -->
<!-- Primary Navigation Menu End -->
<?php do_action( 'after_menu_primary' ); 
	endif; 
}
/**
	eader secondary menu - display below header
**/
function supreme_header_secondary_navigation(){
if ( (has_nav_menu( 'secondary' ) || isset($_REQUEST['ptype']) || isset($_REQUEST['trans_id'])) && !is_active_sidebar('secondary_navigation_right')) : 
  
do_action( 'before_menu_secondary' ); ?>
<div id="menu-secondary" class="menu-container clearfix">
  <nav role="navigation" class="wrap">
    <!-- #menu-secondary-title -->
    <div id="menu-secondary-title">
      <?php _e( 'Menu', THEME_DOMAIN ); ?>
    </div>
    <?php 
		do_action( 'open_menu_secondary' );
		wp_nav_menu( array( 'theme_location' => 'secondary', 'container_class' => 'menu', 'menu_class' => '', 'menu_id' => 'menu-secondary-items', 'fallback_cb' => '' ) ); 
		
		do_action( 'close_menu_secondary' );
		
		?>
  </nav>
</div>
<!-- #menu-secondary .menu-container -->
<?php do_action( 'after_menu_secondary' );
else: ?>
	<div id="menu-secondary" class="menu-container clearfix">
		<nav role="navigation" class="wrap">
		<div id="menu-secondary-title">
		  <?php _e( 'Menu', THEME_DOMAIN ); ?>
		</div>
		<?php
		do_action( 'open_menu_secondary' );
		apply_filters('supreme-nav-right',dynamic_sidebar('secondary_navigation_right')); 
		do_action( 'close_menu_secondary' );
		?>
		</nav>
	</div>
	<?php
endif; 
}
/**
	Header secondary menu - display below header
**/
function supreme_header_secondary_mobile_navigation(){
	
if ( has_nav_menu( 'secondary' ) ) : 
   
	do_action( 'before_menu_secondary' );  ?>
	<div id="menu-mobi-secondary" class="menu-container">
	  <nav role="navigation" class="wrap">
		<div id="menu-mobi-secondary-title">
		  <?php _e( 'Menu', THEME_DOMAIN ); ?>
		</div>
		<!-- #menu-secondary-title -->
		<?php do_action( 'open_menu_secondary' ); 
		
		wp_nav_menu( array( 'theme_location' => 'secondary', 'container_class' => 'menu', 'menu_class' => 'off-canvas-list', 'menu_id' => 'menu-mobi-secondary-items', 'fallback_cb' => '' ) ); 

		do_action( 'close_menu_secondary' );  ?>
	  </nav>
	</div>
<!-- #menu-secondary .menu-container -->
<?php do_action( 'after_menu_secondary' ); 
	endif; 
}
/**
	Footer navigation menu - display in footer
**/
function supreme_footer_navigation(){
	if ( has_nav_menu( 'footer' ) ) : ?>
	<?php 
	do_action( 'before_menu_footer' ); ?>
	<div id="menu-footer" class="menu-container">
	  <nav class="wrap">
		<?php do_action( 'open_menu_footer' ); 
		wp_nav_menu( array( 'theme_location' => 'footer', 'container_class' => 'menu', 'menu_class' => '', 'menu_id' => 'menu-footer-items', 'fallback_cb' => '' ) ); 
		do_action( 'close_menu_footer' ); ?>
	  </nav>
	</div>
	<!-- #menu-footer .menu-container -->
<?php do_action( 'after_menu_footer' ); 
	endif; 
}
/**
	Subsidiary navigation menu - display in subsidiary area
**/
function supreme_subsidiary_navigation(){
	if ( has_nav_menu( 'subsidiary' ) ) : 
	do_action( 'before_menu_subsidiary' ); ?>
	<div id="menu-subsidiary" class="menu-container">
	  <div class="wrap">
		<div id="menu-subsidiary-title">
		  <?php _e( 'Menu', THEME_DOMAIN ); ?>
		</div>
		<!-- #menu-subsidiary-title" -->
		<?php do_action( 'open_menu_subsidiary' ); 
					wp_nav_menu( array( 'theme_location' => 'subsidiary', 'container_class' => 'menu', 'menu_class' => '', 'menu_id' => 'menu-subsidiary-items', 'fallback_cb' => '' ) ); 
				
				do_action( 'close_menu_subsidiary' ); ?>
	  </div>
	</div>
<!-- #menu-subsidiary .menu-container -->
<?php do_action( 'after_menu_subsidiary' );
	endif;
}