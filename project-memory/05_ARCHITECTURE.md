# 05 - Architecture

## 1. System Overview & Data Flow Diagram

```
+-----------------------------------------------------------------------------------+
|                                  VISITOR BROWSER                                  |
+-----------------------------------------------------------------------------------+
          |                                                               |
  [Static Assets: .webp, .css, .js, .mp4]                    [Dynamic Routes: /, /product/*, /checkout]
          |                                                               |
          v                                                               v
+-----------------------------------------------------------------------------------+
|                             NGINX 1.24.0 (REVERSE PROXY)                          |
| - FastCGI Page Cache (coreve_cache, 60m TTL, bypasses on cart/checkout/cookies)  |
| - Gzip Compression (Level 6) & Security Headers (HSTS, nosniff, SAMEORIGIN)        |
+-----------------------------------------------------------------------------------+
          |
          | (UNIX Socket: /run/php/php8.3-fpm-coreve.sock)
          v
+-----------------------------------------------------------------------------------+
|                               PHP 8.3-FPM (WORDPRESS CORE)                        |
|                                                                                   |
|  +-----------------------------------------------------------------------------+  |
|  | mu-plugins/coreve-storefront-loader.php                                     |  |
|  | -> Loads wp-content/coreve-storefront/storefront.php                        |  |
|  |    -> Checks option `coreve_storefront_2026_enabled`                        |  |
|  |    -> Routes home, collection, product, cart, checkout                      |  |
|  |    -> Injects template.php via `template_include` filter                    |  |
|  |    -> Enqueues storefront style.css and app.js                              |  |
|  |    -> Injects WooCommerce native single_add_to_cart variation forms         |  |
|  |    -> Replaces Razorpay script with secure payment.js modal bridge          |  |
|  +-----------------------------------------------------------------------------+  |
|                                                                                   |
|  +-----------------------------------------------------------------------------+  |
|  | mu-plugins/coreve-smtp.php                                                   |  |
|  | -> Hooks into `phpmailer_init`                                              |  |
|  | -> Reads COREVE_SMTP_* constants from wp-config.php                         |  |
|  | -> Authenticates via SSL Port 465 to smtp.zoho.in                           |  |
|  +-----------------------------------------------------------------------------+  |
|                                                                                   |
|  +-----------------------------------------------------------------------------+  |
|  | themes/coreve/functions.php                                                 |  |
|  | -> Atomic Order Sequencing: coreve_assign_order_number() (KLE99817+)        |  |
|  | -> Order Notification Dispatcher: coreve_send_order_notification_email()    |  |
|  | -> Schema.org Structured Data graph generator                               |  |
|  +-----------------------------------------------------------------------------+  |
+-----------------------------------------------------------------------------------+
          |                                                               |
          v                                                               v
+-----------------------------------------+   +-------------------------------------+
|         MARIADB / MYSQL DATABASE        |   |       EXTERNAL INTEGRATIONS         |
| - wp_options (coreve_next_order_seq)    |   | - Zoho Mail SMTP (smtp.zoho.in:465) |
| - wp_wc_orders & wp_wc_orders_meta      |   | - Razorpay Payments API             |
|   (WooCommerce HPOS storage)            |   | - Postfix local loopback MTA        |
+-----------------------------------------+   +-------------------------------------+
```

---

## 2. Directory Structure & Key Files

