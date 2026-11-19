# 03 — Changelog

This changelog documents every code, database, configuration, and architectural change made to the project.
New entries must be appended to the END of this file. Existing entries are permanent and must never be deleted.

---

### CHG-001
- **Date and Time:** 2026-11-15 15:45:00 IST
- **Linked Command:** [CMD-001](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-001)
- **Type:** Added
- **What changed and why:** Created the baseline Corive Tech sales engine. Configured Flask application running under Gunicorn with PostgreSQL database, background worker loop for job execution, and basic UI.
- **Files touched:**
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/core.py`
  - `/opt/myclone/app/sales.py`
  - `/opt/myclone/app/providers.py`
  - `/opt/myclone/app/worker.py`
  - `/opt/myclone/app/schema_base.sql`
  - `/etc/systemd/system/myclone.service`
  - `/etc/systemd/system/myclone-worker.service`
  - Database: Initialized PostgreSQL database `myclone`.
- **How it was tested, and result:** Verified systemd services active via `systemctl status myclone myclone-worker`. Status: Tested (Passed).
- **Breakage risk:** Low; greenfield initial deployment.
- **How to undo:** Stop systemd services and drop PostgreSQL database.

---

### CHG-002
- **Date and Time:** 2026-11-17 06:10:00 IST
- **Linked Command:** [CMD-002](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-002)
- **Type:** Config / Refactored
- **What changed and why:** Implemented operating hours restriction (Monday-Saturday 10:00-18:30 IST) to prevent customer disruption outside business hours. Implemented 2-hour quotation review escalation alert service.
- **Files touched:**
  - `/opt/myclone/app/core.py`
  - `/opt/myclone/app/worker.py`
  - `/opt/myclone/app/app.py`
  - `/etc/systemd/system/myclone-escalation.service`
- **How it was tested, and result:** Checked current IST time evaluation in python REPL. Status: Tested (Passed).
- **Breakage risk:** Automated jobs created outside working hours stay queued in `jobs` table until next operating window.
- **How to undo:** Revert `core.py` and `worker.py` to their `.bak_hours` backups.

---

### CHG-003
- **Date and Time:** 2026-11-17 15:15:00 IST
- **Linked Command:** [CMD-003](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-003)
- **Type:** Upgraded / Performance
- **What changed and why:** Added SQL `LIMIT 100` constraints to all list queries in `app.py` and front-end rendering to prevent browser lockup on large datasets.
- **Files touched:**
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/templates/index.html`
- **How it was tested, and result:** Queried API endpoints with curl to confirm results capped at 100. Status: Tested (Passed).
- **Breakage risk:** Records beyond the 100th row are not visible unless filtered or paginated.
- **How to undo:** Remove `LIMIT 100` from SQL statements in `app.py`.

---

### CHG-004
- **Date and Time:** 2026-11-17 20:35:00 IST
- **Linked Command:** [CMD-004](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-004)
- **Type:** Added
- **What changed and why:** Added Celine Kamau BCL autonomous sales consultant agent for Brandcart Kenya retail e-commerce. Configured isolated SQLite database `celine_customers.db` and deep catalog specification.
- **Files touched:**
  - `/opt/myclone/app/celine.py`
  - `/opt/myclone/app/CELINE_KAMAU_BRAIN.md`
  - `/opt/myclone/app/setup_celine_tables.py`
  - `/opt/myclone/app/celine_customers.db`
  - `/opt/myclone/app/templates/index.html`
  - `/opt/myclone/app/app.py`
- **How it was tested, and result:** Executed SQLite setup script and verified tables via Python sqlite3 connector. Status: Tested (Passed).
- **Breakage risk:** None to Kavya or Coreve sales engine due to database isolation.
- **How to undo:** Unregister `celine_bp` blueprint in `app.py` and remove navigation tab.

---

### CHG-005
- **Date and Time:** 2026-11-17 21:15:00 IST
- **Linked Command:** [CMD-005](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-005)
- **Type:** Added
- **What changed and why:** Integrated Maya AI me personal operations agent. Created dedicated PostgreSQL tables for tasks, lead enrichment, and persistent reinforcement learning rules.
- **Files touched:**
  - `/opt/myclone/app/maya.py`
  - `/opt/myclone/app/templates/index.html`
  - `/opt/myclone/app/app.py`
  - Database: Added `maya_tasks`, `maya_d2c_leads`, `maya_learning_notes`, `maya_settings` tables in PostgreSQL.
