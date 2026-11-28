# Landing TSI

A modern landing page website for TSI (PT Teknologi Sistem Integrasi) built with Laravel 12, Tailwind CSS, and Alpine.js.

## Features

- **Landing Page** - Home, About, Contact, Portfolio, and Article sections
- **Admin Panel** - Full content management system for site administrators
- **Multi-language Support** - English and Indonesian language switching
- **Authentication** - Secure login system with Laravel Breeze
- **SEO Optimized** - Sitemap, robots.txt, and Open Graph meta tags
- **Page Analytics** - Track page views for articles and projects
- **Contact Form** - Rate-limited contact submission form
- **Dynamic Content** - Easily manage testimonials, FAQs, services, and more

## Tech Stack

- **Backend**: Laravel 13, PHP 8.3+ (production runs 8.4)
- **Frontend**: Tailwind CSS 4, Alpine.js
- **Build Tool**: Vite 7
- **Database**: SQLite (default) / MySQL
- **Image Processing**: Intervention Image

## Requirements

- PHP 8.3 or higher (8.4 recommended, matching production)
- Composer
- Node.js & npm
- XAMPP / Laravel Herd / Docker (for local development)

## Installation

1. Clone the repository:
```bash
git clone https://github.com/shabianay/landing-tsi.git
cd landing-tsi
```

2. Install dependencies:
```bash
composer install
npm install
```

3. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations and seeders:
```bash
php artisan migrate
```

5. Build frontend assets:
```bash
npm run build
```

6. Start the development server:
```bash
php artisan serve
```

## Project Structure

- `app/Http/Controllers/` - Application controllers
- `app/Models/` - Eloquent models (Article, Client, Contact, Faq, Partner, Popup, ProcessStep, Project, Service, Setting, Testimonial, User)
- `resources/views/` - Blade templates
- `routes/web.php` - Web routes
- `database/migrations/` - Database migrations

## Admin Panel

Access the admin panel at `/admin` (requires admin privileges).

Manage the following content:
- Clients & Projects
- Articles & FAQs
- Testimonials & Partners
- Services & Process Steps
- Site Settings & Popups
- Contact Form Submissions

## Sales Recruitment

- Public job listing and application form: `/jobs`
- Admin applicant dashboard: `/admin/applicants` (admin login required)
- Applicants must be at least 18; duplicate WhatsApp numbers are rejected, and status changes are logged.
- Admin records the agreement version when marking it signed. Partner codes such as `TSI-001` are assigned once when status becomes active.
- Active partners send prospective client details to TSI via WhatsApp before making an offer; clients should mention the partner code. Admin records the lead and subsequent deals/payments in the partner sales dashboard; partner self-service and lead entry are deferred to phase 2.
- Admin can record discounted deals, each received payment, unpaid/paid 10% commissions, and monthly bonuses. Bonus eligibility uses distinct clients with a deal of at least Rp2,000,000 and a DP received in the calendar month.
- CSV export respects the active applicant filters; deleted applications are soft-deleted.
- MySQL/MariaDB backups are written outside the public directory to `storage/app/private/backups`.

For local development, configure `.env.local` for your local database, then run:

```bash
php artisan migrate --env=local
npm run build
php artisan serve --env=local
```

Run `php artisan database:backup --env=local` to create a backup manually. In production the daily 02:00 backup is a server cron running `scripts/backup-database.sh` (the host disables `proc_open`), and deploys also back up before migrating. The deploy installs one `schedule:run` cron (`# tsi-schedule`) for the remaining scheduled tasks (page-view pruning 03:00, AI article 08:00).

## License

This project is licensed under the MIT License.

### Running locally next to an older PHP (XAMPP)

This project needs PHP 8.3+, while other local projects may still use XAMPP's PHP 8.2.
On the maintainer's Windows machine PHP 8.4 is installed separately, so `php` stays XAMPP:

```bash
php84 artisan serve --env=local      # this project
composer84 install                    # Composer running on PHP 8.4
php artisan ...                       # other projects keep XAMPP's PHP
```

`php84` / `composer84` are small shims in `%LOCALAPPDATA%\Microsoft\WindowsApps` pointing to
`%LOCALAPPDATA%\Programs\php84\php.exe`.
