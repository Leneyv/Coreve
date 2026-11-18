# 07 — Environment & Setup Guide

## 1. System Requirements & Prerequisites
- **OS:** Ubuntu 22.04 LTS or 24.04 LTS (x86_64)
- **Python:** Python 3.12+ with `python3-venv` and `python3-pip`
- **Node.js:** Node.js v24+ (self-hosted in `/opt/myclone/node24`)
- **Database:** PostgreSQL 16+ running on `localhost:5432`
- **Reverse Proxy:** Nginx with Let's Encrypt Certbot
- **Process Manager:** Systemd

---

## 2. Directory Layout & Setup from Scratch

```bash
# 1. Create base directories
sudo mkdir -p /opt/myclone/app
sudo mkdir -p /var/lib/myclone/ops
sudo mkdir -p /var/lib/myclone/n8n
sudo mkdir -p /etc/myclone

# 2. Create unprivileged system user
sudo useradd -r -s /bin/false -d /var/lib/myclone myclone
sudo chown -R myclone:myclone /var/lib/myclone
sudo chown -R root:myclone /opt/myclone
sudo chmod -R 775 /opt/myclone/app

# 3. Initialize Python 3.12 Virtual Environment
cd /opt/myclone
python3.12 -m venv venv
./venv/bin/pip install --upgrade pip
./venv/bin/pip install -r app/requirements.txt
./venv/bin/playwright install chromium firefox --with-deps
```

---

## 3. Database Initialization

```bash
# PostgreSQL Setup
sudo -u postgres psql -c "CREATE USER myclone WITH PASSWORD 'SANITISED_PASSWORD';"
sudo -u postgres psql -c "CREATE DATABASE myclone OWNER myclone;"
sudo -u postgres psql -d myclone -f /opt/myclone/app/schema_base.sql
sudo -u postgres psql -d myclone -c "GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO myclone;"
sudo -u postgres psql -d myclone -c "GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO myclone;"

# SQLite Celine Database Setup
cd /opt/myclone/app
/opt/myclone/venv/bin/python3 setup_celine_tables.py
sudo chown myclone:myclone celine_customers.db
sudo chmod 664 celine_customers.db
```

---

## 4. Systemd Service Daemons

The control plane runs four systemd daemons.

### A. Web API Server (`/etc/systemd/system/myclone.service`)
```ini
[Unit]
Description=Corive Tech private sales control plane
After=network.target postgresql.service

[Service]
User=myclone
Group=myclone
WorkingDirectory=/opt/myclone/app
EnvironmentFile=/etc/myclone/runtime.env
ExecStart=/opt/myclone/venv/bin/gunicorn --bind 127.0.0.1:5679 --workers 1 --threads 3 --timeout 60 app:app
Restart=on-failure
NoNewPrivileges=true
PrivateTmp=true
ProtectSystem=strict
ProtectHome=true
MemoryMax=300M

[Install]
WantedBy=multi-user.target
```

### B. Durable Worker (`/etc/systemd/system/myclone-worker.service`)
```ini
[Unit]
Description=Corive Kavya durable sales workflow worker
After=network.target postgresql.service myclone.service

[Service]
User=myclone
Group=myclone
WorkingDirectory=/opt/myclone/app
EnvironmentFile=/etc/myclone/runtime.env
Environment=HOME=/var/lib/myclone
Environment=PYTHONDONTWRITEBYTECODE=1
Environment=PLAYWRIGHT_BROWSERS_PATH=/opt/myclone/browsers
ExecStart=/opt/myclone/venv/bin/python worker.py
Restart=on-failure
RestartSec=10
NoNewPrivileges=true
PrivateTmp=true
ProtectSystem=strict
ProtectHome=true
ReadWritePaths=/var/lib/myclone/ops
MemoryMax=1600M
Nice=5

[Install]
WantedBy=multi-user.target
```

### C. n8n Engine (`/etc/systemd/system/myclone-n8n.service`)
Binds n8n workflow engine to `127.0.0.1:5678` with proxying under `/workflow/`.

### D. Escalation Checker (`/etc/systemd/system/myclone-escalation.service`)
Oneshot timer service checking 2-hour quotation review SLA.

### Daemon Management Commands:
```bash
sudo systemctl daemon-reload
sudo systemctl enable --now myclone.service myclone-worker.service myclone-n8n.service
sudo systemctl restart myclone.service myclone-worker.service
```

---

## 5. Environment Variable Reference

All operational secrets and endpoints are managed in `/etc/myclone/runtime.env`.
> [!IMPORTANT]
> NEVER COMMIT OR PASTE ACTUAL SECRETS IN MEMORY OR REPOSITORY FILES.

| Variable Name | Required | Purpose & Description |
| :--- | :---: | :--- |
| `GEMINI_API_KEY` | Yes | Google Gemini API key for structured inference and natural language extraction |
| `GEMINI_MODEL` | Yes | Selected model identifier (e.g. `gemini-2.5-flash` or `gemini-3.8-flash`) |
| `OUTREACH_ENABLED` | Yes | Boolean flag (`true`/`false`) toggling automated outbound dispatches |
| `SARVAM_API_KEY` | Yes | API authentication key for Sarvam conversational voice telephony |
| `SARVAM_ORG_ID` | Yes | Sarvam organization account ID |
| `SARVAM_WORKSPACE_ID` | Yes | Sarvam workspace scope ID |
| `SARVAM_AGENT_ID` | Yes | Pre-configured Sarvam voice agent configuration ID |
| `SARVAM_CALLER_ID` | Yes | E.164 outbound caller ID assigned by Sarvam (`+918064277369`) |
| `SARVAM_CONNECTION_ID`| Yes | Telecom SIP trunk or telephony routing ID |
| `RAZORPAY_KEY_ID` | Yes | Public key ID for Razorpay API |
| `RAZORPAY_KEY_SECRET` | Yes | Private API secret key for Razorpay API |
| `ESCALATION_PRIMARY_PHONE` | Yes | E.164 phone number for high-priority quote alerts |
| `ESCALATION_BACKUP_PHONE` | Yes | Backup phone number for unresolved escalations |
| `OWNER_NOTIFICATION_EMAIL`| Yes | Recipient email address for quotations and system alerts |
| `SENDER_EMAIL` | Yes | Outbound SMTP sender email address |

---

## 6. Nginx Reverse Proxy & HTTP Basic Auth

The application is served securely over HTTPS via Nginx:
- Port 5679 is proxied for all application routes.
- HTTP Basic Auth protects UI endpoints using `/etc/nginx/myclone.htpasswd`.
- Webhooks `/api/whatsapp/webhook`, `/api/webhook/sarvam/*`, `/api/razorpay/webhook`, and `/api/celine/webhook` have `auth_basic off;` to allow incoming third-party callbacks.
- Static assets and webhooks enforce `Cache-Control: no-store` and standard security headers.
