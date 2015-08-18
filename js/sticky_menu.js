/* sticky navigation for mobile view*/
jQuery.noConflict();jQuery(function(){var e=jQuery(".off-canvas-wrap");var t=jQuery(".off-canvas-wrap").css("display");var n=e.offset().top;var r=jQuery("#branding");var i=false;if(t=="block"){var s=jQuery(window);s.scroll(function(){var e=s.scrollTop();var t=e>n})}})
/* sticky navigation for desk top*/
// Stick the #nav to the top of the window
jQuery(document).ready(function(){
	jQuery(window).scroll(function(){		
		var e=jQuery(window).scrollTop();
		if(jQuery(window).width()>1200){
			if(e>220){ 
				var site_title='';
				var site_description_html='';		
				
				if(jQuery('#site-title').length>0 && jQuery('#site-title').html()!=''){
					site_title=jQuery('#site-title').html();
				}
				if(jQuery('#site-description').length>0 && jQuery('#site-description').html()!=''){
					var site_description=jQuery('#site-description').html();
					site_description_html='<div id="site-description">'+site_description+'</div>';
				}			
				
				jQuery('#nav-secondary #branding1,.nav-secondary #branding1').remove();
				jQuery('#menu-secondary,#menu_secondary_mega_menu').before('<div id="branding1"><h1>'+site_title+'</h1>'+site_description_html+'</div>');
				
				jQuery('#container #nav-secondary,#container .nav-secondary').addClass("sticky_main");			
				jQuery(".sticky_main").fadeIn(); 
			}else{ 
				jQuery('#nav-secondary #branding1,.nav-secondary #branding1').remove();
				jQuery('#nav-secondary,.nav-secondary').css('display','');
				jQuery('#container #nav-secondary,#container .nav-secondary').removeClass("sticky_main");
				jQuery(".sticky_main").fadeOut();			
			}
		}
	})
})