- **How it was tested, and result:** Tested API endpoints `/api/maya/status` and `/api/maya/task`. Status: Tested (Passed).
- **Breakage risk:** Low; separate database tables and isolated blueprint prefix.
- **How to undo:** Unregister `maya_bp` in `app.py` and drop `maya_*` tables.

---

### CHG-006
- **Date and Time:** 2026-11-18 09:45:00 IST
- **Linked Command:** [CMD-006](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-006)
- **Type:** Audited
- **What changed and why:** Quality control audit performed across Kavya sales engine. Diagnosed reasons for paused execution, evaluated telephony token headers, and inspected Malayalam prompt boundaries.
- **Files touched:**
  - None (Read-only operational and code audit).
- **How it was tested, and result:** Inspected live database state and log output. Status: Tested (Passed).
- **Breakage risk:** None.
- **How to undo:** Not applicable.

---

### CHG-007
- **Date and Time:** 2026-11-18 10:30:00 IST
- **Linked Command:** [CMD-007](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-007)
- **Type:** Added
- **What changed and why:** Built Master Coexistence Inbox for phone number `+919400199817` to enable reading and replying to WhatsApp messages alongside the physical mobile device.
- **Files touched:**
  - `/opt/myclone/app/master_inbox.py`
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/templates/index.html`
- **How it was tested, and result:** Verified inbox UI rendered via browser inspection subagent. Status: Tested (Passed).
- **Breakage risk:** Coexistence number must have active Meta Cloud API credentials or Web fallback.
- **How to undo:** Unregister `master_inbox_bp` in `app.py` and remove view from template.

---

### CHG-008
- **Date and Time:** 2026-11-18 11:00:00 IST
- **Linked Command:** [CMD-008](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-008)
- **Type:** Upgraded
- **What changed and why:** Implemented "+ New Chat / Send Template" modal in Master Inbox. Added dual send buttons: `📱 Open in WhatsApp Web / App` (bypasses 24h Meta window restrictions) and `⚡ Send Meta Official Template` (via Cloud API).
- **Files touched:**
  - `/opt/myclone/app/templates/index.html`
  - `/opt/myclone/app/master_inbox.py`
- **How it was tested, and result:** Verified modal popup and button event handlers in browser. Status: Tested (Passed).
- **Breakage risk:** Low; client-side modal overlay with optional API trigger.
- **How to undo:** Remove `#master-new-chat-modal` markup and JS controller functions.

---

### CHG-009
- **Date and Time:** 2026-11-18 11:35:00 IST
- **Linked Command:** [CMD-009](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-009)
- **Type:** Added
- **What changed and why:** Created Kavya Boss Command Desk (`🗣️ Talk to Kavya`) allowing the owner to converse directly with Kavya, review pipeline health, and auto-persist directives into `kavya_training_notes`.
- **Files touched:**
  - `/opt/myclone/app/kavya_chat.py`
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/templates/index.html`
  - Database: Created `kavya_internal_chat` table with sequence permissions granted to `myclone`.
- **How it was tested, and result:** Sent message to Kavya via browser; verified response returned from Gemini and saved in `kavya_internal_chat`. Status: Tested (Passed).
- **Breakage risk:** Low; dedicated chat table and endpoint.
- **How to undo:** Unregister `kavya_chat_bp` in `app.py` and remove view from template.

---

### CHG-010
- **Date and Time:** 2026-11-18 12:00:00 IST
- **Linked Command:** [CMD-010](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-010)
- **Type:** Fixed
- **What changed and why:** Fixed visual bug where `#view-master-inbox` had inline `display: flex;` which prevented it from hiding when the user navigated to other agent tabs (Celine, Maya, Kavya).
- **Files touched:**
  - `/opt/myclone/app/templates/index.html`
- **How it was tested, and result:** Navigated between Master Inbox, Kavya, Celine, and Maya dashboards using browser inspector; captured clean screenshots confirming no visual overlap. Status: Tested (Passed).
- **Breakage risk:** None.
- **How to undo:** Revert `index.html` changes to commit before masking fix.

---

