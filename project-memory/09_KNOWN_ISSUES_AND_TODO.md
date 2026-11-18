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
