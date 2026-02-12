# Laravel / Vue 3 Starter Kit - OCR Engine

[![PHP Version](https://img.shields.io/badge/php-%5E8.2-777BB4?style=flat&logo=php)](composer.json)
[![Laravel Version](https://img.shields.io/badge/laravel-%5E12.0-FF2D20?style=flat&logo=laravel)](composer.json)
[![Vue Version](https://img.shields.io/badge/vue-3.5-4FC08D?style=flat&logo=vue.js)](package.json)
[![Inertia Version](https://img.shields.io/badge/inertia-%5E2.0-9553E9?style=flat)](composer.json)

**OCR Engine** is a modern web application starter kit built with Laravel 12, Vue 3, and Inertia.js, configured specifically for **Laragon 6.0** development environment on Windows. This project integrates OCR processing capabilities via OCR.space API and Google Gemini AI.

## 🚀 Features

- **Laravel 12** with PHP 8.2+
- **Vue 3** Composition API with `<script setup>`
- **Inertia.js v2** - Monolithic SPA without API boilerplate
- **Laravel Fortify** - Authentication scaffolding
- **Tailwind CSS v4** - Utility-first styling
- **Reka UI** - Headless component primitives
- **OCR.space API** - Document text extraction
- **Google Gemini AI** - Advanced text processing
- **Laravel Wayfinder** - Type-safe named routes
- **Pest PHP** - Testing suite
- **Vite 7** - Lightning-fast builds
- **ESLint + Prettier** - Code quality tools

## 📋 Requirements (Laragon 6.0)

This project is configured for **Laragon 6.0** with the following services:

| Service | Version |
|---------|---------|
| **Apache** | 2.4.62 |
| **MySQL** | 8.1.0 |
| **PostgreSQL** | 15.3 |
| **Redis** | 5.0.14 |
| **PHP** | 8.2+ (configured in Laragon) |
| **Node.js** | 20+ (configured in Laragon) |
| **npm** | 10+ |
| **Composer** | 2.5+ |

## 🔧 Installation

### 1. Clone the repository

```bash
cd C:\laragon\www
git clone <repository-url> ocr-engine
cd ocr-engine
```

### 2. Environment Setup

Copy the environment file and generate application key:

```bash
cp .env.example .env
php artisan key:generate
```

The `.env` file should already be configured with:

```env
APP_NAME=OCR Engine
APP_URL=http://localhost
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ocr-engine
DB_USERNAME=root
DB_PASSWORD=
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
REDIS_HOST=127.0.0.1
REDIS_PORT=6379

OCRSPACE_KEY=K83691918588957
GEMINI_API_KEY=AIzaSyCwg3nsMoud5nQwOr3dPSGVfw7KjRWjH0U
```

### 3. Create Database

Open Laragon's MySQL terminal or use HeidiSQL:

```sql
CREATE DATABASE `ocr-engine`;
```

### 4. Install Dependencies

```bash
composer install
npm install
```

### 5. Run Migrations

```bash
php artisan migrate
```

### 6. Build Assets

```bash
npm run build
```

### 7. Start Development Server

#### Option A: Laragon Native (Recommended)
1. Start Laragon
2. Click "Start All"
3. Access via `http://ocr-engine.test`

#### Option B: Project Dev Script
```bash
composer run dev
```

This will launch:
- Laravel development server (http://localhost:8000)
- Queue worker
- Vite development server (http://localhost:5173)

## 🏗️ Project Structure

```
├── app/
│   ├── Actions/              # Fortify actions
│   │   ├── Fortify/
│   │   │   ├── CreateNewUser.php
│   │   │   ├── ResetUserPassword.php
│   │   │   └── UpdateUserPassword.php
│   │   └── Jetstream/        # (if applicable)
│   ├── Http/
│   │   ├── Controllers/      # Inertia controllers
│   │   │   └── Controller.php
│   │   └── Middleware/       # HandleInertiaRequests, etc.
│   ├── Models/               # Eloquent models
│   │   ├── User.php
│   │   └── Document.php      # OCR documents model
│   └── Providers/            # Service providers
│       └── FortifyServiceProvider.php
├── database/
│   ├── migrations/           # Database schema
│   ├── factories/           # Model factories
│   └── seeders/            # Database seeders
├── resources/
│   ├── js/
│   │   ├── Components/      # Vue components
│   │   │   ├── UI/          # Reusable UI components
│   │   │   └── Forms/       # Form components
│   │   ├── Composables/     # Vue composables
│   │   │   ├── useOCR.ts    # OCR functionality
│   │   │   └── useGemini.ts # Gemini AI integration
│   │   ├── Layouts/         # Inertia layouts
│   │   │   └── AppLayout.vue
│   │   ├── Pages/           # Inertia pages
│   │   │   ├── Auth/        # Authentication pages
│   │   │   ├── Dashboard.vue
│   │   │   └── Profile/
│   │   ├── types/           # TypeScript declarations
│   │   │   └── inertia.d.ts
│   │   ├── lib/            # Utilities
│   │   │   └── utils.ts
│   │   └── app.ts          # Application entry
│   └── css/                # Global styles
│       └── app.css
├── routes/
│   ├── web.php             # Web routes
│   └── api.php             # API routes (if needed)
├── tests/                  # Pest tests
│   ├── Feature/
│   └── Unit/
└── package.json           # Frontend dependencies
```

## 🛠️ Available Scripts

### Composer Scripts

| Command | Description |
|---------|-------------|
| `composer run setup` | Complete project setup (install, env, key, migrate, npm) |
| `composer run dev` | Start dev servers (PHP, Queue, Vite) |
| `composer run dev:ssr` | Start dev with SSR support |
| `composer run lint` | Fix code style with Pint |
| `composer run test` | Run lint checks + tests |
| `composer run test:lint` | Check code style only |

### NPM Scripts

| Command | Description |
|---------|-------------|
| `npm run dev` | Start Vite dev server |
| `npm run build` | Build for production |
| `npm run build:ssr` | Build with SSR support |
| `npm run format` | Format code with Prettier |
| `npm run format:check` | Check code formatting |
| `npm run lint` | Lint and fix Vue/TS files |

## 🔐 Authentication

This project uses **Laravel Fortify** with Inertia.js frontend. Authentication routes and views are pre-configured:

### Features
- **Login** - Email/password authentication
- **Registration** - New user registration
- **Password Reset** - Forgot password flow
- **Email Verification** - Verify email addresses
- **Profile Management** - Update profile information
- **Password Update** - Change password
- **Two-Factor Authentication** - Coming soon

### Fortify Configuration
Fortify is configured in `config/fortify.php` with Inertia views:

```php
'views' => true, // Inertia views are used
'features' => [
    Features::registration(),
    Features::resetPasswords(),
    Features::emailVerification(),
    Features::updateProfileInformation(),
    Features::updatePasswords(),
    // Features::twoFactorAuthentication(),
],
```

## 🧪 Testing

Run the test suite:

```bash
composer run test
```

Individual test commands:

```bash
# Laravel/Pest tests
php artisan test

# Code style check
./vendor/bin/pint --test

# Format code
./vendor/bin/pint

# Run specific test file
php artisan test --filter=UserTest
```

## 🌐 Environment Configuration

### Database
The project is configured for **MySQL** by default, but PostgreSQL is also available in your Laragon setup:

**For PostgreSQL:**
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=ocr-engine
DB_USERNAME=postgres
DB_PASSWORD=
```

**For MySQL (default):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ocr-engine
DB_USERNAME=root
DB_PASSWORD=
```

### Redis Configuration
Redis is enabled for caching and queueing:

```env
REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=null
CACHE_STORE=redis
QUEUE_CONNECTION=redis
```

Make sure Redis service is running in Laragon:
- Click "Redis" in Laragon menu
- Or start manually: `redis-server`

### Queue Worker
The application uses database/redis queue driver. Start the queue worker:

```bash
# Database driver
php artisan queue:work

# Redis driver
php artisan queue:work redis

# With the dev script (included)
composer run dev
```

## 📦 Key Dependencies

### Backend Dependencies
| Package | Version | Purpose |
|---------|---------|---------|
| `laravel/framework` | ^12.0 | Laravel core |
| `inertiajs/inertia-laravel` | ^2.0 | Inertia.js server adapter |
| `laravel/fortify` | ^1.30 | Authentication backend |
| `laravel/wayfinder` | ^0.1.9 | Type-safe route generation |
| `laravel/pail` | ^1.2.2 | Log inspection |
| `pestphp/pest` | ^4.3 | Testing framework |

### Frontend Dependencies
| Package | Version | Purpose |
|---------|---------|---------|
| `vue` | ^3.5.13 | Vue.js core |
| `@inertiajs/vue3` | ^2.3.7 | Inertia Vue adapter |
| `@vueuse/core` | ^12.8.2 | Vue composable utilities |
| `reka-ui` | ^2.6.1 | Headless Vue components |
| `lucide-vue-next` | ^0.468.0 | Icon library |
| `tailwindcss` | ^4.1.1 | Utility-first CSS |
| `class-variance-authority` | ^0.7.1 | Variant management |
| `clsx` | ^2.1.1 | Conditional classes |
| `tailwind-merge` | ^3.2.0 | Merge Tailwind classes |
| `vue-input-otp` | ^0.3.2 | OTP input component |

## 🤖 OCR & AI Integration

### OCR.space Configuration
The project includes a pre-configured OCR.space API key:

```env
OCRSPACE_KEY=K83691918588957
```

**Example Controller:**
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class OCRController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'document' => 'required|file|mimes:pdf,png,jpg,jpeg|max:10240',
        ]);

        $response = Http::asMultipart()
            ->timeout(30)
            ->post('https://api.ocr.space/parse/image', [
                'apikey' => env('OCRSPACE_KEY'),
                'file' => $request->file('document'),
                'language' => 'eng',
                'isOverlayRequired' => false,
                'isCreateSearchablePdf' => false,
                'isSearchablePdfHideTextLayer' => false,
            ]);

        $result = $response->json();
        
        if ($result['IsErroredOnProcessing']) {
            return back()->withErrors([
                'message' => 'OCR processing failed: ' . $result['ErrorMessage'][0]
            ]);
        }

        $extractedText = $result['ParsedResults'][0]['ParsedText'] ?? '';
        
        return Inertia::render('OCR/Result', [
            'text' => $extractedText,
            'filename' => $request->file('document')->getClientOriginalName(),
        ]);
    }
}
```

### Google Gemini AI Configuration
The project includes a pre-configured Gemini API key:

```env
GEMINI_API_KEY=AIzaSyCwg3nsMoud5nQwOr3dPSGVfw7KjRWjH0U
```

**Example Service:**
```php
<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeminiService
{
    protected string $apiKey;
    protected string $endpoint = 'https://generativelanguage.googleapis.com/v1/models/gemini-pro:generateContent';

    public function __construct()
    {
        $this->apiKey = env('GEMINI_API_KEY');
    }

    public function analyzeText(string $text): array
    {
        $response = Http::post("{$this->endpoint}?key={$this->apiKey}", [
            'contents' => [
                [
                    'parts' => [
                        ['text' => "Analyze this document text and extract key information: \n\n" . $text]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => 0.4,
                'maxOutputTokens' => 2048,
            ]
        ]);

        return $response->json();
    }
}
```

## 🐞 Troubleshooting

### Common Issues & Solutions

#### Vite manifest not found
```bash
npm run build
```

#### Class "Inertia\Testing\TestResponseMacro" not found
```bash
composer update inertiajs/inertia-laravel
```

#### Redis extension not loaded
1. Right-click Laragon → PHP → Extensions
2. Check `php_redis`
3. Restart Laragon

#### Port conflicts
Default ports used:
| Service | Port |
|---------|------|
| Apache | 80/443 |
| MySQL | 3306 |
| PostgreSQL | 5432 |
| Redis | 6379 |
| Laravel dev | 8000 |
| Vite | 5173 |

To change ports:
```env
# Laravel dev server
APP_PORT=8080

# Vite
VITE_PORT=5174
```

#### Database connection issues
1. Verify MySQL is running in Laragon
2. Check credentials in `.env`
3. Create database if not exists:
```sql
CREATE DATABASE `ocr-engine`;
```

#### npm install errors
Clear npm cache and reinstall:
```bash
npm cache clean --force
rm -rf node_modules package-lock.json
npm install
```

## 🚀 Production Deployment

### Server Requirements
- PHP 8.2+
- MySQL 8.0+ / PostgreSQL 15+
- Redis 5.0+
- Node.js 20+ (for building assets)
- Composer 2.5+

### Build for Production
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev
npm ci

# Build assets
npm run build

# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force
```

### Nginx Configuration Example
```nginx
server {
    listen 80;
    server_name ocr-engine.com;
    root /var/www/ocr-engine/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## 📊 Performance Optimization

### Caching Configuration
```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Database Indexing
Create indexes for frequently queried columns:
```php
// database/migrations/xxx_add_indexes_to_documents_table.php
Schema::table('documents', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('created_at');
    $table->index('status');
});
```

### Vite Optimization
```javascript
// vite.config.js
export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['vue', '@inertiajs/vue3'],
                    ui: ['reka-ui', 'lucide-vue-next'],
                },
            },
        },
        minify: 'esbuild',
        target: 'es2020',
    },
});
```

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open Pull Request

### Coding Standards
- **PHP**: Follow PSR-12 and Laravel conventions
- **JavaScript/Vue**: Use ESLint + Prettier configuration
- **Database**: Use migrations, name tables in snake_case plural
- **Tests**: Write Pest tests for new features

## 📄 Changelog

### Version 1.0.0 (Feb 12, 2026)
- Initial release
- Laravel 12 + Vue 3 + Inertia.js v2 setup
- Laravel Fortify authentication
- OCR.space API integration
- Google Gemini AI integration
- Tailwind CSS v4 + Reka UI components
- Laragon 6.0 optimized configuration

## 📝 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🙏 Acknowledgments

- [Laravel](https://laravel.com)
- [Vue.js](https://vuejs.org)
- [Inertia.js](https://inertiajs.com)
- [Tailwind CSS](https://tailwindcss.com)
- [Laragon](https://laragon.org)
- [OCR.space](https://ocr.space)
- [Google Gemini](https://deepmind.google/technologies/gemini/)

---

## 🆘 Support

For support and questions:
- 📧 Email: support@ocr-engine.com
- 🐛 Issues: [GitHub Issues](https://github.com/yourusername/ocr-engine/issues)
- 📚 Documentation: [Wiki](https://github.com/yourusername/ocr-engine/wiki)

---

**Built with ❤️ for Laragon 6.0**  
*Last updated: February 12, 2026*