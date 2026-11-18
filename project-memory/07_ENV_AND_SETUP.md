# 07 - Environment, Setup & Deployment

> **SECURITY NOTICE:**
> Never store raw passwords, secret keys, or customer PII in any documentation file. All secrets must be referenced by variable/constant names only.

---

## 1. Environment Variables & Constants

Configuration settings are stored in `/var/www/coreve.in/wp-config.php`.

### Database Constants
| Constant Name | Description | Example / Allowed Values |
| :--- | :--- | :--- |
| `DB_NAME` | MySQL database name | `coreve_db` |
| `DB_USER` | MySQL database user | `coreve_user` |
| `DB_PASSWORD` | MySQL database user password | `[REDACTED_SECRET]` |
| `DB_HOST` | Database host and port | `localhost` |
| `DB_CHARSET` | Character encoding | `utf8mb4` |

### Zoho SMTP Constants (`coreve-smtp.php`)
| Constant Name | Description | Value / Purpose |
| :--- | :--- | :--- |
| `COREVE_SMTP_HOST` | Outgoing SMTP server hostname | `smtp.zoho.in` |
| `COREVE_SMTP_PORT` | Outgoing SMTP port | `465` (SSL) or `587` (TLS) |
| `COREVE_SMTP_SECURE` | Encryption protocol | `ssl` (for 465) or `tls` (for 587) |
| `COREVE_SMTP_USER` | SMTP username | Sender email (e.g. `lenee@coreve.in`) |
| `COREVE_SMTP_PASS` | Zoho application-specific password | `[REDACTED_SECRET]` |
| `COREVE_SMTP_FROM` | Default "From" email address | `lenee@coreve.in` |
| `COREVE_SMTP_FROM_NAME` | Default "From" display name | `Coreve Orders` |

### Razorpay Gateway Settings
* Stored in WordPress database options table (`woocommerce_razorpay_settings`).
* Keys: `key_id`, `key_secret` (configured in WP Admin > WooCommerce > Settings > Payments > Razorpay).

---

## 2. Server Infrastructure & Services

* **OS:** Ubuntu 24.04 LTS (x86_64)
* **IP Address:** `129.121.132.35`
* **SSH Access:** `ssh -i <path_to_private_key> root@129.121.132.35`
* **Services:**
  - Nginx: `systemctl status nginx`
  - PHP 8.3 FPM: `systemctl status php8.3-fpm`
  - MariaDB/MySQL: `systemctl status mariadb`
  - Postfix MTA: `systemctl status postfix`

### Useful Maintenance Commands
```bash
# Check Nginx configuration syntax
nginx -t

# Reload Nginx without dropping connections
systemctl reload nginx

# Clear Nginx FastCGI microcache
rm -rf /var/cache/nginx/coreve/* && systemctl reload nginx

# Restart PHP-FPM
systemctl restart php8.3-fpm

# Check mail transfer logs in systemd journal
journalctl -u postfix@-.service -n 50 --no-pager

# Run WP-CLI commands as web user
sudo -u www-data wp --path=/var/www/coreve.in <command>
```

---

## 3. Deployment & Code Synchronization

### Repository Hierarchy
* **Local Git Working Tree:** `/Users/lenee/coreve/`
* **Production Webroot:** `/var/www/coreve.in/`

### Deployment Flow: Local to Remote
```bash
# 1. Sync theme updates
rsync -avz -e "ssh -i ~/.gemini/antigravity/scratch/ssh_keys/id_ed25519" \
  /Users/lenee/coreve/wp-content/themes/coreve/ \
  root@129.121.132.35:/var/www/coreve.in/wp-content/themes/coreve/

# 2. Sync storefront updates
rsync -avz -e "ssh -i ~/.gemini/antigravity/scratch/ssh_keys/id_ed25519" \
  /Users/lenee/coreve/wp-content/coreve-storefront/ \
  root@129.121.132.35:/var/www/coreve.in/wp-content/coreve-storefront/

# 3. Sync mu-plugins
rsync -avz -e "ssh -i ~/.gemini/antigravity/scratch/ssh_keys/id_ed25519" \
  /Users/lenee/coreve/wp-content/mu-plugins/ \
  root@129.121.132.35:/var/www/coreve.in/wp-content/mu-plugins/

# 4. Set correct ownership and flush FastCGI cache on server
ssh -i ~/.gemini/antigravity/scratch/ssh_keys/id_ed25519 root@129.121.132.35 \
  "chown -R www-data:www-data /var/www/coreve.in/wp-content && rm -rf /var/cache/nginx/coreve/* && systemctl reload nginx"
```

### Pulling Live Updates: Remote to Local
```bash
rsync -avz -e "ssh -i ~/.gemini/antigravity/scratch/ssh_keys/id_ed25519" \
  --exclude="uploads" --exclude="cache" \
  root@129.121.132.35:/var/www/coreve.in/wp-content/ \
  /Users/lenee/coreve/wp-content/
```
