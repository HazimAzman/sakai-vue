@echo off
echo Setting up Institutes...

REM Find PHP executable
set PHP_PATH=""
if exist "C:\laragon\bin\php\php-7.4.1-Win32-vc15-x64" (
    set PHP_PATH="C:\laragon\bin\php\php-7.4.1-Win32-vc15-x64"
    
) else if exist "C:\laragon\bin\php\php-8.2.12-Win32-vs16-x64\php.exe" (
    set PHP_PATH="C:\laragon\bin\php\php-8.2.12-Win32-vs16-x64\php.exe"
) else if exist "C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe" (
    set PHP_PATH="C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe"
) else (
    echo PHP not found in common Laragon locations. Please check your PHP installation.
    pause
    exit /b 1
)

echo Using PHP: %PHP_PATH%

REM Change to backend directory
cd /d "%~dp0backend\backend-api"

REM Run migration
echo Running migration...
%PHP_PATH% yii migrate --interactive=0

REM Seed institutes data
echo Seeding institutes data...
%PHP_PATH% yii seed-institutes

echo Institutes setup completed!
pause
