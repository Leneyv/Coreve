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



