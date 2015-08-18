<?php 

/* get the color settings from customizer and write in theme_options.css file located in functions */
function directory_hex2rgb($hex='') {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

/*
    File contain the code for color options in customizer 
*/
global $wpdb;
if(function_exists('supreme_get_setting')){
	$color1 = supreme_get_setting( 'color_picker_color1' );
	$color2 = supreme_get_setting( 'color_picker_color2' );
	$color3 = supreme_get_setting( 'color_picker_color3' );
	$color4 = supreme_get_setting( 'color_picker_color4' );
	$color5 = supreme_get_setting( 'color_picker_color5' );
	$color6 = supreme_get_setting( 'color_picker_color6' );
}else{
	$supreme_theme_settings = get_option(supreme_prefix().'_theme_settings');
	if(isset($supreme_theme_settings[ 'color_picker_color1' ]) && $supreme_theme_settings[ 'color_picker_color1' ] !=''):
		$color1 = $supreme_theme_settings[ 'color_picker_color1' ];
	else:
		$color1 ='';
	endif;
	
	if(isset($supreme_theme_settings[ 'color_picker_color2' ]) && $supreme_theme_settings[ 'color_picker_color2' ] !=''):
		$color2 = $supreme_theme_settings[ 'color_picker_color2' ];
	else:
		$color2 = '';
	endif;
	
	if(isset($supreme_theme_settings[ 'color_picker_color3' ]) && $supreme_theme_settings[ 'color_picker_color3' ] !=''):
		$color3 = $supreme_theme_settings[ 'color_picker_color3' ];
	else:
		$color3 ='';
	endif;
	
	if(isset($supreme_theme_settings[ 'color_picker_color4' ]) && $supreme_theme_settings[ 'color_picker_color4' ] !=''):
		$color4 = $supreme_theme_settings[ 'color_picker_color4' ];
	else:
		$color4 = '';
	endif;
	
	if(isset($supreme_theme_settings[ 'color_picker_color5' ]) && $supreme_theme_settings[ 'color_picker_color5' ] !=''):
		$color5 = $supreme_theme_settings[ 'color_picker_color5' ];
	else:
		$color5 ='';
	endif;
	
	if(isset($supreme_theme_settings[ 'color_picker_color6' ]) && $supreme_theme_settings[ 'color_picker_color6' ] !=''):
		$color6 = $supreme_theme_settings[ 'color_picker_color6' ];
	else:
		$color6 ='';
	endif;
}

//Change color of body background
if($color1 != "#" && !empty($color1)){
	$color_data=<<<COLOR1
body,
.map_full_width,
body #main > .wrap.row,
#header, .sidebar-after-header, .sidebar-subsidiary, div#menu-subsidiary, body .nav_bg .widget-nav-menu, .widget.templatic_slider, #sidebar-subsidiary, #sidebar-subsidiary-2c, #sidebar-subsidiary-3c, .sidebar-after-header, .sidebar-subsidiary, .footer_top .footer-wrap, .home .map_fixed_width,
.home .map_full_width,
body .event_manager_tab ul.event_type li a.active, .wrap404,.wordpress .tabs dd.active a, .wordpress .tabs .tab-title.active a {background-color:$color1;}

body #header,body .footer_top .footer-wrap.row,body.tevolution-event-manager .ui-widget-header .ui-state-active a, body.tevolution-directory .ui-widget-header .ui-state-active a:link, body.tevolution-directory .ui-widget-header .ui-state-active a:visited,
.author_custom_post_wrapper ul li a.nav-author-post-tab-active,
body.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active, 
body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active,	
body.woocommerce div.product .woocommerce-tabs ul.tabs li.active, body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active,
.tab-bar-section.middle,
.tab-bar, .d_location_type_navigation {background:$color1!important;}

@media screen and (max-width:1200px){  
 .toggle_handler #directorytab {background:$color1!important;}
}

a.button:hover, input[type="reset"]:hover, input[type="submit"]:hover, input[type="button"]:hover, a.button:hover, .button:hover, .uploadfilebutton:hover, .upload:hover, body.woocommerce a.button, body.woocommerce button.button, body.woocommerce input.button, body.woocommerce #respond input#submit, body.woocommerce #content input.button, body.woocommerce-page a.button, body.woocommerce-page button.button, body.woocommerce-page input.button, body.woocommerce-page #respond input#submit, body.woocommerce-page #content input.button, #searchform input[type="submit"], body.woocommerce .widget_layered_nav_filters ul li a, body.woocommerce-page .widget_layered_nav_filters ul li a, div.woocommerce form.track_order input.button, body.woocommerce a.button.alt, body.woocommerce button.button.alt, body.woocommerce input.button.alt, body.woocommerce #respond input#submit.alt, body.woocommerce #content input.button.alt, body.woocommerce-page a.button.alt, body.woocommerce-page button.button.alt, body.woocommerce-page input.button.alt, body.woocommerce-page #respond input#submit.alt, body.woocommerce-page #content input.button.alt,
body .mega-menu ul.mega li:hover a, body .mega-menu ul.mega li a:hover, body .mega-menu ul.mega li.current-menu-item a, body .mega-menu ul.mega li.current-page-item a,
body .mega-menu ul.mega li .sub-container.non-mega .sub a:hover, body .mega-menu ul.mega li .sub-container.non-mega li a:hover, body .mega-menu ul.mega li .sub-container.non-mega li.current-menu-item a, body .mega-menu ul.mega.sub li.mega-hdr li a:hover, body .mega-menu ul.mega li .sub li.mega-hdr a.mega-hdr-a:hover, #footer .footer_bottom, body.singular #content .claim-post-wraper ul li a:hover, body.tevolution-event-manager .get_direction .b_getdirection,
body #loop_event_taxonomy.list .post .entry .date, body #loop_event_archive.list .post .entry .date, body.woocommerce nav.woocommerce-pagination ul li a:hover, body.woocommerce-page nav.woocommerce-pagination ul li a:hover, body.woocommerce #content nav.woocommerce-pagination ul li a:hover, body.woocommerce-page #content nav.woocommerce-pagination ul li a:hover, body.woocommerce nav.woocommerce-pagination ul li span.current, body.woocommerce-pagenav.woocommerce-pagination ul li span.current, body.woocommerce #content nav.woocommerce-pagination ul li span.current, body.woocommerce-page #content nav.woocommerce-pagination ul li span.current, body div.product form.cart .button, body #content div.product form.cart .button, body.woocommerce .quantity .plus, body.woocommerce-page .quantity .plus, body.woocommerce #content .quantity .plus, body.woocommerce-page #content .quantity .plus, body.woocommerce .quantity .minus, body.woocommerce-page .quantity .minus, body.woocommerce #content .quantity .minus, body.woocommerce-page #content .quantity .minus,
body.woocommerce a.button.alt:hover, body.woocommerce-page a.button.alt:hover, body.woocommerce button.button.alt:hover, body.woocommerce-page button.button.alt:hover, body.woocommerce input.button.alt:hover, body.woocommerce-page input.button.alt:hover, body.woocommerce #respond input#submit.alt:hover, body.woocommerce-page #respond input#submit.alt:hover, body.woocommerce #content input.button.alt:hover, body.woocommerce-page #content input.button.alt:hover, article.event .entry-header span.date, .widget #wp-calendar caption, .widget #wp-calendar th, .uploadfilebutton:hover, body .secondary_btn:hover, body .ui-datepicker-trigger, body .ui-datepicker-trigger:hover,
.comment-pagination .page-numbers:hover, .loop-nav span.next:hover, .loop-nav span.previous:hover, .pagination .page-numbers:hover, body .pos_navigation .post_left a:hover, body .pos_navigation .post_right a:hover,
.comment-pagination .page-numbers.current, .loop-nav span.next.current, .loop-nav span.previous.current, .pagination .page-numbers.current, body .pos_navigation .post_left a.current, body .pos_navigation .post_right a.current
{color: $color1;}
   
body .event_manager_tab ul.event_type li a.active,
body.tevolution-event-manager .ui-widget-header .ui-state-active a, body.tevolution-directory .ui-widget-header .ui-state-active a:link, body.tevolution-directory .ui-widget-header .ui-state-active a:visited,
body.tevolution-directory .ui-widget-header .ui-state-active a, body.tevolution-directory .ui-widget-header .ui-state-active a:link, body.tevolution-directory .ui-widget-header .ui-state-active a:visited
{border-bottom-color: $color1}

body.woocommerce div.product .woocommerce-tabs ul.tabs li.active, body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active, body.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active, body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active,
#tabs ul li.ui-tabs-active a,
body.singular-property #tabs ul.ui-tabs-nav li.ui-tabs-active a,
body .author_custom_post_wrapper ul li a.nav-author-post-tab-active {border-bottom-color: $color1;!important}

body .secondary_btn:hover, .comment-pagination .page-numbers:hover strong, strong.prev:hover, strong.next:hover, .loop-nav span.next:hover, .loop-nav span.previous:hover, .pagination .page-numbers:hover strong, body .pos_navigation .post_left a:hover, body .pos_navigation .post_right a:hover, a.current.page-numbers, a.page-numbers[title~="Last"]:hover, a.page-numbers[title~="First"]:hover {  color: $color1;!important}

COLOR1;
}// Finish Color 1 if condition


//Change blue color 
if($color2 != "#" && !empty($color2)){
	
	$color2_rgba = directory_hex2rgb($color2);
	$color2_1 = '';
	$color2_2 = 0;
	$color2_3 = 0;
	if($color2_rgba[0] >= 0 && $color2_rgba[1] > 0)
	{
		$color2_1 = $color2_rgba[0];
	}
	if($color2_rgba[1] > 0 )
	{
		$color2_2 = $color2_rgba[1];
	}
	if($color2_rgba[2] > 0 )
	{
		$color2_3 = $color2_rgba[2];
	}

	if($color2 != '') {
		$none = 'none';
	}
	
	$colorblack_rgba = directory_hex2rgb($color2);
	$colorblack_1 = 0;
	$colorblack_2 = 0;
	$colorblack_3 = 0;
	
	$color_data.=<<<COLOR2
body .primary_menu_wrapper,
div#menu-secondary .wrap, div#menu-secondary1 .wrap, div#menu-subsidiary .wrap, .nav_bg .widget-nav-menu nav,
div#menu-secondary .menu ul ul, div#menu-secondary1 .menu ul ul, div#menu-subsidiary .menu ul ul, .nav_bg .widget-nav-menu ul ul,
div#menu-primary .menu ul ul,
.widget #wp-calendar caption,
#footer .footer_bottom,
.tags a:hover, .tagcloud a:hover, .browse_by_tag a:hover,
body .ui-datepicker-trigger:hover,
button:hover,  input[type="reset"]:hover,  input[type="submit"]:hover,  input[type="button"]:hover,  a.button:hover,  .button:hover, .uploadfilebutton:hover,
.submitbutton, body.woocommerce a.button.alt, body.woocommerce button.button.alt, body.woocommerce input.button.alt, body.woocommerce #respond input#submit.alt, body.woocommerce #content input.button.alt, body.woocommerce-page a.button.alt, body.woocommerce-page button.button.alt, body.woocommerce-page input.button.alt, body.woocommerce-page #respond input#submit.alt, body.woocommerce-page #content input.button.alt,
body.woocommerce .quantity .plus:hover, body.woocommerce-page .quantity .plus:hover, body.woocommerce #content .quantity .plus:hover, body.woocommerce-page #content .quantity .plus:hover, body.woocommerce .quantity .minus:hover, body.woocommerce-page .quantity .minus:hover, body.woocommerce #content .quantity .minus:hover, body.woocommerce-page #content .quantity .minus:hover,
body .main_btn,
.stickyheader .header_container,
body .mega-menu .nav_bg,
#silde_gallery .flex-direction-nav li a,
body .mega-menu ul.mega li ul.sub-menu,
body #loop_property_taxonomy .post .entry .property-title .property-price .prop-price,
body .supreme_wrapper .home_page_banner .property_search #searchproperty .form_row .b_search_event { background-color: $color2; }



button:hover, input[type="reset"]:hover, input[type="submit"]:hover, input[type="button"]:hover, a.button:hover, .button:hover, .uploadfilebutton:hover,
.comment-pagination .page-numbers:hover, .loop-nav span.next:hover, .loop-nav span.previous:hover, .pagination .page-numbers:hover, body .pos_navigation .post_left a:hover, body .pos_navigation .post_right a:hover{background-color:$color2 !important}   
body .templatic_advanced_search #widget_searchform .ui-datepicker-trigger:hover,
.upload:hover, body.woocommerce a.button:hover, body.woocommerce button.button:hover, body.woocommerce input.button:hover, body.woocommerce #respond input#submit:hover, body.woocommerce #content input.button:hover, body.woocommerce-page a.button:hover, body.woocommerce-page button.button:hover, body.woocommerce-page input.button:hover, body.woocommerce-page #respond input#submit:hover, body.woocommerce-page #content input.button:hover, #content input.button:hover, #searchform input[type="submit"]:hover, body.woocommerce .widget_layered_nav_filters ul li a:hover, body.woocommerce-page .widget_layered_nav_filters ul li a:hover, div.woocommerce form.track_order input.button:hover, body.woocommerce a.button.alt:hover, body.woocommerce button.button.alt:hover, body.woocommerce input.button.alt:hover, body.woocommerce #respond input#submit.alt:hover, body.woocommerce #content input.button.alt:hover, body.woocommerce-page a.button.alt:hover, body.woocommerce-page button.button.alt:hover, body.woocommerce-page input.button.alt:hover, body.woocommerce-page #respond input#submit.alt:hover, body.woocommerce-page #content input.button.alt:hover,
body .mega-menu ul.mega li .sub-container.non-mega .sub a:hover, body .mega-menu ul.mega li .sub-container.non-mega li a:hover, body .mega-menu ul.mega li .sub-container.non-mega li.current-menu-item a,
.left-off-canvas-menu,
.right-off-canvas-menu,
.singular-property .supreme_wrapper .entry-header-custom-wrap ul li i,
body .pagination .current,
.pagination a:hover,.loop-nav a span:hover,
.pagination .page-numbers.next:hover:before, .pagination .page-numbers.previous:hover:before, .pagination .page-numbers.prev:hover:before,
.mobile-view #content .claim-post-wraper ul li a#send_inquiry_id:hover,
#silde_gallery .flex-direction-nav li a { background: $color2!important }

.singular-property .entry-header-custom-wrap ul li i,
body.singular-property .entry-header-custom-wrap ul li i,
body.singular-property #property_image_gallery ul.photos li a:hover:before,
footer.footer,
body .secondary_btn:hover, .comment-pagination .page-numbers:hover strong, strong.prev:hover, strong.next:hover, .loop-nav span.next:hover, .loop-nav span.previous:hover, .pagination .page-numbers:hover strong, body .pos_navigation .post_left a:hover, body .pos_navigation .post_right a:hover, a.current.page-numbers, a.page-numbers[title~="Last"]:hover, a.page-numbers[title~="First"]:hover { background-color: $color2 }

body .pagination .current,
.pagination a:hover,.loop-nav a span:hover,
.pagination .page-numbers.next:hover:before, .pagination .page-numbers.previous:hover:before, .pagination .page-numbers.prev:hover:before { background: $color2!important }

a,
a.comment_excerpt,
.list .entry .bottom_line a:hover,
.social_media ul li a:hover abbr,
.byline a:hover, .entry-meta a:hover,
#site-title, #site-title1,
#site-title a, #site-title1 a,
#breadcrumb a:hover,  .breadcrumb a:hover,
.byline a:hover, .entry-meta a:hover,
.entry-meta .category a:hover, .entry-meta .post_tag a:hover,
.post_info_meta a:hover,
.comment-meta a:hover,
#respond #cancel-comment-reply-link,
.templatic_twitter_widget .twit_time,
#recentcomments a,
.arclist h2,
.arclist ul li a:hover,
.arclist ul li .arclist_date a:hover,
body.woocommerce div.product .woocommerce-tabs ul.tabs li.active, body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active, body.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active, body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active,
#content ul.products li.product:hover h3,
#content ul.products li.product .price,
#content ul.products li.product .price .from, #content ul.products li.product .price del,
body.woocommerce p.stars a:hover:before, body.woocommerce p.stars a:focus:before, body.woocommerce p.stars a:active:before, body.woocommerce p.stars a.active:before, body.woocommerce-page p.stars a:hover:before, body.woocommerce-page p.stars a:focus:before, body.woocommerce-page p.stars a:active:before, body.woocommerce-page p.stars a.active:before,
body.woocommerce div.product span.price, body.woocommerce-page div.product span.price, body.woocommerce #content div.product span.price, body.woocommerce-page #content div.product span.price, body.woocommerce div.product p.price, body.woocommerce-page div.product p.price, body.woocommerce #content div.product p.price, body.woocommerce-page #content div.product p.price,
.show_review_form,
body #sub_event_categories ul li a,
body .all_category_list_widget .category_list h3 a:hover,
.widget .follow_us_twitter:hover,
.listing_post .hentry h2 a,
.home_page_banner .flexslider ul li .post_list .slider-post h2 a,
.attending_event span.fav span.span_msg a:hover,
body .widget #wp-calendar .calendar_tooltip .event_title,
.all_category_list_widget .category_list h3 a:hover, body #sub_listing_categories ul li a,
body .all_category_list_widget .category_list ul li a,
body .related_post_grid_view li h3 a, del span.amount,
body #tev_sub_categories ul li a,
.custom-content-widget h3 a:hover,
.custom-content-widget i,
.custom-content-widget .viewmore:hover,
body #loop_property_taxonomy .post .entry .property-title a:hover,
body.singular-property #content .claim-post-wraper ul li a:hover,
#tabs ul li a:hover,
#tabs ul li.ui-tabs-active a,
.singular-property .property .entry-header-right .property-price,
body #loop_property_taxonomy .post .entry .moretag:hover, body #tmpl-search-results .post .entry .moretag:hover,
.social_media ul li a:hover i,
body #tabs.ui-tabs .ui-tabs-nav li.ui-tabs-active a,
body .sidebar .categories li a:hover,
body .directory_manager_tab ul.view_mode li a#gridview:hover:after, body .directory_manager_tab ul.view_mode li a#gridview.active:after,
body .directory_manager_tab ul.view_mode li a#listview:hover:after, body .directory_manager_tab ul.view_mode li a#listview.active:after,
.tmpl-agent-details p a:hover,
body.singular-property .property .entry-header-right .property-price,
body.singular-property #tabs ul.ui-tabs-nav li.ui-tabs-active a,
body.singular-property #tabs ul.ui-tabs-nav li a:hover,
body .author_custom_post_wrapper ul li a:hover,
ol li a:hover, ul li a:hover,
.supreme_wrapper .fav a.addtofav:hover, .supreme_wrapper .fav a.removefromfav:hover,
.list .entry .bottom_line a:hover,
#post-listing .complete .step-heading,
.singular-property .supreme_wrapper .property .property-price span,
.entry-links-mobile ul li:hover a,
.error_404 h4 { color: $color2;}

@media screen and (max-width:1200px){
   .toggle_handler #directorytab,body .mobile-search { color: $color2;}
}

body.home .fav .addtofav:hover,
body.home .fav .removefromfav:hover,
body #content .people_info h3 a,
body #content .add_to_my_calendar .addtocalendar ul li a:hover
{color: $color2 !important}

.social_media ul li a:hover abbr,
.nav_bg .widget input[type="text"]:focus, .mega-menu .widget .search-form input:focus,
.recent_comments li span a img:hover,
body table.calendar_widget td.date_n div span.calendar_tooltip, body #loop_property_taxonomy .post .entry .moretag:hover, body #tmpl-search-results .post .entry .moretag:hover,
.social_media ul li a i:hover,
.custom-content-widget .viewmore:hover,
#footer .social_media ul li a:hover i { border-color: $color2; }

#footer .footer_bottom a:hover
{color:rgba($colorblack_1,$colorblack_2,$colorblack_3,0.5)!important;}

#post-listing .complete .step-heading,
.search404
{background:rgba($color2_1,$color2_2,$color2_3,0.1)!important;}
.packageblock .packagelistitems.selected
{background:rgba($color2_1,$color2_2,$color2_3,0.2)!important;}
	
.tab-bar .menu-icon span { box-shadow: 0 0 0 1px $color2, 0 7px 0 1px $color2, 0 14px 0 1px $color2 !important; }


COLOR2;
}// Finish Color 2 if condition



//Change color of page content
if($color3 != "#" && !empty($color3)){
	$color_data.=<<<COLOR3
.widget #wp-calendar th,
body .mega-menu ul.mega li a:hover, body .mega-menu ul.mega li.current-menu-item a, body .mega-menu ul.mega li.current-page-item a, body .mega-menu ul.mega li:hover a,
.dsidx #dsidx-listings .dsidx-primary-data .dsidx-address + .dsidx-price { background-color: $color3; }

body .supreme_wrapper .home_page_banner .property_search #searchproperty .form_row .b_search_event:hover { background-color: $color3;}

body,
a:hover,
.widget h3, .widget.title, .widget-title, .widget-search .widget-title,
.tags a, .tagcloud a, .browse_by_tag a,
.social_media ul li a abbr,
.loop-nav span.previous, .loop-nav span.next, .pagination .page-numbers, .comment-pagination .page-numbers, body .pos_navigation .post_left a, body .pos_navigation .post_right a,
input[type="date"],  input[type="datetime"],  input[type="datetime-local"],  input[type="email"],  input[type="month"],  input[type="number"],  input[type="password"],  input[type="search"],  input[type="tel"],  input[type="text"],  input.input-text,  input[type="time"],  input[type="url"],  input[type="week"],  select,  textarea,
.entry-meta .category a, .entry-meta .post_tag a,
.post_info_meta a,
.comment-author,
#respond #cancel-comment-reply-link:hover,
.widget .follow_us_twitter,
.listing_post .post h2 a:hover,
.arclist ul li .arclist_date, .arclist ul li .arclist_date a,
.arclist ul li span.arclist_comment a,
ins span.amount,
body .fav .addtofav:hover,
body .package label h3,
body.woocommerce div.product .woocommerce-tabs ul.tabs li a, body.woocommerce-page div.product .woocommerce-tabs ul.tabs li a, body.woocommerce #content div.product .woocommerce-tabs ul.tabs li a, body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a,
body .fav .addtofav,
body .fav .removefromfav,
body .widget table.calendar_widget td.date_n div span.calendar_tooltip small,
body .widget table.calendar_widget td.date_n div span.calendar_tooltip small .wid_event_list b.label,
body #loop_listing_taxonomy .post .entry p, body #loop_listing_archive .post .entry p,
body.tevolution-directory .ui-widget-header li a,
body .ui-widget-content,
body .event_manager_tab ul.event_type li a,
body.tevolution-event-manager .ui-widget-header li a,
body #loop_event_taxonomy .post .entry p, body #loop_event_archive .post .entry p,
body #content .claim_ownership .claimed,
body .author_custom_post_wrapper ul li a,
body.woocommerce nav.woocommerce-pagination ul li a, body.woocommerce-page nav.woocommerce-pagination ul li a, body.woocommerce #content nav.woocommerce-pagination ul li a, body.woocommerce-page #content nav.woocommerce-pagination ul li a,
.upload.button span.upload_title, .upload.button span,
body .hentry.error, body.woocommerce .star-rating:before, body.woocommerce-page .star-rating:before, #content ul.products li.product a .star-rating,
.shop_table th, body.woocommerce .woocommerce-message, body.woocommerce .woocommerce-error, body.woocommerce .woocommerce-info, body.woocommerce-page .woocommerce-message, body.woocommerce-page .woocommerce-error, body.woocommerce-page .woocommerce-info, body.woocommerce #payment div.payment_box, body.woocommerce-page #payment div.payment_box,
.widget_loop_taxonomy .post .entry p,
.home_page_full_content .widget h3, .home_page_full_content .widget-title, .home_page_content .widget h3, .home_page_content .widget-title,
body #loop_property_taxonomy .post .entry .property-title a,
body .widget_loop_taxonomy .post .entry p,
body .widget_loop_taxonomy .post .entry .phone,
.custom-content-widget h3 a,
.custom-content-widget .viewmore,
body #loop_property_taxonomy .post .entry .property-title .property-price,
body.singular-property #content .claim-post-wraper ul li a,
#tabs ul li a, body .agent-social-networks a,
body .tmpl_property_agent .agent-top_wrapper .tmpl-agent-detail-rt p.title,
.property-page-end .view_counter p span em, 
.property-page-end .view_counter p span i,
.singular-property .property .entry-header-right strong,
body #loop_property_taxonomy .post .entry .moretag, body #tmpl-search-results .post .entry .moretag,
.singular-property #tabs .property_custom_field p strong,
.contact-detail label,
.custom-banner-widget h3,
.social_media ul li a i,
#footer .copyright, #footer .credit,
body .classified .contact-no,
body .classified .spt-left > ul li,
body #tabs.ui-tabs .ui-tabs-nav li a,
body .classified .classified-price + span.price-type,
body #loop_event_archive .post .entry .phone, body #loop_event_taxonomy .post .entry p.phone,
body .all_category_list_widget .category_list ul li,
body #loop_property_taxonomy .post .entry p.phone,
body #loop_property_taxonomy.grid .rev_pin a,
body .property_search #searchproperty .form_row.advt-ptype span.chkbox label,
body .home_page_banner .property_search #searchproperty .form_row > label,
.widget.property_search h3.widget-title,
body #loop_listing_archive.list .post .entry .bottom_line a, body #loop_listing_taxonomy.list .post .entry .bottom_line a, body #tmpl-search-results.list .hentry .entry .bottom_line a, .user #content .hentry .entry .bottom_line a, .hfeed .post .entry,
body .sidebar .categories li,
body .sidebar .categories li a,
#footer .home_page_below_content .subscribe_cont h3,
.home_page_below_content .slider_carousel h3,
.home_page_below_content .slider_carousel p,
.d_location_type_navigation .d_location_navigation_right .horizontal_city_name,
.home_page_below_content .flex-direction-nav li a,
body .directory_manager_tab ul.view_mode li a#gridview:after,
body .directory_manager_tab ul.view_mode li a#listview:after,
body .tmpl_search_property #tmpl_find_property h4,
.tmpl-agent-details p a,
body.singular-property #tabs .property_custom_field p label,
body.singular-property #tabs .property_custom_field p strong,
body.singular-property .property .entry-header-right strong,
body.singular-property .property .entry-header-right span.tmpl-rentperiod,
body.singular-property #tabs ul.ui-tabs-nav li a,
body .property-page-end .view_counter p span em,
body .property-page-end .view_counter p span i,
.dsidx #dsidx.dsidx-details #dsidx-header table#dsidx-primary-data td, .dsidx #dsidx.dsidx-details #dsidx-secondary-data td, .dsidx #dsidx.dsidx-details .dsidx-supplemental-data td,
.dsidx #dsidx.dsidx-details #dsidx-header table#dsidx-primary-data th, .dsidx #dsidx.dsidx-details #dsidx-secondary-data th, .dsidx #dsidx.dsidx-details .dsidx-supplemental-data th,
.dsidx #dsidx.dsidx-details blockquote#dsidx-description,
body.dsidx #dsidx-actions a,
body #dsidx table,
.dsidx #dsidx-listings .dsidx-primary-data .dsidx-address a,
body #loop_listing_archive .post .entry p, body #loop_listing_taxonomy .post .entry p, body #tmpl-search-results.list .hentry p, .entry-details p,
.grid .post p.event_date:before, .post .favourite:before, .attended_persons:before,
.list .post p.owner_name:before, .grid .post p.owner_name:before, .list .post p.phone:before, .grid .post p.phone:before, .list .post p.address:before, .grid .post p.address:before, .list .post p.time:before, .grid .post p.time:before, .list .post p.event_date:before, .grid .post p.event_date:before, .list .post p.address:before, .grid .post p.address:before, .list .post p.time:before, .grid .post p.time:before, .peoplelink .website:before, .peoplelink .facebook:before, .peoplelink .twitter:before, .peoplelink .linkedin:before, .links .email:before, .links .phone:before, .post .rev_pin ul li.pinpoint:before, .post .rev_pin ul li.review:before,
a:hover, ol li a:hover, ul li a:hover,
div#menu-secondary .menu li a:hover,
#breadcrumb a:hover, .breadcrumb a:hover,
div#menu-secondary .menu li a:hover, div#menu-secondary1 .menu li a:hover, div#menu-secondary .menu li:hover > a, div#menu-secondary1 .menu li:hover > a, div#menu-secondary .menu li.current-menu-item > a, div#menu-secondary1 .menu li.current-menu-item > a, div#menu-subsidiary .menu li.current-menu-item > a{ color: $color3!important;}

div.event_manager_tab ul.view_mode li a.active:before, div.directory_manager_tab ul.view_mode li a.active:before { color: $color3; }
   
body #map_canvas .google-map-info .map-inner-wrapper .map-item-info h6 a, 
#loop_classified_taxonomy.list .entry .classified-price { color:$color3!important; }

.social_media ul li a abbr,
body .secondary_btn,
.social_media ul li a i { border-color:$color3 }

COLOR3;
}// Finish Color 3 if condition


//Change color of lighter-blue
if($color4 != "#" && !empty($color4)){
	$color_data.=<<<COLOR4
div#menu-secondary .menu li a, div#menu-secondary1 .menu li a, div#menu-subsidiary .menu li a, .nav_bg .widget-nav-menu li a,
div#menu-primary .menu li a,
#footer .footer_bottom .menu a:hover,
#footer .credit,
#footer .credit a,
body .mega-menu ul.mega li a,
body .mega-menu ul.mega li .sub .row li a,
#footer .footer_bottom a,
body .mega-menu ul.mega li .sub a,
body .mega-menu ul.mega li .sub li.mega-hdr a.mega-hdr-a,
#footer .footer_bottom .social_media ul li a:hover i
{color: $color4!important}
body #content .claim-post-wraper ul li a:hover
{color: $color4!important}

#footer .footer_bottom .social_media ul li a:hover i { border-color: $color4; }
COLOR4;
}// Finish Color 4 if condition


//Change color of lighter-gray
if($color5 != "#" && !empty($color5)){
	$color_data.=<<<COLOR5
.post_info_meta a,.byline,.byline a,#breadcrumb .trail-end, .breadcrumb .trail-end,.entry-meta,.comment-meta span.comment-reply:after,.widget-widget_rss ul li span.rss-date,  .widget-widget_rss ul li cite,
.nav_bg .widget input[type="text"], .mega-menu .widget .search-form input,.arclist ul li span.arclist_comment,div#menu-mobi-primary .menu li a,div#menu-mobi-secondary .menu li a, div#menu-mobi-secondary1 .menu li a,  div#menu-subsidiary .menu li a,.woocommerce-checkout .form-row .chzn-container-single .chzn-single,.gfield_description,body .message_note, body .form_row span.message_note, body .form_row .description,
body .form_row label:hover,.flex-control-paging li a,
body .user_dsb_cf label,
 
body #loop_listing_taxonomy.list .post .entry .bottom_line a, body #loop_listing_archive.list .post .entry .bottom_line a, body.tevolution-directory .post-meta,
.comment-meta .published,

body #loop_event_atteding_list.list .post .entry .bottom_line a,
body.tevolution-event-manager .post-meta,
body #loop_event_taxonomy.list .post .entry .bottom_line a, body #loop_event_taxonomy.list .post .entry .bottom_line a,

body .form_row span.error_message, body .error, body.message_error,
body .event_show_event button,
body.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover, body.woocommerce-page div.product .woocommerce-tabs ul.tabs li a:hover, body.woocommerce #content div.product .woocommerce-tabs ul.tabs li a:hover, body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a:hover, .listing_post .hentry h2 a:hover,
body.woocommerce #reviews #comments ol.commentlist li .meta, body.woocommerce-page #reviews #comments ol.commentlist li .meta 
{color:$color5;}
   
body .message_note, body .form_row span.message_note, body .form_row .description {color:$color5!important;   }
COLOR5;
}// Finish Color 5 if condition



//Change color of black
if($color6 != "#" && !empty($color6)){
	$color_data.=<<<COLOR6
.loop-nav span.previous:hover, .loop-nav span.next:hover, .pagination .page-numbers:hover, .comment-pagination .page-numbers:hover, .bbp-pagination .page-numbers:hover, body .pagination .current, body .pos_navigation .post_left a:hover, body .pos_navigation .post_right a:hover,
body .ui-datepicker-trigger,
button,  input[type="reset"],  input[type="submit"],  input[type="button"],  a.button,  .button,
body .secondary_btn:hover,
.loop-nav span.previous:hover, .loop-nav span.next:hover, .pagination .page-numbers:hover, .comment-pagination .page-numbers:hover, .bbp-pagination .page-numbers:hover, body .pagination .current, body .pos_navigation .post_left a:hover, body .pos_navigation .post_right a:hover,
div#menu-mobi-secondary .menu li a:hover, div#menu-mobi-secondary1 .menu li a:hover,  div#menu-subsidiary .menu li a:hover,
div#menu-mobi-secondary .menu li li:hover > a, div#menu-mobi-secondary1 .menu li li:hover > a,  div#menu-mobi-secondary .menu li li a:hover,  div#menu-mobi-secondary1 .menu li li a:hover,  div#menu-subsidiary .menu li li a:hover,  .nav_bg .widget-nav-menu li li a:hover,
body.woocommerce nav.woocommerce-pagination ul li a:hover, body.woocommerce-page nav.woocommerce-pagination ul li a:hover, body.woocommerce #content nav.woocommerce-pagination ul li a:hover, body.woocommerce-page #content nav.woocommerce-pagination ul li a:hover, body.woocommerce nav.woocommerce-pagination ul li span.current, body.woocommerce-page nav.woocommerce-pagination ul li span.current, body.woocommerce #content nav.woocommerce-pagination ul li span.current, body.woocommerce-page #content nav.woocommerce-pagination ul li span.current,
.flex-control-paging li a:hover, .flex-control-paging li a.flex-active,
body .recurrence_text,
 
body #content .claim-post-wraper ul li a:hover,

body #loop_event_atteding_list.list .post .entry .date,
body #loop_event_taxonomy.list .post .entry .date, body #loop_event_archive.list .post .entry .date,
body .sort_order_alphabetical ul li.active a, body .sort_order_alphabetical ul li a:hover,
body.woocommerce .quantity .plus, body.woocommerce-page .quantity .plus, body.woocommerce #content .quantity .plus, body.woocommerce-page #content .quantity .plus, body.woocommerce .quantity .minus, body.woocommerce-page .quantity .minus, body.woocommerce #content .quantity .minus, body.woocommerce-page #content .quantity .minus,
body .ui-datepicker-calendar th,
body .widget_loop_taxonomy .post .fp_entry .date,
body article .entry-header span.date,
#silde_gallery .flex-direction-nav li a:hover
{background-color:$color6}

button, input[type="reset"], input[type="submit"], input[type="button"], a.button, .button, .uploadfilebutton {background-color:$color6 !important}
  
body .templatic_advanced_search #widget_searchform .ui-datepicker-trigger,
.upload.button:hover,
body.woocommerce a.button, body.woocommerce button.button, body.woocommerce input.button, body.woocommerce #respond input#submit, body.woocommerce #content input.button, body.woocommerce-page a.button, body.woocommerce-page button.button, body.woocommerce-page input.button, body.woocommerce-page #respond input#submit, body.woocommerce-page #content input.button, #searchform input[type="submit"], body.woocommerce .widget_layered_nav_filters ul li a, body.woocommerce-page .widget_layered_nav_filters ul li a, div.woocommerce form.track_order input.button, body.woocommerce a.button.alt, body.woocommerce button.button.alt, body.woocommerce input.button.alt, body.woocommerce #respond input#submit.alt, body.woocommerce #content input.button.alt, body.woocommerce-page a.button.alt, body.woocommerce-page button.button.alt, body.woocommerce-page input.button.alt, body.woocommerce-page #respond input#submit.alt, body.woocommerce-page #content input.button.alt,
body #content .upload.button:hover, #uploadimage:hover, body.woocommerce .widget_price_filter .ui-slider .ui-slider-handle, body.woocommerce-page .widget_price_filter .ui-slider .ui-slider-handle
{background:$color6}

.post_info_meta,
.comment-meta a,
h1, h2, h3, h4, h5, h6,
input[type="date"]:focus,  input[type="datetime"]:focus,  input[type="datetime-local"]:focus,  input[type="email"]:focus,  input[type="month"]:focus,  input[type="number"]:focus,  input[type="password"]:focus,  input[type="search"]:focus,  input[type="tel"]:focus,  input[type="text"]:focus,  input.input-text:focus,  input[type="time"]:focus,  input[type="url"]:focus,  input[type="week"]:focus,  select:focus,  textarea:focus,
#breadcrumb, .breadcrumb,
#breadcrumb a,  .breadcrumb a,
.entry-meta .category, .entry-meta .post_tag,
.comment-reply-link, .comment-reply-login,
.view_counter b,
.arclist ul li a,
.flex-direction-nav li a:hover,
.attending_event span.fav span.span_msg a,
.attending_event span.fav span.span_msg,
.attending_event span.fav a.addtofav,
body .sort_order_alphabetical ul li a,
body.woocommerce div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active a,
body #sub_event_categories ul li a:hover,
body .all_category_list_widget .category_list ul li a:hover,
body .user_dsb_cf span,
 
body #loop_listing_taxonomy.list .post .entry .bottom_line, body #loop_listing_archive.list .post .entry .bottom_line,
body #loop_listing_taxonomy .post .entry .phone, body #loop_listing_archive .post .entry .phone,
body.tevolution-directory .post-meta a,
body.directory-single-page .hentry .entry-header-title .entry-header-custom-wrap p label,
body #content .claim-post-wraper ul li a,
body #content .claim-post-wraper ul li a:after,
.comment-author cite,

body #loop_event_atteding_list.list .post .entry .bottom_line,
body.tevolution-event-manager .post-meta a,
body.event-single-page .hentry .entry-header-title .entry-header-custom-wrap p label,
body.tevolution-event-manager.event-single-page .entry-content h2,
body #loop_event_taxonomy .post .entry p strong, body #loop_event_archive .post .entry p strong,
body #loop_event_taxonomy.grid .post .entry .date, body #loop_event_archive.grid .post .entry .date,
body .all_category_list_widget .category_list h3 a,
body #loop_event_taxonomy.list .post .entry .bottom_line, body #loop_event_taxonomy.list .post .entry .bottom_line,
body .event-organizer .event-organizer-right label,
body .ui-widget-content a,
body .widget_loop_taxonomy.grid .post .fp_entry .date,
body #sub_listing_categories ul li a:hover,
body #tev_sub_categories ul li a:hover,
ul li a:hover, ol li a:hover,
.error_404 h4, body .related_post_grid_view li h3 a:hover,
body.woocommerce div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce-page div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce #content div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li.active a, body.woocommerce div.product .woocommerce-tabs ul.tabs li a:hover, body.woocommerce-page div.product .woocommerce-tabs ul.tabs li a:hover, body.woocommerce #content div.product .woocommerce-tabs ul.tabs li a:hover, body.woocommerce-page #content div.product .woocommerce-tabs ul.tabs li a:hover, #recentcomments a:hover,
body .social_media ul li a:hover i { color: $color6 }
   
body #map_canvas .google-map-info .map-inner-wrapper .map-item-info a:hover {color: $color6 !important;}
   
.loop-nav span.previous:hover, .loop-nav span.next:hover, .pagination .page-numbers:hover, .comment-pagination .page-numbers:hover, .bbp-pagination .page-numbers:hover, body .pagination .current, body .pos_navigation .post_left a:hover, body .pos_navigation .post_right a:hover,
body.woocommerce nav.woocommerce-pagination ul li a:hover, body.woocommerce-page nav.woocommerce-pagination ul li a:hover, body.woocommerce #content nav.woocommerce-pagination ul li a:hover, body.woocommerce-page #content nav.woocommerce-pagination ul li a:hover, body.woocommerce nav.woocommerce-pagination ul li span.current, body.woocommerce-page nav.woocommerce-pagination ul li span.current, body.woocommerce #content nav.woocommerce-pagination ul li span.current, body.woocommerce-page #content nav.woocommerce-pagination ul li span.current,
.flex-control-paging li a:hover, .flex-control-paging li a.flex-active,
body .social_media ul li a:hover i {border-color:$color6;}
   
.d_location_type_navigation {border-color: $color6 !important;  }
COLOR6;
}// Finish If Condition

if(($color1 == "#" || $color1 == "") && ($color2 == "#" || $color2 == "") && ($color3 == "#" || $color3 == "") && ($color4 == "#" || $color4 == "") && ($color5 == "#" || $color5 == "")  && ($color6 == "#" || $color6 == "")){
	file_put_contents(trailingslashit(get_template_directory())."css/admin_style.css", "");
}elseif(isset($_POST['wp_customize']) && $_POST['wp_customize']=='on'){
	file_put_contents(trailingslashit(get_template_directory())."css/admin_style.css" , $color_data); 
}