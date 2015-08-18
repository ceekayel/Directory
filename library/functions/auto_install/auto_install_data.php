<?php 
global $upload_folder_path,$wpdb,$blog_id;
$a = get_option(supreme_prefix().'_theme_settings');
$b = array(
		'supreme_logo_url' 					=> get_template_directory_uri()."/images/logo.png",
		'supreme_site_description'			=> '',
		'display_publish_date'				=> 1,
		'display_post_terms'				=> 1,
		'supreme_display_noimage'			=> 1,
		'supreme_archive_display_excerpt'	=> 1,
		'templatic_excerpt_length'			=> 50,
		'display_header_text'				=> 1,
		'supreme_show_breadcrumb'			=> 1,
		'tmpl_mobile_view'			=> 1,
		'enable_inquiry_form'				=> 1,
		'footer_insert' 					=> '<p class="copyright">&copy; '.date('Y').' <a href="'.home_url().'">'.get_option('blogname').'</a>.&nbsp;Designed by <a href="http://templatic.com" class="footer-logo"><img src="'.get_template_directory_uri().'/library/images/templatic-wordpress-themes.png" alt="WordPress Directory Theme" /></a></p>'
	);
update_option(supreme_prefix().'_theme_settings',$b);
update_option('posts_per_page',5);
update_option('show_on_front','page');
$args = array(
			'post_type' => 'page',
			'meta_key' => '_wp_page_template',
			'meta_value' => 'page-templates/front-page.php'
			);
$page_query = new WP_Query($args);
$front_page_id = $page_query->post->ID;
update_option('page_on_front',$front_page_id);


$dummy_image_path = get_template_directory_uri().'/images/dummy/';
$post_info = array();
$category_array = array('News','Directory');
insert_taxonomy_category($category_array);
function insert_taxonomy_category($category_array){
	global $wpdb;
	for($i=0;$i<count($category_array);$i++)	{
		$parent_catid = 0;
		if(is_array($category_array[$i]))		{
			$cat_name_arr = $category_array[$i];
			for($j=0;$j<count($cat_name_arr);$j++)			{
				$catname = $cat_name_arr[$j];
				if($j>1){
					$catid = $wpdb->get_var("select term_id from $wpdb->terms where name=\"$catname\"");
					if(!$catid)					{
					$last_catid = wp_insert_term( $catname, 'category' );
					}					
				}else				{
					$catid = $wpdb->get_var("select term_id from $wpdb->terms where name=\"$catname\"");
					if(!$catid)
					{
						$last_catid = wp_insert_term( $catname, 'category',$args = array('description'=>'This is description of your blog category page. It can be changed from Posts -> Post Categories in your WordPress backend. This is an excellent way to attract your users and explain what this category is all about and also explain what they can do in this category and how does it matter to them. It also serves SEO at some instant. Use the Blog description the way that it helps in SEO.'));
					}
				}
			}
		}else		{
			$catname = $category_array[$i];
			$catid = $wpdb->get_var("select term_id from $wpdb->terms where name=\"$catname\"");
			if(!$catid)
			{
				wp_insert_term( $catname, 'category',$args = array('description'=>'This is description of your blog category page. It can be changed from Posts -> Post Categories in your WordPress backend. This is an excellent way to attract your users and explain what this category is all about and also explain what they can do in this category and how does it matter to them. It also serves SEO at some instant. Use the Blog description the way that it helps in SEO.'));
			}
		}
	}
	for($i=0;$i<count($category_array);$i++)	{
		$parent_catid = 0;
		if(is_array($category_array[$i]))		{
			$cat_name_arr = $category_array[$i];
			for($j=0;$j<count($cat_name_arr);$j++){
				$catname = $cat_name_arr[$j];
				if($j>0)				{
					$parentcatname = $cat_name_arr[0];
					$parent_catid = $wpdb->get_var("select term_id from $wpdb->terms where name=\"$parentcatname\"");
					$last_catid = $wpdb->get_var("select term_id from $wpdb->terms where name=\"$catname\"");
					wp_update_term( $last_catid, 'category', $args = array('description'=>'This is description of your blog category page. It can be changed from Posts -> Post Categories in your WordPress backend. This is an excellent way to attract your users and explain what this category is all about and also explain what they can do in this category and how does it matter to them. It also serves SEO at some instant. Use the Blog description the way that it helps in SEO.','parent'=>$parent_catid) );
				}
			}
			
		}
	}
}

////post end///

//====================================================================================//
////post start 20///
$image_array = array();
$post_meta = array();
$image_array[] = "http://templatic.net/images/Directory/img20.jpg" ;
$post_meta = array(
				   "templ_seo_page_title" =>'Make money with Directory',
				   "templ_seo_page_kw" => '',
				   "tl_dummy_content"	=> '1',
				   "templ_seo_page_desc" => '',
				   "country_id" => 226,
				   "zones_id" => 3721,
				   "post_city_id"=>"1"
				);
$post_info[] = array(
					"post_title" =>	'Make money with Directory',
					"post_content" =>	'<strong>Directory </strong>is our brand new platform that encompasses a parent theme, various plugins and a wide selection of child themes. It is the most advanced theme we’ve ever created with literally hundreds of custom features. Read this page to learn more about the ways in which Directory can earn you some extra cash.

<h2>Make money by</h2>

<ul>
	<li><strong>Charging for submissions</strong></li>

Create price packages and insert them into your submission forms. Price packages can be created for every post type and they are category specific. Scroll down for more info. 

	<li><strong>Selling event tickets?</strong></li>

Create ticket products using WooCommerce? and connect them with events. Event detail page will show the buy button as well as the remaining ticket count. 

	<li><strong>Creating a webshop</strong></li>

Along with selling tickets, you can use WooCommerce to sell other stuff as well. Create your product categories, setup shipping, tax and you’re ready to go! 

	<li><strong>Selling ad space with <a href="http://templatic.com/directory-add-ons/templatic-admanager-wordpress-plugin">Ad Manager add-on</a></strong></li>

Use the back-end to control exactly where each banner shows. Set category specific banners or assign them to each post manually. Multiple locations available.

</ul>
<!--more-->



<h2>Price packages, explained</h2>
Content is key for any directory, and the one you create using this WP Directory theme won’t be any different. Price packages are designed to offer as many possibilities as possible both to you (the admin) and the visitors submitting a post. Here are three things you should know about price packages.

<ul>
	<li><strong>Two package types</strong></li>

Pay-per-post packages require the visitors to pay during each post submission. Pay-per-subscription packages allow you to set the timeframe in which posts can be submitted as well as a maximum number of listings. Subscription price packages work great in conjunction with recurring PayPal payments.

	<li><strong>Featured posts</strong></li>

One of the ways you can charge extra for a particular post submission is to set a featured price. Featured prices can be set for both the homepage and category page (different price for each). Featured posts show with a specific label and are stacked at the top of listing pages. Another way to charge extra is to set category prices.

	<li><strong>Custom field monetization?</strong></li>

This feature allows you to define exactly which custom fields show for each price package. You can also control the number of allowed images and stuff like character count for text fields. In practice, this will allow you to provide additional options (input fields) within the more expensive price packages.</ul>

<h2>More monetization features</h2>

<ul>
	<li><strong>Included coupon module</strong></li>

Create amount based or percentage based coupons and offer discounts on price packages. Set a start/end date for coupons and don’t worry about expiry dates. 

	<li><strong>Change the currency</strong></li>

Set the currency ISO code, the symbol and even the position (before/after amount). There are virtually no limitations here. 

	<li><strong>Payment gateways</strong></li>

Directory comes preinstalled with PayPal and PreBank transfer methods. There are dozens more available optional payment processors. 

	<li><strong>Manage transactions</strong></li>

All payments can be reviewed and approved/denied in the back-end. There are also several dashboard widgets? you can use to keep track of transactions. 

	<li><strong>Post upgrade option</strong></li>

Allow visitors to upgrade their submitted listing to a more expensive price package. They can do so from their front-end user dashboard.  

	<li><strong>Generate reports</strong></li>

Search through submitted transactions using multiple filtering fields such as date, package type, post type, etc. Export results to a .CSV file.
</ul>
',
					"post_meta" =>	$post_meta,
					"post_image" =>	$image_array,
					"post_category" =>	array('News','Directory'),
					"post_tags" =>	array('Tags','Sample Tags')

					);
////post end///
//====================================================================================//
////post start 21///
$image_array = array();
$post_meta = array();
$image_array[] = "http://templatic.net/images/Directory/img21.jpg" ;
$post_meta = array(
				   "templ_seo_page_title" =>'Manage a global website with Directory',
				   "templ_seo_page_kw" => '',
"tl_dummy_content"	=> '1',
				   "templ_seo_page_desc" => '',
				   "country_id" => 226,
				   "zones_id" => 3721,
				   "post_city_id"=>"1"
				);
