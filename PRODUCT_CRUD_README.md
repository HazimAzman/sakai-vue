# Product CRUD Admin Panel

This document explains the new CRUD functionality for managing products in the admin panel.

## Features

### 1. Database Structure
The products table has been updated with the following fields:
- `id` - Primary key
- `name` - Product name (e.g., "Witeg", "IKA")
- `description` - Product description
- `image_path` - Path to product image (e.g., "/images/product/witeg-150.png")
- `category` - Product category (e.g., "Laboratory Instrument")
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 2. API Endpoints
The following REST API endpoints are available:

- `GET /api/products` - List all products
- `GET /api/products/{id}` - Get specific product
- `POST /api/products/create` - Create new product
- `PUT /api/products/{id}/update` - Update existing product
- `DELETE /api/products/{id}/delete` - Delete product

### 3. Admin Interface
Access the admin panel at `/admin` and navigate to the "Products" tab to:
- View all products in a data table
- Add new products
- Edit existing products
- Delete products
- Upload and manage product images

### 4. Frontend Integration
The ProductWidget component continues to display hardcoded products as before. The admin panel provides CRUD functionality for managing products in the database separately.

## Setup Instructions

### Quick Setup (Recommended)
Run the automated setup script:
```powershell
# PowerShell (recommended)
.\setup-products.ps1

# Or Batch file
.\setup-products.bat
```

### Manual Setup

#### 1. Database Migration
Run the migration to update the products table:
```bash
cd backend/backend-api
php yii migrate
```

#### 2. Seed Sample Data
Populate the database with all original products:
```bash
cd backend/backend-api
php yii seed-products
```

#### 3. Start Backend Server
Make sure your backend server is running:
```bash
cd backend/backend-api
php yii serve
```

#### 4. Start Frontend Development Server
```bash
npm run dev
```

## Usage

### Adding a New Product
1. Go to Admin Panel → Products tab
2. Click "Add Product"
3. Fill in the required fields:
   - Product Name
   - Category
   - Description
   - Image Path (e.g., "/images/product/brand-150.png")
4. Click "Save"

### Editing a Product
1. In the Products table, click the edit (pencil) icon
2. Modify the fields as needed
3. Click "Save"

### Deleting a Product
1. In the Products table, click the delete (trash) icon
2. Confirm the deletion in the dialog

## File Structure

### Backend Files
- `backend/backend-api/models/Product.php` - Product model
- `backend/backend-api/controllers/ProductController.php` - API controller
- `backend/backend-api/migrations/m240101_000001_update_products_table.php` - Database migration
- `backend/backend-api/commands/SeedProductsCommand.php` - Sample data seeder

### Frontend Files
- `src/views/pages/Admin.vue` - Admin panel with Products tab
- `src/views/pages/components/ProductEditor.vue` - Product management component
- `src/components/landing/ProductWidget.vue` - Updated to use API data
- `src/service/ApiService.js` - API service with product methods

## Troubleshooting

### API Not Responding
1. Ensure the backend server is running
2. Check CORS settings in ProductController.php
3. Verify the API base URL in ApiService.js

### Database Issues
1. Run migrations: `php yii migrate`
2. Check database connection in config/db.php
3. Verify table structure matches the migration

### Frontend Issues
1. Check browser console for errors
2. Verify API service is properly imported
3. Ensure all required PrimeVue components are available
