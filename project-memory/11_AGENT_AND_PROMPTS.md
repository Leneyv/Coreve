# 11 - Agent & Prompts Specification

## 1. Overview
This project is engineered and maintained using agentic AI assistants (Antigravity AI Agent and ChatGPT Codex). This document defines the roles, system capabilities, guardrails, and supervision standards governing AI agent interaction on the Coreve project.

---

## 2. Active AI Agent Profiles

### 1. Antigravity AI Assistant (Google DeepMind)
* **Primary Role:** Full-stack systems architect, DevOps engineer, and codebase custodian.
* **Responsibilities:**
  - VPS infrastructure management (Ubuntu, Nginx, PHP-FPM, Postfix, Let's Encrypt).
  - Database schema administration and HPOS data integrity.
  - Commerce and payment flow verification (WooCommerce, Razorpay).
  - Technical SEO, AEO, and GEO optimization.
  - Maintaining the `/project-memory` handover ledger.
* **Available Tooling:**
  - `run_command`: Remote shell execution via SSH.
  - `read_file`, `write_file`, `edit_file`: Local and remote file operations.
  - `search_web`: Up-to-date documentation and standards lookup.

### 2. ChatGPT Codex Integration Agent
* **Primary Role:** High-fidelity UI/UX frontend designer and template integrator.
* **Responsibilities:**
  - Designing visual storefront templates and animations (`wp-content/coreve-storefront/`).
  - Refining mobile/desktop typography, color profiles, and layout grids.
  - Packaging static previews into native WordPress template hooks.

---

## 3. Mandatory Guardrails & Standing Rules for All Agents

1. **Project Memory Maintenance (Standing Rule):**
   - The agent MUST update `/project-memory` (Command Log, Changelog, Feature Status, Decisions) *before* submitting any final response to the user.
   - History entries are append-only. Never delete or rewrite past command or change logs.
2. **Strict Secrets & PII Redaction:**
   - Never output or store real passwords, API secret keys, database credentials, or private customer data in repository files or memory documents. Refer to variables by their constant names only (`DB_PASSWORD`, `COREVE_SMTP_PASS`, etc.).
3. **No Unverified Claims (Honesty Rule):**
   - Never claim a feature or fix works unless an explicit test command was executed and verified. Mark untested changes as `NOT TESTED`.
4. **Commercial Safeguards:**
   - Keep Cash on Delivery (COD) permanently disabled.
   - Do not alter the order numbering sequence from `KLE99817` without explicit user instruction.
   - Ensure the order notification dispatcher to `lenee@coreve.in` remains active and tested.
