# 09 — Known Issues & Backlog

This document tracks identified bugs, architectural risks, technical debt, and pending tasks ordered by urgency and priority.

---

## 1. High Priority (Immediate Operational Impact)

| Issue ID | Description & Impact | Technical Risk | Suggested Solution | Status |
| :--- | :--- | :--- | :--- | :--- |
| **ISSUE-001** | **Sarvam Telephony Credit Exhaustion** | If Sarvam prepaid wallet reaches ₹0, outbound calls fail silently. | Add credit balance check in `providers.py` or automated email alert when balance < ₹500. | Open |
| **ISSUE-002** | **Meta 24-Hour Window Limitation** | In Master Inbox, freeform text replies via Meta API fail if lead has not messaged in past 24 hours. | UI now provides `📱 Open in WhatsApp Web / App` button; need more pre-approved HSM templates. | Mitigated |
| **ISSUE-003** | **Historical WhatsApp Message Backfill** | Meta Cloud API only receives webhooks from registration forward; does not pull historical device chats. | Implement one-time WhatsApp chat history parser from exported `.txt` or device backup script. | Open |

---

## 2. Medium Priority (Stability & Enhancement)

| Issue ID | Description & Impact | Technical Risk | Suggested Solution | Status |
| :--- | :--- | :--- | :--- | :--- |
| **ISSUE-004** | **SQLite Concurrency for Brandcart Kenya** | `celine_customers.db` uses SQLite. High concurrent write load from WhatsApp traffic may lock DB (`database is locked`). | Migrate `celine_customers.db` into a dedicated PostgreSQL schema if traffic exceeds 10 concurrent writes/sec. | Open |
| **ISSUE-005** | **Maya Local File Export Synchronization** | Maya directs outputs to `/Users/lenee/Downloads/maya`, but server is remote VPS (`129.121.132.35`). | Implement automated SFTP or webhook push script to transfer generated reports to local workstation. | Open |
| **ISSUE-006** | **M-Pesa Safaricom Production Keys** | M-Pesa STK push currently in sandbox readiness; production Daraja credentials required for live KES settlement. | Acquire and configure live Safaricom Daraja Shortcode and Passkey in `celine_coexistence_settings`. | Pending Credentials |

---

## 3. Low Priority (Cosmetics & UI Enhancements)

| Issue ID | Description & Impact | Technical Risk | Suggested Solution | Status |
| :--- | :--- | :--- | :--- | :--- |
| **ISSUE-007** | **Pagination Controls Beyond LIMIT 100** | Lists currently cap at 100 items via `LIMIT 100` without numeric page buttons (Page 1, 2, 3...). | Implement offset-based or cursor-based pagination controls in table footers. | Open |
| **ISSUE-008** | **Raw Log Dark Mode Contrast** | Certain monospace text elements in the raw audit log tab have low contrast in dark theme. | Standardize CSS text color tokens to `--text-secondary` across all preformatted code tags. | Open |

---

## 4. Completed & Resolved Issues

| Issue ID | Description | Resolution Date | Resolution Details |
| :--- | :--- | :--- | :--- |
| **FIXED-001** | **Master Inbox View Masking** | 2026-11-18 | Removed inline `display: flex;` on `#view-master-inbox`; added `.category-view { display: none !important; }`. |
| **FIXED-002** | **Dr. Lenny Delayed Scheduled Callback** | 2026-11-18 | Unpaused `settings.paused = false` and updated `worker.py:followup()` to trigger callback directly. |
| **FIXED-003** | **Cold Voice Call Greeting** | 2026-11-18 | Injected prior conversation transcript and lead name into Sarvam agent variables in `providers.py`. |
| **FIXED-004** | **Postgres Permissions for kavya_internal_chat** | 2026-11-18 | Granted full privileges and sequence rights to user `myclone`. |


---

## Supervisor high-priority additions — 2026-11-20
Deployment requires a stable source window after concurrent Antigravity changes. Rebase staged repairs, rerun tests and deploy with backup. Reconcile 335 reserved call attempts / matching uncertain call and follow-up jobs without replay. Remove conflicting fabricated-booking coaching; inspect live Sarvam v6 independently. Fix real pause, 09:00 hours, WhatsApp guard bypass and swallowed failures, manufactured consent, ambiguous HTTP write retries, email approval validation and invented quote timelines. No repairs deployed from this QC. Full list: supervisor report.


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
