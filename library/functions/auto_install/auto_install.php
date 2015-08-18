<?php
/* File contain the functions which run & execute the auto install */
set_time_limit(0);
global  $wpdb,$pagenow;

/* Show notifications As per plug-ins activation */
$related_addon= wp_get_theme();

if(file_exists(WP_PLUGIN_DIR."/".'Tevolution-'.$related_addon.'/'.strtolower($related_addon).'.php')){
	$related_addon= wp_get_theme();
}else{
	$related_addon= "Directory";
}
if((!is_plugin_active('Tevolution/templatic.php') || !is_plugin_active('Tevolution-'.$related_addon.'/'.strtolower($related_addon).'.php')) && is_admin() && 'themes.php' == $pagenow ){
	add_action("admin_notices", "activate_eco_plugin"); // action show notification when tevolution not activated.
}else{
		if(false==get_option( 'hide_ajax_notification' ) && strstr($_SERVER['REQUEST_URI'],'themes.php') && (!isset($_REQUEST['page']))) {
			add_action("admin_notices", "tmpl_autoinstall"); 
		}
	
}

/*
Name: activate_eco_plugin
Desc: Return notifications to admin - to activate tevolution and related plug-in 
*/
function activate_eco_plugin(){
	global $pagenow;
	$url = home_url().'/wp-admin/plugins.php';
	add_css_to_admin();
	$current_system = '';
	$related_addon= wp_get_theme();
	
	if(file_exists(WP_PLUGIN_DIR."/".'Tevolution-'.$related_addon.'/'.strtolower($related_addon).'.php')){
		$related_addon= wp_get_theme();
	}else{
		$related_addon= "Directory";
	}

	if(!is_plugin_active('Tevolution/templatic.php') && is_admin() ){
		$current_system = "<a id='templatic_plugin' href=".$url." style='color:#21759B'>".__('Tevolution',ADMINDOMAIN)."</a>";
	}	
	if(!is_plugin_active('Tevolution-'.$related_addon.'/'.strtolower($related_addon).'.php') && is_admin() ){
		if($current_system != '')
			$current_system .= __(' and ', ADMINDOMAIN);
		$current_system .= '<a id="booking_plugin" href="'.$url.'" style="color:#21759B">'.__('Tevolution - ',ADMINDOMAIN).$related_addon.'</a>';
	}
	
	
	if(!is_plugin_active('Tevolution-'.$related_addon.'/'.strtolower($related_addon).'.php') || !is_plugin_active('Tevolution/templatic.php')):
	
?>
	<div class="error" style="padding:10px 0 10px 10px;font-weight:bold;"> <span>
  <?php echo sprintf(__('Thanks for choosing templatic themes, It seems to want Tevolution-Directory activated first before the warning and verification request go away. Please download and activate %s addons to get started with %s website.',ADMINDOMAIN),$current_system,'<span style="color:#000">'. @wp_get_theme().'</span>');?>
  </span> 
  </div>
<?php 	
	endif;
}

/* css to hide notification */
add_action('admin_notices','add_css_to_admin');
function add_css_to_admin(){
	echo '<style type="text/css">
		#message1{
			display:none;
		}
	</style>';
}
	
