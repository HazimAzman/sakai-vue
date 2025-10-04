@echo off
echo Setting up Complete Widget CRUD System...
echo.

echo 1. Running database migrations...
cd backend\backend-api

REM Try to find PHP in common locations
set PHP_PATH=
if exist "C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe" set PHP_PATH=C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe
if exist "C:\laragon\bin\php\php-8.2.12-Win32-vs16-x64\php.exe" set PHP_PATH=C:\laragon\bin\php\php-8.2.12-Win32-vs16-x64\php.exe
if exist "C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe" set PHP_PATH=C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe
if exist "C:\laragon\bin\php\php-8.0.30-Win32-vs16-x64\php.exe" set PHP_PATH=C:\laragon\bin\php\php-8.0.30-Win32-vs16-x64\php.exe
if exist "C:\laragon\bin\php\php-7.4.33-Win32-vc15-x64\php.exe" set PHP_PATH=C:\laragon\bin\php\php-7.4.33-Win32-vc15-x64\php.exe
if exist "C:\laragon\bin\php\php-7.4.1-Win32-vc15-x64\php.exe" set PHP_PATH=C:\laragon\bin\php\php-7.4.1-Win32-vc15-x64\php.exe
if exist "C:\xampp\php\php.exe" set PHP_PATH=C:\xampp\php\php.exe
if exist "C:\wamp64\bin\php\php8.1.0\php.exe" set PHP_PATH=C:\wamp64\bin\php\php8.1.0\php.exe

if "%PHP_PATH%"=="" (
    echo Error: PHP not found. Please check your Laragon installation.
    echo.
    echo Please try one of these solutions:
    echo 1. Make sure Laragon is running
    echo 2. Check if PHP is installed in C:\laragon\bin\php\
    echo 3. Add PHP to your system PATH
    echo 4. Run the setup manually using the commands below
    echo.
    pause
    exit /b 1
)

echo Using PHP at: %PHP_PATH%
echo.

REM Run migrations
echo Running migrations...
"%PHP_PATH%" yii migrate --interactive=0
if %errorlevel% neq 0 (
    echo Error: Migration failed. Please check your database connection.
    pause
    exit /b 1
)

echo.
echo 2. Seeding all widget data...
echo.

REM Seed all data
echo Seeding Products...
"%PHP_PATH%" yii seed-products
if %errorlevel% neq 0 echo Warning: Product seeding failed

echo Seeding Services...
"%PHP_PATH%" yii seed-services
if %errorlevel% neq 0 echo Warning: Service seeding failed

echo Seeding About...
"%PHP_PATH%" yii seed-about
if %errorlevel% neq 0 echo Warning: About seeding failed

echo Seeding Downloads...
"%PHP_PATH%" yii seed-downloads
if %errorlevel% neq 0 echo Warning: Download seeding failed

echo Seeding Clients...
"%PHP_PATH%" yii seed-clients
if %errorlevel% neq 0 echo Warning: Client seeding failed

echo Seeding Contacts...
"%PHP_PATH%" yii seed-contacts
if %errorlevel% neq 0 echo Warning: Contact seeding failed

echo.
echo 3. Setup completed successfully!
echo.
echo You can now:
echo - Access the admin panel at /admin in your Vue app
echo - Manage all widgets: Products, Services, About, Downloads, Clients, Contacts
echo - All widgets continue to show hardcoded data as before
echo - Use the admin panel to add/edit/delete entries in the database
echo.
echo Available Admin Tabs:
echo - Products: Manage product brands and categories
echo - Services: Manage service offerings
echo - About: Manage about content and CEO information
echo - Downloads: Manage brand download links
echo - Clients: Manage university and client logos
echo - Contacts: Manage office locations and contact information
echo.
pause
