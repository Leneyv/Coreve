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
│   ├── kavya_flow.py                    # Kavya Agent Swarm & n8n-style visual workflow controller
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
| `kavya_flow.py` | Controller and REST API for Kavya Agent Swarm. Manages dynamic agents in `kavya_agents`, telemetry aggregation, and n8n visual graph states. |
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
