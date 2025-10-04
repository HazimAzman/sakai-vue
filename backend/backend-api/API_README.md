# Sakai Vue API Backend

This is the Yii2-based API backend for the Sakai Vue application.

## Setup

1. **Database Configuration**: The API is configured to use MySQL database `sakai_vue_api`
2. **PHP Version**: Requires PHP 7.4+
3. **Dependencies**: Installed via Composer

## API Endpoints

### General Endpoints
- `GET /api` - API information
- `GET /api/health` - Health check

### Products Endpoints
- `GET /api/products` - List all products
- `GET /api/products/{id}` - Get specific product
- `POST /api/products/create` - Create new product
- `PUT /api/products/{id}/update` - Update product
- `DELETE /api/products/{id}/delete` - Delete product

## CORS Configuration

The API is configured to accept requests from:
- http://localhost:3000
- http://127.0.0.1:3000
- http://localhost:5173
- http://127.0.0.1:5173

## Database Schema

### Products Table
- `id` (Primary Key)
- `name` (String, 255 chars)
- `description` (Text)
- `price` (Decimal, 10,2)
- `created_at` (Timestamp)
- `updated_at` (Timestamp)

## Testing

Run the test script to verify API functionality:
```bash
php test_api.php
```

## Sample Data

The database comes pre-populated with sample products for testing.

## Development

To add new API endpoints:
1. Create controller in `controllers/` directory
2. Add routes to `config/web.php`
3. Implement CORS and content negotiation behaviors
4. Test endpoints using the provided test script
