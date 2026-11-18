# 02 - Command Log

This log records every instruction given by the user in strict chronological order. It is append-only.

---

### CMD-001
* **Date & Time:** 2026-11-09 (Initial Interaction)
* **User Instruction:** "coreve.in we have a website hosted like tihs in the server check"
* **Understanding:** Perform an initial inspection of the website `coreve.in` on the server and understand its environment, software stack, and architecture.
* **What Was Done:** Checked web response of `https://coreve.in/`, confirmed it was a WordPress/WooCommerce site running on Nginx on an Ubuntu VPS.
* **Files Created or Changed:** None (Read-only investigation).
* **Result:** Done.
* **Changelog Link:** [CHG-001](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-001)

---

### CMD-002
* **Date & Time:** 2026-11-09
* **User Instruction:** "first for thru the webite and usnerstand what it is then do technical SEO for Google and AI's then make on page seo too"
* **Understanding:** Deeply analyze the website, brand context, and product category, then implement technical SEO for search engines (Googlebot) and Generative Engine Optimization (GEO/AEO) for AI search engines.
* **What Was Done:** Audited meta tags, schema markup, and robots.txt; drafted and implemented structured data schemas (`Organization`, `Product`, `WebSite`, `FAQPage`, `BreadcrumbList`).
* **Files Created or Changed:**
  - `/var/www/coreve.in/wp-content/themes/coreve/functions.php`
  - `/var/www/coreve.in/robots.txt`
* **Result:** Done.
* **Changelog Link:** [CHG-002](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-002)

---

### CMD-003 & CMD-004
* **Date & Time:** 2026-11-09
* **User Instruction:** "i have give ssh access alreayd" and "129.121.132.35 this is the ip and password is [REDACTED_PASSWORD]"
* **Understanding:** Access the production VPS via SSH at `129.121.132.35` using the provided root credentials, then establish key-based SSH access for subsequent commands.
* **What Was Done:** Connected via SSH to `root@129.121.132.35`, installed ed25519 public key in `/root/.ssh/authorized_keys`, verified passwordless key access, and secured credentials.
* **Files Created or Changed:**
  - `/root/.ssh/authorized_keys` (on server)
  - `/Users/lenee/.gemini/antigravity/scratch/ssh_keys/id_ed25519` (local key pair)
* **Result:** Done.
* **Changelog Link:** [CHG-003](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-003)

---

### CMD-005
* **Date & Time:** 2026-11-09
* **User Instruction:** "check the speed of the website and fix it if it is slow"
* **Understanding:** Measure page load metrics, asset weights, and server response times, then optimize caching, compression, and image weights to improve speed.
* **What Was Done:** Identified heavy uncompressed hero images (>2.2MB). Converted assets to WebP, implemented Nginx Gzip compression, enabled FastCGI page caching (`coreve_cache`), and set 1-year immutable caching on static assets, reducing page size from 2.2MB down to ~0.6MB and TTFB to <0.5s.
* **Files Created or Changed:**
  - `/etc/nginx/sites-available/coreve.in`
  - `/var/www/coreve.in/wp-content/themes/coreve/assets/`
* **Result:** Done.
* **Changelog Link:** [CHG-004](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-004)

---

### CMD-006 & CMD-007
* **Date & Time:** 2026-11-09
* **User Instruction:** "are you haappy with the prodcut page of coreve.in ? check and let me knwo the customer experiance in it" followed by "fux everythiging except cash on delivery not available more from the colction not available rest everything is ok fix those errors what you found"
* **Understanding:** Audit product page customer experience (CX), identify UX bottlenecks (unclear sizing, missing material details, clunky image views), and fix all identified issues while keeping Cash on Delivery disabled (prepaid only).
* **What Was Done:** Added dual EU + UK sizing pills to eliminate sizing confusion, introduced visual style switcher pills across the 5 models, added interactive size guide popup, improved touch target sizes, highlighted verified customer reviews, and kept Cash on Delivery strictly disabled.
* **Files Created or Changed:**
  - `/var/www/coreve.in/wp-content/themes/coreve/single-product.php`
  - `/var/www/coreve.in/wp-content/themes/coreve/style.css`
* **Result:** Done.
* **Changelog Link:** [CHG-005](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-005)

---

### CMD-008
* **Date & Time:** 2026-11-09
* **User Instruction:** "also add manufacturere and marketer both are coreve lifestyle pvt limited - on every products page address is Building No./Flat No.: 17Road/Street: 7th Main Road , 2 Stage City/Town/Village: Bengaluru District: Bengaluru Urban State: Karnataka PIN Code: 560038"
* **Understanding:** Display the full mandatory legal manufacturer and marketer entity details and registered address on all single product pages for regulatory compliance.
* **What Was Done:** Added a structured "Manufacturer & Marketer Information" section to `single-product.php` containing Coreve Lifestyle Pvt Ltd and the complete Bengaluru address.
* **Files Created or Changed:**
  - `/var/www/coreve.in/wp-content/themes/coreve/single-product.php`
