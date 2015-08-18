<?php
/*
Template Name: Contact Us
*/
add_action('wp_head','attach_supreme_contact_css');
function attach_supreme_contact_css(){
echo '
	<style type="text/css">
		.success_msg {
			font-size:16px;
			padding-top:10px;
			color:green;
		}
	</style>
';
}

include_once(ABSPATH.'wp-admin/includes/plugin.php');
$captcha=supreme_get_settings( 'supreme_global_contactus_captcha' );
$a = get_option("recaptcha_options");
get_header();
do_action( 'templ_before_container_breadcrumb' );

$theme_options = get_option(supreme_prefix().'_theme_settings');
$supreme_show_breadcrumb = $theme_options['supreme_show_breadcrumb'];
?>
<section id="content" class="multiple large-9 small-12 columns">
  <?php
  do_action( 'templ_inside_container_breadcrumb' ); 
  do_action( 'after_content' );
  ?>
  
  <div class="hfeed">
	<?php apply_filters('tmpl_before-content',supreme_sidebar_before_content() ); // Loads the sidebar-before-content.
     while ( have_posts() ) : the_post(); 
		do_action( 'before_entry' ); ?>
		<div id="post-<?php the_ID(); ?>" class="<?php supreme_entry_class(); ?>">
			<?php do_action( 'open_entry' ); ?>
               <h1 class="loop-title"><?php the_title(); ?></h1>
               <div class="loop-description">
               <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', THEME_DOMAIN ) ); ?>
               </div>
               <!-- .entry-content -->
               <?php  do_action( 'close_entry' );  ?>
		</div>
		<!-- .hentry -->
		<?php
		do_action( 'after_entry' );
		apply_filters('tmpl_after-singular',supreme_sidebar_after_singular()); // Loads the sidebar-after-singular.
		do_action( 'after_singular' );
     endwhile;
	
	if(isset($_REQUEST['msg']) && $_REQUEST['msg'] == 'success'){ 
		echo '<p class="success_msg">'; _e('Contact mail successfully sent.',THEME_DOMAIN); echo ' </p>';
	}
	if ( is_active_sidebar('contact_page_widget') ) {
		apply_filters('tmpl_above_form_widget',supreme_contact_page_widget()); 
	}
	?>
    <script type="text/javascript">
			 var RecaptchaOptions = {
				theme : '<?php echo $a['registration_theme']; ?>',
				lang : '<?php echo $a['recaptcha_language']; ?>'
			 };
	</script>
    
    <?php 
		if(isset($_REQUEST['ecptcha']) && $_REQUEST['ecptcha'] == 'captch' && !isset($_REQUEST['msg'])) {
			$blank_field = $a['no_response_error'];
			$incorrect_field = $a['incorrect_response_error'];
			echo '<div class="error_msg">'.$incorrect_field.'</div>';
		}
		if(isset($_REQUEST['invalid']) == 'playthru') {
			echo '<div class="error_msg">';
			_e('You need to play the game to contact us.',THEME_DOMAIN);
			echo '</div>';
		}
		$theme_options = get_option(supreme_prefix().'_theme_settings');		

		if( is_active_sidebar('contact_page_widget') ){ 
			do_action('tmpl_below_form_widget'); 
		} 
	?>
    <script type="text/javascript">
	var $c = jQuery.noConflict();
	$c(document).ready(function(){
	
	jQuery('.success_msg').delay(5000).fadeOut('slow');
	//global vars
	var enquiryfrm = $c("#contact_frm");
	var your_name = $c("#your-name");
	var your_email = $c("#your-email");
	var your_subject = $c("#your-subject");
	var your_message = $c("#your-message");
	var recaptcha_response_field = $c("#recaptcha_response_field");
	
	var your_name_Info = $c("#your_name_Info");
	var your_emailInfo = $c("#your_emailInfo");
	var your_subjectInfo = $c("#your_subjectInfo");
	var your_messageInfo = $c("#your_messageInfo");
	var recaptcha_response_fieldInfo = $c("#recaptcha_response_fieldInfo");
	
	//On blur
	your_name.blur(validate_your_name);
	your_email.blur(validate_your_email);
	your_subject.blur(validate_your_subject);
	your_message.blur(validate_your_message);
	//On key press
	your_name.keyup(validate_your_name);
	your_email.keyup(validate_your_email);
	your_subject.keyup(validate_your_subject);
	your_message.keyup(validate_your_message);
	
	
	//On Submitting
	enquiryfrm.submit(function(){
		if(validate_your_name() & validate_your_email() & validate_your_subject() & validate_your_message() 
			<?php if( $captcha == 1){
			   if(file_exists(get_tmpl_plugin_directory().'wp-recaptcha/recaptchalib.php') && is_plugin_active('wp-recaptcha/wp-recaptcha.php')){
			 ?>
				& validate_recaptcha() 		
			 <?php }
			 }  
			?>
		  )
		{
			hideform();
			return true
		}
		else
		{
			return false;
		}
	});
	//validation functions
	function validate_your_name()
	{
		
		if($c("#your-name").val() == '')
		{
			your_name.addClass("error");
			your_name_Info.text("<?php _e('Please enter your name',THEME_DOMAIN); ?>");
			your_name_Info.addClass("message_error");
			return false;
		}
		else
		{
			your_name.removeClass("error");
			your_name_Info.text("");
			your_name_Info.removeClass("message_error");
			return true;
		}
	}
	function validate_your_email()
	{
		var isvalidemailflag = 0;
		if($c("#your-email").val() == '')
		{
			isvalidemailflag = 1;
		}else
		if($c("#your-email").val() != '')
		{
			var a = $c("#your-email").val().replace(/\s+$/,"");
			var filter = /^[a-zA-Z0-9]+[a-zA-Z0-9_.-]+[a-zA-Z0-9_-]+@[a-zA-Z0-9]+[a-zA-Z0-9.-]+[a-zA-Z0-9]+.[a-z]{2,4}$/;
			//if it's valid email
			if(filter.test(a)){
				isvalidemailflag = 0;
			}else{
				isvalidemailflag = 1;	
			}
		}
		
		if(isvalidemailflag)
		{
			your_email.addClass("error");
			your_emailInfo.text("<?php _e('Please enter valid email address',THEME_DOMAIN); ?>");
			your_emailInfo.addClass("message_error");
			return false;
		}else
		{
			your_email.removeClass("error");
			your_emailInfo.text("");
			your_emailInfo.removeClass("message_error");
			return true;
		}
	}
	
	function validate_your_subject()
	{
		if($c("#your-subject").val() == '')
		{
			your_subject.addClass("error");
			your_subjectInfo.text("<?php _e('Please enter a subject',THEME_DOMAIN); ?>");
			your_subjectInfo.addClass("message_error");
			return false;
		}
		else{
			your_subject.removeClass("error");
			your_subjectInfo.text("");
			your_subjectInfo.removeClass("message_error");
			return true;
		}
	}
	function validate_your_message()
	{
		if($c("#your-message").val() == '')
		{
			your_message.addClass("error");
			your_messageInfo.text(" <?php _e("Please enter message",THEME_DOMAIN); ?> ");


			your_messageInfo.addClass("message_error");
			return false;
		}
		else{
			your_message.removeClass("error");
			your_messageInfo.text("");
			your_messageInfo.removeClass("message_error");
			return true;
		}
	}
	
	function validate_recaptcha()
	{
		if($c("#recaptcha_response_field").val() == '')
		{
			recaptcha_response_field.addClass("error");
			recaptcha_response_fieldInfo.text("<?php _e("Please enter captcha",THEME_DOMAIN); ?>");
			recaptcha_response_fieldInfo.addClass("message_error");
			return false;
		}
		else{
			recaptcha_response_field.removeClass("error");
			recaptcha_response_fieldInfo.text("");
			recaptcha_response_fieldInfo.removeClass("message_error");
			return true;
		}
	}
	});
	</script>
    <?php apply_filters('tmpl_after-content',supreme_sidebar_after_content());  ?>
  </div>
  <?php do_action( 'close_content' ); ?>
  <!--  CONTENT AREA END -->
</section>
<?php do_action( 'after_content' );
apply_filters('supreme-contact_page_sidebar',supreme_contact_page_sidebar());// load the side bar of listing page
get_footer();?>