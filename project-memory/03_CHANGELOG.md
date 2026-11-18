# 03 - Changelog

This changelog records all code, configuration, database, and infrastructure changes in chronological order. It is append-only.

---

### CHG-001
* **Date & Time:** 2026-11-09
* **Linked Command:** [CMD-001](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-001)
* **Type:** Config
* **What Changed & Why:** Initial baseline inspection of WordPress, Nginx, PHP, and MySQL on VPS `129.121.132.35`. Confirmed site structure, active theme `coreve`, and plugin suite (`woo-razorpay`, `woocommerce`).
* **Files Touched:** None.
* **How Tested & Result:** Ran `curl -sI https://coreve.in/` and verified HTTP 200. Result: Tested (Pass).
* **Potential Breakages:** None.
* **How to Undo:** N/A (read-only).

---

### CHG-002
* **Date & Time:** 2026-11-09
* **Linked Command:** [CMD-002](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-002)
* **Type:** Added
* **What Changed & Why:** Implemented technical SEO structured data schemas in `functions.php` (`Organization`, `WebSite`, `Product`, `BreadcrumbList`, and `FAQPage`). Updated `robots.txt` with clear crawl directives and sitemap references.
* **Files Touched:**
  - `/var/www/coreve.in/wp-content/themes/coreve/functions.php`
  - `/var/www/coreve.in/robots.txt`
* **How Tested & Result:** Tested schema output with JSON schema validator and Google Rich Results test. Result: Tested (Pass).
* **Potential Breakages:** Invalid JSON syntax could fail search crawler parsing (mitigated with `wp_json_encode`).
* **How to Undo:** Revert changes in `functions.php` and restore previous `robots.txt.bak-seo`.

---

### CHG-003
* **Date & Time:** 2026-11-09
* **Linked Command:** [CMD-003 & CMD-004](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-003--cmd-004)
* **Type:** Config
* **What Changed & Why:** Configured SSH key-based access for root on the VPS (`129.121.132.35`) using ed25519 cryptography to eliminate password transmission risks and streamline remote orchestration.
* **Files Touched:**
  - `/root/.ssh/authorized_keys`
* **How Tested & Result:** Ran `ssh -i ... root@129.121.132.35 whoami`. Result: Tested (Pass, returned `root`).
* **Potential Breakages:** Locking out SSH if authorized_keys permissions are corrupted (verified 0600 permissions).
* **How to Undo:** Delete the appended public key from `/root/.ssh/authorized_keys`.

---

### CHG-004
* **Date & Time:** 2026-11-09
* **Linked Command:** [CMD-005](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-005)
* **Type:** Config / Upgraded
* **What Changed & Why:** Performance optimization: Configured Nginx Gzip compression level 6, FastCGI microcaching (`coreve_cache`), and 1-year immutable cache headers for static assets (`.webp`, `.css`, `.js`, fonts). Converted uncompressed banner images to WebP.
* **Files Touched:**
  - `/etc/nginx/sites-available/coreve.in`
  - `/etc/nginx/conf.d/fastcgi-cache.conf`
  - `/var/www/coreve.in/wp-content/themes/coreve/assets/`
* **How Tested & Result:** Measured homepage weight before (2.2MB) vs after (0.6MB). TTFB dropped from >1.8s to <0.5s. Result: Tested (Pass).
* **Potential Breakages:** Stale cache served to authenticated users (prevented by strict `$skip_cache` cookies rule for `woocommerce_items_in_cart` and `wordpress_logged_in`).
* **How to Undo:** Restore `/etc/nginx/sites-available/coreve.in.bak-seo` and reload Nginx.

---

### CHG-005
* **Date & Time:** 2026-11-09
* **Linked Command:** [CMD-006 & CMD-007](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-006--cmd-007)
* **Type:** Upgraded / Fixed
* **What Changed & Why:** Overhauled single-product page UX: Added dual EU + UK size selector pills, style preview switcher across all 5 models, responsive sizing chart modal, and verified review highlights. Enforced strict prepaid-only policy (Cash on Delivery disabled).
* **Files Touched:**
  - `/var/www/coreve.in/wp-content/themes/coreve/single-product.php`
  - `/var/www/coreve.in/wp-content/themes/coreve/style.css`
* **How Tested & Result:** Validated interactive size modal toggles, style switcher links, and checkout redirection on desktop and mobile viewports. Result: Tested (Pass).
* **Potential Breakages:** Layout overflow on very narrow viewports (<340px) - handled via flexible wrap and clamp typography.
* **How to Undo:** Revert to `single-product.php.bak-cx` and `style.css.bak-cx`.

---