$post_info[] = array(
					"post_title" =>	'Manage a global website with Directory',
					"post_content" =>	'Directory is our brand new platform that encompasses a parent theme, various plugins and a wide selection of child themes. It is the most advanced theme we’ve ever created with literally hundreds of custom features. Read this page to learn more about how you can turn your website into a global directory.

<h2>How do cities work in Directory?</h2>

Cities in this WordPress listing directory theme essentially provide another layer of filtering content. With regular themes you’re limited to organizing posts into categories; in Directory everything you create is also filtered by cities. In practice, it means that a person who “lands” in New York won’t see anything posted for London. Customize cities by adding a city message or setting a custom header and body background. Choose between using an image or a simple color for both the header and the body.

Use city logs to check out how many people visited each of your cities. The theme also logs each visitors IP address.

<h2>A map for everything</h2>

In Directory we’ve made it so that geo-location information can be associated with virtually any piece of content. This will allow you to showcase pretty much anything on a map. Maps themselves are plentiful. They are featured on the homepage, along with search, category and detail pages. There are 6 different map widgets you can use thought the site. With category pages you can choose between using an AJAX based map or a listing map widget. The map widget also enables pinpointing functionality for quickly focusing on a specific map marker.
<!--more-->


<h2>Go global with these location related features</h2>

<ul>
	<li><strong>City management</strong></li>

Add unlimited cities to your site and organize them into countries and states. We’ve pre-loaded hundreds of them to make the process faster. 

	<li><strong>Geo-tracker</strong></li>

A built-in IP tracking script will ensure every visitor is shown the correct city upon arrival. Of course, you can turn this off and show a default city instead.

	<li><strong>Homepage map</strong></li>

Directory is filled with maps, but this one is special. Integrated search and content-rich popups are just some of the features you’ll find in it.
 
	<li><strong>City selectors</strong></li>

While browsing the site visitors can use one of 4 selectors to change the city. Two work above the header, one is appended on the side and the last one is a widget.
</ul>


<h2>Google Map features</h2>

<ul>
	<li><strong>Marker clustering</strong></li>

Reduce map clutter with marker clustering, a feature available for all listing maps. An option for disabling it is also provided.

 
	<li><strong>Custom markers</strong></li>

The icon you add while creating a category will be used to represent that category within every map on the site.

 
	<li><strong>Auto width</strong></li>

Automatic map width will allow you insert map widgets in any widgetized area and not worry whether it will fit or not.

	<li><strong>Street View</strong></li>

Turn on street view by dragging the orange man at any time. Set street view as default view for the detail page map.

 
	<li><strong>Map shortcode</strong></li>

Use a map shortcode to generate a fully functional listing map. Works with all created post types.

 
	<li><strong>Change zoom behavior</strong></li>

The zoom factor on listing maps can be automatic (by fitting all available posts) or static (by setting it beforehand).

	<li><strong>Four types</strong></li>

For most of the maps you can choose the map type. These include road, terrain, satellite, hybrid.

 
	<li><strong>Detail map directions</strong></li>

Enter your address on the detail page and the map will generate directions to the location of the post you were viewing.

 
	<li><strong>Full page map</strong></li>

The homepage map has a button for loading it across the whole page. Use it when searching for something specific.
</ul>
',
					"post_meta" =>	$post_meta,
					"post_image" =>	$image_array,
					"post_category" =>	array('News','Directory'),
					"post_tags" =>	array('Tags','Sample Tags')

					);
////post end///
//====================================================================================//
////post start 22///
$image_array = array();
$post_meta = array();
$image_array[] = "http://templatic.net/images/Directory/img22.jpg" ;
$post_meta = array(
				   "templ_seo_page_title" =>'Create & manage content with Directory',
				   "templ_seo_page_kw" => '',
"tl_dummy_content"	=> '1',
				   "templ_seo_page_desc" => '',
				   "country_id" => 226,
				   "zones_id" => 3721,
				   "post_city_id"=>"1"
				);
$post_info[] = array(
					"post_title" =>	'Create & manage content with Directory',
					"post_content" =>	'Directory is our brand new platform that encompasses a parent theme, various plugins and a wide selection of child themes. It is the most advanced theme we’ve ever created with literally hundreds of custom features. Read this page to learn more about the stuff you can create and manage with Directory.
<h2>Allow visitors to register and post content</h2>

Every CMS allows you to modify and publish content, but not many give visitors the same opportunity. Our listings directory theme enables you to do just that; <strong>create submission pages visitors can use to post content on the site</strong>. Submitted content can be moderated from the back-end. But before they do this they’ll have to register. With Directory, you can edit register fields in order to capture unique information from visitors.<!--more-->

Go one step further and define a new post type. For instance, create a post type called “Properties” and showcase nearby houses and apartments for sale. Submission pages can be generated for every post type you create.
<h2>Custom fields – the glue that binds everything</h2>
All submission pages within the listings directory theme (you can create as many as you need) are constructed using custom fields. A bunch of these fields come pre installed when you activate Directory, but new ones can be added as well. There are 13 different field types for you to choose from, including radio and checkbox buttons, text fields, date pickers and more.
Along with facilitating submissions, custom fields also play a role in monetizing the site. This is achieved by connecting custom fields to price packages?. Read more about money-making features on the monetization page.
<h2>More management features</h2>
<ul>
<ul>
	<li>Bulk upload</li>
</ul>
</ul>
Already running a directory with lots of content? You can use the bulk upload option within Directory to transfer it. Bulk exporting and updating also available.
<ul>
<ul>
	<li>Multi-option ratings?</li>
</ul>
</ul>
Along with leaving reviews on your site, visitors will also have an ability to rate. Use the back-end to define different rating categories. Display the average on site.
<ul>
<ul>
	<li>Claim posts</li>
</ul>
</ul>
Claim post functionality will allow you to populate the site with content and then let the actual owners claim the listings. Enable it for all your post types.
<ul>
<ul>
	<li>Plethora of shortcodes</li>
</ul>
</ul>
Shortcodes in Directory can be divided in two categories: design and app. Use design shortcodes to make text more appealing. Use app shortcodes to generate forms, maps and more.',
					"post_meta" =>	$post_meta,
					"post_image" =>	$image_array,
					"post_category" =>	array('News','Directory'),
					"post_tags" =>	array('Tags','Sample Tags')

					);
////post end///
////post start 22///
$image_array = array();
$post_meta = array();
$image_array[] = "http://templatic.net/images/Directory/img20.jpg" ;
$post_meta = array(
				   "templ_seo_page_title" =>'How to speed up your Directory website?',
				   "templ_seo_page_kw" => '',
"tl_dummy_content"	=> '1',
				   "templ_seo_page_desc" => '',
				   "country_id" => 226,
				   "zones_id" => 3721,
				   "post_city_id"=>"1"
				);
