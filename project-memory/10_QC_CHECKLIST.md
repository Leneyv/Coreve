# 10 - Quality Control (QC) Checklist & Test SOPs

This document contains step-by-step Quality Control (QC) procedures for another AI or human engineer to verify that every component of the Coreve platform is operational.

---

## 1. Core Platform QC Matrix

| QC Test ID | Category | Feature / Flow | Exact Steps to Test | Expected Result | Status | Last Tested Date |
| :--- | :--- | :--- | :--- | :--- | :--- | :--- |
| **QC-001** | **Availability** | Web Server & HTTPS | Run `curl -sI https://coreve.in/` | HTTP/2 200 OK, valid Let's Encrypt SSL, strict transport security header present. | **Pass** | 2026-11-15 |
| **QC-002** | **Performance** | TTFB & Speed | Run `curl -o /dev/null -s -w "%{time_starttransfer}s" https://coreve.in/` | TTFB under 0.6 seconds; total download under 1.0 second. | **Pass** | 2026-11-15 |
| **QC-003** | **Visual Assets** | Hero Video Delivery | Request `https://coreve.in/wp-content/coreve-storefront/assets/coreve-desktop-hero.mp4` via curl HEAD | HTTP 200 with `Content-Type: video/mp4` and `Accept-Ranges: bytes`. | **Pass** | 2026-11-15 |
| **QC-004** | **PDP Experience** | Image Gallery & Zoom | Visit `/product/cipher-mocha-mousse-women-sneaker/`, click thumbnails, click main image | Main photo switches instantly; zoom modal opens with high-res original without console errors. | **Pass** | 2026-11-15 |
| **QC-005** | **PDP Cleanup** | Angle Video Absence | Search DOM of all 5 PDPs for `<div class="angle-film">` | Zero occurrences found; no video element appears above description. | **Pass** | 2026-11-14 |
| **QC-006** | **Validation** | Mandatory Size Selection | Visit PDP, click "Buy now" without choosing an EU size | Browser prevents submission, prompts user to select a size (EU 37–41). | **Pass** | 2026-11-15 |
| **QC-007** | **Cart & Checkout** | Buy Now Auto-Redirect | Select EU 39 on any sneaker, click "Buy now" | Item added to WooCommerce session; user redirected instantly to `/checkout/`. | **Pass** | 2026-11-15 |
| **QC-008** | **Validation** | Required Phone Field | On `/checkout/`, enter name and address but leave phone blank, click submit | WooCommerce blocks submission with error: *"Phone is a required field."* | **Pass** | 2026-11-15 |
| **QC-009** | **Payments** | Razorpay Modal Launch | On `/checkout/`, fill valid details and submit to place order | Razorpay modal overlay opens with Coreve branding, amount in INR, and UPI/Card options. | **Pass** | 2026-11-15 |
| **QC-010** | **Business Rule** | COD Disabled | Inspect available payment gateways at `/checkout/` | Only Razorpay is available. Cash on Delivery is strictly absent. | **Pass** | 2026-11-11 |
| **QC-011** | **Sequencing** | Order Numbering (`KLE99817`+) | Place order or run WP-CLI `wc_create_order()` | Order number returns format `KLExxxxx` sequentially without database collision. | **Pass** | 2026-11-12 |
| **QC-012** | **Notifications** | HTML Order Alert to Admin | Complete order or trigger `coreve_send_order_notification_email()` | Detailed HTML email delivered to `lenee@coreve.in` via `smtp.zoho.in:465` with zero bounce. | **Pass** | 2026-11-11 |
| **QC-013** | **Email Relay** | Zoho SMTP Authentication | Run `sudo -u www-data wp eval 'var_dump(wp_mail("lenee@coreve.in", "Test", "Body"));'` | Output `bool(true)`. `/var/log/mail.log` confirms TLS handshake with Zoho MX. | **Pass** | 2026-11-11 |
| **QC-014** | **SEO** | Search Engine Directives | Inspect `<meta name="robots">` on home page | Contains `max-image-preview:large, max-snippet:-1`. Does NOT contain `noindex`. | **Pass** | 2026-11-15 |
| **QC-015** | **AI Engine SEO** | LLM Context Files | Fetch `https://coreve.in/llms.txt` and `https://coreve.in/llms-full.txt` | Both return HTTP 200 with text/plain content. | **Pass** | 2026-11-14 |
| **QC-016** | **Security** | Sensitive File Access | Attempt to curl `https://coreve.in/wp-config.php` or `https://coreve.in/.git/` | Returns HTTP 403 Forbidden or 404 Not Found. Never outputs source code. | **Pass** | 2026-11-15 |
| **QC-017** | **Security** | No Secrets in Git/Repo | Run `grep -rn "DB_PASSWORD" /Users/lenee/coreve/project-memory/` | Returns zero instances of plain-text passwords or API secrets. | **Pass** | 2026-11-18 |
| **QC-018** | **Mobile UX** | Mobile Drawer & Viewport | Load site in mobile viewport (<700px) | Navigation collapses to hamburger menu; hero scales responsively without horizontal scroll. | **Pass** | 2026-11-15 |

---

## 2. Command-Line Automated Smoke Test Script

Run this bash snippet from the local machine or server to perform an instant health check:

```bash
#!/usr/bin/env bash
set -e

echo "=== COREVE PLATFORM SMOKE TEST ==="
DOMAIN="https://coreve.in"

# 1. Homepage HTTP 200
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$DOMAIN/")
if [ "$HTTP_CODE" -eq 200 ]; then
  echo "✔ Homepage status: 200 OK"
else
  echo "✘ Homepage failed with status $HTTP_CODE" && exit 1
fi

# 2. Check for staging preview text leak
LEAK=$(curl -s "$DOMAIN/" | grep -iE "Staging preview|Shopping disabled|Design preview" || true)
if [ -z "$LEAK" ]; then
  echo "✔ No staging/preview text leaks on live homepage"
else
  echo "✘ Found staging text leak on homepage!" && exit 1
fi

# 3. Check PDP video removal
VIDEO_LEAK=$(curl -s "$DOMAIN/product/zivana-winterberry-girl-sneaker-shoe/" | grep -i "angle-film" || true)
if [ -z "$VIDEO_LEAK" ]; then
  echo "✔ Product video successfully removed from PDP"
else
  echo "✘ Product video still present on PDP!" && exit 1
fi

# 4. Check LLMS.txt
LLMS_CODE=$(curl -s -o /dev/null -w "%{http_code}" "$DOMAIN/llms.txt")
if [ "$LLMS_CODE" -eq 200 ]; then
  echo "✔ llms.txt accessible: 200 OK"
else
  echo "✘ llms.txt failed with status $LLMS_CODE" && exit 1
fi

echo "=== ALL SMOKE TESTS PASSED ==="
```
