# HALI Access Partner Portal — Phase 2 & 3 Scoping Document
## For Review & Sign-off by HALI Secretariat

> This document defines what has been built, what is planned next, and what requires client decisions before development can begin. **Do not quote Phase 2 or Phase 3 without signed answers to the open questions.**

---

## What Is Already Live (Phase 1)

The following is fully built and running at haliportal.tickooplug.co.ke:

| Module | Status |
|--------|--------|
| Invitation-only member onboarding | Live |
| Member & Friend of HALI roles | Live |
| Secretariat & Super Admin roles | Live |
| Member directory (searchable, filterable) | Live |
| Events (registration, agenda, attendance) | Live |
| Stories & Posts (members-only flag) | Live |
| Opportunities board (jobs, fellowships, scholarships) | Live |
| Resources library (auth-gated downloads) | Live |
| Member bulletins (broadcast email) | Live |
| Profile & organisation management | Live |
| Billing via Stripe (subscription + customer portal) | Live |
| Email notifications | Live |
| SMS notifications via Africa's Talking | Live |
| In-app notification centre | Live |
| Security headers, rate limiting, audit log | Live |

---

## Phase 2 — Membership Tiers, Billing Overhaul & Core Fixes

**Estimated scope:** 3–4 weeks  
**Can begin:** Once open questions below are answered

### 2.1 Membership Tiers & Dues

Replace the current single `member` / `friend` role distinction with a full tier matrix.

**What we know:**
- 3 Member tiers (e.g. Tier 1, Tier 2, Tier 3)
- 5 Friends of HALI tiers
- Each tier has an annual dues amount in USD
- The Secretariat assigns a tier to each member at the invitation stage
- Members see their tier and dues amount on their dashboard
- A "Pay Membership" button links to payment

**Open questions — MUST be answered before building:**

| # | Question | Why It Matters |
|---|----------|---------------|
| Q1 | What are the exact names and USD amounts for all 8 tiers? | Cannot build the plans table or dues display without this |
| Q2 | Can a member's tier be changed after they join? | Affects whether tier changes trigger prorated invoices |
| Q3 | Is the dues deadline a fixed calendar date (e.g. 31 Jan each year) or per-member anniversary date? | Changes the entire renewal logic |
| Q4 | What happens when dues are overdue — grace period before suspension? How many days? | Required to build the suspension trigger |

---

### 2.2 Billing & Payment Gateway

**Current state:** Stripe (card payments, USD).

**Requirement raised:** PayPal.

**Recommendation — do not use PayPal as the primary gateway for Kenyan members.** USD 300/year through PayPal requires members to hold a funded PayPal account, and Kenyan PayPal accounts cannot receive money. FX fees apply on both ends. Renewal rates will suffer.

**Recommended replacement:** Flutterwave or Pesapal.

| Gateway | M-Pesa | Card (Visa/MC) | USD Settlement | Notes |
|---------|:------:|:--------------:|:--------------:|-------|
| Flutterwave | Yes | Yes | Yes | Widely used, good API, settles in USD for international payments |
| Pesapal | Yes | Yes | KES only | Better for KES billing, weaker for international members |
| Stripe | No | Yes | Yes | Current — keep for non-Kenyan members |
| PayPal | No | Yes | USD | Not recommended as primary |

**Recommended approach:** Flutterwave as primary (covers M-Pesa + card + USD settlement) with Stripe retained as fallback for international members who prefer card.

**Open questions:**

| # | Question | Why It Matters |
|---|----------|---------------|
| Q5 | Is Flutterwave acceptable as the primary payment gateway? | Determines integration work |
| Q6 | Should members be able to pay in KES or USD only? | Affects exchange rate handling |
| Q7 | Who receives the money — HALI directly, or through a third party? | Determines Flutterwave account setup |

---

### 2.3 Invoices & Receipts

**Requirement:** Invoice and receipt download. "QuickBooks-formatted."

**This requirement has two very different meanings — clarify before building:**

**Option A — Portal-generated PDFs that look like the HALI QuickBooks template**
- Portal generates a PDF invoice on payment
- PDF is styled to match HALI's existing QuickBooks invoice layout
- No connection to QuickBooks whatsoever
- 1–2 weeks of work

**Option B — Real QuickBooks API integration**
- Portal creates customers and invoices directly in QuickBooks via API
- Requires QuickBooks OAuth setup, sandbox testing, customer sync
- 3–5 weeks of work minimum
- Ongoing maintenance if QuickBooks API changes

**Open questions:**

| # | Question | Why It Matters |
|---|----------|---------------|
| Q8 | Option A or Option B? | 2 weeks vs 5 weeks and a significant cost difference |
| Q9 | QuickBooks Online or QuickBooks Desktop? | Desktop has no cloud API — only Online supports integration |
| Q10 | Can you share a sample invoice/receipt from your current QuickBooks template? | Required to match the layout |

---

### 2.4 Suspension Logic

**Requirement:** Suspending a member blocks their login and makes them invisible across the portal and website.

**There are four separate behaviours — confirm which apply:**

| # | Behaviour | Include in Phase 2? |
|---|-----------|:------------------:|
| S1 | Suspended user cannot log in | Yes (already built) |
| S2 | Suspended user hidden from member directory | Confirm |
| S3 | Suspended user's published content (posts, opportunities) hidden | Confirm |
| S4 | Suspended user invisible on the public HALI website | Confirm — requires website integration |

S4 requires access to the current HALI website stack. See Phase 3 — Website Integration.

---

### 2.5 Cross-Posting Fix

**Requirement:** Blog posts are currently posting twice. Fix this.

**Assessment:** This is a bug in the current post creation flow — likely a form double-submit or a duplicate Livewire event. Will be investigated and fixed as part of Phase 2 at no additional cost.

