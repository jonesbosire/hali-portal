# HALI Access Partner Portal
## System Overview & Client Briefing

---

## Table of Contents

1. [What the Portal Is](#1-what-the-portal-is)
2. [How Members Join](#2-how-members-join)
3. [User Roles & Permissions](#3-user-roles--permissions)
4. [Platform Features](#4-platform-features)
5. [Administration Panel](#5-administration-panel)
6. [Security Architecture](#6-security-architecture)
7. [Notifications & Communications](#7-notifications--communications)
8. [Billing & Subscriptions](#8-billing--subscriptions)
9. [Technical Stack](#9-technical-stack)
10. [Future Roadmap](#10-future-roadmap)

---

## 1. What the Portal Is

HALI Access Network is a coalition of non-profit organisations, secondary schools, tertiary institutions, and access organisations dedicated to supporting high-achieving, low-income students from sub-Saharan Africa in pursuing university education. The network operates across **17 countries**, supports **2,000+ students annually**, and has channelled **$150 million** in grants and scholarships.

The **HALI Access Partner Portal** is the network's private, invitation-only platform connecting all member organisations and partners. It serves as the central hub where members can:

- Stay informed through events, stories, and bulletins from the Secretariat
- Discover and post opportunities — jobs, fellowships, scholarships, internships
- Connect with the full network through a searchable member organisation directory
- Manage their organisational profile and billing subscription
- Receive real-time notifications via email, SMS, and in-app alerts

The portal is live at **haliportal.tickooplug.co.ke** and is managed by the HALI Secretariat.

---

## 2. How Members Join

The portal is **closed to self-registration**. Every account is created through a controlled invitation flow managed by the Secretariat.

### Step-by-Step Invitation Flow

```
Admin sends invitation
        ↓
System emails invite link (valid 7 days)
        ↓
Guest clicks link → fills in name, title, password
        ↓
Account created → auto-verified → logged in
        ↓
Redirected to dashboard
```

### What Happens in Detail

| Step | Action |
|------|--------|
| **1. Admin creates invite** | Secretariat goes to Admin → Invitations, enters email, assigns a role, and optionally links an organisation |
| **2. Email is sent** | An invitation email is sent with a unique secure link that expires in 7 days |
| **3. Guest accepts** | The invitee clicks the link, enters their full name, job title, and sets a password |
| **4. Account activated** | Their account is created, immediately verified and active — no waiting for approval |
| **5. Org attached** | If an organisation was selected, they are automatically connected to it |
| **6. Portal access** | They are logged in and redirected to the dashboard |

**Security notes on invitations:**
- Each link is a unique 64-character token
- Old pending invitations for the same email are automatically expired when a new one is sent
- Invite acceptance is rate-limited (10 attempts per minute per IP) to prevent token brute-forcing
- Email is auto-verified through the invite — no separate verification step required

---

## 3. User Roles & Permissions

There are **four roles** and **three account statuses** in the system.

### Roles

| Role | Who They Are | Access Level |
|------|-------------|--------------|
| `super_admin` | HALI technical lead / system owner | Full access to everything including destructive actions (delete members) |
| `secretariat` | HALI Secretariat staff | Full admin access — manage members, events, posts, invitations, bulletins. Cannot delete member accounts |
| `member` | Member organisation representatives | Full member access — events, directory, opportunities, stories, resources, billing, profile |
| `friend` | Friends of HALI / limited partners | View-only access to public content. Cannot see members-only posts, resources, or full directory |

### What Each Role Can Do

| Feature | super_admin | secretariat | member | friend |
|---------|:-----------:|:-----------:|:------:|:------:|
| View dashboard | ✓ | ✓ | ✓ | ✓ |
| View member directory | ✓ | ✓ | ✓ | — |
| View member-only posts | ✓ | ✓ | ✓ | — |
| View member-only resources | ✓ | ✓ | ✓ | — |
| Register for events | ✓ | ✓ | ✓ | ✓ |
| Post opportunities | ✓ | ✓ | ✓ | — |
| Manage own profile | ✓ | ✓ | ✓ | ✓ |
| Manage billing | ✓ | ✓ | ✓ | — |
| Access admin panel | ✓ | ✓ | — | — |
| Manage members | ✓ | ✓ | — | — |
| Send invitations | ✓ | ✓ | — | — |
| Create/edit events | ✓ | ✓ | — | — |
| Publish posts & bulletins | ✓ | ✓ | — | — |
| Delete member accounts | ✓ | — | — | — |

### Account Statuses

| Status | Meaning | Effect |
|--------|---------|--------|
| `active` | Normal, functioning account | Full access based on role |
| `pending` | Awaiting approval | Cannot log in — redirected with "awaiting approval" message |
| `suspended` | Account suspended by admin | Automatically logged out, cannot log in until reinstated |

> The Secretariat can change any member's status at any time from **Admin → Members**.

---

## 4. Platform Features

### 4.1 Dashboard

The dashboard is the first page a member sees after login. It shows:

- **Upcoming events** — next 3 events with registration status
- **Latest stories & updates** — 4 most recent published posts
- **Latest opportunities** — 3 most recent active opportunities
- **Profile completeness** — a prompt showing what's missing (avatar, bio, title, organisation, email verified)
- **Admin quick-stats** *(admins only)* — total active members, pending approvals, upcoming events, active opportunities

---

### 4.2 Member Directory

A searchable, filterable directory of all active member organisations.

**What it shows:**
- Organisation name, logo, type (Member / Friend of HALI)
- Country, region, description
- Founding year, students supported, scholarship total
- Team members attached to the organisation
- Organisation opportunities

**Filtering options:**
- Search by name, country, or description
- Filter by country
- Filter by type (Member Organisation / Friend of HALI)

> Only members with `member`, `secretariat`, or `super_admin` roles can access the full directory. `friend` accounts do not see this section.

---

### 4.3 Events

A full events management system covering the entire event lifecycle.

**Event types:** Indaba, Webinar, Conference, Workshop

**What members can do:**
- Browse all upcoming events
- Register for an event (one registration per member)
- Cancel their registration
- View event details, agenda/programme, and speakers

**Event details include:**
- Title, description, date and time (with timezone)
- Location (physical or virtual)
- Registration window (open/close dates)
- Capacity limit
- Programme / agenda (with speakers and time slots)
- Featured event flag

**Registration confirmations are sent via:**
- In-app notification
- Email confirmation
- SMS (if the member has a phone number on file)

---

### 4.4 Stories & Posts

A content hub for network updates, member stories, and announcements.

**Post types:** Update, Story, Blog, Bulletin

**Features:**
- Category tagging
- Cover images
- Members-only flag (restricts visibility to verified members)
- Featured post flag
- View counter
- Published / Draft / Archived status

---

### 4.5 Opportunities

A board where members can discover and post opportunities relevant to students and the network.

**Opportunity types:** Job, Fellowship, Scholarship, Internship, Volunteer

**Fields include:**
- Title, description, requirements
- Location, salary range
- Application deadline and link
- Members-only flag
- Organisation association

**Posting rules:**
- Any `member` or admin can post opportunities
- Rate-limited to 5 posts per hour per account
- Only opportunities with active status and a future deadline are shown

---

### 4.6 Resources

A library of downloadable documents and materials.

- Files are stored securely on the server outside the public web directory
- All downloads require authentication — direct file URLs do not work
- Each resource can be marked members-only
- Download counts are tracked

---

### 4.7 Profile & Organisation

**Personal profile** — each member can update:
- Full name, job title, bio
- Phone number, LinkedIn URL
- Profile photo (avatar)
- Password (separate secure form)
- Account deletion (requires password confirmation)

**Organisation profile** — members can update their organisation's:
- Name, type, country, region
- Website, description
- Founding year, students supported, scholarship total
- Logo

> Email changes trigger a re-verification flow. Password changes log out all other active sessions.

---

### 4.8 Notifications

Members receive notifications through up to three channels simultaneously:

| Channel | When used |
|---------|-----------|
| **In-app** | Always — visible in the notification bell in the header |
| **Email** | Configured per notification type |
| **SMS** | When a phone number is on the account and Africa's Talking is configured |

**Current notification triggers:**
- Event registration confirmed
- Account status changed (activated, suspended, etc.)

Notifications can be marked as read individually or all at once.

---

## 5. Administration Panel

The admin panel is accessible at `/admin` and is available to `secretariat` and `super_admin` roles only.

### 5.1 Admin Dashboard

An overview of the entire network:

| Stat | What it Shows |
|------|--------------|
| Active Members | Total accounts with active status and member/friend role |
| Pending Approval | Accounts waiting to be activated |
| Organisations | Total active organisations |
| Upcoming Events | Published events with a future date |
| Published Posts | Live, published stories and posts |
| Active Opportunities | Opportunities with active status and future deadline |
| Pending Invitations | Invitations sent but not yet accepted |

Also shows: **Recent members** (last 5 to join) and **upcoming events** with registration counts.

---

### 5.2 Member Management

Full visibility and control over all member accounts.

**What admins can do:**
- Search and filter members by role and status
- View a member's full profile, organisation, and activity
- Change status: active → suspended → pending (and back)
- Delete accounts *(super_admin only)*

---

### 5.3 Invitation Management

- View all sent invitations with their status (pending / accepted / expired)
- Send new invitations with role and organisation assignment
- Cancel pending invitations
- Duplicate invitations for the same email automatically expire the previous one

---

### 5.4 Event Management

Full CRUD management for events:

- Create, edit, publish, archive, delete events
- Add and manage programme/agenda items (speaker, time slot, title)
- View the registrant list with their details
- Mark individual attendees as attended
- Export the attendee list

---

### 5.5 Post Management

Full CRUD for all content types (updates, stories, blog posts, bulletins):

- Create and publish content with categories and cover images
- Set members-only flag
- Feature posts on the dashboard
- Publish, draft, or archive posts

---

### 5.6 Bulletins

A direct broadcast tool to send a message to all active members.

- Write a bulletin in the admin panel
- Click **Send** — the bulletin is dispatched as an email to every active member asynchronously (queued)
- The system records how many members received it and when it was sent

---

### 5.7 Opportunity Management

Admin can manage all opportunities posted on the platform — edit, republish, or remove any submission.

---

## 6. Security Architecture

### Authentication

| Feature | Implementation |
|---------|---------------|
| Login | Email + password with session-based authentication |
| Password storage | bcrypt hashed (never stored in plain text) |
| Session storage | Database-backed sessions |
| Session lifetime | 120 minutes of inactivity |
| Email verification | Required before accessing any member features |
| Remember me | Secure persistent cookie (30 days) |

### Brute-Force & Abuse Protection (Rate Limiting)

| Action | Limit |
|--------|-------|
| Login attempts | 5 per minute per email + IP |
| Invitation acceptance | 10 per minute per IP |
| Event registration | 10 per minute per user |
| Opportunity posting | 5 per hour per user |
| Email verification | 6 attempts per minute |
| Stripe webhooks | 60 per minute |

After 5 failed login attempts, the account is locked for 60 seconds and the user is told how long to wait.

### HTTP Security Headers

Every page response includes:

| Header | Protection |
|--------|-----------|
| `X-Frame-Options: SAMEORIGIN` | Prevents clickjacking (embedding the portal in an iframe) |
| `X-Content-Type-Options: nosniff` | Prevents MIME-type sniffing attacks |
| `Content-Security-Policy` | Restricts which scripts and resources can load |
| `Referrer-Policy: strict-origin-when-cross-origin` | Controls referrer data in requests |
| `Permissions-Policy` | Disables access to camera, microphone, geolocation, payment, USB |
| `Strict-Transport-Security` | Forces HTTPS in production (1-year duration) |
| `X-XSS-Protection: 1; mode=block` | Additional XSS protection |
| *(hidden)* | `X-Powered-By` and `Server` headers removed to hide tech stack |

### CSRF Protection

All forms include CSRF tokens. Every state-changing request (POST, PATCH, DELETE) is validated against the user's session token. The only exception is the Stripe webhook endpoint, which is instead verified using Stripe's HMAC-SHA256 signature.

### File Security

- Uploaded files (avatars, documents) are stored **outside the public web directory**
- Accessing a file directly by URL is not possible
- All file downloads are routed through an authenticated controller that checks the user's session first

### Audit Trail

Every significant admin action is logged using the Spatie Activity Log library:
- Member status changes
- Account deletions
- Invitation sending
- Profile updates

This creates a full audit history linked to the admin who performed the action.

---

## 7. Notifications & Communications

### Email

The system sends transactional emails for:

- Invitations to join the portal
- Event registration confirmations
- Account status changes (activated, suspended)
- Member bulletins (broadcast from admin)
- Password reset requests

Emails are sent via SMTP (currently configured with Mailtrap for testing; configurable for production via Postmark, SendGrid, SES, etc.).

### SMS (Africa's Talking)

SMS notifications are sent for:
- Event registration confirmations (includes event name, date, time, timezone)
- Account status changes

SMS is sent when:
1. The member has a phone number saved on their profile
2. Africa's Talking API credentials are configured on the server

SMS failures are logged but do not interrupt other notification channels — if SMS fails, email and in-app notifications still deliver.

### In-App Notifications

A notification bell in the top navigation shows unread notification count. Members can view all notifications at `/notifications` and mark them read individually or all at once.

---

## 8. Billing & Subscriptions

Billing is handled through **Stripe**, using the Laravel Cashier integration.

### How it Works

1. Organisations are assigned a **Membership Plan** (defined by the Secretariat)
2. Subscriptions are managed in Stripe and synced to the portal via webhooks
3. Invoices are recorded automatically when Stripe events arrive
4. Members can access the **Stripe Customer Portal** directly from `/billing` to:
   - Update their payment method
   - View and download past invoices
   - Manage or cancel their subscription

### Subscription Statuses

| Status | Meaning |
|--------|---------|
| `active` | Subscription is current and paid |
| `trialing` | In a free trial period |
| `past_due` | Payment failed — member sees a warning banner |
| `canceled` | Subscription ended |

### Webhook Security

Stripe sends events to the portal's webhook endpoint. Every incoming webhook is verified using **HMAC-SHA256 signature verification** against a shared secret. Any request with a mismatched or missing signature is rejected (HTTP 400) and logged.

---

## 9. Technical Stack

| Layer | Technology |
|-------|-----------|
| **Framework** | Laravel 13 (PHP 8.3) |
| **Frontend** | Livewire 3, Alpine.js, Tailwind CSS |
| **Database** | MySQL (UUID primary keys, soft deletes on all records) |
| **File Storage** | Local server storage (private) + public storage (logos, images) |
| **Queue** | Database-backed queue for emails and notifications |
| **Cache** | Database cache driver |
| **Session** | Database sessions (encrypted) |
| **Billing** | Stripe via Laravel Cashier |
| **SMS** | Africa's Talking |
| **Activity Log** | Spatie Laravel Activity Log |
| **Slug Generation** | Spatie Laravel Sluggable |

**All primary keys are UUIDs** — records cannot be enumerated by guessing IDs in URLs.

**Soft deletes are used throughout** — deleted records are never permanently removed from the database immediately, preserving audit history and allowing recovery.

---

## 10. Future Roadmap

The following features are either scaffolded in the current codebase or identified as natural next steps:

### Already Scaffolded (Infrastructure Present)

| Feature | Status | Notes |
|---------|--------|-------|
| **Two-Factor Authentication (2FA)** | Fields in database, not yet active | `two_factor_secret` and `two_factor_recovery_codes` columns exist on the users table. Can be activated with minimal development |
| **Slack Notifications** | Config key present | `SLACK_BOT_USER_OAUTH_TOKEN` in services config — can add Slack alerts for admins (e.g. new member pending approval) |

### Identified Next Features

| Feature | Description |
|---------|-------------|
| **2FA Enforcement** | Activate two-factor authentication for admin accounts as a security requirement |
| **Activity Dashboard** | Surface the existing activity log to admins — show a timeline of who did what across the portal |
| **Event Attendance Reports** | Downloadable analytics per event: registrations vs attendance, demographics |
| **Resource Management Admin** | Admin UI to upload, categorise, and manage downloadable resources (currently needs direct database/file access) |
| **Bulk Member Invitations** | Upload a CSV of emails to send invitations in bulk |
| **Member-to-Member Messaging** | Direct messaging or discussion threads between organisations |
| **Opportunity Applications** | Allow members to apply to posted opportunities directly through the portal |
| **Advanced Search** | Full-text search across posts, opportunities, and the directory |
| **Organisation Verification Badges** | Visual indicators for verified or featured organisations |
| **Embedded Stripe Billing Portal** | Replace the external Stripe redirect with an embedded billing experience |
| **CSP Hardening** | Move to a stricter Content Security Policy once Livewire/Alpine.js compatibility is confirmed |

---

## Quick Reference

### Key URLs

| Page | URL |
|------|-----|
| Portal home | `/` |
| Member login | `/login` |
| Member dashboard | `/dashboard` |
| Directory | `/directory` |
| Events | `/events` |
| Opportunities | `/opportunities` |
| Stories | `/stories` |
| Profile | `/profile` |
| Billing | `/billing` |
| Notifications | `/notifications` |
| Admin panel | `/admin/dashboard` |
| Admin members | `/admin/members` |
| Admin invitations | `/admin/invitations` |
| Admin events | `/admin/events` |
| Admin posts | `/admin/posts` |
| Admin bulletins | `/admin/bulletins` |

### Contact

For access issues, account management, or platform support:
**portal@haliaccess.org**

---

*Document prepared by the HALI Access Portal development team.*
*Last updated: April 2026*
