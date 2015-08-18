<?php
/*
WPUpdates Theme Updater Class
http://wp-updates.com
v1.1
Example Usage:
require_once('wp-updates-theme.php');
new WPUpdatesThemeUpdater( 'http://wp-updates.com/api/1/theme', 1, basename(get_template_directory()) );
*/
/*
 * Function Name: supreme_child_update_theme
 * Return: update supreme child theme version after templatic member login
 */
global $theme_name;
$theme_name = basename(get_template_directory_uri());
function supreme_update_theme()
{
	global $theme_name;
	check_ajax_referer( $theme_name, '_ajax_nonce' );
	$theme_dir = rtrim(  get_template_directory_uri(), '/' );		
	require_once( get_template_directory() .  '/library/templatic_login.php' );	
	exit;
}
if( !class_exists('WPUpdates_Supreme_Updater') ) {
    class WPUpdates_Supreme_Updater {
    
		var $api_url;		
		var $theme_slug;
		function supreme_child_clear_update_transient() {
			$theme_name = basename(get_template_directory_uri());
			delete_transient( $theme_name.'-update' );
		}
		function __construct( $api_url,  $theme_slug ) {
			global $theme_name;
			$this->api_url = $api_url;    		
			$this->theme_slug = $theme_slug;
			if(is_multisite())
			{
				add_action( 'load-themes.php', 'wp_update_themes' );	
			}
			add_filter( 'pre_set_site_transient_update_themes', array(&$this, 'check_for_update_') );
			add_action( 'after_theme_row_'.$theme_name, array(&$this, 'supreme_theme_row') );
			add_action('wp_ajax_'.$theme_name,'supreme_update_theme');
			add_action('wp_ajax_changelog',array(&$this,'display_changelog'));
			// This is for testing only!
			//set_site_transient('update_themes', null);
			if(!strstr($_SERVER['REQUEST_URI'],'plugin-install.php') && !strstr($_SERVER['REQUEST_URI'],'update.php'))
			{
				add_filter( 'plugins_api_result', array(&$this, 'debug_result'), 10, 3 );
				add_action( 'load-update-core.php', array(&$this,'supreme_child_clear_update_transient') );
				add_action( 'load-themes.php', array(&$this, 'supreme_child_clear_update_transient') );
				if(!strstr($_SERVER['REQUEST_URI'],'/network/')){
					add_action( 'admin_notices', array(&$this, 'supreme_child_update_nag') );
				}
				delete_transient( $theme_name.'-update' );
			}
		}
    		function display_changelog(){	
		
			$options = array('method' => 'POST', 'timeout' => 3, 'body' => $body);
			$options['headers'] = array(
			  'Content-Type' => 'application/x-www-form-urlencoded; charset=' . get_option('blog_charset'),
			  'Content-Length' => strlen($body),
			  'User-Agent' => 'WordPress/' . get_bloginfo("version"),
			  'Referer' => home_url(), 
			);
			
			$raw_response = wp_remote_request('http://templatic.com/updates/api/change_log.php?Directory', $options);	
			if ( is_wp_error( $raw_response ) || 200 != $raw_response['response']['code']){
			  $page_text = __("Oops!! Something went wrong.<br/>Please try again or <a href='http://www.templatic.com'>contact us</a>.",ADMINDOMAIN);
			}
			else{
			  $page_text = $raw_response['body'];
			
			}
			echo  stripslashes($page_text);
			exit;
		}
		function supreme_child_update_nag($transient) {
			global $theme_response,$wp_version;			
			$update_themes=get_option($this->theme_slug.'_templatic_theme_version');
			$theme_name = basename(get_template_directory_uri());
    			$theme_data = wp_get_theme($theme_name);			
			$theme_version = $theme_data['Version'];			
			@$remote_version = (!empty( $update_themes) && $update_themes!="" )? $update_themes[$theme_name]['new_version']: $theme_response[$theme_name]['new_version'];			
			//compare theme version
			if (version_compare($theme_version, $remote_version, '<') && $theme_version!='')
			{	
				echo '<div id="update-nag">';
				 $new_version = version_compare($supreme_version, $remote_version, '<') ? sprintf(__('There is a new version of %s available.', ADMINDOMAIN) ,$theme_name) : '';		
				 $changelog = esc_url( add_query_arg( array( 'slug' => 'changelog', 'action' => 'changelog' , '_ajax_nonce' => wp_create_nonce( 'changelog' ), 'TB_iframe' => true ,'width'=>500,'height'=>400), admin_url( 'admin-ajax.php' ) ) );
				 $new_version .= ' <a class="thickbox" title="Directory" href="'.$changelog.'">'. sprintf(__('View version %s Details', ADMINDOMAIN), $remote_version) . '</a>. Or ' ;
			          $theme_name = basename(get_template_directory_uri());
					$ajax_url=site_url('/wp-admin/admin.php?page=tmpl_theme_update');
					$download= wp_nonce_url( self_admin_url('update.php?action=upgrade-theme&theme=').$theme_name, 'upgrade-theme_' . $theme_name);
					echo '<tr class="plugin-update-tr">
							<td colspan="3" class="plugin-update">
								<div class="update-message">' . $new_version . '<a href="'.$ajax_url.'" title="Directory Update">'.__( 'update now',ADMINDOMAIN).'</a></div>
							</td>
						 </tr>';
				echo '</div>';
			}
		}
	
		function check_for_update_( $transient ) {
			global $theme_response,$wp_version;

			/*Unset theme transient if same theme name also available in wordpress repository  */
			if(!empty($transient->response[$this->theme_slug]) && strpos($transient->response[$this->theme_slug]['url'],'wordpress.org') !== false){
				unset($transient->response[$this->theme_slug]);			
			}
			if (empty($transient->checked)) return $transient;			
			
			$request_args = array(
				'slug' => $this->theme_slug,
				'version' => $transient->checked[$this->theme_slug]
				);
			$request_string = $this->prepare_request( 'templatic_theme_update', $request_args );
			$raw_response = wp_remote_post( $this->api_url, $request_string );				
			$response = null;
			if( !is_wp_error($raw_response) && ($raw_response['response']['code'] == 200) ){
					$response = json_decode(@$raw_response['body']);
				
			}
			if( !empty($response) ) {// Feed the update data into WP updater
				$transient->response[$this->theme_slug] = (array)$response; 
				$theme_response[$this->theme_slug] = (array)$response; 			
				update_option($this->theme_slug.'_templatic_theme_version',$theme_response);
			}
			
			return $transient;
		}        
		
		
		/*
		 * add action for set the auto update for tevolution plugin
		 * Functio Name: tevolution_plugin_row
		 * Return : Display the plugin new version update message
		 */
		function supreme_theme_row()
		{
			global $theme_response,$wp_version;			
			$update_themes=get_option($this->theme_slug.'_templatic_theme_version');
			$theme_name = basename(get_template_directory_uri());
    		$theme_data = wp_get_theme($theme_name);			
			$theme_version = $theme_data['Version'];			
			@$remote_version = (!empty( $update_themes) && $update_themes!="" )? $update_themes[$theme_name]['new_version']: $theme_response[$theme_name]['new_version'];			
			//compare theme version
			
			if (version_compare($theme_version, $remote_version, '<') && $theme_version!='')
			{	
			   	$new_version = version_compare($supreme_version, $remote_version, '<') ? __('There is a new version of Directory available.', ADMINDOMAIN) : '';
				$changelog = esc_url( add_query_arg( array( 'slug' => 'changelog', 'action' => 'changelog' , '_ajax_nonce' => wp_create_nonce( 'changelog' ), 'TB_iframe' => true ,'width'=>500,'height'=>400), admin_url( 'admin-ajax.php' ) ) );
				 $new_version .= ' <a class="thickbox" title="Directory" href="'.$changelog.'">'. sprintf(__('View version %s Details', ADMINDOMAIN), $remote_version) . '</a>. Or ' ;
			    $theme_name = basename(get_template_directory());
				$ajax_url = esc_url( add_query_arg( array( 'slug' => $theme_name, 'action' => $theme_name , '_ajax_nonce' => wp_create_nonce(  $theme_name ), 'TB_iframe' => true ,'width'=>500,'height'=>400), admin_url( 'admin-ajax.php' ) ) );
				$file= get_template_directory().'/style.css';
				$download= wp_nonce_url( self_admin_url('update.php?action=upgrade-theme&theme=').$file, 'upgrade-theme_' . $file);
				echo '</tr><tr class="plugin-update-tr"><td colspan="3" class="plugin-update"><div class="update-message">' . $new_version .'<a href='.$ajax_url.' class="thickbox" title="Directory Update">'.__( 'update now',ADMINDOMAIN).'</a></div></td>';
	
			}
		}
		function prepare_request( $action, $args ) {
			global $wp_version;
			
			return array(
				'body' => array(
					'action' => $action, 
					'request' => serialize($args),
					'api-key' => md5(home_url())
				),
				'user-agent' => 'WordPress/'. $wp_version .'; '. home_url()
			);	
		}//finish the prepare requst function
    }
} ?>