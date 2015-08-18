<?php
/*
 * supreme Framework Version
 */
function supreme_version_init () {
    $suprem_framework_version = '2.0.3';
    if ( get_option( 'suprem_framework_version' ) != $suprem_framework_version ) {
    		update_option( 'suprem_framework_version', $suprem_framework_version );
    }
}
add_action( 'init', 'supreme_version_init', 10 );


/* frame work update templatic menu*/
function tmpl_theme_update(){
	
	require_once(TEMPLATE_DIR."library/templatic_login.php");
}


/* call theme update notification page */
add_action('admin_menu','supreme_templatic_menu');
function supreme_templatic_menu(){
	
	add_submenu_page( 'templatic_menu',  __('Theme Update',ADMINDOMAIN), __('Theme Update',ADMINDOMAIN), 'administrator', 'tmpl_theme_update', 'tmpl_theme_update',27 );	
}

?>