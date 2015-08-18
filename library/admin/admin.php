<?php
/**
 * used with other components of the framework admin. this file is for 
 * setting up any basic features and holding additional admin helper functions.
 */
/* add the admin setup function to the 'admin_menu' hook. */
add_action( 'admin_menu', 'tmpl_admin_setup' );
/*
	sets up the administration functionality for the framework and themes.
*/
function tmpl_admin_setup() {
	/* loads admin stylesheets for the framework. */
	add_action( 'admin_enqueue_scripts', 'tmpl_admin_enqueue_styles' );
}

/*
	loads the admin.css stylesheet for admin-related features.
*/
function tmpl_admin_enqueue_styles( $suffix ) {
	/* load admin styles if on the widgets screen and the current theme supports 'tmpldir-core-widgets'. */
	if ( current_theme_supports( 'tmpldir-core-widgets' ) && 'widgets.php' == $suffix )
		wp_enqueue_style( 'supreme-core-admin' );
}
/*
	function for getting an array of available custom templates with a specific header. ideally, this function would be used to grab custom singular post (any post type) templates.  it is a recreation of the wordpress page templates function because it doesn't allow for other types of templates.
*/
function supreme_get_post_templates( $args = array() ) {
	/* parse the arguments with the defaults. */
	$args = wp_parse_args( $args, array( 'label' => array( 'post template' ) ) );
	/* get theme and templates variables. */
	$themes = wp_get_themes();
	$theme = wp_get_theme();
	@$templates = $themes[$theme]['template files'];
	$post_templates = array();
	/* if there's an array of templates, loop through each template. */
	if ( is_array( $templates ) ) {
		/* set up a $base path that we'll use to remove from the file name. */
		$base = array( trailingslashit( get_template_directory() ), trailingslashit( get_stylesheet_directory() ) );
		/* loop through the post templates. */
		foreach ( $templates as $template ) {
			/* remove the base (parent/child theme path) from the template file name. */
			$basename = str_replace( $base, '', $template );
			/* get the template data. */
			$template_data = implode( '', file( $template ) );
			/* make sure the name is set to an empty string. */
			$name = '';
			/* loop through each of the potential labels and see if a match is found. */
			foreach ( $args['label'] as $label ) {
				if ( preg_match( "|{$label}:(.*)$|mi", $template_data, $name ) ) {
					$name = _cleanup_header_comment( $name[1] );
					break;
				}
			}
			/* if a post template was found, add its name and file name to the $post_templates array. */
			if ( !empty( $name ) )
				$post_templates[trim( $name )] = $basename;
		}
	}
	/* return array of post templates. */
	return $post_templates;
}

/*=========================== load theme customization options ===========================================*/

/* load custom control classes. */
add_action( 'customize_register', 'tmpldir_customize_controls', 1 );
/* register custom sections, settings, and controls. */
add_action( 'customize_register', 'tmpldir_customize_register' );
/* add the footer content ajax to the correct hooks. */
add_action( 'wp_ajax_supreme_customize_footer_content', 'supreme_customize_footer_content_ajax' );
add_action( 'wp_ajax_nopriv_supreme_customize_footer_content', 'supreme_customize_footer_content_ajax' );


