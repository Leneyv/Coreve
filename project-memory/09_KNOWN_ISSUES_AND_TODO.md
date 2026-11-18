# 09 - Known Issues, Risks & TODO

Ordered by priority (P1 = Critical/High, P2 = Medium, P3 = Low).

---

## 1. Known Risks & Technical Debt

### [P1] Zoho SMTP Password Expiration / Password Change Risk
* **Description:** The Zoho SMTP relay is currently configured in `wp-config.php` using the account password provided by the user. If the account password is changed or multi-factor authentication (MFA) is reconfigured on the Zoho account, SMTP authentication will immediately fail, halting order notification emails.
* **Mitigation / Next Step:** Generate a dedicated permanent **Zoho Application-Specific Password** ("App Password") from Zoho Accounts Security console and replace the master password in `wp-config.php`.

### [P2] Custom Bridal Edition (`custom` / Product ID 143) Navigation
* **Description:** The storefront module currently maps the 5 primary production models (`cipher-mocha-mousse`, `ventra-blue-granite`, `zivana-winterberry`, `elara-tendril`, `nyro-eclipse`). The custom bridal version (`Coreve Her Day Handmade Custom Version` - ID 143) exists in WooCommerce and is linked in editorial copy, but does not have a dedicated card in the `coreve_sf_map()`.
* **Mitigation / Next Step:** Add custom mapping in `coreve_sf_map()` in `storefront.php` if the bridal edition should be directly accessible via the modern storefront renderer.

### [P3] FastCGI Cache Invalidation on Product Stock Updates
* **Description:** If inventory levels change, the FastCGI cache on `coreve.in` holds pages for up to 60 minutes for unauthenticated users unless explicitly cleared.
* **Mitigation / Next Step:** Add an automated hook in `functions.php` on `woocommerce_update_product` to automatically execute a cache purge or purge single URI via Nginx cache purge module.

---

## 2. Unfinished Work & Feature Roadmap

| Priority | Task Description | Target Milestone | Complexity |
| :--- | :--- | :--- | :--- |
| **P2** | **Automated Logistics Integration (Shiprocket / Delhivery):** Connect WooCommerce webhooks to Shiprocket for automated AWB and pickup scheduling. | Next Release | Medium |
| **P2** | **WhatsApp Notification Bridge:** Integrate a WhatsApp business API webhook (Interakt/Wati) to send tracking links to customers upon dispatch. | Next Release | Medium |
| **P3** | **Automated Review Collection Flow:** 14-day post-delivery email/WhatsApp asking verified buyers for photo reviews. | Future | Low |
| **P3** | **Abandoned Checkout Recovery:** Follow-up email sequence for shoppers who drop off on the checkout page. | Future | Low |
