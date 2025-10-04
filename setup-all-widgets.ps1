Write-Host "Setting up Complete Widget CRUD System..." -ForegroundColor Green
Write-Host ""

Write-Host "1. Running database migrations..." -ForegroundColor Yellow
Set-Location "backend\backend-api"

try {
    # Try to find PHP in common locations
    $phpPath = $null
    
    # Check if PHP is in PATH
    try {
        $phpPath = Get-Command php -ErrorAction Stop
        $phpPath = $phpPath.Source
    } catch {
    # Try common Laragon paths
    $commonPaths = @(
        "C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe",
        "C:\laragon\bin\php\php-8.2.12-Win32-vs16-x64\php.exe",
        "C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe",
        "C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe",
        "C:\laragon\bin\php\php-8.0.30-Win32-vs16-x64\php.exe",
        "C:\laragon\bin\php\php-7.4.33-Win32-vc15-x64\php.exe",
        "C:\xampp\php\php.exe",
        "C:\wamp64\bin\php\php8.1.0\php.exe",
        "C:\wamp64\bin\php\php8.0.0\php.exe"
    )
    
    # Also try to find PHP in Laragon's current directory
    $laragonPhpPaths = Get-ChildItem "C:\laragon\bin\php\" -Recurse -Name "php.exe" -ErrorAction SilentlyContinue
    foreach ($path in $laragonPhpPaths) {
        $fullPath = "C:\laragon\bin\php\$path"
        if (Test-Path $fullPath) {
            $commonPaths += $fullPath
        }
    }
        
        foreach ($path in $commonPaths) {
            if (Test-Path $path) {
                $phpPath = $path
                break
            }
        }
    }
    
    if (-not $phpPath) {
        Write-Host "Error: PHP not found. Please ensure PHP is installed and in your PATH, or update this script with the correct PHP path." -ForegroundColor Red
        Read-Host "Press Enter to exit"
        exit 1
    }
    
    Write-Host "Using PHP at: $phpPath" -ForegroundColor Cyan
    
    # Run all migrations
    Write-Host "Running migrations..." -ForegroundColor Yellow
    & $phpPath yii migrate --interactive=0
    if ($LASTEXITCODE -ne 0) {
        throw "Migration failed"
    }
    
    Write-Host ""
    Write-Host "2. Seeding all widget data..." -ForegroundColor Yellow
    
    # Seed Products
    Write-Host "Seeding Products..." -ForegroundColor Cyan
    & $phpPath yii seed-products
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Warning: Product seeding failed" -ForegroundColor Yellow
    }
    
    # Seed Services
    Write-Host "Seeding Services..." -ForegroundColor Cyan
    & $phpPath yii seed-services
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Warning: Service seeding failed" -ForegroundColor Yellow
    }
    
    # Seed About
    Write-Host "Seeding About..." -ForegroundColor Cyan
    & $phpPath yii seed-about
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Warning: About seeding failed" -ForegroundColor Yellow
    }
    
    # Seed Downloads
    Write-Host "Seeding Downloads..." -ForegroundColor Cyan
    & $phpPath yii seed-downloads
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Warning: Download seeding failed" -ForegroundColor Yellow
    }
    
    # Seed Clients
    Write-Host "Seeding Clients..." -ForegroundColor Cyan
    & $phpPath yii seed-clients
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Warning: Client seeding failed" -ForegroundColor Yellow
    }
    
    # Seed Contacts
    Write-Host "Seeding Contacts..." -ForegroundColor Cyan
    & $phpPath yii seed-contacts
    if ($LASTEXITCODE -ne 0) {
        Write-Host "Warning: Contact seeding failed" -ForegroundColor Yellow
    }
    
    Write-Host ""
    Write-Host "3. Setup completed successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "You can now:" -ForegroundColor Cyan
    Write-Host "- Access the admin panel at /admin in your Vue app" -ForegroundColor White
    Write-Host "- Manage all widgets: Products, Services, About, Downloads, Clients, Contacts" -ForegroundColor White
    Write-Host "- All widgets continue to show hardcoded data as before" -ForegroundColor White
    Write-Host "- Use the admin panel to add/edit/delete entries in the database" -ForegroundColor White
    Write-Host ""
    Write-Host "Available Admin Tabs:" -ForegroundColor Yellow
    Write-Host "- Products: Manage product brands and categories" -ForegroundColor White
    Write-Host "- Services: Manage service offerings" -ForegroundColor White
    Write-Host "- About: Manage about content and CEO information" -ForegroundColor White
    Write-Host "- Downloads: Manage brand download links" -ForegroundColor White
    Write-Host "- Clients: Manage university and client logos" -ForegroundColor White
    Write-Host "- Contacts: Manage office locations and contact information" -ForegroundColor White
    Write-Host ""
    
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Please check your PHP and database setup." -ForegroundColor Red
}

Read-Host "Press Enter to exit"
