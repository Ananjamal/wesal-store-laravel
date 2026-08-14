<p align="center">
  <h1 align="center">✦ متجر وِصال | WISAL STORE ✦</h1>
  <p align="center">
    <strong>A Premium Full-Stack E-Commerce Platform</strong><br>
    Built for Gaza Sky Geeks × Chingu Solo Project Evaluation (Tier 3)
  </p>
  <p align="center">
    <img src="https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11" />
    <img src="https://img.shields.io/badge/Vue.js-3.x-4FC08D?style=for-the-badge&logo=vuedotjs&logoColor=white" alt="Vue 3" />
    <img src="https://img.shields.io/badge/Inertia.js-1.x-9553E9?style=for-the-badge&logo=inertia&logoColor=white" alt="Inertia.js" />
    <img src="https://img.shields.io/badge/Filament-v3-FDAE4B?style=for-the-badge&logo=laravel&logoColor=black" alt="Filament v3" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-3.4-38BDF8?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/Pest-v3.0-8B5CF6?style=for-the-badge&logo=pest&logoColor=white" alt="Pest PHP" />
  </p>
</p>

---

## 📌 Project Overview | نبذة عن المشروع

**Wisal Store (متجر وِصال)** is a full-featured, modern E-Commerce web application engineered to deliver a seamless shopping experience for customers and a robust management suite for store administrators.

The project blends cultural heritage aesthetics with modern user interfaces (Optimistic UI, Skeleton Loaders, Smooth View Transitions) powered by **Laravel 11**, **Inertia.js**, **Vue 3**, and **Filament v3**.

> 🎓 **Submitted for**: GSG × Chingu: Team Project Experience Program — Developer Track (Solo Project Evaluation).  
> 🏷️ **Tier**: Tier 3 (Full-Stack Advanced E-Commerce Application).

---

## 🎨 Brand Identity & Design System

The visual identity of **Wisal Store** is governed by standard brand design tokens representing modern elegance merged with Palestinian cultural warmth:

| Color Name | Hex Code | Variable Name | Role & Usage |
|---|---|---|---|
| **Beige Heritage** | `#D1CBB4` | `wisal-beige` | Warm heritage accents, subtle badges & secondary borders |
| **Aqua Blue** | `#467389` | `wisal-aqua` | Primary brand color, key calls-to-action & interactive states |
| **Ivory** | `#FFFBF5` | `wisal-ivory` | Main light background, ultra-clean surface container |
| **Charcoal** | `#323232` | `wisal-charcoal` | Primary typography, headers & high-contrast dark UI elements |

---

## ✨ Key Features & Capability Matrix

### 🛒 Storefront & Customer Experience (Vue 3 + Inertia)
- **Product Catalog & Variant Management**: Dynamic color/size selector with real-time stock updates.
- **Instant Search & Filtering (Meilisearch)**: High-speed fuzzy search, category filtering, and price range sliders.
- **Flash Sales & Promotional Banners**: Countdown timers for special sales and campaign offers.
- **Cart & Dynamic Checkout**: Guest & authenticated cart sync, optimistic cart updates, coupon validation.
- **Wishlist & Customer Dashboard**: Order history, order tracking, address book management, and user profile updates.
- **Interactive Reviews & Ratings**: Verified purchaser reviews with star ratings and media uploads.
- **Bilingual Support (i18n)**: Seamless language switching between Arabic (RTL) and English (LTR).

### ⚙️ Back-Office Administration (Filament v3)
- **Resource Management**: Products, Categories, Collections, Coupons, and Customer Management.
- **Order Lifecycle Management**: Order processing pipeline (Pending $\rightarrow$ Processing $\rightarrow$ Shipped $\rightarrow$ Completed / Cancelled).
- **Abandoned Cart Recovery**: Automated queue jobs (`AbandonedCartReminderJob`) sending reminders to visitors.
- **Role-Based Access Control (RBAC)**: Fine-grained permissions powered by `spatie/laravel-permission`.
- **Invoicing & QR Code**: Automated PDF invoice generation (`barryvdh/laravel-dompdf`) with QR codes.
- **Analytics & Telemetry**: Integrated performance dashboard with `laravel/pulse`.

---

## 🛠️ Technology Stack

| Layer | Technologies Used |
|---|---|
| **Backend Framework** | Laravel 11.x (PHP 8.2+) |
| **Frontend Framework** | Vue 3 (Composition API) + Inertia.js |
| **Admin Panel** | Filament v3 (Livewire 3 + Alpine.js) |
| **State & Localization** | Pinia, Vue-i18n, Ziggy (Route Helper) |
| **Styling & UI** | Tailwind CSS 3.4, PostCSS, Canvas Confetti |
| **Database & Cache** | MySQL 8.0 / PostgreSQL, Redis |
| **Search Engine** | Meilisearch + Laravel Scout |
| **Authentication** | Laravel Sanctum + Laravel Socialite |
| **Media & PDF** | Spatie Media Library, DomPDF, SimpleSoftwareIO QR Code |
| **Testing & Code Quality**| Pest PHP 3.0, Laravel Pint |

