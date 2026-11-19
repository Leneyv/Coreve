# 02 — Command Log

This log documents every user instruction given to the agent across the project history in strict chronological order.
New entries must be appended to the END of this file. Existing entries are permanent and must never be deleted.

---

### CMD-001
- **Date and Time:** 2026-11-15 15:40:00 IST
- **Instruction:** `[UNVERIFIED BASELINE] Initialize Corive Tech sales engine on server with Kavya AI, PostgreSQL database, and Flask control plane.`
- **Understanding:** Deploy the baseline Corive Tech sales pipeline with Kavya sales agent, PostgreSQL database schema, and Flask web control plane.
- **What was done:** Initialized `/opt/myclone/app`, created `schema_base.sql`, implemented `core.py`, `app.py`, `sales.py`, `providers.py`, `worker.py`, set up systemd services `myclone.service` and `myclone-worker.service`.
- **Files created or changed:**
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/core.py`
  - `/opt/myclone/app/sales.py`
  - `/opt/myclone/app/providers.py`
  - `/opt/myclone/app/worker.py`
  - `/opt/myclone/app/schema_base.sql`
  - `/etc/systemd/system/myclone.service`
  - `/etc/systemd/system/myclone-worker.service`
- **Result:** Done
- **Link to Changelog:** [CHG-001](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-001)

---

### CMD-002
- **Date and Time:** 2026-11-17 06:00:00 IST
- **Instruction:** `[UNVERIFIED BASELINE] Restrict calling and outreach to operating hours Monday to Saturday 10:00 to 18:30 IST, and enforce 2-hour quotation review rule.`
- **Understanding:** Ensure the system does not execute outbound automated voice calls outside working hours, and manage quotes past 16:30 IST to roll over to next business day noon.
- **What was done:** Added `is_operating_hours()` check to `core.py`, modified `worker.py` to pause scheduling outside 10:00-18:30 IST Monday-Saturday, added escalation checks for 2-hour quotation review SLA.
- **Files created or changed:**
  - `/opt/myclone/app/core.py`
  - `/opt/myclone/app/worker.py`
  - `/opt/myclone/app/app.py`
  - `/etc/systemd/system/myclone-escalation.service`
- **Result:** Done
- **Link to Changelog:** [CHG-002](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-002)

---

### CMD-003
- **Date and Time:** 2026-11-17 15:00:00 IST
- **Instruction:** `dont put more than 100 row iems in as single sub tab it may take time to load so keel it max 100 per page`
- **Understanding:** Implement pagination or row limiting across all table sub-views in the dashboard to cap rows at 100 items per view to optimize page load speed.
- **What was done:** Added SQL `LIMIT 100` clauses across API endpoints serving dashboard lists (leads, messages, audit logs, calls) and updated table views in `app.py` and `templates/index.html`.
- **Files created or changed:**
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/templates/index.html`
- **Result:** Done
- **Link to Changelog:** [CHG-003](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-003)

---

### CMD-004
- **Date and Time:** 2026-11-17 20:20:00 IST
- **Instruction:** `[UNVERIFIED BASELINE] Add Celine Kamau agent for Brandcart Kenya retail appliances with full catalog knowledge and Kenyan dialect.`
- **Understanding:** Implement an isolated, specialised retail sales agent (Celine Kamau) for Brandcart Kenya handling TVs, appliances, M-Pesa payments, and Kenyan delivery rules.
- **What was done:** Created `CELINE_KAMAU_BRAIN.md`, created `celine_customers.db` SQLite database via `setup_celine_tables.py`, implemented `celine.py` blueprint with catalog search, customer tracking, and coexistence settings, and added Celine navigation views to `templates/index.html`.
- **Files created or changed:**
  - `/opt/myclone/app/celine.py`
  - `/opt/myclone/app/CELINE_KAMAU_BRAIN.md`
  - `/opt/myclone/app/setup_celine_tables.py`
  - `/opt/myclone/app/celine_customers.db`
  - `/opt/myclone/app/templates/index.html`
  - `/opt/myclone/app/app.py`
- **Result:** Done
- **Link to Changelog:** [CHG-004](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-004)

---

