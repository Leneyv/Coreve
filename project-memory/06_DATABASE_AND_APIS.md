# 06 — Database & APIs Reference

## 1. PostgreSQL Database (`myclone`)

The primary enterprise database powering Kavya, the Core Sales Engine, Master Inbox, Ledger, and Maya.
Managed via `psycopg` connection pool in `core.py:db()`.

### Key Tables & Schema

#### `leads`
Stores all B2B sales prospects engaged by Kavya.
- `id` (SERIAL PRIMARY KEY): Unique lead identifier.
- `business` (VARCHAR): Business or practice name (e.g., "Dr. Lenny Clinic").
- `phone` (VARCHAR UNIQUE): Primary E.164 phone number.
- `decision_maker` (VARCHAR): Contact person or physician name.
- `website` (VARCHAR): Business domain URL.
- `status` (VARCHAR): Lifecycle stage (`new`, `qualifying`, `callback_scheduled`, `quote_pending`, `quote_delivered`, `converted`, `lost`).
- `scope` (TEXT): Agreed project requirements and scope.
- `scope_version` (INT): Incremented on scope revisions.
- `conversation_transcript` (JSONB): Full turn-by-turn chat and call history (`[{role, text, timestamp}]`).
- `sarvam_call_id` (VARCHAR): Active or most recent Sarvam call UUID.
- `sarvam_call_status` (VARCHAR): Telephony status (`ringing`, `in-progress`, `completed`, `failed`).
- `callback_at` (TIMESTAMPTZ): Scheduled callback timestamp in IST.
- `created_at` / `updated_at` (TIMESTAMPTZ): Audit timestamps.

#### `messages`
Central log for inbound and outbound WhatsApp and SMS communications.
- `id` (SERIAL PRIMARY KEY)
- `lead_id` (INT REFERENCES leads(id))
- `direction` (VARCHAR): `inbound` or `outbound`.
- `channel` (VARCHAR): `whatsapp`, `meta_coexistence`, `sms`.
- `sender` / `recipient` (VARCHAR): E.164 phone numbers.
- `content` (TEXT): Message body text.
- `provider_message_id` (VARCHAR): Meta Cloud API message UUID.
- `status` (VARCHAR): `delivered`, `read`, `failed`, `received`.
- `created_at` (TIMESTAMPTZ)

#### `call_attempts`
Telephony attempt logs and duration records.
- `id` (SERIAL PRIMARY KEY)
- `lead_id` (INT REFERENCES leads(id))
- `provider` (VARCHAR): `sarvam`.
- `call_sid` (VARCHAR): External provider call UUID.
- `duration_seconds` (INT): Billable call length.
- `status` (VARCHAR): `completed`, `no-answer`, `busy`, `failed`.
- `transcript` (TEXT): Synthesized voice call transcript.
- `created_at` (TIMESTAMPTZ)

#### `kavya_internal_chat`
Chat messages exchanged between the human owner and Kavya in the Boss Command Desk.
- `id` (SERIAL PRIMARY KEY)
- `role` (VARCHAR): `user` or `kavya`.
- `message` (TEXT): Message text content.
- `created_at` (TIMESTAMPTZ): Created timestamp.

#### `kavya_training_notes`
Permanent directive and learning rules extracted from boss instructions.
- `id` (SERIAL PRIMARY KEY)
- `note` (TEXT): Extracted rule or directive to be injected into Kavya's prompt.
- `active` (BOOLEAN): Flag indicating if note is currently active.
- `created_at` (TIMESTAMPTZ)

#### `kavya_agents`
Specialized autonomous agent units operating inside Kavya's agentic swarm.
- `id` (SERIAL PRIMARY KEY)
- `name` (VARCHAR): Agent name.
- `role_description` (TEXT): Plain English definition of agent's responsibilities.
- `stage` (VARCHAR): Pipeline stage (`discovery`, `telephony`, `qualification`, `extraction`, `quotation`, `closing`, `supervision`, `custom`).
- `engine` (VARCHAR): AI model or runtime (`Gemini 3.8 Flash`, `Sarvam AI Outbound (+918064277369)`, `Playwright & DOM Parser`, etc.).
- `system_prompt` (TEXT): Specialized directives and guardrails.
- `is_core` (BOOLEAN): Flag protecting core pipeline agents from deletion.
- `active` (BOOLEAN): Operational status flag.
- `telemetry_count` (INT): Processed workload metrics.
- `created_at` / `updated_at` (TIMESTAMPTZ)


#### `ledger`
Financial accounting records for customer quotes and payment transactions.
- `id` (SERIAL PRIMARY KEY)
- `lead_id` (INT REFERENCES leads(id))
- `milestone` (VARCHAR): `advance_50` or `completion_50`.
- `amount` (NUMERIC(12,2)): Milestone amount in INR.
- `razorpay_payment_link_id` (VARCHAR): Unique link ID from Razorpay.
- `payment_status` (VARCHAR): `issued`, `paid`, `expired`, `refunded`.
- `created_at` / `paid_at` (TIMESTAMPTZ)

#### `jobs`
Queue table for the background worker loop.
- `id` (SERIAL PRIMARY KEY)
- `type` (VARCHAR): `voice_call`, `whatsapp_message`, `research_enrichment`.
- `payload` (JSONB): Execution parameters and metadata.
- `status` (VARCHAR): `pending`, `running`, `completed`, `failed`.
- `scheduled_for` (TIMESTAMPTZ)
- `created_at` (TIMESTAMPTZ)