```
/var/www/coreve.in/ (Server Webroot) & /Users/lenee/coreve/ (Local Git Workspace)
│
├── wp-config.php                     # WP configuration & encrypted/safe SMTP constants
├── nginx-coreve.in.conf              # Nginx server block configuration backup
├── robots.txt                        # Search engine crawler permissions & sitemap reference
├── llms.txt & llms-full.txt          # Generative Engine Optimization (GEO) context for AI agents
│
├── wp-content/
│   ├── mu-plugins/
│   │   ├── coreve-smtp.php           # Authenticated Zoho SMTP PHPMailer connector
│   │   └── coreve-storefront-loader.php # Bootstrap for the high-performance storefront module
│   │
│   ├── coreve-storefront/            # Active Modern Storefront Module
│   │   ├── storefront.php            # Core router, hook overrides, and Buy Now logic
│   │   ├── template.php              # Template renderer merging WP headers with markup
│   │   ├── style.css                 # Storefront styling (typography, grid, dark scenes)
│   │   ├── app.js                    # UI interactions (modal, progress rail, image zoom)
│   │   ├── payment.js                # Razorpay checkout bridge and modal controller
│   │   ├── pages/                    # Canonical HTML layouts for home, shop, and PDPs
│   │   │   ├── index.html            # Home page template
│   │   │   ├── collection/           # Collection overview template
│   │   │   └── products/             # Product templates (5 models)
│   │   └── assets/                   # Storefront WebP images, MP4 hero videos, SVGs
│   │
│   ├── themes/
│   │   └── coreve/                   # Active Theme
│   │       ├── functions.php         # Business logic: KLE sequencing, emails, SEO schema
│   │       ├── single-product.php    # Fallback product template with legal manufacturer info
│   │       ├── front-page.php        # Fallback classic theme homepage
│   │       ├── header.php            # Theme header
│   │       ├── footer.php            # Theme footer
│   │       └── style.css             # Theme stylesheet
│   │
│   └── plugins/
│       ├── woocommerce/              # E-commerce core engine (HPOS enabled)
│       └── woo-razorpay/             # Official Razorpay payment gateway plugin
│
└── project-memory/                   # Comprehensive handover & QC documentation
    ├── 00_README_FOR_AI.md
    ├── 01_PROJECT_OVERVIEW.md
    ├── 02_COMMAND_LOG.md
    ├── 03_CHANGELOG.md
    ├── 04_FEATURES_STATUS.md
    ├── 05_ARCHITECTURE.md
    ├── 06_DATABASE_AND_APIS.md
    ├── 07_ENV_AND_SETUP.md
    ├── 08_DECISIONS.md
    ├── 09_KNOWN_ISSUES_AND_TODO.md
    ├── 10_QC_CHECKLIST.md
    └── 11_AGENT_AND_PROMPTS.md
```

---

## 3. How the Parts Connect

1. **Request Routing:** When a request hits `coreve.in`:
   - Nginx checks FastCGI cache. If request is a GET and no cart/login cookies exist, cached HTML is served.
   - If not cached, Nginx forwards the request to PHP 8.3-FPM via socket `/run/php/php8.3-fpm-coreve.sock`.
2. **Template Interception:**
   - WordPress loads `mu-plugins/coreve-storefront-loader.php` which executes `coreve-storefront/storefront.php`.
   - `coreve_sf_route()` identifies whether the current page is `'home'`, `'collection'`, `'product'`, `'cart'`, or `'checkout'`.
   - When matched, it intercepts the template via `template_include` and loads `coreve-storefront/template.php`.
3. **WooCommerce Commerce Engine Integration:**
   - For product pages, `template.php` invokes `woocommerce_template_single_add_to_cart()` to render the live WooCommerce variable product form.
   - Size selection pills map directly to WooCommerce attribute `pa_size`.
   - Submitting the form adds the item to the WooCommerce session cart and redirects straight to `/checkout/` via `woocommerce_add_to_cart_redirect`.
4. **Checkout & Payment Execution:**
   - The checkout page renders the WooCommerce checkout form inside the storefront styling.
   - Customer enters their shipping address and phone number (enforced as required).
   - Upon clicking "Place Order", the order is created in WooCommerce HPOS tables.
   - For payment handoff, `payment.js` intercepts the Razorpay form, initializes `window.Razorpay` with signed options, and presents the secure payment modal.
5. **Post-Payment Processing & Notifications:**
   - Upon successful payment signature verification, order status changes from `pending` to `processing`.
   - This fires `woocommerce_order_status_processing`, triggering:
     a) `coreve_assign_order_number()`: Assigns the next sequential order number (e.g. `KLE99817`).
     b) `coreve_send_order_notification_email()`: Generates a complete HTML summary and sends it to `lenee@coreve.in` via Zoho SMTP.