/* Activate add on when run the auto install */
function tmpl_autoinstall()
{
global $wpdb;
	$wp_user_roles_arr = get_option($wpdb->prefix.'user_roles');
	global $wpdb;
	
		$post_counts = $wpdb->get_var("select count(p.ID) from $wpdb->posts p join $wpdb->postmeta pm on pm.post_id=p.ID where (meta_key='pt_dummy_content' || meta_key='tl_dummy_content' || meta_key='auto_install') and (meta_value=1 || meta_value='auto_install')");
		/* help links */
		$menu_msg1 = "<ul><li><a href='".site_url("/wp-admin/post-new.php?post_type=listing")."'>".__('Add a listing',ADMINDOMAIN)."</a></li>";
		$menu_msg1 .= "<li><a href='".site_url("/wp-admin/user-new.php")."'>".__('Add listing agents',ADMINDOMAIN)."</a></li>";
		$menu_msg1 .= "<li><a href='".site_url("/wp-admin/admin.php?page=monetization&action=add_package&tab=packages")."'>".__("Set pricing options",ADMINDOMAIN)."</a></li>";
		$menu_msg1 .= "<li><a href='".site_url("/wp-admin/admin.php?page=monetization&tab=payment_options")."'>".__('Setup payment types',ADMINDOMAIN)."</a></li></ul>";

		$menu_msg2 = "<ul><li><a href='".site_url("/wp-admin/admin.php?page=templatic_settings#listing_page_settings")."'>".__('Setup category page',ADMINDOMAIN)."</a> and <a href='".site_url("/wp-admin/admin.php?page=templatic_settings#detail_page_settings")."'>".__('detail page',ADMINDOMAIN)."</a></li>";
		$menu_msg2 .= "<li><a href='".site_url("/wp-admin/admin.php?page=templatic_settings#registration_page_setup")."'>".__('Setup registration',ADMINDOMAIN)."</a> and <a href='".site_url("/wp-admin/admin.php?page=templatic_settings#submit_page_settings")."'>".__('submission page',ADMINDOMAIN)."</a></li>";
		$menu_msg2 .= "<li><a href='".site_url("/wp-admin/admin.php?page=location_settings&location_tabs=location_manage_locations&locations_subtabs=city_manage_locations")."'>".__("Manage cities and locations",ADMINDOMAIN)."</a></li>";
		$menu_msg2 .= "<li><a href='".site_url("/wp-admin/admin.php?page=templatic_settings&tab=email")."'>".__('Manage and customize emails',ADMINDOMAIN)."</a></li></ul>";
		
		$menu_msg3 = "<ul><li><a href='".site_url("/wp-admin/widgets.php")."'>Manage Widgets </a>,  <a href='".site_url("/wp-admin/customize.php")."'>".__('Add your logo',ADMINDOMAIN)." </a></li>";
		$menu_msg3 .= "<li><a href='".site_url("/wp-admin/customize.php")."'>".__('Change site colors',ADMINDOMAIN)." </a></li>";
		$menu_msg3 .= "<li><a href='".site_url("/wp-admin/nav-menus.php?action=edit")."'>".__('Manage menu navigation',ADMINDOMAIN)."</a></li>";
		$menu_msg3 .= "<li><a href='".site_url("/wp-admin/widgets.php")."'>".__('Manage sidebar widgets ',ADMINDOMAIN)." </a></li></ul>";
		$my_theme = wp_get_theme();
		$theme_name = $my_theme->get( 'Name' );
		$version = $my_theme->get( 'Version' );
		$dummydata_title .= '<h3 class="twp-act-msg">'.sprintf (__('Thank you. %s (<small>%s</small>) theme is now activated.',ADMINDOMAIN),'Directory',strtolower($version)).' <a href="'.site_url().'">Visit Your Site</a></h3>';
		
		/* theme message */	
		$dummy_theme_message .='<div class="tmpl-wp-desc">The Directory theme is ideal for creating and monetizing an online listings directory. To help you setup the theme, please refer to its <a href="http://templatic.com/docs/directory-theme-guide/">Installation Guide</a> to help you better understand the theme&#39;s functions. To help you get started, we have outlined a few recommended steps below to get you going. Should you need any assistance please also visit the Directory <a href="http://templatic.com/forums/viewforum.php?f=119">support forum</a> or use <a href="http://templatic.com/docs/submit-a-ticket/">helpdesk</a>. Need some extra features added to the theme? Please check out all of the available add-ons for it <a href="http://templatic.com/plugins/directory-add-ons/"> here</a>. </div>';
		
		/* guilde and support link */	
		
		$dummy_nstallation_link  = '<div class="tmpl-ai-btm-links clearfix"><ul><li>Need Help?</li><li><a href="http://templatic.com/docs/directory-theme-guide/">Theme & Installation Guide</a></li><li><a href="http://templatic.com/forums/viewforum.php?f=119">Support Forum</a></li><li><a href="http://templatic.com/docs/submit-a-ticket/">HelpDesk</a></li></ul><p><a href="http://templatic.com">Team Templatic</a> at your service</p></div>';;
		if($post_counts>0){
			$theme_name = get_option('stylesheet');
			
			$dummy_data_msg='';
			$dummy_data_msg = $dummydata_title;
			
			if( false == get_option( 'hide_install_notification' ) ) 
			{
				$dummy_data_msg .= '<div id="install-notification" class="tmpl-auto-install-yb" ><h4>'.__("I don&rsquo;t need it, please",ADMINDOMAIN).' </h4> <span><a class="button button-primary delete-data-button" href="'.home_url().'/wp-admin/themes.php?dummy=del">'.__('Delete sample data',ADMINDOMAIN).'</a></span> <p>Please note that deleting sample data will also remove any content you may have added or edited on any sample content. Deleting the sample data will also remove all sample widgets which were inserted.</p>';
				$dummy_data_msg .='<span id="install-notification-nonce" class="hidden">' . wp_create_nonce( 'install-notification-nonce' ) . '</span><a href="javascript:;" id="dismiss-autoinstall-notification" class="install-dismiss" style="float:right">Dismiss</a></div>';
				
			}
			
			$dummy_data_msg .= apply_filters('tmpl_after_install_delete_button',$dummy_data_msg);
			
			$dummy_data_msg .= $dummy_theme_message;
			
			$dummy_data_msg .='<div class="wrapper_templatic_auto_install_col3"><div class="templatic_auto_install_col3"><h4>'.__('Next Steps',ADMINDOMAIN).'</h4>'.$menu_msg1.'</div>';
			$dummy_data_msg .='<div class="templatic_auto_install_col3"><h4>'.__('Advance Directory Options',ADMINDOMAIN).'</h4>'.$menu_msg2.'</div>';
			$dummy_data_msg .='<div class="templatic_auto_install_col3"><h4>'.__('Customize Your Website',ADMINDOMAIN).'</h4>'.$menu_msg3.'</div></div>';
			$dummy_data_msg .='<div class="ref-tev-msg">'.__('Please refer to &quot;Tevolution&quot; and other sections on the left side menu for more of the advanced options.',ADMINDOMAIN).'</div>';
			$dummy_data_msg .= $dummy_nstallation_link;
			
		}else{
			$theme_name = get_option('stylesheet');
			$dummy_data_msg='';
			$dummy_data_msg = $dummydata_title;
			if( false == get_option( 'hide_install_notification' ) ) {
				$dummy_data_msg .= '<div id="install-notification" class="tmpl-auto-install-yb" ><h4>'.__("Your site not looking like our online demo?",ADMINDOMAIN).' </h4> <span><a class="button button-primary" href="'.home_url().'/wp-admin/themes.php?dummy_insert=1">'.__('Install sample data',ADMINDOMAIN).'</a></span> <p>So that you don&prime;t start on a blank site, the sample data will help you get started with the theme. The content includes some default settings, widgets in their locations, pages, posts and a few dummy listings.</p>';
				$dummy_data_msg .='<span id="install-notification-nonce" class="hidden">' . wp_create_nonce( 'install-notification-nonce' ) . '</span><a href="javascript:;" id="dismiss-autoinstall-notification" class="install-dismiss" style="float:right">Dismiss</a></div>';
			}
			
			$dummy_data_msg .= apply_filters('tmpl_after_install_delete_button',$dummy_data_msg);
			
			$dummy_data_msg .= $dummy_theme_message;
			
			$dummy_data_msg .='<div class="wrapper_templatic_auto_install_col3"><div class="templatic_auto_install_col3"><h4>'.__('Next Steps',ADMINDOMAIN).'</h4>'.$menu_msg1.'</div>';
			$dummy_data_msg .='<div class="templatic_auto_install_col3"><h4>'.__('Advance Directory Options',ADMINDOMAIN).'</h4>'.$menu_msg2.'</div>';
			$dummy_data_msg .='<div class="templatic_auto_install_col3"><h4>'.__('Customize Your Website',ADMINDOMAIN).'</h4>'.$menu_msg3.'</div></div>';
			$dummy_data_msg .='<div class="ref-tev-msg">'.__('Please refer to &quot;Tevolution&quot; and other sections on the left side menu for more of the advanced options.',ADMINDOMAIN).'</div>';
			$dummy_data_msg .= $dummy_nstallation_link;
		}
		
		if(isset($_REQUEST['dummy_insert']) && $_REQUEST['dummy_insert']){
			$theme_name = str_replace(' ','',strtolower(wp_get_theme()));
			require_once (get_template_directory().'/library/functions/auto_install/auto_install_data.php');
			
			$args = array(
						'post_type' => 'page',
						'meta_key' => '_wp_page_template',
						'meta_value' => 'page-templates/front-page.php'
						);
			$page_query = new WP_Query($args);
			$front_page_id = $page_query->post->ID;
			update_option('page_on_front',$front_page_id);
			
			$args = array('post_type' => 'page',
						'meta_key' => 'page_for_posts',
						'meta_value' => '1'
						);
			$page_query = new WP_Query($args);
			
			$blog_page_id = $page_query->post->ID;
			update_option('page_for_posts',$blog_page_id);
			
			/*BEING Cretae primary menu */
			$nav_menus=wp_get_nav_menus( array('orderby' => 'name') );
			$navmenu=array();
			if(!$nav_menus){
				foreach($nav_menus as $menus){
					$navmenu[]=$menus->slug;	
				}
				/*Primary menu */
				if(!in_array('primary',$navmenu)){
					$primary_post_info[] = array('post_title'=>'Home','post_id'   =>$front_page_id,'_menu_item_type'=>'post_type','_menu_item_object'=>'page','menu_item_parent'=>0);
					/*Get submit listing page id */
					$submit_listing_id = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'submit-listing' and $wpdb->posts.post_type = 'page'");
					$primary_post_info[] = array('post_title'=>'','post_id'   =>$submit_listing_id->ID,'_menu_item_type'=>'post_type','_menu_item_object'=>'page','menu_item_parent'=>0);
					/*Insert primary menu */	
					wp_insert_name_menu_auto_install($primary_post_info,'primary');					
					
				}// end primary nav menu if condition
				/*Secondary menu */
				if(!in_array('secondary',$navmenu)){
					/*Home Page */
					$secondary_post_info[] = array('post_title'=>'Home','post_id'   =>$front_page_id,'_menu_item_type'=>'post_type','_menu_item_object'=>'page','menu_item_parent'=>0);
					
					/*Get the  listing category list */
					$args = array( 'taxonomy' =>'listingcategory','orderby'=> 'id','order' => 'ASC', );
					$terms = get_terms('listingcategory', $args);
					if($terms){
						$i=0;
						foreach($terms as $term){
							$menu_item_parent=($i!=0)?'1':'0';
							$secondary_post_info[] = array('post_title'=>'','post_content'=>$term->description,'post_id' =>$term->term_id,'_menu_item_type'=>'taxonomy','_menu_item_object'=>'listingcategory','menu_item_parent'=>$menu_item_parent);
							$i++;
						}
					}
					/*finish listingcategory menu */
					/*Get people page id */
					$people_id = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'people' and $wpdb->posts.post_type = 'page'");
					$secondary_post_info[] = array('post_title'=>'','post_id'   =>$people_id->ID,'_menu_item_type'=>'post_type','_menu_item_object'=>'page','menu_item_parent'=>0);
					/*Get all in one map page id */
					$all_in_one_map_id = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'all-in-one-map' and $wpdb->posts.post_type = 'page'");
					$secondary_post_info[] = array('post_title'=>'','post_id'   =>$all_in_one_map_id->ID,'_menu_item_type'=>'post_type','_menu_item_object'=>'page','menu_item_parent'=>0);					
					
					/*Blog menu */
					$args = array( 'taxonomy' =>'category','orderby'=> 'id','order' => 'ASC','exclude'=>array('1'));
					$terms = get_terms('category', $args);
					$secondary_post_info[] = array('post_title'=>'Blog','post_id' =>$blog_page_id,'_menu_item_type'=>'post_type','_menu_item_object'=>'page','menu_item_parent'=>0);
					
					if($terms){
						$i=0;
						foreach($terms as $term){
							$menu_item_parent=1;
							$secondary_post_info[] = array('post_title'=>'','post_content'=>$term->description,'post_id' =>$term->term_id,'_menu_item_type'=>'taxonomy','_menu_item_object'=>'category','menu_item_parent'=>$menu_item_parent);
							$i++;
						}
					}
					/*finish blog menu */
					
					/*Get contact us page id */
					$contact_us_id = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'contact-us' and $wpdb->posts.post_type = 'page'");
					$secondary_post_info[] = array('post_title'=>'','post_id'   =>$contact_us_id->ID,'_menu_item_type'=>'post_type','_menu_item_object'=>'page','menu_item_parent'=>0);
					/*Get How to setup your site page id */
					$howtosetup_id = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'how-to-setup-your-site' and $wpdb->posts.post_type = 'page'");
					$secondary_post_info[] = array('post_title'=>'','post_id'   =>$howtosetup_id->ID,'_menu_item_type'=>'post_type','_menu_item_object'=>'page','menu_item_parent'=>0);
					/*Get extend_id page id */
					$extend_id = $wpdb->get_row("SELECT ID FROM $wpdb->posts WHERE $wpdb->posts.post_name = 'extend' and $wpdb->posts.post_type = 'page'");
					$secondary_post_info[] = array('post_title'=>'','post_id'   =>$extend_id->ID,'_menu_item_type'=>'post_type','_menu_item_object'=>'page','menu_item_parent'=>0);
					/*Insert secondary menu */	
					wp_insert_name_menu_auto_install($secondary_post_info,'secondary');
				}// end secondary nav menu if condition
			}

			/*END primary menu */
			
			wp_redirect(admin_url().'themes.php?x=y');
		}
		if(isset($_REQUEST['dummy']) && $_REQUEST['dummy']=='del'){
			tmpl_delete_dummy_data();
			wp_redirect(admin_url().'themes.php');
		}
		
		define('THEME_ACTIVE_MESSAGE','<div id="ajax-notification" class="welcome-panel tmpl-welcome-panel"><div class="welcome-panel-content">'.$dummy_data_msg.'<span id="ajax-notification-nonce" class="hidden">' . wp_create_nonce( 'ajax-notification-nonce' ) . '</span><a href="javascript:;" id="dismiss-ajax-notification" class="templatic-dismiss" style="float:right">Dismiss</a></div></div>');
		echo THEME_ACTIVE_MESSAGE;
}

