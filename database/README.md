# Coreve database export

`coreve-deploy.sql` is a full database export with every internal URL already
rewritten from `http://localhost:8090` (the local dev environment) to
`https://coreve.in` (the live domain), using `wp search-replace` so
PHP-serialized data (widget settings, theme mods, cached asset manifests,
etc.) was rewritten correctly rather than corrupted by a blind text
replacement. Transients were cleared before export since they're
regenerable cache data, not real content.

Contains all 6 real WooCommerce products (5 sneakers as variable products
with EU 37–41 size variations, plus the made-to-order bridal item), all 16+
content pages, the Coreve theme's customizer settings (site icon, custom
logo), and WooCommerce configuration (COD payment gateway with the ₹99
handling fee, India-only selling/shipping restriction).

## To import on the live server

1. Install WordPress + WooCommerce on the live host first (same versions
   this was built against: WordPress with PHP 8.3, WooCommerce 11.1.0).
2. Copy `wp-content/themes/coreve` and `wp-content/uploads` from this repo
   to the live server's `wp-content/`.
3. Create the database and import:
   ```
   mysql -u <user> -p <database_name> < database/coreve-deploy.sql
   ```
4. Update `wp-config.php` on the live server with real database
   credentials and fresh `AUTH_KEY`/`SECRET_KEY` salts (generate new ones
   at https://api.wordpress.org/secret-key/1.1/salt/ — don't reuse dev
   values). These live in `wp-config.php`/environment variables, not the
   database, so they aren't in this export.
5. Activate the Coreve theme and confirm WooCommerce is active.
6. If the live URL differs from `https://coreve.in` for any reason (e.g.
   staging first), run `wp search-replace` again with the actual URL
   before going live — don't hand-edit the SQL file.
7. Re-check: `woocommerce_coming_soon` should be `no` (this export already
   has it set correctly, but WooCommerce sometimes resets it on a fresh
   activation) — the live site should not show a "coming soon" wall.
8. Confirm SSL/HTTPS is actually configured on the live host to match the
   `https://` URLs baked into this export — if the live site only has HTTP
   available, assets and links will break.

## Not included here (see PROGRESS.md for full context)

- `.env` (database credentials, gitignored) — the live host needs its own
- A real payment gateway beyond COD — Razorpay or similar still needs to be
  configured with real API keys if online payment is wanted
- Real per-size stock numbers — all sizes are currently marked in-stock as
  a placeholder
- A GA4/GTM ID — the analytics event scaffold is built but has nowhere to
  send data yet
