# Deployment addendum — 20 November 2026

**The prepared QC fixes are now deployed.** After the owner confirmed Antigravity was paused, its final Decimal-comparison and email self-reply improvements were preserved. All five production modules match the tested release. The 13 provider-mocked tests passed again against the deployed modules. Web and worker services are active, local dashboard responds 200, unauthenticated public access remains 401, global pause remains true and zero categories are active. At 09:30 the operating-hours check is false; report generation succeeds.

A protected source/database backup is at `/var/backups/myclone-qc-20261120`. One fabricated-booking coaching note and two exact duplicates were deactivated without deleting history; factual commercial guardrails were added. The corrections cover hours, authoritative pause, WhatsApp guard/failure/consent handling, ambiguous write retries, quotation terms, email approval checks and financial summaries. These supersede the **staged/not deployed** statuses in the original audit below.

**Still pending:** Sarvam voice/prompt and interruption testing; synchronization of coaching with the voice agent; reconciliation of historical uncertain calls; owner reminder voice agent and WhatsApp report template; a delivered-message/real-provider end-to-end test; and connecting visual agent registry controls to execution. No live customer outreach, paid LLM tests or financial actions were initiated. This remains a focused QC release, not an E2E certification.

---

## Original audit and deployment-attempt history (superseded where noted above)

# Kavya supervisor QC — 20 November 2026

**Verdict: NOT ready for an unattended end-to-end launch.** Antigravity's memory is useful, but several “Done and tested” entries do not match runtime code or prove delivery. This audit used all 12 memory documents as the reference set, current production Python source, PostgreSQL operational aggregates, configuration readiness flags, service checks and isolated provider-mocked regression tests. Browser layout, voice audio and provider-side delivery were not tested in this pass.

## Verified live state

- Web application and durable worker: active.
- Global pause: true. All three campaigns (Dental clinics, Dentists, physiotherapist) paused; each daily target is 100 and category calling window 10:00–18:30.
- Public dashboard rejects unauthenticated access with 401. Project memory is blocked with 403. The memory itself inconsistently claims 403 and 404; either denies access, but the recorded evidence is inconsistent.
- Customer Sarvam version selected on server: **6**. Prior draft improvements must not be described as active without confirming a committed version and server selection.
- WhatsApp automatic mode is now enabled and a token is configured. This supersedes the earlier manual-mode setup; configured credentials alone do not prove live message delivery.
- SMTP and IMAP credentials are now configured. Recipient is `lenee@coreve.in`, matching the later CMD-065 instruction. Older Gmail setup assumptions are stale.
- Owner reminder voice agent missing; owner WhatsApp report template missing. The old escalation timer is disabled; current quotation escalation is implemented by the queue worker.
- `report_data()` executes successfully now. Four historical report jobs remain failed; their complete delivery chain has not been retested.
- 301 customer-call jobs and 34 follow-up jobs are uncertain with “Call already reserved”; 335 call attempts remain reserved. These need provider reconciliation, not blind replay. Counts are historical, not today's failed-call rate.

## Findings and prepared repairs

