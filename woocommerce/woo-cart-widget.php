<?php
/* woo coomerce shopping cart widget for secondary menu.*/
if(!class_exists('templatic_woo_shopping_cart_info')){
	class templatic_woo_shopping_cart_info extends WP_Widget {
		var $woo_widget_cssclass;
		var $woo_widget_description;
		var $woo_widget_idbase;
		var $woo_widget_name;
		function templatic_woo_shopping_cart_info() {
			/* Widget variable settings. */
			$this->woo_widget_cssclass 		= 'woocommerce widget_shopping_cart';
			$this->woo_widget_description 	= __( "Display Cart Informations with automatic cart update. Best to use it in \"Header right\" sidebar", ADMINDOMAIN );
			$this->woo_widget_idbase 		= 'woocommerce_widget_cart';
			$this->woo_widget_name 			= __( 'T &rarr; WooCommerce Shopping Cart', ADMINDOMAIN );

			$widget_ops = array('classname' => 'widget WooCommerce shopping cart info', 'description' => __('Display Cart Informations with automatic cart update. Best to use it in "Header right area" widget are',ADMINDOMAIN));
			$this->WP_Widget('templatic_woo_shopping_cart_info',$this->woo_widget_name, $widget_ops);
		}
		function widget($args, $instance) {

			global $woocommerce;
			extract($args, EXTR_SKIP);
			?>
<?php if($before_title=='' || $after_title=='')
			{
				$before_title=='<h3><span>';
				$after_title=='</span></h3>';
			}
			?>
<div class="widget templatic_shooping  widget_shopping_cart">
  <div  id="woo_shoppingcart_box" class="cart_items shoppingcart_box shoppingcart_box_bg" onclick="show_hide_cart_items();" style="cursor:pointer;">
    <?php if ( empty( $title ) ) { echo $before_title . "Shopping Cart" . $after_title; }else{echo $before_title . $title . $after_title; }; ?>
  </div>
  <div id="woo_shopping_cart" style="display:none">
    <div class="widget_shopping_cart_content">
      <?php 
		echo '<ul class="cart_list product_list_widget ';
		if ($hide_if_empty) echo 'hide_cart_widget_if_empty';
		echo '">';
		if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product = $cart_item['data'];
				if ( $_product->exists() && $cart_item['quantity'] > 0 ) {
					echo '<li><a href="'.get_permalink($cart_item['product_id']).'">';
					echo $_product->get_image(). '</a><a href="'.get_permalink($cart_item['product_id']).'">';
					echo apply_filters('woocommerce_cart_widget_product_title', $_product->get_title(), $_product)."</a>";
					$product_price = get_option('woocommerce_display_cart_prices_excluding_tax') == 'yes' || $woocommerce->customer->is_vat_exempt() ? $_product->get_price_excluding_tax() : $_product->get_price();
					$product_price = apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $cart_item, $cart_item_key ); 		
					echo '<span class="quantity">' . $cart_item['quantity'] . ' &times; ' . $product_price . '</span></li>';
				}
			}
		}
		echo '</ul>';
		echo '<div class="woo_checkout_btn"><p class=""><strong>' . __('Subtotal', THEME_DOMAIN) . ':</strong> '. $woocommerce->cart->get_cart_subtotal() . '</p>';
		do_action( 'woocommerce_widget_shopping_cart_before_buttons' );
		echo '<div class="buttons"><a href="' . $woocommerce->cart->get_cart_url() . '" class="button">' . __('Checkout &rarr;', THEME_DOMAIN) . '</a></div></div>';
	?>
    </div>
	</div>
	<script type="text/javascript">
					function show_hide_cart_items(){
						var dis = document.getElementById('woo_shopping_cart').style.display;
						if(dis == 'none'){
							 jQuery("#woo_shopping_cart").animate({
							  height:'toggle'
							});
						}else{
							jQuery("#woo_shopping_cart").animate({
							  height:'toggle'
							});
						}
					}
				</script>
	</div>
<?php 
		}
		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			return $instance;
		}
		function form($instance) {
			/* widgetform in backend */
			$instance = wp_parse_args( (array) $instance, array( '' => ' ' ) );
		}
	}
	register_widget('templatic_woo_shopping_cart_info'); 
}?>