/*
 To delete dummy data
*/
function tmpl_delete_dummy_data()
{
	global $wpdb;
	delete_option('sidebars_widgets'); //delete widgets
	$productArray = array();
	$pids_sql = "select p.ID from $wpdb->posts p join $wpdb->postmeta pm on pm.post_id=p.ID where (meta_key='pt_dummy_content' || meta_key='tl_dummy_content' || meta_key='auto_install') and (meta_value=1 || meta_value='auto_install')";
	$pids_info = $wpdb->get_results($pids_sql);
	foreach($pids_info as $pids_info_obj)
	{
		wp_delete_post($pids_info_obj->ID,true);
	}
	$widget_array = array(
		'widget_social_media',
		'widget_googlemap_homepage',
		'widget_templatic_text',
		'widget_supreme_subscriber_widget',
		'widget_hybrid-categories',
		'widget_widget_directory_featured_category_list',
		'widget_directory_featured_homepage_listing',
		'widget_templatic_key_search_widget',
		'widget_flicker_widget',
		'widget_hybrid-pages',
		'widget_templatic_browse_by_categories',
		'widget_templatic_aboust_us',
		'widget_supreme_facebook',
		'widget_directory_mile_range_widget',
		'widget_directory_neighborhood',
		'widget_templatic_popular_post_technews',
		'widget_templatic_twiter',
		'widget_text',
		'widget_templatic_google_map',
		'widget_supreme_facebook',
	);
	foreach($widget_array as $widget_array){
		delete_option($widget_array); //delete widgets
	}
}
/* Setting For dismiss auto install notification message from themes.php START */
wp_register_theme_activation_hook( wp_get_theme(), 'activate'  );
wp_register_theme_deactivation_hook( wp_get_theme(), 'deactivate'  );
function wp_register_theme_activation_hook($code, $function) {

	add_option( 'hide_ajax_notification',  false);
	
}
function wp_register_theme_deactivation_hook($code, $function) {
	/* store function in code specific global */
	$GLOBALS["wp_register_theme_deactivation_hook_function" . $code]=$function;
 
    /* create a runtime function which will delete the option set while activation of this theme and will call deactivation function provided in $function */
	$fn=create_function('$theme', ' call_user_func($GLOBALS["wp_register_theme_deactivation_hook_function' . $code . '"]); delete_option( "hide_ajax_notification" );');
 
	/* Your theme can perceive this hook as a deactivation hook.*/
	add_action("switch_theme", $fn);
}
add_action( 'admin_footer', 'register_admin_scripts'  );
add_action( 'wp_ajax_hide_admin_notification', 'hide_admin_notification' );
add_action( 'wp_ajax_hide_authoinstall_notification', 'tmpl_hide_autoinstall_notification' );
function activate() {
	add_option( 'hide_ajax_notification', false );
	add_option( 'hide_install_notification', false );
}
function deactivate() {
	delete_option( 'hide_ajax_notification' );
	delete_option( 'hide_install_notification' );
}
function register_admin_scripts() {
	wp_register_script( 'ajax-notification-admin', get_template_directory_uri().'/js/_admin-install.js'  );
	wp_enqueue_script( 'ajax-notification-admin' );
}
function hide_admin_notification() {
	if( wp_verify_nonce( $_REQUEST['nonce'], 'ajax-notification-nonce' ) ) {
		if( update_option( 'hide_ajax_notification', true ) ) {
			die( '1' );
		} else {
			die( '0' );
		}
	}
}
/* to hide auto install notifications */
function tmpl_hide_autoinstall_notification() {
	if( wp_verify_nonce( $_REQUEST['nonce'], 'install-notification-nonce' ) ) {
		if( update_option( 'hide_install_notification', true ) ) { 
			die( '1' );
		} else {
			die( '0' );
		}
	}
}
/* Setting For dismiss auto install notification message from themes.php END */
/*
Name : set_page_info_autorun
Description : update pages in autorun
*/
function set_page_info_autorun($pages_array,$page_info_arr)
{
	global $wpdb,$current_user;
	for($i=0;$i<count($page_info_arr);$i++)
	{ 
		$post_title = $page_info_arr[$i]['post_title'];
		$post_count = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts where post_title like \"$post_title\" and post_type='page' and post_status in ('publish','draft')");
		if(!$post_count)
		{
			$post_info_arr = array();
			$catids_arr = array();
			$my_post = array();
			$post_info_arr = $page_info_arr[$i];
			$my_post['post_title'] = $post_info_arr['post_title'];
			$my_post['post_content'] = $post_info_arr['post_content'];
			$my_post['post_type'] = 'page';
			if(isset($post_info_arr['post_author']) && $post_info_arr['post_author'])
			{
				$my_post['post_author'] = $post_info_arr['post_author'];
			}else
			{
				$my_post['post_author'] = 1;
			}
			$my_post['post_status'] = 'publish';
	
			$last_postid = wp_insert_post( $my_post );
			$post_meta = $post_info_arr['post_meta'];
			if($post_meta)
			{
				foreach($post_meta as $mkey=>$mval)
				{
					update_post_meta($last_postid, $mkey, $mval);
				}
			}
			
			$post_image = (isset($post_info_arr['post_image']))?$post_info_arr['post_image']:'';
			if($post_image)
			{
				for($m=0;$m<count($post_image);$m++)
				{
					$menu_order = $m+1;
					$image_name_arr = explode('/',$post_image[$m]);
					$img_name = $image_name_arr[count($image_name_arr)-1];
					$img_name_arr = explode('.',$img_name);
					$post_img = array();
					$post_img['post_title'] = $img_name_arr[0];
					$post_img['post_status'] = 'attachment';
					$post_img['post_parent'] = $last_postid;
					$post_img['post_type'] = 'attachment';
					$post_img['post_mime_type'] = 'image/jpeg';
					$post_img['menu_order'] = $menu_order;
					$last_postimage_id = wp_insert_post( $post_img );
					update_post_meta($last_postimage_id, '_wp_attached_file', $post_image[$m]);					
					$post_attach_arr = array(
										"width"	=>	580,
										"height" =>	480,
										"hwstring_small"=> "height='150' width='150'",
										"file"	=> $post_image[$m],
										//"sizes"=> $sizes_info_array,
										);
					wp_update_attachment_metadata( $last_postimage_id, $post_attach_arr );
				}
			}
		}
	}
}


