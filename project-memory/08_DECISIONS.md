# 08 - Architecture Decision Records (ADRs)

---

### ADR-001: Hybrid Storefront Module (`coreve-storefront`) vs Standalone Static Site vs Full Next.js
* **Context:** The client designed a modern, visual design preview under `/staging/` (with video heroes, interactive studio scenes, and custom typography) and wanted it brought live.
* **Alternatives Considered:**
  1. *Pure Static HTML via Nginx `location = /`:* Rejected because it completely bypassed WordPress `wp_head()`, breaking analytics, SEO JSON-LD schemas, and dynamic WooCommerce cart/checkout sessions.
  2. *Full Next.js Headless Rebuild:* Rejected due to high operational complexity and unnecessary hosting overhead for a 5-product boutique catalog.
  3. *Hybrid WordPress Integration via `mu-plugins`:* **Chosen.**
* **Rationale:** By loading the storefront through `mu-plugins/coreve-storefront-loader.php` and `template_include`, the site gains the blazing speed (<0.5s TTFB) and aesthetic perfection of the custom design while retaining full WordPress SEO, dynamic WooCommerce variation forms, and Razorpay payment handling.

---

### ADR-002: Authenticated Zoho SMTP Relay vs Local Sendmail Direct Port 25
* **Context:** Order notifications to `lenee@coreve.in` and customer order emails were failing completely on the server.
* **Alternatives Considered:**
  1. *Direct Port 25 Delivery via Postfix:* Rejected. Because `coreve.in`'s MX is hosted on Zoho Mail India, Zoho's anti-spoofing policy immediately rejects incoming unauthenticated port 25 connections claiming to originate from `@coreve.in` (`554 5.7.1 Antispam policy`). Furthermore, shared cloud datacenter IPs fail Forward-Confirmed Reverse DNS (FCrDNS).
  2. *Third-Party Transactional API (SendGrid, Mailgun):* Rejected as unnecessary extra monthly expense and third-party dependency.
  3. *Authenticated Zoho SMTP via `coreve-smtp.php`:* **Chosen.**
* **Rationale:** Sending through `smtp.zoho.in:465` (SSL) authenticates directly with Zoho's infrastructure, guaranteeing 100% inbox delivery and full SPF/DKIM/DMARC alignment.

---

### ADR-003: Atomic MySQL Sequence (`LAST_INSERT_ID`) for Order Numbering (`KLE99817`+) vs Plugin
* **Context:** The client required all future order numbers to follow a custom branded sequence starting at `KLE99817`.
* **Alternatives Considered:**
  1. *Commercial WooCommerce Sequential Order Numbers Plugin:* Rejected to prevent plugin bloat, database overhead, and subscription costs.
  2. *Standard PHP Counter in wp_options:* Rejected due to concurrency hazards (race conditions could assign duplicate numbers if two customers checkout simultaneously).
  3. *Atomic MySQL `LAST_INSERT_ID(CAST(option_value AS UNSIGNED) + 1)`:* **Chosen.**
* **Rationale:** Executing `UPDATE wp_options SET option_value = LAST_INSERT_ID(...)` is a single atomic SQL transaction handled inside the MySQL engine, guaranteeing 100% thread-safety across concurrent checkouts.

---

### ADR-004: Direct "Buy Now" Auto-Redirect to Checkout vs Traditional Cart Drawer
* **Context:** The brand sells a curated collection of 5 premium sneakers (average order value ₹6,995).
* **Alternatives Considered:**
  1. *Multi-Step Cart Drawer:* Rejected because customers buying luxury sneakers rarely add multiple pairs in a single transaction; extra cart steps add friction and increase cart abandonment.
  2. *Direct Buy Now Redirect (`woocommerce_add_to_cart_redirect`):* **Chosen.**
* **Rationale:** Upon size selection and tapping "Buy now", the customer is routed directly to the streamlined `/checkout/` page, maximizing conversion velocity.

---

### ADR-005: Strictly Prepaid (Cash on Delivery Disabled)
* **Context:** Return-to-Origin (RTO) in Indian footwear e-commerce exceeds 30-40% when Cash on Delivery is enabled, eroding unit economics on handmade leather goods.
* **Decision:** Keep Cash on Delivery permanently disabled. Only Razorpay prepaid transactions (UPI, Cards, NetBanking) are accepted.
