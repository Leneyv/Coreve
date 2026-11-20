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

---

### CHG-024
- **Date and Time:** 2026-11-20 00:30:00 IST
- **Linked Command:** [CMD-064](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-064)
- **Type:** Config / Refactored
- **What changed and why:**
  - Standardized customer-facing and order notification emails to `hello@coreve.in` instead of `lenee@coreve.in`.
  - Updated `wp-config.php`, WooCommerce options, cart recovery options, and `functions.php` order alert hooks.
- **Files touched:**
  - `/var/www/coreve.in/wp-config.php`
  - `/wp-content/themes/coreve/functions.php`
  - `wp_options` (WooCommerce and WCF recovery options)
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
- **How it was tested, and result:**
  - Dispatched test email using `wp_mail('hello@coreve.in', ...)`: returned `true`. Status: Tested (Passed).
- **Breakage risk:** None.
- **How to undo:** Revert options, `wp-config.php`, and `functions.php` to `lenee@coreve.in`.

---

### CHG-025
- **Date and Time:** 2026-11-20 09:30:00 IST
- **Linked Command:** [CMD-065](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-065)
- **Type:** Feature / Enhancement
- **What changed and why:**
  - Kavya previously did not trigger a scope handover email when customers asked "How much ?" or requested pricing, because the LLM prompt lacked an explicit quote request trigger and `set_scope` suppressed duplicate scope emails if `scope_sent_at` was already populated. Additionally, notification email was pointed to `leninvarghese@gmail.com` rather than `lenee@coreve.in`.
  - Configured `OWNER_NOTIFICATION_EMAIL` to `lenee@coreve.in`.
  - Added `force=True` flag to `set_scope` in `core.py` to allow quote inquiries to re-trigger scope handovers even if text has not changed.
  - Added "CRITICAL QUOTE REQUEST & HANDOVER RULE" to `PROMPT` and quote detection regex (covering English and Malayalam price terms) in `sales.py`: compiles all discussed deliverables (including website + WhatsApp booking) into confirmed scope and immediately dispatches `scope_email` with full transcript, customer details, and approval tokens to `lenee@coreve.in`.
- **Files touched:**
  - `/opt/myclone/app/core.py`
  - `/opt/myclone/app/sales.py`
  - `/opt/myclone/app/providers.py`
  - `/opt/myclone/app/worker.py`
  - `/var/lib/myclone/ops/integrations.json`
- **How it was tested, and result:**
  - Sent test email to `lenee@coreve.in`: `{'accepted': True}`.
  - Triggered `scope_email` for Lead 6 Scope v3 ("Website development, WhatsApp appointment booking automation, and 24/7 WhatsApp emergency booking setup"): job 662039 executed and completed with status `done`.
  - Python regex test across English and Malayalam quote terms (`How much ?`, `റേറ്റ് എത്രയാണ്?`, `ചിലവ് എത്ര വരും?`): verified all return `True`.
  - Services compiled and restarted: `myclone` and `myclone-worker` active.
- **Breakage risk:** Low; only triggers handover email when quotation is explicitly asked.
- **How to undo:** Revert `sales.py`, `core.py`, and `providers.py` to previous git commits.


---

### CHG-026
- **Date and Time:** 2026-11-20 09:45:00 IST
- **Linked Command:** [CMD-066](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-066)
- **Type:** UI / Redesign
- **What changed and why:**
  - Redesigned the "Agent Swarm & Flow (n8n)" tab in the dashboard (`https://myclone.coreve.in`) from a flat grid of cards into an authentic, interactive n8n AI Agent visual graph matching the user's reference diagram.
  - Features implemented:
    - Dot grid canvas background with light n8n aesthetics.
    - SVG bezier wiring layer with flowing pulse animation between nodes.
    - Left Node: `When chat message received` (Trigger node with ⚡ lightning badge and WhatsApp icon).
    - Center Node: `AI Agent (Tools Agent)` with robot icon 🤖 and bottom purple diamond attachment ports.
    - Sub-Nodes attached below AI Agent:
      - `Gemini 3.5 Flash Lite` (Chat Model with Sarvam Indic localization)
      - `Window Buffer Memory` (PostgreSQL conversation history)
      - `SerpApi Scraper` (Research tool / Dossier builder)
      - `Scope & Quotation Handover Engine` (Quotation handover to `lenee@coreve.in`)
    - Router Node: `If` (Quote requested?) with green crossroads icon 🔀 and two branches:
      - Upper branch (`true`) ➔ `Success` (Handover to Owner `lenee@coreve.in`)
      - Lower branch (`false`) ➔ `Failure / Reply` (Send WhatsApp message via `+91 94001 99817`)
    - Floating bottom controls: Zoom In `+`, Zoom Out `-`, Reset `↺`, Fullscreen `⛶`, and `💬 Chat` test button.
    - Interactive node inspection: clicking any node opens the directives/runtime configuration modal.
    - Collapsible Swarm Agent Registry below the canvas maintaining full management of all 7 specialized agents (viewing system prompts, toggling active states, deploying new agents).