$post_info[] = array(
					"post_title" =>	'How to speed up your Directory website?',
					"post_content" =>	'<em>Note: You must take backup of your site and database before following this step. Better be safe then sorry.</em>

Here are some tips on how to speed up your WordPress website:
<ul>
	<li><strong>Shared hosting v/s Dedicated hosting:</strong> If you are having more data or higher traffic on your Directory website then instead of shared hosting, we recommend using a  dedicated server. Directory is an application like theme and as soon as your site gets traction both content and traffic on your website will increase simultaneously so your website will need more server resources for better performance. If you are on a shared hosting your server resources will be shared with other websites on the same server so you will get limited resources for your website which will ultimately result in an under performing website. On the other hand if you go with a dedicated server all server resources will be available for your website and it will perform much better. </li>
	<li><strong>Remove plugins</strong>: Please visit plugins page in your WordPress admin and remove any and all unnecessary plugins which is not really contributing to your site.</li>
	<li><strong>Optimize DB:</strong> If your site is more than few months old, you should optimize your site database with plugins like <a href="http://wordpress.org/extend/plugins/rvg-optimize-database/">RGV optimize</a> and <a href="http://wordpress.org/extend/plugins/simple-optimizer/">Simple optimizer</a> or likes that will remove unnecessary junk from your site such as spam comments, post revisions etc. which will make your site database perform better</li>
	<li><strong>Limit post revisions: </strong>Most users dont need each version of post revisions. Here is a good article on <a href="http://bacsoftwareconsulting.com/blog/index.php/web-programming/how-to-delete-and-limit-revisions-in-wordpress/">how to disable or limit it</a>.</li>
	<li><strong>Spam Comments</strong><span class="Apple-converted-space"> </span>– If your spam comment receipts are in high numbers then all the spam comments should be deleted at the regular interval by just going to<span class="Apple-converted-space"> </span><strong>wp-admin</strong><span class="Apple-converted-space"> </span>&gt;<span class="Apple-converted-space"> </span><strong>Comments</strong><span class="Apple-converted-space"> </span>&gt;<strong>Spam</strong><span class="Apple-converted-space"> </span>&gt;<strong>Empty Spam (button)</strong><span class="Apple-converted-space"> </span>otherwise you may end up with a compromise in the site speed!</li>
	<li><strong>Lack of Image optimization</strong><span class="Apple-converted-space"> </span>– It is very important to upload the just perfect sized image &amp; that too with the specific formats like “jpg,png etc”.</li>
	<li><strong>W3 Cache plugin:</strong> This will really make your site faster. We highly recommend using <a href="http://wordpress.org/extend/plugins/w3-total-cache/">this plugin</a> which will cache your site and serve pages really faster.</li>
	<li><strong>Cloud flare:</strong> Use <a href="http://www.cloudflare.com/">cloud flare</a> and it will improvise your site performance further. Its free!</li>
	<li><strong>CDN:</strong> Most of the popular sites nowadays use CDN services such as <a href="http://www.maxcdn.com/">MaxCDN</a> or likes to deliver content from their site (we at templatic use it too)</li>
	<li><strong>Memory Limit</strong>: Many times increase in memory limit variable of the php.ini file also helps the user in loading the site faster.</li>
	<li><strong>Better WordPress Minify:</strong> It compress and combines CSS and JS scripts on site to improve the page load time. It can be downloaded from <a href="https://wordpress.org/plugins/bwp-minify/" target="_blank">here</a>. When this plugin is active, go to its settings &gt; Manage enqueued Files. Select three files mentioned below:
- google-clustering
- location_script
- google-maps-apiscript Select them and choose action "Say at position". Save the Changes.</li>
	<li><strong>Google page speed:</strong> If you really wish to go in detail, <a href="https://developers.google.com/speed/pagespeed/insights">Google Page Speed</a> is a very good site analysis tool that will tell you exactly how you can improve your site speed.</li>
	<li>For the error: <strong>The following cacheable resources have a short freshness lifetime. Specify an expiration at least one week in the future for the following resources </strong>(80997)<strong>
</strong>This is the code you need to add in your .htaccess file:&nbsp;
<div>## EXPIRES CACHING ##</div>
<div>&lt;IfModule mod_expires.c&gt;</div>
<div>ExpiresActive On</div>
<div>ExpiresByType image/jpg "access plus 1 year"</div>
<div>ExpiresByType image/jpeg "access plus 1 year"</div>
<div>ExpiresByType image/gif "access plus 1 year"</div>
<div>ExpiresByType image/png "access plus 1 year"</div>
<div>ExpiresByType text/css "access plus 1 month"</div>
<div>ExpiresByType application/pdf "access plus 1 month"</div>
<div>ExpiresByType text/x-javascript "access plus 1 month"</div>
<div>ExpiresByType application/x-shockwave-flash "access plus 1 month"</div>
<div>ExpiresByType image/x-icon "access plus 1 year"</div>
<div>ExpiresDefault "access plus 2 days"</div>
<div>&lt;/IfModule&gt;</div>
## EXPIRES CACHING ##</li>
</ul>
The above mentioned reasons are quite in brief just to make you aware with the actual problems, so to have a detailed description &amp; guideline on each of them, please have a look at the below given articles.
<ol>
	<li><a href="http://www.wpexplorer.com/how-to-speed-up-wordpress/" target="_blank">http://www.eugenoprea.com/increase-wordpress-site-speed/</a></li>
	<li><a href="http://www.socialmediaexaminer.com/improve-the-speed-of-your-wordpress-site/" target="_blank">www.socialmediaexaminer.com/improve-the-speed-of-your-wordpress-site/</a></li>
	<li><a href="http://www.wpexplorer.com/how-to-speed-up-wordpress/" target="_blank">http://www.wpexplorer.com/how-to-speed-up-wordpress/</a></li>
</ol>
Hope this helps.',
					"post_meta" =>	$post_meta,
					"post_image" =>	$image_array,
					"post_category" =>	array('News','Directory'),
					"post_tags" =>	array('Tags','Sample Tags')

					);
////post end///
//====================================================================================//
insert_posts($post_info);
function insert_posts($post_info)
{
	global $wpdb,$current_user;
	for($i=0;$i<count($post_info);$i++)
	{
		$post_title = $post_info[$i]['post_title'];
		$post_count = $wpdb->get_var("SELECT count(ID) FROM $wpdb->posts where post_title like \"$post_title\" and post_type='post' and post_status in ('publish','draft')");
		if(!$post_count)
		{
			$post_info_arr = array();
			$catids_arr = array();
			$my_post = array();
			$post_info_arr = $post_info[$i];
			if($post_info_arr['post_category'])
			{
				for($c=0;$c<count($post_info_arr['post_category']);$c++)
				{
					$catids_arr[] = get_cat_ID($post_info_arr['post_category'][$c]);
				}
			}else
			{
				$catids_arr[] = 1;
			}
			$my_post['post_title'] = $post_info_arr['post_title'];
			$my_post['post_content'] = $post_info_arr['post_content'];
			if($post_info_arr['post_author'])
			{
				$my_post['post_author'] = $post_info_arr['post_author'];
			}else
			{
				$my_post['post_author'] = 1;
			}
			$my_post['post_status'] = 'publish';
			$my_post['post_category'] = $catids_arr;
			$my_post['tags_input'] = $post_info_arr['post_tags'];
			$last_postid = wp_insert_post( $my_post );
			$post_meta = $post_info_arr['post_meta'];
			$data = array(
				'comment_post_ID' => $last_postid,
				'comment_author' => 'admin',
				'comment_author_email' => get_option('admin_email'),
				'comment_author_url' => 'http://',
				'comment_content' => $post_info_arr['post_title'].'its amazing.',
				'comment_type' => '',
				'comment_parent' => 0,
				'user_id' => $current_user->ID,
				'comment_author_IP' => '127.0.0.1',
				'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
				'comment_date' => $time,
				'comment_approved' => 1,
			);

			wp_insert_comment($data);
			if($post_meta)
			{
				foreach($post_meta as $mkey=>$mval)
				{
					update_post_meta($last_postid, $mkey, $mval);
				}
			}
			
			$post_image = $post_info_arr['post_image'];
			tmpl_directory_upload_image($last_postid,$post_image);
			
		}
	}
}
//=============================PAGES ENTRY START=======================================================//
$post_info = array();
$pages_array = array(array('Archives','Contact Us','Home','Blog'));
$page_info_arr = array();
$page_meta = array('_wp_page_template'=>'page-templates/archives.php', 'tl_dummy_content' => 1);
$page_info_arr[] = array('post_title'=>'Archives',
						'post_content'=>'This is Archives page template. Just select it from page templates section and you&rsquo;re good to go.',
						'post_meta'=>$page_meta);
$page_meta = array( 'tl_dummy_content' => 1,'_wp_page_template'=>'page-templates/contact-us.php');
$page_info_arr[] = array('post_title'=>'Contact Us',
						'post_content'=>'<p>Contact Us page is listed at Page section in to backend. Different widgets areas for this page are: Contact Page – Main Content and Contact Page Sidebar<p><p>Address on Google map can be changed from the Contact Page – Main Content -> T – Google Map Location widet. Similarly, T – Contact Us widget is used to show the form. Captcha can be enabled.</p><p>Mail on the Contact Us page is sent to the mail ID provided into WordPress General Settings -> Email field.</p>',
						'post_meta'=>$page_meta);
$page_meta = array( 'tl_dummy_content' => 1);
$page_meta = array('_wp_page_template'=>'page-templates/front-page.php','Layout'=>'default'); 
$page_info_arr[] = array('post_title'=>'Home',
						'post_content'=>'',
						'comment_status'=>'closed',
						'post_meta'=> $page_meta);

$page_meta = array('_wp_page_template'=>'page-templates/full-page-map.php','Layout'=>'default'); 
$page_info_arr[] = array('post_title'=>'All In One Map',
						'post_content'=>"[tevolution_listings_map post_type='listing'   zoom_level='5'  latitude='40.46800769694572'  longitude='-101.42762075195316' clustering=1][/tevolution_listings_map]",
						'comment_status'=>'closed',
						'post_meta'=> $page_meta);

