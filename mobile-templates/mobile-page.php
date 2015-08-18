<?php
/**
 * Page Template
 *
 * This is the default page template.  It is used when a more specific template can't be found to display 
 * singular views of pages.
 */
get_header(); // Loads the header.php template. 
$page_template = get_post_meta( $post->ID, '_wp_page_template', true );
do_action( 'before_content' );
?>
<section id="content" class="large-9 small-12 columns">
	<?php do_action( 'open_content' ); 
	do_action( 'templ_after_container_breadcrumb' ); ?>  
	<div class="hfeed">
     <?php apply_filters('tmpl_before-content',supreme_sidebar_before_content() ); // Loads the sidebar-before-content.
		if ( have_posts() ) :
			while ( have_posts() ) : the_post(); 
				do_action( 'before_entry' );  ?>
				<div id="post-<?php the_ID(); ?>" class="<?php supreme_entry_class(); ?>">
      			<?php do_action( 'open_entry' ); 					
					 do_action('entry-title'); ?>
                          <section class="entry-content">
                            <?php do_action('open-post-content');
                                  the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', THEME_DOMAIN ) );
                                  wp_link_pages( array( 'before' => '<p class="page-links">' . __( 'Pages:', THEME_DOMAIN ), 'after' => '</p>' ) );
                                   do_action('entry-edit-link'); 
                                   do_action('close-post-content');
                              ?>
                          </section>
                          <!-- .entry-content -->
					<?php do_action( 'close_entry' );  ?>
    				</div>
					<!-- .hentry -->
					<?php 
					do_action( 'after_entry' ); 
					do_action( 'after_singular' );
					do_action( 'before_comments' ); 
					 
					 // If comments are open or we have at least one comment, load the comments template.
					 if ( supreme_get_settings( 'enable_comments_on_page' )) {
					 	comments_template( '/comments.php', true ); // Loads the comments.php template. 
					 }
					 do_action( 'after_comments' ); // after_comments 
				endwhile;


	if($page_template =='page-templates/contact-us.php'){
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
	<?php } endif; ?>
	</div>
	<!-- .hfeed -->
  <?php do_action( 'close_content' ); ?>
</section>
<!-- #content -->
<?php do_action( 'after_content' ); 

get_footer(); // Loads the footer.php template. ?>