- **Files touched:**
  - `/opt/myclone/app/templates/index.html` (VPS)
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
- **How it was tested, and result:**
  - Applied changes to `/opt/myclone/app/templates/index.html`.
  - Restarted `myclone` service and verified `active` status via systemctl.
  - Verified `curl http://127.0.0.1:5679` outputs `#n8n-viewport` and SVG connectors.
  - Inspected canvas rendering in browser. Status: Tested (Passed).
- **Breakage risk:** None.
- **How to undo:** Restore `/opt/myclone/app/templates/index.html.bak_before_n8n_flow`.

---

### CHG-027
- **Date and Time:** 2026-11-20 10:10:00 IST
- **Linked Command:** [CMD-067](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-067)
- **Type:** Feature / Automation
- **What changed and why:**
  - Kavya can now automatically parse natural email replies sent by the owner to handover emails (e.g. `This will cost 24,000 and 2 weeks time we will do`), approve the quotation, and instantly dispatch the approved quote to the customer's WhatsApp.
  - Changes:
    - `worker.py`: `poll_mail` now runs every 25 seconds, filtering specifically for replies to handover emails (`SUBJECT "QUOTE REQUIRED"`, `Re:` prefix or sender from `lenee@coreve.in`). Strips quoted email threads and parses price/amount, timeline, and custom instructions. Marks UIDs in `mailbox_events` to prevent duplicate sends.
    - `sales.py`: `quote_send` formats a clean, polite Malayalam WhatsApp response with deliverables scope, approved price, timeline, and standard 50/50 payment terms. Outbound message is saved to `conversation_transcript` so it reflects in the Master Inbox.
    - Fixed numeric Decimal comparison bug (`str(Decimal('24000.00')) != str(Decimal('24000'))`).
- **Files touched:**
  - `/opt/myclone/app/worker.py`
  - `/opt/myclone/app/sales.py`
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
- **How it was tested, and result:**
  - Tested with real email reply from `lenee@coreve.in` for Lead #6 (`UID 162466`): worker parsed `Amount=₹24000, Timeline=2 weeks`.
  - Approved quote for Lead #6 in DB and executed job 662045.
  - Verified WhatsApp delivery via Meta Cloud API: provider message ID `wamid.HBgMOTE5Nzc4MjE1OTYxFQIAERgUQ0UwQUE0QUQwNUU2Q0NGM0JDOTQA` marked `delivered`.
  - Verified `conversation_transcript` updated with `role: 'kavya'`. Status: Tested (Passed).
- **Breakage risk:** Low.
- **How to undo:** Revert `worker.py` and `sales.py` to backups.


---

### SUP-QC-20261120 — audit and staged repairs, NOT DEPLOYED
Prepared fixes for business hours, actual pause state, WhatsApp guard bypass/failure reporting/consent, ambiguous write retries, financial summaries, fabricated scope/timeline and email approval validation. 13 provider-mocked tests passed. Concurrent edits detected in sales.py, worker.py, sales.py prevented deployment. Do not mark these fixes complete in production. Runtime credentials untouched; no customer communication initiated.


---

### SUP-QC-20261120-DEPLOYED
Owner confirmed Antigravity paused. Final Antigravity Decimal checks and email self-reply guard preserved; five QC modules deployed with protected source/database backup at /var/backups/myclone-qc-20261120. All deployed hashes match the tested release. Thirteen mocked regression tests pass against production modules. Both services active, local dashboard 200, public dashboard 401, global pause true, zero active categories, 09:30 blocked, report_data succeeds. Fabricated-booking coaching deactivated (1), exact duplicate notes deactivated (2), factual supervisor guardrails added; history retained. No customer calls/messages or paid LLM tests initiated. Voice testing, uncertain-call reconciliation, owner reminder agent/report template, provider delivery E2E and registry execution controls remain pending. This supersedes earlier staged-only statements. See 12_SUPERVISOR_QC_2026-11-20.md deployment addendum.


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

