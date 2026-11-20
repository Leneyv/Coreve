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
| **FEAT-023** | Automated Abandoned Cart Recovery (WooCommerce) | E-Commerce Automation | Automated 3-stage email recovery sequence (30m, 24h, 48h) via Zoho SMTP, pre-submit contact capture, WhatsApp concierge link, 1-click cart restore | **Done and tested** | 2026-11-20 | Verified test email to lenee@coreve.in and scheduled DB queues |
| **FEAT-024** | Subdomain & IT Services Showcase (`web.coreve.in`) | Web & Marketing Infrastructure | High-converting single-page IT services portal showcasing Corive Tech / Kavya capabilities (Custom Web, Multi-Agent Swarms, Sarvam Malayalam/English Voice, WhatsApp Coexistence, Custom ERP/n8n) with Nginx vhost, Certbot SSL, strict 403 project-memory denial, and complete 3-layer anti-indexing (SEO/AEO/GEO blocked via X-Robots-Tag, robots.txt, and meta tags) | **Done and tested** | 2026-11-20 | Verified live via HTTPS returning HTTP/2 200, 403 on `/project-memory`, and x-robots-tag noindex |
| **FEAT-025** | Inbound Website Lead Auto-Identification & Kavya Routing (`web.coreve.in`) | Lead Capture & Sales Pipeline | Pre-filled tags `[web.coreve.in | Kavya Lead]` across all 8 CTAs and audit form on `web.coreve.in`; backend auto-recognition enrolls contact as `web.coreve.in Inbound Lead`, sets `wa_opt_in=true`, bypasses outbound-call restriction, extracts structured form fields (`Name`, `Business`, `Website`, `Service`), and queues autonomous Kavya discovery | **Done and tested** | 2026-11-20 | Live test confirmed lead creation, contacted_by_kavya=True, and reply job execution |
| **FEAT-026** | Summarized Executive Email Handover & Deliverables Synthesis | Sales Operations & Notification | Replaced raw JSON dump emails with concise executive handover format: prominent Action Required instructions, customer full profile (Website, CRM, Automation), smart Scope of Work synthesis (Custom Web, AI Agents, Voice, WA API, ERP), and clean timestamped chat transcript | **Done and tested** | 2026-11-20 | Verified clean plaintext rendering, deliverable synthesis, and delimiter compatibility with email approvals |
| **FEAT-027** | 24/7 Hourly Provider & System Health Daemon | System Monitoring & Infrastructure | Autonomous daemon running every 60m 24/7 (independent of 10:00-18:30 IST calling window): syncs Meta WhatsApp approved templates (`kavya_initial`), verifies Sarvam connectivity, refreshes snapshots, and eliminates false stale alerts | **Done and tested** | 2026-11-20 | Verified live daemon startup in worker logs: 1 approved template synced, Sarvam verified fresh, stale=False |
| **FEAT-027** | Patek Philippe Luxury Curtain Parallax & Vertical Rail | Frontend Animation & UX | Brought Patek Philippe (`patek.com/en`) 2-image scroll physics to `coreve.in`'s product collection: counter-parallax curtain reveal (-170px to 0px), luxury top elevation shadow (`box-shadow: 0 -28px 70px -10px rgba(0,0,0,0.55)`), outgoing media drift (+75px) with typography fade, and signature right-side vertical pagination rail with active pill indicator | **Done and tested** | 2026-11-20 | Verified live on `https://coreve.in` with HTTP/2 200 and smooth scroll execution |


---

## Supervisor status correction — 2026-11-20
Previous “Done and tested” statuses must be read with the following exceptions: pause from Boss Chat broken; global hours contain 09:00; WhatsApp send failure handling/consent unsafe; owner reminder agent and owner report template missing; call reservation reconciliation pending; full payment E2E unverified; agent registry switches do not control worker execution. Repairs staged only. See supervisor report for evidence.


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

### GOOGLE-TAG-GTM-TM3ZZV8C — deployed and verified
- **Feature:** Google Tag Manager / Google Analytics web stream integration (`GTM-TM3ZZV8C`).
- **Status:** Done and tested.
- **Location:** Installed immediately after `<head>` in `wp-content/coreve-storefront/template.php` and `wp-content/themes/coreve/header.php`. Fallback `<noscript>` in `wp_body_open` via `wp-content/mu-plugins/coreve-gtm.php`.
- **Testing:** Live curl requests on `https://coreve.in`, `/about-us/`, `/product/zivana-winterberry/`, `/checkout/`. Exact placement immediately after `<head>` confirmed with zero duplicates.