#### `settings`
Global operational switches.
- `key` (VARCHAR PRIMARY KEY): Setting key name (e.g. `paused`, `operating_hours_override`).
- `value` (JSONB): Setting value (`{"paused": false}`).

#### Maya Operational Tables
- `maya_tasks` (`id`, `title`, `description`, `status`, `output`, `created_at`, `updated_at`)
- `maya_d2c_leads` (`id`, `brand_name`, `founder_name`, `linkedin_url`, `store_url`, `tech_stack`, `verified`, `created_at`)
- `maya_learning_notes` (`id`, `feedback`, `learning_rule`, `created_at`)
- `maya_settings` (`key`, `value`)

---

## 2. SQLite Database (`celine_customers.db`)

Isolated database for Brandcart Kenya retail commerce located at `/opt/myclone/app/celine_customers.db`.

### Tables
- `customers` : `id`, `name`, `phone`, `email`, `location`, `county`, `notes`, `created_at`
- `orders_and_inquiries` : `id`, `customer_id`, `product_name`, `sku`, `price_kes`, `status`, `delivery_zone`, `payment_method`, `created_at`
- `celine_campaign_outreach` : `id`, `campaign_name`, `phone`, `status`, `created_at`
- `celine_whatsapp_messages` : `id`, `customer_id`, `direction`, `body`, `message_id`, `created_at`
- `celine_coexistence_settings` : `key`, `value` (Meta API tokens, phone ID, webhook secrets for Kenya)
- `celine_catalog_knowledge` : `id`, `brand`, `model`, `category`, `specifications`, `price_kes`, `warranty`

---

## 3. REST API Endpoints

### Core Dashboard & Sales (`app.py`)
- `GET /` : Serves monolithic dashboard UI (HTTP Basic Auth required).
- `GET /api/leads` : Lists leads with `LIMIT 100`.
- `POST /api/leads` : Creates or imports new sales leads.
- `GET /api/leads/<id>` : Fetches full lead dossier, transcript, and quote status.
- `POST /api/leads/<id>/pause` : Pauses outbound automation for specific lead.
- `POST /api/leads/<id>/quote/approve` : Owner approves quotation; issues Razorpay deposit link.
- `GET /api/audit` : Fetches recent system audit logs with `LIMIT 100`.
- `GET /api/status` : System healthcheck and worker status.

### Master Coexistence Inbox (`master_inbox.py` prefix: `/api/master-inbox`)
- `GET /api/master-inbox/feed` : Lists all active conversations for `+919400199817`.
- `GET /api/master-inbox/chat/<phone>` : Retrieves full message history for specific contact.
- `POST /api/master-inbox/send` : Sends text reply via Meta Cloud API within 24h window.
- `POST /api/master-inbox/send-template` : Dispatches pre-approved Meta HSM template to initiate conversation.
- `GET /api/master-inbox/templates` : Lists available pre-approved Meta WhatsApp templates.

### Boss Command Desk (`kavya_chat.py` prefix: `/api/kavya-chat`)
- `GET /api/kavya-chat/history` : Retrieves internal conversation history with Kavya.
- `POST /api/kavya-chat/send` : Sends message/directive to Kavya; invokes Gemini inference and persists rules.

### Kavya Agent Swarm & Flow (`kavya_flow.py` prefix: `/api/kavya`)
- `GET /api/kavya/agents` : Lists all specialized agents in Kavya's swarm, telemetry metrics, and active counts.
- `POST /api/kavya/agents` : Adds a new specialized agent to Kavya's swarm.
- `PUT /api/kavya/agents/<id>` : Updates configuration, engine, or prompt of an agent.
- `DELETE /api/kavya/agents/<id>` : Removes a custom agent (core system agents are protected).
- `POST /api/kavya/agents/<id>/toggle` : Toggles active / paused state of any agent.


### Celine Kenya (`celine.py` prefix: `/api/celine`)
- `GET /api/celine/dashboard` : Returns Kenya retail KPIs (inquiries, orders, KES revenue).
- `GET /api/celine/catalog/search` : Searches appliance and TV catalog by brand or query.
- `POST /api/celine/order` : Creates retail order and initiates M-Pesa STK push.
- `POST /api/celine/webhook` : Receives WhatsApp messages for Brandcart Kenya number.

### Maya AI me (`maya.py` prefix: `/api/maya`)
- `GET /api/maya/status` : Returns Maya agent health and recent learning rules.
- `POST /api/maya/task` : Submits new autonomous task to Maya.
- `GET /api/maya/tasks` : Lists past task execution logs.
- `POST /api/maya/feedback` : Submits employer feedback to generate learning rules.

---

## 4. Webhooks & Third-Party Integrations

| Provider | Webhook URL | Direction | Purpose |
| :--- | :--- | :--- | :--- |
| **Meta WhatsApp** | `/api/whatsapp/webhook` | Inbound | Inbound messages, delivery receipts, read statuses |
| **Sarvam AI** | `/api/webhook/sarvam/<token>`| Inbound | Post-call summaries, recording URLs, final call statuses |
| **Razorpay** | `/api/razorpay/webhook` | Inbound | Payment link captured notifications; triggers ledger updates |
| **Brandcart WhatsApp**| `/api/celine/webhook` | Inbound | Inbound messages for Kenya retail number |
| **Google Gemini** | Outbound API call | Outbound | LLM structured completions for Kavya, Celine, Maya |
| **SMTP Mail** | Outbound SMTP | Outbound | 2-hour escalation alerts and daily executive briefings |


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