$page_meta = array('tl_dummy_content'=>'1','Layout'=>'default'); 
$page_info_arr[] = array('post_title'=>'People',
						'post_content'=>"[tevolution_author_list role='subscriber' users_per_page='8'][/tevolution_author_list]",
						'comment_status'=>'closed',
						'post_meta'=> $page_meta);
$page_meta = array('tl_dummy_content'=>'1','Layout'=>'default','page_for_posts'=>1); 
$page_info_arr[] = array('post_title'=>'Blog',
						'post_content'=>"",
						'comment_status'=>'closed',
						'post_meta'=> $page_meta);
$page_meta = array('tl_dummy_content'=>'1','Layout'=>'default'); 
$page_info_arr[] = array('post_title'=>'How to setup your site',
						 'post_name' => 'how-to-setup-your-site',
						'post_content'=> 'We highly recommend that you go through this <a href="http://templatic.com/docs/directory-theme-guide/">documentation guide</a> for the Directory theme. Please also refer to the links on this <a href="http://templatic.com/docs/directory-guides/">page </a>for the detailed documentation of the whole Directory platform.
<h3><a href="http://templatic.com/docs/directory-theme-guide/#basic-setup">Basic setup of your Directory website</a></h3>
Please visit <a href="http://templatic.com/docs/directory-theme-guide/#basic-setup">this section</a> of the guide for more information on how to do some basic settings like configuring permalinks, changing your site logo, etc.
<h3><a href="http://templatic.com/docs/directory-theme-guide/#translating">How to translate Directory?</a></h3>
Directory can be translated using Poedit software. The files you should be using for translating are located inside the /wp-content/themes/Directory/languages folder. Use the en_US.po file to translate the front-end strings and admin-en_US.po to translate the back-end strings.

Those are “global” PO files and contain strings from each of the 4 Directory components. If you want, you can also translate each individual component by opening the “languages” folder inside each plugin (and the theme). For detailed instructions on translating the PO file open the following article.

Quick tip: For displaying Directory in multiple languages you will need to purchase and install the WPML plugin.
<h3><a href="http://templatic.com/docs/how-to-speed-up-your-directory-website/">How to speed up your Directory website</a></h3>
Directory is a massive application like theme so it will need more resources compared to some other simple portfolio or business WordPress themes. You may find it working a little slow if you have a lot of content and you are on a shared server. However, we have listed down some methods using which you can improvise performance of your Directory website. Please go through <a href="http://templatic.com/docs/how-to-speed-up-your-directory-website/"><strong>this article</strong></a> for more details on this.
<h3><a href="http://templatic.com/docs/customizing-directory/">How to customize Directory?</a></h3>
If you are a developer and want to customize Directory we recommend to read <a href="http://templatic.com/docs/customizing-directory/"><strong>this article</strong></a> once, we are sure it will help
<h2>Frequently Asked Questions</h2>
<h3><a href="http://templatic.com/docs/directory-theme-guide/#megamenu">How to create a demo site like megamenu?</a></h3>
<h3><a href="http://templatic.com/docs/directory-theme-guide/#social-login">How to enable social login through Facebook, Twitter, etc?</a></h3>
<h3><a href="http://templatic.com/docs/directory-theme-guide/#seo-settings">How to configure SEO settings?</a></h3>
<h3><a href="http://templatic.com/docs/directory-theme-guide/#clear-cache">Why aren\'t changes to my custom fields showing?</a></h3>
<strong>Note</strong>: If you run into any problems while using the theme do not hesitate to ask for help on our <a href="http://templatic.com/forums/viewforum.php?f=140">support forum</a>.',
						'comment_status'=>'closed',
						'post_meta'=> $page_meta);
						
$page_meta = array('tl_dummy_content'=>'1','Layout'=>'default'); 
$page_info_arr[] = array('post_title'=>'Extend',
						 'post_name' => 'extend',
						'post_content'=> 'You can extend your Directory website by using a wide range of add-ons that we offer, see the list of add-ons:
<h3><a href="http://templatic.com/directory-add-ons/wp-events-directory">Events</a></h3>
Turn your Directory into an events portal where event organizers can submit event listings. Just like the regular listings, you will be able to charge for event submissions and monetize your site even further.

<h3><a href="http://templatic.com/directory-add-ons/tevolution-fields-monetization">Fields Monetization</a></h3>
Control which listing packages get what fields with this amazing add-on. As admin, you setup packages that can have exactly the fields you wish to offer on each of them. As well as being able to limit the number of categories a listing can be submitted to, you can also limit the number of images that can be uploaded per listing. A great tool which can encourage people to go for a higher package that has more fields so they can add more content and details on their listings.

<h3><a href="http://templatic.com/directory-add-ons/star-rating-plugin-multirating">Multi Rating</a></h3>
Allow visitors to leave category-specific multiple ratings with their reviews on listings. As admin, you can specify more than one rating option on listings. This means a person can for example rate a listing based on quality, friendliness of staff, hygiene and service. Customize it to add whatever ratings you wish to let users rate listing by.

<h3><a href="http://templatic.com/directory-add-ons/tevolution-plugin-admin-dashboard">Admin Dashboard</a></h3>
Makes your life as admin more easier with extremely useful dashboard widgets. Get more information on your site\'s performance.

<h3><a href="http://templatic.com/directory-add-ons/templatic-admanager-wordpress-plugin">Ad Manager</a></h3>
A powerful banner management system which lets you display ads on your pages, posts and listings. Banners can be city, category or listing-specific with many banner location available. Ad Manager also offers banner rotation so you can basically offer the same ad space more than once and make an even bigger profit.

<h3><a href="http://templatic.com/directory-add-ons/duplicatepostalert-listings-theme-plugin">Duplicate Post Alert</a></h3>
Provides a verification on submitted listing titles and refuses new listing titles if the same title already exists. A useful tool if you wish to keep each listing on your directory unique with no repeated titles.

<h3><a href="http://templatic.com/directory-add-ons/real-estate">Directory Real Estate</a></h3>
Turn your Directory theme into a fully fledged real estate classifieds portal. Allow agents and property owners to submit property listings on free or paid listing plans. As well as search by price, number of bedrooms and bathrooms, the add-on offers many amazing functions.

<h3><a href="http://templatic.com/directory-add-ons/listing-vouchers">Listing Vouchers</a></h3>
Allow listing owners to upload a voucher or coupon to their listings. This offers your users an extra option to benefit more from their listing on your directory.

<h3><a href="http://templatic.com/directory-add-ons/tabs-manager">Tabs Manager</a></h3>
Create new custom fields and have them appear as extra tabs above listing descriptions. This offers you as admin more control over how you wish to organize the submission form and listing detail pages.

<h3><a href="http://templatic.com/directory-add-ons/header-fields">Header Fields</a></h3>
As well as the default header fields such as Phone, Website and Time, create and assign new custom fields to appear in the same area. This is a great tool if you wish to provide your visitors a clearer format so they can quickly spot each listing\'s short details.

<h3><a href="http://templatic.com/directory-add-ons/listing-badges">Listing Badges</a></h3>
You as admin can place custom color labels with a unique text on listings to highlight them.

<h3><a href="http://templatic.com/directory-add-ons/proximity-search">Proximity Search</a></h3>
Allow users on your site to quickly find listings by ZIP/Post codes. The add-on works in any country so it\'s an ideal tool which gives your listings directory that extra edge over the competition.

<h3><a href="http://templatic.com/directory-add-ons/wysiwyg-submission">WYSIWYG Submission</a></h3>
Use this add-on to enable a totally unique way of submitting listings and speed up the submission process and earning power of your site. This add-on will let listing submitters see an almost live preview of their content as they submit it.

<h3><a href="http://templatic.com/directory-add-ons/category-icons">Category Icons</a></h3>
Show custom icons next to each category on your listings directory to give your site its unique identity. This add-on offers a great way to give each of your categories their own styling and helps users visually navigate around your site.


<h3><a href="http://templatic.com/directory-add-ons/global-location">Global Location</a></h3>
Show all listings on your homepage without your users having to first select a city. This add-on lets you as admin create a new location which will become the first one your visitors will land on when they visit your site.


<h3><a href="http://templatic.com/directory-add-ons/map-customizer">Map Customizer</a></h3>
Customize your directory\'s Google map color scheme to match your site\'s design. A useful tool to give your listings directory its own unique identity and make it stand out from the rest.',
						'comment_status'=>'closed',
						'post_meta'=> $page_meta);