### CHG-006
* **Date & Time:** 2026-11-09
* **Linked Command:** [CMD-008](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-008)
* **Type:** Added
* **What Changed & Why:** Added mandatory Indian e-commerce statutory declarations for Manufacturer and Marketer (Coreve Lifestyle Pvt Ltd, 17, 7th Main Road, 2 Stage, Indiranagar, Bengaluru 560038) on every product page.
* **Files Touched:**
  - `/var/www/coreve.in/wp-content/themes/coreve/single-product.php`
* **How Tested & Result:** Inspected DOM on all 5 product pages; verified address formatting and legal clarity. Result: Tested (Pass).
* **Potential Breakages:** None.
* **How to Undo:** Remove the legal disclaimer block from `single-product.php`.

---

### CHG-007
* **Date & Time:** 2026-11-09
* **Linked Command:** [CMD-009](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-009)
* **Type:** Added
* **What Changed & Why:** Added Generative Engine Optimization (GEO) files `llms.txt` and `llms-full.txt` outlining brand facts, size conversions, design specs, and verified delivery policies for AI search systems (Perplexity, ChatGPT, Gemini). Enhanced JSON-LD with Speakable specifications.
* **Files Touched:**
  - `/var/www/coreve.in/llms.txt`
  - `/var/www/coreve.in/llms-full.txt`
  - `/var/www/coreve.in/wp-content/themes/coreve/functions.php`
* **How Tested & Result:** Curled `/llms.txt` and `/llms-full.txt` over HTTP/2; checked schema output via Google Rich Results Tool. Result: Tested (Pass).
* **Potential Breakages:** None.
* **How to Undo:** Delete `/var/www/coreve.in/llms*.txt` and revert schema addition in `functions.php`.

---

### CHG-008
* **Date & Time:** 2026-11-11
* **Linked Command:** [CMD-010 & CMD-011](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-010--cmd-011)
* **Type:** Added / Config
* **What Changed & Why:**
  1. Resolved missing MTA (`/usr/sbin/sendmail: not found`) by installing and configuring Postfix in loopback mode on Ubuntu.
  2. Overcame Zoho Mail MX inbound rejection (`554 5.7.1 Antispam policy`) by deploying `wp-content/mu-plugins/coreve-smtp.php`, configuring WordPress PHPMailer to authenticate directly via Zoho SMTP (`smtp.zoho.in:465` SSL).
  3. Defined SMTP credentials safely as PHP constants in `wp-config.php`.
  4. Added `coreve_send_order_notification_email()` in `functions.php` hooked to `woocommerce_order_status_processing`, `woocommerce_order_status_completed`, and `woocommerce_checkout_order_processed`.
  5. Updated `woocommerce_new_order_settings` recipient to `lenee@coreve.in`.
* **Files Touched:**
  - `/var/www/coreve.in/wp-content/mu-plugins/coreve-smtp.php`
  - `/var/www/coreve.in/wp-config.php`
  - `/var/www/coreve.in/wp-content/themes/coreve/functions.php`
  - `/etc/postfix/main.cf`
* **How Tested & Result:** Sent live test email via WP-CLI `wp eval 'wp_mail(...)'`; dispatched simulated order `#213` notification to `lenee@coreve.in`. Zoho Mail accepted and delivered successfully without bounce. Result: Tested (Pass).
* **Potential Breakages:** If Zoho account password changes, SMTP auth will fail until `wp-config.php` is updated.
* **How to Undo:** Remove `coreve-smtp.php`, unset constants in `wp-config.php`, and remove hook from `functions.php`.

---

### CHG-009
* **Date & Time:** 2026-11-12
* **Linked Command:** [CMD-012](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-012)
* **Type:** Added / Database
* **What Changed & Why:** Implemented custom sequential order numbering starting from `KLE99817`. Stored sequence in `wp_options` under `coreve_next_order_seq` (initialized to `99816`). Used atomic MySQL `LAST_INSERT_ID(CAST(option_value AS UNSIGNED) + 1)` to prevent race conditions during concurrent checkouts. Filtered `woocommerce_order_number` and enabled search by `_coreve_order_number` in WooCommerce Admin HPOS.
* **Files Touched:**
  - `/var/www/coreve.in/wp-content/themes/coreve/functions.php`
  - Database: `wp_options` row `coreve_next_order_seq`
* **How Tested & Result:** Created sequential test orders in WP-CLI. Verified Order 1 received `KLE99817` and Order 2 received `KLE99818`. Cleaned up test orders and reset sequence to `99816`. Result: Tested (Pass).
* **Potential Breakages:** Legacy orders (ID <= 213) intentionally retain their original IDs to prevent disrupting existing records.
* **How to Undo:** Remove filters from `functions.php` and delete `coreve_next_order_seq` option.

---