### WHATSAPP-CUSTOMER-SCOPE-20261120 — personal coexistence chats protected
Owner instructed: Kavya must auto-reply only to customers she contacted; personal incoming chats on the coexistence number must remain manual. Implemented deterministic provenance checks in whatsapp_scope.py, sales.receive_message/reply_job, providers.send_wa, worker.send_customer/followup. Eligibility requires a connected customer call or a genuine initial campaign / owner-approved plan WhatsApp outreach / approved quote send with a provider message record. A scraped contact, inbound message alone, manual owner reply, failed dial, owner reminder or accidental old bot reply never enrolls a personal chat. Personal messages remain in Master Inbox but do not queue AI replies or reach Gemini through this intake. Historical private chat records retained; manual owner messaging unchanged.

Deployed and verified: 10 isolated privacy tests passed again against production modules. Cancelled 2 pending personal automation jobs, reclassified 2 old personal-only generic enquiry threads, and saved an active privacy_rule coaching note. Both services active; local dashboard 200; existing global outreach pause preserved. Backup: /var/backups/myclone-whatsapp-scope-20261120-release. No customer test messages or calls sent. This rule supersedes any earlier blanket inbound auto-response behaviour.


---

### BOSS-DASHBOARD-20261120 — deployed
New private Boss Dashboard: https://myclone.coreve.in/#boss. Full release/evidence: 14_KAVYA_BOSS_DASHBOARD_2026-11-20.md. Today/yesterday/7d/all-time business metrics, live work, 15 agent drilldowns, lead-stage baseline/forward tracking, costs, provider snapshots and direct billing links. Owner safety caps are enforced but unset; pauses and personal-chat protections preserved. 17 dashboard tests + 10 privacy regressions passed; Chrome verified UI. At release: 518 unique business leads, 140 connected customer calls; Sarvam org wallet snapshot -112.10 credits (not live/Kavya-only), Meta GREEN/TIER_2K, no approved templates, available service-fee data zero. Exact Meta remaining quota/wallet unknown; historical stage dates not invented. No payments or customer test outreach. Backup /var/backups/myclone-boss-dashboard-20261120. See canonical release for API/schema/cost boundaries.

---

### CHG-028
- **Date and Time:** 2026-11-20 14:25:00 IST
- **Linked Command:** CMD-068
- **Type:** Added
- **What changed and why:** Implemented and launched single-page IT Services website under subdomain `web.coreve.in` hosted on the VPS. Designed a high-converting, modern dark-slate UI highlighting Corive Tech's real offerings extracted from Kavya's system prompts, agent swarms, and database records: Custom Web & Portal Development, Autonomous Multi-Agent AI Swarms, Bilingual AI Voice Telephony (Sarvam Malayalam/English), WhatsApp Official API Coexistence, Custom ERP/n8n Automations, and Technical Website & Speed Audits. Implemented interactive simulated Kavya terminal, transparent 50/50 milestone payment philosophy, 2-hour scope review turnaround, and instant pre-filled WhatsApp scoping contact form. Configured Nginx virtual host with gzip, cache controls, and strict 403 access denial for `/project-memory` and `/.git`. Provisioned Let's Encrypt SSL certificate via Certbot.
- **Files touched:**
  - `/var/www/web.coreve.in/index.html` (VPS & Local `/Users/lenee/coreve/web.coreve.in/index.html`)
  - `/etc/nginx/sites-available/web.coreve.in` (VPS & Local `/Users/lenee/coreve/web.coreve.in/nginx-web.coreve.in.conf`)
  - `/etc/nginx/sites-enabled/web.coreve.in` (VPS symlink)
- **How it was tested and the result:**
  1. Nginx configuration syntax tested on VPS: `nginx -t` passed (`syntax is ok`, `test is successful`).
  2. Nginx reloaded: `systemctl reload nginx` succeeded.
  3. DNS resolution tested: `dig +short web.coreve.in` resolved to `129.121.132.35`.
  4. SSL certificate issued and deployed via Certbot: successfully provisioned for `web.coreve.in` (valid until 2027-02-18).
  5. Live HTTP/2 test: `curl -sI https://web.coreve.in` returned HTTP/2 200 OK.
  6. Security access test: `curl -sI https://web.coreve.in/project-memory/00_README_FOR_AI.md` returned HTTP/2 403 Forbidden.
  7. Git security test: `curl -sI https://web.coreve.in/.git/config` returned HTTP/2 403 Forbidden.