### CMD-005
- **Date and Time:** 2026-11-17 21:00:00 IST
- **Instruction:** `[UNVERIFIED BASELINE] Create Maya AI me agent as an autonomous personal digital employee for Lenin with strict isolation from Kavya and Celine.`
- **Understanding:** Implement a dedicated operations assistant (Maya AI me) with complete separation of concerns, zero hallucination guardrails, D2C intelligence tools, and local file export sync.
- **What was done:** Created PostgreSQL tables (`maya_tasks`, `maya_d2c_leads`, `maya_learning_notes`, `maya_settings`), built `maya.py` blueprint with continuous learning rule retrieval, and integrated Maya UI dashboard in `templates/index.html`.
- **Files created or changed:**
  - `/opt/myclone/app/maya.py`
  - `/opt/myclone/app/templates/index.html`
  - `/opt/myclone/app/app.py`
- **Result:** Done
- **Link to Changelog:** [CHG-005](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-005)

---

### CMD-006
- **Date and Time:** 2026-11-18 09:30:00 IST
- **Instruction:** `do a full QC of KAvya and her operation and her dashboard and let me know if there is any error list up every error and mistakes and operaatinal erro for kavya and how her brain works`
- **Understanding:** Perform a thorough quality control audit of Kavya's system prompt, telephony flows, dashboard metrics, database consistency, and operational error logs.
- **What was done:** Audited `sales.py`, inspected `call_attempts` table, verified Sarvam API credentials, checked prompt boundaries for Malayalam-English split, confirmed absence of hallucinated prices, and surfaced key operational bottlenecks (such as paused agent status and cold-greeting bugs).
- **Files created or changed:**
  - Audit report generated in session transcript.
- **Result:** Done
- **Link to Changelog:** [CHG-006](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-006)

---

### CMD-007
- **Date and Time:** 2026-11-18 10:15:00 IST
- **Instruction:** `i want an individual inbox for every messages comes to my meta coexistance number of +919400199817 in myclone.coreve.in above kavya as Master inbox also allow me to reply and read message there too also take historical data of existing whatsap messges inside the number`
- **Understanding:** Build a dedicated Master Coexistence Inbox above Kavya in the dashboard navigation for WhatsApp number `+919400199817` supporting read, reply, chat listing, and coexistence synchronization.
- **What was done:** Created `master_inbox.py` blueprint, registered in `app.py`, created HTML template sections `#view-master-inbox` and `#view-master-coex-setup`, implemented client JS for real-time polling, and configured coexistence webhook routing.
- **Files created or changed:**
  - `/opt/myclone/app/master_inbox.py`
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/templates/index.html`
- **Result:** Done
- **Link to Changelog:** [CHG-007](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-007)

---

### CMD-008
- **Date and Time:** 2026-11-18 10:45:00 IST
- **Instruction:** `this inbox is not connected and taken historical message also this inbox not allowing to me to send a message to a person if not directly i want to create a template and that template can initiate a conversation with a number is that possible of i need to ahve a meta service provider for that ?`
- **Understanding:** Clarify Meta 24-hour customer service window rules, explain template message initiation vs direct message reply, explain coexistence limitation for historical message backfill, and build a Template Initiation modal in the Master Inbox.
- **What was done:** Added "+ New Chat / Send Template" modal (`#master-new-chat-modal`) to Master Inbox, added dual action buttons (`📱 Open in WhatsApp Web / App` and `⚡ Send Meta Official Template`), explained Meta Cloud API pricing and BSP trade-offs.
- **Files created or changed:**
  - `/opt/myclone/app/templates/index.html`
  - `/opt/myclone/app/master_inbox.py`
- **Result:** Done
- **Link to Changelog:** [CHG-008](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-008)

---