/*
 * registers custom sections, settings, and controls for the $wp_customize instance.
*/
function tmpldir_customize_register( $wp_customize ) {
	/* get supported theme settings. */
	$supports = get_theme_support( 'supreme-core-theme-settings' );
	/* get the theme prefix. */
	$prefix = supreme_prefix();
	/* get the default theme settings. */
	$default_settings = supreme_default_theme_settings();
	/* add the footer section, setting, and control if theme supports the 'footer' setting. */
	if ( is_array( $supports[0] ) && in_array( 'footer', $supports[0] ) ) {
		/* add the footer section. */
		$wp_customize->add_section(
			'supreme-core-footer',
			array(
				'title' => 		esc_html__( 'Footer', ADMINDOMAIN ),
				'priority' => 	200,
				'capability' => 	'edit_theme_options'
			)
		);
		/* add the 'footer_insert' setting. */
		$wp_customize->add_setting(
			"{$prefix}_theme_settings[footer_insert]",
			array(
				'label' => 		' html tags allow, enter whatever you want to display in footer section.',
				'default' => 		@$default_settings['footer_insert'],
				'type' => 			'option',
				'capability' => 		'edit_theme_options',
				'sanitize_callback' => 	'supreme_customize_sanitize',
				'sanitize_js_callback' => 	'supreme_customize_sanitize',
				'transport' => 		'postmessage',
			)
		);
		/* add the textarea control for the 'footer_insert' setting. */
		$wp_customize->add_control(
			new hybrid_customize_control_textarea(
				$wp_customize,
				'supreme-core-footer',
				array(
					'label' => 	 __('Footer', ADMINDOMAIN ),
					'section' => 	'supreme-core-footer',
					'settings' => 	"{$prefix}_theme_settings[footer_insert]",
				)
			)
		);
	/* if viewing the customize preview screen, add a script to show a live preview. */
		if ( $wp_customize->is_preview() && !is_admin() )
			add_action( 'wp_footer', 'supreme_customize_preview_script', 21 );
	}
}
/*
  sanitizes the footer content on the customize screen.  users with the 'unfiltered_html' cap can post 
  anything.  for other users, wp_filter_post_kses() is ran over the setting.
*/
function supreme_customize_sanitize( $setting, $object ) {
	/* get the theme prefix. */
	$prefix = supreme_prefix();
	/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
	if ( "{$prefix}_theme_settings[footer_insert]" == $object->id && !current_user_can( 'unfiltered_html' )  )
		$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
	/* return the sanitized setting and apply filters. */
	return apply_filters( "{$prefix}_customize_sanitize", $setting, $object );
}
/*
  runs the footer content posted via ajax through the do_shortcode() function.  this makes sure the short codes are output correctly in the live preview.
*/
function supreme_customize_footer_content_ajax() {
	/* check the ajax nonce to make sure this is a valid request. */
	check_ajax_referer( 'supreme_customize_footer_content_nonce' );
	/* if footer content has been posted, run it through the do_shortcode() function. */
	if ( isset( $_post['footer_content'] ) )
		echo do_shortcode( wp_kses_stripslashes( $_post['footer_content'] ) );
	/* always die() when handling ajax. */
	die();
}
/*
 * handles changing settings for the live preview of the theme.
*/
function supreme_customize_preview_script() {
	/* create a nonce for the ajax. */
	$nonce = wp_create_nonce( 'supreme_customize_footer_content_nonce' );
	?>
<script type="text/javascript">
	wp.customize(
		'<?php echo supreme_prefix(); ?>_theme_settings[footer_insert]',
		function( value ) {
		value.bind(
			function( to ) {
				jquery.post( 
					'<?php echo admin_url( 'admin-ajax.php' ); ?>', 
					{ 
						action: 'supreme_customize_footer_content',
						_ajax_nonce: '<?php echo $nonce; ?>',
						footer_content: to
					},
					function( response ) {
						jquery( '.footer-content' ).html( response );
					}
				);
			}
		);
		}
	);
	</script>
<?php
}
/*
	@theme customizer settings for wordpress customizer.
*/	
global $pagenow;
if(is_admin() && 'admin.php' == $pagenow){
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'you do not have sufficient permissions to access this section.',ADMINDOMAIN ) );
	}
}
/*	add action for customizer   start	*/
	add_action( 'customize_register',  'templatic_register_customizer_settings');
/*	add action for customizer   end	*/


