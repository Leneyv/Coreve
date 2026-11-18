# 01 — Project Overview

## 1. Project Identification
- **Project Name:** Corive Tech Multi-Agent Control Plane (`myclone`)
- **System Repository:** `/opt/myclone`
- **Application Directory:** `/opt/myclone/app`
- **Live URL:** `https://myclone.coreve.in`
- **Host Server:** Ubuntu 24.04 LTS on Contabo VPS (`129.121.132.35`)
- **Project Type:** Agentic AI Autonomous Multi-Agent Control Plane & Operations Platform

---

## 2. Business Purpose & Objectives
Corive Tech operates an autonomous digital workforce built to streamline end-to-end commercial operations:
1. **B2B IT Services (Corive Tech):** Outbound discovery calls and WhatsApp qualification for custom web development, CRM, ERP, and automation projects across Kerala and India.
2. **Retail E-Commerce (Brandcart Kenya):** Direct-to-consumer appliance sales, stock consultations, warranty verification, and M-Pesa transaction management in Nairobi and upcountry Kenya.
3. **Executive Operations:** Autonomous data enrichment, founder research, competitor analysis, and direct instruction execution for Corive's founder.

### Primary Business Metric Goals:
- Zero manual effort in outbound lead calling and initial discovery.
- Strict 2-hour SLA on technical quotation reviews.
- Deterministic 50% advance / 50% completion payment milestone enforcement.
- Omni-channel coexistence on WhatsApp without phone number lock-in.

---

## 3. Autonomous Agent Personas

### A. Kavya (Kerala B2B Sales Executive)
- **Role:** Authorised B2B IT sales representative for Corive Tech representing 20+ years of enterprise engineering experience.
- **Dialect & Script:** 70% Malayalam in Malayalam script, 30% English (preserving technical terms like SEO, CRM, API, automation).
- **Communication Channels:** Outbound telephony via Sarvam AI (`+918064277369`), WhatsApp follow-up suggestions, Boss Command Desk.
- **Key Guardrail:** Strictly no hallucinated prices or delivery commitments. All quotes require explicit human owner approval before delivery.

### B. Celine Kamau (Brandcart Kenya Retail Specialist)
- **Role:** Technical sales consultant and product specialist for Brandcart Kenya (`brandcart.co.ke`).
- **Dialect & Style:** Kenyan English, conversational Swahili (*Karibu sana*, *Habari ya leo*), and contemporary Sheng (*mzigo sealed*).
- **Domain Mastery:** LG OLED/QNED/NanoCell TVs, Samsung QLED/Crystal UHD, Haier, Bosch, M-Pesa STK push, Nairobi same-day courier dispatch.
- **Key Guardrail:** Zero mention of unverified pay-on-delivery for upcountry orders; 100% factory-sealed original regional warranty guarantee.

### C. Maya AI me (Executive Digital Employee)
- **Role:** Dedicated, completely autonomous digital employee and personal operations specialist for founder Lenin.
- **Domain:** Autonomous task execution, founder and D2C brand enrichment, zero-fake-data cross-verification, continuous reinforcement learning.
- **Key Guardrail:** Complete separation from Kavya and Celine; strict local export synchronization to `/Users/lenee/Downloads/maya`.

---

## 4. Technology Stack & Versions

| Layer | Technology | Version | Purpose |
| :--- | :--- | :--- | :--- |
| **Language** | Python | `3.12.3` | Core runtime for backend web app, workers, and tools |
| **Framework** | Flask | `3.1.3` | Lightweight REST API and web controller |
| **WSGI Server** | Gunicorn | `23.0.0` | Production HTTP server running 1 worker, 3 threads |
| **Core Database** | PostgreSQL | `16+` | Primary relational store for leads, calls, ledger, and logs |
| **Postgres Driver**| psycopg (binary)| `3.3.9` | High-performance PostgreSQL 3 adapter |
| **Retail Database**| SQLite | `3.45.1`| Embedded isolated database for Celine Kenya (`celine_customers.db`) |
| **Web Server** | Nginx | `1.24+` | TLS termination, reverse proxy, HTTP Basic Auth, rate limits |
| **Node.js** | Node.js / npm | `v24.22.3` | Runtime for self-hosted n8n workflow engine |
| **Workflow Engine**| n8n | Embedded | Low-code orchestration and custom trigger execution |
| **LLM Inference** | Google Gemini | Gemini 3.8 Flash | Natural language parsing, JSON structured extraction |
| **Voice Telephony**| Sarvam AI | API v1 | Outbound conversational AI telephony (`+918064277369`) |
| **WhatsApp** | Meta Cloud API | Graph v21.0 | Official WhatsApp Business API with coexistence on `+919400199817` |
| **Payments** | Razorpay | `2.0.1` | Payment link generation, webhook validation, ledger balance |
| **Process Manager**| Systemd | Linux init | Daemon management (`myclone.service`, `myclone-worker.service`, etc.) |

---

## 5. Domain Routing & Network Architecture
- `https://myclone.coreve.in` : Authenticated UI dashboard and admin console (HTTP Basic Auth required).
- `https://myclone.coreve.in/workflow/` : Authenticated n8n workflow designer proxying to local port `5678`.
- Public Webhook Endpoints (Bypassing HTTP Basic Auth):
  - `/api/whatsapp/webhook` : Meta Cloud API inbound messages and delivery receipts.
  - `/api/webhook/sarvam/*` : Sarvam telephony post-call transcripts and event webhooks.
  - `/api/razorpay/webhook` : Razorpay payment link paid notifications.
  - `/api/celine/webhook` : Celine WhatsApp and Kenya order webhooks.
  - `/api/maya/*` : Maya asynchronous task triggers.

---

## 6. User Roles & Access Hierarchy
1. **Master Administrator / Boss (Lenin):** Full administrative privileges. Controls system pause states, initiates manual calls, approves quotations, chats directly with Kavya/Maya/Celine, and sends manual WhatsApp broadcasts.
2. **Technical Review Team:** Human engineers reviewing Kavya's confirmed deliverables within the 2-hour SLA window.
3. **Public Lead / Prospect:** External contact engaged via outbound phone call or WhatsApp message.