set_page_info_autorun($pages_array,$page_info_arr);
//Sidebar widget settings: start
$sidebars_widgets = get_option('sidebars_widgets');  //collect widget informations
$sidebars_widgets = array();
//==============================HEADER WIDGET AREA SETTINGS START=========================//
//Search widget settings start
$directory_search_location = array();
$directory_search_location[1] = array(
					"title"				=>	'',
					"post_type"			=>	array('listing'),
					"miles_search"		=>	0,
					"radius_measure"	=>	'kilometer',
					);
$directory_search_location['_multiwidget'] = '1';
update_option('widget_directory_search_location', $directory_search_location);
$directory_search_location = get_option('widget_directory_search_location');
krsort($directory_search_location);
foreach($directory_search_location as $key1=>$val1)
{
	$directory_search_location_key1 = $key1;
	if(is_int($directory_search_location_key1))
	{
		break;
	}
}
//Search widget settings end
$sidebars_widgets["header"] = array("directory_search_location-{$directory_search_location_key1}");
//==============================HEADER WIDGET AREA SETTINGS END=========================//
//==============================HOME PAGE BANNER WIDGET AREA SETTING START=========================//
//Home page Goole map
$supreme_banner_map = array();
$supreme_banner_map[1] = array(
					"hight"	=>	'500',
					);
$supreme_banner_map['_multiwidget'] = '1';
update_option('widget_googlemap_homepage',$supreme_banner_map);
$supreme_banner_map = get_option('widget_googlemap_homepage');
krsort($supreme_banner_map);
foreach($supreme_banner_map as $key=>$val1)
{
	$supreme_banner_map_key1 = $key;
	if(is_int($supreme_banner_map_key1)){
		break;
	}
}
//Home page banner slider settings end
$sidebars_widgets["home-page-banner"] = array("googlemap_homepage-{$supreme_banner_map_key1}");
//==============================HOME PAGE BANNER WIDGET AREA SETTING END=========================//
//==============================FOOTER WIDGET AREA SETTING START=========================//
//about theme widget settings start
$templatic_text = array();
$templatic_text[1] = array(
				"title"			=>	__("About Directory",THEME_DOMAIN),
				"text"		=>	'Directory is the most feature rich directory <a "title="WordPress Directory Theme" alt="WordPress Directory Theme" href="http://templatic.com">WordPress theme</a> available today. It provides all the tools necessary to run a modern directory website and lots of <a href="http://templatic.com/plugins/directory-add-ons/">add-ons</a> made for it. Full listings support, Built-in monetization, unlimited custom fields and categories, custom post types plus Google Maps integration are just some of the features available in this advanced directory theme.<br/>',
				);
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key = $key;
	if(is_int($templatic_text_key)){
		break;
	}
}
//Navigation menu widget settings start
//Social Media widget settings start
$social_media = array();
$social_media[1] = array(
				"title"						=>	'Connect With Us',
				"social_description"		=>	'',
				"social_link"				=>	array('http://facebook.com/templatic','http://twitter.com/templatic','http://www.youtube.com/user/templatic','http://templatic.com/','http://templatic.com/','http://templatic.com/'),
				"social_icon"				=>	array('','','','','',''),
				"social_text"				=>	array('<i class="fa fa-facebook"></i>Find us on Facebook','<i class="fa fa-twitter"></i>Follow us on Twitter','<i class="fa fa-youtube"></i>Find us on Youtube','<i class="fa fa-linkedin"></i>Connect with us on LinkedIn','<i class="fa fa-google-plus"></i>Find us on Google+','<i class="fa fa-pinterest"></i>Find us on Pinterest')
				);
$social_media['_multiwidget'] = '1';
update_option('widget_social_media',$social_media);
$social_media = get_option('widget_social_media');
krsort($social_media);
foreach($social_media as $key=>$val)
{
	$social_media_key1 = $key;
	if(is_int($social_media_key1)){
		break;
	}
}
//Social Media widget settings start

//Newsletter subscribe widget settings start
$supreme_subscriber_widget = array();
$supreme_subscriber_widget[1] = array(
				"title"					=>	__('Get Latest Updates',THEME_DOMAIN),
				"text"					=>	__(' Subscribe to get our latest news',THEME_DOMAIN),
				"newsletter_provider"	=>	'feedburner',
				"feedburner_id"			=>	'',
				"mailchimp_api_key"		=>	'',
				"mailchimp_list_id"		=>	'',
				"feedblitz_list_id"		=>	'',
				"aweber_list_name"		=>	'',
				);						
$supreme_subscriber_widget['_multiwidget'] = '1';
update_option('widget_supreme_subscriber_widget',$supreme_subscriber_widget);
$supreme_subscriber_widget = get_option('widget_supreme_subscriber_widget');
krsort($supreme_subscriber_widget);
foreach($supreme_subscriber_widget as $key=>$val)
{
	$supreme_subscriber_widget_key = $key;
	if(is_int($supreme_subscriber_widget_key))
	{
		break;
	}
}
//Newsletter subscribe widget settings start

$sidebars_widgets["footer"] = array("templatic_text-{$templatic_text_key}","social_media-{$social_media_key1}","supreme_subscriber_widget-{$supreme_subscriber_widget_key}");
//==============================FOOTER WIDGET AREA SETTING END=========================//
//==============================HOME PAGE CONTENT WIDGET AREA SETTING START=========================//
//T → All Category List Home Page widget settings start
$widget_directory_featured_category_list = array();
$widget_directory_featured_category_list[2] = array(
				"title"					=>	__('Browse Listings By Categories',THEME_DOMAIN),
				"post_type"				=>	'listing',
				"category_level"		=>	2,
				"number_of_category"	=>	5,
				);						
$widget_directory_featured_category_list['_multiwidget'] = '1';
update_option('widget_directory_featured_category_list',$widget_directory_featured_category_list);
$widget_directory_featured_category_list = get_option('widget_directory_featured_category_list');
krsort($widget_directory_featured_category_list);
foreach($widget_directory_featured_category_list as $key=>$val)
{
	$widget_directory_featured_category_list_key = $key;
	if(is_int($widget_directory_featured_category_list_key))
	{
		break;
	}
}
//T → All Category List Home Page widget settings end

//Advertisement widget settings start
$templatic_text[2] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com"><img src="'.get_template_directory_uri().'/images/adv_728x90.jpg" style="padding-left:60px;"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key = $key;
	if(is_int($templatic_text_key))
	{
		break;
	}
}
//advertisement widget settings end

//T → Featured Listings For Home Page widget settings start
$directory_featured_homepage_listing = array();
$directory_featured_homepage_listing[1] = array(
				"title"					=>	__("Places Around You",THEME_DOMAIN),
				"text"					=>	__("View All",THEME_DOMAIN),
				"link"					=>	'#',
				"number"				=>	6,
				"view"					=>	'grid',
				"post_type"				=>	'listing',
				"category"				=>	'',
				);						
$directory_featured_homepage_listing['_multiwidget'] = '1';
update_option('widget_directory_featured_homepage_listing',$directory_featured_homepage_listing);
$directory_featured_homepage_listing = get_option('widget_directory_featured_homepage_listing');
krsort($directory_featured_homepage_listing);
foreach($directory_featured_homepage_listing as $key=>$val)
{
	$directory_featured_homepage_listing_key1 = $key;
	if(is_int($directory_featured_homepage_listing_key1))
	{
		break;
	}
}
//T → Featured Listings For Home Page widget settings end

//Advertisement widget settings start
$templatic_text[3] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com"><img src="'.get_template_directory_uri().'/images/adv_728x90.jpg" style="padding-left:60px;"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key2 = $key;
	if(is_int($templatic_text_key2))
	{
		break;
	}
}
//advertisement widget settings end

//T → Featured Listings For Home Page widget settings start
$directory_featured_homepage_listing[2] = array(
				"title"					=>	__("Hotels Around You",THEME_DOMAIN),
				"text"					=>	__("View All",THEME_DOMAIN),
				"link"					=>	'#',
				"number"				=>	3,
				"view"					=>	'list',
				"post_type"				=>	'listing',
				"content_limit"			=>	150,
				"category"				=>	'',
				);						
$directory_featured_homepage_listing['_multiwidget'] = '1';
update_option('widget_directory_featured_homepage_listing',$directory_featured_homepage_listing);
$directory_featured_homepage_listing = get_option('widget_directory_featured_homepage_listing');
krsort($directory_featured_homepage_listing);
foreach($directory_featured_homepage_listing as $key=>$val)
{
	$directory_featured_homepage_listing_key2 = $key;
	if(is_int($directory_featured_homepage_listing_key2))
	{
		break;
	}
}
//T → Featured Listings For Home Page widget settings end

