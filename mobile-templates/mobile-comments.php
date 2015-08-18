<?php
/**
 * Comment Template
 *
 * The comment template displays an individual comment. This can be overwritten by templates specific
 * to the comment type (comment.php, comment-{$comment_type}.php, comment-pingback.php, 
 * comment-trackback.php) in a child theme.
 */
	global $post, $comment;
	
	remove_action( 'comment_text', 'display_rating_star' );
	$tmpdata = get_option('templatic_settings');
	if($tmpdata['templatin_rating']=='yes'){
		add_action( 'comment_rate', 'display_rating_star' );
	}

  /**
 * Lists comments and calls the comment form.  Individual comments have their own templates.  The 
 */
/* Kill the page if trying to access this template directly. */
if ( 'comments.php' == basename( $_SERVER['SCRIPT_FILENAME'] ) )
	die( __( 'Please do not load this page directly. Thanks!', THEME_DOMAIN ) );
/* If a post password is required or no comments are given and comments/pings are closed, return. */
if ( post_password_required() || ( !have_comments() && !comments_open() && !pings_open() ) )
	return;
	
	?>
	<dd class="tmpl-accordion-navigation">
		<?php
		
		/* comment form */
		if(get_option('default_comment_status') =='open'){
			echo "<a href='#respond-fr'>".__('Add Review',THEME_DOMAIN)."</a>";
			
			echo "<div id='respond-fr' class='content'>";
			comment_form();
			echo "</div>";			
		} 
		
		?>
	</dd>
	
	<?php
 	if ( have_comments() ) : 
	
	?>
	<dd id="comments" class="tmpl-accordion-navigation">
	<?php
	global $post;
	if($post->post_type == 'post')
	{
		echo "<a id= 'reviews-click' href='#comment-list'>"; templatic_comments_number( __( 'No Comment', THEME_DOMAIN ), __( 'One Comment', THEME_DOMAIN ), __( 'Comments', THEME_DOMAIN )); echo "</a>";
	}
	else
	{
		echo "<a id= 'reviews-click' href='#comment-list'>"; templatic_comments_number( __( 'No Review', THEME_DOMAIN ), __( 'One Review', THEME_DOMAIN ), __( 'Reviews', THEME_DOMAIN ) ); echo "</a>";
	}
	
	?>
	<div id="comment-list" class="content comment-list">
	<?php
	

			
	do_action("show_comment");	?>
	
	<ul>
	<?php 
	
		do_action( 'before_comment_list' );

		if ( get_option( 'page_comments' ) ) : ?>
		<div class="comment-navigation comment-pagination"> <span class="page-numbers"><?php printf( __( 'Page %1$s of %2$s', THEME_DOMAIN ), ( get_query_var( 'cpage' ) ? absint( get_query_var( 'cpage' ) ) : 1 ), get_comment_pages_count() ); ?></span>
		<?php paginate_comments_links(); ?>
		</div>
		<!-- .comment-navigation -->
		<?php 
		endif;
	wp_list_comments( supreme_list_comments_args() ); ?>
    </ul>
	
	<!-- .comment-list -->
	<?php 
	do_action( 'after_comment_list' ); 
	

	if ( pings_open() && !comments_open() ) : ?>
		<p class="comments-closed pings-open"> <?php 
		_e( 'Reviews are disabled, but',THEME_DOMAIN);
		echo '<a href='.get_trackback_url().' title="Trackback URL for this post">';
			_e('trackbacks',THEME_DOMAIN);
		echo '</a> '; 
		_e('and pingbacks are open.', THEME_DOMAIN  ); ?>
		</p>
	  <!-- .comments-closed .pings-open -->
		<?php 
		elseif ( !comments_open() ) : ?>
		  <p class="comments-closed">
			<?php _e( 'Reviews are disabled.', THEME_DOMAIN ); ?>
		  </p>
	  <!-- .comments-closed -->
	<?php endif;  ?>
	</div>
	</dd>
	 <?php 
	endif; 

/* when click on reviews text open the reviews tab in mobile devices */	
add_action('wp_footer','tmpl_show_reviews'); 

function tmpl_show_reviews(){
	?>
	<script type="text/JavaScript">
	
		jQuery('#reviews_show').click(function(){
			jQuery('#comments').addClass('active');
			jQuery('#comment-list').addClass('active');
			jQuery('#comments #comments-number + ol.comment-list').slideToggle( "fast");
			jQuery('#comments h3#comments-number').addClass('reviews-open');
		});
	</script>

<?php } ?>