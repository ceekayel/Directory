<?php
/*
 * Get Theme Version
 */
function tmpl_get_theme_version () {		
	$theme_name = basename(get_template_directory());
	$theme_data = templatic_get_theme_data(get_template_directory().'/style.css');
	return $theme_version = $theme_data['Version'];	
}
/* GET REMOTE VERSION */
function tmpl_get_remote_verison(){
	global $theme_response,$wp_version;			
	$theme_name = basename(get_template_directory());
	$remote_version = get_option($theme_name."_templatic_theme_version");
	return $remote_version = $remote_version[$theme_name]['new_version'];	
}
	global $current_user;
	
	$theme_name = basename(get_template_directory());
	$self_url = esc_url( add_query_arg( array( 'slug' => $theme_name, 'action' => $theme_name , '_ajax_nonce' => wp_create_nonce( $theme_name ), 'TB_iframe' => true ), admin_url( 'admin-ajax.php' ) ));
	$th_name =  basename(get_template_directory());
	if(isset($_POST['templatic_login']) && isset($_POST['templatic_username']) && $_POST['templatic_username']!=''  && isset($_POST['templatic_password']) && $_POST['templatic_password']!='')
	{ 
		$arg=array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'body' => array( 'username' => $_POST['templatic_username'], 'password' => $_POST['templatic_password']),
			'cookies' => array()
		    );
		$warnning_message='';
		$response = wp_remote_post('http://templatic.com/members/login_api.php',$arg );	
	
		if( is_wp_error( $response ) ) {
		  	$warnning_message=__("Invalid UserName or password. are you using templatic member username and password?",ADMINDOMAIN);
		} else { 
		  	$data = json_decode($response['body']);
		}
	
		/*Return error message */
		if(isset($data->error_message) && $data->error_message!='')
		{
			$warnning_message=$data->error_message;			
		}
		/*Finish error message */
		$data->product=(array)$data->product;		
		
		if(isset($data->product) && is_array($data->product) && !empty($data->product))
		{		
			foreach($data->product as $key=>$val)
			{
				$product[]=$key;
			}			
			if(in_array($th_name.' - developer license',$product) || in_array($th_name.' - standard license',$product))
			{
				$successfull_login=1;				
				$download_link=$data->product[$th_name.' - developer license'];
			}else
			{
				$warnning_message= sprintf(__("Update was unable to continue because %s theme doesn't seem to be associated with your Templatic account.",ADMINDOMAIN),$th_name)." ".__("Please",ADMINDOMAIN)." <a href='http://templatic.com/contact/' target='_blank'>".__("contact us",ADMINDOMAIN)."</a> ".__("if this message is showing in error.",ADMINDOMAIN);
			}			
		}elseif($data->error_message!=''){
			$warnning_message=$data->error_message;	
		}
		
	}else{
		if(isset($_POST['templatic_login']) && ($_POST['templatic_username'] =='' || $_POST['templatic_password']=='')){
			$warnning_message=__("Invalid UserName or password. Please enter templatic member's username and password.",ADMINDOMAIN); 
		}
	}
			$theme_version = tmpl_get_theme_version();
			$remote_version = tmpl_get_remote_verison();
			
			/* set flag on updates */
			if (version_compare($theme_version, $remote_version, '<') && $theme_version!='')
			{	
				$flag =1;
			}else{
				$flag=0;
			} ?>
<div class='wrap templatic_login'>
  <?php if($flag ==1){ ?>
  <div id="update-nag">
    <p style=" clear:both;"> <?php echo sprintf(__('The new version of %s is available.',ADMINDOMAIN),$theme_name); ?></p>
    <p>
      <?php echo __('you can update to the latest version automatically , or download the latest version of the theme.',ADMINDOMAIN); ?>
    </p>
    <p><span style="color:red; font-weight:bold;">
      <?php echo __('Warning',ADMINDOMAIN); ?>
      : </span>
      <?php echo __('Remember that the updates will replace all your changes so please keep backup of your changes.',ADMINDOMAIN); ?>
    </p>
    <a class="button-secondary" href="http://templatic.com/members/member" target="blank">
    <?php echo __('Download latest Version',ADMINDOMAIN); ?>
    </a> </div>
  <?php } ?>
  <!-- Logo -->
  <div id='pblogo'> <a href="http://templatic.com" alt="WordPress Directory Theme" title="WordPress Directory Theme"><img src="<?php echo esc_url( get_template_directory_uri()."/images/templatic.png"); ?>" alt="WordPress Directory Theme" style="margin-right: 50px;" /></a><?php echo '<h3>'. @$theme_name.' Updates</h3>'; ?> </div>
  <?php
		if($flag ==1){
			if(isset($warnning_message) && $warnning_message!='')
			{?>
  <div class='error'>
    <p><strong><?php echo sprintf(__('%s',ADMINDOMAIN), $warnning_message);?></strong></p>
  </div>
  <?php
			  }
			if(!isset($successfull_login) && $successfull_login!=1):?>
  <p class="info"> <?php echo  sprintf(__('Enter your templatic login credentials to update your %s theme to the latest version.',ADMINDOMAIN),$th_name);?> </p>
  <form action="<?php echo site_url()."/wp-admin/admin.php?page=tmpl_theme_update";?>" name="" method="post">
    <table>
      <tr>
        <td><label>
            <?php echo __('User Name', ADMINDOMAIN)?>
          </label></td>
        <td><input type="text" name="templatic_username"  /></td>
      </tr>
      <tr>
        <td><label>
            <?php echo __('Password', ADMINDOMAIN)?>
          </label></td>
        <td><input type="password" name="templatic_password"  /></td>
      </tr>
      <tr>
        <td><input type="submit" name="templatic_login" value="Sign In" class="button-secondary"/></td>
        <td><a title="Close" id="TB_closeWindowButton" href="#" class="button-secondary">
          <?php echo __('Cancel',ADMINDOMAIN); ?>
          </a></td>
      </tr>
    </table>
  </form>
  <?php else:								
				 $file=$theme_name;
				 $download= wp_nonce_url(admin_url('update.php?action=upgrade-theme&theme=').$file, 'upgrade-theme_' . $file);				
				 echo $th_name.' - theme <a id="TB_closeWindowButton" href="'.$download.'" target="_parent" class="button-secondary">'.__('Update Now',ADMINDOMAIN).'</a>';
			 endif;
			 
			 } ?>
</div>
<?php
	if($flag == 0){
		echo '<h3>'.__('You have the latest version of the ',ADMINDOMAIN).$th_name.'</h3>';
        echo '<p>&rarr;'.sprintf(__('<strong>Your version:</strong> %s',ADMINDOMAIN),$theme_version).'</p>';	
	}
do_action('admin_footer', '');
do_action('admin_print_footer_scripts');
?>