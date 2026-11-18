# 04 — Features Status

This document maintains the real-time operational status of all platform capabilities, agent features, and integrations.
Status values: `Planned` | `In progress` | `Done and tested` | `Done but not tested` | `Broken` | `Removed`.

---

## 1. Kavya B2B Sales Engine (Corive Tech)

| Feature | Category | Status | Notes & Verification Evidence |
| :--- | :--- | :--- | :--- |
| **Bilingual Lead Qualification** | AI Agent | Done and tested | Uses Gemini 3.8 Flash with 70% Malayalam / 30% English prompt in `sales.py`. Tested live on leads. |
| **Outbound Telephony (Sarvam)** | Telephony | Done and tested | Outbound calls via `providers.py` using caller ID `+918064277369`. Verified via Sarvam API. |
| **Context-Aware Call Greeting** | Telephony | Done and tested | Passes prior conversation transcript into Sarvam agent variables to eliminate cold "Who is this?" intros. |
| **Scheduled Callback Execution** | Worker | Done and tested | Scheduled callbacks trigger voice calls directly via `worker.py:followup()`. Tested on Dr. Lenny. |
| **Operating Hours Lock (10-18:30 IST)**| Automation | Done and tested | Outbound actions suppressed outside Mon-Sat 10:00-18:30 IST via `core.py:is_operating_hours()`. |
| **2-Hour Quote Review SLA** | Escalation | Done and tested | Systemd timer checks pending quotes and triggers owner alert if unreviewed within 2 working hours. |
| **Deterministic Razorpay Links** | Payments | Done and tested | Generates 50% advance / 50% handover milestone payment links. Tested via `providers.py:create_payment_link()`. |
| **Boss Command Desk (Talk to Kavya)** | Chat UI | Done and tested | Direct internal chat in `#kavya-chat` view. Directives starting with "Instruction:" persist to training notes. |
| **Lead List Pagination (Max 100)** | Dashboard UI | Done and tested | All lead, message, and audit views enforce `LIMIT 100` to prevent UI freezing. |
| **Agent Swarm & n8n Visual Flow** | Workflow UI / Swarm | Done and tested | Interactive n8n-style workflow canvas in `#view-kavya-flow`. Visualizes 7 core agents, who does what, and supports adding/removing agents dynamically via PostgreSQL table `kavya_agents`. |


---

## 2. Master Coexistence Inbox (`+919400199817`)

| Feature | Category | Status | Notes & Verification Evidence |
| :--- | :--- | :--- | :--- |
| **Real-time Conversation Feed** | Messaging | Done and tested | Displays active conversations for coexistence number `+919400199817`. Verified in browser. |
| **Direct Chat Reply (24h Window)**| Messaging | Done and tested | Sends text replies through Meta Cloud API if within active 24h customer window. |
| **WhatsApp Web / App Deep-Link** | Messaging | Done and tested | `📱 Open in WhatsApp Web / App` button opens chat directly in native app, bypassing API window. |
| **Meta Official Template Modal** | Messaging | Done and tested | `+ New Chat / Send Template` modal allows initiating conversations using approved business templates. |
| **Historical Message Auto-Backfill**| Messaging | In progress | Live sync captures new webhook events; historical message backfill requires phone device export script. |
| **Unmasked Dashboard Navigation** | Dashboard UI | Done and tested | Fixed CSS issue where Master Inbox masked other agent views (`display: none !important` isolation). |

---

## 3. Celine Kamau BCL (Brandcart Kenya Retail)

| Feature | Category | Status | Notes & Verification Evidence |
| :--- | :--- | :--- | :--- |
| **Kenyan Persona & Dialects** | AI Agent | Done and tested | Fluent Kenyan English, Swahili, and Sheng in `CELINE_KAMAU_BRAIN.md`. Verified in test chats. |
| **LG & Samsung Catalog Brain** | Knowledge | Done and tested | Deep product specs for OLED, QNED, Crystal UHD, refrigerators, and washing machines. |
| **Isolated SQLite Database** | Database | Done and tested | Stored in `/opt/myclone/app/celine_customers.db` (`customers`, `orders_and_inquiries`, etc.). |
| **M-Pesa STK Push Integration** | Payments | Done but not tested | Endpoint stubbed in `celine.py` for Safaricom Daraja API; live API credentials pending verification. |
| **Shopify / Catalog Webhook** | E-commerce | Done but not tested | Webhook receiver in `celine.py` configured for stock updates; live webhook subscription pending. |
| **Coexistence for Brandcart (+254)**| Messaging | Done and tested | Separate coexistence settings table and webhook endpoint `/api/celine/webhook`. |

---

## 4. Maya AI me (Executive Operations Employee)

| Feature | Category | Status | Notes & Verification Evidence |
| :--- | :--- | :--- | :--- |
| **Autonomous Task Processing** | AI Agent | Done and tested | Handles open-ended tasks in `/api/maya/task` with zero hallucination constraints. |
| **D2C Lead Multi-Source Verification**| Intelligence| Done and tested | Enriches brands with founder LinkedIn, store tech stack, and physical location checks. |
| **Continuous Learning Notes** | Memory | Done and tested | Ingests employer feedback into `maya_learning_notes` and injects rules into subsequent prompts. |
| **Strict Operational Separation** | Architecture | Done and tested | Maya explicitly prohibited from managing or meddling with Kavya or Celine workflows. |
| **Local Directory Export (/Users/lenee)**| File Sync | Done but not tested | Directs file outputs to local path; requires client-side sync daemon or direct SFTP script. |

---

## 5. Platform Infrastructure & Governance

| Feature | Category | Status | Notes & Verification Evidence |
| :--- | :--- | :--- | :--- |
| **Gunicorn Daemon (Port 5679)** | Runtime | Done and tested | Managed by `myclone.service`. 1 worker, 3 threads, memory capped at 300MB. |
| **Worker Daemon (Background jobs)**| Runtime | Done and tested | Managed by `myclone-worker.service`. Playwright browser support, memory capped at 1600MB. |
| **Self-Hosted n8n (Port 5678)** | Automation | Done and tested | Managed by `myclone-n8n.service`. Node 24 runtime, proxy path `/workflow/`. |
| **Nginx Reverse Proxy & SSL** | Security | Done and tested | HTTP to HTTPS redirection, Let's Encrypt automated cert, HTTP Basic Auth protection. |
| **Project Memory Repository** | QC / Memory | Done and tested | 12 structured markdown files in `/opt/myclone/project-memory/` committed to Git. |
| **FEAT-021** | Project Memory VPS & Local Dual-Sync | Governance & DevOps | Automated bidirectional synchronization of `/project-memory` between local repository and `/opt/myclone/project-memory` on VPS with Git commits | **Done and tested** | 2026-11-18 | Tested via rsync and Git commit |
| **FEAT-022** | Project Memory Web Server Block | Nginx Security | Explicit Nginx deny rule returning HTTP 404 for `/project-memory` ensuring memory is 100% private | **Done and tested** | 2026-11-18 | Verified via curl returning HTTP/2 404 |
