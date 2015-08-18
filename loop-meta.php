<?php
/**
 * Loop Meta Template
 *
 * Displays information at the top of the page about archive and search results when viewing those pages.  
 * This is not shown on the front page or singular views.
 */
?>
<?php if ( is_home() && !is_front_page() ) : ?>
<div class="loop-meta">
  <h1 class="loop-title"><?php echo get_post_field( 'post_title', get_queried_object_id() ); ?></h1>
  <div class="loop-description"> <?php echo apply_filters( 'the_excerpt', get_post_field( 'post_excerpt', get_queried_object_id() ) ); ?> </div>
  <!-- .loop-description -->
</div>
<!-- .loop-meta -->
<?php elseif ( is_category() ) : ?>
<div class="loop-meta">
  <h1 class="loop-title">
    <?php single_cat_title(); ?>
  </h1>
  <div class="loop-description"> <?php echo category_description(); ?> </div>
  <!-- .loop-description -->
</div>
<!-- .loop-meta -->
<?php elseif ( is_tag() ) : ?>
<div class="loop-meta">
  <h1 class="loop-title">
    <?php single_tag_title(); ?>
  </h1>
  <div class="loop-description"> <?php echo tag_description(); ?> </div>
  <!-- .loop-description -->
</div>
<!-- .loop-meta -->
<?php elseif ( is_tax() ) : ?>
<div class="loop-meta">
  <h1 class="loop-title">
    <?php single_term_title(); ?>
  </h1>
  <div class="loop-description"> <?php echo term_description( '', get_query_var( 'taxonomy' ) ); ?> </div>
  <!-- .loop-description -->
</div>
<!-- .loop-meta -->
<?php elseif ( is_author() ) : ?>
<?php $user_id = get_query_var( 'author' ); ?>
<!-- Display author box on author dashboard -->
<div id="hcard-<?php the_author_meta( 'user_nicename', $user_id ); ?>" class="loop-meta vcard clearfix">
  <h1 class="loop-title fn n">
    <?php the_author_meta( 'display_name', $user_id ); ?>
  </h1>
  <div class="author_photo">
    <?php $curauth = get_userdata($user_id); //wp_get_current_user($user_id);
				
				//echo get_avatar($curauth->ID, 75 ); 
				
				if(get_current_user_id()==$user_id):
				$profile_page_id=get_option('tevolution_profile');				
				$profile_url=get_permalink($profile_page_id);
				if($profile_url!=''):
				?>
    <div class="editProfile"><a href="<?php echo $profile_url;?>" ><?php echo PROFILE_EDIT_TEXT;?> </a> </div>
    <?php endif;?>
    <?php endif; ?>
  </div>
  <div class="author_content">
    <div class="agent_biodata">
      <?php
				global $form_fields_usermeta;
				if(is_array($form_fields_usermeta) && !empty($form_fields_usermeta)){
				 foreach($form_fields_usermeta as $key=> $_form_fields_usermeta)
				 {
					if(get_user_meta($user_id,$key,true) != ""): 
							if($_form_fields_usermeta['on_author_page']): 
							if($_form_fields_usermeta['type']!='upload') :
				 
					if($_form_fields_usermeta['type']=='multicheckbox'):  ?>
      <?php
							$checkbox = '';
							foreach(get_user_meta($user_id,$key,true) as $check):
									$checkbox .= $check.",";
							endforeach; ?>
      <p>
        <label><?php echo $_form_fields_usermeta['label']; ?></label>
        : <?php echo substr($checkbox,0,-1); ?></p>
      <?php else:  ?>
      <p>
        <label><?php echo $_form_fields_usermeta['label']; ?></label>
        : <?php echo get_user_meta($user_id,$key,true); ?></p>
      <?php endif;
						endif;
						if($_form_fields_usermeta['type']=='upload')
						{?>
      <p>
        <label  style="vertical-align:top;"><?php echo $_form_fields_usermeta['label']." : "; ?></label>
        <img src="<?php echo get_user_meta($user_id,$key,true);?>" style="width:150px;height:150px" /></p>
      <?php }
						endif;
					endif;
				  }
				 } // finish display the user custom field display?>
      <?php if($curauth->user_url):
						$website = $curauth->user_url;				
						$facebook = $curauth->facebook;				
						$twitter = $curauth->twitter;				
						if(!strstr($website,'http'))				
							 $website = 'http://'.$curauth->user_url;	?>
      <span><a href="<?php echo $website; ?>" target="_blank">
      <?php _e('Link',THEME_DOMAIN);?>
      </a></span>
      <?php if($facebook) ?>
      <span><a href="<?php echo $facebook; ?>" target="_blank">
      <?php _e('Facebook',THEME_DOMAIN);?>
      </a></span>
      <?php if($twitter) ?>
      <span><a href="<?php echo $twitter; ?>" target="_blank">
      <?php _e('Twitter',THEME_DOMAIN);?>
      </a></span> <br class="clearfix"  />
      <?php endif;//finish check current author user url		
				
				do_action('supreme_author_info'); // use this action to show total posting of users or.....
				/* payment type details */
				$price_pkg = get_user_meta($curauth->ID,'package_select',true);
				$pagd_data = get_post($price_pkg);
				$package_name = $pagd_data->post_title;
				$pkg_type = get_post_meta($price_pkg,'package_type',true);
				$limit_no_post = get_post_meta($price_pkg,'limit_no_post',true);
				
				$submited =get_user_meta($curauth->ID,'list_of_post',true);
				$remaining = intval($limit_no_post) - intval($submited);
				if($pkg_type == 2){
					echo "<p>"; 
					_e('You have subscribed to ',THEME_DOMAIN)."<strong>".$package_name."</strong> ";
					_e(' which allows you to submit ',THEME_DOMAIN)." <strong>".$limit_no_post."</strong> "." events";
					$msg ='';
					if($remaining >0){
						_e(" You have submitted ",THEME_DOMAIN)."<strong>".$submited." </strong>";
						_e("events till now, go and submit remaining ",THEME_DOMAIN)."<strong>".$remaining." </strong>";
	
					}else{
						_e(" and you have already submitted.",THEME_DOMAIN); echo ".<strong>".$limit_no_post."</strong> "; _e("to continue the listing Click on add/submit events.",THEME_DOMAIN);
					}
					
					echo ".</p>";
				}//get_the_author_meta( 'description', $userID );
				?>
    </div>
    <?php  $desc = get_the_author_meta( 'description', $user_id ); ?>
    <?php if ( !empty( $desc ) ) { ?>
    <?php echo get_avatar( get_the_author_meta( 'user_email', $user_id ), '60', '', get_the_author_meta( 'display_name', $user_id ) ); ?>
    <p class="user-bio"> <?php echo $desc; ?> </p>
    <!-- .user-bio -->
    <?php } ?>
  </div>
