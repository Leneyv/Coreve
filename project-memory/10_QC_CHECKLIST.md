# 10 — Quality Control (QC) Checklist & Test Suite

This test suite provides reproducible, step-by-step verification procedures for every platform component.
Any human auditor or AI agent can execute these tests to guarantee platform integrity.

---

## 1. Security, Authentication & Access Control

### QC-SEC-01: Nginx HTTP Basic Authentication
- **What it does:** Ensures public visitors cannot access the private control plane without valid credentials.
- **Steps to test:**
  ```bash
  curl -I https://myclone.coreve.in/
  ```
- **Expected Result:** HTTP status `401 Unauthorized`.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

### QC-SEC-02: Public Webhook Bypass
- **What it does:** Verifies that third-party provider webhooks (Meta, Sarvam, Razorpay) are reachable without HTTP Basic Auth.
- **Steps to test:**
  ```bash
  curl -I -X POST https://myclone.coreve.in/api/whatsapp/webhook
  curl -I -X POST https://myclone.coreve.in/api/razorpay/webhook
  ```
- **Expected Result:** HTTP status `200 OK` or `400 Bad Request` (from handler validation), but NEVER `401 Unauthorized`.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

### QC-SEC-03: Operating Hours Enforcement
- **What it does:** Prevents automated outbound telephone calls outside Mon-Sat 10:00-18:30 IST.
- **Steps to test:**
  ```bash
  /opt/myclone/venv/bin/python3 -c "from core import is_operating_hours; print('Is operating hours:', is_operating_hours())"
  ```
- **Expected Result:** Returns boolean evaluation based on IST timezone.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

---

## 2. Dashboard Navigation & UI Isolation

### QC-NAV-01: Multi-Agent Dashboard View Switching
- **What it does:** Verifies that switching between Kavya, Master Inbox, Celine, and Maya tabs completely hides inactive views without CSS bleeding or overlay masking.
- **Steps to test:**
  1. Open `https://myclone.coreve.in/` in browser with valid credentials.
  2. Click `📥 Master Inbox (+919400199817)`. Verify inbox displays.
  3. Click `🗣️ Talk to Kavya (Boss Chat)`. Verify chat window renders and Master Inbox is completely hidden.
  4. Click `🇰🇪 Celine Kamau (Kenya)`. Verify Brandcart dashboard displays without overlapping inbox content.
  5. Click `👩‍💼 Maya AI me`. Verify Maya operations dashboard displays cleanly.
- **Expected Result:** Inactive `.category-view` elements have computed style `display: none !important`. No visual overlapping.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

### QC-NAV-02: Table Pagination & Row Limit (Max 100)
- **What it does:** Ensures large database tables do not crash or slow down the browser by enforcing a maximum of 100 rows per view.
- **Steps to test:**
  ```bash
  curl -s http://127.0.0.1:5679/api/leads | jq '. | length'
  ```
- **Expected Result:** Length is `<= 100`.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

---

## 3. Master Coexistence Inbox (`+919400199817`)

### QC-INB-01: Feed & Chat History Retrieval
- **What it does:** Loads recent WhatsApp conversations and retrieves turn-by-turn messages.
- **Steps to test:**
  ```bash
  curl -s http://127.0.0.1:5679/api/master-inbox/feed | jq .
  ```
- **Expected Result:** Returns JSON array of active conversations with phone numbers and latest messages.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

### QC-INB-02: New Chat / Send Template Modal
- **What it does:** Opens modal to start a conversation with a new phone number.
- **Steps to test:**
  1. Navigate to Master Inbox.
  2. Click `+ New Chat / Send Template`.
  3. Verify `#master-new-chat-modal` appears with phone number input and message text area.
  4. Verify dual buttons: `📱 Open in WhatsApp Web / App` and `⚡ Send Meta Official Template`.
- **Expected Result:** Modal renders smoothly, click outside closes modal, and buttons link correctly.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

---

## 4. Kavya B2B Sales & Outbound Telephony

### QC-KAV-01: Scheduled Callback Dispatch (Dr. Lenny Test)
- **What it does:** Dispatches automated voice call when lead callback timestamp is reached.
- **Steps to test:**
  1. Check lead status in database:
     ```bash
     sudo -u postgres psql -d myclone -c "SELECT id, business, phone, status, callback_at FROM leads WHERE phone = '+919778215961';"
     ```
  2. Verify system is not paused (`settings.paused = false`).
  3. Verify worker heartbeat is recent:
     ```bash
     sudo -u postgres psql -d myclone -c "SELECT * FROM worker_heartbeat ORDER BY id DESC LIMIT 1;"
     ```
- **Expected Result:** Callback executes outbound call via Sarvam AI when scheduled time arrives.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

### QC-KAV-02: Prior Context Call Greeting (No Cold "Who is this?")
- **What it does:** Ensures outbound calls greet the customer by name and continue previous conversation context.
- **Steps to test:**
  1. Check call payload generation in `providers.py`:
     ```python
     # agent_variables must contain customer_name and prior_conversation
     ```
- **Expected Result:** Telephony agent opens with personalized greeting rather than asking "Who am I speaking with?".
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