* **Result:** Done.
* **Changelog Link:** [CHG-006](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-006)

---

### CMD-009
* **Date & Time:** 2026-11-09
* **User Instruction:** "check SEO GEO AEO and do the needfull compleatly"
* **Understanding:** Execute a complete audit and implementation of Search Engine Optimization (SEO), Generative Engine Optimization (GEO), and Answer Engine Optimization (AEO).
* **What Was Done:** Created `llms.txt` and `llms-full.txt` at web root; added `SpeakableSpecification` schema, comprehensive FAQPage schema, HowTo fit guide schema, Collection ItemList graph, and BreadcrumbList schema; verified Googlebot robots directives.
* **Files Created or Changed:**
  - `/var/www/coreve.in/llms.txt`
  - `/var/www/coreve.in/llms-full.txt`
  - `/var/www/coreve.in/wp-content/themes/coreve/functions.php`
* **Result:** Done.
* **Changelog Link:** [CHG-007](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-007)

---

### CMD-010 & CMD-011
* **Date & Time:** 2026-11-11
* **User Instruction:** "make any order comes in coreve.in will send an email with order details to lenee@coreve.in" followed by "the password for my email is [REDACTED_PASSWORD]"
* **Understanding:** Ensure every WooCommerce order automatically triggers an email containing complete order details to `lenee@coreve.in`. Solve underlying MTA failures and configure authenticated SMTP via Zoho Mail.
* **What Was Done:**
  1. Installed and configured Postfix MTA loopback on VPS to restore `/usr/sbin/sendmail`.
  2. Discovered Zoho Mail MX rejects direct unauthenticated port 25 email (`554 5.7.1 Antispam policy`).
  3. Created `wp-content/mu-plugins/coreve-smtp.php` to route all WordPress `wp_mail()` calls via authenticated Zoho SMTP (`smtp.zoho.in:465` SSL).
  4. Secured SMTP credentials in `wp-config.php`.
  5. Implemented `coreve_send_order_notification_email()` in `functions.php` sending rich HTML emails with customer contact, shipping address, item breakdown, and direct WP Admin order link.
  6. Tested live delivery to `lenee@coreve.in` with success.
* **Files Created or Changed:**
  - `/var/www/coreve.in/wp-content/mu-plugins/coreve-smtp.php`
  - `/var/www/coreve.in/wp-config.php`
  - `/var/www/coreve.in/wp-content/themes/coreve/functions.php`
  - `/etc/postfix/main.cf`
* **Result:** Done.
* **Changelog Link:** [CHG-008](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-008)

---

### CMD-012
* **Date & Time:** 2026-11-12
* **User Instruction:** "make the order number start from KLE99817"
* **Understanding:** Configure WooCommerce so that new orders receive sequential, custom order numbers starting from `KLE99817` (`KLE99818`, `KLE99819`, etc.).
* **What Was Done:** Implemented thread-safe atomic MySQL sequence increments using `LAST_INSERT_ID` on `coreve_next_order_seq` in `functions.php`. Filtered `woocommerce_order_number` and enabled order search by `KLExxxxx` in WooCommerce Admin HPOS.
* **Files Created or Changed:**
  - `/var/www/coreve.in/wp-content/themes/coreve/functions.php`
* **Result:** Done.
* **Changelog Link:** [CHG-009](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-009)

---

### CMD-013
* **Date & Time:** 2026-11-14
* **User Instruction:** "bring https://coreve.in/staging/ this to live home page as it is on coreve.in"
* **Understanding:** Deploy the new design that was previewed under `/staging/` directly to the live home page of `coreve.in`.
* **What Was Done:** Initially routed `location = /` in Nginx to `/var/www/coreve.in/staging/index.html` with zero-stale cache headers and verified asset paths.
* **Files Created or Changed:**
  - `/etc/nginx/sites-available/coreve.in`
* **Result:** Done.
* **Changelog Link:** [CHG-010](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-010)

---

### CMD-014
* **Date & Time:** 2026-11-14
* **User Instruction:** "remove this video on on every product page" (with attached screenshot of the 4-angle video loop on product pages)
* **Understanding:** Remove the angle-loop video element (`<div class="angle-film">...</div>`) and the text "Explore four angles · Tap play" from every single product page.
* **What Was Done:** Removed `<div class="angle-film">...</div>` across all 5 product template files in `/var/www/coreve.in/staging/products/*/index.html`.
* **Files Created or Changed:**
  - `/var/www/coreve.in/staging/products/cipher-mocha-mousse/index.html`
  - `/var/www/coreve.in/staging/products/elara-tendril/index.html`
  - `/var/www/coreve.in/staging/products/nyro-eclipse/index.html`
  - `/var/www/coreve.in/staging/products/ventra-blue-granite/index.html`
  - `/var/www/coreve.in/staging/products/zivana-winterberry/index.html`
