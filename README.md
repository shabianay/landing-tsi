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

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Tailwind CSS 4, Alpine.js
- **Build Tool**: Vite 7
- **Database**: SQLite (default) / MySQL
- **Image Processing**: Intervention Image

## Requirements

- PHP 8.2 or higher
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

## License

This project is licensed under the MIT License.
