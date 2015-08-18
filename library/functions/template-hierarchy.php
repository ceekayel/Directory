<?php
/**
 * The framework has its own template hierarchy that can be used instead of the default WordPress 
 * template hierarchy.  It is not much different than the default.  It was built to extend the default by 
 * making it smarter and more flexible.  The goal is to give theme developers and end users an 
 * easy-to-override system that doesn't involve massive amounts of conditional tags within files.
 */

/* Filter the author/user template. */
add_filter( 'author_template', 'supreme_user_template' );

add_filter( 'category_template', 'supreme_taxonomy_template' );

/* Filter the single, page, and attachment (singular) templates. */
add_filter( 'single_template', 'supreme_singular_template' );
add_filter( 'page_template', 'supreme_singular_template' );
add_filter( 'attachment_template', 'supreme_singular_template' );


/**
 * Overrides WP's default template for author-based archives. Better abstraction of templates than 
 * is_author() allows by allowing themes to specify templates for a specific author. The hierarchy is 
 * user-$nicename.php, $user-role-$role.php, user.php, author.php, archive.php.
 */
 
function supreme_user_template( $template ) {
	$templates = array();
	/* Get the user nicename. */
	$name = get_the_author_meta( 'user_nicename', get_query_var( 'author' ) );
	/* Get the user object. */
	$user = new WP_User( absint( get_query_var( 'author' ) ) );
	/* Add the user nicename template. */
	$templates[] = "user-{$name}.php";
	/* Add role-based templates for the user. */
	if ( is_array( $user->roles ) ) {
		foreach ( $user->roles as $role )
			$templates[] = "user-role-{$role}.php";
	}
	/* Add a basic user template. */
	$templates[] = 'user.php';
	/* Add backwards compatibility with the WordPress author template. */
	$templates[] = 'author.php';
	/* Fall back to the basic archive template. */
	$templates[] = 'archive.php';
	/* Return the found template. */
	return locate_template( $templates );
}

/**
 * Overrides the default single (singular post) template.  Post templates can be loaded using a custom 
 * post template, by slug, or by ID.
 * @return string $template The theme post template after all templates have been checked for.
 */
function supreme_singular_template( $template ) {
	$templates = array();
	/* Get the queried post. */
	$post = get_queried_object();
	/* Check for a custom post template by custom field key '_wp_post_template'. */
	$custom = get_post_meta( get_queried_object_id(), "_wp_{$post->post_type}_template", true );
	if ( $custom )
		$templates[] = $custom;
	/* If viewing an attachment page, handle the files by mime type. */
	if ( is_attachment() ) {
		/* Split the mime_type into two distinct parts. */
		$type = explode( '/', get_post_mime_type() );
		$templates[] = "attachment-{$type[0]}_{$type[1]}.php";
		$templates[] = "attachment-{$type[1]}.php";
		$templates[] = "attachment-{$type[0]}.php";
	}
	/* If viewing any other type of singular page. */
	else {
		/* Add a post name (slug) template. */
		$templates[] = "{$post->post_type}-{$post->post_name}.php";
		/* Add a post ID template. */
		$templates[] = "{$post->post_type}-{$post->ID}.php";
	}
	/* Add a template based off the post type name. */
	$templates[] = "{$post->post_type}.php";
	/* Allow for WP standard 'single' templates for compatibility. */
	$templates[] = "single-{$post->post_type}.php";
	$templates[] = 'single.php';
	/* Add a general template of singular.php. */
	$templates[] = "singular.php";

	return locate_template( $templates );
}
?>