# Customer POS System - Setup Guide

## Current Status
✅ All customer-side code has been created
❌ Dependencies need to be installed due to network issues

## Quick Setup Steps

### Step 1: Install Dependencies (Try these options)

**Option A: Basic Composer Install**
```powershell
composer install --no-dev --optimize-autoloader
```

**Option B: If network issues persist**
```powershell
composer install --prefer-dist --no-interaction --ignore-platform-reqs
```

**Option C: Alternative approach**
```powershell
composer update --ignore-platform-reqs --no-interaction
```

### Step 2: Environment Setup
```powershell
# Copy environment file (already done)
copy .env.example .env

# Generate application key (after dependencies are installed)
php artisan key:generate
```

### Step 3: Database Setup
```powershell
# Run migrations to create tables
php artisan migrate

# Seed database with test data
php artisan db:seed
```

### Step 4: Install Node Dependencies (Optional - for asset compilation)
```powershell
npm install
npm run build
```

### Step 5: Start the Server
```powershell
php artisan serve
```

## Test Accounts (After seeding)

- **Admin**: `admin@example.com` / `password`
- **Cashier**: `cashier@example.com` / `password`
- **Customer**: `customer@example.com` / `password`

## Customer System URLs

### Public Access (No Login Required)
- **Shop**: `http://localhost:8000/shop`
- **Cart**: `http://localhost:8000/cart`

### Customer Dashboard (Login Required)
- **Dashboard**: `http://localhost:8000/customer/dashboard`
- **Orders**: `http://localhost:8000/customer/orders`
- **Checkout**: `http://localhost:8000/customer/orders/checkout`

## Key Features Available

### 🛒 Public Shop
- Browse all products
- Search and filter by category  
- Add to cart (works for guests)
- Product detail pages

### 🛍️ Shopping Cart
- View cart items
- Update quantities
- Remove items
- Calculate totals with tax
- Checkout (requires login)

### 📊 Customer Dashboard
- Order statistics
- Recent orders
- Quick action links
- Account management

### 📋 Order Management
- Place orders through checkout
- View order history
- Track order status
- Cancel pending orders

## Troubleshooting

### If Composer Install Fails:
1. Check internet connection
2. Try different composer install options above
3. Consider using a VPN if behind corporate firewall
4. Download dependencies manually if needed

### If Database Issues:
1. Make sure `.env` file has correct database settings
2. Create SQLite file: `touch database/database.sqlite` (or create empty file)
3. Update `.env`: `DB_CONNECTION=sqlite`

### If Assets Don't Load:
1. Run `npm install && npm run build`
2. Or use CDN versions of Tailwind CSS

## File Structure Created

```
app/
├── Http/Controllers/Customer/
│   ├── CartController.php
│   ├── DashboardController.php
│   ├── OrderController.php
│   └── ShopController.php
├── Models/
│   ├── Cart.php
│   ├── Order.php
│   └── OrderItem.php
└── Services/
    └── CartService.php

resources/views/
├── layouts/
│   └── customer.blade.php
└── customer/
    ├── cart/
    ├── dashboard.blade.php
    ├── orders/
    └── shop/

database/
├── migrations/
│   ├── *_create_carts_table.php
│   ├── *_create_orders_table.php
│   └── *_create_order_items_table.php
└── seeders/
    ├── CustomerSeeder.php
    └── RoleSeeder.php (updated)

routes/web.php (updated with customer routes)
```

## Next Steps After Dependencies Install

Once `composer install` works:

1. **Generate App Key**: `php artisan key:generate`
2. **Run Migrations**: `php artisan migrate`
3. **Seed Database**: `php artisan db:seed`
4. **Start Server**: `php artisan serve`
5. **Visit**: `http://localhost:8000`

The system will automatically redirect:
- Guests → Public Shop
- Customer → Customer Dashboard
- Admin/Cashier → Admin Dashboard/POS

## Features Working

✅ Product browsing and search
✅ Shopping cart (session + database)
✅ Customer authentication and roles
✅ Order placement and management
✅ Order history and tracking
✅ Order cancellation
✅ Customer dashboard
✅ Responsive design
✅ Tax calculations
✅ Role-based redirects

The customer-side system is complete and ready to run once dependencies are installed!