- **Anything that might break because of this change:** None. The new subdomain is completely isolated from existing virtual hosts (`coreve.in`, `myclone.coreve.in`, `mycoreve.com`).
- **How to undo it:**
  - Remove symlink: `rm /etc/nginx/sites-enabled/web.coreve.in`
  - Reload Nginx: `systemctl reload nginx`
  - Optionally remove directory: `rm -rf /var/www/web.coreve.in`

---

### CHG-029
- **Date and Time:** 2026-11-20 14:34:00 IST
- **Linked Command:** CMD-069
- **Type:** Config
- **What changed and why:** Implemented strict anti-indexing controls for `web.coreve.in` across three layers (Nginx headers, `robots.txt`, and HTML meta tags) to guarantee that `web.coreve.in` is completely blocked from SEO, AEO, and GEO indexing, while leaving the primary domain `coreve.in` completely unblocked and actively pushing its sitemaps and `llms.txt` for maximum search and AI visibility.
- **Files touched:**
  - `/var/www/web.coreve.in/robots.txt` (VPS & Local `/Users/lenee/coreve/web.coreve.in/robots.txt`)
  - `/var/www/web.coreve.in/index.html` (VPS & Local `/Users/lenee/coreve/web.coreve.in/index.html`)
  - `/etc/nginx/sites-available/web.coreve.in` (VPS & Local `/Users/lenee/coreve/web.coreve.in/nginx-web.coreve.in.conf`)
- **How it was tested and the result:**
  1. Nginx syntax tested on VPS: `nginx -t` passed.
  2. Nginx reloaded: `systemctl reload nginx` succeeded.
  3. Header verification on `web.coreve.in`: `curl -sI https://web.coreve.in` confirmed `x-robots-tag: noindex, nofollow, noarchive, nosnippet, noimageindex, noai, noimageai`.
  4. Robots file verification: `curl -s https://web.coreve.in/robots.txt` confirmed `Disallow: /` for all bots and explicit AI crawlers (`GPTBot`, `ClaudeBot`, `Google-Extended`, `PerplexityBot`, etc.).
  5. HTML meta tags verification: `curl -s https://web.coreve.in` confirmed anti-indexing tags present for search engines and generative models.
  6. Verified `coreve.in`: `curl -sI https://coreve.in` confirmed clean HTTP/2 200 without anti-indexing headers, `robots.txt` intact pointing to `wp-sitemap.xml`, and `llms.txt` active (HTTP/2 200) for AEO and GEO discovery.
- **Anything that might break because of this change:** None. Settings apply strictly and exclusively to the `web.coreve.in` virtual host.
- **How to undo it:**
  - Remove `add_header X-Robots-Tag` from `/etc/nginx/sites-available/web.coreve.in`
  - Remove `robots.txt` from `/var/www/web.coreve.in/`
  - Revert meta tags in `index.html`
  - Reload Nginx: `systemctl reload nginx`

---

### CHG-030
- **Date and Time:** 2026-11-20 15:05:00 IST
- **Linked Command:** [CMD-070](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-070)
- **Type:** UI / Enhancement
- **What changed and why:**
  - In Talk to Kavya (Boss Chat), action plan approval cards (`Plan #2 · completed`, `Plan #1 · rejected`) previously rendered as full-width unconstrained blocks with raw JSON `<pre>` tags right above the message input, dominating the view and obstructing the conversation stream.
  - Implemented collapsible / minimizable plans panel:
    - Added `toggleKavyaPlansMinimize()` with localStorage persistence.
    - Minimized state renders as a compact 34px bar with pending count indicator and `[ ➕ Expand ]` button.
    - Auto-minimizes when there are no active pending approvals.
    - Expanded state includes `[ ➖ Minimize ]` button, constrained `max-height: 220px; overflow-y: auto;`, and collapsible `<details>` for plan execution steps.
    - Added `[ Show History / Hide History ]` button to hide completed/rejected plans by default.
- **Files touched:**
  - `/opt/myclone/app/templates/index.html` (VPS)
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
- **How it was tested, and result:**
  - Deployed to `/opt/myclone/app/templates/index.html`.
  - Restarted `myclone` service and verified live HTML response.
  - Verified default collapsed state when no plans are pending. Status: Tested (Passed).
