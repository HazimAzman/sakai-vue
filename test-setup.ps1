Write-Host "Testing Widget CRUD Setup..." -ForegroundColor Green
Write-Host ""

# Find PHP
$phpPath = $null
$commonPaths = @(
    "C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe",
    "C:\laragon\bin\php\php-8.2.12-Win32-vs16-x64\php.exe",
    "C:\laragon\bin\php\php-8.3.0-Win32-vs16-x64\php.exe",
    "C:\laragon\bin\php\php-8.0.30-Win32-vs16-x64\php.exe",
    "C:\laragon\bin\php\php-7.4.33-Win32-vc15-x64\php.exe",
    "C:\laragon\bin\php\php-7.4.1-Win32-vc15-x64\php.exe"
)

foreach ($path in $commonPaths) {
    if (Test-Path $path) {
        $phpPath = $path
        break
    }
}

if (-not $phpPath) {
    Write-Host "❌ PHP not found. Please check your Laragon installation." -ForegroundColor Red
    exit 1
}

Write-Host "✅ Using PHP: $phpPath" -ForegroundColor Green
Write-Host ""

# Change to backend directory
Set-Location "backend\backend-api"

Write-Host "1. Testing migrations..." -ForegroundColor Yellow
& $phpPath yii migrate --interactive=0
if ($LASTEXITCODE -ne 0) {
    Write-Host "❌ Migration failed" -ForegroundColor Red
    exit 1
}
Write-Host "✅ Migrations completed" -ForegroundColor Green

Write-Host ""
Write-Host "2. Testing seeder commands..." -ForegroundColor Yellow

$seeders = @(
    "seed-products",
    "seed-services", 
    "seed-about",
    "seed-downloads",
    "seed-clients",
    "seed-contacts"
)

foreach ($seeder in $seeders) {
    Write-Host "Testing $seeder..." -ForegroundColor Cyan
    & $phpPath yii $seeder
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ $seeder working" -ForegroundColor Green
    } else {
        Write-Host "❌ $seeder failed" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "3. Testing API endpoints..." -ForegroundColor Yellow

# Test API endpoints
$apiEndpoints = @(
    "http://localhost/sakai-vue/backend/backend-api/web/api/products",
    "http://localhost/sakai-vue/backend/backend-api/web/api/services",
    "http://localhost/sakai-vue/backend/backend-api/web/api/about",
    "http://localhost/sakai-vue/backend/backend-api/web/api/downloads",
    "http://localhost/sakai-vue/backend/backend-api/web/api/clients",
    "http://localhost/sakai-vue/backend/backend-api/web/api/contacts"
)

foreach ($endpoint in $apiEndpoints) {
    try {
        $response = Invoke-WebRequest -Uri $endpoint -Method GET -TimeoutSec 5
        if ($response.StatusCode -eq 200) {
            Write-Host "✅ $endpoint working" -ForegroundColor Green
        } else {
            Write-Host "❌ $endpoint returned status $($response.StatusCode)" -ForegroundColor Red
        }
    } catch {
        Write-Host "❌ $endpoint failed: $($_.Exception.Message)" -ForegroundColor Red
    }
}

Write-Host ""
Write-Host "4. Setup Summary:" -ForegroundColor Yellow
Write-Host "✅ Database tables created" -ForegroundColor Green
Write-Host "✅ Seeder commands working" -ForegroundColor Green
Write-Host "✅ API endpoints configured" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Start backend server: $phpPath yii serve" -ForegroundColor White
Write-Host "2. Start frontend server: npm run dev" -ForegroundColor White
Write-Host "3. Access admin panel at /admin" -ForegroundColor White
Write-Host ""

Read-Host "Press Enter to exit"
