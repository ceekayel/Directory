<?php
/**
 * Supreme Core - A WordPress theme development framework.
 */
class Supreme {
	/**
	 * It's adds other methods of the class to 
	 * specific hooks within WordPress.  It controls the load order of the required files for running 
	 * the framework.
	 */
	function __construct() {
		global $supreme;
		/* Set up an empty class for the global $supreme object. */
		$supreme = new stdClass;
		/* Define framework, parent theme, and child theme constants. */
		add_action( 'after_setup_theme', array( &$this, 'constants' ), 1 );
		/* Load the core functions required by the rest of the framework. */
		add_action( 'after_setup_theme', array( &$this, 'core' ), 2 );
		/* Initialize the framework's default actions and filters. */
		add_action( 'after_setup_theme', array( &$this, 'default_filters' ), 3 );
		/* Language functions and translations setup. */
		add_action( 'after_setup_theme', array( &$this, 'i18n' ), 4 );
		/* Handle theme supported features. */
		add_action( 'after_setup_theme', array( &$this, 'theme_support' ), 12 );
		/* Load the framework functions. */
		add_action( 'after_setup_theme', array( &$this, 'functions' ), 13 );
		/* Load the framework extensions. */
		add_action( 'after_setup_theme', array( &$this, 'extensions' ), 14 );
		/* Load admin files. */
		add_action( 'wp_loaded', array( &$this, 'admin' ) );
	}
	/**
	 * Defines the constant paths for use within the core framework, parent theme, and child theme.  
	 * Constants prefixed with 'SUPREME_' are for use only within the core framework and don't 
	 */
	function constants() {
		/* Sets the framework version number. */
		define( 'SUPREME_VERSION', '1.4.0' );
		/* Sets the path to the parent theme directory. */
		define( 'THEME_DIR', get_template_directory() );
		/* Sets the path to the parent theme directory URI. */
		define( 'THEME_URI', get_template_directory_uri() );
		/* Sets the path to the child theme directory. */
		define( 'CHILD_THEME_DIR', get_stylesheet_directory() );
		/* Sets the path to the child theme directory URI. */
		define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );
		/* Sets the path to the core framework directory. */
		define( 'SUPREME_DIR', trailingslashit( THEME_DIR ) . basename( dirname( __FILE__ ) ) );
		/* Sets the path to the core framework directory URI. */
		define( 'SUPREME_URI', trailingslashit( THEME_URI ) . basename( dirname( __FILE__ ) ) );
		/* Sets the path to the core framework admin directory. */
		define( 'SUPREME_ADMIN', trailingslashit( SUPREME_DIR ) . 'admin' );
		/* Sets the path to the core framework classes directory. */
		define( 'SUPREME_CLASSES', trailingslashit( SUPREME_DIR ) . 'classes' );
		/* Sets the path to the core framework extensions directory. */
		define( 'SUPREME_EXTENSIONS', trailingslashit( SUPREME_DIR ) . 'extensions' );
		/* Sets the path to the core framework functions directory. */
		define( 'SUPREME_FUNC', trailingslashit( SUPREME_DIR ) . 'functions' );
		/* Sets the path to the core framework languages directory. */
		define( 'SUPREME_LANGUAGES', trailingslashit( SUPREME_DIR ) . 'languages' );
		/* Sets the path to the core framework images directory URI. */
		define( 'SUPREME_IMAGES', trailingslashit( SUPREME_URI ) . 'images' );
		/* Sets the path to the core framework CSS directory URI. */
		define( 'SUPREME_CSS', trailingslashit( SUPREME_URI ) . 'css' );
		/* Sets the path to the core framework JavaScript directory URI. */
		define( 'SUPREME_JS', trailingslashit( SUPREME_URI ) . 'js' );
	}
	/**
	 Loads the core framework functions.  These function needed before loading anything else in the 
	 framework because they have required functions for use.
	 */
	function core() {
		/* Load the context-based functions. */
		require_once( trailingslashit( SUPREME_FUNC ) . 'functions.php' );
		
		/* Load the context-based functions. */
		require_once( trailingslashit( SUPREME_FUNC ) . 'context.php' );
		/* Load the core framework internationalization functions. */
		require_once( trailingslashit( SUPREME_FUNC ) . 'i18n.php' );
	}
	/**
	 * Loads both the parent and child theme translation files.  If a locale-based functions file exists
	 * in either the parent or child theme (child overrides parent), it will also be loaded.  All translation 
	 * and locale functions files are expected to be within the theme's '/languages' folder, but the 
	 * framework will fall back on the theme root folder if necessary.  Translation files are expected 
	 * to be prefixed with the template or stylesheet path (example: 'templatename-en_US.mo').
	 *
	 * @since 1.2.0
	 */
	function i18n() {
		global $supreme;
		/* Get parent and child theme textTHEME_DOMAINs. */
		$parent_textdomain = supreme_get_parent_textdomain();
		$child_textdomain = supreme_get_child_textdomain();
		/* Load the framework textTHEME_DOMAIN. */
	
		/* Load theme textTHEME_DOMAIN. */
		$supreme->textdomain_loaded[$parent_textdomain] = load_theme_textdomain( $parent_textdomain );
		/* Load child theme textTHEME_DOMAIN. */
		$supreme->texttextdomain_loaded[$child_textdomain] = is_child_theme() ? load_child_theme_textdomain( $child_textdomain ) : false;
		/* Get the user's locale. */
		$locale = get_locale();
		/* Locate a locale-specific functions file. */
		$locale_functions = locate_template( array( "languages/{$locale}.php", "{$locale}.php" ) );
		/* If the locale file exists and is readable, load it. */
		if ( !empty( $locale_functions ) && is_readable( $locale_functions ) )
			require_once( $locale_functions );
	}
	/**
	 * Removes theme supported features from themes in the case that a user has a plugin installed
	 * that handles the functionality.
	 *
	 * @since 1.3.0
	 */
	function theme_support() {
		
		/* Remove support for the the Breadcrumb Trail extension if the plugin is installed. */
		if ( function_exists( 'breadcrumb_trail' ) )
			remove_theme_support( 'breadcrumb-trail' );
		/* Remove support for the the Cleaner Gallery extension if the plugin is installed. */
		if ( function_exists( 'cleaner_gallery' ) )
			remove_theme_support( 'cleaner-gallery' );
		/* Remove support for the the Get the Image extension if the plugin is installed. */
		if ( function_exists( 'get_the_image' ) )
			remove_theme_support( 'get-the-image' );
	}
	/**
	 * Loads the framework functions.  Many of these functions are needed to properly run the 
	 * framework.  Some components are only loaded if the theme supports them.
	 *
	 * @since 0.7.0
	 */
	function functions() {
		/* Load the comments functions. */
		require_once( trailingslashit( SUPREME_FUNC ) . 'comments.php' );
		/* Load the metadata functions. */
		require_once( trailingslashit( SUPREME_FUNC ) . 'templatic-news.php' );
		
		/* Load the customizer functions if theme settings are supported. */
		require_if_theme_supports( 'supreme-core-theme-settings', trailingslashit( SUPREME_ADMIN ) . 'admin.php' );
		/* Load the menus functions if supported. */
		require_if_theme_supports( 'supreme-core-menus', trailingslashit( SUPREME_FUNC ) . 'menus.php' );
		/* Load the shortcodes if supported. */
		require_if_theme_supports( 'supreme-core-shortcodes', trailingslashit( SUPREME_FUNC ) . 'shortcodes.php' );
		/* Load the shortcodes if supported. */
		if(file_exists(trailingslashit( SUPREME_FUNC ) . 'hooks.php'))
			require_once( trailingslashit( SUPREME_FUNC ) . 'hooks.php' );
		/* Load the widgets if supported. */
		require_if_theme_supports( 'tmpldir-core-widgets', trailingslashit( SUPREME_FUNC ) . 'widgets_functions.php' );
		require_if_theme_supports( 'tmpldir-core-widgets', trailingslashit( SUPREME_FUNC ) . 'widgets.php' );
		require_if_theme_supports( 'tmpldir-core-widgets', trailingslashit( SUPREME_CLASSES ) . 'twitter.php' );
		
		require_if_theme_supports( 'tmpldir-core-widgets', trailingslashit( SUPREME_CLASSES ) . 'facebook.php' );
		if(file_exists(get_template_directory().'/library/auto_update.php')){
			require_once(get_template_directory().'/library/auto_update.php'); // file for auto updating feature of framework
		}
	}
	/**
	 * Load extensions (external projects).  Extensions are projects that are included within the 
	 * framework but are not a part of it.  They are external projects developed outside of the 
	 * framework.  Themes must use add_theme_support( $extension ) to use a specific extension 
	 * within the theme.  This should be declared on 'after_setup_theme' no later than a priority of 11.
	 */
	function extensions() {
		/* Load the Breadcrumb Trail extension if supported. */
		require_if_theme_supports( 'breadcrumb-trail', trailingslashit( SUPREME_EXTENSIONS ) . 'breadcrumb-trail.php' );
		/* Load the Get the Image extension if supported. */
		require_once( trailingslashit( SUPREME_EXTENSIONS ) . 'get-the-image.php');
		/* Load the Theme Layouts extension if supported. */
		require_if_theme_supports( 'theme-layouts', trailingslashit( SUPREME_EXTENSIONS ) . 'theme-layouts.php' );
		/* Load the Post Stylesheets extension if supported. */
		
	}
	/**
	 * Load admin files for the framework.
	 *
	 * @since 0.7.0
	 */
	function admin() {
		/* Check if in the WordPress admin. */
		if ( is_admin() ) {
			/* Load the main admin file. */
			require_once( trailingslashit( SUPREME_ADMIN ) . 'admin.php' );
		}
	}
	/**
	 * Adds the default framework actions and filters.
	 *
	 * @since 1.0.0
	 */
	function default_filters() {
		/* Move the WordPress generator to a better priority. */
		remove_action( 'wp_head', 'wp_generator' );
		/* Add the theme info to the header (lets theme developers give better support). */
		add_action( 'supreme_add_meta', 'supreme_meta_template', 1 );
		/* Filter the textdomain mofile to allow child themes to load the parent theme translation. */
		add_filter( 'load_textdomain_mofile', 'supreme_load_textdomain_mofile', 10, 2 );
		/* Filter text strings for Hybrid Core and extensions so themes can serve up translations. */
		add_filter( 'gettext', 'supreme_gettext', 1, 3 );
		add_filter( 'gettext', 'supreme_extensions_gettext', 1, 3 );
		/* Make text widgets and term descriptions shortcode aware. */
		add_filter( 'widget_text', 'do_shortcode' );
		add_filter( 'term_description', 'do_shortcode' );
	}
}
?>