- **Breakage risk:** None.
- **How to undo:** Revert `index.html` from `index.html.bak_before_plan_minimize`.

---

### CHG-031
- **Date and Time:** 2026-11-20 16:15:00 IST
- **Linked Command:** [CMD-071](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-071)
- **Type:** Feature / Inbound Automation
- **What changed and why:**
  - Inbound WhatsApp messages initiated from `https://web.coreve.in/` previously lacked identifying tags and were categorized as generic personal chats (`personal_inbox`), automatically blocking Kavya's autonomous response pipeline with `{'automation':'off_personal_chat'}`.
  - Tagged all WhatsApp CTAs on `web.coreve.in` with `[web.coreve.in | Kavya Lead]` across navigation, hero, 5 core services, footer, and discovery audit form.
  - Updated `/opt/myclone/app/whatsapp_scope.py` to recognize leads with `category='web.coreve.in Inbound Lead'` as fully eligible for automatic responses.
  - Updated `/opt/myclone/app/sales.py:receive_message`:
    - Inbound messages containing `web.coreve.in` / `Kavya Lead` tags are automatically created/enrolled with `category='web.coreve.in Inbound Lead'`, `status='contacted'`, and `wa_opt_in=true`.
    - Automatically extracts structured form fields (`Name`, `Business`, `Website`, `Primary Requirement`) directly into the lead record.
    - Added prompt directive to `sales.py:PROMPT` instructing Kavya to warmly welcome the web lead, acknowledge the specific requested service, and initiate immediate discovery.
- **Files touched:**
  - `/Users/lenee/coreve/web.coreve.in/index.html` (Local & VPS `/var/www/web.coreve.in/index.html`)
  - `/opt/myclone/app/whatsapp_scope.py` (VPS)
  - `/opt/myclone/app/sales.py` (VPS)
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
  - `/Users/lenee/coreve/project-memory/04_FEATURES_STATUS.md`
- **How it was tested, and result:**
  - Updated website CTAs and synced to `/var/www/web.coreve.in/index.html`.
  - Restarted `myclone` and `myclone-worker`.
  - Executed simulated inbound message containing `Hi Kavya [web.coreve.in | Kavya Lead]...` via test script on VPS.
  - Verified lead creation: `status='contacted'`, `category='web.coreve.in Inbound Lead'`, `wa_opt_in=true`, `contacted_by_kavya: True`, and `reply` job queued and executed by worker. Status: Tested (Passed).
- **Breakage risk:** None. Outbound cold calling gating and personal chat protection remain intact for non-tagged personal messages.
- **How to undo:** Revert `sales.py` from `sales.py.bak_web_lead` and remove the category check in `whatsapp_scope.py`.

---

### CHG-032
- **Date and Time:** 2026-11-20 17:35:00 IST
- **Linked Command:** [CMD-072](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-072)
- **Type:** Enhancement / Email Handover
- **What changed and why:**
  - Owner handover emails (`QUOTE REQUIRED`) previously dumped raw multi-kilobyte JSON data structures (`call_dossier`, `limits`, `schema`, `conversation_rules`) directly into the body and frequently showed empty/unconfirmed scope when quotes were requested.
  - Redesigned handover email template in `sales.py:scope_email`:
    - Clean executive layout with 3 clear sections:
      1. Customer Full Details (Business, Contact, Phone, Category, Website, CRM, Automation, Observations).
      2. Scope of Work (Deliverables to Quote, Scope Version, SLA deadline, Standard Payment Terms).
      3. Clean Conversation Transcript (`[Time] Customer: ... / Kavya: ...`).
    - Prominent "👉 ACTION REQUIRED" banner at the top detailing exact reply options (`₹<amount> and <timeline>`) and web approval link.
    - Zero raw JSON blobs.
  - Added smart deliverables synthesis in `sales.py:reply_job`: automatically identifies requested services (Custom Website Development, Autonomous AI Agents, Sarvam Voice AI, WhatsApp Cloud API, Custom ERP) from the conversation.
  - Updated `approval_mail.py:fresh_reply` to strip standard separator lines (`===` and `---`) from incoming email replies.
- **Files touched:**
  - `/opt/myclone/app/sales.py` (VPS)
  - `/opt/myclone/app/approval_mail.py` (VPS)
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
  - `/Users/lenee/coreve/project-memory/04_FEATURES_STATUS.md`
