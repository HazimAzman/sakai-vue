Write-Host "Setting up Product CRUD system..." -ForegroundColor Green
Write-Host ""

Write-Host "1. Running database migration..." -ForegroundColor Yellow
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
            "C:\xampp\php\php.exe",
            "C:\wamp64\bin\php\php8.1.0\php.exe"
        )
        
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
    
    # Run migration
    & $phpPath yii migrate --interactive=0
    if ($LASTEXITCODE -ne 0) {
        throw "Migration failed"
    }
    
    Write-Host ""
    Write-Host "2. Seeding products database..." -ForegroundColor Yellow
    & $phpPath yii seed-products
    if ($LASTEXITCODE -ne 0) {
        throw "Seeding failed"
    }
    
    Write-Host ""
    Write-Host "3. Setup completed successfully!" -ForegroundColor Green
    Write-Host ""
    Write-Host "You can now:" -ForegroundColor Cyan
    Write-Host "- Access the admin panel at /admin in your Vue app" -ForegroundColor White
    Write-Host "- Go to the Products tab to manage products" -ForegroundColor White
    Write-Host "- The ProductWidget will continue to show the hardcoded products" -ForegroundColor White
    Write-Host "- Use the admin panel to add/edit/delete products in the database" -ForegroundColor White
    Write-Host ""
    
} catch {
    Write-Host "Error: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Please check your PHP and database setup." -ForegroundColor Red
}

Read-Host "Press Enter to exit"
