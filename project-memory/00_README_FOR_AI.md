# Coreve Project Memory - AI Handover & QC Guide

> **Important for any AI Agent / Auditor / Engineer reading this:**
> This repository contains the full living context of the Coreve project. Read this file first, then consult the remaining files in `/project-memory` before modifying code, diagnosing bugs, or running deployments.

---

## 1. 10-Line Project Summary
1. **Brand & Product:** Coreve (`coreve.in`) is an Indian luxury D2C footwear brand specializing in women-first leather wedge sneakers (65mm heel, 25mm forefoot, EU sizes 37–41).
2. **Current Architecture:** Headless/Hybrid WordPress + WooCommerce hosted on Ubuntu 24.04 LTS (Nginx + PHP 8.3-FPM + MariaDB/MySQL).
3. **Storefront Routing:** The live storefront is rendered via a high-performance modern template system in `wp-content/coreve-storefront/` hooked via `wp-content/mu-plugins/coreve-storefront-loader.php`.
4. **Checkout & Payments:** Built natively on WooCommerce with Razorpay payment gateway integration (`woo-razorpay`) and custom modal handoff in `payment.js`.
5. **Order Sequencing:** Custom atomic sequential numbering starting from `KLE99817` (`KLE99818`, `KLE99819`, etc.) implemented via MySQL `LAST_INSERT_ID` in `functions.php`.
6. **Order Notifications:** Automated HTML order receipt and dispatch emails delivered to `lenee@coreve.in` via authenticated Zoho Mail SMTP (`smtp.zoho.in:465` SSL) via `wp-content/mu-plugins/coreve-smtp.php`.
7. **SEO / GEO / AEO:** Comprehensive Schema.org JSON-LD graphs (`Organization`, `WebSite`, `Product`, `FAQPage`, `BreadcrumbList`, `ItemList`), `llms.txt`, and `llms-full.txt` optimized for Googlebot and LLMs.
8. **Hosting Environment:** Single Oracle Cloud / VPS instance at `129.121.132.35` with Postfix loopback MTA, FastCGI caching, and SSL via Let's Encrypt Certbot.
9. **Local Repository:** `/Users/lenee/coreve/` tracks all themes, plugins, mu-plugins, and configuration templates in Git.
10. **Standing Rule:** Maintain this memory folder across every interaction. Update command logs, changelogs, feature statuses, and QC checklists before concluding any task.

---

## 2. Memory Index & File Guide
| File | Purpose |
| :--- | :--- |
| [`00_README_FOR_AI.md`](file:///Users/lenee/coreve/project-memory/00_README_FOR_AI.md) | Entry point, index, and 10-line executive project summary. |
| [`01_PROJECT_OVERVIEW.md`](file:///Users/lenee/coreve/project-memory/01_PROJECT_OVERVIEW.md) | Business goals, target audience, hosting details, tech stack, and user roles. |
| [`02_COMMAND_LOG.md`](file:///Users/lenee/coreve/project-memory/02_COMMAND_LOG.md) | Chronological append-only record of every user instruction (CMD-001 to present). |
| [`03_CHANGELOG.md`](file:///Users/lenee/coreve/project-memory/03_CHANGELOG.md) | Append-only ledger of code, config, server, and database changes (CHG-001 to present). |
| [`04_FEATURES_STATUS.md`](file:///Users/lenee/coreve/project-memory/04_FEATURES_STATUS.md) | Living matrix of all features and their verified operational status. |
| [`05_ARCHITECTURE.md`](file:///Users/lenee/coreve/project-memory/05_ARCHITECTURE.md) | Directory structure, data flows, routing mechanics, and ASCII architectural diagrams. |
| [`06_DATABASE_AND_APIS.md`](file:///Users/lenee/coreve/project-memory/06_DATABASE_AND_APIS.md) | Database tables, HPOS schemas, REST endpoints, webhooks, and third-party APIs. |
| [`07_ENV_AND_SETUP.md`](file:///Users/lenee/coreve/project-memory/07_ENV_AND_SETUP.md) | Environment configuration, secrets definitions, build/sync commands, and server maintenance. |
| [`08_DECISIONS.md`](file:///Users/lenee/coreve/project-memory/08_DECISIONS.md) | Architecture Decision Records (ADRs) explaining key choices and rejected alternatives. |
| [`09_KNOWN_ISSUES_AND_TODO.md`](file:///Users/lenee/coreve/project-memory/09_KNOWN_ISSUES_AND_TODO.md) | Prioritized backlog of technical debt, risks, and upcoming roadmap items. |
| [`10_QC_CHECKLIST.md`](file:///Users/lenee/coreve/project-memory/10_QC_CHECKLIST.md) | Standard Operating Procedure (SOP) test suite for human/AI quality control. |
| [`11_AGENT_AND_PROMPTS.md`](file:///Users/lenee/coreve/project-memory/11_AGENT_AND_PROMPTS.md) | Multi-agent configurations, prompts, and tooling specs (if applicable). |