</div>
<!-- Display author box on author dashboard -->
<?php elseif ( is_search() ) : ?>
<div class="loop-meta extra-search-criteria-title">
  <h1 class="loop-title"><?php 
  $search_text = (get_search_query() != ' ') ? get_search_query() : $_REQUEST['location'];
   _e('Search results for',THEME_DOMAIN);echo ' "'.$search_text.'"'; ?></h1>
   <?php do_action('after_search_result_label'); ?>
  <div class="loop-description no_search_found_msg">
  <?php
	global $current_cityinfo;
	if((isset($_REQUEST['radius']) && $_REQUEST['radius']!='') || (isset($_REQUEST['location']) && $_REQUEST['location']!='')){
		if(isset($_REQUEST['radius']) && $_REQUEST['radius']==1){
			$radius_type=(isset($_REQUEST['radius_type']) && $_REQUEST['radius_type']=='kilometer')? 'kilometer': 'mile';
		}
		if(isset($_REQUEST['radius']) && $_REQUEST['radius']!=1 && $_REQUEST['radius']!=""){
			$radius_type=(isset($_REQUEST['radius_type']) && $_REQUEST['radius_type']=='kilometer')? 'kilometers': 'miles';
		}
		$radius=(isset($_REQUEST['location']) && $_REQUEST['location']!='')?  $_REQUEST['radius'].' '.$radius_type.' around "'.$_REQUEST['location'].'"' : $_REQUEST['radius'].' '.$radius_type.' around "'.$current_cityinfo['cityname'].'"';
	}	
	if(isset($radius)){
	?>
    <p><?php _e( 'You are browsing the search results for ', THEME_DOMAIN )."<strong>".esc_attr( get_search_query() )."</strong> ".'<span>'. $radius.'</span>'; ?></p>
	<?php } ?>
  </div>
  <!-- .loop-description -->
</div>
<!-- .loop-meta -->
<?php elseif ( is_date() ) : ?>
<div class="loop-meta">
  <?php if ( is_day() ) : ?>
  <h1 class="loop-title"><?php _e( 'Daily Archives: ', THEME_DOMAIN ). '<span>' . get_the_date() . '</span>';?></h1>
  <div class="loop-description">
    <p><?php _e( 'You are browsing the site archives by.', THEME_DOMAIN ). '<span>' . get_the_date() . '</span>'; ?></p>
  </div>
  <!-- .loop-description -->
  <?php elseif ( is_month() ) :?>
  <h1 class="loop-title"><?php _e( 'Monthly Archives:', THEME_DOMAIN ). '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', THEME_DOMAIN ) ) . '</span>';?></h1>
  <div class="loop-description">
    <p><?php _e( 'You are browsing the site archives by.', THEME_DOMAIN ). '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', THEME_DOMAIN ) ) . '</span>'; ?></p>
  </div>
  <!-- .loop-description -->
  <?php elseif ( is_year() ) :?>
  <h1 class="loop-title"><?php _e( 'Yearly Archives:', THEME_DOMAIN ).'<span>' . get_the_date( _x( 'Y', 'yearly archives date format', THEME_DOMAIN ) ) . '</span>' ; ?></h1>
  <div class="loop-description">
    <p><?php _e('You are browsing the site archives by.', THEME_DOMAIN ).'<span>' . get_the_date( _x( 'Y', 'yearly archives date format', THEME_DOMAIN ) ) . '</span>' ; ?></p>
  </div>
  <!-- .loop-description -->
  <?php endif;?>
</div>
<!-- .loop-meta -->
<?php elseif ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) : ?>
<?php $post_type = get_post_type_object( get_query_var( 'post_type' ) ); ?>
<div class="loop-meta">
  <h1 class="loop-title">
    <?php post_type_archive_title(); ?>
  </h1>
  <div class="loop-description">
    <?php if ( !empty( $post_type->description ) ) echo "<p>{$post_type->description}</p>"; ?>
  </div>
  <!-- .loop-description -->
</div>
<!-- .loop-meta -->
<?php elseif ( is_archive() ) : ?>
<div class="loop-meta">
  <h1 class="loop-title">
    <?php _e( 'Archives', THEME_DOMAIN ); ?>
  </h1>
  <div class="loop-description">
    <p>
      <?php _e( 'You are browsing the site archives.', THEME_DOMAIN ); ?>
    </p>
  </div>
  <!-- .loop-description -->
</div>
<!-- .loop-meta -->
<?php endif; ?>