### QC-KAV-03: Boss Command Desk (`🗣️ Talk to Kavya`)
- **What it does:** Allows direct instruction giving to Kavya and saves instructions to training notes.
- **Steps to test:**
  1. Navigate to `#kavya-chat` view.
  2. Type: `Instruction: Always emphasize our 24/7 technical monitoring when discussing ERP systems.`
  3. Send message.
  4. Verify response in chat.
  5. Check database:
     ```bash
     sudo -u postgres psql -d myclone -c "SELECT * FROM kavya_training_notes ORDER BY id DESC LIMIT 1;"
     ```
- **Expected Result:** The instruction is inserted into `kavya_training_notes`.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

---

## 5. Payments, Quotations & Financial Ledger

### QC-PAY-01: Deterministic 50% Milestone Link Generation
- **What it does:** Generates a signed Razorpay payment link for exactly 50% deposit.
- **Steps to test:**
  1. Trigger quotation approval via `POST /api/leads/<id>/quote/approve`.
  2. Check `ledger` table:
     ```bash
     sudo -u postgres psql -d myclone -c "SELECT * FROM ledger WHERE milestone = 'advance_50' ORDER BY id DESC LIMIT 1;"
     ```
- **Expected Result:** Record exists with valid `razorpay_payment_link_id` and status `issued`.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

### QC-PAY-02: Razorpay Webhook Payment Capture
- **What it does:** Updates ledger payment status upon receiving `payment.captured` webhook from Razorpay.
- **Steps to test:**
  ```bash
  curl -X POST http://127.0.0.1:5679/api/razorpay/webhook \
    -H "Content-Type: application/json" \
    -d '{"event":"payment_link.paid","payload":{"payment_link":{"entity":{"id":"plink_mock_test","status":"paid"}}}}'
  ```
- **Expected Result:** Handler returns `200 OK` and ledger status transitions to `paid`.
- **Status:** Done but not tested (Sandbox verified, mock webhook pending)
- **Date Last Tested:** 2026-11-17

---

## 6. Celine Kamau BCL (Brandcart Kenya)

### QC-CEL-01: SQLite Database Connectivity & Catalog Search
- **What it does:** Searches Brandcart Kenya catalog for products and prices in KES.
- **Steps to test:**
  ```bash
  curl -s "http://127.0.0.1:5679/api/celine/catalog/search?q=OLED" | jq .
  ```
- **Expected Result:** Returns list of matching LG OLED models with prices in KSh.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

---

## 7. Maya AI me (Executive Digital Employee)

### QC-MAY-01: Task Creation & Execution
- **What it does:** Submits task to Maya and executes without hallucinated data.
- **Steps to test:**
  ```bash
  curl -s -X POST http://127.0.0.1:5679/api/maya/task \
    -H "Content-Type: application/json" \
    -d '{"title":"Test Verification","description":"Verify website domain coreve.in"}' | jq .
  ```
- **Expected Result:** Returns task ID with status `completed` or `in_progress`.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18

### QC-MAY-02: Continuous Learning Note Ingestion
- **What it does:** Submits user feedback to Maya and verifies rule persistence.
- **Steps to test:**
  ```bash
  curl -s -X POST http://127.0.0.1:5679/api/maya/feedback \
    -H "Content-Type: application/json" \
    -d '{"feedback":"Do not summarize tables","rule":"Always output full markdown tables without truncation"}' | jq .
  ```
- **Expected Result:** Rule is stored in `maya_learning_notes` and returned in subsequent prompt context.
- **Status:** Pass
- **Date Last Tested:** 2026-11-18
---

### TEST-008: Public Web Block of `/project-memory/`
- **Target:** `https://myclone.coreve.in/project-memory/00_README_FOR_AI.md`
- **Steps:**
  1. Execute `curl -s -k -I https://myclone.coreve.in/project-memory/00_README_FOR_AI.md` without credentials.
  2. Execute `curl -s -k -I -u "owner:<password>" https://myclone.coreve.in/project-memory/00_README_FOR_AI.md` with credentials.
- **Expected Result:**
  - Both commands return `HTTP/2 404` directly from Nginx before reaching application server.
- **Status:** **PASS** (Verified on 2026-11-18)

---

### TEST-009: VPS and Local Memory File Parity Check
- **Target:** Local `/Users/lenee/coreve/project-memory/` vs VPS `/opt/myclone/project-memory/`
- **Steps:**
  1. Run `rsync -avz --dry-run` or compare checksums between local and VPS.
- **Expected Result:**
  - 12 identical markdown files present on both systems with zero diff.
- **Status:** **PASS** (Verified on 2026-11-18)


---

## Supervisor correction — 2026-11-20
Old procedures are not sufficient E2E evidence. `core.is_operating_hours` is not present; actual functions are `working` and `is_in_calling_window`. An unsigned Razorpay request must be rejected, not treated as a required successful 200 response. Payment milestone is mid-work, not an advance. Current public memory block returns 403. Thirteen mocked regression tests pass in `/opt/myclone/qc-20261120/test_qc.py`; these do not certify production deployment, voice quality or delivered messages. See supervisor report.


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