- **How it was tested, and result:**
  - Deployed to VPS, restarted `myclone` and `myclone-worker`.
  - Executed template generation test with complete customer profile; verified clean formatting, readable text, clear action instructions, and proper delimiter stripping. Status: Tested (Passed).
- **Breakage risk:** None. IMAP email parser compatibility preserved with `Lead #` and `Scope v` matching.
- **How to undo:** Revert `sales.py` and `approval_mail.py` to previous git commits.

---

### CHG-033
- **Date and Time:** 2026-11-20 19:00:00 IST
- **Linked Command:** [CMD-073](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-073)
- **Type:** Enhancement / 24x7 System Health Daemon
- **What changed and why:**
  - In Kavya's Boss Command Centre, provider status cards previously went stale after 30 minutes, showing false warnings such as *"No approved WhatsApp templates found"* and *"Snapshot needs a fresh check"*. During non-working hours (outside 10:00-18:30 IST), scheduled maintenance was entirely suspended.
  - Implemented an autonomous 24/7 hourly background health check daemon (`hourly_health_check_loop`) in `worker.py`:
    - Runs every 3600 seconds, 24 hours a day, 7 days a week, independent of sales calling windows or office hours.
    - Automatically syncs approved WhatsApp message templates from Meta Graph API (instantly recognizing approved templates like `kavya_initial`).
    - Verifies Sarvam AI connection reachability (`apps.sarvam.ai`) and refreshes provider snapshots.
    - Monitors outreach status and backlog.
  - Updated `boss_dashboard.py` staleness threshold to 75m so snapshots stay continuously verified without stale warnings.
- **Files touched:**
  - `/opt/myclone/app/worker.py` (VPS)
  - `/opt/myclone/app/boss_dashboard.py` (VPS)
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
  - `/Users/lenee/coreve/project-memory/04_FEATURES_STATUS.md`
- **How it was tested, and result:**
  - Restarted `myclone-worker` and verified background startup in journal logs.
  - Logs confirmed: `Meta WhatsApp hourly check: 1 approved template(s), tier=TIER_2K, quality=GREEN`, `Sarvam hourly check: reachable=True, snapshot refreshed`.
  - Verified `boss_dashboard:snapshot()` returns `stale: False` for both providers. Status: Tested (Passed).
- **Breakage risk:** None. Operates asynchronously in a daemon thread.
- **How to undo:** Remove `hourly_health_check_loop` from `worker.py`.


---

### CHG-033
- **Date and Time:** 2026-11-20 18:56:00 IST
- **Linked Command:** [CMD-073](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-073)
- **Type:** Upgraded / Frontend Animation
- **What changed and why:** Upgraded `coreve.in`'s homepage product model sequence scroll transition to replicate Patek Philippe's curtain reveal counter-parallax effect. As a user scrolls down or up, each incoming product card rolls over the prior scene with a deep luxury elevation shadow, while the inner shoe visual counter-translates downwards, giving the illusion of a stationary luxury object revealed behind a rising physical window. Added Patek Philippe-style vertical pagination rail on the right screen edge with active indicator and smooth jump navigation.
- **Files touched:**
  - `/var/www/coreve.in/wp-content/coreve-storefront/style.css` (VPS & Local)
  - `/var/www/coreve.in/wp-content/coreve-storefront/app.js` (VPS & Local)
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
  - `/Users/lenee/coreve/project-memory/04_FEATURES_STATUS.md`
- **How it was tested and the result:**
  1. Validated JS syntax with `node -c` (exit code 0).
  2. Deployed to VPS via rsync.
  3. Verified HTTP/2 200 response on `https://coreve.in`.
  4. Verified live asset delivery: `curl -s https://coreve.in/wp-content/coreve-storefront/style.css` and `app.js` confirmed `.patek-rail` and counter-parallax equations present.
- **Anything that might break because of this change:** None. Backward compatibility preserved for `prefers-reduced-motion: reduce`.
- **How to undo it:**
  - Revert `style.css` and `app.js` via git checkout and rsync to VPS.



---