| Priority | Finding | Evidence / consequence | Repair status |
|---|---|---|---|
| High | Global hours start at 09:00 despite 10:00 agreement | `core.working`, `next_open`, invalid-window fallback; category initial-call filter partly masks the defect but other dispatch paths remain affected | Fixed in staging; boundary and SLA tests pass |
| High | Boss Chat pause is ineffective | Writes `OUTREACH_PAUSED` JSON; actual worker/dispatch checks PostgreSQL `settings.paused` | Staged fix uses the authoritative DB field under the dispatch lock; tested |
| High | Active WhatsApp window bypasses outreach protections | `send_wa` passes `owner=in_window`, bypassing global pause, terminal/opt-out and consent checks for an active customer window | Staged fix keeps customer guards in force; tested |
| High | Failed WhatsApp sends can become completed jobs | Worker catches all send exceptions and returns normally; tick marks job done | Staged fix propagates failures and records conversation only after provider acceptance; tested |
| High | Follow-up manufactures WhatsApp permission | Sets `wa_opt_in=true` before sending; silently falls back to a voice call on failure | Staged fix never invents consent or changes channels silently; tested |
| High | Side-effect API requests are retried after ambiguous timeouts/server errors | Common HTTP helper can repeat call/message/payment requests | Staged fix retries only reads; ambiguous writes become uncertain for reconciliation; tested |
| High | Conflicting saved coaching | One active note directs a fabricated booking attempt; another mentions calls until 20:00; notes include duplicates | Stronger staged text prompt rules; existing database notes are unchanged and need cleanup. Voice coaching injection is not proven |
| High | Email approval changed during QC | Concurrent new code accepts absent authentication headers, optional/invalid approval tokens, guesses amounts and defaults delivery/GST | Merged staging version requires signed matching scope, authentication and explicit GST/DELIVERY/TERMS. Tests reject unauthenticated, unsigned and incomplete approvals |
| High | Concurrent quotation edit invents two-week delivery | New `quote_send` defaults to two weeks and promises immediate work | Staged merge removes invented default, includes approved terms and retains follow-up-compatible status |
| Medium | Wrong financial intelligence in Boss Chat | Sums all ledger entries as revenue, including spend; pending quote status query is inaccurate | Staged correction counts collections minus refunds, and actual pending-scope reviews |
| Medium | Unapproved pricing in internal prompt | Hardcoded ₹15,000 / ₹25,000–35,000; “50% advance / mid-work” | Staged removal; no advance, owner-approved pricing only |
| Medium | Callback context and opening overstate agreement | Any history is called a scheduled continuation; missing history gets fabricated booking follow-up context; company announced proactively | Staged name-only greeting, factual continuation context; existing Sarvam prompt still requires independent inspection |
| Medium | Ambiguous price question invents project scope | Default website plus WhatsApp booking package in `reply_job` | Staged fallback explicitly says deliverables are unconfirmed and requires owner review |
| Medium | Visual swarm is not an executable swarm | `kavya_agents` is read/written only by registry UI/API; worker does not consult its active switches, prompts or telemetry | Architecture/documentation correction required; adding a registry row does not provision a working agent |
| Medium | “Training” is inconsistent across channels | DB notes used by text sales and Boss Chat; no lookup of these notes in call payload construction | Do not promise that saving a Boss Chat rule trains Sarvam's voice agent |
| Medium | Memory QC procedures are unreliable | References nonexistent `is_operating_hours`, incorrect webhook path expectations, advance-payment ledger names and stale model versions | Corrections recorded; checklist needs replacement with reproducible tests |

## Deployment status — important

**No production application fixes from this QC have been deployed.** Hash checks detected successive concurrent edits to `sales.py`, then `worker.py`, then `sales.py` again. Each check stopped before mutation; neither services nor live files were overwritten. Continuing to merge while another developer is writing these same files would invalidate the review.

The five repaired modules are staged at `/opt/myclone/qc-20261120/` and locally under `work/qc-20261120/fixed/`. The guarded deployment script refuses stale source, creates a protected source/database backup, preserves pause state and restores source on failure. It must be rebased against the final Antigravity changes before deployment.

## Validation evidence

13 isolated regression tests passed using fake provider responses and mocked database connections. Covered:
- 10:00 opening, 18:30 closure, Sunday and next-business-day handling;
- quotation deadline and working-minute arithmetic;
- authoritative Boss Chat pause;
- active-window WhatsApp guard enforcement;
- failure propagation without fabricated sent transcript;
- no fabricated consent or silent voice fallback;
- no duplicate write retries, while read retries remain;
- real milestone required before payment-link generation;
- unsigned Razorpay events rejected;
- email approvals require authentication, matching signed token and explicit terms.

These are focused regression tests, **not a complete E2E certification**. No paid LLM generations, customer calls, WhatsApp messages, test payment links or outbound test emails were initiated during this QC.

## Required before launch approval

1. Pause Antigravity edits, take a fresh source snapshot, merge/retest/deploy the prepared fixes.
2. Independently inspect committed Sarvam prompt, variables, voice, interruption detection and callback behaviour; run an owner voice test. A text prompt cannot guarantee a human-sounding voice.
3. Reconcile reserved/uncertain calls against Sarvam; never bulk retry unknown delivery outcomes.
4. Remove or supersede conflicting coaching notes across text and voice; do not fabricate appointment attempts.
5. Configure a separate owner reminder agent and reporting template; verify authenticated inbound email approval and actual email/WhatsApp delivery receipts.
6. Exercise complete quote → owner approval → customer acceptance → actual mid-work → 50% payment → handover → final payment → technical handoff with isolated fixtures/sandbox providers.
7. Verify a new-day discovery run reaches 100 unique contacts per active category, cross-category dedupe, pause enforcement and honest shortfall reporting. Paused campaigns cannot demonstrate this now.
8. Replace registry-only controls with connected worker controls, or clearly label them as a visual registry.

Payment rule remains **50% at owner-confirmed mid-work, 50% at handover; no advance**. Existing `create_payment_link` correctly requires approved and accepted current scope plus owner-marked milestone readiness; staged regression tests protect this behaviour.
