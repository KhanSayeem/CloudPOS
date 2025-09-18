# Customer-Side POS System

This document describes the customer-facing features that have been added to the POS system.

## Overview

The customer-side system allows customers to:
- Browse products in a public shop
- Add products to their shopping cart
- Place orders through a checkout process  
- View their order history
- Cancel pending orders
- Manage their account through a dashboard

## Features Implemented

### ✅ 1. Customer Authentication & Roles
- Added "Customer" role to the existing role system
- Customer users have separate access from Admin/Cashier users
- Test customer account: `customer@example.com` / `password`

### ✅ 2. Shopping Cart System
- Session-based cart for guests
- Persistent cart for logged-in customers
- Add, update, remove, and clear cart functionality
- Automatic quantity updates and pricing calculations
- Tax calculation (10% configurable rate)

### ✅ 3. Product Browsing Interface
- Public product catalog accessible to everyone
- Search functionality (by name, description, or SKU)
- Category filtering
- Product detail views
- Add to cart directly from product listings
- Responsive grid layout for products

### ✅ 4. Order Management System
- Complete order placement workflow
- Order history with status tracking
- Order details view with item breakdown
- Order cancellation for pending orders
- Order status tracking (pending → confirmed → processing → ready → completed)

### ✅ 5. Checkout Process
- Customer information form
- Order review before placement
- Order summary with tax calculation
- Payment method information (pay on pickup/delivery)
- Order confirmation

### ✅ 6. Customer Dashboard
- Order statistics overview
- Recent orders display
- Quick action links
- Account management access

### ✅ 7. Routes & Middleware
- Public shop routes (no authentication required)
- Cart management routes (guest and authenticated)
- Customer-only routes protected by role middleware
- Proper redirect handling based on user roles

### ✅ 8. Order Cancellation
- Customers can cancel orders in 'pending' or 'confirmed' status
- Orders cannot be cancelled once processing begins
- Clear status indicators

## Database Schema

### New Tables Created:
- `carts` - Shopping cart items (for guests and customers)
- `orders` - Customer orders (separate from internal sales)
- `order_items` - Items within customer orders

### Updated Tables:
- `roles` - Added "Customer" role

## Routes

### Public Routes:
- `GET /shop` - Product catalog
- `GET /shop/product/{product}` - Product details
- `GET /cart` - Shopping cart
- `POST /cart/add/{product}` - Add to cart
- `PATCH /cart/update/{cartItemId}` - Update cart item
- `DELETE /cart/remove/{cartItemId}` - Remove from cart
- `DELETE /cart/clear` - Clear cart

### Customer Routes (Auth Required):
- `GET /customer/dashboard` - Customer dashboard
- `GET /customer/orders` - Order history
- `GET /customer/orders/checkout` - Checkout page
- `POST /customer/orders` - Place order
- `GET /customer/orders/{order}` - Order details
- `PATCH /customer/orders/{order}/cancel` - Cancel order

## Models

### New Models:
- `Cart` - Shopping cart management
- `Order` - Customer orders
- `OrderItem` - Order line items
- `CartService` - Business logic for cart operations

### Controllers:
- `Customer\ShopController` - Product browsing
- `Customer\CartController` - Cart management
- `Customer\OrderController` - Order processing
- `Customer\DashboardController` - Customer dashboard

## Views

### Layout:
- `layouts/customer.blade.php` - Customer-facing layout with navigation

### Shop Views:
- `customer/shop/index.blade.php` - Product catalog
- `customer/cart/index.blade.php` - Shopping cart

### Order Views:
- `customer/orders/checkout.blade.php` - Checkout form
- `customer/orders/index.blade.php` - Order history
- `customer/orders/show.blade.php` - Order details

### Dashboard:
- `customer/dashboard.blade.php` - Customer dashboard

## Key Features

### Cart Functionality:
- Works for both guests (session-based) and customers (database-stored)
- Automatic price updates from product catalog
- Quantity validation against stock levels
- Line total calculations with tax

### Order Processing:
- Generates unique order numbers (ORD-XXXXXXXX format)
- Captures customer information separate from user account
- Supports order notes and special instructions
- Automatic stock consideration (though stock reduction not implemented)

### User Experience:
- Mobile-responsive design using Tailwind CSS
- Real-time cart count in navigation
- Status badges with color coding
- Comprehensive order tracking
- Easy navigation between sections

## Security Features:
- Role-based access control
- CSRF protection on all forms
- User can only access their own orders
- Proper authorization checks

## To Run the System:

1. Install dependencies: `composer install --ignore-platform-reqs`
2. Run migrations: `php artisan migrate`
3. Seed database: `php artisan db:seed`
4. Start server: `php artisan serve`

## Test Accounts:
- Admin: `admin@example.com` / `password`
- Cashier: `cashier@example.com` / `password`  
- Customer: `customer@example.com` / `password`

## Default Landing Page:
- Guests are redirected to the public shop (`/shop`)
- Customers are redirected to their dashboard (`/customer/dashboard`)
- Admins/Cashiers use existing dashboard/POS routes

The customer system is now fully functional and ready for use!