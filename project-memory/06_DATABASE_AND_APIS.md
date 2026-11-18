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
