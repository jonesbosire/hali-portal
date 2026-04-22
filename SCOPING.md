# HALI Access Partner Portal — Phase 2 & 3 Scoping Document
## Confirmed Specifications & Remaining Open Questions

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
**Status:** All Phase 2 questions answered — ready to begin

---

### 2.1 Membership Tiers & Dues ✓ Confirmed

| Spec | Decision |
|------|----------|
| Initial tier | Start with "Member Tier 1" — admin creates and edits all tiers from the admin panel |
| Tier names & amounts | Admin-managed — Secretariat sets names and USD amounts per tier |
| Tier changes | A member can request a tier upgrade — admin approves and reassigns |
| Dues deadline | Per-member anniversary date (date they joined / were activated) |
| Grace period | 7 days after due date before account is suspended |
| Auto-suspension | After 7-day grace period, account suspends automatically |

**What gets built:**
- Admin panel: full tier CRUD (create, name, set price, activate/deactivate)
- Invitation flow: Secretariat selects a tier when inviting a member
- Member dashboard: shows current tier, dues amount, due date, and "Pay Membership" button
- Automatic suspension trigger: runs daily, suspends accounts 7 days past due date
- Admin can manually override status at any time

---

### 2.2 Billing & Payment Gateway ✓ Confirmed

| Spec | Decision |
|------|----------|
| Primary gateway | Flutterwave |
| Currency | Member selects at checkout — KES, USD, or other supported African currencies |
| Settlement | HALI's Flutterwave account receives funds directly |
| Secondary gateway | Stripe retained for international members who prefer card-only |
| PayPal | Not used |

**What gets built:**
- Flutterwave checkout integrated into the "Pay Membership" flow
- Currency selector at checkout (KES / USD / member's local currency)
- Webhook handler: payment confirmed → subscription activated, receipt generated
- Secure implementation: HMAC signature verification on all Flutterwave webhooks, no card data touches the server
- Stripe kept as fallback for non-African members

> **Note on Q7:** You mentioned "HALI PayPal account" — since we're using Flutterwave, this means HALI's **Flutterwave account** receives the money. Confirm HALI will create a Flutterwave business account if they don't already have one.

---

### 2.3 Invoices & Receipts ✓ Confirmed

| Spec | Decision |
|------|----------|
| Integration type | Option B — real QuickBooks API integration |
| QuickBooks version | QuickBooks Online |
| Invoice template | Use QuickBooks default layout (no custom template needed) |

**What gets built:**
- QuickBooks Online OAuth integration (admin authorises once from portal settings)
- On payment: customer created/updated in QuickBooks, invoice generated and marked paid
- Invoice downloadable as PDF from member's billing page
- Receipt emailed to member on payment

**Note:** QuickBooks API integration requires QuickBooks Online admin credentials during setup. Plan for a testing phase against the QuickBooks sandbox before going live.

---

### 2.4 Suspension Logic ✓ Confirmed

All four suspension behaviours confirmed — applies when admin manually suspends OR auto-suspension triggers after grace period:

| Behaviour | Status |
|-----------|--------|
| Suspended user cannot log in | Already built |
| Suspended user hidden from member directory | Phase 2 |
| Suspended user's posts and opportunities hidden across portal | Phase 2 |
| Suspended user invisible on public HALI website | Phase 3 (requires website integration) |

---

### 2.5 Portal ↔ Website Content Sync ✓ Clarified

This is not a bug fix — it is a **new feature**: posts published on the portal appear on the public HALI website, and posts published on the website appear on the portal. Bidirectional sync.

This requires access to the haliaccess.org website stack. Moved to Phase 3 — Website Integration, where the full sync scope will be defined.

---

## Phase 3 — Storage, Chat, Website Integration & 2FA

**Estimated scope:** 6–10 weeks (after Phase 2)
**Status:** Some open questions remain — listed below

---

### 3.1 File Storage ✓ Partially Confirmed

| Spec | Decision |
|------|----------|
| Storage provider | Wasabi (~$7/TB/month, S3-compatible, no egress fees) |
| Access control | Secretariat/Admin can share files with: all members, Friends of HALI only, or specific selected members |
| Notification on share | Yes — selected members receive an in-app and email notification with the link |

**Still needed before building:**

| # | Question | Why It Matters |
|---|----------|---------------|
| Q11 | Approximate storage needed now and in 12 months? | Determines Wasabi bucket sizing and cost estimate |
| Q12 | Who can upload? Secretariat only, or any member? | Determines upload permissions in the UI |
| Q13 | Maximum file size per upload? | Server and Wasabi limits must be configured |

---

### 3.2 Internal Chat

**Recommendation stands: do not build from scratch.**

Real-time chat with channels, presence, history, and notifications is 4–6 weeks of work with permanent maintenance cost. Recommended approach: embed Rocket.Chat (self-hosted, ~$15/month server) with single sign-on so members log in automatically using their portal account.

**Still needed before building:**

| # | Question | Why It Matters |
|---|----------|---------------|
| Q15 | Is self-hosted Rocket.Chat embedded in the portal acceptable? | If client insists on fully native chat, the scope and price change significantly |
| Q16 | How many members are expected to be online simultaneously? | Determines server sizing |
| Q17 | Must chat history be permanently archived and searchable? | Affects storage and data retention policy |

---

### 3.3 Portal ↔ Public Website Integration

Covers two requirements:
1. **Suspension sync** — suspended portal members become invisible on haliaccess.org
2. **Content sync** — posts on portal appear on website and vice versa

**Still needed before building:**

| # | Question | Why It Matters |
|---|----------|---------------|
| Q18 | What is haliaccess.org built on? (WordPress, Webflow, custom?) | Determines whether API integration is feasible at all |
| Q19 | Who manages the website and is there access to the codebase/CMS? | Required before any integration work begins |
| Q20 | For content sync — one source of truth (portal pushes to website) or true bidirectional? | Bidirectional is significantly more complex |

---

### 3.4 Two-Factor Authentication (2FA)

Database schema already exists. Phase 3 will activate 2FA, recommended as mandatory for admin and secretariat accounts.

---

## Remaining Open Questions

| # | Question | Phase | Blocking? |
|---|----------|-------|-----------|
| Q7 | Will HALI create a Flutterwave business account to receive payments? | 2 | Yes — needed before billing integration |
| Q11 | Approximate storage needed now and in 12 months? | 3 | No — Wasabi confirmed, size TBD |
| Q12 | Who can upload files — Secretariat only or any member? | 3 | No |
| Q13 | Maximum single file upload size? | 3 | No |
| Q15 | Rocket.Chat embed acceptable or must chat be fully native? | 3 | Yes — changes scope significantly |
| Q16 | Expected concurrent chat users? | 3 | No |
| Q17 | Chat history permanently archived? | 3 | No |
| Q18 | What is haliaccess.org built on? | 3 | Yes — cannot scope website integration without this |
| Q19 | Website code/CMS access available? | 3 | Yes |
| Q20 | Content sync: one-way or bidirectional? | 3 | Yes — different build entirely |

---

## Phase Summary

| Phase | Scope | Estimated Time | Status |
|-------|-------|---------------|--------|
| **Phase 1** | Core portal (auth, directory, events, posts, opportunities, billing, notifications) | Complete | Live |
| **Phase 2** | Membership tiers, Flutterwave, QuickBooks Online, enhanced suspension | 3–4 weeks | Ready to begin |
| **Phase 3** | Wasabi storage, Rocket.Chat, website integration, content sync, 2FA | 6–10 weeks | Pending Q18–Q20 |

---

*Last updated: April 2026*
