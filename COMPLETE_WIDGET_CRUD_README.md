# Complete Widget CRUD System

This document explains the comprehensive CRUD functionality for managing all widgets in the admin panel.

## Features

### 1. Database Structure
The system includes the following tables:

#### Products Table
- `id` - Primary key
- `name` - Product name (e.g., "Witeg", "IKA")
- `description` - Product description
- `image_path` - Path to product image
- `category` - Product category
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

#### Services Table
- `id` - Primary key
- `title` - Service title
- `description` - Service description
- `image_path` - Path to service image
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

#### About Table
- `id` - Primary key
- `title` - About section title
- `content` - About content
- `ceo_name` - CEO name
- `ceo_title` - CEO title
- `ceo_image` - CEO image path
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

#### Downloads Table
- `id` - Primary key
- `brand_name` - Brand name
- `download_url` - Download URL
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

#### Clients Table
- `id` - Primary key
- `name` - Client name
- `short_name` - Client short name
- `logo_path` - Client logo path
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

#### Contacts Table
- `id` - Primary key
- `office_name` - Office name
- `address` - Office address
- `phone` - Phone number
- `created_at` - Creation timestamp
- `updated_at` - Last update timestamp

### 2. API Endpoints
Each widget has complete REST API endpoints:

#### Products API
- `GET /api/products` - List all products
- `GET /api/products/{id}` - Get specific product
- `POST /api/products/create` - Create new product
- `PUT /api/products/{id}/update` - Update existing product
- `DELETE /api/products/{id}/delete` - Delete product

#### Services API
- `GET /api/services` - List all services
- `GET /api/services/{id}` - Get specific service
- `POST /api/services/create` - Create new service
- `PUT /api/services/{id}/update` - Update existing service
- `DELETE /api/services/{id}/delete` - Delete service

#### About API
- `GET /api/about` - List all about entries
- `GET /api/about/{id}` - Get specific about entry
- `POST /api/about/create` - Create new about entry
- `PUT /api/about/{id}/update` - Update existing about entry
- `DELETE /api/about/{id}/delete` - Delete about entry

#### Downloads API
- `GET /api/downloads` - List all downloads
- `GET /api/downloads/{id}` - Get specific download
- `POST /api/downloads/create` - Create new download
- `PUT /api/downloads/{id}/update` - Update existing download
- `DELETE /api/downloads/{id}/delete` - Delete download

#### Clients API
- `GET /api/clients` - List all clients
- `GET /api/clients/{id}` - Get specific client
- `POST /api/clients/create` - Create new client
- `PUT /api/clients/{id}/update` - Update existing client
- `DELETE /api/clients/{id}/delete` - Delete client

#### Contacts API
- `GET /api/contacts` - List all contacts
- `GET /api/contacts/{id}` - Get specific contact
- `POST /api/contacts/create` - Create new contact
- `PUT /api/contacts/{id}/update` - Update existing contact
- `DELETE /api/contacts/{id}/delete` - Delete contact

### 3. Admin Interface
Access the admin panel at `/admin` and navigate to any of the following tabs:

#### Products Tab
- View all products in a data table
- Add new products with name, description, image path, and category
- Edit existing products
- Delete products
- Image preview functionality

#### Services Tab
- View all services in a data table
- Add new services with title, description, and image path
- Edit existing services
- Delete services
- Image preview functionality

#### About Tab
- View all about entries in a data table
- Add new about entries with title, content, CEO information
- Edit existing about entries
- Delete about entries
- CEO image preview functionality

#### Downloads Tab
- View all download entries in a data table
- Add new downloads with brand name and download URL
- Edit existing downloads
- Delete downloads

#### Clients Tab
- View all clients in a data table
- Add new clients with name, short name, and logo path
- Edit existing clients
- Delete clients
- Logo preview functionality

#### Contacts Tab
- View all contacts in a data table
- Add new contacts with office name, address, and phone
- Edit existing contacts
- Delete contacts

### 4. Frontend Integration
All widgets continue to display hardcoded data as before. The admin panel provides separate database management capabilities for future content updates.

## Setup Instructions

### Quick Setup (Recommended)
Run the automated setup script:
```powershell
# PowerShell (recommended)
.\setup-all-widgets.ps1

# Or individual setup
.\setup-products.ps1
```

### Manual Setup

#### 1. Database Migrations
Run all migrations to create the required tables:
```bash
cd backend/backend-api
php yii migrate
```

#### 2. Seed All Data
Populate the database with all original widget data:
```bash
cd backend/backend-api
php yii seed-products
php yii seed-services
php yii seed-about
php yii seed-downloads
php yii seed-clients
php yii seed-contacts
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

### Managing Content
1. Go to Admin Panel → Select desired tab (Products, Services, About, etc.)
2. Use the interface to:
   - **Add**: Click "Add" button, fill in required fields, click "Save"
   - **Edit**: Click the edit (pencil) icon, modify fields, click "Save"
   - **Delete**: Click the delete (trash) icon, confirm deletion

### Widget Display
- All widgets continue to show hardcoded data as before
- No changes to the frontend display
- Admin panel manages database separately
- Future updates can be made through the admin interface

## File Structure

### Backend Files
- `backend/backend-api/models/` - All widget models
- `backend/backend-api/controllers/` - All API controllers
- `backend/backend-api/migrations/` - Database migrations
- `backend/backend-api/commands/` - Data seeders

### Frontend Files
- `src/views/pages/Admin.vue` - Admin panel with all tabs
- `src/views/pages/components/` - All widget editor components
- `src/service/ApiService.js` - API service with all methods
- `src/components/landing/` - Original widgets (unchanged)

## Available Admin Tabs

1. **Products** - Manage product brands and categories
2. **Services** - Manage service offerings
3. **About** - Manage about content and CEO information
4. **Downloads** - Manage brand download links
5. **Clients** - Manage university and client logos
6. **Contacts** - Manage office locations and contact information

## Troubleshooting

### API Not Responding
1. Ensure the backend server is running
2. Check CORS settings in all controllers
3. Verify the API base URL in ApiService.js

### Database Issues
1. Run migrations: `php yii migrate`
2. Check database connection in config/db.php
3. Verify table structures match the migrations

### Frontend Issues
1. Check browser console for errors
2. Verify API service is properly imported
3. Ensure all required PrimeVue components are available

## Data Seeding

The system includes comprehensive seeders that populate the database with all original widget data:

- **Products**: 47 product brands with categories and image paths
- **Services**: 12 service offerings with descriptions and images
- **About**: Complete about content with CEO information
- **Downloads**: 40 brand download entries
- **Clients**: 20 university clients with logos
- **Contacts**: 5 office locations with contact details

All seeders can be run individually or together using the setup script.
