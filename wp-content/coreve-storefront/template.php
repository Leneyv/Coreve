<?php
if(!defined('ABSPATH'))exit;
$route=coreve_sf_route();$map=coreve_sf_map();$product_id=get_queried_object_id();
if(in_array($route,['cart','checkout'],true)){nocache_headers();}
if(coreve_sf_preview()){nocache_headers();header('X-Robots-Tag: noindex, nofollow');}
$relative=$route==='product'?'products/'.$map[$product_id].'/index.html':($route==='collection'?'collection/index.html':'index.html');
$html=file_get_contents(__DIR__.'/pages/'.$relative);
$html=preg_replace('~^.*?<body>~s','',$html);$html=preg_replace('~</body>.*$~s','',$html);
$html=preg_replace('~<div class="preview">.*?</div>~s','',$html);
$html=preg_replace('~<script src="/staging/app.js" defer></script>~','',$html);
$html=preg_replace('~<div class="overlay" id="bag-overlay".*?(?=<div class="overlay" id="size-overlay")~s','',$html);
$html=preg_replace_callback('~<button class="icon-button bag-toggle".*?</button>~s',function($m){$inner=preg_replace('~^<button[^>]*>|</button>$~','',$m[0]);$inner=preg_replace('~<span class="count">.*?</span>~s','<span class="count">'.(WC()->cart?WC()->cart->get_cart_contents_count():0).'</span>',$inner);return '<a class="icon-button bag-toggle" href="'.esc_url(wc_get_checkout_url()).'" aria-label="Continue to checkout">'.$inner.'</a>';},$html);
$html=str_replace(['/staging/assets/','Design preview · No orders or payments','Preview only · No checkout','Our design direction →','Design direction'],[content_url('/coreve-storefront/assets/'),'Secure checkout · Made in India','Secure checkout','About Coreve →','About Coreve'],$html);
foreach($map as $id=>$slug)$html=str_replace('/staging/products/'.$slug.'/',get_permalink($id),$html);
$html=str_replace(['/staging/collection/','/staging/brand/','/staging/'],[wc_get_page_permalink('shop'),home_url('/about-us/'),home_url('/')],$html);
if($route==='product'){
 global $product;$product=wc_get_product($product_id);
 ob_start();echo '<div class="live-purchase">';woocommerce_template_single_add_to_cart();echo '</div>';$purchase=ob_get_clean();
 $html=preg_replace_callback('~<div class="size-heading">.*?<p class="form-status"[^>]*>.*?</p>~s',function()use($purchase){return $purchase;},$html,1);
 $html=preg_replace('~<p class="pdp-price">.*?</p>~s','<div class="pdp-price">'.$product->get_price_html().'</div>',$html,1);
}
if(in_array($route,['cart','checkout'],true)){
 $page_id=wc_get_page_id($route);
 $content=apply_filters('the_content',get_post_field('post_content',$page_id));
 $title=$route==='cart'?'Your shopping bag':'Secure checkout';
 $html=preg_replace('~<main id="main">.*?</main>~s','<main id="main" class="commerce-page woocommerce"><h1>'.$title.'</h1>'.$content.'</main>',$html,1);
}
ob_start();if(function_exists('wc_print_notices'))wc_print_notices();$notices=ob_get_clean();
$html=str_replace('<main id="main">','<div class="live-notices">'.$notices.'</div><main id="main">',$html);
// The approved preview's assets are reused; production keeps canonical product URLs.
if(coreve_sf_preview()){
 $html=preg_replace_callback('~(href|action)="(https://coreve\.in/[^"#]*)(#[^"]*)?"~',function($m){return $m[1].'="'.esc_url(add_query_arg('coreve_preview',sanitize_text_field(wp_unslash($_GET['coreve_preview'])),$m[2])).($m[3]??'').'"';},$html);
}
?><!doctype html><html <?php language_attributes();?>><head>
<script async src="https://www.googletagmanager.com/gtm.js?id=GTM-TM3ZZV8C"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
</script>
<meta charset="<?php bloginfo('charset');?>"><meta name="viewport" content="width=device-width,initial-scale=1"><?php wp_head();?></head><body <?php body_class('coreve-storefront-live');?>><?php wp_body_open();echo $html;wp_footer();?></body></html>