---

## 📁 Directory Structure

```text
wesal-store/
├── app/
│   ├── Filament/            # Admin Panel Resources, Pages & Widgets
│   ├── Http/
│   │   ├── Controllers/     # API & Inertia Controllers
│   │   └── Requests/        # Input Validation Requests
│   ├── Jobs/                # Queue Jobs (e.g., AbandonedCartReminderJob)
│   ├── Models/              # Eloquent Models (Order, Product, Coupon, etc.)
│   └── Services/            # Business Logic Services
├── database/
│   ├── factories/           # Model Factories
│   ├── migrations/          # Schema Migrations
│   └── seeders/             # Database Seeders
├── resources/
│   ├── js/
│   │   ├── Components/      # Reusable Vue Components (WButton, WCard, etc.)
│   │   ├── Layouts/         # Inertia Layouts (AppLayout, StoreLayout)
│   │   └── Pages/           # Vue Page Views (Home, Shop, Checkout, Dashboard)
│   └── css/                 # Tailwind & Custom Design Tokens
├── routes/
│   ├── api.php              # Sanctum API Routes
│   └── web.php              # Inertia Web Routes
└── tests/                   # Pest Automated Test Suite
```

---

## 🚀 Local Installation & Setup Guide

Follow these step-by-step instructions to get a local development environment running:

### 1. Prerequisites
Ensure your machine has the following tools installed:
- **PHP** $\ge$ 8.2
- **Composer** $\ge$ 2.5
- **Node.js** $\ge$ 20.x & **npm** / **pnpm**
- **MySQL** $\ge$ 8.0
- **Meilisearch** (Optional for search features)

### 2. Clone the Repository
```bash
git clone https://github.com/Ananjamal/wesal-store-laravel.git
cd wesal-store-laravel
```

### 3. Backend Dependencies & Environment Setup
```bash
# Install PHP packages
composer install

# Copy environment template
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup & Migrations
Configure your `.env` database connection:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=wesal_store
DB_USERNAME=root
DB_PASSWORD=
```

Run database migrations and seeders:
```bash
php artisan migrate:fresh --seed
```

### 5. Frontend Dependencies & Build
```bash
# Install Node dependencies
npm install

# Start Vite dev server
npm run dev
```

### 6. Storage Link & Server Execution
```bash
# Create symbolic link for uploaded media
php artisan storage:link

# Launch local PHP development server
php artisan serve
```

Access the applications locally:
- **Storefront Application**: `http://localhost:8000`
- **Admin Panel**: `http://localhost:8000/admin`

---

## 🔑 Demo Credentials | بيانات الدخول التجريبية

After running `php artisan migrate:fresh --seed`, the following accounts are seeded automatically and ready to use:

| Role | Panel | Email | Password |
|---|---|---|---|
| 🔴 **Super Admin** (مدير النظام) | `/admin` | `admin@wisal-store.com` | `1234567890` |
| 🟡 **Manager** (مدير العمليات) | `/admin` | `manager@wisal-store.com` | `password123` |
| 🟢 **Customer** (عميل) | `/login` | *(register from storefront)* | *(self-registered)* |

> ⚠️ **For evaluators**: Please use the Super Admin credentials to access the Filament admin panel and explore the full management capabilities.

---

## 🧪 Testing & Code Quality

Run automated tests and code style checks with the following commands:

```bash
# Run test suite with Pest
vendor/bin/pest

# Run code style formatting with Laravel Pint
vendor/bin/pint
```

---

## 🌿 Git Branching & Submission Strategy

The project adheres to a **3-Tier Git Branching Model**:
- `main`: Production-ready stable release code.
- `staging`: Pre-production integration & acceptance testing.
- `develop`: Primary integration branch for active features.
- `features/*`: Modular feature branches (e.g., `features/frontend/checkout`, `features/admin/coupons`).

---

## 👨‍💻 Author & Submission Details

- **Author Name**: Anan Jamal Abed Al-Aziz Abo Tawahena (عنان جمال عبد العزيز أبو طواحينة)
- **Program**: Gaza Sky Geeks × Chingu: Team Project Experience Program
- **Track**: Developer Track (Solo Project)
- **Repository**: [wesal-store-laravel](https://github.com/Ananjamal/wesal-store-laravel)

---

<p align="center">
  Crafted with ❤️ for Palestinian craftsmanship and modern web development.
</p>