### CHG-010
* **Date & Time:** 2026-11-14
* **Linked Command:** [CMD-013](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-013)
* **Type:** Config
* **What Changed & Why:** Routed `location = /` in Nginx to serve the newly created static staging design from `/var/www/coreve.in/staging/index.html` with zero-stale cache control headers.
* **Files Touched:**
  - `/etc/nginx/sites-available/coreve.in`
* **How Tested & Result:** Curled `https://coreve.in/` and verified staging HTML returned with HTTP 200. Result: Tested (Pass).
* **Potential Breakages:** Static routing initially bypassed WordPress dynamic theme hooks (later superseded cleanly by CHG-013).
* **How to Undo:** Remove `location = /` block from Nginx configuration.

---

### CHG-011
* **Date & Time:** 2026-11-14
* **Linked Command:** [CMD-014](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-014)
* **Type:** Removed
* **What Changed & Why:** Removed the 4-angle video loop (`<div class="angle-film">...</div>`) and the caption "Explore four angles · Tap play" from all 5 single product page templates as requested by the user.
* **Files Touched:**
  - `/var/www/coreve.in/staging/products/cipher-mocha-mousse/index.html`
  - `/var/www/coreve.in/staging/products/elara-tendril/index.html`
  - `/var/www/coreve.in/staging/products/nyro-eclipse/index.html`
  - `/var/www/coreve.in/staging/products/ventra-blue-granite/index.html`
  - `/var/www/coreve.in/staging/products/zivana-winterberry/index.html`
* **How Tested & Result:** Grepped for `angle-film` and confirmed 0 occurrences. Checked DOM on `zivana-winterberry` to verify clean transition from gallery thumbs into `product-info`. Result: Tested (Pass).
* **Potential Breakages:** None.
* **How to Undo:** Restore backups from `/root/coreve-backups/product-video-removal-*/`.

---

### CHG-012
* **Date & Time:** 2026-11-15
* **Linked Command:** [CMD-015](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-015)
* **Type:** Removed
* **What Changed & Why:** Removed `<span>Design preview · No orders or payments</span>` from the footer across the home page and all sub-pages.
* **Files Touched:**
  - `/var/www/coreve.in/staging/index.html`
  - `/var/www/coreve.in/staging/brand/index.html`
  - `/var/www/coreve.in/staging/collection/index.html`
  - `/var/www/coreve.in/staging/products/*/index.html`
* **How Tested & Result:** Ran `curl -s https://coreve.in/ | grep -F "Design preview · No orders or payments"` and verified zero matches. Result: Tested (Pass).
* **Potential Breakages:** None.
* **How to Undo:** Re-insert `<span>Design preview · No orders or payments</span>` into `.footer-bottom`.

---

### CHG-013
* **Date & Time:** 2026-11-15
* **Linked Command:** [CMD-016](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-016)
* **Type:** Refactored / Upgraded
* **What Changed & Why:** Integrated the ChatGPT Codex storefront system. Removed static Nginx `location = /` bypass; routed all traffic through WordPress. Added `wp-content/coreve-storefront/` and `wp-content/mu-plugins/coreve-storefront-loader.php`. Dynamically injected WooCommerce `single_add_to_cart` variation form, converted "Add to bag" to "Buy now" (auto-redirecting to checkout), replaced Razorpay payment bridge with reliable DOM-ready handler (`payment.js`), and preserved full WordPress `wp_head()`/`wp_footer()` SEO and analytics execution.
* **Files Touched:**
  - `/var/www/coreve.in/wp-content/coreve-storefront/` (entire module)
  - `/var/www/coreve.in/wp-content/mu-plugins/coreve-storefront-loader.php`
  - `/etc/nginx/sites-available/coreve.in`
  - Synced to local repo `/Users/lenee/coreve/`
* **How Tested & Result:** Verified HTTP 200 on all 43 scripts, stylesheets, WebP images, and video assets; confirmed variation form present on single product pages; verified TTFB <0.5s. Result: Tested (Pass).
* **Potential Breakages:** If `coreve_storefront_2026_enabled` option is deleted or set to false, storefront falls back to classic `themes/coreve`.
* **How to Undo:** Set `wp option update coreve_storefront_2026_enabled 0` or remove `coreve-storefront-loader.php`.

---

### CHG-014
* **Date & Time:** 2026-11-18
* **Linked Command:** [CMD-017](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#cmd-017)
* **Type:** Added
* **What Changed & Why:** Created the permanent, structured `/project-memory` directory and authored comprehensive handover documentation (00 to 11) for AI supervision and quality control.
* **Files Touched:**
  - `/Users/lenee/coreve/project-memory/` (all 12 markdown documents)
* **How Tested & Result:** Validated markdown link structures and local file paths. Result: Tested (Pass).
* **Potential Breakages:** None (documentation files only).
* **How to Undo:** Delete `/Users/lenee/coreve/project-memory`.
