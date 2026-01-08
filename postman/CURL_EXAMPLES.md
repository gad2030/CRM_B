# API Testing with cURL

Quick reference for testing API endpoints using cURL commands.

## Setup

Replace these variables in commands below:
- `YOUR_TOKEN` - Your authentication token from login/register
- `CATEGORY_ID` - Created category ID
- `PRODUCT_ID` - Created product ID
- `PRICE_ID` - Created price ID

---

## Authentication

### Register
```bash
curl -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### Get Current User
```bash
curl -X GET http://localhost:8000/api/v1/me \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## Categories

### List All Categories
```bash
curl -X GET http://localhost:8000/api/v1/categories \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Create Category
```bash
curl -X POST http://localhost:8000/api/v1/categories \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Electronics",
    "description": "Electronic devices and accessories"
  }'
```

### Get Single Category
```bash
curl -X GET http://localhost:8000/api/v1/categories/CATEGORY_ID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Update Category
```bash
curl -X PUT http://localhost:8000/api/v1/categories/CATEGORY_ID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Electronics & Gadgets",
    "description": "Updated description"
  }'
```

### Delete Category
```bash
curl -X DELETE http://localhost:8000/api/v1/categories/CATEGORY_ID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## Products

### List All Products
```bash
curl -X GET http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### List Active Products
```bash
curl -X GET "http://localhost:8000/api/v1/products?active_only=1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### List Products by Category
```bash
curl -X GET "http://localhost:8000/api/v1/products?category_id=CATEGORY_ID" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Create Product
```bash
curl -X POST http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Laptop HP EliteBook",
    "sku": "HP-ELITE-001",
    "description": "High-performance business laptop",
    "price": 1299.99,
    "cost_price": 899.99,
    "category_id": CATEGORY_ID,
    "is_active": true
  }'
```

### Get Single Product
```bash
curl -X GET http://localhost:8000/api/v1/products/PRODUCT_ID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Update Product
```bash
curl -X PUT http://localhost:8000/api/v1/products/PRODUCT_ID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Laptop HP EliteBook 840 G8",
    "sku": "HP-ELITE-001",
    "description": "Updated description",
    "price": 1399.99,
    "cost_price": 899.99,
    "category_id": CATEGORY_ID,
    "is_active": true
  }'
```

### Delete Product
```bash
curl -X DELETE http://localhost:8000/api/v1/products/PRODUCT_ID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## Product Prices

### List All Product Prices
```bash
curl -X GET http://localhost:8000/api/v1/product-prices \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Get Price History for Product
```bash
curl -X GET "http://localhost:8000/api/v1/product-prices?product_id=PRODUCT_ID" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Create Product Price
```bash
curl -X POST http://localhost:8000/api/v1/product-prices \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "product_id": PRODUCT_ID,
    "price": 1499.99,
    "starts_at": "2026-01-08",
    "ends_at": "2026-12-31"
  }'
```

### Get Single Product Price
```bash
curl -X GET http://localhost:8000/api/v1/product-prices/PRICE_ID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### Update Product Price
```bash
curl -X PUT http://localhost:8000/api/v1/product-prices/PRICE_ID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "product_id": PRODUCT_ID,
    "price": 1599.99,
    "starts_at": "2026-01-08",
    "ends_at": "2026-12-31"
  }'
```

### Delete Product Price
```bash
curl -X DELETE http://localhost:8000/api/v1/product-prices/PRICE_ID \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

---

## Testing Script

Save this as `test-api.sh`:

```bash
#!/bin/bash

BASE_URL="http://localhost:8000/api/v1"

# 1. Register
echo "1. Registering user..."
REGISTER_RESPONSE=$(curl -s -X POST $BASE_URL/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test User",
    "email": "test'$(date +%s)'@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }')

TOKEN=$(echo $REGISTER_RESPONSE | grep -o '"token":"[^"]*' | cut -d'"' -f4)
echo "Token: $TOKEN"

# 2. Create Category
echo "\n2. Creating category..."
CATEGORY_RESPONSE=$(curl -s -X POST $BASE_URL/categories \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Electronics",
    "description": "Electronic devices"
  }')

CATEGORY_ID=$(echo $CATEGORY_RESPONSE | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
echo "Category ID: $CATEGORY_ID"

# 3. Create Product
echo "\n3. Creating product..."
PRODUCT_RESPONSE=$(curl -s -X POST $BASE_URL/products \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"name\": \"Laptop HP\",
    \"sku\": \"HP-001\",
    \"description\": \"Business laptop\",
    \"price\": 1299.99,
    \"cost_price\": 899.99,
    \"category_id\": $CATEGORY_ID,
    \"is_active\": true
  }")

PRODUCT_ID=$(echo $PRODUCT_RESPONSE | grep -o '"id":[0-9]*' | head -1 | cut -d':' -f2)
echo "Product ID: $PRODUCT_ID"

# 4. List Products
echo "\n4. Listing products..."
curl -s -X GET $BASE_URL/products \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json" | json_pp

echo "\nTest completed successfully!"
```

Run with:
```bash
chmod +x test-api.sh
./test-api.sh
```

---

## Pretty Print JSON (Optional)

For better readability, pipe output through `jq`:

```bash
curl -X GET http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" | jq
```

Or use Python:
```bash
curl -X GET http://localhost:8000/api/v1/products \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json" | python -m json.tool
```