/*	function to create sections, settings, controls for wordpress customizer start.  */
global $support_woocommerce;
$support_woocommerce = get_theme_support('supreme_woocommerce_layout');
/*
	register customizer settings option , it returns the options for theme->customizer.php
*/
function templatic_register_customizer_settings( $wp_customize ){
	global $support_woocommerce;
		/* add section for different controls in customizer start 
		header image section settings start */
		$wp_customize->get_section('header_image')->priority = 5;
		
		/*header image section settings end
		navigation menu section settings start*/
		$wp_customize->get_section('nav')->priority = 6;
		
		/* navigation menu section settings end
		color section settings start */
		$wp_customize->get_section('colors')->title = __( 'Colors settings' ,ADMINDOMAIN);
		$wp_customize->get_section('colors')->priority = 7;
		
		/* colour section settings end	background section settings start */		
		$wp_customize->get_section('background_image')->title = __( 'Background settings',ADMINDOMAIN );
		$wp_customize->get_section('background_image')->priority = 8;
		
		
		/* background section settings end
		add site logo section start */
		$wp_customize->add_section('templatic_logo_settings', array(
			'title' => __('Site logo',ADMINDOMAIN),
			'priority'=> 1
		));
		
		
		/* site title section settings start */
		$wp_customize->get_section('title_tagline')->priority = 2;
		
		//static front page section settings start
		$wp_customize->get_section('static_front_page')->priority = 12;
		//static front page section settings end
		
		//supreme core footer section settings start
		$wp_customize->get_section('supreme-core-footer')->priority = 17;
		//supreme core footer section settings end
		
	
	/*	add settings start */
		
		//add settings for site logo start
		//callback function: templatic_customize_supreme_logo_url
		$wp_customize->add_setting(supreme_prefix().'_theme_settings[supreme_logo_url]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	"templatic_customize_supreme_logo_url",
			'sanitize_js_callback' => 	"templatic_customize_supreme_logo_url",
			//'transport' => 'postmessage',
		));
		//add settings for site logo finish
		
		//add settings for favicon icon start
		//callback function: templatic_customize_supreme_favicon_icon
		$wp_customize->add_setting(supreme_prefix().'_theme_settings[supreme_favicon_icon]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	"templatic_customize_supreme_favicon_icon",
			'sanitize_js_callback' => 	"templatic_customize_supreme_favicon_icon",
			//'transport' => 'postmessage',
		));
		//add settings for favicon icon finish
		
		//add settings for hide/show site description start
		//callback function: templatic_customize_supreme_site_description
		$wp_customize->add_setting(supreme_prefix().'_theme_settings[supreme_site_description]',array(
			'default' => '',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	'templatic_customize_supreme_site_description',
			'sanitize_js_callback' => 	'templatic_customize_supreme_site_description',
			
			//'transport' => 'postmessage',
		));
		//add settings for hide/show site description finish
			
		$wp_customize->add_setting(supreme_prefix().'_theme_settings[footer_lbl]', array(
	        'default' => '',
		));
		// added custom label control finish
		
		//color settings start.
		$wp_customize->add_setting(supreme_prefix().'_theme_settings[color_picker_color1]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"templatic_customize_supreme_color1",
				'sanitize_js_callback' => 	"templatic_customize_supreme_color1",
				//'transport' => 'postmessage',
			));
			
			$wp_customize->add_setting(supreme_prefix().'_theme_settings[color_picker_color2]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"templatic_customize_supreme_color2",
				'sanitize_js_callback' => 	"templatic_customize_supreme_color2",
				//'transport' => 'postmessage',
			));
			
			$wp_customize->add_setting(supreme_prefix().'_theme_settings[color_picker_color3]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"templatic_customize_supreme_color3",
				'sanitize_js_callback' => 	"templatic_customize_supreme_color3",
				//'transport' => 'postmessage',
			));
			
			$wp_customize->add_setting(supreme_prefix().'_theme_settings[color_picker_color4]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"templatic_customize_supreme_color4",
				'sanitize_js_callback' => 	"templatic_customize_supreme_color4",
				//'transport' => 'postmessage',
			));
			
			$wp_customize->add_setting(supreme_prefix().'_theme_settings[color_picker_color5]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"templatic_customize_supreme_color5",
				'sanitize_js_callback' => 	"templatic_customize_supreme_color5",
				//'transport' => 'postmessage',
			));
			
			$wp_customize->add_setting(supreme_prefix().'_theme_settings[color_picker_color6]',array(
				'default' => '',
				'type' => 'option',
				'capabilities' => 'edit_theme_options',
				'sanitize_callback' => 	"templatic_customize_supreme_color6",
				'sanitize_js_callback' => 	"templatic_customize_supreme_color6",
				//'transport' => 'postmessage',
			));
			
	
				
		//add settings for background header image start
		//callback function: templatic_customize_supreme_header_background_image
		$wp_customize->add_setting( 'header_image', array(
			'default'        => get_theme_support( 'custom-header', 'default-image' ),
			'theme_supports' => 'custom-header',
		) );
		
		$wp_customize->add_setting(supreme_prefix().'_theme_settings[header_image_display]',array(
			'default' => 'after_nav',
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	"templatic_customize_header_image_display",
			'sanitize_js_callback' => 	"templatic_customize_header_image_display",
			//'transport' => 'postmessage',
		));
		//add settings for background header image finish
		
		//add settings for hide/show header text start
		$wp_customize->add_setting(supreme_prefix().'_theme_settings[display_header_text]',array(
			'default' => 1,
			'type' => 'option',
			'capabilities' => 'edit_theme_options',
			'sanitize_callback' => 	"templatic_customize_display_header_text",
			'sanitize_js_callback' => 	"templatic_customize_display_header_text",
			//'transport' => 'postmessage',
		));
		//add settings for hide/show header text end
		
	/*	add settings end */
		
	/*	add control start */
		
		/* added site logo control start */

		$wp_customize->add_control( new wp_customize_image_control($wp_customize, supreme_prefix().'_theme_settings[supreme_logo_url]', array(
			'label' => __(' upload image for logo',ADMINDOMAIN),
			'section' => 'templatic_logo_settings',
			'settings' => supreme_prefix().'_theme_settings[supreme_logo_url]',
		)));
		/* added site logo control finish */
		
		/* override class for image extension for favicon */
		class tmpl_customize_favicon_control extends wp_customize_image_control {
			public function __construct( $manager, $id, $args ) {

				$this->extensions[] = 'ico';/* new extension for upload */

				return parent::__construct( $manager, $id, $args );
			}
		}
		$wp_customize->add_control( new tmpl_customize_favicon_control($wp_customize, supreme_prefix().'_theme_settings[supreme_favicon_icon]', array(
			'label' => __(' upload favicon icon',ADMINDOMAIN),
			'section' => 'templatic_logo_settings',
			'settings' => supreme_prefix().'_theme_settings[supreme_favicon_icon]',
		)));
		
		/* added site favicon icon control finish */
		
		$wp_customize->add_control( 'supreme_site_description', array(
			'label' => __('Display tagline ',ADMINDOMAIN),
			'section' => 'title_tagline',
			'settings' => supreme_prefix().'_theme_settings[supreme_site_description]',
			'type' => 'checkbox',
			'priority' => 106
		));
		
		/* added show/hide site description control finish */
		
		$wp_customize->add_control( new supreme_custom_lable_control($wp_customize, supreme_prefix().'_theme_settings[footer_lbl]', array(
			'label' => __('footer text ( e.g. <p class="copyright">&copy;',ADMINDOMAIN).' '.date('y').' '.__('<a href="http://templatic.com/demos/responsive">responsive</a>. all rights reserved. </p>)',ADMINDOMAIN),
			'section' => 'supreme-core-footer',
			'priority' => 1,
		)));
		
		/* color settings control start */
		/*
			primary: 	 effect on buttons, links and main headings.
			secondary: 	 effect on sub-headings.
			content: 	 effect on content.
			sub-text: 	 effect on sub-texts.
			background:  effect on body & menu background. 
		
		*/
		$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color1', array(
			'label'   => __( 'body background color', ADMINDOMAIN ),
			'section' => 'colors',
			'settings'   => supreme_prefix().'_theme_settings[color_picker_color1]',
			'priority' => 1,
		) ) );
		
		$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color2', array(
			'label'   => __( 'primary and secondary navigation, footer, background color', ADMINDOMAIN ),
			'section' => 'colors',
			'settings'   => supreme_prefix().'_theme_settings[color_picker_color2]',
			'priority' => 2,	
		) ) );
		
		$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color3', array(
			'label'   => __( 'text color of content area', ADMINDOMAIN ),
			'section' => 'colors',
			'settings'   => supreme_prefix().'_theme_settings[color_picker_color3]',
			'priority' => 3,
		) ) );
		
		$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color4', array(
			'label'   => __( 'categories links, navigation links, footer links hover and sub text of page color', ADMINDOMAIN ),
			'section' => 'colors',
			'settings'   => supreme_prefix().'_theme_settings[color_picker_color4]',
			'priority' => 4,
		) ) );
		
		$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color5', array(
			'label'   => __( 'meta text, breadcrumb, pagination text and all grey color text', ADMINDOMAIN ),
			'section' => 'colors',
			'settings'   => supreme_prefix().'_theme_settings[color_picker_color5]',
			'priority' => 5,
		) ) );
		
		$wp_customize->add_control( new wp_customize_color_control( $wp_customize, 'color_picker_color6', array(
			'label'   => __( 'buttons, date and recurrences label color', ADMINDOMAIN ),
			'section' => 'colors',
			'settings'   => supreme_prefix().'_theme_settings[color_picker_color6]',
			'priority' => 6,
		) ) );
		
		/* remove wordpress default control start.*/
		$wp_customize->remove_control('background_color');
		
		
		
		/* added header background image control start */
		$wp_customize->add_control( new wp_customize_header_image_control( $wp_customize ) );
		
		$wp_customize->add_control( supreme_prefix().'_theme_settings[header_image_display]', array(
			'label' => __('display header image ( go in appearance -> header to set/change the image )',ADMINDOMAIN),
			'section' => 'header_image',
			'settings' => supreme_prefix().'_theme_settings[header_image_display]',
			'type' => 'select',
			'choices' => array(
								'before_nav' 	=> 'before secondary menu',	
								'after_nav' 	=> 'after secondary menu',	
							  ),
		));
		
		/* added display header text control start */
		$wp_customize->add_control( supreme_prefix().'_theme_settings[display_header_text]', array(
			'label' => __('Display site title',ADMINDOMAIN),
			'section' => 'title_tagline',
			'settings' => supreme_prefix().'_theme_settings[display_header_text]',
			'type' => 'checkbox',
			'priority' => 105,
		));
		
		//added header background image control finish
		$wp_customize->remove_control('header_textcolor');
		$wp_customize->remove_control('display_header_text');
	/*	add control end */
}
/*	function to create sections, settings, controls for wordpress customizer end.  */
/*  handles changing settings for the live preview of the theme start.  */	
	
	function templatic_customize_supreme_logo_url( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[supreme_logo_url]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_logo_url", $setting, $object );
	}
	
	function templatic_customize_supreme_favicon_icon( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[supreme_favicon_icon]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_favicon_icon", $setting, $object );
	}
	
	function templatic_customize_supreme_site_description( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[supreme_site_description]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_site_description", $setting, $object );
	}
	function templatic_customize_supreme_color1( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[color_picker_color1]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_color1", $setting, $object );
	}
	
	function templatic_customize_supreme_color2( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[color_picker_color2]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_color2", $setting, $object );
	}
	
	function templatic_customize_supreme_color3( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[color_picker_color3]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_color3", $setting, $object );
	}
	
	function templatic_customize_supreme_color4( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[color_picker_color4]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_color4", $setting, $object );
	}
	
	function templatic_customize_supreme_color5( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[color_picker_color5]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_color5", $setting, $object );
	}
	
	function templatic_customize_supreme_color6( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[color_picker_color6]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_supreme_color6", $setting, $object );
	}
	
	
	//background header image function start
	function templatic_customize_header_image_display( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[header_image_display]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_header_image_display", $setting, $object );
	}
	//background header image function end
	
	//display header text function start
	function templatic_customize_display_header_text( $setting, $object ) {
		
		/* make sure we kill evil scripts from users without the 'unfiltered_html' cap. */
		if ( supreme_prefix()."_theme_settings[display_header_text]" == $object->id && !current_user_can( 'unfiltered_html' )  )
			$setting = stripslashes( wp_filter_post_kses( addslashes( $setting ) ) );
		/* return the sanitized setting and apply filters. */
		return apply_filters( "templatic_customize_display_header_text", $setting, $object );
	}
	//display header text function end
	
