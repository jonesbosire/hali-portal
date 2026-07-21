# HALI Access Partner Portal
## Platform Proposal & Feature Overview

Prepared by Jones Bosire · April 2026
Prepared for HALI Access Network

---

## Overview

The HALI Access Partner Portal is a private, invitation-only web platform built exclusively for the HALI Access Network. It gives member organisations, the Secretariat, and programme officers a single place to connect, stay informed, manage events, pay membership dues, and access HALI resources.

The portal is live at **haliportal.tickooplug.co.ke** and is built to grow with HALI — from the current network of 16+ member organisations to hundreds of members across Africa.

---

## Who Uses the Portal

| Role | Who They Are | What They Can Do |
|------|-------------|-----------------|
| **Super Admin** | HALI technical lead | Full access to everything — user management, settings, all content |
| **Secretariat** | HALI staff | Invite members, manage tiers and dues, send bulletins, manage all content and events |
| **Member** | Member organisation staff | Access all portal features, pay dues, post opportunities, register for events |
| **Friend of HALI** | Partner organisations | Access public portal content and events |

Access is strictly invitation-only. No one can create an account without receiving a personal invitation from the Secretariat.

---

## Features Built

### 1. Invitation-Only Onboarding

The Secretariat sends a secure, time-limited invitation to any email address. The recipient clicks the link, sets a password, and their account is created automatically with the correct role and membership tier pre-assigned. Invitations expire after 7 days and cannot be reused.

---

### 2. Member Directory

A searchable, filterable directory of all member organisations. Members can search by name, filter by country or organisation type, and view full organisation profiles including team members, description, and active opportunities.

Only active members are visible — suspended accounts are automatically removed from the directory.

---

### 3. Events Management

The Secretariat creates events with full details — date, time, location (physical or online), agenda, and capacity. Members register directly from the portal. The admin panel shows a live attendee list, allows marking attendance, and exports the full attendee list to a spreadsheet.

---

### 4. Stories & Posts

A content publishing area for HALI updates, member stories, announcements, and press releases. Content can be marked members-only (hidden from non-members) or public. The Secretariat controls all published content from the admin panel.

---

### 5. Opportunities Board

Members post jobs, fellowships, scholarships, and grants for the network. Each opportunity shows a deadline, location, type, and description. Non-members see only publicly listed opportunities. Opportunities from suspended members are automatically hidden.

---

### 6. Resources Library

A gated file library where HALI stores guides, templates, reports, and other documents. Files are accessible to authenticated members only — they cannot be accessed or downloaded without logging in, even if someone has a direct link.

---

### 7. Member Bulletins

A broadcast email tool for the Secretariat. Write a message, select the audience, and send — the portal delivers it to all members via email. Sent bulletins are archived and viewable in the admin panel.

---

### 8. Membership Tiers & Dues

The Secretariat defines membership tiers in the admin panel — setting the name, annual price in USD, and a list of what each tier includes. Tiers are assigned when a member is invited.

Each member's dashboard shows:
- Their current tier and annual amount
- Their dues due date
- A colour-coded status — Paid, Due Soon, Grace Period, or Overdue
- A Pay button when dues are approaching or overdue

**Grace period:** If a member does not pay within 7 days of their due date, their account is automatically suspended. Their account is reinstated the moment payment is received.

---

### 9. Profile & Organisation Management

Each member has a personal profile — name, title, bio, LinkedIn, avatar — and can update it at any time. Members linked to an organisation can manage the organisation's profile, logo, country, and team roster directly from the portal.

---

### 10. Notification Centre

Members receive notifications three ways:

| Channel | Used for |
|---------|---------|
| **Email** | Invitations, payment receipts, account status changes, event confirmations |
| **SMS** | Urgent notifications via Africa's Talking — sent to the member's registered phone number |
| **In-app** | Notifications appear in the portal with an unread badge — members can mark individual notifications or all as read |

---

### 11. Admin Panel & Dashboard

A dedicated administration area for the Secretariat and Super Admin. From here they can:

- View live portal stats — active members, pending approvals, upcoming events, open opportunities
- Manage all members — view profiles, change roles, update status, suspend or reinstate accounts
- Send and revoke invitations — with tier and organisation assigned at invite time
- Create and edit events, posts, opportunities, and bulletins
- Manage membership tiers — create, edit, activate, or deactivate tiers
- Connect QuickBooks for automatic invoice generation
- View a full audit log of all actions taken on the portal

