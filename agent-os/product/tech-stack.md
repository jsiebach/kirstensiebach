# Tech Stack

## Framework & Runtime
- **Application Framework:** Laravel 12 (upgrading from Laravel 10)
- **Language/Runtime:** PHP 8.4.13
- **Package Manager:** Composer 2.x
- **Development Environment:** Laravel Sail (Docker-based local development)

## Backend Architecture
- **Admin Panel:** Laravel Filament 4 (replacing Laravel Nova 4.33)
- **Content Editor:** FilamentTiptapEditor or Filament-compatible block editor
- **ORM:** Eloquent (Laravel's built-in ORM)
- **Routing:** Laravel Router with named routes
- **Authentication:** Laravel Sanctum or built-in authentication
- **Authorization:** Laravel Policies and Gates

## Database & Storage
- **Database:** MySQL 8.x
- **Schema Migrations:** Laravel migrations
- **Seeders & Factories:** Laravel database seeders and model factories
- **File Storage:** Laravel filesystem with local/S3 storage drivers
- **Media Management:** Spatie Laravel Media Library or custom media handling

## Frontend
- **Template Engine:** Blade (Laravel's templating engine)
- **CSS Architecture:** Custom SASS/SCSS (replacing Bootstrap 3)
- **JavaScript:** Vanilla JavaScript with Hotwire Turbo for SPA-like navigation
- **Asset Bundling:** Laravel Mix 6.x (current), may upgrade to Vite in future
- **Icons:** Font Awesome 4.x (current), consider upgrading to Font Awesome 6 or heroicons
- **Frontend Framework:** No JavaScript framework (using Blade server-side rendering)

## Current Dependencies
### Production Dependencies
- **guzzlehttp/guzzle** (^7.0.1) - HTTP client for API requests
- **laravel/tinker** (^2.5) - Interactive REPL for Laravel
- **mdixon18/fontawesome** (^0.2.1) - Font Awesome integration
- **spatie/laravel-schemaless-attributes** (^2.0) - Flexible model attributes
- **spatie/laravel-sitemap** (^7.0) - SEO sitemap generation
- **jQuery** (^3.6.0) - DOM manipulation and AJAX

### Removed in Upgrade
- **laravel/nova** (4.33.3) - Being replaced by Filament 4
- **outl1ne/nova-settings** (^5.1) - Settings management (migrate to Filament alternative)
- **outl1ne/nova-sortable** (^3.4) - Sortable resources (migrate to Filament alternative)
- **vmitchell85/nova-links** (^2.1) - Link fields (migrate to Filament alternative)

### New Dependencies (To Be Added)
- **filament/filament** (^4.x) - Modern admin panel framework
- **filament/spatie-laravel-media-library-plugin** (^4.x) - Media management for Filament
- **awcodes/filament-tiptap-editor** (^4.x) - Rich block-style editor for Filament
- **intervention/image** (^3.x) - Image processing and optimization

## Testing & Quality
- **Test Framework:** PHPUnit 9.x (current), upgrade to PHPUnit 10+ with Laravel 12
- **Code Formatting:** Laravel Pint 1.x
- **IDE Support:** Laravel IDE Helper for better autocomplete
- **Error Handling:** Spatie Laravel Ignition for better error pages
- **Testing Utilities:** Laravel's built-in testing features (factories, assertions, HTTP tests)

## Development Tools
- **Local Development:** Laravel Sail (Docker containers)
- **API Testing:** Laravel Tinker for interactive testing
- **Database Tools:** Laravel migrations, seeders, and artisan commands
- **Debugging:** Laravel Telescope (optional), Laravel Debugbar (optional)
- **Code Quality:** Laravel Pint for consistent code style

## Third-Party Integrations
- **Social Media:** Instagram API or oEmbed for Instagram feed integration (to be determined)
- **Photo Hosting:** Option for Flickr API integration for external galleries
- **Email:** Laravel Mail (SMTP/Mailgun/SendGrid configuration)
- **SEO:** Spatie Laravel Sitemap for XML sitemap generation

## Deployment & Infrastructure
- **Hosting:** To be determined (current hosting provider)
- **Web Server:** Nginx or Apache
- **PHP Process Manager:** PHP-FPM
- **SSL/TLS:** Let's Encrypt or hosting provider SSL
- **Deployment Strategy:** Git-based deployment (current method to be documented)
- **Environment Management:** .env files for configuration

## Browser Compatibility
- **Target Browsers:** Modern browsers (Chrome, Firefox, Safari, Edge)
- **Mobile Support:** Fully responsive design for iOS Safari and Android Chrome
- **Legacy Support:** No support for IE11 or older browsers

## Performance Optimization
- **Asset Compilation:** Laravel Mix for minification and bundling
- **Caching:** Laravel cache for views, routes, and config
- **Image Optimization:** Intervention Image for automatic image resizing and compression
- **Query Optimization:** Eager loading, query caching, and database indexing
- **CDN:** Optional CDN integration for static assets

## Security
- **CSRF Protection:** Laravel built-in CSRF tokens
- **SQL Injection Prevention:** Eloquent ORM parameter binding
- **XSS Protection:** Blade template automatic escaping
- **Authentication:** Laravel's authentication with secure password hashing
- **Authorization:** Policy-based access control
- **Dependency Management:** Regular composer updates for security patches

## Version Control & CI/CD
- **Version Control:** Git
- **Repository Hosting:** GitHub (based on project structure)
- **CI/CD:** GitHub Actions (optional, to be configured)
- **Branch Strategy:** Git Flow or GitHub Flow (to be documented)

## Notes on Tech Stack Evolution
- **Bootstrap 3 to Custom SCSS:** The site currently uses Bootstrap 3 which is outdated. The roadmap includes replacing this with modern custom SCSS for a professional academic aesthetic.
- **Laravel Mix to Vite:** Consider upgrading from Laravel Mix to Vite during Laravel 12 migration for faster build times.
- **jQuery Dependency:** Evaluate reducing or removing jQuery dependency in favor of vanilla JavaScript during frontend redesign.
- **Turbo Integration:** Currently using Hotwire Turbo (^7.0.0-beta.4) for SPA-like navigation - ensure compatibility during upgrades.