---

## Phase 3 — Storage, Chat & Website Integration

**Estimated scope:** 6–10 weeks (after Phase 2)  
**Requires:** Phase 2 complete + answers to open questions below

### 3.1 File Storage (Recordings, PDFs, Invites)

**Requirement:** Storage for recordings, PDFs, and invite documents. Shareable links with access control ("choose who to send to").

**Current state:** The portal stores files on the shared cPanel server. This is not suitable for video recordings or large PDFs at scale.

**Recommendation:** Migrate to dedicated object storage.

| Provider | Cost (est.) | Notes |
|----------|------------|-------|
| Wasabi | ~$7/TB/month | S3-compatible, no egress fees — best value |
| Backblaze B2 | ~$6/TB/month | Cheap, reliable, S3-compatible |
| DigitalOcean Spaces | ~$25/250GB/month | Includes CDN |
| AWS S3 | Variable | More expensive but widest ecosystem |

**Open questions:**

| # | Question | Why It Matters |
|---|----------|---------------|
| Q11 | Approximate total storage needed now, and in 12 months? | Determines storage tier and recurring cost |
| Q12 | Who uploads recordings — only the Secretariat, or any member? | Determines upload permissions |
| Q13 | What is the maximum file size for a single upload? | Server PHP/Nginx limits must be configured |
| Q14 | "Choose who to send to" — does this mean: (a) generate a link and pick which members receive a notification, or (b) a full send-to-groups access control system? | Very different implementation |

---

### 3.2 Internal Chat

**Requirement:** Hali Network internal chat with main channel and side chat.

**Recommendation: Do not build this from scratch.**

Building real-time chat with channels, presence indicators, message history, notifications, and file sharing is 4–6 weeks of work that carries permanent maintenance cost. Every new device, browser version, or scaling event becomes your problem.

**Recommended approach — embed a self-hosted solution:**

| Option | Cost | Notes |
|--------|------|-------|
| Rocket.Chat (self-hosted) | Server cost only (~$10–20/month DO droplet) | Full-featured, open source, embeds via iframe or SSO |
| Matrix + Element | Free to self-host | Federated, strong encryption, good mobile apps |
| Discord (invite-only server) | Free | Fastest to set up, members likely already use it — not self-hosted |

**Charge for integration** (SSO so members log in automatically, branding, channel setup), not for building chat infrastructure from scratch.

**Open questions:**

| # | Question | Why It Matters |
|---|----------|---------------|
| Q15 | Is self-hosted Rocket.Chat acceptable, or does the client require a fully native in-portal experience? | Determines build vs integrate decision |
| Q16 | How many concurrent chat users expected? | Determines server sizing |
| Q17 | Should chat history be searchable and permanently archived? | Affects storage and compliance |

---

### 3.3 Portal ↔ Public Website Integration

**Requirement:** Suspended members should be invisible on the public HALI website (haliaccess.org). Potential for deeper integration.

**Current website:** haliaccess.org (stack unknown — needs investigation).

**Open questions:**

| # | Question | Why It Matters |
|---|----------|---------------|
| Q18 | What is the current haliaccess.org built on? (WordPress, custom, Webflow, etc.) | Determines if API integration is feasible |
| Q19 | Who manages the website? Is there access to the codebase/CMS? | Required before scoping integration |
| Q20 | Is the goal only suspension-sync, or full member profile display on the public site? | Very different scopes |

---

### 3.4 Two-Factor Authentication (2FA)

The database schema for 2FA already exists in the portal (fields are present on the users table). Activating it is a Phase 3 item recommended for admin accounts at minimum.

---

## Open Questions Summary

Before the next meeting, get answers to these. **Do not quote Phase 2 without Q1–Q10. Do not quote Phase 3 without Q11–Q20.**

| # | Question | Phase |
|---|----------|-------|
| Q1 | Exact names and USD amounts for all 8 membership tiers | 2 |
| Q2 | Can a member's tier change after joining? | 2 |
| Q3 | Fixed dues deadline or per-member anniversary date? | 2 |
| Q4 | Grace period before suspension for overdue dues? | 2 |
| Q5 | Flutterwave acceptable as primary payment gateway? | 2 |
| Q6 | KES or USD billing? | 2 |
| Q7 | Who receives the payments (Flutterwave account)? | 2 |
| Q8 | QuickBooks: PDF lookalike (Option A) or real API integration (Option B)? | 2 |
| Q9 | QuickBooks Online or Desktop? | 2 |
| Q10 | Sample QuickBooks invoice template? | 2 |
| Q11 | Estimated storage needed (now and in 12 months)? | 3 |
| Q12 | Who can upload recordings? | 3 |
| Q13 | Maximum file size per upload? | 3 |
| Q14 | "Send to" = notification or access control? | 3 |
| Q15 | Self-hosted chat acceptable or must be native? | 3 |
| Q16 | Expected concurrent chat users? | 3 |
| Q17 | Chat history archived permanently? | 3 |
| Q18 | What is haliaccess.org built on? | 3 |
| Q19 | Who manages the website / is there code access? | 3 |
| Q20 | Suspension-sync only, or full member display on public site? | 3 |

---

## What to Do Before the Next Meeting

1. **Send this document to the HALI Secretariat** and ask them to answer Q1–Q10 in writing before Phase 2 begins
2. **Do not start Phase 2 billing work** until Q5–Q10 are confirmed — the gateway and QuickBooks questions alone can shift the estimate by 3+ weeks
3. **Get website access** (Q18–Q19) before promising any website integration
4. **Push back on PayPal** — present the Flutterwave alternative at the next meeting with the rationale above

---

*Prepared by the development team — April 2026*
