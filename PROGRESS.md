# Coreve Full Rebuild — Progress Tracker

Source brief: "Principal E-Commerce Architect" 30-part CRO/brand rebuild (pasted in full twice by user).
Read this file first on any resume (new session, usage-limit reset, "continue"). Resume from the first `[ ]`/`[~]` item. Never redo `[x]` items — check their evidence note instead.

Status legend: `[ ]` pending · `[~]` in progress · `[x]` done (evidence noted)

---

## PHASE 0 — CONFIRMED FACTS REGISTRY (do not re-derive, do not contradict)

Verified 2026-09-13 via wp-cli against the live WooCommerce DB and the real scraped page content (About Us, Shipping Policy, Refab Warranty). This is the *only* source of truth for claims on the site — anything not here is unconfirmed and must use neutral/TBC copy.

- **Sizes**: EU **37–41** only (NOT 36–41 — brief assumed 36, real size chart starts at 37).
- **Products**: 6 total, all currently `simple` products (no WooCommerce variations yet): Cipher Mocha Mousse, Ventra Blue Granite, Zivana Winterberry, Elara Tendril, Nyro Eclipse (₹8,995 → ₹6,995, 22% off) + "Coreve Her Day Handmade Custom" bridal item (₹14,995, made-to-order, doesn't fit the sneaker/wedge narrative — treat as a separate category, keep out of the main "Collection" CRO narrative).
- **Payment gateways**: NONE are actually configured (`woocommerce_cod_settings`, `_bacs_settings`, `_cheque_settings` don't exist — only a leftover `woocommerce_paypal_settings`). The site cannot currently take a real payment. This must be fixed as infra work, and no payment method should be *implied as live* in copy until configured.
- **COD is a confirmed real policy** (from real Shipping Policy page): "Cash on Delivery (COD): Available at an additional handling fee of ₹99 per order." → safe to state in copy, but the gateway itself still needs configuring (see Phase 1).
- **Shipping (real, from Shipping Policy page)**: Processing 1–2 business days. Metro 2–5 business days. Other cities 4–7 business days. Remote areas 7–10 business days. Free standard shipping pan-India. Undeliverable/returned orders: refund (excl. COD fee) within 7 working days.
- **International shipping (real)**: India-only today; Middle East "will be announced soon" — this directly and legitimately supports the Part 13/28 India→Middle East narrative. Not an invented claim.
- **Returns/exchange (real)**: within 7 days, unused/unworn/unwashed, original packaging + tags.
- **Refab warranty (real, more specific than what's currently on the site)**: Exclusive to **Coreve Queens Club members**. Window is **6–12 months** post-purchase (not "any time after 6 months"). Requires registering the sneaker's serial number in the Queens Club — without registration, no claim possible. Covers upper/sole restoration from normal wear; excludes physical damage/misuse/abuse. Approval is at brand discretion. **Current product-page copy overstates this as unconditional — needs tightening (Phase 3).**
- **Contact (real)**: WhatsApp/phone +91 93639 36665, hello@coreve.in, 17 2nd Floor 7th Main Road Indiranagar Bengaluru Karnataka 560038, GST 29AAMCC4007A1Z1.
- **Kerala connection is real** — found on the live site's own "In the News" content: Coreve's own blog ties its story to Kerala ("Kerala isn't just giving India blockbuster movies... stories from Kerala don't just belong to..."). Safe to use "Born in Kerala" framing (Part 13/28) — this was wrongly flagged as unconfirmed in earlier inspection; correcting that here.
- **Registered/compliance address is Bengaluru** (GST address) — both facts coexist fine: founder/brand story rooted in Kerala, registered office in Bengaluru. Don't force a contradiction.
- **Real brand copy already exists and should be reused, not reinvented**, found in the live site's own "related articles" content: an article titled *"Why are Indian women still settling for men's sneakers?"* opening with *"Walk into any sneaker store in India and here's the truth: women don't really have a choice. What they get is either a 'shrunk-down men's sneaker' or a unisex pair..."* — this is the brand's own authentic voice for Part 6/Section 4 ("The Cultural Truth"). Use/adapt this real line instead of writing a new one from scratch.
- **Real Instagram social proof exists**: customer/creator posts embedded on the live site — Ammu Nair (@ammunair_), Chandru Kaustuba (@chandru_kaustuba), Durga S (@durga_surendran), Soumya Thomas (@soumya_thomas__), each shown with 5 stars. Only the handles were captured, not the actual post URLs/permalinks — **need to re-fetch from live coreve.in to get real embed URLs before using these** (Phase 6). Do not fabricate the post content.
- **5 written testimonials already on the theme** (Keerti Singh, Anjali Bhandari, Celine, Fathima Ibrahim, Sara Sodhi) were migrated verbatim from the live site during the original rebuild — these are real, not fabricated, and remain safe to use.
- **"EU standard leather"** — this exact vague-but-real phrase appears in the live site's own FAQ copy ("sneakers shoes for women made from EU standard leather") — safe to reuse verbatim, but do not upgrade it to a more specific unverified claim like "full-grain leather."
- **"Ortholite" insole** — not found in any scraped text, but a downloaded product image filename from the live CDN is literally `ortholite_Insole_1.webp`, which is reasonably strong evidence it's a real material used in the product (their own asset naming). Treat as confirmed-but-unverified-in-writing; keep using it, don't expand into unverified performance claims about it.
- **65mm heel / 25mm toe wedge height** — confirmed in real About Us copy ("The 65mm/25mm Biomechanical Wedge"). Matches the product spec table already on product pages.
- **No confirmed**: ratings/ review counts as numbers, certifications, ISO/EU-grade claims beyond the leather line above, ARIA of "India's First" (not found in any real scraped copy — the current homepage hero badge "India's First Sneaker made for Women" is UNVERIFIED and should be softened/removed — flagged as an existing over-claim to fix, not something to add more of).
- **Design palette conflict**: the current live theme (as of the last rebuild) uses a dark near-black + gold "Liquid Glass" palette (Rubik/Nunito Sans fonts, kept — those satisfy the "rounded font" requirement from a separate earlier request and don't conflict with this brief). This brief asks for **ivory/alabaster/charcoal/muted-stone warm luxury neutrals** — a real, deliberate palette change. Fonts stay; palette gets reworked (Phase 1).

---

## PHASE 1 — FOUNDATION (blocks most other phases; do this first)

- [x] Re-verify current live homepage/product-page state — used the desktop + true-375px Puppeteer screenshots already captured earlier this session (unchanged since); no need to reshoot identical content.
- [x] Convert all 5 sneaker products from `simple` to `variable` WooCommerce products with a real global `Size` attribute (`pa_size`, terms 37–41), 5 published variations each, same price as parent. Verified via wp-cli (`product_type` term = `variable` on all 5, variations 150-179 range published) and confirmed the native WooCommerce variation `<select name="attribute_pa_size">` renders on the live product page. Hit and fixed a real bug: `wc_get_product()` returned a stale `WC_Product_Simple` right after changing the `product_type` term in the same request, so saving it silently reverted the type back to "simple" — fixed by instantiating `WC_Product_Variable` directly. Script kept at `/tmp/convert_to_variable.php` (not in the repo, one-time migration). Still a plain `<select>` dropdown, not the tappable pill UI the brief wants — that's a front-end task in Phase 2/3, backend is ready for it.
- [x] Stock: all 5 size variations per product set to `stock_status = instock`, `manage_stock = false` — placeholder per user decision, no real per-size numbers exist yet.
- [x] Configure the COD payment gateway for real (₹99 handling fee, matches confirmed policy) — user decided COD-only for now, no online gateway. Enabled `woocommerce_cod_settings` (only active gateway, verified via `WC()->payment_gateways()->get_available_payment_gateways()`), added the ₹99 fee via a `woocommerce_cart_calculate_fees` hook in `functions.php` (`coreve_cod_handling_fee`) since core WooCommerce COD has no built-in fee field.
- [x] Rework the design-system tokens in `style.css` from dark+gold to warm ivory/alabaster/charcoal/muted-stone: `--color-background`/`--color-muted`: #F8F4EC/#EFE9DD (ivory/stone), `--color-primary`/`--color-foreground`: #26221D/#201C17 (charcoal), `--color-secondary`/`--color-muted-foreground`: #6B6255 (stone), `--color-accent`: #8C4A2C (restrained clay/terracotta, replacing gold — checked for contrast against both ivory and white). Also changed the hero background from a dark charcoal gradient to `var(--color-muted)` (light stone) for consistency — a dark hero would have clashed with the new accent color's contrast calibration (accent is tuned for light backgrounds). Header nav stays charcoal glass as the one deliberate dark UI element (common pattern, not scattered darkness). Updated hardcoded rgba(28,25,23,*) glass-overlay values to match the new primary rgb(38,34,29). Verified via true-390px Puppeteer screenshot — looks cohesive, terracotta accent reads clearly on ivory.
- [x] 8pt spacing — audited the existing `--space-*` scale (4/8/16/24/32/48/64px): already effectively an 8pt grid, no rework needed beyond documenting it.
- [x] 390px breakpoint added explicitly (tighter gutters/type scale below the existing 480px phone breakpoint). Verified with a true Puppeteer 390px-viewport screenshot (not a resized window) — renders cleanly, no overflow.
- [x] Built AJAX cart infrastructure on the WooCommerce Store API (`/wp-json/wc/store/v1/cart`), enqueued as `assets/js/store-api-cart.js` (`window.CoreveCart.addItem/updateItem/removeItem/getCart`, nonce rotation handled, dispatches `coreve:cart-updated` custom event that keeps `.cart-count` badges in sync). **Found and fixed a second real bug while testing end-to-end via curl**: the Phase-1 variable-product script had saved each variation's size under post meta key `attribute_size` instead of the `attribute_pa_size` WooCommerce/Store API actually requires for a global taxonomy attribute — this silently broke size matching entirely (Store API reported every variation's size as `null` and add-to-cart failed with "No matching variation found"). Fixed via a one-time migration script re-keying all 25 variations; verified with a real curl add-item/remove-item round trip against a live cart session. This module is infra-only for now — no product-card/product-page UI calls it yet, that's Phase 2/3.
- [x] The unverified "India's First Sneaker made for Women" hero badge claim will be resolved as part of the Phase 2 hero rewrite below (Section 3), not patched separately — avoids editing the same line twice.

## PHASE 2 — HOMEPAGE REBUILD (Part 6, 9 sections) — DONE, verified

Full rewrite of `front-page.php`, `header.php`, `functions.php` (added `coreve_collection_products()` data helper, updated `coreve_home_faqs()` with corrected facts), `style.css` (new component styles), `theme.js` (size-pill state, add-to-cart wiring, intelligent sticky header). Verified with a true 390px Puppeteer screenshot (full page, all sections) and a scripted functional test (see below).

- [x] Section 1 — Micro trust bar: "Women-first sneakers · 7-day size exchange · Cash on delivery available" (all confirmed real, in `header.php`)
- [x] Section 2 — Header nav updated to Collection/Why Coreve/Our Story/Size Guide (both the real WP menu and `coreve_fallback_menu()`); added intelligent sticky behavior (hides on scroll down past 120px, reveals on scroll up, respects reduced-motion via the existing global transition-duration override)
- [x] Section 3 — Hero: "She Was Never Meant to Fit Into His Shoe." + supporting line + Explore Coreve (→ /shop/) / Why Coreve? (→ #why-coreve anchor) CTAs. Removed the unverified "India's First Sneaker made for Women" badge entirely rather than replace it with another unverified claim. Single product image kept, no carousel.
- [x] Section 4 — "Why Is She Still Wearing His?" — lede paragraph adapted from the real brand copy found on the live site ("shrunk-down men's sneaker" line), plus an elegant two-card Generic-vs-Coreve comparison (not disparaging other brands)
- [x] Section 5 — "Start With Her." reveal + Shop the Collection CTA anchored to Section 6
- [x] Section 6 — Product Collection: built `coreve_collection_products()` to pull real per-size availability/pricing from the now-variable products; tappable EU 37–41 size pills (44px targets), "Choose your size first" inline error (not a silent failure), Add to Bag wired to the Phase-1 Store API client with a visible "Added ✓" state. **Verified end-to-end with a scripted Puppeteer test**: clicking Add to Bag with no size shows the error; selecting a size then adding succeeds and the header cart-count badge updates live via the `coreve:cart-updated` event (0 → 1, no page reload).
- [x] Section 7 — "She Wanted Sneakers. She Didn't Want to Give Up Height." using the confirmed 65mm heel / 25mm toe spec, explicitly framed as design philosophy, not a medical claim
- [x] Section 8 — Why Coreve Feels Different: 4 pillars (Women-First Design / Elevated Comfort / Premium Craft — "EU standard leather" real phrase reused / Made for Her Life)
- [x] Added a "What She's Saying" real-testimonials section (Part 29's conversion journey explicitly calls for a "see genuine social proof" step here, between comfort/design and size-anxiety resolution) — reuses the 5 already-real migrated testimonials, nothing fabricated
- [x] Section 9 — "Still Wondering If Coreve Is For You?": rewrote `coreve_home_faqs()` with corrected facts (real delivery windows, ₹99 COD fee, the actual 6–12 month Queens-Club-gated Refab terms, real care instructions) + link to Size Guide
- [x] Tightened the footer tagline, which still had the old Gen-Z-slang brand voice ("Drip", "boss energy") left over from the earlier rebuild — clashed with Part 26's more restrained voice direction; rewritten to match, no new facts introduced

**Not yet done / carried to later phases**: the cart drawer itself (Section 6 button currently gives inline success feedback but there's no slide-in drawer yet — that's Phase 4). The hero's "Why Coreve?" anchor and the header nav's "Why Coreve" page are two different destinations (in-page scroll vs. dedicated page) — intentional for now (local context vs. global nav) but worth a consistency look during the Phase 10 CRO audit.

## PHASE 3 — PRODUCT PAGE REBUILD (Part 7–9) — DONE, verified

Built a custom `single-product.php` (theme-root override, WooCommerce's standard hook point) for the 5 sneaker products only; the made-to-order bridal item (id 143) falls back to WooCommerce's own default `content-single-product` template via `wc_get_template_part()` since it doesn't fit this sneaker/wedge narrative (per Phase 0 notes) — verified both render (200, no PHP errors).

- [x] Above-the-fold: WooCommerce's own gallery (`woocommerce_show_product_images()` — reused rather than rebuilt, preserves the zoom/lightbox theme support already declared), name, real average-rating stars + review count, price (`get_price_html()`), real short benefit statement (each product's existing `post_excerpt`), reused the same tappable size-pill component from Section 6, Size Guide link, Add to Bag + Buy Now (Buy Now adds to cart then redirects to `/checkout/` — verified it lands there).
- [x] Below-fold accordion: added `coreve_product_accordion_sections()` in `functions.php` — Why She'll Love It / Product Design / Fit & Comfort (size chart) / Materials / Care / Delivery / Size Exchange / FAQ, shared across the 5 products since specs are identical across the line. Real WooCommerce reviews render below via `comments_template()` (native, unstyled-but-functional — a polish item for later, not incorrect).
- [x] Tightened the Refab copy in the new Size Exchange tab to the real conditions (Queens Club registration required, 6–12 month window) — the old unconditional "every pair gets one free refurbish" phrasing only survives on the bridal item's fallback template, which is out of scope for this rebuild.
- [ ] Editorial gallery reorder per Part 8's suggested sequence — **not done**: doing this correctly requires visually identifying which of each product's 6–10 gallery images is the "sole/wedge shot" vs "on-foot" vs "detail" etc., which needs a manual look per product rather than a blind reorder. Left in the existing (already coherent) order; flagging for a follow-up pass rather than guessing.
- [x] Mobile sticky buy bar: product thumbnail/name/price/Add to Bag, appears once scrolled past the product hero, reserves layout space via `body:has(.sticky-buy-bar:not([hidden]))` so it never covers content. **Found and fixed a real bug during testing**: the show/hide threshold was calculated once at page load from `.product-hero`'s height, but WooCommerce's gallery script (zoom/flexslider) collapses a taller pre-JS stacked-image layout into the final compact gallery *after* that calculation ran, making the cached threshold wildly stale (~4755px instead of ~1379px) — fixed by recomputing the hero's position on every scroll event instead of caching it once. Verified visually and via a scripted scroll test.
- [x] **Found and fixed a second real bug**: clicking "Add to Bag" with no size selected showed the correct inline error, but then permanently disabled the button (stuck on "Adding…" forever) because the code unconditionally called `.finally()` on `addToCart()`'s return value, which is `undefined` in the no-size branch — calling `.finally()` on `undefined` throws, aborting the reset logic. Fixed by guarding on whether `addToCart()` actually returned a promise before chaining `.finally()`. Verified end-to-end: error-then-recover, successful add-to-cart, Buy Now redirect, and a homepage-collection-card regression check all pass with no console errors.
- [x] Moved the FAQ/accordion toggle JS out of an inline `<script>` in `front-page.php` (page-specific, wouldn't have run on the new product page) into `theme.js` as a global handler shared by both the homepage FAQ and the product-page accordion.

## PHASE 4 — CART DRAWER (Part 10–11)

- [ ] Slide-in cart drawer on Add to Bag (no page reload), shows product/size/qty/price/subtotal, Checkout CTA
- [ ] Checkout-anxiety trust content visible in drawer/checkout: size help, exchange info, real delivery timelines, payment security, COD, WhatsApp support link

## PHASE 5 — NEW PAGES (Part 12, 13, 28)

- [ ] "Why Coreve" story page: Problem → Realisation → Response → Future structure, founder mentioned but not dominant
- [ ] India → Middle East page/section: "Born in Kerala. Built for India. Designed to travel." + real "international shipping coming soon" fact + Abaya Sneaker concept (positioned as vision/story, NOT a purchasable product — none exists yet) + UAE/Saudi/GCC framing

## PHASE 6 — TRUST, FAQ, SOCIAL PROOF (Part 14–15)

- [ ] Re-fetch live coreve.in to recover real Instagram post URLs for Ammu Nair / Chandru Kaustuba / Durga S / Soumya Thomas before embedding them (don't fabricate post content)
- [ ] FAQ page/section using only verified answers (reuse real FAQ content already extracted, tightened per Phase 0 corrections — e.g. Refab conditions, no COD-as-universal-default)
- [ ] Excellent standalone Size Guide, linked from product page, product cards, cart, sticky buy bar

## PHASE 7 — SEO / AEO (Part 16)

- [ ] Unique SEO title/meta description/H1/alt text per product
- [ ] Product schema, Organization schema, Breadcrumb schema, FAQ schema (only on pages with real FAQ content)
- [ ] AEO-structured Q&A content: what is Coreve / who is it for / what's a women-first sneaker / what's an Abaya Sneaker

## PHASE 8 — ANALYTICS (Part 23)

- [ ] Event tracking scaffold: view_product, select_size, add_to_cart, remove_from_cart, begin_checkout, purchase, size_guide_open, faq_open, whatsapp_click, search, collection_view (check first whether GA4/GTM or any analytics is already installed — none confirmed yet, needs checking)

## PHASE 9 — ACCESSIBILITY, PERFORMANCE, POPUPS (Part 18–21, 24)

- [ ] Re-run true-viewport (Puppeteer, not resized-window) mobile audit after redesign: 44px targets, no hover-dependency, no horizontal overflow, keyboard nav, focus states, prefers-reduced-motion
- [ ] No popups on load; if any popup is added later, trigger only on exit-intent (desktop) or real scroll depth — none planned unless requested

## PHASE 10 — FINAL CRO AUDIT (Part 30)

- [ ] Walk all 6 personas end-to-end (Instagram 25yo / 35yo professional / height-seeker / size-anxious buyer / first-time Indian online buyer / GCC visitor) against the live rebuilt site and fix friction found
- [ ] Final before/after screenshot set (desktop + true mobile) for the record

---

## Decisions (locked in by user, 2026-09-13)

1. **Stock**: mark all EU 37–41 sizes in-stock as a placeholder for all 5 sneakers. Real numbers can correct this later.
2. **Payment gateway**: COD only for now (₹99 fee, matches confirmed policy). No online gateway yet — skip Razorpay/etc.
3. **Palette**: full repaint to ivory/alabaster/charcoal/stone, replacing the dark+gold theme entirely. Keep Rubik/Nunito Sans typography.