### CHG-011
- **Date and Time:** 2026-11-18 12:30:00 IST
- **Linked Command:** [CMD-011](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-011)
- **Type:** Fixed
- **What changed and why:** Resolved delayed follow-up call to Dr. Lenny (+919778215961). Identified `settings.paused = true` blocking worker loop. Unpaused system in database and updated `worker.py:followup()` to directly execute voice call for `callback_scheduled` leads.
- **Files touched:**
  - `/opt/myclone/app/worker.py`
  - Database: `settings` table `paused` column set to `false`.
- **How it was tested, and result:** Checked worker logs; verified worker heartbeat and confirmed callback voice trigger invoked without delay. Status: Tested (Passed).
- **Breakage risk:** Outbound calls will now execute whenever scheduled callback time is reached.
- **How to undo:** Revert `worker.py` and toggle `paused` back to `true` if pause is desired.

---

### CHG-012
- **Date and Time:** 2026-11-18 13:55:00 IST
- **Linked Command:** [CMD-012](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-012)
- **Type:** Fixed
- **What changed and why:** Eliminated cold opening greeting in Sarvam voice calls. Passed prior conversation transcript history into `dossier['prior_conversation_history']` and Sarvam `agent_variables`, with strict instruction to greet the contact by name and resume the conversation seamlessly.
- **Files touched:**
  - `/opt/myclone/app/providers.py`
- **How it was tested, and result:** Inspected dossier payload construction and verified Sarvam call payload structure. Status: Tested (Passed).
- **Breakage risk:** None; enrichments add context to outbound payload.
- **How to undo:** Revert `providers.py` to previous version.

---

### CHG-013
- **Date and Time:** 2026-11-18 14:15:00 IST
- **Linked Command:** [CMD-013](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-013)
- **Type:** Added / Governance
- **What changed and why:** Created permanent `/project-memory` directory on host with complete 12-file memory documentation, backfilled all historical instructions and changes, documented database schemas, system architecture, environment configurations, and QC checklists.
- **Files touched:**
  - `/opt/myclone/project-memory/00_README_FOR_AI.md`
  - `/opt/myclone/project-memory/01_PROJECT_OVERVIEW.md`
  - `/opt/myclone/project-memory/02_COMMAND_LOG.md`
  - `/opt/myclone/project-memory/03_CHANGELOG.md`
  - `/opt/myclone/project-memory/04_FEATURES_STATUS.md`
  - `/opt/myclone/project-memory/05_ARCHITECTURE.md`
  - `/opt/myclone/project-memory/06_DATABASE_AND_APIS.md`
  - `/opt/myclone/project-memory/07_ENV_AND_SETUP.md`
  - `/opt/myclone/project-memory/08_DECISIONS.md`
  - `/opt/myclone/project-memory/09_KNOWN_ISSUES_AND_TODO.md`
  - `/opt/myclone/project-memory/10_QC_CHECKLIST.md`
  - `/opt/myclone/project-memory/11_AGENT_AND_PROMPTS.md`
- **How it was tested, and result:** Validated file syntax, verified git repository tracking, and checked markdown rendering. Status: Tested (Passed).
- **Breakage risk:** None; documentation and memory files only.
- **How to undo:** Delete `/opt/myclone/project-memory`.

---