---

### 12. Security

| Feature | What it does |
|---------|-------------|
| Invitation-only access | No public registration — every account comes from a Secretariat invite |
| Role-based access | Each role sees only what it is permitted to see |
| Rate limiting | Login attempts, invitations, and form submissions are throttled to prevent abuse |
| Security headers | HTTP security headers block clickjacking, content injection, and unsafe scripts |
| Audit log | Every significant action (login, status change, content publish, payment) is logged with who did it and when |
| Session protection | Sessions are encrypted, cookie-secured, and invalidated on password change |
| Soft deletes | Deleted records are retained in the database — nothing is permanently lost |

---

## Integrations

### Flutterwave — Payment Processing

Members pay their annual membership dues directly through the portal using Flutterwave's secure hosted checkout. No card details pass through the portal server.

**Supported payment methods:**
- Debit and credit card
- M-Pesa (Kenya)
- Mobile money (Ghana, Uganda, Tanzania)
- Bank transfer

**Supported currencies:** KES · USD · GHS · NGN · ZAR · UGX · TZS

When a payment is confirmed, the portal automatically:
- Marks dues as paid
- Extends the member's due date by one year
- Reinstates the account if it was suspended
- Generates an invoice in QuickBooks (if connected)

HALI receives funds directly into their Flutterwave business account.

---

### QuickBooks Online — Accounting Sync

The Secretariat connects their QuickBooks Online account from the admin panel with a one-click authorisation. After that, every time a member makes a payment the portal automatically:

1. Finds or creates the member as a customer in QuickBooks
2. Creates an invoice for the membership dues amount
3. Marks the invoice as paid

No manual entry needed. HALI's accounting stays in sync without any extra work from the Secretariat.

---

### Africa's Talking — SMS

The portal sends SMS notifications to members for time-sensitive communications — account status changes, payment confirmations, and event reminders. SMS is delivered through Africa's Talking using HALI's registered sender ID.

---

### Stripe — International Payments (Fallback)

Stripe is retained as a secondary payment option for international members who prefer card-only checkout outside of Flutterwave's coverage area.

---

## How the Portal Protects HALI's Data

- All files uploaded to the portal (avatars, organisation logos, resources) are stored outside the web root — they cannot be accessed directly via URL
- Every file download goes through an authenticated route — only logged-in members can access files
- The portal runs on HTTPS with strict cookie settings — sessions cannot be intercepted
- Database sessions — session data is stored in the database, not in files or cookies

---

## What Is Coming — Phase 3

The following features are planned for the next phase of development, to be scoped and quoted separately once open questions from HALI are answered.

| Feature | Description |
|---------|-------------|
| **In-app chat** | Embedded Rocket.Chat with automatic single sign-on — members log into the portal and are signed into chat automatically. Supports channels, direct messages, and file sharing |
| **File storage (Wasabi)** | Shared file library backed by Wasabi cloud storage (~$7/TB/month, no egress fees). Secretariat uploads files and shares with specific members, all members, or Friends only |
| **Public website sync** | Posts published on the portal appear on haliaccess.org and vice versa. Suspended members become invisible on the public website automatically |
| **Two-factor authentication** | Mandatory 2FA for Secretariat and Super Admin accounts. Database structure already in place |

---

## Technical Foundation

The portal is built on a modern, well-supported technology stack designed for reliability and long-term maintainability.

| Component | Technology |
|-----------|-----------|
| Framework | Laravel 13 (PHP 8.3) |
| Frontend | Livewire 3, Alpine.js, Tailwind CSS |
| Database | MySQL |
| Hosting | cPanel shared hosting (upgradeable to VPS for Phase 3) |
| Deployment | GitHub → cPanel automated deployment |
| Payments | Flutterwave (primary), Stripe (fallback) |
| SMS | Africa's Talking |
| Accounting | QuickBooks Online |
| File storage | Local (Phase 2) → Wasabi cloud (Phase 3) |

---

*HALI Access Partner Portal · Built by Jones Bosire · April 2026*
*Live at haliportal.tickooplug.co.ke*