/* This function call for create nav menu on auto install*/
function wp_insert_name_menu_auto_install($post_info,$menu_type){	

	$i=0;
	foreach($post_info as $post){
		$args=array('post_type'=>'nav_menu_item','post_title'=>$post['post_title'],'post_status'=>'publish','menu_order'=>$i);
		/*insert post */
		$post_id=wp_insert_post($args);
		$args=array('ID'=>$post_id,'post_type'=>'nav_menu_item','post_title'=>$post['post_title'],'post_status'=>'publish','post_name'=>$post_id,'menu_order'=>$i);
		/*update inserted post update */
		wp_update_post($args);		
		$i++;
		
		if($post['menu_item_parent']==1){
			$menu_item_parent=$last_post_id;
		}else{
			$menu_item_parent=0;
			$last_post_id=$post_id;
		}
		
		/*update nav menu post meta option */
		update_post_meta($post_id,'_menu_item_type',$post['_menu_item_type']);
		update_post_meta($post_id,'_menu_item_menu_item_parent',$menu_item_parent);
		update_post_meta($post_id,'_menu_item_object_id',$post['post_id']);
		update_post_meta($post_id,'_menu_item_object',$post['_menu_item_object']);
		update_post_meta($post_id,'_menu_item_target','');
		update_post_meta($post_id,'_menu_item_classes',array());
		update_post_meta($post_id,'_menu_item_xfn','');
		update_post_meta($post_id,'_menu_item_url','');
		
		/* Get the nav menu*/
		wp_set_post_terms($post_id,$menu_type,'nav_menu',true);
	}
	
	/*get the nav menu list */
	$nav_menus=wp_get_nav_menus( array('orderby' => 'name') );
	foreach($nav_menus as $menus){
		if($menus->slug==$menu_type){
			$term_id=$menus->term_id;
			break;
		}
	}
	/*Set the nav menu location option as per menu type */
	$nav_menu_locations = get_option('theme_mods_Directory');
	$nav_menu_locations['nav_menu_locations'][$menu_type]=$term_id;	
	update_option('theme_mods_Directory',$nav_menu_locations);
}


