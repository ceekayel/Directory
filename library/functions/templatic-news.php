<?php
/**
 * Metadata functions used in the core.  This file registers meta keys for use in WordPress 
 * in a safe manner by setting up a custom sanitize callback.
 *
 */
/**
 * Callback function for sanitizing meta when add_metadata() or update_metadata() is called by WordPress. 
 * If a developer wants to set up a custom method for sanitizing the data, they should use the 
 * "sanitize_{$meta_type}_meta_{$meta_key}" filter hook to do so.
 */
function supreme_sanitize_meta( $meta_value, $meta_key, $meta_type ) {
	return strip_tags( $meta_value );
}
add_action( 'wp_dashboard_setup', 'TemplaticDashboardWidgetSetup');
function TemplaticDashboardWidgetSetup() {
	global $current_user;
	if(is_super_admin($current_user->ID)) {
		add_meta_box( 'templatic_dashboard_news_widget', __('News From Templatic',ADMINDOMAIN), 'TemplaticDashboardWidgetFunction', 'dashboard', 'normal', 'high' );
	}
}

/* TemplaticDashboardWidgetFunction - Admin dashboard widget to show templatic news */
function TemplaticDashboardWidgetFunction() {	
	?>
<div class="table table_tnews">
  <p class="sub"><strong>
    <?php echo __('Templatic News',ADMINDOMAIN); ?>
    </strong></p>
	<div class="trss-widget">
    <?php
               $items = get_transient('templatic_dashboard_news');
          
               if (empty($items)) {
                    include_once(ABSPATH . WPINC . '/class-simplepie.php');
                    $trss = new SimplePie();
                    $trss->set_timeout(5);
                    $trss->set_feed_url('http://feeds.feedburner.com/Templatic');
                    $trss->strip_htmltags(array_merge($trss->strip_htmltags, array('h1', 'a')));
                    $trss->enable_cache(false);
                    $trss->init();
                    
                    if ( is_wp_error($trss) ) {
                         if ( is_admin() || current_user_can('manage_options') ) {
                              echo '<div class="rss-widget"><p>';
                              printf(__('<strong>RSS Error</strong>: %s',THEME_DOMAIN), $trss->get_error_message());
                              echo '</p></div>';
                         }
                    }
                    
                    $items = $trss->get_items(0, 6);
                    $cached = array();
                    
                    foreach ($items as $item) { 
                         preg_match('/(.{128}.*?)\b/', $item->get_content(), $matches);
                         $cached[] = array(
                                        'url' => $item->get_permalink(),
                                        'title' => $item->get_title(),
                                        'date' => $item->get_date("d M Y"),
                                        'content' => rtrim($matches[1]) . '...'
                                   );
                    }
                    $items = $cached;
                    set_transient('templatic_dashboard_news', $cached, 60 * 60 * 24);
               }
               ?>
			<ul class="news">
			<?php foreach ($items as $item): ?>
			<li class="post"> <a href="<?php echo $item['url']; ?>" class="rsswidget"><?php echo $item['title']; ?></a> <span class="rss-date"><?php echo $item['date']; ?></span>
			<div class="rssSummary"><?php echo strip_tags($item['content']); ?></div>
			</li>
			<?php endforeach;?>
			</ul>
	</div>
</div>
<div class="t_theme">
  <div class="t_thumb">
    <?php		
		//$lastTheme = file_get_contents('http://templatic.com/latest-theme/');		
		$lastTheme='';
		$cache_key = 'dash_templatic_latest_theme';
		if ( false !== ( $lastTheme = get_transient( $cache_key ) ) ) {
			echo $lastTheme;	
		}else{	
			$raw_response = wp_remote_post( 'http://templatic.com/latest-theme/');
			if(! is_wp_error( $raw_response ) ) {			
				$lastTheme=$raw_response['body'];
			}
			if ($lastTheme){
				echo $lastTheme; 
			}
			set_transient( $cache_key, $lastTheme, 12 * HOUR_IN_SECONDS ); // Default lifetime in cache of 12 hours (same as the feeds)
		}
	?>
  </div>
  <hr/>
  <p class="sub"><strong>
    <?php echo __('More...',ADMINDOMAIN); ?>
    </strong></p>
  <ul id="templatic-services">
    <li><a href="http://templatic.com/support"><?php echo __('Need support?',ADMINDOMAIN);?> </a></li>
    <li><a href="http://templatic.com/free-theme-install-service/"><?php echo __('Custom services',ADMINDOMAIN);?></a></li>
    <li><a href="http://templatic.com/premium-themes-club"><?php echo __('Join our theme club',ADMINDOMAIN);?></a></li>
  </ul>
</div>
<div class="clearfix"></div>
<?php
}?>