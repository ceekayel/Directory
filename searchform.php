<?php
/**
 * Search Form Template
 *
 * The search form template displays the search form.
 */
?>
<form method="get" class="search-form" action="<?php echo trailingslashit( home_url() ); ?>">
  <div>
	<input type="hidden" value="listing" name="post_type">
	<input type="hidden" value="all" name="mkey[]">
	<span role="status" aria-live="polite" class="ui-helper-hidden-accessible"></span><input type="text" size="100" placeholder="<?php _e( 'Looking For ...', THEME_DOMAIN ); ?>" class="searchpost "  name="s" value="" autocomplete="off">
	
	<input type="submit" value="<?php _e( 'Search', THEME_DOMAIN ); ?>"  class="sgo">
  </div>
</form>
<!-- .search-form -->
