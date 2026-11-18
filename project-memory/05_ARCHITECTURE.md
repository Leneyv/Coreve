# 05 — Architecture & System Design

## 1. Directory & File Hierarchy

```
/opt/myclone/
├── app/                                 # Primary Python application directory
│   ├── app.py                           # Flask master router, API controllers, view dispatchers
│   ├── core.py                          # Database pool, configuration loader, operating hours check
│   ├── sales.py                         # Kavya sales logic, prompt construction, qualification engine
│   ├── providers.py                     # Third-party integrations (Sarvam, Gemini, Razorpay, Mail)
│   ├── worker.py                        # Autonomous background job loop, callbacks, status polling
│   ├── master_inbox.py                  # Master Coexistence Inbox blueprint (+919400199817)
│   ├── kavya_chat.py                    # Talk to Kavya (Boss Command Desk) blueprint
│   ├── celine.py                        # Celine Kamau BCL (Brandcart Kenya) retail blueprint
│   ├── maya.py                          # Maya AI me (Executive operations) blueprint
│   ├── research.py                      # Lead dossier builder and automated web enrichment
│   ├── scraper.py                       # Playwright-based website scanner and metadata parser
│   ├── setup_celine_tables.py           # SQLite initialization script for Celine Kenya
│   ├── schema_base.sql                  # PostgreSQL base DDL schema
│   ├── CELINE_KAMAU_BRAIN.md            # Knowledge base and persona rules for Celine Kamau
│   ├── celine_customers.db              # Isolated SQLite database for Brandcart Kenya
│   └── templates/
│       └── index.html                   # Monolithic SPA UI with multi-agent subviews & CSS
├── workflow/                            # Self-hosted n8n workflow engine directory
│   ├── package.json                     # n8n Node.js dependencies
│   └── node_modules/                    # n8n runtime packages
├── project-memory/                      # Permanent AI documentation, changelog, and QC records
├── browsers/                            # Playwright browser binaries (Chromium, Firefox)
├── node-v24.22.3-linux-x64/             # Portable Node.js v24 runtime
├── node24 -> ...                        # Symlink to portable Node.js binary
├── venv/                                # Isolated Python 3.12 virtual environment
└── .gitignore                           # Git ignore rules for virtualenvs, caches, and DBs
```

---

## 2. Main System Modules & Responsibilities

| File Path | Functional Responsibility |
| :--- | :--- |
| `app.py` | Central Flask entrypoint. Registers blueprints (`master_inbox_bp`, `kavya_chat_bp`, `celine_bp`, `maya_bp`). Manages authentication headers, session states, and main dashboard view rendering. |
| `core.py` | Provides PostgreSQL connection context manager `db()`, loads runtime configuration from `/etc/myclone/runtime.env`, evaluates operating hours via `is_operating_hours()`, and logs security events. |
| `sales.py` | Orchestrates Kavya's conversational AI state machine. Enforces Malayalam/English 70/30 split, runs structured JSON extraction via Gemini, and flags scope confirmation. |
| `providers.py` | Implements provider adapters: `initiate_sarvam_call()` (Sarvam telephony), `create_payment_link()` (Razorpay), `call_gemini()` (LLM completions), and SMTP email dispatches. |
| `worker.py` | Continuous background daemon (`myclone-worker.service`). Polls `jobs` table every 10 seconds, checks pending callbacks, executes voice calls, and polls Sarvam call status. |
| `master_inbox.py` | Dedicated controller for Master Coexistence Inbox on `+919400199817`. Handles WhatsApp message history querying, template triggers, and message dispatches. |
| `kavya_chat.py` | Dedicated controller for direct owner-to-Kavya conversation. Persists directives into `kavya_internal_chat` and auto-extracts training rules into `kavya_training_notes`. |
| `celine.py` | Isolated controller for Celine Kamau BCL. Queries `celine_customers.db` (SQLite), handles Brandcart Kenya inquiries, product searches, and Kenyan coexistence settings. |
| `maya.py` | Autonomous controller for Maya AI me. Handles open-ended task execution, lead verification, continuous learning notes, and isolated task status tracking. |
| `index.html` | Monolithic responsive single-page web interface containing isolated subviews (`#view-master-inbox`, `#view-kavya`, `#view-celine`, `#view-maya`, `#kavya-chat`) controlled by vanilla JS. |

