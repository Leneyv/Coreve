<?php
/** Coreve approved storefront integration. Loaded from an MU plugin. */
if (!defined('ABSPATH')) exit;
function coreve_sf_map(){return [117=>'cipher-mocha-mousse',118=>'ventra-blue-granite',119=>'zivana-winterberry',120=>'elara-tendril',121=>'nyro-eclipse'];}
function coreve_sf_preview(){return (string)get_option('coreve_storefront_preview_token','')!=='' && isset($_GET['coreve_preview']) && hash_equals((string)get_option('coreve_storefront_preview_token',''),sanitize_text_field(wp_unslash($_GET['coreve_preview'])));}
function coreve_sf_enabled(){return (bool)get_option('coreve_storefront_2026_enabled',false)||coreve_sf_preview();}
function coreve_sf_route(){
 if(!coreve_sf_enabled()||is_admin()||wp_doing_ajax()||!function_exists('WC'))return false;
 if(is_front_page())return 'home';
 if(is_shop())return 'collection';
 if(is_product()&&isset(coreve_sf_map()[get_queried_object_id()]))return 'product';
 if(is_cart())return 'cart';
 if(is_checkout())return 'checkout';
 return false;
}
add_filter('template_include',function($template){return coreve_sf_route()?__DIR__.'/template.php':$template;},99);
add_action('wp_enqueue_scripts',function(){
 if(!coreve_sf_route())return;
 foreach(['coreve-local-fonts','coreve-style'] as $h)wp_dequeue_style($h);
 foreach(['coreve-store-api-cart','coreve-cart-drawer','coreve-size-guide-modal','coreve-theme'] as $h)wp_dequeue_script($h);
 wp_enqueue_style('coreve-storefront-2026',content_url('/coreve-storefront/style.css'),[],filemtime(__DIR__.'/style.css'));
 wp_enqueue_script('coreve-storefront-2026',content_url('/coreve-storefront/app.js'),[],filemtime(__DIR__.'/app.js'),true);
},1000);
// Buy now uses WooCommerce's native variation validation and checkout session.
add_filter('woocommerce_add_to_cart_redirect',function($url){return coreve_sf_enabled()?wc_get_checkout_url():$url;},100);
add_filter('woocommerce_product_single_add_to_cart_text',function($text){return coreve_sf_enabled()?'Buy now':$text;},100);
add_filter('woocommerce_product_add_to_cart_text',function($text){return coreve_sf_enabled()?'Buy now':$text;},100);
add_filter('woocommerce_billing_fields',function($fields){if(coreve_sf_enabled()&&isset($fields['billing_phone']))$fields['billing_phone']['required']=true;return $fields;},100);
// Preserve Razorpay's native signed order payload and payment verification.
add_action('wp_enqueue_scripts',function(){
 if(!coreve_sf_enabled()||!is_checkout_pay_page())return;
 $scripts=wp_scripts();
 if(isset($scripts->registered['razorpay_wc_script'])){
  $scripts->registered['razorpay_wc_script']->src=content_url('/coreve-storefront/payment.js');
  $scripts->registered['razorpay_wc_script']->ver=filemtime(__DIR__.'/payment.js');
 }
},1100);
add_action('template_redirect',function(){
 if(!coreve_sf_enabled()||!is_404())return;
 $path=trim(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH),'/');
 foreach(coreve_sf_map() as $id=>$slug){if($path==='products/'.$slug){wp_safe_redirect(get_permalink($id),301);exit;}}
 if($path==='collection'){wp_safe_redirect(wc_get_page_permalink('shop'),301);exit;}
});
add_filter('redirect_canonical',function($url){return coreve_sf_preview()?false:$url;});
// Commerce dependencies must initialize in their registered order.
add_filter('script_loader_tag',function($tag){if(coreve_sf_route())$tag=preg_replace('/\\sdefer(?:=("[^"]*"|\x27[^\x27]*\x27))?/','',$tag);return $tag;},999);
// Keep the excluded custom edition out of the empty-cart merchandising block.
add_filter('render_block_woocommerce/product-new',function($content){return coreve_sf_route()==='cart'?'<p><a class="button" href="'.esc_url(wc_get_page_permalink('shop')).'">Explore the collection</a></p>':$content;},20);
add_filter('script_loader_src',function($src,$handle){return coreve_sf_enabled()&&$handle==='razorpay_wc_script'?add_query_arg('ver',filemtime(__DIR__.'/payment.js'),content_url('/coreve-storefront/payment.js')):$src;},100,2);
add_filter('body_class',function($classes){if(coreve_sf_enabled()&&is_checkout_pay_page())$classes[]='coreve-payment-handoff';return $classes;});
add_action('wp_enqueue_scripts',function(){if(coreve_sf_enabled()&&is_checkout_pay_page())wp_add_inline_style('coreve-storefront-2026','.coreve-payment-handoff .order_details{display:none}.coreve-payment-handoff .commerce-page{min-height:55vh}');},1200);
// Keep an accessible size label while simplifying the visible purchase form.
add_filter('woocommerce_dropdown_variation_attribute_options_args',function($args){if(coreve_sf_enabled()&&($args['attribute']??'')==='pa_size')$args['show_option_none']='Choose the Size';return $args;},100);
add_action('wp_enqueue_scripts',function(){if(coreve_sf_route()==='product')wp_add_inline_style('coreve-storefront-2026','.live-purchase .variations th.label{position:absolute!important;width:1px!important;height:1px!important;padding:0!important;margin:-1px!important;overflow:hidden!important;clip:rect(0,0,0,0)!important;white-space:nowrap!important;border:0!important}');},1200);
