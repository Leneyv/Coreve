# Kavya connected team — release 20 November 2026

Deployed at **https://myclone.coreve.in/#kavya-flow**. Outreach remains globally paused and all categories remain paused. No customer calls/messages, payment links or paid LLM generations were initiated for this release's tests.

## Working team

Kavya — **Brain & Team Leader** coordinates:

- **Research Team Leader:** Data Scraper, IT Researcher, SEO & Speed Researcher, plus up to five optional research specialists.
- **Communication Team Leader:** Caller — Sarvam, WhatsApp Communicator, Email Communicator and optional SMS Communicator.
- **Scope Writer**, **Quotation Coordinator**, **Payment Tracker**, **Technical Handover Coordinator**, **Sales & Cost Reporter**.

There are 15 standard roles. SMS is visible but paused and unconnected. Roles are bounded executable Python/PostgreSQL workflow stages, with shared Gemini reasoning where needed; they are not 15 separate paid model subscriptions. The tab is an **n8n-style visualization of this actual worker**, not a claim that the work runs as n8n workflows.

Each role has a runtime gate, reporting relationship, supported-tool selector and actual execution log. Historical registry counters were reset when mapped to executable roles so they are not presented as new runtime evidence. A paused member cannot start its guarded step. Pausing a member does not retroactively cancel an external operation already accepted by a provider.

## Research before outreach

The scraper records available public listing name, category, phone, address, website, Maps source and available rating/review/hours labels. Missing details remain unknown. Existing contact deduplication and campaign targets remain in place; existing calling eligibility and WhatsApp permission requirements are preserved.

Before outreach or scope handover, the research chain builds a cached dossier:

- Checks homepage and up to two relevant public pages.
- Observes booking/contact links, forms and public CRM/vendor signals; does not submit booking, enquiry or payment forms.
- Checks title, description, headings, canonical, viewport, HTTPS, noindex, image alt presence, structured-data types, robots and sitemap responses.
- Optional installed Chromium performs a mobile-viewport server lab run with navigation timings, FCP and an LCP sample. Blocked resources and measurement limits are included.
- Summarizes business-relevant routine tasks and unanswered questions for Kavya. Customer-confirmed systems are separate from public findings. Later corrections reach Kavya immediately even when technical research is cached.

These are bounded technical observations, not a complete functional/security audit, real-user Core Web Vitals, ranking measurements or evidence that a CRM is absent. GEO/AEO checks concern technical answer-readiness; there is no special schema or guaranteed AI recommendation claim. Google's guidance supports this distinction: [AI features and your website](https://developers.google.com/search/docs/appearance/ai-features).

