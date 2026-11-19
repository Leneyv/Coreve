<?php
/**
 * Plugin Name: Coreve Google Tag Manager
 * Description: Embeds Google Tag Manager / Google Analytics 4 (GTM-TM3ZZV8C) in <head> and <body> across all pages.
 * Version: 1.0.1
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_head', function () {
    ?>
<!-- Google Tag Manager / Google Analytics (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtm.js?id=GTM-TM3ZZV8C"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'GTM-TM3ZZV8C');
</script>
<!-- End Google Tag Manager -->
<?php
}, 1);

add_action('wp_body_open', function () {
    ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TM3ZZV8C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
}, 1);
