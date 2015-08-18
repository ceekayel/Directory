<?php
/**
 * Loop Template
 *
 * Displays the entire post content.
 */
global $posts,$wpdb;
if ( have_posts() ) : 
 while ( have_posts() ) : the_post();
	do_action( 'before_entry' );
	$format = get_post_format( $post->ID ); ?>
	<div id="post-<?php echo $post->ID; ?>" <?php post_class(); ?>>
	<?php	
	if($post->post_type =='post'){
		get_template_part( 'content', 'blog'); 
	}else{
		get_template_part( 'content', get_post_format()); 
	} ?>
	</div>
	<?php
	do_action( 'after_entry' );
	endwhile; 
	wp_reset_query();
else:
	apply_filters('supreme-loop-error',get_template_part( 'loop-error' )); // Loads the loop-error.php template. 
endif; ?>