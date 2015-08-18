<div class="entry-header">
  <h2 class="entry-title"> <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', THEME_DOMAIN ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
    <?php the_title(); ?>
    </a> </h2>
  <div class="entry-media">
    <div class="audio-content">
      <?php
          if(function_exists('the_post_format_audio')){
          	the_post_format_audio(); // wordpre 3.6 compatibility
          }else{
          	the_content();
          }
          ?>
    </div>
    <!-- .audio-content -->
  </div>
  <!-- .entry-media -->
  <?php
     do_action('supreme_after-image'.$post_type);
     /* get the image code - show image if Display imege option is enable from backend - Start */
     do_action('supreme_before-title'.$post_type);
  apply_filters('supreme-post-info',supreme_core_post_info($post)); // return post information; 	
  do_action('supreme_after-title'.$post_type); ?>
</div>
<!-- .entry-header -->
