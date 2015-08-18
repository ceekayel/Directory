<?php
/**
 * Footer Template
 *
 * The footer template is generally used on every page of your site. Nearly all other
 * templates call it somewhere near the bottom of the file. It is used mostly as a closing
 * wrapper, which is opened with the header.php file. It also executes key functions needed
 * by the theme, child themes, and plugins. 
 */
 do_action( 'close_main' ); // supreme_close_main 
 ?>
</div>
<!-- .wrap -->
<?php 
if(is_front_page() && (function_exists('tmpl_wp_is_mobile') && !tmpl_wp_is_mobile()))
{
	do_action( 'after_wrapper' ); // supreme_after_main 
}	
?>
</section>
<!-- #main -->
<?php do_action( 'after_main' ); // supreme_after_main ?>
</div>
<!-- #container -->

<a class="exit-off-canvas"></a> <!-- exit-off-canvas - overlay to exit offcanvas -->
<a class="exit-selection"></a>
<div class="exit-sorting"></div>
  </div> <!-- inner-wrap start -->
</div> <!-- off-canvas-wrap end -->


<?php do_action( 'close_body' ); // supreme_close_body 
	/* auto detect mobile devices */
	if ( tmpl_wp_is_mobile()) {
		include_once('mobile-templates/mobile-footer.php');
	}else{
	apply_filters('tmpl-subsidiary',supreme_subsidiary_sidebar() ); // Loads the sidebar-subsidiary 
	apply_filters('tmpl-subsidiary-2c',supreme_subsidiary_2c_sidebar() ); // Loads the sidebar-subsidiary 
	apply_filters('tmpl-subsidiary-3c',supreme_subsidiary_3c_sidebar() ); // Loads the sidebar-subsidiary
	apply_filters('tmpl-subsidiary-4c',supreme_subsidiary_4c_sidebar() ); // Loads the sidebar-subsidiary 
	apply_filters('tmpl_subsidiary_nav',supreme_subsidiary_navigation()); // Loads the menu-subsidiary.php template.
	do_action( 'before_footer' ); // supreme_before_footer ?>
	<footer id="footer" class="clearfix">
	  <?php do_action( 'open_footer' ); // supreme_open_footer 
		if(is_active_sidebar('footer')):
		?>
			<section class="footer_top clearfix">
			<?php do_action('open_footer_widget'); ?>
			<div class="footer-wrap clearfix row">
			  <div class="columns">
				<div class="footer_widget_wrap">
				  <?php apply_filters('tmpl_supreme_footer_widgets' ,supreme_footer_widgets()); //load footer widgets ?>
				</div>
			  </div>
			</div>
			<?php do_action('close_footer_widget'); ?>
			</section>
	  <?php endif; ?>
	  <section class="footer_bottom clearfix">
		<div class="footer-wrap clearfix row">
		  <div class="columns">
			<?php 
			/* before footer menu */
			do_action( 'before-footer-nav' ); 
			apply_filters('tmpl_supreme_footer_nav',supreme_footer_navigation()); // Loads the menu-footer. 
			 /* before footer content */
			do_action( 'before-footer-content' );
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
		</div>
		<!-- .wrap -->
	  </section>
	  <?php do_action( 'close_footer' ); // supreme_close_footer ?>
	</footer>
	<!-- #footer -->
	<?php } ?>
</div>
<?php do_action( 'after_footer' ); // supreme_after_footer 
	wp_footer(); // wp_footer 
	do_action('before_body_end',10); 
?>
</body>
</html>