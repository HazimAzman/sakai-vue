# Manual Setup Guide

If the automated setup scripts don't work, follow this manual setup guide.

## Prerequisites

1. **Laragon must be running**
2. **Database must be accessible**
3. **PHP must be available**

## Step 1: Find Your PHP Path

Open Command Prompt or PowerShell and navigate to your project directory:

```cmd
cd C:\laragon\www\sakai-vue
```

Try to find PHP in your Laragon installation:

```cmd
dir C:\laragon\bin\php\ /s /b | findstr php.exe
```

This will show you all PHP installations. Look for something like:
- `C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe`
- `C:\laragon\bin\php\php-8.2.12-Win32-vs16-x64\php.exe`

## Step 2: Run Migrations

Navigate to the backend directory and run migrations:

```cmd
cd backend\backend-api
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe yii migrate --interactive=0
```

Replace the PHP path with your actual PHP path.

## Step 3: Seed All Data

Run each seeder command:

```cmd
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe yii seed-products
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe yii seed-services
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe yii seed-about
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe yii seed-downloads
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe yii seed-clients
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe yii seed-contacts
```

## Step 4: Start Backend Server

Start the Yii2 backend server:

```cmd
C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe yii serve
```

## Step 5: Start Frontend Server

Open a new terminal and start the Vue.js frontend:

```cmd
cd C:\laragon\www\sakai-vue
npm run dev
```

## Step 6: Access Admin Panel

1. Open your browser and go to your Vue app (usually `http://localhost:5173`)
2. Navigate to `/admin`
3. You should see all the new tabs: Products, Services, About, Downloads, Clients, Contacts

## Alternative: Using Laragon's Terminal

If you have Laragon running:

1. Right-click on Laragon icon in system tray
2. Select "Terminal"
3. Navigate to your project: `cd C:\laragon\www\sakai-vue`
4. Follow the steps above

## Troubleshooting

### PHP Not Found
- Make sure Laragon is running
- Check if PHP is installed in `C:\laragon\bin\php\`
- Try different PHP versions in the directory

### Database Connection Issues
- Make sure MySQL is running in Laragon
- Check database credentials in `backend/backend-api/config/db.php`
- Verify database exists

### Migration Errors
- Check database permissions
- Ensure database user has CREATE TABLE privileges
- Check for existing tables that might conflict

### Seeder Errors
- Run migrations first
- Check if tables exist
- Verify data format in seeder files

## Quick Test

After setup, test the API by visiting:
- `http://localhost/sakai-vue/backend/backend-api/web/api/products`
- `http://localhost/sakai-vue/backend/backend-api/web/api/services`
- `http://localhost/sakai-vue/backend/backend-api/web/api/about`
- etc.

You should see JSON data returned.

## Success Indicators

✅ **Setup Complete When:**
- All migrations run without errors
- All seeders complete successfully
- Backend server starts without errors
- Frontend loads without errors
- Admin panel shows all 6 new tabs
- API endpoints return data

If you encounter any issues, check the error messages and refer to the troubleshooting section above.