/*  handles changing settings for the live preview of the theme end.  */	


/**
 * loads framework-specific customize control classes.  customize control classes extend the wordpress 
 * wp_customize_control class to create unique classes that can be used within the framework.
 */
function tmpldir_customize_controls() {
	 /*
	 * custom label customize control class.
	 */
	if(class_exists('wp_customize_control')){
		class supreme_custom_lable_control extends wp_customize_control{
			  public function render_content(){
	?>
<label> <span><?php echo esc_html( $this->label ); ?></span> </label>
<?php
			 }
		}
	}
	/**
	 * text area customize control class.
	 */
	if(class_exists('wp_customize_control')){
		class hybrid_customize_control_textarea extends wp_customize_control {
			public $type = 'textarea';
			public function __construct( $manager, $id, $args = array() ) {
				parent::__construct( $manager, $id, $args );
			}
			public function render_content() { ?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<div class="customize-control-content">
				  <textarea cols="25" rows="5" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
				</div>
			</label>
<?php }
		}
	}

}
/*
	To display header image
*/
if(!function_exists('get_header_image_location')){
	function get_header_image_location(){
		$theme_name = get_option('stylesheet');
		$theme_settings = get_option(supreme_prefix().'_theme_settings');
		if(!empty($theme_settings)){
			if(isset($theme_settings['header_image_display']) && @$theme_settings['header_image_display']!="" && @$theme_settings['header_image_display'] == 'before_nav'){
				return 0;
			}elseif(isset($theme_settings['header_image_display']) && @$theme_settings['header_image_display']!="" && @$theme_settings['header_image_display'] == 'after_nav'){
				return 1;
			}
		}
	}
}
?>
