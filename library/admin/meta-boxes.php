<?php
/**
 * Adds the template meta box to the post editing screen for public post types.  This feature allows users and 
 * devs to create custom templates for any post type, not just pages as default in WordPress core.  The 
 * functions in this file create the template meta box and save the template chosen by the user when the 
 * post is saved.  This file is only used if the theme supports the 'supreme-core-template-hierarchy' feature.

	Add the post template meta box on the 'add_meta_boxes' hook. Save the post template meta box data on the 'save_post' hook. */

add_action( 'save_post', 'supreme_meta_box_post_save_template', 10, 2 );

/**
 * Displays the post template meta box.
 */
function supreme_metabox_post_display_template( $object, $box ) {
	/* Get the post type object. */
	$post_type_object = get_post_type_object( $object->post_type );
	/* Get a list of available custom templates for the post type. */
	$templates = supreme_get_post_templates( array( 'label' => array( "{$post_type_object->labels->singular_name} Template", "{$post_type_object->name} Template" ) ) );
	wp_nonce_field( basename( __FILE__ ), 'supreme-core-post-meta-box-template' ); ?>
<p>
  <?php if ( 0 != count( $templates ) ) { ?>
  <select name="supreme-post-template" id="supreme-post-template" class="widefat">
    <option value=""></option>
    <?php foreach ( $templates as $label => $template ) { ?>
    <option value="<?php echo esc_attr( $template ); ?>" <?php selected( esc_attr( get_post_meta( $object->ID, "_wp_{$post_type_object->name}_template", true ) ), esc_attr( $template ) ); ?>><?php echo esc_html( $label ); ?></option>
    <?php } ?>
  </select>
  <?php } else { ?>
  <?php echo __( 'No templates exist for this post type.', ADMINDOMAIN ); ?>
  <?php } ?>
</p>
<?php
}
/**
 * Saves the post template meta box settings as post metadata.
 */
function supreme_meta_box_post_save_template( $post_id, $post ) {
	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['supreme-core-post-meta-box-template'] ) || !wp_verify_nonce( $_POST['supreme-core-post-meta-box-template'], basename( __FILE__ ) ) )
		return $post_id;
	/* Return here if the template is not set. There's a chance it won't be if the post type doesn't have any templates. */
	if ( !isset( $_POST['supreme-post-template'] ) )
		return $post_id;
	/* Get the posted meta value. */
	$new_meta_value = $_POST['supreme-post-template'];
	/* Set the $meta_key variable based off the post type name. */
	$meta_key = "_wp_{$post->post_type}_template";
	/* Get the meta value of the meta key. */
	$meta_value = get_post_meta( $post_id, $meta_key, true );
	/* If there is no new meta value but an old value exists, delete it. */
	if ( current_user_can( 'delete_post_meta', $post_id ) && '' == $new_meta_value && $meta_value )
		delete_post_meta( $post_id, $meta_key, $meta_value );
	/* If a new meta value was added and there was no previous value, add it. */
	elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value )
		add_post_meta( $post_id, $meta_key, $new_meta_value, true );
	/* If the new meta value does not match the old value, update it. */
	elseif ( current_user_can( 'edit_post_meta', $post_id ) && $new_meta_value && $new_meta_value != $meta_value )
		update_post_meta( $post_id, $meta_key, $new_meta_value );
}
/**
 * Adds the SEO meta box to the post editing screen for public post types.  This feature allows the post author 
 * to set a custom title, description, and keywords for the post, which will be viewed on the singular post page.  
 * To use this feature, the theme must support the 'supreme-core-seo' feature.  The functions in this file create
 * the SEO meta box and save the settings chosen by the user when the post is saved.
 */
/* Add the post SEO meta box on the 'add_meta_boxes' hook. */
add_action( 'add_meta_boxes', 'supreme_meta_box_post_remove_seo', 10, 2 );

/*
Name :supreme_meta_box_post_remove_seo
Description: Remove the meta box from some post types.
 */ 
function supreme_meta_box_post_remove_seo( $post_type, $post ) {
	/* Removes post stylesheets support of the bbPress 'topic' post type. */
	if ( function_exists( 'bbp_get_topic_post_type' ) && bbp_get_topic_post_type() == $post_type )
		remove_meta_box( 'supreme-core-post-seo', bbp_get_topic_post_type(), 'normal' );
	/* Removes post stylesheets support of the bbPress 'reply' post type. */
	elseif ( function_exists( 'bbp_get_reply_post_type' ) && bbp_get_reply_post_type() == $post_type )
		remove_meta_box( 'supreme-core-post-seo', bbp_get_reply_post_type(), 'normal' );
}?>