/*Display dummy data insert and delete notification msg on tevolution overview box  */
add_filter('tevoluton_overviewbox_datacontent','directory_tevoluton_overviewbox_datacontent');
function directory_tevoluton_overviewbox_datacontent($dummy_data_msg){
	global $wpdb;	
	$post_counts = $wpdb->get_var("select count(post_id) from $wpdb->postmeta where (meta_key='pt_dummy_content' || meta_key='tl_dummy_content') and meta_value=1");	
	$dummy_data_msg='';	
	if((strstr($_SERVER['REQUEST_URI'],'themes.php') && !isset($_REQUEST['page'])) && @$_REQUEST['template']==''){
		if($post_counts>0){
			$dummy_data_msg .= '<div id="install-notification" class="tmpl-auto-install-yb" ><h4>'.__("I don&rsquo;t need it, please",ADMINDOMAIN).' </h4> <span><a class="button button-primary delete-data-button" href="'.home_url().'/wp-admin/themes.php?dummy=del">'.__('Delete sample data',ADMINDOMAIN).'</a></span> <p>Please note that deleting sample data will also remove any content you may have added or edited on any sample content. Deleting the sample data will also remove all sample widgets which were inserted.</p>';
			$dummy_data_msg .='<span id="install-notification-nonce" class="hidden">' . wp_create_nonce( 'install-notification-nonce' ) . '</span></div>';

		}else{
			$dummy_data_msg .= '<div id="install-notification" class="tmpl-auto-install-yb" ><h4>'.__("Your site not looking like our online demo?",ADMINDOMAIN).' </h4> <span><a class="button button-primary" href="'.home_url().'/wp-admin/themes.php?dummy_insert=1">'.__('Install sample data',ADMINDOMAIN).'</a></span> <p>Please note that deleting sample data will also remove any content you may have added or edited on any sample content. Deleting the sample data will also remove all sample widgets which were inserted.</p>';
			$dummy_data_msg .='<span id="install-notification-nonce" class="hidden">' . wp_create_nonce( 'install-notification-nonce' ) . '</span></div>';

		}
	}
	return $dummy_data_msg;
}
?>