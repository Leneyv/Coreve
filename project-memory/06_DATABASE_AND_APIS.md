# 06 - Database & APIs

## 1. Database Schema & Tables

The site uses MariaDB / MySQL 8.x with WordPress default tables plus WooCommerce High-Performance Order Storage (HPOS). Database name: `coreve_db`.

### Key Custom & Commerce Tables

#### 1. `wp_wc_orders` (WooCommerce HPOS Primary Order Table)
* `id` (`bigint unsigned`, Primary Key): Internal order ID.
* `status` (`varchar(20)`): Order status (`wc-pending`, `wc-processing`, `wc-completed`, `wc-cancelled`, `wc-failed`).
* `currency` (`varchar(10)`): Set to `INR`.
* `total_amount` (`decimal(26,8)`): Order total in Rupees.
* `customer_id` (`bigint unsigned`): WordPress user ID or 0 for guest.
* `billing_email` (`varchar(320)`): Customer email address.
* `payment_method` (`varchar(100)`): Payment gateway ID (`razorpay`).
* `transaction_id` (`varchar(100)`): Razorpay payment ID (e.g., `pay_...`).
* `date_created_gmt` (`datetime`): Timestamp of order placement.

#### 2. `wp_wc_orders_meta` (HPOS Order Metadata)
* `id` (`bigint unsigned`, Primary Key)
* `order_id` (`bigint unsigned`, Foreign Key to `wp_wc_orders.id`)
* `meta_key` (`varchar(255)`): Metadata key.
  - `_coreve_order_number`: Stores custom sequential string (e.g. `KLE99817`).
  - `_coreve_order_notification_sent`: Timestamp lock preventing duplicate admin emails.
  - `_razorpay_order_id`: Razorpay server-side order ID.
* `meta_value` (`longtext`): Value.

#### 3. `wp_options` (Configuration & Sequences)
* `coreve_next_order_seq`: Integer tracking sequence counter (e.g. `99816`). Incremented atomically via `LAST_INSERT_ID(CAST(option_value AS UNSIGNED) + 1)`.
* `coreve_storefront_2026_enabled`: Boolean flag (`1` or `0`) toggling modern storefront vs classic theme.
* `woocommerce_new_order_settings`: JSON/serialized options configuring new order emails (recipient: `lenee@coreve.in`).
* `woocommerce_email_from_address`: `lenee@coreve.in`.
* `woocommerce_email_from_name`: `Coreve Orders`.

#### 4. Product Taxonomy & Variations
* Products are variable items linked via `wp_posts` and `wp_postmeta`.
* Attribute taxonomy: `pa_size` with terms representing EU sizes: `37`, `38`, `39`, `40`, `41`.
* Core Product IDs:
  - `117`: Cipher Mocha Mousse
  - `118`: Ventra Blue Granite
  - `119`: Zivana Winterberry
  - `120`: Elara Tendril
  - `121`: Nyro Eclipse
  - `143`: Coreve Her Day Handmade Custom Version (Bespoke bridal edition)

---

## 2. API Endpoints & Routes

### Frontend Public Routes
* `/`: Dynamic homepage rendered by `coreve-storefront/template.php`.
* `/shop/` (or `/collection`): Complete catalog view of Coreve styles.
* `/product/{slug}/`: Single product detail pages with size selection and Buy Now.
* `/cart/`: Shopping bag view (auto-redirects to checkout on Buy Now).
* `/checkout/`: Single-page checkout with billing details, shipping validation, and Razorpay payment.
* `/about-us/`: Brand storytelling, craftsmanship philosophy, and design process.
* `/size-guide/`: Footwear measurement guide (heel-to-toe length chart).
* `/faq/`: Shipping, returns, fit, and material care answers.
* `/llms.txt` & `/llms-full.txt`: Generative AI structured markdown documentation.
* `/wp-sitemap.xml`: Native XML sitemaps for search engines.

### WordPress REST API (`/wp-json/`)
* `/wp-json/wc/v3/`: WooCommerce REST API (authenticated via API keys for external ERP/Shiprocket integrations).
* `/wp-json/wp/v2/`: Standard WordPress endpoints (content query and metadata).

---

## 3. Third-Party Integrations & Services

### 1. Razorpay Payment Gateway
* **Plugin:** `woo-razorpay` (v4.8.8)
* **Mode:** Live production
* **Integration Points:**
  - Client bridge: `wp-content/coreve-storefront/payment.js`
  - Webhook listener: `/wc-api/WC_Razorpay/` for asynchronous capture confirmation.
* **Currency:** INR (₹).

### 2. Zoho Mail SMTP Relay
* **Host:** `smtp.zoho.in`
* **Port:** `465` (SSL)
* **Auth:** Required (credentials defined as constants in `wp-config.php`).
* **From Address:** `lenee@coreve.in` (Sender: "Coreve Orders").
* **Recipient for Order Alerts:** `lenee@coreve.in`.
* **Purpose:** Ensures transactional emails pass SPF, DKIM, and DMARC without hitting Zoho spam filters.

### 3. Let's Encrypt SSL
* **Tool:** `certbot`
* **Renewal:** Automated systemd timer renewing certificates every 60 days.
* **Domains covered:** `coreve.in`, `www.coreve.in`.