### CMD-009
- **Date and Time:** 2026-11-18 11:20:00 IST
- **Instruction:** `i also want to ahve option to talk to kavya to tell her what to do`
- **Understanding:** Provide a direct conversational control desk where the boss can talk directly to Kavya, give instructions, ask about leads/pipeline, and have rules automatically stored in Kavya's memory.
- **What was done:** Created `kavya_internal_chat` PostgreSQL table, created `kavya_chat.py` blueprint, added `🗣️ Talk to Kavya (Boss Chat)` sub-view (`#kavya-chat`) in the dashboard, linked directives starting with "Instruction:" to auto-persist into `kavya_training_notes`.
- **Files created or changed:**
  - `/opt/myclone/app/kavya_chat.py`
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/templates/index.html`
- **Result:** Done
- **Link to Changelog:** [CHG-009](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-009)

---

### CMD-010
- **Date and Time:** 2026-11-18 11:45:00 IST
- **Instruction:** `📥 Master Coexistence Inbox content is masking eveyr other sub category and dashboard f kavya, maya and celine fix it`
- **Understanding:** Fix CSS layout bug where Master Inbox view remained visible (`display: flex`) over other tab views when switching between Kavya, Celine, and Maya dashboards.
- **What was done:** Removed hardcoded inline `display: flex;` from `<div id="view-master-inbox">`, added `.category-view { display: none !important; }` in CSS, updated `navigateTo()` JS router to explicitly enforce `display: none !important` on inactive sections.
- **Files created or changed:**
  - `/opt/myclone/app/templates/index.html`
- **Result:** Done
- **Link to Changelog:** [CHG-010](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-010)

---

### CMD-011
- **Date and Time:** 2026-11-18 12:15:00 IST
- **Instruction:** `kavya is not doing follow up based on schedules i.e there suppos to be a call to 👤 Dr. Lenny 📞 +919778215961 at 11:40 according to my knowledge check yourself and verify`
- **Understanding:** Investigate why Dr. Lenny's scheduled 11:40 AM callback was delayed until 12:00 PM, identify the root cause in the database or worker loop, and fix the delay mechanism.
- **What was done:** Checked `leads` and `jobs` tables and worker logs. Discovered global setting `paused = true` caused 23 skipped worker iterations with `Agent paused`. Unpaused system, updated `worker.py:followup()` to place callback voice calls directly when `status == 'callback_scheduled'`, verified live execution.
- **Files created or changed:**
  - `/opt/myclone/app/worker.py`
  - PostgreSQL `settings` table (`paused` set to `false`)
- **Result:** Done
- **Link to Changelog:** [CHG-011](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-011)

---

### CMD-012
- **Date and Time:** 2026-11-18 13:40:00 IST
- **Instruction:** `why she initiated call like Kavya: "Hello, I am Kavya. I called to talk about something. Who am I speaking with?" this ? she has to be acculmulate earlier convertion and shuld start as a coontinueation of earlier communication`
- **Understanding:** Fix cold greeting in Sarvam voice calls where Kavya asked who she was speaking to instead of greeting the lead by name and continuing from prior conversation context.
- **What was done:** Updated `providers.py` in `initiate_sarvam_call()` to compile previous conversation transcripts from `lead['conversation_transcript']` into `dossier['prior_conversation_history']`, passed `customer_name` and `prior_conversation` to Sarvam agent variables, and instructed the voice prompt to greet by name and continue directly without asking identity.
- **Files created or changed:**
  - `/opt/myclone/app/providers.py`
- **Result:** Done
- **Link to Changelog:** [CHG-012](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-012)

---

### CMD-013
- **Date and Time:** 2026-11-18 14:11:00 IST
- **Instruction:** `STANDING RULE FOR THIS PROJECT: MAINTAIN A "PROJECT MEMORY" FOR HANDOVER AND QC... Create a folder called /project-memory in the project root... Backfill the history now...`
- **Understanding:** Implement permanent standing memory protocol across 12 structured files in `/opt/myclone/project-memory`, backfill entire historical record, document tech stack, database schemas, and QC tests, and commit to Git.
- **What was done:** Created `/opt/myclone/project-memory` directory, built all 12 markdown memory files (`00` through `11`), performed backfill of all commands and changes, documented complete system architecture, environment configurations, decisions, and QC test suites.
- **Files created or changed:**
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
- **Result:** Done
- **Link to Changelog:** [CHG-013](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-013)

---

### CMD-014
- **Date and Time:** 2026-11-18 14:47:00 IST
- **Instruction:** `STANDING RULE: HOST THE PROJECT MEMORY IN THE PROJECT DIRECTORY AND ON OUR VPS SERVER... Keep it inside the project... Put it on the VPS... Keep it private and safe (explicit deny rule, test 403/404)... Never write secrets... Sync automatic after every task...`
- **Understanding:** Establish bidirectional synchronization and standing operational rule for `/project-memory`: ensure it lives in `<project root>/project-memory`, is committed in Git (not in `.gitignore`), is synchronized to the VPS project root (`/opt/myclone/project-memory`), is kept 100% private and blocked from the public web via explicit Nginx deny rule (returning 404), contains zero secrets/passwords, and syncs automatically at the completion of every task.
- **What was done:**
  1. Audited VPS deployment structure: `/opt/myclone` owned by user `myclone`, managed via systemd `myclone.service` and Nginx HTTP/2 reverse proxy.
  2. Verified git repository in `/opt/myclone/.git` tracking `project-memory/` (confirmed `.gitignore` does not exclude `project-memory`).
  3. Added explicit Nginx deny rule in `/etc/nginx/sites-available/myclone.coreve.in` for `location ^~ /project-memory { deny all; return 404; }` across both port 80 and 443 blocks.
  4. Reloaded Nginx and verified live that `curl -I https://myclone.coreve.in/project-memory/00_README_FOR_AI.md` returns `HTTP/2 404` (even with credentials).
  5. Established automatic SSH `rsync` + git commit pipeline between local repository (`/Users/lenee/coreve/project-memory`) and VPS (`/opt/myclone/project-memory`).
  6. Executed full bidirectional sync and verified all 12 memory documents exist identically on both machines.
