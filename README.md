# Alpha Digital Sarees

A Laravel-based e-commerce web application for a sarees retailer, built with Livewire for the storefront and Filament for the admin panel.

## Tech Stack

- **Framework:** Laravel 11 (PHP 8.2+)
- **Frontend interactivity:** Livewire 3, Blade templates, Tailwind CSS, Vite
- **Admin panel:** Filament 3
- **Payments:** Razorpay
- **Shipping:** Shiprocket
- **Auth:** Laravel auth + Google OAuth (Socialite) + email OTP verification
- **PDF invoices:** barryvdh/laravel-dompdf
- **Image processing:** Intervention Image (WebP conversion/optimization via `app/Services/ImageOptimizationService.php`)
- **SEO:** spatie/laravel-sitemap

## Setup

1. Install dependencies:
   ```
   composer install
   npm install
   ```
2. Copy `.env.example` to `.env` and fill in the required values (see below).
3. Generate an app key and run migrations:
   ```
   php artisan key:generate
   php artisan migrate
   ```
4. Build frontend assets:
   ```
   npm run dev    # for local development
   npm run build  # for production
   ```
5. Serve the app:
   ```
   php artisan serve
   ```

## Required Environment Variables

Beyond the standard Laravel `APP_*`, `DB_*`, `MAIL_*`, and `SESSION_*` settings, this project requires:

| Variable | Purpose |
|---|---|
| `GOOGLE_CLIENT_ID`, `GOOGLE_CLIENT_SECRET`, `GOOGLE_REDIRECT_URI` | Google OAuth login |
| `RAZORPAY_KEY`, `RAZORPAY_SECRET` | Payment processing at checkout |
| `SHIPROCKET_BASE_URL`, `SHIPROCKET_EMAIL`, `SHIPROCKET_PASSWORD` | Shipping/logistics integration |
| `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_BUCKET`, `AWS_DEFAULT_REGION` | S3 file storage (if `FILESYSTEM_DISK=s3`) |

Never commit a populated `.env` file — it is git-ignored by default.

## Useful Artisan Commands

- `php artisan images:optimize-all` (see `app/Console/Commands/OptimizeAllImages.php`; supports `--dry-run`) — regenerates optimized/WebP versions of product, fabric, occasion, and review images.
- Sitemap generation is handled by `app/Console/Commands/GenerateSitemap.php` (spatie/laravel-sitemap).

## Code Style

Run Laravel Pint before committing:
```
./vendor/bin/pint
```
