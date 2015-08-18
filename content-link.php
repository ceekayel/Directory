<div class="entry-header">
  <h2 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php echo esc_attr( sprintf( __( 'Permalink to %s', THEME_DOMAIN ), the_title_attribute( 'echo=0' ) ) ); ?>" rel="bookmark">
    <?php the_title(); ?>
    </a></h2>
  <?php echo apply_filters('supreme_content_format_post_info_',supreme_content_format_post_info()); ?> </div>