---

## 3. High-Level Architecture & Component Flow

```
                                 [ EXTERNAL WORLD ]
                                         │
        ┌────────────────────────────────┼────────────────────────────────┐
        │                                │                                │
  [ WhatsApp App ]              [ Incoming / Outbound ]            [ Public Lead ]
(Meta Coexistence)               (Sarvam Telephony)              (Payment Links)
        │                                │                                │
        ▼                                ▼                                ▼
[ Meta Cloud API ]             [ +918064277369 ]                [ Razorpay Gateway ]
        │                                │                                │
        └────────────────────────────────┼────────────────────────────────┘
                                         │
                                         ▼
                            [ NGINX REVERSE PROXY ]
                           (Port 80 -> 443 SSL TLS)
                   (/api/* bypassed, / secured with Basic Auth)
                                         │
                  ┌──────────────────────┴──────────────────────┐
                  ▼                                             ▼
        [ Port 5679: Gunicorn ]                       [ Port 5678: n8n ]
       (myclone.service: Flask)                   (myclone-n8n.service: Node)
                  │
        ┌─────────┴─────────┐
        ▼                   ▼
[ Background Worker ]  [ Blueprints & Controllers ]
(myclone-worker)       ├── master_inbox.py (WhatsApp)
  - Polls jobs         ├── kavya_chat.py (Boss Desk)
  - Scheduled calls    ├── sales.py (Kavya B2B)
  - Status updates     ├── celine.py (Brandcart Kenya)
                       └── maya.py (Executive Ops)
        │                   │
        └─────────┬─────────┘
                  │
        ┌─────────┴─────────┐
        ▼                   ▼
[ PostgreSQL: myclone ]   [ SQLite: celine_customers.db ]
(Coreve Enterprise DB)    (Brandcart Kenya Retail DB)
  - leads, jobs, ledger    - customers, inquiries, catalog
  - messages, kavya_chat   - celine_whatsapp_messages
  - maya_tasks, audit      - celine_coexistence_settings
```

---

## 4. Key Data Flows

### A. Master Coexistence Inbox Flow (WhatsApp)
1. Customer sends WhatsApp message to `+919400199817`.
2. Meta Cloud API delivers webhook payload to `POST /api/whatsapp/webhook`.
3. Nginx passes request directly to Flask (Basic Auth bypassed for `/api/whatsapp/webhook`).
4. `master_inbox.py` receives webhook, deduplicates message ID, inserts message into `messages` table.
5. In the UI, the Master Inbox polls `GET /api/master-inbox/feed` and updates the active chat list.
6. Owner can reply in two ways:
   - **Direct Reply (Within 24h):** Sent via Meta Cloud API endpoint.
   - **Out-of-Window / App Handoff:** Click `📱 Open in WhatsApp Web / App` to deep-link directly to native app with pre-filled message, or click `⚡ Send Meta Official Template` to initiate approved HSM.

### B. Outbound Telephony & Contextual Callback Flow
1. Lead reaches `callback_scheduled` status (e.g. Dr. Lenny).
2. `myclone-worker.service` polls `jobs` and `leads` where `callback_at <= NOW()` and `settings.paused = false`.
3. Worker invokes `worker.py:followup()`, which calls `providers.py:initiate_sarvam_call()`.
4. `providers.py` extracts `lead['conversation_transcript']`, compiles it into `prior_conversation_history`, and passes `customer_name` into Sarvam agent variables.
5. Sarvam places call from `+918064277369` to customer's phone.
6. Kavya opens the call by addressing the customer by name and continuing from the prior discussion.
7. Upon hang-up, Sarvam fires webhook to `POST /api/webhook/sarvam/<token>`.
8. Flask updates `call_attempts`, transcribes the conversation, and updates lead state in PostgreSQL.
