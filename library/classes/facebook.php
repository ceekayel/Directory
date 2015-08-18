<?php
/*
 * Create the templatic facebook post widget
 */
	
class supreme_facebook extends WP_Widget {
	function supreme_facebook() {
		//Constructor
		$widget_ops = array('classname' => 't_facebook_fans', 'description' => __('Display a like box for your Facebook page. Works best in sidebar areas.',ADMINDOMAIN) );
		$this->WP_Widget('supreme_facebook', __('T &rarr; Facebook Like Box',ADMINDOMAIN), $widget_ops);
	}
	
	function widget($args, $instance) {
		// prints the widget
			extract($args, EXTR_SKIP);
		echo $before_widget;
			$facebook_page_url = empty($instance['facebook_page_url']) ? 'http://facebook.com/templatic' : apply_filters('widget_facebook_page_url', $instance['facebook_page_url']);
			$width = empty($instance['width']) ? 300 : apply_filters('widget_width', $instance['width']);
			$show_faces = empty($instance['show_faces']) ? 0 : apply_filters('widget_show_faces', $instance['show_faces']);
			$show_stream = empty($instance['show_stream']) ? 0 : apply_filters('widget_show_stream', $instance['show_stream']);
			$show_header = empty($instance['show_header']) ? 0 : apply_filters('widget_show_header', $instance['show_header']);
			
			$face=($show_faces == 1)? 'true':'false';
			$stream=($show_stream == 1)? 'true':'false';
			$header=($show_header == 1)? 'true':'false';
			
			?>
               <div id="fb-root"></div>
               <script src="http://connect.facebook.net/en_US/all.js#xfbml=1"></script>
               <fb:like-box href="<?php echo $facebook_page_url; ?>" width="<?php echo $width; ?>" show_faces="<?php echo $face; ?>" border_color="" stream="<?php echo $stream; ?>" header="<?php echo $header; ?>"></fb:like-box>
               <?php
		echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		//save the widget		
		return $new_instance;
	}
	function form($instance) {
		//widgetform in backend
		$instance = wp_parse_args( (array) $instance, array('width'=>300, 'facebook_page_url'=>'http://facebook.com/templatic', 'show_faces'=>1, 'show_stream'=>1, 'show_header'=>1 ) );
			$facebook_page_url = strip_tags($instance['facebook_page_url']);
			$width = strip_tags($instance['width']);
			$show_faces = strip_tags($instance['show_faces']);
			$show_stream = strip_tags($instance['show_stream']);
			$show_header = strip_tags($instance['show_header']);
			
	?>
     <script type="text/javascript">
	function show_facebook_header(str,id){
		var value=jQuery('#'+id).val();		
		if(str.value==1 || value==1){			
			 jQuery('p#facebook_show_header').fadeIn('slow');
		}else{
			jQuery('p#facebook_show_header').fadeOut("slow");
		}
	}
	</script>
<p>
  <label for="<?php echo $this->get_field_id('facebook_page_url'); ?>">
    <?php  echo __('Facebook Page Full URL',ADMINDOMAIN)?>
    :
    <input class="widefat" id="<?php echo $this->get_field_id('facebook_page_url'); ?>" name="<?php echo $this->get_field_name('facebook_page_url'); ?>" type="text" value="<?php echo esc_attr($facebook_page_url); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('width'); ?>">
    <?php  echo __('Width',ADMINDOMAIN)?>
    :
    <input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo esc_attr($width); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('show_faces'); ?>">
    <?php  echo __('Show Faces',ADMINDOMAIN)?>
    :
    <select id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" style="width:50%;" onchange="show_facebook_header(this,'<?php echo $this->get_field_id('show_stream'); ?>');">
      <option value="1" <?php if(esc_attr($show_faces)=='1'){ echo 'selected="selected"';}?>>
      <?php echo __('Yes',ADMINDOMAIN); ?>
      </option>
      <option value="0" <?php if(esc_attr($show_faces)=='0'){ echo 'selected="selected"';}?>>
      <?php echo __('No',ADMINDOMAIN); ?>
      </option>
    </select>
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('show_stream'); ?>">
    <?php  echo __('Show Stream',ADMINDOMAIN)?>
    :
    <select id="<?php echo $this->get_field_id('show_stream'); ?>" name="<?php echo $this->get_field_name('show_stream'); ?>" style="width:50%;" onchange="show_facebook_header(this,'<?php echo $this->get_field_id('show_faces'); ?>');">
      <option value="1" <?php if(esc_attr($show_stream)=='1'){ echo 'selected="selected"';}?>>
      <?php echo __('Yes',ADMINDOMAIN); ?>
      </option>
      <option value="0" <?php if(esc_attr($show_stream)=='0'){ echo 'selected="selected"';}?>>
      <?php echo __('No',ADMINDOMAIN); ?>
      </option>
    </select>
  </label>
</p>
<p id="facebook_show_header" <?php if(esc_attr($show_stream)=='1' || esc_attr($show_faces)=='1'){echo "style='display:block;'";}else{echo "style='display:none;'";}?>>
  <label for="<?php echo $this->get_field_id('show_header'); ?>">
    <?php  echo __('Show Header',ADMINDOMAIN)?>
    :
    <select id="<?php echo $this->get_field_id('show_header'); ?>" name="<?php echo $this->get_field_name('show_header'); ?>" style="width:50%;">
      <option value="1" <?php if(esc_attr($show_header)=='1'){ echo 'selected="selected"';}?>>
      <?php echo __('Yes',ADMINDOMAIN); ?>
      </option>
      <option value="0" <?php if(esc_attr($show_header)=='0'){ echo 'selected="selected"';}?>>
      <?php echo __('No',ADMINDOMAIN); ?>
      </option>
    </select>
  </label>
</p>
<?php
	}
	
}
/*
 * templatic templatic facebook widget init
 */
register_widget('supreme_facebook');
?>
