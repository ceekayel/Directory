<?php 		 
	/* get the image code - show image if Display imege option is enable from backend - Start */
	global $post;
	 ?>
<a class="image-list" href="<?php echo get_permalink(); ?>" title="<?php the_title_attribute( 'echo=1' ); ?>" rel="bookmark" class="featured-image-link">
<?php 
	if(function_exists('the_post_format_image')){
		the_post_format_image( 'medium' ); // wordpress 3.6 
	}else{
		$feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );
		if($feat_image)
		{
			echo '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( $post->post_title ) . '">';
			?>
<img src="<?php echo $feat_image; ?>" alt="<?php echo $post->post_title;?>" />
<?php
			echo '</a>';
		}else{
			 $arrImages =& get_children('order=ASC&orderby=menu_order ID&post_type=attachment&post_mime_type=image&post_parent=' . $post->ID );
			 if($arrImages){		
			   foreach($arrImages as $key=>$val){
					$id = $val->ID;
					$img_arr = wp_get_attachment_image_src($id, 'thumbnail'); // Get the thumbnail url for the attachment
					$return_arr[] = $img_arr[0];
					
				}
				echo '<img src="'. $return_arr[0].'"  />';
			}
		}	
		//the_content();
	}	
	?>
</a>
<h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', THEME_DOMAIN ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
  <?php the_title(); ?>
  </a></h2>