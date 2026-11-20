# Kavya Boss Dashboard — 20 November 2026

Live: **https://myclone.coreve.in/#boss**

## What is available

- Today, Yesterday, Last 7 Days and All Time using Asia/Kolkata calendar boundaries.
- Unique new business leads; current progress of the selected lead cohort; exact newly recorded status transitions; current pipeline stages.
- Connected customer calls, recorded call requests/reservations, automated WhatsApp acceptance/delivery/read counts, accepted sales and confirmed collections.
- Itemized ledger spend, confirmed vs estimated, and a separately labelled voice-usage estimate using rounded billed minutes. Estimates are not added to recorded spend twice.
- Live running, blocked and uncertain jobs; customer/category targets; worker heartbeat; actual agent completion/blocked/failed counts for each requested period.
- Per-agent work-history drilldowns: latest 50 steps for the selected period, customer/business, status, timestamps and safe technical summaries. Raw customer conversation contents are not exposed in these summaries.
- Daily call and customer WhatsApp safety caps. They are optional and remain unset; saving them does not resume outreach. Calls include reserved/unknown attempts conservatively. New WhatsApp writes are reserved before provider submission; ambiguous outcomes cannot be blindly repeated, while a known provider rejection releases its reservation.
- A manually updatable Sarvam credit snapshot, read-only Meta connection/tier/template/pricing refresh, direct recharge/billing links, and a JSON report download.

## Verified provider state at release

**WhatsApp:** configured credentials passed a read-only Meta Graph API check; phone quality GREEN. The WABA was verified to contain Kavya's configured number before account information was read. Provider records include 2 delivered and 6 read automated messages. The current Meta tier is TIER_2K (2,000 unique recipients in a rolling 24-hour portfolio limit), and no approved templates were returned. The API reported service-message traffic at zero cost for the available daily data. This is provider-reported fee data for this number, not an assertion that all historical WhatsApp activity was free or that the wallet has a verified balance.

Kavya can reply to previously contacted customers when an eligible chat window is open, subject to the existing pause, schedule, consent and agent gates. Proactive/out-of-window messages need an approved template. The customer-only personal-chat protection is unchanged.

**Sarvam:** the organisation ID on its General settings page matched the configured Kavya organisation. Billing displayed Pay as you go and **−112.10 credits**. This is an organisation-wide snapshot observed on 20 November 2026, not a live feed or Kavya-only expenditure. The dashboard raises a recharge alert, shows when the snapshot was checked and flags it stale after 30 minutes. After recharge the owner can copy the new balance into the optional snapshot field.

**Meta wallet:** not verified. Opening the direct billing page led to Meta's passkey/sign-in check. No authentication bypass, recharge, payment or billing-setting change was performed.

## Direct links

- Sarvam credits: https://indus.sarvam.ai/settings/billing
- Kavya WhatsApp billing: https://business.facebook.com/latest/billing_hub/accounts/details/?payment_account_id=2212173152726210&business_id=614308927969989&asset_id=972757048782467
- WhatsApp templates: https://business.facebook.com/latest/whatsapp_manager/message_templates/?asset_id=972757048782467

## Measurement boundaries

Exact lead stage history begins with this installation. Existing rows were baselined rather than assigned fabricated transition dates. Historical views distinguish current progress of leads created in that period from exact timestamped transitions. Contact-started is an administrative workflow stage, not proof of qualification or a sale.

Real-business lead totals require a business record and a usable Indian phone number, exclude marked tests, personal-only inbox records and configured owner numbers, and count unique phone numbers. Public listing data is not independent phone verification. At the UI check, the all-time view showed **518 unique business leads** and **140 connected customer calls**. Call request counts include unresolved reservations; they are not claimed to be confirmed carrier dials.

Meta's rolling recipient limit is shared at portfolio level. Local counts do not expose all portfolio usage, so exact remaining provider quota remains **Not verified**, rather than displaying a fabricated tier-minus-local value. The dashboard separately shows local daily budget remaining and eligible customer reply windows. No daily caps were invented or enabled.

Sarvam public PAYG pricing is used only as a labelled reference for voice estimates. Telephony, GST, other organisation products, unknown failed-dial charges and invoice differences are not silently treated as zero. Recorded ledger spend is incomplete until provider reconciliation. Provider-reported Meta cost is displayed separately to avoid double-counting the ledger.

## Validation and operating state

- 17 isolated dashboard/integration tests passed: date boundaries, owner/test/personal exclusions, baseline/history integrity, acceptance-date sales, voice rounding, daily cap enforcement, reservation idempotency, known rejection release, secret omission, owner same-origin settings, agent counts and private step summaries, and confirmed-zero vs unavailable Meta costs.
- 10 existing WhatsApp customer-only privacy tests passed against the deployed modules using the isolated test database.
- Complete JavaScript syntax check passed.
- Chrome verified the live layout, all-time figures, all four comparison rows, 15 agent drilldowns, correct 15-agent sidebar and direct billing links. An actual agent-history dialog opened and closed successfully.
- Web and worker services remain active. Outreach/global/category pauses are preserved. No live customer test messages, paid calls, paid LLM generations or payments were initiated.
- Protected original source/database backup: `/var/backups/myclone-boss-dashboard-20261120`.

Provider references: [Meta WABA limits](https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account), [Meta pricing analytics](https://developers.facebook.com/docs/graph-api/reference/whats-app-business-account/pricing_analytics/), [Sarvam billing](https://docs.sarvam.ai/api/platform/billing), [Sarvam voice pricing/rounding](https://docs.sarvam.ai/voice-agents/pricing).