//T → Post Listing widget settings start
$supreme_recent_post = array();
$idObj = get_term_by('slug', 'blog', 'category'); 
$id = $idObj->term_id;
$supreme_recent_post[1] = array(
				"title"					=>	__("Latest News",THEME_DOMAIN),
				"post_type"				=>	'post',
				"post_type_taxonomy"	=>	$id,
				"post_number"			=>	3,
				"orderby"				=>	'date',
				"order"					=>	'DESC',
				"show_gravatar"			=>	'',
				"gravatar_size"			=>	'',
				"show_image"			=>	1,
				"image_size"			=>	'thumbnail',
				"show_title"			=>	1,
				"show_content"			=>	'content-limit',
				"content_limit"			=>	450,
				"more_text"				=>	__('[Read More...]',THEME_DOMAIN),
				);						
$supreme_recent_post['_multiwidget'] = '1';
update_option('widget_supreme_recent_post',$supreme_recent_post);
$supreme_recent_post = get_option('widget_supreme_recent_post');
krsort($supreme_recent_post);
foreach($supreme_recent_post as $key=>$val)
{
	$supreme_recent_post_key1 = $key;
	if(is_int($supreme_recent_post_key1))
	{
		break;
	}
}
//T → Post Listing widget settings end

$sidebars_widgets["home-page-content"] = array("directory_featured_category_list-{$widget_directory_featured_category_list_key}","templatic_text-{$templatic_text_key}","directory_featured_homepage_listing-{$directory_featured_homepage_listing_key1}","templatic_text-{$templatic_text_key2}","directory_featured_homepage_listing-{$directory_featured_homepage_listing_key2}","supreme_recent_post-{$supreme_recent_post_key1}");
//==============================HOME PAGE CONTENT WIDGET AREA SETTING END=========================//
//==============================FRONT PAGE SIDEBAR WIDGET AREA SETTING START=========================//
//Advertisement widget settings start
$templatic_text[4] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com"><img align="middle" src="'.get_template_directory_uri().'/images/adv_300x250.jpg"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key3 = $key;
	if(is_int($templatic_text_key3))
	{
		break;
	}
}
//advertisement widget settings end

//Recent Review widget settings start
$widget_comment = array();
$widget_comment[1] = array(
				"title"		=>	'Recent Reviews',
				"post_type"	=>	'listing',
				"count"		=>	5,
				);						
$widget_comment['_multiwidget'] = '1';
update_option('widget_widget_comment',$widget_comment);
$widget_comment = get_option('widget_widget_comment');
krsort($widget_comment);
foreach($widget_comment as $key=>$val)
{
	$widget_comment_key = $key;
	if(is_int($widget_comment_key))
	{
		break;
	}
}
//Recent Review widget settings end
//about theme widget settings start
$templatic_text[5] = array(
				"title"		=>	__("Featured Video",THEME_DOMAIN),
				"text"		=>	'<iframe width="300" height="300" src="//www.youtube.com/embed/c_cZ7AW25yA" frameborder="0" allowfullscreen></iframe>',
				);
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key2 = $key;
	if(is_int($templatic_text_key2))
	{
		break;
	}
}
//about theme widget settings end

//T → Popular Posts Widget settings start
$templatic_popular_post_technews = array();
$templatic_popular_post_technews[1] = array(
					"title"					=>	__('Popular Listings',THEME_DOMAIN),
					"post_type"				=>	'listing',
					"number"				=>	5,
					"slide"					=>	5,
					"popular_per"			=>	'comments',
					"pagination_position"	=>	0,
					);
$templatic_popular_post_technews['_multiwidget'] = '1';
update_option('widget_templatic_popular_post_technews',$templatic_popular_post_technews);
$templatic_popular_post_technews = get_option('widget_templatic_popular_post_technews');
krsort($templatic_popular_post_technews);
foreach($templatic_popular_post_technews as $key1=>$val1)
{
	$templatic_popular_post_technews_key1 = $key1;
	if(is_int($templatic_popular_post_technews_key1))
	{
		break;
	}
}
//T → Popular Posts Widget settings end
//Advertisement widget settings start
$templatic_text[6] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com/docs/directory-theme-guide/"><img align="middle" src="'.get_template_directory_uri().'/images/Theme-guide-250x250.jpg"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key4 = $key;
	if(is_int($templatic_text_key4))
	{
		break;
	}
}
//advertisement widget settings end
$sidebars_widgets["front-page-sidebar"] = array("templatic_text-{$templatic_text_key4}", "widget_comment-{$widget_comment_key}", "templatic_text-{$templatic_text_key2}", "templatic_popular_post_technews-{$templatic_popular_post_technews_key1}","templatic_text-{$templatic_text_key3}");
//==============================FRONT PAGE SIDEBAR WIDGET AREA SETTING END=========================//
//==============================LISTING SIDEBAR WIDGET AREA SETTING START=========================//

//Search widget settings start
$directory_search_location[2] = array(
					"title"				=>	__('Search Nearby Listings',THEME_DOMAIN),
					"post_type"			=>	array('listing'),
					"miles_search"		=>	0,
					"radius_measure"	=>	'miles',
					);						
$directory_search_location['_multiwidget'] = '1';
update_option('widget_directory_search_location', $directory_search_location);
$directory_search_location = get_option('widget_directory_search_location');
krsort($directory_search_location);
foreach($directory_search_location as $key1=>$val1)
{
	$directory_search_location_key2 = $key1;
	if(is_int($directory_search_location_key2))
	{
		break;
	}
}
//Search widget settings end

//T → Search Near By Miles Range widget settings start
$directory_mile_range_widget = array();
$directory_mile_range_widget[1] = array(
					"title"				=>	__("Filter Listings By Miles",THEME_DOMAIN),
					"max_range"			=>	500,
					"post_type"			=>	'listing',
					);						
$directory_mile_range_widget['_multiwidget'] = '1';
update_option('widget_directory_mile_range_widget',$directory_mile_range_widget);
$directory_mile_range_widget = get_option('widget_directory_mile_range_widget');
krsort($directory_mile_range_widget);
foreach($directory_mile_range_widget as $key1=>$val1)
{
	$directory_mile_range_widget_key = $key1;
	if(is_int($directory_mile_range_widget_key))
	{
		break;
	}
}
//T → Search Near By Miles Range widget settings end 

//Browse by category widget settings start
$templatic_browse_by_categories = array();
$templatic_browse_by_categories[1] = array(
					"title"				=>	__('Browse Listings By Category',THEME_DOMAIN),
					"post_type"			=>	'listing',
					"categories_count"	=>	1,
					);						
$templatic_browse_by_categories['_multiwidget'] = '1';
update_option('widget_templatic_browse_by_categories',$templatic_browse_by_categories);
$templatic_browse_by_categories = get_option('widget_templatic_browse_by_categories');
krsort($templatic_browse_by_categories);
foreach($templatic_browse_by_categories as $key1=>$val1)
{
	$templatic_browse_by_categories_key1 = $key1;
	if(is_int($templatic_browse_by_categories_key1))
	{
		break;
	}
}
//Browse by category widget settings end
//Advertisement widget settings start
$templatic_text[7] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com/docs/directory-theme-guide/"><img align="middle" src="'.get_template_directory_uri().'/images/Theme-guide-250x250.jpg"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key4 = $key;
	if(is_int($templatic_text_key4))
	{
		break;
	}
}
//advertisement widget settings end
$sidebars_widgets["listingcategory_listing_sidebar"] = array("directory_search_location-{$directory_search_location_key2}","directory_mile_range_widget-{$directory_mile_range_widget_key}", "templatic_browse_by_categories-{$templatic_browse_by_categories_key1}","templatic_text-{$templatic_text_key4}");
//==============================LISTING SIDEBAR WIDGET AREA SETTING END=========================//
//==============================LISTING DETAIL SIDEBAR WIDGET AREA SETTING END=========================//
//T → In the neighborhood widget settings start
$directory_neighborhood = array();
$directory_neighborhood[1] = array(
					"title"					=>	__('Nearest Listings',THEME_DOMAIN),
					"post_type"				=>	'listing',
					"post_number"			=>	4,
					"content_limit"			=>	34,
					"show_list"				=>	0,
					"closer_factor"			=>	0,
					"radius"				=>	5000,
					"radius_measure"		=>	'miles',
					);						
$directory_neighborhood['_multiwidget'] = '1';
update_option('widget_directory_neighborhood',$directory_neighborhood);
$directory_neighborhood = get_option('widget_directory_neighborhood');
krsort($directory_neighborhood);
foreach($directory_neighborhood as $key1=>$val1)
{
	$directory_neighborhood_key1 = $key1;
	if(is_int($directory_neighborhood_key1))
	{
		break;
	}
}
//T → In the neighborhood widget settings end

