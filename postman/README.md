# Laravel CRM API v1 - Postman Testing Guide

Complete API documentation for testing the Laravel CRM Product Management Module endpoints in Postman.

## 🚀 Quick Start

### Base URL
```
http://localhost:8000/api/v1
```

### Import Collection
1. Open Postman
2. Click **Import** button
3. Select `CRM_API_Collection.postman_collection.json`
4. Collection will be imported with all endpoints and auto-scripts

---

## 📋 Table of Contents
- [Authentication](#authentication)
- [Categories API](#categories-api)
- [Products API](#products-api)
- [Product Prices API](#product-prices-api)
- [Testing Workflow](#testing-workflow)

---

## 🔐 Authentication

All endpoints except `/register` and `/login` require Bearer token authentication.

### Register New User
```http
POST /api/v1/register
Content-Type: application/json

{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response (201):**
```json
{
    "success": true,
    "message": "User registered successfully",
    "data": {
        "user": { ... },
        "token": "1|abc123..."
    }
}
```

### Login
```http
POST /api/v1/login
Content-Type: application/json

{
    "email": "test@example.com",
    "password": "password123"
}
```

**Response (200):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": { ... },
        "token": "2|xyz789..."
    }
}
```

> 💡 **Token Auto-Save**: The collection automatically saves the token to the `{{auth_token}}` variable.

### Get Current User
```http
GET /api/v1/me
Authorization: Bearer {{auth_token}}
```

### Logout
```http
POST /api/v1/logout
Authorization: Bearer {{auth_token}}
```

---

## 📁 Categories API

### Get All Categories
```http
GET /api/v1/categories
Authorization: Bearer {{auth_token}}
```

**Query Parameters:**
- `with_products` (optional): Include products in the response

**Example:**
```http
GET /api/v1/categories?with_products=1
```

### Create Category
```http
POST /api/v1/categories
Authorization: Bearer {{auth_token}}
Content-Type: application/json

{
    "name": "Electronics",
    "description": "Electronic devices and accessories"
}
```

**Validation Rules:**
- `name` - Required, string, max 255 characters, unique
- `description` - Optional, string

**Response (201):**
```json
{
    "success": true,
    "message": "Category created successfully",
    "data": {
        "id": 1,
        "name": "Electronics",
        "description": "Electronic devices and accessories",
        "owner_id": 1,
        "created_at": "2026-01-08T05:00:00.000000Z",
        "updated_at": "2026-01-08T05:00:00.000000Z"
    }
}
```

> 💡 **ID Auto-Save**: Category ID is automatically saved to `{{category_id}}` variable.

### Get Single Category
```http
GET /api/v1/categories/{id}
Authorization: Bearer {{auth_token}}
```

Returns category with its products.

### Update Category
```http
PUT /api/v1/categories/{id}
Authorization: Bearer {{auth_token}}
Content-Type: application/json

{
    "name": "Electronics & Gadgets",
    "description": "Electronic devices, gadgets and accessories"
}
```

### Delete Category
```http
DELETE /api/v1/categories/{id}
Authorization: Bearer {{auth_token}}
```

---

## 📦 Products API

### Get All Products
```http
GET /api/v1/products
Authorization: Bearer {{auth_token}}
```

**Query Parameters:**
- `active_only` - Get only active products
- `category_id` - Filter by category
- `with_prices` - Include price history

**Examples:**
```http
GET /api/v1/products?active_only=1
GET /api/v1/products?category_id=1
GET /api/v1/products?with_prices=1
```

### Create Product
```http
POST /api/v1/products
Authorization: Bearer {{auth_token}}
Content-Type: application/json

{
    "name": "Laptop HP EliteBook",
    "sku": "HP-ELITE-001",
    "description": "High-performance business laptop",
    "price": 1299.99,
    "cost_price": 899.99,
    "category_id": 1,
    "is_active": true
}
```

**Validation Rules:**
- `name` - Required, string, max 255 characters
- `sku` - Optional, string, max 100 characters, unique
- `description` - Optional, string
- `price` - Required, numeric, min 0, max 999999999999.99
- `cost_price` - Optional, numeric, min 0, max 999999999999.99
- `category_id` - Optional, must exist in categories table
- `is_active` - Optional, boolean (default: true)

**Response (201):**
```json
{
    "success": true,
    "message": "Product created successfully",
    "data": {
        "id": 1,
        "name": "Laptop HP EliteBook",
        "sku": "HP-ELITE-001",
        "description": "High-performance business laptop",
        "price": "1299.99",
        "cost_price": "899.99",
        "category_id": 1,
        "owner_id": 1,
        "is_active": true,
        "employer_id": null,
        "created_at": "2026-01-08T05:00:00.000000Z",
        "updated_at": "2026-01-08T05:00:00.000000Z",
        "category": {
            "id": 1,
            "name": "Electronics"
        }
    }
}
```

> 💡 **ID Auto-Save**: Product ID is automatically saved to `{{product_id}}` variable.

### Get Single Product
```http
GET /api/v1/products/{id}
Authorization: Bearer {{auth_token}}
```

Returns product with category and price history.

### Update Product
```http
PUT /api/v1/products/{id}
Authorization: Bearer {{auth_token}}
Content-Type: application/json

{
    "name": "Laptop HP EliteBook 840 G8",
    "sku": "HP-ELITE-001",
    "description": "High-performance business laptop with Intel Core i7",
    "price": 1399.99,
    "cost_price": 899.99,
    "category_id": 1,
    "is_active": true
}
```

### Delete Product
```http
DELETE /api/v1/products/{id}
Authorization: Bearer {{auth_token}}
```

> ⚠️ **Soft Delete**: Products are soft-deleted, not permanently removed.

---

## 💰 Product Prices API

Track price changes over time for products.

### Get All Product Prices
```http
GET /api/v1/product-prices
Authorization: Bearer {{auth_token}}
```

**Query Parameters:**
- `product_id` - Get price history for specific product

**Example:**
```http
GET /api/v1/product-prices?product_id=1
```

### Create Product Price
```http
POST /api/v1/product-prices
Authorization: Bearer {{auth_token}}
Content-Type: application/json

{
    "product_id": 1,
    "price": 1499.99,
    "starts_at": "2026-01-08",
    "ends_at": "2026-12-31"
}
```

**Validation Rules:**
- `product_id` - Required, must exist in products table
- `price` - Required, numeric, min 0, max 999999999999.99
- `starts_at` - Required, date format
- `ends_at` - Optional, date format, must be after starts_at

**Response (201):**
```json
{
    "success": true,
    "message": "Product price created successfully",
    "data": {
        "id": 1,
        "product_id": 1,
        "price": "1499.99",
        "starts_at": "2026-01-08T00:00:00.000000Z",
        "ends_at": "2026-12-31T00:00:00.000000Z",
        "created_at": "2026-01-08T05:00:00.000000Z",
        "updated_at": "2026-01-08T05:00:00.000000Z",
        "product": {
            "id": 1,
            "name": "Laptop HP EliteBook"
        }
    }
}
```

> 💡 **ID Auto-Save**: Price ID is automatically saved to `{{price_id}}` variable.

### Get Single Product Price
```http
GET /api/v1/product-prices/{id}
Authorization: Bearer {{auth_token}}
```

### Update Product Price
```http
PUT /api/v1/product-prices/{id}
Authorization: Bearer {{auth_token}}
Content-Type: application/json

{
    "product_id": 1,
    "price": 1599.99,
    "starts_at": "2026-01-08",
    "ends_at": "2026-12-31"
}
```

### Delete Product Price
```http
DELETE /api/v1/product-prices/{id}
Authorization: Bearer {{auth_token}}
```

---

## 🧪 Testing Workflow

### Recommended Testing Sequence

1. **Authentication Setup**
   ```
   1. Register → Auto-saves token
   2. (Optional) Login → Auto-saves token
   3. Get Current User → Verify authentication
   ```

2. **Create Category**
   ```
   POST /categories → Auto-saves category_id
   GET /categories → Verify creation
   ```

3. **Create Product**
   ```
   POST /products → Auto-saves product_id
   (Uses {{category_id}} from step 2)
   GET /products → Verify creation
   ```

4. **Create Price History**
   ```
   POST /product-prices → Auto-saves price_id
   (Uses {{product_id}} from step 3)
   GET /product-prices?product_id={{product_id}}
   ```

5. **Update Operations**
   ```
   PUT /products/{{product_id}}
   PUT /categories/{{category_id}}
   PUT /product-prices/{{price_id}}
   ```

6. **Filter & Query Tests**
   ```
   GET /products?active_only=1
   GET /products?category_id={{category_id}}
   GET /products?with_prices=1
   GET /categories?with_products=1
   ```

7. **Delete Operations** (Test in reverse order)
   ```
   DELETE /product-prices/{{price_id}}
   DELETE /products/{{product_id}}
   DELETE /categories/{{category_id}}
   ```

---

## 📝 Collection Variables

The Postman collection uses these variables:

| Variable | Description | Auto-Set |
|----------|-------------|----------|
| `base_url` | API base URL | Manual |
| `auth_token` | Bearer token | ✅ Login/Register |
| `category_id` | Last created category | ✅ Create Category |
| `product_id` | Last created product | ✅ Create Product |
| `price_id` | Last created price | ✅ Create Price |

---

## ⚙️ Environment Setup

### Option 1: Use Collection Variables (Recommended)
Variables are pre-configured in the collection.

### Option 2: Create Environment
1. Create new environment in Postman
2. Add variables:
   - `base_url`: `http://localhost:8000/api/v1`
   - `auth_token`: (leave empty, will auto-fill)

---

## 🔍 Response Format

All API responses follow this format:

**Success Response:**
```json
{
    "success": true,
    "message": "Operation successful",
    "data": { ... }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        "field": ["Validation error"]
    }
}
```

---

## 🛡️ Authorization & Policies

Some endpoints check authorization policies:
- **Update**: Only owner can update
- **Delete**: Only owner can delete

If unauthorized, you'll receive:
```json
{
    "success": false,
    "message": "This action is unauthorized.",
    "data": null
}
```

---

## 📌 Tips for Testing

1. **Always authenticate first** - Run Register/Login before other requests
2. **Follow the sequence** - Create categories before products
3. **Check auto-saved IDs** - Variables are automatically populated
4. **Test filters** - Use query parameters to test different scenarios
5. **Validate responses** - Check that data matches your input
6. **Test edge cases** - Try invalid data, duplicate SKUs, etc.

---

## 🐛 Common Issues

### 401 Unauthorized
- Token expired or invalid
- Re-login to get new token

### 422 Validation Error
- Check request body matches validation rules
- Ensure required fields are present

### 404 Not Found
- Check ID exists in database
- May have been deleted (soft delete)

### 403 Forbidden
- Authorization policy failed
- You don't own the resource

---

## 📊 Sample Test Data

```json
// Category
{
    "name": "Electronics",
    "description": "Electronic devices and accessories"
}

// Product
{
    "name": "Laptop HP EliteBook 840 G8",
    "sku": "HP-ELITE-840-G8",
    "description": "14-inch business laptop with Intel Core i7",
    "price": 1299.99,
    "cost_price": 899.99,
    "category_id": 1,
    "is_active": true
}

// Product Price
{
    "product_id": 1,
    "price": 1499.99,
    "starts_at": "2026-01-08",
    "ends_at": "2026-12-31"
}
```

---

## 🎯 Complete Test Checklist

- [ ] Register new user
- [ ] Login with credentials
- [ ] Get current user details
- [ ] Create category
- [ ] Get all categories
- [ ] Get categories with products
- [ ] Get single category
- [ ] Update category
- [ ] Create product
- [ ] Get all products
- [ ] Get active products only
- [ ] Get products by category
- [ ] Get products with prices
- [ ] Get single product
- [ ] Update product
- [ ] Create product price
- [ ] Get all product prices
- [ ] Get price history for product
- [ ] Get single product price
- [ ] Update product price
- [ ] Delete product price
- [ ] Delete product
- [ ] Delete category
- [ ] Logout

---

## 📞 Support

For issues or questions, check:
- Laravel logs: `storage/logs/laravel.log`
- Database migrations: `database/migrations/`
- Model relationships: `app/Models/`

---

**Happy Testing! 🚀**
