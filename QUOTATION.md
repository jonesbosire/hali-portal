# Development Quotation
## HALI Access Partner Portal

---

| | |
|--|--|
| **Quotation No.** | QT-HALI-2026-001 |
| **Date** | April 23, 2026 |
| **Prepared by** | Jones Bosire |
| **Client** | HALI Access Network |
| **Valid until** | May 23, 2026 |

---

## Original Agreement — Phase 1

*Member portal for the HALI Access Network.*
*Original quoted amount: KES 80,000*

| # | Module | Description | Amount (KES) |
|---|--------|-------------|-------------|
| 1 | Invitation-only onboarding | Secure invite system with time-limited tokens, email delivery, and account creation flow | 8,000 |
| 2 | Role & permission system | Four roles — Member, Friend of HALI, Secretariat, Super Admin — each with scoped access across the portal | 5,000 |
| 3 | Member directory | Searchable and filterable directory of member organisations with public profiles, country and type filters | 7,000 |
| 4 | Events management | Full event lifecycle — creation, agenda builder, member registration, attendance tracking, attendee export | 10,000 |
| 5 | Stories & posts | Content publishing with members-only flag, categories, featured post, rich text editor | 7,000 |
| 6 | Opportunities board | Jobs, fellowships, and scholarships — posted by members, with deadlines, location, and type filters | 6,000 |
| 7 | Resources library | Auth-gated file downloads — members only, organised by category | 5,000 |
| 8 | Member bulletins | Broadcast email system for Secretariat to reach all members at once | 4,000 |
| 9 | Profile & organisation management | Member profiles with avatar, bio, LinkedIn. Organisation profiles with logo, country, team roster | 7,000 |
| 10 | Notification centre | Email, SMS (Africa's Talking), and in-app notifications with unread badge and mark-as-read | 8,000 |
| 11 | Admin panel & dashboard | Admin dashboard with live stats, full management of members, events, content, invitations, and organisations | 9,000 |
| 12 | Security | Security headers, rate limiting, audit log, session protection, CSRF, soft deletes across all models | 4,000 |
| | | **Phase 1 Subtotal** | **80,000** |

---

## Additional Modules — Phase 2

*Requested after original agreement.*

| # | Module | Description | Amount (KES) |
|---|--------|-------------|-------------|
| 13 | Membership tiers & dues | Admin-managed tier CRUD with pricing and features. Tier assigned at invitation. Member dues dashboard showing status, due date, grace period warnings, and auto-suspension after 7-day grace | 8,000 |
| 14 | Flutterwave payment integration | Multi-currency checkout (KES, USD, GHS, NGN, ZAR, UGX, TZS). Card, M-Pesa, and bank transfer. HMAC-verified webhook. Dues extended on payment, suspended accounts reinstated | 7,000 |
| 15 | QuickBooks Online integration | OAuth 2.0 connect from admin panel. Auto-creates customer and paid invoice in QuickBooks on every successful payment. Silent skip if QuickBooks not connected | 5,000 |
| | | **Phase 2 Subtotal** | **20,000** |

---

## Summary

| | Amount (KES) |
|--|-------------|
| Phase 1 — Core portal (12 modules) | 80,000 |
| Phase 2 — Tiers, Flutterwave, QuickBooks (3 modules) | 20,000 |
| **Total Development Fee** | **100,000** |

---

## Not Included in This Quotation

The following are planned for Phase 3 and will be quoted separately:

| Module | Notes |
|--------|-------|
| In-app chat | Rocket.Chat embedded with single sign-on — pending client confirmation on hosting model |
| Wasabi file storage | Shared file library with role-based access — pending storage sizing answers |
| Public website integration | Portal ↔ haliaccess.org content sync and suspension visibility — pending website stack details |
| Two-factor authentication | Database schema already in place — activation deferred to Phase 3 |

---

## Third-Party Services

Ongoing costs paid directly by HALI to each provider — not included in the development fee.

| Service | Purpose | Cost |
|---------|---------|------|
| Web hosting | Runs the portal | Existing plan |
| Email (SMTP) | Invitations, receipts, notifications | Free tier sufficient at launch |
| Flutterwave | Processes member dues payments | No monthly fee — % per transaction only |
| Africa's Talking | SMS notifications | ~KES 1–2 per SMS |
| QuickBooks Online | Accounting sync | HALI's own subscription |

---

## Payment Terms

| Milestone | Amount (KES) |
|-----------|-------------|
| Deposit on agreement (paid) | 50,000 |
| Balance on delivery | 50,000 |
| **Total** | **100,000** |

**Payment via:** M-Pesa / Bank Transfer
*(Add your payment details here before sending)*

---

*Prepared by Jones Bosire · April 2026*
*This quotation covers development services only. Third-party service costs are paid directly by HALI to the respective providers and are not part of this agreement.*
