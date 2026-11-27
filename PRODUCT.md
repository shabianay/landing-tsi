# Product

<!-- impeccable:product-schema 1 -->

## Platform

web

## Users

Two audiences, weighted equally (confirmed 2026-11-27):

- **Small business owners (UMKM)** in Indonesia looking for someone to build their website or business app. Often non-technical, mostly on a phone, prefer to start the conversation on WhatsApp.
- **Medium companies / corporate teams** (marketing or IT) comparing vendors; they read the portfolio, process and FAQ closely and want evidence before contacting.

Secondary audiences: job applicants (Karier page), and the internal admin who manages content, leads, applicants and partner sales in `/admin`.

## Product Purpose

The Solution ID is a web and business-app development studio. The public site exists to make a visitor trust the studio enough to start a consultation (contact form or WhatsApp). The admin panel exists so one or two people can run the content, leads, applicants and partner commissions without touching code.

## Positioning

A studio that ships a professional website for an Indonesian business in 1–3 weeks, with a transparent step-by-step process, post-launch support, and a real portfolio of delivered projects.

## Operating Context

- Public site is bilingual (Indonesian default, English via `/lang/en`); all copy goes through `__()` with `lang/en.json`.
- Conversion paths: `/contact` form (reCAPTCHA + honeypot), WhatsApp deep link, marketing popup.
- Content (services, projects, testimonials, FAQ, process steps, partners/client logos, articles, settings, stats) is managed from the admin panel; the design must survive any of these being empty or long.
- Admin is used on desktop and phone by the owner.

## Capabilities and Constraints

- Laravel 12 Blade, Tailwind 3, Alpine.js 3, Vite. No React.
- Strict CSP: no inline scripts or inline event handlers; behaviour lives in `resources/js/app.js` via `data-*` hooks. Fonts only from hosts allowed in `app/Http/Middleware/SecurityHeaders.php` (Google Fonts, Fontshare) unless that header is updated.
- Shared hosting behind Cloudflare; keep pages light.
- `main` is protected: changes ship via PR, tests must pass, merge auto-deploys.

## Brand Commitments

- The logo (`public_html/logo.webp`, configurable via Setting `logo`) stays: a stepped block mark of slate squares with one blue square, plus the wordmark "The Solution ID".
- Brand blue **#3498DB** stays the primary colour (confirmed 2026-11-27). Slate of the logo (~#415060) is part of the identity.
- Copy may be tightened for clarity, but existing claims and numbers stay exactly as they are (no additions, no removals) — e.g. satisfaction guarantee, revisions, 1–3 week timeline, stats from Settings.

## Evidence on Hand

- Portfolio projects, testimonials, client/partner logos, services, FAQ and process steps from the database (admin-managed).
- Stats from Settings: `stat_projects`, `stat_rating`, `stat_clients`, `stat_timeline`.
- No team photos or office imagery in the repo. Do not invent clients, testimonials, prices or figures.

## Product Principles

1. The next step is always one tap away: WhatsApp or consultation, from every page, on a phone.
2. Show the work, don't describe it: portfolio and process carry the persuasion.
3. Plain Indonesian over marketing adjectives.
4. Everything admin-managed must look right when empty, sparse, or overflowing.

## Accessibility & Inclusion

WCAG 2.1 AA: contrast, visible focus, 44px touch targets, reduced-motion respected, works without JS for reading content.
