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


---

### TEAM-20261120-RELEASE — connected Kavya team deployed
User requested a real supervised business-development team: factual website/CRM/automation discovery; technical SEO, speed and answer-readiness research; understandable role names and tool choices; Boss Chat plan before approval; owner quotation only; 50% mid-work and 50% handover, no advance.

Deployed 15 core roles with agent_key/tool_key/reports_to, runtime gates, actual run logs and supported tool selectors. Free HTTP/Chromium/evidence research is cached seven days; customer corrections bypass stale cached customer state. Research Team Leader passes findings to Kavya; communication summaries, scope, quote, milestones and technical handoff use the existing durable worker. Extra research specialists are executable and limited to five. No claim of independent paid LLMs or actual n8n execution: UI is n8n-style, runtime is Python/PostgreSQL.

Boss Chat now creates bounded durable plans and requires an explicit owner approval before dispatch; plan IDs/step keys prevent duplicate execution and delivery-dependent completion waits for provider results. Gemini restored as Brain after a zero-cost UI Rule Planner check. The demonstration plan was rejected; no active approved plan remains. Supported actions are listed in the release report; unsupported or ambiguous requests must clarify, not invent work or run arbitrary code.

Sarvam prompt updated and COMMITTED as v7; server selects v7 (v8 is the next draft). Confirm existing systems; explain scoped AI-agent routine work through connected channels/languages; short Malayalam/English turns and truthful evidence. Voice sound/interruption quality still requires native listening; 99%-human is not certified.

Scope handovers now include existing-system confirmations and research/unknowns. Natural owner replies work for new stored Message-ID threads: only Re: QUOTE REQUIRED from lenee@coreve.in with the first receiving Gmail authentication result passing aligned DMARC and matching current scope/revision. One explicit cost/timeline; negative or conflicting replies rejected; UID dedupe. Unknown GST stays unknown. Legacy signed approvals supported. Independent worker email intake sleeps 25s after each poll so research cannot block it.

Validation: 18 isolated team integration tests + 13 earlier provider-mocked regressions = 31 passing tests; JS syntax passed; real free public-site HTTP/Chromium audit succeeded; Chrome confirmed 15 tool selectors, tool switching, pending approval controls and rejected test-plan cleanup. Deployed source hashes match final local release. Web/worker active, public dashboard 401, global/category outreach pauses retained. Protected original source/database/config backup: /var/backups/myclone-team-20261120. No customer test outreach, payment links or paid LLM test generations initiated.

Remaining: SMS unconnected/paused; supported languages depend on provider; voice listening and real-provider end-to-end pilot unverified. Earlier uncertain calls need reconciliation; dedicated owner reminder agent and owner WhatsApp report template remain missing. Existing contact eligibility/WhatsApp permission gates remain; no automatic consent from a public listing. New free tools add no subscription, but existing Gemini/Sarvam/Meta/Razorpay billing still applies.

Canonical release report: 13_KAVYA_TEAM_RELEASE_2026-11-20.md. This entry supersedes older memory describing a decorative swarm, v6 selection, token-only new-thread approval, advance payment, or immediate Boss Chat task execution.


---

### BOSS-DASHBOARD-20261120 — deployed
New private Boss Dashboard: https://myclone.coreve.in/#boss. Full release/evidence: 14_KAVYA_BOSS_DASHBOARD_2026-11-20.md. Today/yesterday/7d/all-time business metrics, live work, 15 agent drilldowns, lead-stage baseline/forward tracking, costs, provider snapshots and direct billing links. Owner safety caps are enforced but unset; pauses and personal-chat protections preserved. 17 dashboard tests + 10 privacy regressions passed; Chrome verified UI. At release: 518 unique business leads, 140 connected customer calls; Sarvam org wallet snapshot -112.10 credits (not live/Kavya-only), Meta GREEN/TIER_2K, no approved templates, available service-fee data zero. Exact Meta remaining quota/wallet unknown; historical stage dates not invented. No payments or customer test outreach. Backup /var/backups/myclone-boss-dashboard-20261120. See canonical release for API/schema/cost boundaries.