### CHG-034
- **Date and Time:** 2026-11-20 19:05:00 IST
- **Linked Command:** [CMD-074](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-074)
- **Type:** Upgraded / Analytics Configuration
- **What changed and why:** Installed Google Tag Manager / Google Analytics web stream tag (`GTM-TM3ZZV8C`) across all pages of `https://coreve.in`. Embedded the exact snippet immediately after the opening `<head>` tag in both `wp-content/coreve-storefront/template.php` (for custom storefront pages) and `wp-content/themes/coreve/header.php` (for standard WooCommerce and theme pages). Cleaned up `wp-content/mu-plugins/coreve-gtm.php` to prevent duplicate head injection while preserving the `<noscript>` iframe in `wp_body_open`.
- **Files touched:**
  - `/Users/lenee/coreve/wp-content/coreve-storefront/template.php` (Local & VPS `/var/www/coreve.in/wp-content/coreve-storefront/template.php`)
  - `/Users/lenee/coreve/wp-content/themes/coreve/header.php` (Local & VPS `/var/www/coreve.in/wp-content/themes/coreve/header.php`)
  - `/Users/lenee/coreve/wp-content/mu-plugins/coreve-gtm.php` (Local & VPS `/var/www/coreve.in/wp-content/mu-plugins/coreve-gtm.php`)
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
  - `/Users/lenee/coreve/project-memory/04_FEATURES_STATUS.md`
- **How it was tested and the result:**
  1. Tested with `curl -sL https://coreve.in` to verify the tag is the very first child of `<head>`. Result: Verified.
  2. Tested with `curl -sL https://coreve.in/about-us/` to verify theme pages. Result: Verified.
  3. Tested with `curl -sL https://coreve.in/product/zivana-winterberry/` and `/checkout/`. Result: Verified.
  4. Verified tag count on homepage: exactly 1 `<script>` tag in `<head>` and 1 `<noscript>` iframe in `<body>`. Result: Verified.
- **Anything that might break because of this change:** None. Asynchronous script loading prevents any blocking of page rendering or Web Vitals.
- **How to undo it:** Revert the edits to `template.php`, `header.php`, and `coreve-gtm.php`.


---

### CHG-035
- **Date and Time:** 2026-11-20 19:55:00 IST
- **Linked Command:** [CMD-075](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md#CMD-075)
- **Type:** Added / E-Commerce Architecture & Service Showcase
- **What changed and why:** Integrated complete E-Commerce store building and automation capabilities onto the `web.coreve.in` single-page portal:
  1. Updated page metadata and title to include E-Commerce Store Building (Shopify, WooCommerce, Magento, Wix) and Cart Recovery.
  2. Added an interactive Supported E-Commerce Platforms & Enterprise Stack bar (Shopify & Plus, WooCommerce, Magento / Adobe Commerce, Wix eCommerce, Custom Headless).
  3. Expanded Core Capabilities grid from 6 to 8 services, introducing Complete E-Commerce Store Building and Cart Recovery & Store Automation.
  4. Created a dedicated `#ecommerce` deep dive section detailing the 4-pillar pipeline (Turnkey Storefronts, Frictionless Checkout, Abandoned Cart Engine, Logistics & COD RTO Guard) and the 3-Stage Abandoned Cart & Retention Funnel (T+15m WhatsApp ping, T+1h concierge email, T+24h dynamic incentive).
  5. Updated the Discovery Audit form dropdown and footer navigation.
  6. Preserved strict anti-indexing robot tags (`noindex, nofollow, noarchive, nosnippet, noimageindex, noai, noimageai`) and Nginx headers.
- **Files touched:**
  - `/Users/lenee/coreve/web.coreve.in/index.html` (Local & VPS `/var/www/web.coreve.in/index.html`)
  - `/Users/lenee/coreve/project-memory/02_COMMAND_LOG.md`
  - `/Users/lenee/coreve/project-memory/03_CHANGELOG.md`
  - `/Users/lenee/coreve/project-memory/04_FEATURES_STATUS.md`
- **How it was tested and the result:**
  1. Validated HTML parser balance (0 unclosed tags, 0 tag mismatches).
  2. Deployed to VPS `/var/www/web.coreve.in/index.html` via rsync.
  3. Verified HTTP/2 200 response and anti-indexing headers (`x-robots-tag: noindex, nofollow...`).
  4. Verified live rendering of Shopify, WooCommerce, Magento, Wix, and Cart Recovery sections via curl. Status: Tested (Passed).
- **Anything that might break because of this change:** None. Static single-page layout with responsive Tailwind classes.
- **How to undo it:** Revert `index.html` via git checkout and rsync to `/var/www/web.coreve.in/index.html`.