//Search widget settings start
$directory_search_location[3] = array(
					"title"				=>	__('Search Nearby Listings',THEME_DOMAIN),
					"post_type"			=>	array('listing'),
					"miles_search"		=>	0,
					"radius_measure"	=>	'miles',
					);						
$directory_search_location['_multiwidget'] = '1';
update_option('widget_directory_search_location', $directory_search_location);
$directory_search_location = get_option('widget_directory_search_location');
krsort($directory_search_location);
foreach($directory_search_location as $key1=>$val1)
{
	$directory_search_location_key3 = $key1;
	if(is_int($directory_search_location_key3))
	{
		break;
	}
}
//Search widget settings end

//Browse by category widget settings start
$templatic_browse_by_categories[2] = array(
					"title"				=>	__('Browse Listings By Categories',THEME_DOMAIN),
					"post_type"			=>	'listing',
					"categories_count"	=>	1,
					);						
$templatic_browse_by_categories['_multiwidget'] = '1';
update_option('widget_templatic_browse_by_categories',$templatic_browse_by_categories);
$templatic_browse_by_categories = get_option('widget_templatic_browse_by_categories');
krsort($templatic_browse_by_categories);
foreach($templatic_browse_by_categories as $key1=>$val1)
{
	$templatic_browse_by_categories_key2 = $key1;
	if(is_int($templatic_browse_by_categories_key2))
	{
		break;
	}
}
//Browse by category widget settings end

//Advertisement widget settings start
$templatic_text[8] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com"><img align="middle" src="'.get_template_directory_uri().'/images/adv_300x250.jpg"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key4 = $key;
	if(is_int($templatic_text_key4))
	{
		break;
	}
}
//advertisement widget settings end
//Advertisement widget settings start
$templatic_text[9] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com/docs/directory-theme-guide/"><img align="middle" src="'.get_template_directory_uri().'/images/Theme-guide-250x250.jpg"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key5 = $key;
	if(is_int($templatic_text_key5))
	{
		break;
	}
}
//advertisement widget settings end
$sidebars_widgets["listing_detail_sidebar"] = array("directory_neighborhood-{$directory_neighborhood_key1}", "templatic_text-{$templatic_text_key4}", "directory_search_location-{$directory_search_location_key3}", "templatic_browse_by_categories-{$templatic_browse_by_categories_key2}", "templatic_text-{$templatic_text_key5}");
//==============================LISTING DETAIL SIDEBAR WIDGET AREA SETTING END=========================//

//POST DETAIL PAGE SIDEBAR WIDGET START
//=============================================
//about theme widget settings start
$templatic_text[10] = array(
				"title"		=>	__("About the author",THEME_DOMAIN),
				"text"		=>	"<img src='http://templatic.com/demos/dirchild/video/wp-content/uploads/2013/09/20130903093522_profile7.png' height=90 width=90 style='float:left; margin:0 10px 10px 0'>
<h4><strong>Allen Rechard</strong></h4>
Use the 'Text' widget in the 'Post Detail Page Sidebar' to make any information you wish to display in this sidebar area.",
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key3 = $key;
	if(is_int($templatic_text_key3))
	{
		break;
	}
}
//about theme widget settings end

//Social Media widget settings start
$social_media[2] = array(
				"title"						=>	'Connect With Us',
				"social_description"		=>	'',
				"social_link"				=>	array('http://facebook.com/templatic','http://twitter.com/templatic','http://www.youtube.com/user/templatic','http://templatic.com/','http://templatic.com/','http://templatic.com/'),
				"social_icon"				=>	array('','','','','',''),
				"social_text"				=>	array('<i class="fa fa-facebook"></i>Find us on Facebook','<i class="fa fa-twitter"></i>Follow us on Twitter','<i class="fa fa-youtube"></i>Find us on Youtube','<i class="fa fa-linkedin"></i>Connect with us on LinkedIn','<i class="fa fa-google-plus"></i>Find us on Google+','<i class="fa fa-pinterest"></i>Find us on Pinterest')
				);
$social_media['_multiwidget'] = '1';
update_option('widget_social_media',$social_media);
$social_media = get_option('widget_social_media');
krsort($social_media);
foreach($social_media as $key=>$val)
{
	$social_media_key2 = $key;
	if(is_int($social_media_key2)){
		break;
	}
}
//Social Media widget settings start
//Newsletter subscribe widget settings start
$supreme_subscriber_widget[2] = array(
				"title"					=>	__('Subscribe To Newsletter',THEME_DOMAIN),
				"text"					=>	__('Subscribe to get latest news from site',THEME_DOMAIN),
				"newsletter_provider"	=>	'feedburner',
				"feedburner_id"			=>	'templatic',
				"mailchimp_api_key"		=>	'',
				"mailchimp_list_id"		=>	'',
				"feedblitz_list_id"		=>	'',
				"aweber_list_name"		=>	'',
				);						
$supreme_subscriber_widget['_multiwidget'] = '1';
update_option('widget_supreme_subscriber_widget',$supreme_subscriber_widget);
$supreme_subscriber_widget = get_option('widget_supreme_subscriber_widget');
krsort($supreme_subscriber_widget);
foreach($supreme_subscriber_widget as $key=>$val)
{
	$supreme_subscriber_widget_key = $key;
	if(is_int($supreme_subscriber_widget_key))
	{
		break;
	}
}
//Newsletter subscribe widget settings start
//Browse by category widget settings start
$templatic_browse_by_categories[3] = array(
					"title"				=>	__('Categories',THEME_DOMAIN),
					"post_type"			=>	'post',
					"categories_count"	=>	1,
					);						
$templatic_browse_by_categories['_multiwidget'] = '1';
update_option('widget_templatic_browse_by_categories',$templatic_browse_by_categories);
$templatic_browse_by_categories = get_option('widget_templatic_browse_by_categories');
krsort($templatic_browse_by_categories);
foreach($templatic_browse_by_categories as $key1=>$val1)
{
	$templatic_browse_by_categories_key3 = $key1;
	if(is_int($templatic_browse_by_categories_key3))
	{
		break;
	}
}
//Advertisement widget settings start
$templatic_text[11] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com/docs/directory-theme-guide/"><img align="middle" src="'.get_template_directory_uri().'/images/Theme-guide-250x250.jpg"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key5 = $key;
	if(is_int($templatic_text_key5))
	{
		break;
	}
}
//advertisement widget settings end
$sidebars_widgets["post-detail-sidebar"] = array("templatic_text-{$templatic_text_key5}","social_media-{$social_media_key2}","supreme_subscriber_widget-{$supreme_subscriber_widget_key}","templatic_browse_by_categories-{$templatic_browse_by_categories_key3}","templatic_text-{$templatic_text_key3}");
//POST DETAIL PAGE SIDEBAR WIDGET END
//=============================================
//POST LISTING PAGE SIDEBAR WIDGET START
//=============================================
//about theme widget settings start
$templatic_text[12] = array(
				"title"		=>	__("About the author",THEME_DOMAIN),
				"text"		=>	"<img src='http://templatic.com/demos/dirchild/video/wp-content/uploads/2013/09/20130903093522_profile7.png' height=90 width=90 style='float:left; margin:0 10px 10px 0'>
<h4><strong>Allen Rechard</strong></h4>
Use the 'Text' widget in the 'Post Category Page Sidebar' to make any information you wish to display in this sidebar area.",
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key4 = $key;
	if(is_int($templatic_text_key4))
	{
		break;
	}
}
//about theme widget settings end

//Social Media widget settings start
$social_media[3] = array(
				"title"						=>	'Connect With Us',
				"social_description"		=>	'',
				"social_link"				=>	array('http://facebook.com/templatic','http://twitter.com/templatic','http://www.youtube.com/user/templatic','http://templatic.com/','http://templatic.com/','http://templatic.com/'),
				"social_icon"				=>	array('','','','','',''),
				"social_text"				=>	array('<i class="fa fa-facebook"></i>Find us on Facebook','<i class="fa fa-twitter"></i>Follow us on Twitter','<i class="fa fa-youtube"></i>Find us on Youtube','<i class="fa fa-linkedin"></i>Connect with us on LinkedIn','<i class="fa fa-google-plus"></i>Find us on Google+','<i class="fa fa-pinterest"></i>Find us on Pinterest')
				);
