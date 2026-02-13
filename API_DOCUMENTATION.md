# Système de Gestion de Stock Intelligent avec Prédiction

## API Documentation

### Base URL
```
http://localhost:8000/api/v1
```

### Authentication
Toutes les routes (sauf login/register) requirent un token `Bearer` incluyen dans la header:
```
Authorization: Bearer <YOUR_TOKEN>
```

---

## Authentication Endpoints

### Register
**POST** `/auth/register`
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123"
}
```

**Response (201):**
```json
{
  "success": true,
  "message": "User registered successfully",
  "data": {
    "user": { ... },
    "token": "..."
  }
}
```

### Login
**POST** `/auth/login`
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

### Logout
**POST** `/auth/logout`

### Get Current User
**GET** `/auth/me`

---

## Categories Endpoints

### List Categories
**GET** `/categories?per_page=15`

### Create Category
**POST** `/categories`
```json
{
  "name": "Electronics",
  "description": "Electronic equipment",
  "parent_id": null
}
```

### Get Category
**GET** `/categories/{id}`

### Update Category
**PUT** `/categories/{id}`

### Delete Category
**DELETE** `/categories/{id}`

---

## Products Endpoints

### List Products
**GET** `/products?per_page=15&category_id=1&search=laptop&low_stock=1`

### Create Product
**POST** `/products`
```json
{
  "name": "Dell Laptop",
  "description": "High performance laptop",
  "barcode": "DELL001",
  "supplier": "Dell",
  "price": 899.99,
  "category_id": 1,
  "min_stock": 5,
  "optimal_stock": 20
}
```

### Get Product
**GET** `/products/{id}`

### Update Product
**PUT** `/products/{id}`

### Delete Product
**DELETE** `/products/{id}`

---

## Stock Movements Endpoints

### List Movements
**GET** `/stock-movements?per_page=15&product_id=1&type=exit&start_date=2026-02-01&end_date=2026-02-28`

### Record Movement
**POST** `/stock-movements`
```json
{
  "product_id": 1,
  "type": "exit",
  "quantity": 5,
  "reason": "sale",
  "reference": "INV-001",
  "date": "2026-02-12"
}
```

### Get Movement
**GET** `/stock-movements/{id}`

---

## Inventories Endpoints

### List Inventories
**GET** `/inventories?status=pending&per_page=15`

### Create Inventory
**POST** `/inventories`
```json
{
  "date": "2026-02-12",
  "description": "Monthly inventory count"
}
```

### Add Items to Inventory
**POST** `/inventories/{inventory}/add-items`
```json
{
  "items": [
    {"product_id": 1, "counted_quantity": 20},
    {"product_id": 2, "counted_quantity": 35}
  ]
}
```

### Complete Inventory
**POST** `/inventories/{inventory}/complete`
```json
{
  "justifications": {
    "1": "Recount confirmed",
    "2": "System error corrected"
  }
}
```

### Archive Inventory
**POST** `/inventories/{inventory}/archive`

---

## Alerts Endpoints

### List Alerts
**GET** `/alerts?type=min_stock&severity=high&unread=1&per_page=15`

### Mark Alert as Read
**POST** `/alerts/{alert}/mark-as-read`

### Mark All Alerts as Read
**POST** `/alerts/mark-all-as-read`

### Get Unread Count
**GET** `/alerts/unread-count`

### Delete Alert
**DELETE** `/alerts/{alert}`

---

## Predictions Endpoints

### List Predictions
**GET** `/predictions?product_id=1&period_days=7&per_page=15`

### Generate Prediction
**POST** `/predictions/products/{product}/generate`
```json
{
  "period_days": 7,
  "method": "moving_average"
}
```

Methods:
- `linear_regression` - Linear regression based
- `moving_average` - 7-day moving average
- `ml_light` - Light machine learning (automatic if > 100 records)

### Get Prediction
**GET** `/predictions/{id}`

---

## Dashboard Endpoints

### Dashboard Stats
**GET** `/dashboard`

Returns:
- `total_products` - Total number of products
- `total_stock_value` - Total stock value
- `low_stock_products` - Count of low stock items
- `total_movements` - Total movements
- `pending_alerts` - Count of unread alerts

### Low Stock Products
**GET** `/dashboard/low-stock-products?per_page=10`

### Recent Movements
**GET** `/dashboard/recent-movements?limit=10`

### Stock by Category
**GET** `/dashboard/stock-by-category`

### Movements Trend
**GET** `/dashboard/movements-trend?days=30`

### Rotation Rate
**GET** `/dashboard/rotation-rate?limit=10`

---

## Response Format

All responses follow this format:

**Success:**
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... }
}
```

**Error:**
```json
{
  "success": false,
  "message": "Error message",
  "errors": { ... }
}
```

---

## HTTP Status Codes

- `200` - OK
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `500` - Server Error

---

## User Roles

- `admin` - Full access
- `manager` - Products and movements
- `observer` - Read-only

---

## Installation & Setup

```bash
# Clone repository
git clone <repository>

# Install dependencies
composer install

# Configure environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Start server
php artisan serve
```

---

## Testing

Run tests:
```bash
php artisan test
```

Run specific test:
```bash
php artisan test tests/Feature/AuthTest.php
```

---

## Technology Stack

- **Laravel 12** - Backend framework
- **Sanctum** - API authentication
- **MySQL** - Database
- **PHPUnit** - Testing framework

