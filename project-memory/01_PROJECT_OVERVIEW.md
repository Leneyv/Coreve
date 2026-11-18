# 01 - Project Overview

## 1. Core Identity & Business
* **Project Name:** Coreve Lifestyle (`coreve.in`)
* **Legal Entity:** Coreve Lifestyle Pvt Ltd
* **Registered Address:** Building No./Flat No.: 17, Road/Street: 7th Main Road, 2 Stage, Indiranagar, City/Town: Bengaluru, District: Bengaluru Urban, State: Karnataka, PIN Code: 560038, India.
* **Support Email:** `hello@coreve.in`
* **Order Notification Inbox:** `lenee@coreve.in`
* **Contact Phone:** `+91 93639 36665` / `+91 94001 99817`
* **Primary Business Model:** Direct-to-Consumer (D2C) premium footwear.
* **Product Line:** Women-first leather wedge sneakers designed and manufactured in India. Features a 65mm total heel elevation with a 25mm forefoot platform (effective 40mm incline), anatomical arch contouring, and full-grain leather uppers in EU sizes 37–41.
* **Core Collection Styles (5 Models):**
  1. **Cipher Mocha Mousse** (Warm mocha / ivory - SKU/Slug: `cipher-mocha-mousse`)
  2. **Ventra Blue Granite** (Blue granite / ivory - SKU/Slug: `ventra-blue-granite`)
  3. **Elara Tendril** (Tendril green / ivory - SKU/Slug: `elara-tendril`)
  4. **Zivana Winterberry** (Winterberry / ivory - SKU/Slug: `zivana-winterberry`)
  5. **Nyro Eclipse** (Eclipse navy / ivory - SKU/Slug: `nyro-eclipse`)
  *(Plus bespoke bridal edition: `Coreve Her Day Handmade Custom Version`)*

---

## 2. Technical Stack & Hosting Details
* **Domain:** `https://coreve.in` (redirects `http://` and `www.` to `https://coreve.in`)
* **Hosting Platform:** Single VPS / Compute Instance running Ubuntu 24.04 LTS
* **Host IP:** `129.121.132.35`
* **Web Server:** Nginx 1.24.0 with HTTP/2, Gzip compression, FastCGI caching, and SSL via Let's Encrypt Certbot
* **PHP Runtime:** PHP 8.3-FPM (`/run/php/php8.3-fpm-coreve.sock`)
* **Database:** MariaDB / MySQL 8.x with High-Performance Order Storage (HPOS) enabled
* **CMS & Commerce:** WordPress 6.x + WooCommerce 11.x
* **Payment Gateway:** Razorpay (`woo-razorpay` 4.8.8) with native client-side modal bridge (`payment.js`)
* **Mail Delivery (MTA):** Postfix 3.8.6 (configured loopback-only) + Authenticated Zoho Mail SMTP relay (`smtp.zoho.in:465` SSL) via `wp-content/mu-plugins/coreve-smtp.php`
* **DNS & Mail Hosting:** Zoho Mail India (`mx.zoho.in`, `mx2.zoho.in`, `mx3.zoho.in`) with SPF record `v=spf1 include:zohomail.in ~all`

---

## 3. Main User Roles
* **Shopper / Guest Customer:** Browses the high-performance storefront, explores product imagery, selects EU size (37–41), clicks "Buy now", and completes prepaid payment via Razorpay.
* **Store Owner / Fulfillment Admin (`lenee@coreve.in`):** Receives instant structured HTML order alerts containing customer name, phone, address, items, and direct links to fulfill orders in WP Admin.
* **System Administrator:** Maintains VPS security, Nginx fastcgi caching, database backups, SSL renewals, and Git sync.
