<?php
/**
 * Plugin Name: Coreve Google Tag Manager
 * Description: Embeds Google Tag Manager noscript fallback in <body> across all pages. The primary Google tag (gtag.js) is installed immediately after <head> in template.php and header.php.
 * Version: 1.0.2
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('wp_body_open', function () {
    ?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TM3ZZV8C"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php
}, 1);