### CHG-014
- **Date and Time:** 2026-11-18 14:52:00 IST
- **Linked Command:** [CMD-014](file:///opt/myclone/project-memory/02_COMMAND_LOG.md#CMD-014)
- **Type:** Security / Config / Sync
- **What changed and why:**
  - Hardened Nginx configuration to block public access to `/project-memory`: Added `location ^~ /project-memory { deny all; return 404; }` inside the port 443 HTTPS and port 80 HTTP server blocks of `/etc/nginx/sites-available/myclone.coreve.in`.
  - Audited and established sync mechanism: Selected `rsync` over SSH (`root@129.121.132.35` with `~/.ssh/id_ed25519`) combined with local and remote Git commits. Chosen because it provides instant, encrypted, zero-third-party-token synchronization directly to `/opt/myclone/project-memory` on the VPS.
  - Performed full bidirectional sync of all 12 memory documents between the local machine and VPS, and committed changes to Git on both ends.
- **Files touched:**
  - `/etc/nginx/sites-available/myclone.coreve.in`
  - `/opt/myclone/project-memory/*` (all 12 files)
  - `/Users/lenee/coreve/project-memory/*`
- **How it was tested, and result:**
  - Tested `curl -s -k -I "https://myclone.coreve.in/project-memory/00_README_FOR_AI.md"`: Returned `HTTP/2 404`. Status: Tested (Passed).
  - Tested `curl -s -k -I -u "owner:..." "https://myclone.coreve.in/project-memory/00_README_FOR_AI.md"`: Returned `HTTP/2 404`. Status: Tested (Passed).
  - Tested `rsync -avz` over SSH: Transfer completed with 0 errors. Status: Tested (Passed).
  - Tested `git status` on VPS: Clean working tree after commit. Status: Tested (Passed).
- **Breakage risk:** None; memory documentation is internal and blocked from external web exposure.
- **How to undo:** Remove Nginx deny rule block and reload Nginx.

---

### CHG-015
- **Date and Time:** 2026-11-18 18:36:00 IST
- **Linked Command:** [CMD-015](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-015)
- **Type:** Added / Upgraded
- **What changed and why:**
  - Implemented the Kavya Agent Swarm & Visual Workflow (n8n-style) sub-tab in the dashboard.
  - Added PostgreSQL table `kavya_agents` to track swarm members, roles, stages, engines, prompts, and active statuses.
  - Built REST API blueprint `kavya_flow.py` (`GET`, `POST`, `PUT`, `DELETE`, `POST /toggle`) and registered in `app.py`.
  - Added visual workflow interface `#view-kavya-flow` with dark-grid canvas, stage icons, real-time metrics, prompt inspection modal, and dynamic add/remove node controls.
- **Files touched:**
  - `/opt/myclone/app/kavya_flow.py`
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/templates/index.html`
  - Database: PostgreSQL table `kavya_agents`
- **How it was tested, and result:**
  - Tested REST API with curl: verified list of 7 core agents, added custom agent, toggled active state, tested core agent deletion protection, and verified successful deletion of custom agent.
  - Tested browser UI via subagent: navigated to `#kavya-flow`, verified all 7 agent nodes rendered, submitted new agent via modal, verified 8th card appeared in canvas with updated count badges, clicked remove, and verified clean deletion and return to 7 agents. Status: Tested (Passed).
- **Breakage risk:** None; core agents are protected from deletion (`is_core = true`).
- **How to undo:** Unregister `kavya_flow_bp` in `app.py`, drop `kavya_agents` table or restore `index.html.bak_before_kavya_flow`.

---

### CHG-016
- **Date and Time:** 2026-11-19 15:20:00 IST
- **Linked Command:** [CMD-040](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-040)
- **Type:** Config / Upgraded
- **What changed and why:**
  - Activated Meta WhatsApp Cloud API credentials following user registration of developer app `Kavya Wassap` (App ID `1617063857127918`).
  - Queried Meta Graph API dynamically using the newly minted access token to retrieve the official Phone Number ID (`1352344067971735`) for `+91 94001 99817` (`Mycoreve`, WABA ID: `972757048782467`).
  - Updated `/var/lib/myclone/ops/integrations.json` with active `WA_PHONE_NUMBER_ID`, `WA_ACCESS_TOKEN`, `WA_BUSINESS_ACCOUNT_ID`, and set `MANUAL_WHATSAPP_MODE: "false"`.
- **Files touched:**
  - `/var/lib/myclone/ops/integrations.json`
- **How it was tested, and result:**
  - Tested direct Meta Cloud API dispatch via Python: returned HTTP 200 with message receipt ID `wamid.HBgMOTE5Nzc4MjE1OTYxFQIAERgUQ0VDQjFDNkJFNTdGRDQzMkY0RjAA`.
  - Tested Kavya endpoint `POST /api/inbox/6/send` via curl on VPS: returned `{"mode": "cloud_api", "ok": true, "sent": true}`. Verified row inserted in PostgreSQL `messages` table (id: 23, status: `sent`, direction: `out`). Status: Tested (Passed).
- **Breakage risk:** None. Automated fallback to WhatsApp Web remains intact if access tokens expire.
- **How to undo:** Revert `WA_PHONE_NUMBER_ID` and `WA_ACCESS_TOKEN` to empty in `integrations.json`.

---

### CHG-017
- **Date and Time:** 2026-11-19 15:27:00 IST
- **Linked Command:** [CMD-042](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-042)
- **Type:** Security / Config
- **What changed and why:**
  - Strictly enforced team and credential isolation between Celine (Brandcart Kenya) and Kavya (Corive Tech India).
  - Reverted SQLite table `celine_coexistence_settings` in `/var/lib/myclone/celine_customers.db`: restored `phone_number_id = '1430803473453005'`, `waba_id = '1084431507836374'`, and cleared the access token (`access_token = NULL`).
  - Confirmed Kavya credentials reside strictly within `/var/lib/myclone/ops/integrations.json`.
- **Files touched:**
  - `/var/lib/myclone/celine_customers.db` (table `celine_coexistence_settings`)
- **How it was tested, and result:**
  - Queried `celine_coexistence_settings` via Python: Verified `phone_number_id = '1430803473453005'`, `waba_id = '1084431507836374'`, `access_token = None`, `timezone = 'Africa/Nairobi'`. Status: Tested (Passed).
- **Breakage risk:** None; prevents cross-team credential leakage.
- **How to undo:** N/A (Standard isolation state).

---

### CHG-018
- **Date and Time:** 2026-11-19 16:24:00 IST
- **Linked Command:** [CMD-045](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-045)
- **Type:** Fixed / Refactored
- **What changed and why:**
  - WhatsApp Inbox feeds (both single lead view and Master Inbox) were previously mixing voice call transcript turns (`channel: 'call'`) and call attempt cards into the chat bubbles alongside actual WhatsApp messages.
  - Refactored the data ingestion and frontend rendering so that WhatsApp timelines strictly contain messages from the `messages` table (`whatsapp_message`).
  - Removed voice call transcripts and call attempts from WhatsApp thread snippets and chat bubble feeds.
- **Files touched:**
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/master_inbox.py`
  - `/opt/myclone/app/templates/index.html`
- **How it was tested, and result:**
  - Queried `GET /api/inbox/6` via curl: verified 14 items returned, all of type `whatsapp_message`, 0 transcript turns.
  - Queried `GET /api/master-inbox/thread/6` via curl: verified 14 items returned, all of type `whatsapp_message`, 0 transcript turns. Status: Tested (Passed).
- **Breakage risk:** None; call audio player and call summary remain accessible in the call history section without polluting the text chat feed.
- **How to undo:** Restore `app.py.bak_clean_wa_inbox`, `master_inbox.py.bak_clean_wa_inbox`, and `index.html.bak_clean_wa_inbox`.

---

### CHG-019
- **Date and Time:** 2026-11-20 00:07:00 IST
- **Linked Command:** [CMD-059](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-059)
- **Type:** Fixed
- **What changed and why:**
  - Inbound WhatsApp customer conversations were previously having automated AI replies blocked by `dispatch_guard` if the owner had paused cold outreach (`settings.paused = true`) or if the customer messaged outside working hours (10:00-18:30 IST).
  - This caused automated replies to fail with `core.Blocked` and get recorded into the database as `pending_token` without actually being transmitted to Meta Cloud API.
  - Refactored `providers.send_wa`: dynamically checks if `in_window` is active (`last_inbound_at` < 24 hours), and passes `owner=in_window` to `dispatch_guard`, ensuring customer service conversations within the Meta 24-hour window are dispatched instantly 24/7 even when cold discovery outreach is paused.
- **Files touched:**
  - `/opt/myclone/app/providers.py`
- **How it was tested, and result:**
  - Executed `providers.send_wa(6, ...)` via python runtime on VPS: confirmed successful transmission to Meta Cloud API (`wamid.HBgMOTE5Nzc4MjE1OTYxFQIAERgUQ0UzRDM2ODY3OUM4QzVGOTVGOTAA`).
  - Webhook delivery receipt received: PostgreSQL `messages` row updated to `status: delivered`. Status: Tested (Passed).
- **Breakage risk:** Low; only active conversations with recent inbound customer messages bypass the outbound pause. Cold unsolicited outreach remains strictly restricted to working hours.
- **How to undo:** Revert `providers.py` to require `owner=False` in `send_wa`.

---

### CHG-020
- **Date and Time:** 2026-11-20 00:07:00 IST
- **Linked Command:** [CMD-060](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-060)
- **Type:** Planned / Architecture
- **What changed and why:**
  - Designed the end-to-end automated abandoned cart recovery workflow for `coreve.in` to recover lost revenue on luxury wedge sneakers (₹6,995 - ₹8,995).
  - Outlined pre-submit contact capture (debounced AJAX capture on checkout fields), timed 3-touch recovery email sequence using Coreve's Zoho Mail SMTP (`lenee@coreve.in`), and WhatsApp recovery integration for India market.
  - Recommended deployment of industry-standard `woo-cart-abandonment-recovery` plugin vs custom MU-plugin engine.
- **Files touched:**
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
- **How it was tested, and result:**
  - Verified WP Cron and Action Scheduler actively running on VPS webroot; verified Zoho SMTP operational. Status: Verified readiness.
- **Breakage risk:** None (planning phase).
- **How to undo:** N/A.

---

### CHG-021
- **Date and Time:** 2026-11-20 00:13:00 IST
- **Linked Command:** [CMD-061](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-061)
- **Type:** Added / Config
- **What changed and why:**
  - Installed Google Tag Manager container `GTM-TM3ZZV8C` across the entire `coreve.in` site.
  - Implemented via dedicated MU-plugin `wp-content/mu-plugins/coreve-gtm.php` at priority 1 on `wp_head` and `wp_body_open`.
  - Cleared Nginx FastCGI cache on VPS to ensure immediate propagation.
- **Files touched:**
  - `/Users/lenee/coreve/wp-content/mu-plugins/coreve-gtm.php`
  - `/var/www/coreve.in/wp-content/mu-plugins/coreve-gtm.php`
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
- **How it was tested, and result:**
  - Tested `curl -sL https://coreve.in/`, `https://coreve.in/product/zivana-winterberry-girl-sneaker-shoe/`, and `https://coreve.in/checkout/` confirming `https://www.googletagmanager.com/gtm.js?id=GTM-TM3ZZV8C` renders at the top of `<head>` and noscript iframe in `<body>`. Status: Tested (Passed).
- **Breakage risk:** None.
- **How to undo:** Delete `wp-content/mu-plugins/coreve-gtm.php` and flush cache.

---

### CHG-022
- **Date and Time:** 2026-11-20 00:16:00 IST
- **Linked Command:** [CMD-062](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-062)
- **Type:** Config / Verification
- **What changed and why:**
  - Verified and confirmed Google Analytics 4 web stream tag alignment with `GTM-TM3ZZV8C`.
  - Confirmed readiness for Google's live site tester on `https://coreve.in`.
- **Files touched:**
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
- **How it was tested, and result:**
  - Verified `https://coreve.in` returns HTTP 200 with `GTM-TM3ZZV8C` in `<head>` and `<body>`. Status: Tested (Passed).
- **Breakage risk:** None.
- **How to undo:** N/A.

---

### CHG-023
- **Date and Time:** 2026-11-20 00:18:00 IST
- **Linked Command:** [CMD-063](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-063)
- **Type:** Added / Upgraded
- **What changed and why:**
  - Deployed and configured full automated abandoned cart recovery system on `coreve.in` using `woo-cart-abandonment-recovery`.
  - Configured sender identities to `Coreve` <`lenee@coreve.in`>, reply-to `hello@coreve.in`.
  - Overhauled all 3 recovery email templates to custom luxury HTML format incorporating Coreve brand identity, trust badges (Free Pan-India Delivery, 7-Day Exchange, Italian Design), dynamic product tables, 1-click cart restoration links, and WhatsApp concierge touchpoint.
  - Activated 3-stage sequence: 30 minutes, 24 hours, and 48 hours.
- **Files touched:**
  - `/wp-content/plugins/woo-cart-abandonment-recovery/` (installed on server, synced to local git)
  - `wp_cartflows_ca_email_templates` (DB rows 1, 2, 3 updated and activated)
  - `wp_options` (`wcf_ca_*` options configured)
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
  - `/Users/lenee/coreve/project-memory/04_FEATURES_STATUS.md`
- **How it was tested, and result:**
  - Tested live SMTP delivery of template 1 to `lenee@coreve.in` via Zoho Mail relay (`smtp.zoho.in:465`); returned `Send Result: true`.
  - Tested session abandonment simulation and verified 3 automated follow-ups scheduled at +30m, +24h, and +48h in `wp_cartflows_ca_email_history`.
  - Verified checkout block compatibility. Status: Tested (Passed).
- **Breakage risk:** None. Operates asynchronously via WP Cron without adding latency to checkout.
- **How to undo:** Deactivate `woo-cart-abandonment-recovery` via WP-CLI.