* **Result:** Done.
* **Changelog Link:** [CHG-011](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-011)

---

### CMD-015
* **Date & Time:** 2026-11-15
* **User Instruction:** "Design preview · No orders or payments this text remove from the home page"
* **Understanding:** Remove the footer text "Design preview · No orders or payments" from the home page (and ensure it is cleaned up site-wide).
* **What Was Done:** Stripped `<span>Design preview · No orders or payments</span>` from the footer across `staging/index.html` and all related templates.
* **Files Created or Changed:**
  - `/var/www/coreve.in/staging/index.html`
  - `/var/www/coreve.in/staging/brand/index.html`
  - `/var/www/coreve.in/staging/collection/index.html`
  - `/var/www/coreve.in/staging/products/*/index.html`
* **Result:** Done.
* **Changelog Link:** [CHG-012](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-012)

---

### CMD-016
* **Date & Time:** 2026-11-15
* **User Instruction:** "check the site now i have used chatgpt codex to make coreve.in home page"
* **Understanding:** Inspect the modifications performed by ChatGPT Codex on `coreve.in`, verify site health, performance, asset paths, checkout flow, and sync the codebase to the local repository.
* **What Was Done:** Audited the newly introduced `wp-content/coreve-storefront/` architecture; confirmed Nginx passes `/` to WordPress; verified HTTP 200 on all scripts, styles, fonts, and WebP images; verified Buy Now and Razorpay integration; rsynced and committed all changes into local Git.
* **Files Created or Changed:**
  - `/var/www/coreve.in/wp-content/coreve-storefront/` (synced to local)
  - `/var/www/coreve.in/wp-content/mu-plugins/coreve-storefront-loader.php` (synced to local)
* **Result:** Done.
* **Changelog Link:** [CHG-013](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-013)

---

### CMD-017
* **Date & Time:** 2026-11-18
* **User Instruction:** "STANDING RULE FOR THIS PROJECT: MAINTAIN A 'PROJECT MEMORY' FOR HANDOVER AND QC ... Confirm that you understand, then start with step 6 (backfill)."
* **Understanding:** Establish the formal `/project-memory` system, backfill the complete chronological command and change logs, document architecture, APIs, environments, decisions, QC tests, and backlog, and commit to Git.
* **What Was Done:** Created `/project-memory` directory and authored all 12 standardized handover files.
* **Files Created or Changed:**
  - `/Users/lenee/coreve/project-memory/00_README_FOR_AI.md`
  - `/Users/lenee/coreve/project-memory/01_PROJECT_OVERVIEW.md`
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
  - `/Users/lenee/coreve/project-memory/04_FEATURES_STATUS.md`
  - `/Users/lenee/coreve/project-memory/05_ARCHITECTURE.md`
  - `/Users/lenee/coreve/project-memory/06_DATABASE_AND_APIS.md`
  - `/Users/lenee/coreve/project-memory/07_ENV_AND_SETUP.md`
  - `/Users/lenee/coreve/project-memory/08_DECISIONS.md`
  - `/Users/lenee/coreve/project-memory/09_KNOWN_ISSUES_AND_TODO.md`
  - `/Users/lenee/coreve/project-memory/10_QC_CHECKLIST.md`
  - `/Users/lenee/coreve/project-memory/11_AGENT_AND_PROMPTS.md`
* **Result:** Done.
* **Changelog Link:** [CHG-014](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-014)

---

### CMD-018
* **Date & Time:** 2026-11-18
* **User Instruction:** "Not requested by user" (Proactive Security Hardening following Project Memory initialization)
* **Understanding:** Prevent public HTTP web requests from downloading internal `/project-memory/` files by blocking access in the Nginx server configuration (`deny all; return 404;`).
* **What Was Done:** Added an isolated location block `location ^~ /project-memory/ { deny all; return 404; }` in `/etc/nginx/sites-available/coreve.in`, tested syntax with `nginx -t`, reloaded Nginx, backed up to `/var/www/coreve.in/nginx-coreve.in.conf`, and synced locally.
* **Files Created or Changed:**
  - `/etc/nginx/sites-available/coreve.in`
  - `/var/www/coreve.in/nginx-coreve.in.conf`
  - `/Users/lenee/coreve/nginx-coreve.in.conf`
* **Result:** Done.
* **Changelog Link:** [CHG-015](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md#chg-015)