- **Files created or changed:**
  - `/etc/nginx/sites-available/myclone.coreve.in`
  - `/opt/myclone/project-memory/00_README_FOR_AI.md`
  - `/opt/myclone/project-memory/01_PROJECT_OVERVIEW.md`
  - `/opt/myclone/project-memory/02_COMMAND_LOG.md`
  - `/opt/myclone/project-memory/03_CHANGELOG.md`
  - `/opt/myclone/project-memory/04_FEATURES_STATUS.md`
  - `/opt/myclone/project-memory/05_ARCHITECTURE.md`
  - `/opt/myclone/project-memory/07_ENV_AND_SETUP.md`
  - `/opt/myclone/project-memory/08_DECISIONS.md`
  - `/opt/myclone/project-memory/10_QC_CHECKLIST.md`
- **Result:** Done
- **Link to Changelog:** [CHG-014](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-014)

---

### CMD-015
- **Date and Time:** 2026-11-18 18:28:00 IST
- **Instruction:** `i want a sub tab under kavya for me to visualy see (the flow n8n type) who is doing what and how many agent we do have inside kavya's agentic ai and allow me to add and remove frm the dashboard more agents to work with kavya`
- **Understanding:** Create a dedicated n8n-style visual agent workflow sub-tab under Kavya that shows who is doing what, how many specialized agents exist in her swarm, and allows adding and removing agents directly from the UI.
- **What was done:**
  1. Created PostgreSQL table `kavya_agents` in `myclone` database with columns `id`, `name`, `role_description`, `stage`, `engine`, `system_prompt`, `is_core`, `active`, `telemetry_count`.
  2. Populated the 7 core specialized agents: Web Scraper & Dossier Builder, Outbound Sarvam Voice Telephony Agent, Meta WhatsApp Nurture Agent, Gemini Cognitive Extraction Agent, 2-Hour SLA Escalation Agent, Razorpay Milestone Closer Agent, and Boss Directives Supervisor Agent.
  3. Created backend REST API blueprint in `/opt/myclone/app/kavya_flow.py` with endpoints `GET /api/kavya/agents`, `POST /api/kavya/agents`, `PUT /api/kavya/agents/<id>`, `DELETE /api/kavya/agents/<id>`, and `POST /api/kavya/agents/<id>/toggle`. Registered in `app.py`.
  4. Added `🕸️ Agent Swarm & Flow (n8n)` sub-tab under Kavya in the sidebar navigation with real-time agent count badge.
  5. Implemented `#view-kavya-flow` containing a dark-grid n8n-style visual workflow canvas, stage badges, telemetry metric cards, prompt inspection modal, and `+ Add New Agent to Swarm` modal.
  6. Tested with browser automation: verified 7 core agents rendered, added 8th agent ("SEO Competitor Scout Agent"), verified live canvas addition, deleted 8th agent, and verified clean restoration to 7 agents.
- **Files created or changed:**
  - `/opt/myclone/app/kavya_flow.py`
  - `/opt/myclone/app/app.py`
  - `/opt/myclone/app/templates/index.html`
  - Database: PostgreSQL table `kavya_agents`
- **Result:** Done
- **Link to Changelog:** [CHG-015](file:///opt/myclone/project-memory/03_CHANGELOG.md#CHG-015)

---

### CMD-016
- **Date and Time:** 2026-11-19 11:05:00 IST
- **Instruction:** `what is RAG and whats the use of that ?`
- **Understanding:** Provide a clear, comprehensive architectural and business explanation of Retrieval-Augmented Generation (RAG), explaining how it works (Retrieve, Augment, Generate), why it is used (preventing hallucinations, grounding AI in proprietary company documents without expensive fine-tuning), and how it directly applies to this project (Celine's product catalogs, Kavya's IT case studies, and Maya's document intelligence).
- **What was done:** Delivered an executive and technical briefing on RAG concepts, workflows, benefits, and practical use cases across the Corive Tech multi-agent platform.
- **Files created or changed:** None (informational explanation).
- **Result:** Done
- **Link to Changelog:** None (Informational query; no code or schema modified)