A real free audit of `coreve.in` successfully inspected public pages and ran sandboxed Chromium timing. That is a lab measurement, not a customer-speed or ranking guarantee. [Why lab and field timing differ](https://web.dev/articles/lcp).

## Better sales discovery and voice

Text guidance and committed Sarvam **version 7** now require:

- Confirm existing website, CRM, booking and automation, one relevant question at a time.
- Keep existing systems when appropriate; discuss integration/improvements rather than automatically selling replacements.
- Explain a scoped **AI agent** handling agreed repetitive enquiries, reminders, booking coordination and follow-ups through configured WhatsApp/SMS/email/calls. Confirm supported language and technical feasibility rather than promising every language.
- Short, colloquial Malayalam with English technical words; answer the latest question first; stop for interruption; clarify uncertainty; no repeated introduction or fabricated booking attempts.
- Name-only introduction, affiliation disclosed when asked, truthful AI identification when asked.
- Scope based on actual requirements; no invented packages, delivery promises, tax treatment, prices or discounts.

The server selects Sarvam version 7. Prompt persistence and commit were checked in Chrome. **No acoustic call test was made in this release, so a “99% human” result is not certified.** Existing voice/telephony provider settings still need native listening and interruption validation before a live pilot.

## Boss Chat approval workflow

The owner gives a task → Kavya explains the proposed steps → an **Approve this plan** / **Reject** panel appears → only approval queues execution. Each plan and step has a durable ID, idempotent queue key and status. Results return to Boss Chat. Queued, provider-accepted and delivered/completed outcomes are kept distinct; delivery-sensitive plans wait for recorded provider outcomes.

Supported actions are bounded: research an exact lead, discover within an existing category, request a call/message, pause/resume global outreach, change category pause state, or save an owner-approved coaching instruction. Ambiguous or unsupported tasks ask for clarification; they do not execute arbitrary code, install tools or claim completion. Wider open-ended executive tasks are not silently treated as supported.

Brain choices: existing configured Gemini, or a free local Rule Planner for simple explicit commands. Research choices: free HTTP audit, installed Chromium audit or evidence summary. New specialists join the research pipeline, rather than merely creating decorative registry rows.

Chrome verification used Rule Planner to create a research-only plan for existing lead #6. It displayed approval controls, dispatched no jobs, and was rejected after verification. Gemini was restored. No live approved plan remains from this test.

## Owner quotation and payments

**50% at owner-confirmed mid-work; remaining 50% at handover. No advance.** Both sales guidance and customer quotation wording use this rule. Existing technical-milestone and verified Razorpay safeguards remain.

Scope handovers include customer details, current-system confirmations, research findings/unknowns and conversation history. New handover emails have a stored, scope-bound Message-ID. Natural owner replies such as “This will cost 24,000 and 2 weeks time we will do” can be parsed without typing a token, but only when:

- Sender is `lenee@coreve.in`, subject is a `Re:` containing `QUOTE REQUIRED`, receiving authentication passes, and reply references a recorded current-scope handover.
- One explicit price and delivery timeline can be extracted without conflicting or negative instructions.
- Email UID deduplication and scope/quote-revision checks pass.

Unspecified GST is labelled unspecified rather than invented; it must be resolved before final agreement. Legacy signed, explicit approvals remain supported. The independent worker intake checks every 25 seconds after each poll finishes, so slow research does not block email intake. Historical handovers without a recorded thread association are not retroactively trusted as token-free approvals.

## Validation

- **18 isolated team integration tests passed:** real registry gates, tool selection, cached research/current-system corrections, optional specialist execution, no action before approval, idempotent approval, no fabricated SMS connection, natural authenticated reply/thread handling, free planning, and delivery-sensitive plan completion.
- **13 earlier QC regression tests passed** with external providers mocked, protecting pause/hour/consent/quotation/payment and ambiguous-delivery behaviour.
- Complete dashboard JavaScript syntax checked.
- Native Chrome verified 15 role selectors, runtime relationships, tool switching, pending approval controls, rejected test-plan cleanup and restored Gemini selection.
- Web and worker services active; dashboard unauthenticated access still 401; zero enabled categories and global pause preserved; selected voice version 7.
- Protected source/database/config backup: `/var/backups/myclone-team-20261120`.

## Remaining boundaries

- Research/orchestration tools added here are free installed tools. Existing Gemini quota/billing, Sarvam calls, Meta messages and Razorpay fees still apply. No paid tools or subscriptions were purchased.
- SMS is not configured. Supported languages depend on the actual providers.
- Voice naturalness needs a listening test; full carrier/Meta/Razorpay delivery was not re-exercised using real customers or real money.
- Earlier reserved/uncertain calls still need provider reconciliation. Missing dedicated owner reminder voice configuration / owner WhatsApp report template from the earlier audit are not supplied by this release.
- Newly discovered contacts still need recorded calling eligibility; public listing presence does not create WhatsApp consent.


---

### WHATSAPP-CUSTOMER-SCOPE-20261120 — personal coexistence chats protected
Owner instructed: Kavya must auto-reply only to customers she contacted; personal incoming chats on the coexistence number must remain manual. Implemented deterministic provenance checks in whatsapp_scope.py, sales.receive_message/reply_job, providers.send_wa, worker.send_customer/followup. Eligibility requires a connected customer call or a genuine initial campaign / owner-approved plan WhatsApp outreach / approved quote send with a provider message record. A scraped contact, inbound message alone, manual owner reply, failed dial, owner reminder or accidental old bot reply never enrolls a personal chat. Personal messages remain in Master Inbox but do not queue AI replies or reach Gemini through this intake. Historical private chat records retained; manual owner messaging unchanged.

Deployed and verified: 10 isolated privacy tests passed again against production modules. Cancelled 2 pending personal automation jobs, reclassified 2 old personal-only generic enquiry threads, and saved an active privacy_rule coaching note. Both services active; local dashboard 200; existing global outreach pause preserved. Backup: /var/backups/myclone-whatsapp-scope-20261120-release. No customer test messages or calls sent. This rule supersedes any earlier blanket inbound auto-response behaviour.
