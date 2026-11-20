# 08 — Architectural Decisions (ADR)

This record captures key architectural and technical decisions, the problem context, chosen solutions, and alternative options that were evaluated and rejected.

---

### ADR-001: Autonomous Multi-Agent Segregation vs Monolithic Bot
- **Context:** The business requires handling distinct domains: Kerala B2B IT sales (Kavya), Brandcart Kenya retail e-commerce (Celine Kamau), and executive intelligence (Maya AI me).
- **Decision:** Implement strict isolation between agents with separate Python blueprints, dedicated database tables, distinct prompts, and isolated UI sub-views. Maya and Celine are strictly barred from interfering with Kavya's pipeline.
- **Alternatives Rejected:**
  - *Monolithic single prompt agent:* Fails due to context contamination, confused linguistic registers (mixing Swahili and Malayalam), and dangerous cross-domain hallucinations.
  - *Microservices on separate servers:* Rejected due to unnecessary operational complexity, multi-server hosting costs, and complex inter-service RPC overhead.

---

### ADR-002: Dual Database Strategy (PostgreSQL + SQLite)
- **Context:** Kavya and the core platform operate complex relational pipelines (leads, ledger, jobs, audit logs) requiring ACID transactions and concurrency. Celine operates retail inventory and customer lists specific to a single subsidiary store in Kenya.
- **Decision:** Use PostgreSQL for the core system (`myclone`), and use an embedded SQLite database (`celine_customers.db`) for Celine.
- **Alternatives Rejected:**
  - *Storing everything in one PostgreSQL schema:* Poses schema migration risks across unrelated business models and bloats lead tables.
  - *Running a separate Postgres database instance:* Unnecessary resource overhead on a single VPS with 4GB RAM.

---

### ADR-003: Official Meta Cloud API with Coexistence vs 3rd-Party BSPs
- **Context:** The owner uses their primary business number `+919400199817` on a physical phone with WhatsApp Business app, while also wanting automated inbox management and API-driven messaging.
- **Decision:** Utilize official Meta Cloud API with Phone Coexistence enabled. In the UI, provide dual actions: `📱 Open in WhatsApp Web / App` (bypasses 24-hour customer service window) and `⚡ Send Meta Official Template` (triggers pre-approved Meta HSM via Cloud API).
- **Alternatives Rejected:**
  - *Third-party BSPs (Twilio, WATI, AiSensy):* High per-conversation markup, vendor lock-in, and frequent coexistence synchronization failures.
  - *Unofficial WhatsApp Web scraping (Puppeteer / Baileys):* High risk of phone number ban by Meta, fragile session QR drops, and unreliable message delivery.

---

### ADR-004: Context-Accumulating Outbound Telephony
- **Context:** Early outbound callbacks to scheduled leads (e.g. Dr. Lenny) caused Kavya to ask generic introductory questions ("Hello, I am Kavya. Who am I speaking with?"), causing friction with leads who had already spoken to Corive.
- **Decision:** Accumulate full prior conversation transcripts (`lead['conversation_transcript']`) into the Sarvam call dossier. Explicitly pass `customer_name` and `prior_conversation` as agent variables, instructing Kavya to greet the lead by name and resume the conversation seamlessly.
- **Alternatives Rejected:**
  - *Stateless call prompts:* Results in repetitive, robotic discovery questions that alienate qualified leads.

---

### ADR-005: Deterministic 50/50 Milestone Payments via Razorpay
- **Context:** Custom IT services are vulnerable to scope creep and unpaid completion deliveries.
- **Decision:** Strictly enforce a 50% advance deposit before commencement and 50% handover payment upon completion. All payment links are generated deterministically through the Razorpay API with unique IDs recorded in the `ledger` table.
- **Alternatives Rejected:**
  - *Unstructured bank transfers:* High manual reconciliation burden and delayed milestone handoffs.
  - *Stripe / International gateways:* High currency conversion fees and lack of UPI support in Kerala.

---

### ADR-006: View Isolation with Enforced Inline Style Overrides
- **Context:** Switching between Master Inbox and other agent views (Kavya, Celine, Maya) resulted in view masking where `#view-master-inbox` overlapped other dashboards due to inline `display: flex;`.
- **Decision:** Add `.category-view { display: none !important; }` in CSS and enhance `navigateTo()` in `index.html` to programmatically apply `setProperty('display', 'none', 'important')` to all non-active views.
- **Alternatives Rejected:**
  - *Multi-page multi-HTML routing:* Requires full page reloads, drops ongoing WebSocket/polling states, and slows down admin interaction.


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
