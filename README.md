# GEDCO - Gaza Electricity Distribution Company

<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
</p>

<p align="center">
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Laravel Version"></a>
  <a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
  <img src="https://img.shields.io/badge/PHP-8.2%2B-blue" alt="PHP Version">
</p>

## About

GEDCO (Gaza Electricity Distribution Company) is a comprehensive bilingual (Arabic/English) web application built with Laravel 12. The platform serves as the official website and content management system for the Gaza Electricity Distribution Company, providing public-facing content and a robust admin panel for managing company information, news, job announcements, tenders, and staff profiles.

## Features

### Public Website
- **Bilingual Support**: Full Arabic (RTL) and English (LTR) language support with dynamic direction switching
- **Homepage**: Dynamic sliders, impact statistics, about us section, and video integration
- **News Center**: News articles with detailed views and search functionality
- **Job Announcements**: Job listings and announcements management
- **Tenders**: Tender announcements and management
- **Advertisements**: Public advertisement system
- **Search**: Site-wide search with suggestions
- **Staff Profiles**: Staff and dependents profile management with secure session-based editing

### Admin Panel
- **User Management**: Complete user management system with roles and permissions
- **Role-Based Access Control**: Powered by Spatie Laravel Permission package
- **Content Management**: 
  - News articles with rich text editor (Quill)
  - Job announcements
  - Tender management
  - Advertisement management with PDF export
  - Slider management
  - Site settings
  - Footer and social links
- **Staff Management**: Staff profiles and dependents management
- **Activity Logs**: Comprehensive activity tracking for admin users
- **Temporary Passwords**: Secure temporary password system for user accounts
- **User Impersonation**: Super-admin can impersonate users for support purposes

### Technical Features
- **Caching**: Intelligent caching system for improved performance
- **File Uploads**: Secure file upload handling with image optimization
- **Responsive Design**: Modern UI built with Tailwind CSS
- **Interactive UI**: Alpine.js for dynamic frontend interactions
- **Animations**: AOS (Animate On Scroll) for smooth page transitions
- **Drag & Drop**: SortableJS for intuitive content ordering
- **Notifications**: SweetAlert2 for beautiful alert dialogs

## Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x and npm
- MySQL/MariaDB >= 8.0
- Web server (Apache/Nginx) or PHP built-in server

## Installation

### Quick Setup

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd gedco
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure your `.env` file**
   ```env
   APP_NAME="GEDCO"
   APP_URL=http://localhost
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gedco
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build frontend assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

### Automated Setup

Alternatively, use the provided setup script:

```bash
composer run setup
```

This will:
- Install Composer dependencies
- Create `.env` file if it doesn't exist
- Generate application key
- Run migrations
- Install npm dependencies
- Build frontend assets

## Development

### Running Development Environment

For a complete development environment with hot-reload, queue worker, and log monitoring:

```bash
composer run dev
```

This command runs concurrently:
- Laravel development server
- Queue worker
- Laravel Pail (log monitoring)
- Vite dev server (hot-reload)

### Available Commands

#### Composer Scripts
- `composer run setup` - Complete project setup
- `composer run dev` - Start development environment
- `composer run test` - Run PHPUnit tests

#### Artisan Commands
- `php artisan migrate` - Run database migrations
- `php artisan db:seed` - Seed database with initial data
- `php artisan serve` - Start development server
- `php artisan queue:work` - Process queued jobs
- `php artisan cache:clear` - Clear application cache

#### NPM Scripts
- `npm run dev` - Start Vite development server
- `npm run build` - Build production assets

## Project Structure

```
gedco/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controllers
│   │   │   ├── Site/           # Public site controllers
│   │   │   └── Staff/          # Staff profile controllers
│   │   ├── Middleware/         # Custom middleware
│   │   └── Requests/           # Form request validation
│   ├── Models/                 # Eloquent models
│   ├── Providers/              # Service providers
│   └── View/                   # View components
├── config/                     # Configuration files
├── database/
│   ├── migrations/             # Database migrations
│   └── seeders/               # Database seeders
├── public/                     # Public assets
├── resources/
│   ├── views/                  # Blade templates
│   │   ├── admin/              # Admin panel views
│   │   ├── site/               # Public site views
│   │   └── staff/              # Staff views
│   ├── css/                    # Stylesheets
│   └── js/                     # JavaScript files
├── routes/
│   ├── admin.php               # Admin routes
│   ├── auth.php                # Authentication routes
│   └── web.php                 # Public routes
└── tests/                      # PHPUnit tests
```

## Key Technologies

### Backend
- **Laravel 12** - PHP framework
- **Spatie Laravel Permission** - Role and permission management
- **Doctrine DBAL** - Database abstraction layer

### Frontend
- **Tailwind CSS** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **Vite** - Next-generation frontend tooling
- **AOS** - Animate On Scroll library
- **SortableJS** - Drag and drop functionality
- **SweetAlert2** - Beautiful alert dialogs

### Development Tools
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Testing framework
- **Laravel Pail** - Log monitoring
- **Laravel Sail** - Docker development environment

## Database

The application uses MySQL/MariaDB. Key tables include:

- `users` - User accounts
- `roles` & `permissions` - RBAC system
- `news` - News articles
- `jobs` - Job announcements
- `tenders` - Tender announcements
- `advertisements` - Advertisements
- `staff_profiles` - Staff information
- `staff_dependents` - Staff dependents
- `sliders` - Homepage sliders
- `impact_stats` - Impact statistics
- `activity_logs` - Activity tracking

## Security Features

- **CSRF Protection** - Built-in Laravel CSRF protection
- **Rate Limiting** - Throttling on sensitive routes
- **Password Hashing** - Secure password storage
- **Session Security** - Secure session-based authentication for profile editing
- **Role-Based Access** - Granular permission system
- **Input Validation** - Form request validation
- **SQL Injection Protection** - Eloquent ORM protection

## Default Admin Credentials

After running seeders, you can log in with:

- **Super Admin**: Created via `SuperAdminSeeder`
- **Admin**: Created via `AdminSeeder`

Check the seeders for default credentials or create your own admin account.

## Testing

Run tests with:

```bash
composer run test
```

Or directly:

```bash
php artisan test
```

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## Code Style

The project uses Laravel Pint for code style. Run:

```bash
./vendor/bin/pint
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Support

For support, email [support@gedco.ps](mailto:support@gedco.ps) or open an issue in the repository.

---

**Built with ❤️ for Gaza Electricity Distribution Company**
