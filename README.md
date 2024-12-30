# Pre-Order Management Package

## Overview
A Laravel package designed to streamline the pre-order process for online shops, enabling easy management of pre-orders and enhancing customer experience.

## Requirements
- **Laravel**: 11
- **PHP**: 8.2.0 or higher

## Features
- **Pre-Order Management**: Create, update, delete, and view pre-orders.
- **Role-Based Access Control**: Admins and managers have specific permissions.
- **Rate Limiting**: Protects endpoints from excessive requests.
- **reCAPTCHA Support**: Ensures security for public routes.
- **Event Triggers**: Dispatches events on pre-order creation for email notifications.

## Installation
Install via Composer:
   ```bash
   composer require dev_mamun/shop-pre-order
   ```

## Usage
### Routes
- **Public Routes**:
  - `GET /api/products` - View available products.
  - `POST /api/pre-orders` - Submit pre-orders with rate limiting and reCAPTCHA protection.
- **Admin Routes**:
  - `GET /api/pre-orders/{id}` - View details of a specific pre-order.
  - `PUT /api/pre-orders/{id}` - Update an existing pre-order.
  - `DELETE /api/pre-orders/{id}` - Delete a specific pre-order.
  - These routes are protected by authentication and admin role middleware.
- **Manager Routes**:
  - `GET /api/pre-orders` - View a list of pre-orders.
  - Limited permissions compared to admins.

## Testing
To run tests:
1. Install dependencies:
   ```bash
   composer install
   ```
2. Execute tests:
   ```bash
   ./vendor/bin/phpunit
   ```

## Support
For issues or contributions, visit the repository and submit a pull request or issue.

---
**Author**: Md. Al Mamun
**License**: MIT

