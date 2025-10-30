# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

ND-Project is a Laravel 12 ecommerce application specializing in selling products across categories like shrooms, capsules, sheets, pet food, and flowers. It features a dual interface: a customer-facing storefront and an admin dashboard for product and order management.

## Development Commands

### Quick Start Development Environment
```bash
composer dev
```
This single command starts all development services: Laravel server, queue worker, logs, and Vite for assets.

### Individual Commands

#### Backend (Laravel)
```bash
# Start Laravel development server
php artisan serve

# Run database migrations
php artisan migrate

# Run database migrations with seeders
php artisan migrate --seed

# Run specific seeder
php artisan db:seed --class=ProductSeeder

# Clear application cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Generate application key
php artisan key:generate

# Create storage symlink
php artisan storage:link

# Start queue worker
php artisan queue:listen --tries=1

# View live logs
php artisan pail --timeout=0

# Generate IDE helper for better autocomplete
php artisan ide-helper:generate
```

#### Frontend (Vite + TailwindCSS)
```bash
# Development build with hot reloading
npm run dev

# Production build
npm run build

# Install dependencies
npm install
```

#### Testing
```bash
# Run all tests
composer test
# OR
php artisan test

# Run specific test file
php artisan test --filter=ProductTest

# Run tests with coverage
php artisan test --coverage
```

#### Code Quality
```bash
# Format code with Laravel Pint
./vendor/bin/pint

# Fix specific files
./vendor/bin/pint app/Http/Controllers/
```

## Architecture Overview

### Core Models & Relationships
- **User**: Supports role-based access (admin/customer), email verification required
- **Product**: Central entity with categories (shroom, cap, sheet, pet_food, flowers), includes stock management, ratings, and sale flags
- **Cart**: User-specific cart items with quantity tracking
- **Order**: Order management with items and payment receipts (supports guest orders with nullable user_id)
- **OrderItem**: Individual items within orders
- **PaymentReceipt**: Payment processing records

### Key Directory Structure
- `app/Http/Controllers/`: Split into main controllers and `Admin/` namespace
- `app/Models/`: Eloquent models with proper relationships
- `app/Helpers/ImageHelper.php`: Custom helper for product image handling
- `app/Mail/ContactForm.php`: Contact form email handling
- `resources/views/`: Blade templates organized by feature
- `database/migrations/`: Database schema with proper foreign keys
- `database/seeders/`: ProductSeeder for sample data

### Authentication & Authorization
- Email verification required for registered users
- **Guest checkout available** - users can purchase without creating an account
- Role-based middleware (`admin` middleware for admin routes)
- Password reset functionality implemented
- Admin routes grouped under `/admin` prefix with authentication
- Orders support both authenticated users and guest customers

### Image Management
The application uses a centralized image handling system:
- Images stored in `storage/app/public/products/`
- `ImageHelper::getProductImage()` handles image URLs with fallback to placeholder
- Admin can upload/manage product images via dedicated controllers

### Frontend Architecture
- **Vite** for asset bundling with hot reloading
- **TailwindCSS 4.x** for styling
- **Blade templates** with component-based architecture
- **Axios** for AJAX requests
- Product categories have dedicated pages with filtering/sorting

### Route Organization
- Public routes: Product browsing, search, cart, authentication
- Protected routes: User dashboard, cart management
- Admin routes: Product CRUD, user management, payment verification
- Special utility route: `/run-storage-link` for deployment

### Database Configuration
- Default: SQLite for development (`database/database.sqlite`)
- Queue driver: Database-based
- Session storage: Database
- Cache: Database

## Development Patterns

### Product Categories
The application recognizes these product categories:
- `shroom` - Mushroom products
- `cap` - Capsule products  
- `sheet` - Sheet products
- `pet_food` - Pet food products
- `flowers` - Flower products

### Image Storage Pattern
```php
// Store images
$imagePath = $request->file('image')->store('products', 'public');

// Get image URL with fallback
$imageUrl = ImageHelper::getProductImage($product->image);
```

### Admin Access Pattern
All admin functionality is:
1. Grouped under `/admin` prefix
2. Protected by `['auth', 'admin']` middleware
3. Uses dedicated controllers in `Admin/` namespace

### Search & Filtering
Products support:
- Name and description search
- Category-based filtering
- Sorting by price (asc/desc), name, newest
- Pagination (8 items per page for shop, 9 for search)

## Testing Strategy

- Unit tests in `tests/Unit/`
- Feature tests in `tests/Feature/`
- Database testing uses in-memory SQLite
- PHPUnit configuration includes proper environment variables

## Local Environment Setup

1. Copy `.env.example` to `.env`
2. Generate application key: `php artisan key:generate`
3. Create SQLite database: `touch database/database.sqlite`
4. Run migrations: `php artisan migrate --seed`
5. Create storage link: `php artisan storage:link`
6. Install dependencies: `composer install && npm install`
7. Start development: `composer dev`

The application will be available at `http://localhost:8000` with admin panel at `http://localhost:8000/admin`.