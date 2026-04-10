# HALI Access Partner Portal — System Documentation

**Document Version:** 1.0
**Prepared:** March 2026
**Project:** HALI Access Partner Portal
**Stack:** Laravel 13 / PHP 8.3 / Livewire 3 / Tailwind CSS
**Database:** `hali_portal` (MySQL)
**Application Name (env):** `HALI Access Partner Portal`

---

## Table of Contents

1. [System Overview](#1-system-overview)
2. [User Roles & Access Levels](#2-user-roles--access-levels)
3. [Core Features](#3-core-features)
4. [Technical Specifications](#4-technical-specifications)
5. [System Requirements for Hosting](#5-system-requirements-for-hosting)
6. [Environment Configuration](#6-environment-configuration)
7. [Getting Started — Admin Guide](#7-getting-started--admin-guide)
8. [Login & Test Credentials (Development)](#8-login--test-credentials-development)
9. [Security Features](#9-security-features)
10. [Completion Status](#10-completion-status)
11. [Known Limitations & Next Steps](#11-known-limitations--next-steps)
12. [Presentation Summary — Executive Brief](#12-presentation-summary--executive-brief)

---

## 1. System Overview

The **HALI Access Partner Portal** is a private, invite-only web platform built for the **HALI Access Network** — a pan-African network connecting organisations that share the mission of expanding access to quality education across Sub-Saharan Africa. The portal serves as the digital headquarters for this network, enabling member organisations to coordinate, communicate, share resources, and collaborate.

### Who it is for

The portal is designed for three primary audiences:

- **The HALI Secretariat** — the central administrative team that manages the network, sends invitations, creates content, and oversees all member activity.
- **Member Organisations** — scholarship bodies, foundations, universities, and education NGOs that are full partners of the HALI network (examples in the demo data include KenSAP, Tony Elumelu Foundation, CAMFED, Ashesi University Foundation, Rwanda Education Board, and 35+ others spanning 15+ African countries).
- **Friends of HALI** — individual consultants, advisors, and allies of the network who are granted observer-level access without being attached to a member organisation.

### Mission Context

HALI Access Network brings together organisations that collectively support hundreds of thousands of students across Africa. The portal facilitates:

- Network-wide communication through bulletins and stories
- Event coordination for Indabas, webinars, workshops, and conferences
- Peer learning through a shared resource library
- Cross-network talent and opportunity sharing
- Partner onboarding and membership management

The portal is **not a public website**. Every user must be personally invited by the HALI Secretariat. There is no public self-registration path.

---

## 2. User Roles & Access Levels

The system defines four roles, stored in the `role` column of the `users` table as an enum.

### Role Definitions

| Role | Identifier | Description |
|---|---|---|
| Super Admin | `super_admin` | Full system control. Only the Secretariat leadership holds this role. |
| Secretariat | `secretariat` | Network coordinators with admin access. Cannot delete users. |
| Member | `member` | Representatives of partner organisations. Full member-area access. |
| Friend | `friend` | Individual allies. Access to the member area but not linked to an organisation. |

### Detailed Permissions Matrix

| Capability | super_admin | secretariat | member | friend |
|---|:---:|:---:|:---:|:---:|
| Log in to the portal | Yes | Yes | Yes | Yes |
| View dashboard | Yes | Yes | Yes | Yes |
| Browse member directory | Yes | Yes | Yes | Yes |
| View and register for events | Yes | Yes | Yes | Yes |
| Read stories & updates | Yes | Yes | Yes | Yes |
| Browse opportunities board | Yes | Yes | Yes | Yes |
| Post an opportunity | Yes | Yes | Yes | No |
| Download resources | Yes | Yes | Yes | Yes |
| Edit own profile (bio, avatar, LinkedIn) | Yes | Yes | Yes | Yes |
| Edit own organisation profile | Yes | Yes | Yes | No |
| View billing & subscription | Yes | Yes | Yes | No |
| Receive in-portal notifications | Yes | Yes | Yes | Yes |
| **Admin Panel access** | Yes | Yes | No | No |
| Admin: manage events (create/edit/delete) | Yes | Yes | No | No |
| Admin: manage posts (create/edit/delete) | Yes | Yes | No | No |
| Admin: manage opportunities (admin) | Yes | Yes | No | No |
| Admin: send bulletins | Yes | Yes | No | No |
| Admin: view all members | Yes | Yes | No | No |
| Admin: suspend / activate members | Yes | Yes | No | No |
| Admin: send invitations | Yes | Yes | No | No |
| Admin: revoke invitations | Yes | Yes | No | No |
| Admin: mark event attendance | Yes | Yes | No | No |
| Admin: export event attendee list | Yes | Yes | No | No |
| **Delete a member account** | Yes | No | No | No |
| Access activity logs | Yes | No | No | No |

### How Roles Are Assigned

Roles are **assigned at the point of invitation**, not chosen by the user. When the Secretariat sends an invitation email, they select the role (`member` or `friend`) that will be granted upon acceptance. The `super_admin` and `secretariat` roles are created directly via database seeders and are not assignable through the invitation UI.

A user's status can be `active`, `suspended`, or `archived`. Only `active` users can log in — the `EnsureUserIsActive` middleware enforces this on every request.

---

## 3. Core Features

### 3.1 Partner Onboarding — Invitation Flow

Access to the portal is strictly invite-only. The full onboarding flow works as follows:

1. **Secretariat sends invitation:** An admin navigates to *Admin > Invitations* and enters the invitee's email address and selects their intended role. The system generates a cryptographically random 64-character token and stores it with a **7-day expiry**.
2. **Invitation email delivered:** The invitee receives an email with a unique link in the format `/invitation/{token}`.
3. **Invitee completes registration:** The invitation page pre-fills the email address. The new user sets their name and password and submits. The route is rate-limited (`throttle:invitation`) to prevent brute-force token guessing.
4. **Account activated:** Upon acceptance, the invitation is marked with the `accepted_at` timestamp and the user's account is created with the role specified in the invitation. If an `organization_id` was attached to the invitation, the user is linked to that organisation.
5. **Email verification:** New accounts are subject to email verification (`MustVerifyEmail`). The user must verify their email before gaining full access.
6. **First login:** Once verified and active, the user lands on their dashboard.

Expired or already-accepted invitation tokens display an appropriate error. The Secretariat can view all pending invitations and revoke them from the admin panel.

---

### 3.2 Member Directory

The member directory is a searchable, browsable listing of all partner organisations in the network.

- **Organisation profiles** display: name, type, country, region, founding year, number of students supported, description, website URL, and logo.
- **URL pattern:** `/directory` (index), `/directory/{slug}` (individual profile)
- Organisations are resolved via a SEO-friendly URL slug auto-generated from the organisation name (e.g., `african-leadership-academy`).
- Logos are served from public storage. If no logo is uploaded, a branded auto-generated avatar is displayed using the `ui-avatars.com` service with HALI's brand colour (`#1A7A8A`).
- The directory lists **40+ seeded partner organisations** spanning Kenya, South Africa, Nigeria, Ghana, Tanzania, Uganda, Ethiopia, Rwanda, Senegal, Zambia, Zimbabwe, Mozambique, Botswana, Cameroon, DRC, Madagascar, Malawi, and international partners.

---

### 3.3 Events & Registration

The events module supports the full lifecycle of HALI network events.

**Event types supported:**
- `indaba` — flagship HALI gatherings
- `webinar` — online sessions
- `conference` — multi-day academic or network conferences
- `workshop` — hands-on skill-building sessions

**Key capabilities:**
- Events have both **in-person** and **virtual** location types (with optional virtual meeting link).
- Events can be flagged as **members-only** (`is_members_only`), restricting visibility to authenticated portal members.
- **Featured events** (`is_featured`) are surfaced prominently on the dashboard.
- **Capacity control:** Admins set a `max_attendees` cap. The system tracks spots remaining and closes registration when full.
- **Registration windows:** Admins set `registration_opens_at` and `registration_closes_at` datetime boundaries. Registration outside these windows is blocked automatically.
- **Attendee management (admin):** Admins can mark individual registrants as attended and export the full attendee list (CSV).
- Cover images are supported and stored on the public disk.
- **URL pattern:** `/events` (index), `/events/{slug}` (detail), `/events/{slug}/register` (POST), `/events/{slug}/cancel` (DELETE)

---

### 3.4 Stories & Updates (Posts)

The posts module provides a content publishing system for the network's stories, news, and updates.

**Post types:** The `type` field allows posts to be categorised (e.g., `story`, `news`, `update`, `announcement` — values are flexible string-based).

**Key capabilities:**
- Posts belong to an **author** (user) and optionally an **organisation**.
- Posts can be categorised via a many-to-many relationship with `PostCategory`.
- **Members-only flag:** Posts marked `is_members_only` are visible only to authenticated users.
- **Featured flag:** Featured posts are highlighted on the dashboard and listing pages.
- **View counting:** Each post view increments the `views_count` column automatically.
- **Scheduling:** Posts have a `published_at` datetime — a post with a future `published_at` will not appear even if status is `published`.
- Cover images are supported.
- Slugs are auto-generated from the post title.
- **URL pattern:** `/stories` (index), `/stories/{slug}` (detail)

---

### 3.5 Opportunities Board

The opportunities board allows member organisations to share roles, fellowships, scholarships, and other opportunities with the network.

**Opportunity types:**
- `job` — employment positions
- `fellowship` — research and leadership fellowships
- `scholarship` — academic funding opportunities
- `internship` — internship placements
- `volunteer` — volunteer roles

**Key capabilities:**
- Members can **create opportunities** directly via the portal (`/opportunities/create`).
- Each opportunity includes: title, description, requirements, location, salary range (optional), application URL, and deadline.
- **Deadline enforcement:** Opportunities past their `deadline_at` are automatically filtered out from the active listing.
- **Members-only flag:** Opportunities can be restricted to authenticated members.
- Opportunities belong to an organisation and display the posting organisation's branding.
- Admins have full CRUD control via the admin panel.
- **URL pattern:** `/opportunities` (index), `/opportunities/{id}` (detail), `/opportunities/create` (form)

---

### 3.6 Resource Library

The resource library provides a curated repository of documents, templates, links, and videos for network members.

**Resource types:**
- `document` — downloadable files (PDFs, Word documents, etc.)
- `link` — external URLs
- `video` — video resources
- `template` — reusable templates

**Key capabilities:**
- Resources are **members-only by default** (`is_members_only` defaults to `true`).
- File resources are stored on the **private disk** (outside the web root) and served through an authenticated download controller that increments the `download_count`.
- External URL resources redirect directly to the link.
- Admins upload and manage resources via the admin panel.
- Download counts are tracked per resource.
- **URL pattern:** `/resources` (index), `/resources/{id}/download`

---

### 3.7 My Organisation (Organisation Profile)

Authenticated members can view and edit their organisation's profile from the portal.

- **Editable fields:** Name, type, country, region, logo, website URL, description, founding year, students supported, scholarship total.
- Logo upload is supported.
- The organisation profile also displays the current membership subscription status.
- Members linked to multiple organisations via the `organization_members` pivot table can have a **primary organisation** designated.
- **URL:** `/organization`

---

### 3.8 Admin Panel

The admin panel is accessible to `super_admin` and `secretariat` roles at the `/admin` prefix. It provides management interfaces for all key content types.

**Admin — Members (`/admin/members`):**
- List all users with search and filtering by role and status.
- View an individual member's full profile, organisation membership, and activity.
- Change a member's status (`active`, `suspended`, `archived`).
- Super admin only: permanently delete a member account.

**Admin — Invitations (`/admin/invitations`):**
- View all pending, accepted, and expired invitations.
- Send new invitations (email + role + optional organisation assignment).
- Revoke/delete pending invitations.

**Admin — Events (`/admin/events`):**
- Full CRUD for events (create, view, edit, delete).
- View registrant list.
- Mark individual registrants as attended.
- Export attendee list.

**Admin — Posts (`/admin/posts`):**
- Full CRUD for stories and updates.
- Set post type, categories, featured flag, members-only flag, and publication scheduling.

**Admin — Opportunities (`/admin/opportunities`):**
- Full CRUD for opportunities.
- Approve or remove member-submitted opportunities.

**Admin — Bulletins (`/admin/bulletins`):**
- Create draft bulletins.
- Send bulletins to all members (triggers a `recipient_count`-tracked send operation).
- View bulletin send history.

---

### 3.9 Notification System

The portal uses Laravel's built-in database notification system.

- Notifications are stored in the `notifications` table.
- Users can view all notifications at `/notifications` (paginated, 20 per page).
- **Mark as read:** Individual notifications can be marked read, or all unread notifications can be cleared at once.
- Unread notification counts are displayed in the navigation header.
- **URL pattern:** `/notifications` (index), `/notifications/{id}/read` (POST)

---

### 3.10 Billing & Subscriptions (Stripe)

The billing module manages membership subscription tiers for partner organisations.

**Membership Plans (seeded):**

| Plan | Annual Price (USD) | Key Features |
|---|---|---|
| **Associate** | Free | Directory access, view events, receive bulletins |
| **Partner** | $500/year | Everything in Associate + post opportunities, event registration, resource library, directory listing, AGM voting rights |
| **Founding Partner** | $1,500/year | Everything in Partner + featured directory placement, priority event registration, co-branding, Advisory Council seat, dedicated liaison, impact report inclusion |

- Billing is powered by **Laravel Cashier (Stripe)** with `stripe_subscription_id` and `stripe_customer_id` stored per subscription.
- Subscription statuses: `active`, `trialing`, `past_due`, `canceled`.
- Invoices are stored in a dedicated `invoices` table per organisation.
- The billing page is accessible at `/billing`.
- **Note:** Stripe API keys are not configured in the development environment — billing UI is present but payment processing requires live keys.

---

### 3.11 Toast Notifications & UI System

The application uses a client-side toast notification system for action feedback (success, error, warning messages). These are implemented using Alpine.js and appear non-intrusively after form submissions and actions throughout the portal.

---

### 3.12 File Uploads — Avatars, Logos, Cover Images

**Private file serving (user avatars):**
User avatars are uploaded to the **private disk** (outside the web root, in `storage/app/private/`). They are served through the authenticated `FileServeController` at the route `/files/{path}`, which requires the user to be logged in. This prevents direct URL access to private profile images.

**Public storage (organisation logos, event/post cover images):**
Organisation logos and event/post cover images are stored on the **public disk** (`storage/app/public/`) and accessed via `asset('storage/...')`. These are linked via `php artisan storage:link`.

**Fallback avatars:**
Both user and organisation avatars fall back to `ui-avatars.com` with HALI's brand colour when no image is uploaded.

---

## 4. Technical Specifications

### Core Framework

| Component | Technology | Version |
|---|---|---|
| Language | PHP | ^8.3 |
| Framework | Laravel | ^13.0 |
| Auth Scaffolding | Laravel Breeze | ^2.4 |
| Reactive UI | Livewire | ^3.6.4 |
| Livewire Pages | Livewire Volt | ^1.7.0 |
| Build Tool | Vite | ^8.0.0 |

### Frontend

| Component | Technology | Version |
|---|---|---|
| CSS Framework | Tailwind CSS | ^3.1.0 |
| Tailwind Forms | @tailwindcss/forms | ^0.5.2 |
| Tailwind Typography | @tailwindcss/typography | ^0.5.19 |
| Icon Library | Font Awesome Free | ^7.2.0 |
| Icon Library (secondary) | Material Symbols | ^0.42.3 |
| HTTP Client (frontend) | Axios | ^1.11.0 |

### Key Laravel Packages

| Package | Purpose | Version |
|---|---|---|
| `spatie/laravel-activitylog` | Audit trail — logs all model changes | ^4.12 |
| `spatie/laravel-permission` | Role & permission management | ^7.2 |
| `spatie/laravel-medialibrary` | Media file management | ^11.21 |
| `spatie/laravel-sluggable` | Auto-generate URL slugs for models | ^3.8 |
| `spatie/laravel-backup` | Automated database and file backups | ^10.2 |
| `laravel/cashier` | Stripe subscription billing | ^16.5 |
| `laravel/scout` | Full-text search integration | ^11.1 |
| `laravel/tinker` | REPL for debugging | ^3.0 |

### Database

- **Engine:** MySQL
- **Database name (dev):** `hali_portal`
- **ORM:** Eloquent (Laravel)
- **Primary keys:** UUIDs (all primary models use `HasUuids` trait)
- **Soft deletes:** Enabled on Users, Organizations, Events, Posts, Opportunities, Resources
- **Key tables:** `users`, `organizations`, `organization_members`, `invitations`, `membership_plans`, `subscriptions`, `invoices`, `events`, `event_registrations`, `posts`, `post_categories`, `post_category_pivot`, `resources`, `directory_listings`, `opportunities`, `member_bulletins`, `notifications`, `activity_log`, `jobs`, `cache`, `sessions`, `permissions`, `roles`

### Infrastructure (Development / Current)

| Setting | Value |
|---|---|
| Session driver | `file` |
| Session lifetime | 120 minutes |
| Queue driver | `database` |
| Cache driver | `file` |
| File storage | `local` (private) + public disk |
| Mail driver | `log` (writes to log file — no emails sent in dev) |
| Broadcast | `log` |

---

## 5. System Requirements for Hosting

### Minimum Server Specifications

| Resource | Minimum |
|---|---|
| CPU | 1 vCPU |
| RAM | 1 GB |
| Disk | 10 GB SSD |
| OS | Ubuntu 22.04 LTS or Debian 12 |
| Web Server | Nginx or Apache |

### Recommended Server Specifications

| Resource | Recommended |
|---|---|
| CPU | 2 vCPUs |
| RAM | 2–4 GB |
| Disk | 20–50 GB SSD |
| OS | Ubuntu 24.04 LTS |
| Web Server | Nginx with PHP-FPM |

### Required PHP Extensions

The following PHP extensions must be enabled on the server:

- `php8.3-cli`
- `php8.3-fpm`
- `php8.3-mysql`
- `php8.3-mbstring`
- `php8.3-xml`
- `php8.3-bcmath`
- `php8.3-curl`
- `php8.3-zip`
- `php8.3-gd` (for image processing)
- `php8.3-intl`
- `php8.3-fileinfo`

### Required Services

| Service | Purpose |
|---|---|
| MySQL 8.0+ | Primary database |
| SMTP mail server | Sending invitation and notification emails |
| SSL/TLS certificate | HTTPS (mandatory for production; cookie security depends on it) |

### Optional but Recommended Services

| Service | Purpose |
|---|---|
| Redis | Replace file-based session and cache drivers for better performance |
| Supervisor | Keep the queue worker (`php artisan queue:work`) running persistently |
| Laravel Horizon | Queue monitoring UI (if using Redis) |
| AWS S3 / Cloudflare R2 | Replace local file storage for media files at scale |
| Stripe account | Required to activate billing and subscription processing |

---

## 6. Environment Configuration

All configuration is stored in the `.env` file in the project root. A `.env.example` file provides the template.

### Full Variable Reference

#### Application

| Variable | Current (Dev) | Description | Change for Production? |
|---|---|---|---|
| `APP_NAME` | `HALI Access Partner Portal` | Displayed application name | Optional |
| `APP_ENV` | `local` | Environment name | **Yes — set to `production`** |
| `APP_KEY` | `base64:...` | Encryption key (generated) | **Yes — regenerate** |
| `APP_DEBUG` | `true` | Show detailed error pages | **Yes — set to `false`** |
| `APP_URL` | `http://localhost:8000` | Application URL | **Yes — set to live HTTPS domain** |
| `APP_LOCALE` | `en` | Default locale | Optional |
| `BCRYPT_ROUNDS` | `12` | Password hashing cost | No (12 is secure) |

#### Database

| Variable | Current (Dev) | Description | Change for Production? |
|---|---|---|---|
| `DB_CONNECTION` | `mysql` | Database driver | No |
| `DB_HOST` | `127.0.0.1` | Database host | Yes if using remote DB |
| `DB_PORT` | `3306` | Database port | No (unless non-standard) |
| `DB_DATABASE` | `hali_portal` | Database name | Yes — use production DB name |
| `DB_USERNAME` | `root` | Database username | **Yes — never use `root` in production** |
| `DB_PASSWORD` | *(empty)* | Database password | **Yes — set a strong password** |

#### Session, Cache & Queue

| Variable | Current (Dev) | Recommended for Production | Description |
|---|---|---|---|
| `SESSION_DRIVER` | `file` | `redis` or `database` | Where sessions are stored |
| `SESSION_LIFETIME` | `120` | `60`–`120` | Minutes before session expires |
| `SESSION_ENCRYPT` | `false` | `true` | Encrypt session data |
| `QUEUE_CONNECTION` | `database` | `redis` | Queue driver |
| `CACHE_STORE` | `file` | `redis` | Cache backend |

#### Mail

| Variable | Current (Dev) | Description | Change for Production? |
|---|---|---|---|
| `MAIL_MAILER` | `log` | Mail transport method | **Yes — set to `smtp` or `ses`** |
| `MAIL_HOST` | `127.0.0.1` | SMTP server hostname | **Yes** |
| `MAIL_PORT` | `2525` | SMTP port | **Yes** (587 for TLS, 465 for SSL) |
| `MAIL_USERNAME` | `null` | SMTP username | **Yes** |
| `MAIL_PASSWORD` | `null` | SMTP password | **Yes** |
| `MAIL_FROM_ADDRESS` | `noreply@haliaccess.org` | Sender email address | Confirm domain DNS is configured |
| `MAIL_FROM_NAME` | `${APP_NAME}` | Sender display name | No |

#### Stripe (Billing)

| Variable | Current (Dev) | Description | Change for Production? |
|---|---|---|---|
| `STRIPE_KEY` | *(empty)* | Stripe publishable key | **Yes — required for billing** |
| `STRIPE_SECRET` | *(empty)* | Stripe secret key | **Yes — required for billing** |
| `STRIPE_WEBHOOK_SECRET` | *(empty)* | Stripe webhook signing secret | **Yes — required for billing** |

#### File Storage (AWS S3 — Optional)

| Variable | Description |
|---|---|
| `AWS_ACCESS_KEY_ID` | AWS access key for S3 storage |
| `AWS_SECRET_ACCESS_KEY` | AWS secret key |
| `AWS_DEFAULT_REGION` | S3 region (default: `us-east-1`) |
| `AWS_BUCKET` | S3 bucket name |

#### Vite / Frontend

| Variable | Current (Dev) | Description |
|---|---|---|
| `VITE_APP_NAME` | `${APP_NAME}` | App name exposed to frontend JavaScript |
| `VITE_NGROK_DOMAIN` | *(empty)* | ngrok tunnel domain for HMR in development — leave blank in production |

### Variables That MUST Be Changed Before Going Live

1. `APP_ENV` → `production`
2. `APP_KEY` → run `php artisan key:generate`
3. `APP_DEBUG` → `false`
4. `APP_URL` → live HTTPS domain
5. `DB_USERNAME` → non-root database user
6. `DB_PASSWORD` → strong database password
7. `DB_DATABASE` → production database name
8. `SESSION_ENCRYPT` → `true`
9. `MAIL_MAILER` → `smtp` (or `ses`)
10. `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD` → live SMTP credentials
11. `STRIPE_KEY`, `STRIPE_SECRET`, `STRIPE_WEBHOOK_SECRET` → live Stripe keys

---

## 7. Getting Started — Admin Guide

### Fresh Installation

```bash
# 1. Clone the repository
git clone <repository-url> portal
cd portal

# 2. Install PHP dependencies
composer install

# 3. Copy and configure environment
cp .env.example .env
# Edit .env with your database credentials, mail settings, etc.

# 4. Generate application encryption key
php artisan key:generate

# 5. Run database migrations
php artisan migrate

# 6. Install Node.js dependencies
npm install

# 7. Build frontend assets
npm run build

# 8. Create the storage symlink (public uploads)
php artisan storage:link

# 9. Seed the database with demo data (optional but recommended for development)
php artisan db:seed
```

**Alternative — composer setup script (installs, migrates, and builds in one command):**
```bash
composer run setup
```

**Development server (runs all services concurrently — PHP, queue, log viewer, Vite HMR):**
```bash
composer run dev
```

### Creating the First Admin Account

The database seeder creates two admin accounts automatically when `php artisan db:seed` is run:

- `admin@haliaccess.net` / `HALIadmin2026!` (super_admin)
- `secretariat@haliaccess.net` / `HALIsecret2026!` (secretariat)

If installing without seeders, create the super admin manually via Tinker:

```bash
php artisan tinker
```
```php
\App\Models\User::create([
    'name'              => 'HALI Secretariat',
    'email'             => 'admin@haliaccess.net',
    'password'          => bcrypt('your-secure-password'),
    'role'              => 'super_admin',
    'status'            => 'active',
    'email_verified_at' => now(),
]);
```

### Sending the First Invitation

1. Log in at `/login` with the super admin account.
2. Navigate to **Admin > Invitations** (`/admin/invitations`).
3. Click **Send Invitation**.
4. Enter the invitee's email address.
5. Select the role: `member` (for partner organisation representatives) or `friend` (for individual allies).
6. Optionally, select an organisation to associate the new user with on registration.
7. Click **Send**. The system generates a 64-character token valid for 7 days and sends the invitation email.

### How a Partner Completes Onboarding

1. The partner receives an email with a unique invitation link.
2. They click the link and land on the registration page with their email pre-filled.
3. They enter their name and choose a password.
4. They submit the form. Their account is created with the assigned role.
5. They are redirected to verify their email address.
6. After clicking the verification link in the email, they are logged in and land on the portal dashboard.
7. They can then complete their profile (avatar, bio, LinkedIn URL, phone) and review their organisation profile.

---

## 8. Login & Test Credentials (Development)

These accounts are created by the database seeders when `php artisan db:seed` is run. **Do not use these credentials in production.**

### Admin Accounts

| Name | Email | Password | Role |
|---|---|---|---|
| HALI Secretariat | `admin@haliaccess.net` | `HALIadmin2026!` | super_admin |
| Network Coordinator | `secretariat@haliaccess.net` | `HALIsecret2026!` | secretariat |

### Member Accounts

All member accounts use the password: **`password`**

| Name | Email | Organisation | Title | Role |
|---|---|---|---|---|
| Amina Wanjiku | `amina@kensap.org` | KenSAP | Program Director | member |
| Chidi Okonkwo | `chidi@tonyelumelufoundation.org` | Tony Elumelu Foundation | Head of Youth Programs | member |
| Sipho Dlamini | `sipho@leapschool.org.za` | LEAP Science and Maths Schools | Academic Affairs Manager | member |
| Fatima Diallo | `fatima@camfed.org` | CAMFED International | East Africa Regional Director | member |
| Kwame Asante | `kwame@ashesi.edu.gh` | Ashesi University Foundation | Director of Admissions | member |
| Zawadi Muthoni | `zawadi@zawadiafrica.org` | Zawadi Africa Education Fund | Executive Director | member |
| Emmanuel Habimana | `emmanuel@reb.rw` | Rwanda Education Board | Scholarships Coordinator | member |

### Friend Account

| Name | Email | Password | Organisation | Role |
|---|---|---|---|---|
| Aisha Kamara | `aisha.demo@haliaccess.net` | `password` | None (individual) | friend |

---

## 9. Security Features

### Invite-Only Access

There is no public registration page. All users must be personally invited by an admin. Invitation tokens are 64 characters of cryptographically random data and expire after 7 days. The invitation acceptance route is rate-limited to prevent brute-force token guessing.

### Role-Based Access Control (Middleware)

Three custom middleware classes enforce access:

- **`EnsureUserIsActive`** — Applied to all authenticated routes. Blocks suspended or archived accounts immediately, even if they have a valid session.
- **`EnsureUserIsAdmin`** — Applied to all `/admin/*` routes. Permits only `super_admin` and `secretariat` roles.
- **`EnsureSuperAdmin`** — Applied specifically to destructive operations (e.g., `DELETE /admin/members/{user}`). Only `super_admin` may permanently delete accounts.

The `auth` and `verified` middleware are applied to all member routes, ensuring users must be both logged in and email-verified before accessing any content.

### Secure File Serving

User avatar files are stored in `storage/app/private/` — **outside the web root** — and cannot be accessed by guessing a URL. All requests to `/files/{path}` are routed through `FileServeController`, which requires authentication before streaming the file. This prevents unauthorised access to private profile images.

### CSRF Protection

Laravel's CSRF token protection is active on all POST, PATCH, and DELETE routes. Every form includes a `@csrf` directive and every AJAX request via Axios includes the `X-CSRF-TOKEN` header.

### Rate Limiting

The invitation acceptance route (`/invitation/{token}`) uses the named throttle guard `throttle:invitation`. Email verification links use `throttle:6,1` (6 attempts per minute). Both guards defend against automated abuse.

### Activity Logging (Audit Trail)

The `spatie/laravel-activitylog` package provides a full audit trail. The `User` model uses the `CausesActivity` trait, meaning all admin actions are attributed to the logged-in user. Activity logs are stored in the `activity_log` table and are accessible only to `super_admin` users.

### Security Headers

The `SecurityHeaders` middleware is registered and applies HTTP security headers to all responses (e.g., `X-Frame-Options`, `X-Content-Type-Options`, `X-XSS-Protection`, `Referrer-Policy`).

### Password Hashing

All passwords are hashed using bcrypt with a cost factor of **12** (`BCRYPT_ROUNDS=12`), which is above the Laravel default of 10 and provides stronger resistance to offline brute-force attacks.

### Soft Deletes

User accounts, organisations, events, posts, opportunities, and resources all use Laravel's `SoftDeletes` trait. Deleted records are retained in the database with a `deleted_at` timestamp rather than being permanently removed, enabling recovery and maintaining referential integrity in audit logs.

---

## 10. Completion Status

The following table rates the implementation status of each module based on the codebase as of March 2026.

| Module | Status | Notes |
|---|---|---|
| **Auth & Onboarding** | Complete | Login, registration via invite, email verification, password reset — all routes and Volt components present |
| **Dashboard** | Complete | Authenticated dashboard with role-aware content |
| **Member Directory** | Complete | Index and detail views, slug-based routing, org profile display |
| **Events** | Complete | Full lifecycle: create, publish, register, cancel, mark attended, export attendees, capacity management |
| **Stories & Updates** | Complete | Full CRUD in admin, public index and detail views, view counting, categories, featured/members-only flags |
| **Opportunities** | Complete | Member submission + admin management, type filtering, deadline enforcement |
| **Resources** | Complete | Library listing, private file download with auth gate, download count tracking |
| **My Profile** | Complete | Profile edit (info + password as separate forms), avatar upload, account deletion |
| **My Organisation** | Complete | Organisation profile edit, logo upload |
| **Admin — Members** | Complete | List, view, status update; super admin delete |
| **Admin — Events** | Complete | Full resource CRUD + attendee management + CSV export |
| **Admin — Posts** | Complete | Full resource CRUD |
| **Admin — Invitations** | Complete | Send, list, revoke |
| **Admin — Bulletins** | Complete | Draft, send, history with recipient count tracking |
| **Billing** | Partial | Database schema, membership plans, and subscription model complete; Stripe keys not configured — payment processing not live |
| **Notifications** | Complete | Database notifications, index page, mark read (single + all) |
| **Toast & UI System** | Complete | Alpine.js-powered toast notifications throughout the portal |
| **Seeders / Demo Data** | Complete | Admin users, 40+ organisations, member users, plans, events, posts, opportunities all seeded |

### Overall Completion Estimate: ~90%

The portal is feature-complete for all core network operations. The remaining ~10% consists of Stripe billing activation (API keys + webhook configuration), production infrastructure configuration (Redis, Supervisor, SMTP), and any final view-layer polish identified during user acceptance testing.

---

## 11. Known Limitations & Next Steps

### Current Limitations

1. **Billing not live:** Stripe API keys are not configured. The billing UI and database models are in place but no payments can be processed. Organisations cannot be moved between subscription tiers through the portal.

2. **Email delivery (development only):** The mail driver is set to `log`. Invitation emails and notification emails are written to `storage/logs/laravel.log` rather than being delivered. A real SMTP provider must be configured before launch.

3. **No full-text search:** While `laravel/scout` is installed, no search driver (Meilisearch, Algolia, Typesense) is configured. Directory, event, post, and opportunity browsing relies on standard database queries and filtering.

4. **Queue worker must be started manually:** In development, `php artisan queue:work` must be running for queued jobs (such as emails) to process. In production, a process manager like Supervisor is required.

5. **No two-factor authentication UI:** The `two_factor_secret` and `two_factor_recovery_codes` fields exist in the `users` table (hidden from serialisation), but no 2FA setup flow is exposed in the interface.

6. **No bulk admin actions:** Admin list views support individual record actions only. There is no bulk status update, bulk email, or bulk delete.

7. **Friend role has no UI explanation:** The `friend` role cannot post opportunities. There is no explicit UI message explaining this restriction to friends when they navigate to the opportunities area.

### Recommended Next Features

1. **Configure Stripe and activate billing** — set live keys, configure the webhook endpoint, build the subscription upgrade/downgrade flow in the billing UI.
2. **SMTP configuration** — integrate with a transactional email provider (Postmark, Mailgun, Amazon SES) so invitations and notifications are actually delivered.
3. **Two-factor authentication (2FA)** — expose the 2FA setup flow for admin accounts at minimum (Laravel Fortify supports this natively).
4. **Full-text search** — configure Laravel Scout with Meilisearch or Algolia for fast searching across the directory, events, posts, and opportunities.
5. **Redis for session and cache** — replace file-based drivers with Redis for performance and reliability at scale.
6. **Supervisor / queue worker** — configure Supervisor on the production server to keep the queue worker running persistently.
7. **Automated backups** — `spatie/laravel-backup` is installed; configure a backup schedule and an offsite destination (S3).
8. **Structured event agendas** — extend events with multi-session agenda items and speaker profiles.
9. **Bulk admin actions** — add bulk status updates and bulk email triggers in admin member list views.
10. **Public-facing landing page** — a public page at the root domain describing the network, without exposing any member content, with a link to the portal login.
11. **Network impact analytics dashboard** — Secretariat-facing dashboard showing network-wide metrics (total students supported, countries represented, events held, resources downloaded) for leadership reporting and funder presentations.
12. **Mobile Progressive Web App (PWA)** — the portal is responsive, but a PWA wrapper would improve the experience for members accessing it on mobile devices.

---

## 12. Presentation Summary — Executive Brief

---

### HALI Access Partner Portal
#### Executive Summary for HALI Leadership

**Prepared:** March 2026

---

#### What Has Been Built

The HALI Access Partner Portal is a secure, private web platform that serves as the operational hub for the HALI Access Network. It is purpose-built for the Secretariat and partner organisations — a dedicated digital space for collaboration, communication, and coordination that reflects the professionalism and mission of the network.

The portal is **not a public website**. Every person who accesses it has been personally invited by the Secretariat. This invite-only design reflects the curated, trust-based nature of the HALI network itself.

The platform has been built from the ground up using Laravel, the industry-leading PHP framework, with a modern, maintainable technology stack. It is designed to scale with the network as it grows.

---

#### Who It Serves

The portal serves three groups:

**The HALI Secretariat** has a full administration panel allowing them to manage invitations, oversee all member accounts, publish content, create and manage events, send network-wide bulletins, and track activity across the portal.

**Member Organisations** — foundations, scholarship bodies, universities, and NGOs from across Sub-Saharan Africa — have a full-featured member area where they can maintain their organisation profile, register for events, access the resource library, share opportunities, read network stories, and connect with peers.

**Friends of HALI** — individual consultants and allies — receive observer-level access to the portal's content and community without being formally linked to a member organisation.

---

#### What Partners Can Do on the Portal

Once a partner representative accepts their invitation and logs in, they can:

- **Browse the full member directory** — view all 40+ partner organisations with their profiles, countries, and impact statistics.
- **Register for HALI events** — Indabas, webinars, conferences, and workshops with real-time capacity tracking and registration windows.
- **Read Stories & Updates** — network news, impact stories, and announcements published by the Secretariat and partner organisations.
- **Browse the Opportunities Board** — jobs, fellowships, scholarships, internships, and volunteer roles posted by network members.
- **Access the Resource Library** — curated documents, templates, and reference materials shared across the network.
- **Manage their organisation profile** — keep contact details, description, logo, and impact figures up to date.
- **Receive notifications** — stay informed of network activity through in-portal notifications.

---

#### Membership Tiers

The portal supports three membership tiers, creating a sustainable funding model for the network:

| Tier | Annual Fee | Key Benefits |
|---|---|---|
| **Associate** | Free | Directory access, view events, receive bulletins, network access |
| **Partner** | $500/year | Full collaboration: post opportunities, event registration, resource library, directory listing, AGM voting |
| **Founding Partner** | $1,500/year | All Partner benefits plus: featured directory placement, Advisory Council seat, co-branding, dedicated Secretariat liaison, impact report inclusion |

---

#### Security and Trust

The portal has been built with security as a core principle:

- **Invite-only access** — no one can self-register; every account is personally vetted by the Secretariat.
- **Role-based permissions** — four distinct access levels ensure each user sees only what is appropriate for their role.
- **Secure file handling** — private files (profile images, documents) are stored outside the web root and require authentication to access.
- **Full audit trail** — every administrative action is logged and attributable to the user who performed it.
- **Email verification** — all accounts must verify their email address before gaining access.

---

#### Current State and Path to Launch

The portal is **approximately 90% complete** and ready for structured user review and testing. All core features — authentication, onboarding, directory, events, stories, opportunities, resources, profiles, admin panel, billing models, and notification system — are built and functional in the development environment.

The three steps required before the portal is live for the full network are:

1. **Connect a live email provider** — so invitation and notification emails are delivered (currently written to a development log).
2. **Activate Stripe billing** — configure live API keys to enable subscription payments.
3. **Deploy to a production server** — with an SSL-secured domain and a process manager for background jobs.

**Recommended next step:** A structured pilot review with 3–5 selected Secretariat staff and partner contacts to validate workflows and surface any feedback before the broader network rollout.

---

*This document was generated from the codebase as at March 2026. For technical queries, refer to Sections 4–6. For onboarding instructions, refer to Section 7.*

---

*End of Document*
