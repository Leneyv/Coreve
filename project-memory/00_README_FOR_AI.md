# Project Memory — Instructions for Future AI Agents & QC

Welcome to the Corive Tech Multi-Agent Control Plane (`myclone`) repository.
This `/project-memory` directory is the persistent, canonical source of truth for all architectural, operational, and development knowledge. It enables any incoming AI (Claude, ChatGPT, Gemini, Codex, or human engineer) to supervise work, perform quality control (QC), troubleshoot regressions, or take over system development seamlessly without prior conversational context.

---

## 10-Line Executive Summary of the Project
1. **Core Identity:** Corive Tech Autonomous Multi-Agent Sales & Operations Platform (`myclone.coreve.in`) and IT Services Portal (`web.coreve.in`).
2. **Business Goal:** Automated lead qualification, omni-channel outbound customer engagement, quotation generation, payment closure, and digital IT engineering.
3. **Agent Kavya:** Outbound B2B IT sales representative conducting bilingual (Malayalam/English) discovery calls and WhatsApp follow-ups.
4. **Agent Celine Kamau:** Brandcart Kenya retail e-commerce specialist handling appliances, TVs, and Nairobi logistics with M-Pesa integration.
5. **Agent Maya AI me:** Autonomous digital employee for Lenin handling executive tasks, D2C lead verification, and persistent rule learning.
6. **Master Coexistence Inbox:** Real-time WhatsApp web inbox on `+919400199817` synchronised with the Meta Cloud API and physical mobile device.
7. **Telephony Engine:** Sarvam AI outbound voice telephony with automated webhook callbacks and transcript synthesis.
8. **Financial Ledger:** Deterministic Razorpay payment link generation for 50% milestone deposits and 50% handover disbursements.
9. **Tech Stack:** Python 3.12, Flask 3.1.3, Gunicorn 23, PostgreSQL 16 (`myclone`), SQLite 3 (`celine_customers.db`), Nginx, Systemd, Tailwind CSS.
10. **Hosting & Access:** Hosted on Ubuntu 24.04 (Contabo VPS `129.121.132.35`), secured behind Nginx SSL (Let's Encrypt), HTTP Basic Auth, and strict 403 blocks on `/project-memory`.

---

## Structure of the Project Memory Directory
| File Name | Purpose & Contents |
| :--- | :--- |
| `00_README_FOR_AI.md` | Memory navigation instructions, file index, 10-line executive summary. |
| `01_PROJECT_OVERVIEW.md` | Purpose, business model, user roles, tech stack, infrastructure, domain routing. |
| `02_COMMAND_LOG.md` | Verbatim log of every user instruction (CMD-001, CMD-002...) with timestamps and outcomes. |
| `03_CHANGELOG.md` | Technical log of every change (CHG-001, CHG-002...) with touched files, tests, and rollbacks. |
| `04_FEATURES_STATUS.md` | Master status matrix of all features across agents and platform modules. |
| `05_ARCHITECTURE.md` | System topology, folder hierarchy, component interactions, and data flow diagrams. |
| `06_DATABASE_AND_APIS.md` | Complete schemas for PostgreSQL and SQLite, REST endpoints, webhooks, 3rd party APIs. |
| `07_ENV_AND_SETUP.md` | Deployment guide, systemd units, startup sequences, environment variable definitions (NO SECRETS). |
| `08_DECISIONS.md` | Architectural Decision Records (ADRs) with rationale and rejected alternatives. |
| `09_KNOWN_ISSUES_AND_TODO.md` | Prioritized bugs, operational risks, performance bottlenecks, and future backlog. |
| `10_QC_CHECKLIST.md` | Step-by-step test matrix covering auth, agents, inbox, telephony, and billing. |
| `11_AGENT_AND_PROMPTS.md` | System prompts, behavioral guardrails, tools, and tuning parameters for Kavya, Celine, Maya. |

---

## Rules for Maintaining This Memory (Mandatory Protocol)
1. **Pre-Response Update Rule:** Memory files MUST be updated and saved **before** returning any final answer to the user. A task is strictly incomplete until this directory is updated.
2. **Notification in Reply:** Conclude every user turn with a short `Memory updated` note listing added entries (e.g., `CMD-014`, `CHG-022`) and modified paths.
3. **Append-Only History:** Never delete, overwrite, or truncate historical entries in `02_COMMAND_LOG.md` or `03_CHANGELOG.md`. If a past entry was inaccurate, append an explicit correction pointing to the original ID.
4. **Zero Secrets Policy:** NEVER record actual API keys, passwords, database credentials, or sensitive customer PII. Record only variable names and sanitized placeholders.
5. **Truthful Testing Status:** Mark features as `Tested` ONLY if executed and verified in terminal/browser with evidence. Otherwise, explicitly mark `NOT TESTED` or `UNVERIFIED`.
6. **Live State Synchronization:** Keep `00_README_FOR_AI.md`, `04_FEATURES_STATUS.md`, `05_ARCHITECTURE.md`, and `09_KNOWN_ISSUES_AND_TODO.md` completely reflective of the system as it stands right now.


---

## Supervisor correction — 2026-11-20
Read `12_SUPERVISOR_QC_2026-11-20.md` before relying on older “Done and tested” claims. This is a focused code/runtime QC, not full E2E certification. No production repairs from this pass were deployed: concurrent developer edits repeatedly failed the guarded deployment check. All campaigns and global outreach were paused at inspection. Payment is 50% mid-work and 50% handover, no advance. Sarvam server selection remains version 6. Registry rows are not independently executing agents.


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
