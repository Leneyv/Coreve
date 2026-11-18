# Project Memory — Instructions for Future AI Agents & QC

Welcome to the Corive Tech Multi-Agent Control Plane (`myclone`) repository.
This `/project-memory` directory is the persistent, canonical source of truth for all architectural, operational, and development knowledge. It enables any incoming AI (Claude, ChatGPT, Gemini, Codex, or human engineer) to supervise work, perform quality control (QC), troubleshoot regressions, or take over system development seamlessly without prior conversational context.

---

## 10-Line Executive Summary of the Project
1. **Core Identity:** Corive Tech Autonomous Multi-Agent Sales & Operations Platform (`myclone.coreve.in`).
2. **Business Goal:** Automated lead qualification, omni-channel outbound customer engagement, quotation generation, and payment closure for IT services and retail commerce.
3. **Agent Kavya:** Outbound B2B IT sales representative conducting bilingual (Malayalam/English) discovery calls and WhatsApp follow-ups.
4. **Agent Celine Kamau:** Brandcart Kenya retail e-commerce specialist handling appliances, TVs, and Nairobi logistics with M-Pesa integration.
5. **Agent Maya AI me:** Autonomous digital employee for Lenin handling executive tasks, D2C lead verification, and persistent rule learning.
6. **Master Coexistence Inbox:** Real-time WhatsApp web inbox on `+919400199817` synchronised with the Meta Cloud API and physical mobile device.
7. **Telephony Engine:** Sarvam AI outbound voice telephony with automated webhook callbacks and transcript synthesis.
8. **Financial Ledger:** Deterministic Razorpay payment link generation for 50% milestone deposits and 50% handover disbursements.
9. **Tech Stack:** Python 3.12, Flask 3.1.3, Gunicorn 23, PostgreSQL 16 (`myclone`), SQLite 3 (`celine_customers.db`), Nginx, Systemd.
10. **Hosting & Access:** Hosted on Ubuntu 24.04 (Contabo VPS `129.121.132.35`), secured behind Nginx SSL (Let's Encrypt) and HTTP Basic Auth.

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