$social_media['_multiwidget'] = '1';
update_option('widget_social_media',$social_media);
$social_media = get_option('widget_social_media');
krsort($social_media);
foreach($social_media as $key=>$val)
{
	$social_media_key3 = $key;
	if(is_int($social_media_key3)){
		break;
	}
}
//Social Media widget settings start
//Newsletter subscribe widget settings start
$supreme_subscriber_widget[3] = array(
				"title"					=>	__('Subscribe To Newsletter',THEME_DOMAIN),
				"text"					=>	__('Subscribe to get latest news from site',THEME_DOMAIN),
				"newsletter_provider"	=>	'feedburner',
				"feedburner_id"			=>	'templatic',
				"mailchimp_api_key"		=>	'',
				"mailchimp_list_id"		=>	'',
				"feedblitz_list_id"		=>	'',
				"aweber_list_name"		=>	'',
				);						
$supreme_subscriber_widget['_multiwidget'] = '1';
update_option('widget_supreme_subscriber_widget',$supreme_subscriber_widget);
$supreme_subscriber_widget = get_option('widget_supreme_subscriber_widget');
krsort($supreme_subscriber_widget);
foreach($supreme_subscriber_widget as $key=>$val)
{
	$supreme_subscriber_widget_key = $key;
	if(is_int($supreme_subscriber_widget_key))
	{
		break;
	}
}
//Newsletter subscribe widget settings start
//Browse by category widget settings start
$templatic_browse_by_categories[4] = array(
					"title"				=>	__('Categories',THEME_DOMAIN),
					"post_type"			=>	'post',
					"categories_count"	=>	1,
					);						
$templatic_browse_by_categories['_multiwidget'] = '1';
update_option('widget_templatic_browse_by_categories',$templatic_browse_by_categories);
$templatic_browse_by_categories = get_option('widget_templatic_browse_by_categories');
krsort($templatic_browse_by_categories);
foreach($templatic_browse_by_categories as $key1=>$val1)
{
	$templatic_browse_by_categories_key4 = $key1;
	if(is_int($templatic_browse_by_categories_key4))
	{
		break;
	}
}
//Browse by category widget settings end
//Advertisement widget settings start
$templatic_text[13] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com/docs/directory-theme-guide/"><img align="middle" src="'.get_template_directory_uri().'/images/Theme-guide-250x250.jpg"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key5 = $key;
	if(is_int($templatic_text_key5))
	{
		break;
	}
}
//advertisement widget settings end
$sidebars_widgets["post-listing-sidebar"] = array("templatic_text-{$templatic_text_key5}","social_media-{$social_media_key3}","supreme_subscriber_widget-{$supreme_subscriber_widget_key}","templatic_text-{$templatic_text_key4}","templatic_browse_by_categories-{$templatic_browse_by_categories_key4}");
//POST LISTING PAGE SIDEBAR WIDGET END
//=============================================
//PRIMARY SIDEBAR WIDGET START
//=============================================

//About Us widget settings start
$templatic_text[14] = array(
					"title"				=>	__('Become An Agent',THEME_DOMAIN),
					"text"			=>	__('You can become an agent by submitting a listing on our site. <a href="http://templatic.com/">List your business</a> on our site and get access to thousands of visitors we get everyday. You will be able to reach out to <strong>more people and that means more business</strong>.',THEME_DOMAIN),
					);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key1=>$val1)
{
	$templatic_text_key3 = $key1;
	if(is_int($templatic_text_key3))
	{
		break;
	}
}
//About Us widget settings end

//Login widget settings start
$widget_login = array();
$widget_login[1] = array(
					"title"				=>	__('Dashboard',THEME_DOMAIN),
					"hierarchical"		=>	1,
					);						
$widget_login['_multiwidget'] = '1';
update_option('widget_widget_login',$widget_login);
$widget_login = get_option('widget_widget_login');
krsort($widget_login);
foreach($widget_login as $key1=>$val1)
{
	$widget_login_key1 = $key1;
	if(is_int($widget_login_key1))
	{
		break;
	}
}
//Login widget settings end
//Advertisement widget settings start
$templatic_text[15] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com/docs/directory-theme-guide/"><img align="middle" src="'.get_template_directory_uri().'/images/Theme-guide-250x250.jpg"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key5 = $key;
	if(is_int($templatic_text_key5))
	{
		break;
	}
}
//advertisement widget settings end
$sidebars_widgets["primary-sidebar"] = array("templatic_text-{$templatic_text_key5}", "widget_login-{$widget_login_key1}","templatic_text-{$templatic_text_key3}");

//PRIMARY SIDEBAR WIDGET END
//=============================================
//CONTACT PAGE WIDGET AREA START
//=========================================
//Google map widget settings start
$templatic_google_map = array();
$templatic_google_map[1] = array(
					"title"			=>	'Find us on map',
					"address"		=>	'230 Vine Street And locations throughout Old City, Philadelphia, PA 19106',
					"map_height"	=>	400,
					);						
$templatic_google_map['_multiwidget'] = '1';
update_option('widget_templatic_google_map',$templatic_google_map);
$templatic_google_map = get_option('widget_templatic_google_map');
krsort($templatic_google_map);
foreach($templatic_google_map as $key1=>$val1)
{
	$templatic_google_map_key = $key1;
	if(is_int($templatic_google_map_key))
	{
		break;
	}
}

$supreme_contact_widget = array();
$supreme_contact_widget[1] = array(
					"title"			=>	'Contact Us',
					"address"		=>	'230 Vine Street And locations throughout Old City, Philadelphia, PA 19106',
					"map_height"	=>	400,
					);						
$supreme_contact_widget['_multiwidget'] = '1';
update_option('widget_supreme_contact_widget',$supreme_contact_widget);
$supreme_contact_widget = get_option('widget_supreme_contact_widget');
krsort($supreme_contact_widget);
foreach($supreme_contact_widget as $key1=>$val1)
{
	$supreme_contact_widget_key = $key1;
	if(is_int($supreme_contact_widget_key))
	{
		break;
	}
}
//Google map widget settings end

$sidebars_widgets["contact_page_widget"] = array("templatic_google_map-{$templatic_google_map_key}","supreme_contact_widget-{$supreme_contact_widget_key}");
//Facebook fan widget settings start
$supreme_facebook = array();
$supreme_facebook[1] = array(
					"facebook_page_url"		=>	'https://www.facebook.com/templatic',
					"width"					=>	300,
					"show_faces"			=>	1,
					"show_stream"			=>	1,
					"show_header"			=>	1,
					);						
$supreme_facebook['_multiwidget'] = '1';
update_option('widget_supreme_facebook',$supreme_facebook);
$supreme_facebook = get_option('widget_supreme_facebook');
krsort($supreme_facebook);
foreach($supreme_facebook as $key1=>$val1)
{
	$supreme_facebook_key1 = $key1;
	if(is_int($supreme_facebook_key1))
	{
		break;
	}
}
//Facebook fan widget settings end
//Advertisement widget settings start
$templatic_text[16] = array(
				"title"	=>	'',
				"text"	=>	'<a href="http://templatic.com/docs/directory-theme-guide/"><img align="middle" src="'.get_template_directory_uri().'/images/Theme-guide-250x250.jpg"></a>',
				);						
$templatic_text['_multiwidget'] = '1';
update_option('widget_templatic_text',$templatic_text);
$templatic_text = get_option('widget_templatic_text');
krsort($templatic_text);
foreach($templatic_text as $key=>$val)
{
	$templatic_text_key5 = $key;
	if(is_int($templatic_text_key5))
	{
		break;
	}
}
//advertisement widget settings end
$sidebars_widgets["contact_page_sidebar"] = array("templatic_text-{$templatic_text_key5}","supreme_facebook-{$supreme_facebook_key1}");
//CONTACT PAGE WIDGET AREA END

/*BEING Below header Listing category widget*/
/*Catgeory map widget */
$category_map = array();
$category_map[1] = array("height"=>	'500');						
$category_map['_multiwidget'] = '1';
update_option('widget_category_googlemap',$category_map);
$category_map = get_option('widget_category_googlemap');
krsort($category_map);
foreach($category_map as $key1=>$val1)
{
	$category_map_key1 = $key1;
	if(is_int($category_map_key1)){
		break;
	}
}
$sidebars_widgets["after_directory_header"] = array("category_googlemap-{$category_map_key1}");
/*END Below header Listing category widget*/
//=========================================
update_option('sidebars_widgets',$sidebars_widgets);  //save widget informations 

/*
 * Function Name: Tmpl_builder_upload_image
 * upload property image from outside server
 */
function tmpl_directory_upload_image($post_id,$post_image){
	if($post_image)
	{
		for($m=0;$m<count($post_image);$m++){
			
	        $title = basename($post_image[$m]);
			
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');
			require_once(ABSPATH . "wp-admin" . '/includes/media.php');
	        // next, download the URL of the image
	        $upload = media_sideload_image($post_image[$m], $post_id, $title);
		}
	}

}
?>