@echo off
echo Setting up Product CRUD system...
echo.

echo 1. Running database migration...
cd backend\backend-api
php yii migrate --interactive=0
if %errorlevel% neq 0 (
    echo Error: Migration failed. Please check your PHP and database setup.
    pause
    exit /b 1
)

echo.
echo 2. Seeding products database...
php yii seed-products
if %errorlevel% neq 0 (
    echo Error: Seeding failed. Please check your database connection.
    pause
    exit /b 1
)

echo.
echo 3. Setup completed successfully!
echo.
echo You can now:
echo - Access the admin panel at /admin in your Vue app
echo - Go to the Products tab to manage products
echo - The ProductWidget will continue to show the hardcoded products
echo - Use the admin panel to add/edit/delete products in the database
echo.
pause
