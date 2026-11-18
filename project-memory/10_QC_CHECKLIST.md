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
