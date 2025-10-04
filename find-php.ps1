Write-Host "Finding PHP installations..." -ForegroundColor Green
Write-Host ""

# Check if PHP is in PATH
Write-Host "1. Checking PATH..." -ForegroundColor Yellow
try {
    $phpInPath = Get-Command php -ErrorAction Stop
    Write-Host "✅ PHP found in PATH: $($phpInPath.Source)" -ForegroundColor Green
} catch {
    Write-Host "❌ PHP not found in PATH" -ForegroundColor Red
}

Write-Host ""
Write-Host "2. Checking Laragon installations..." -ForegroundColor Yellow

# Check Laragon directory
$laragonPath = "C:\laragon\bin\php\"
if (Test-Path $laragonPath) {
    Write-Host "✅ Laragon PHP directory found: $laragonPath" -ForegroundColor Green
    
    # Find all PHP installations
    $phpInstallations = Get-ChildItem $laragonPath -Recurse -Name "php.exe" -ErrorAction SilentlyContinue
    if ($phpInstallations) {
        Write-Host ""
        Write-Host "Found PHP installations:" -ForegroundColor Cyan
        foreach ($php in $phpInstallations) {
            $fullPath = Join-Path $laragonPath $php
            Write-Host "  - $fullPath" -ForegroundColor White
        }
        
        # Test the first one
        $firstPhp = Join-Path $laragonPath $phpInstallations[0]
        Write-Host ""
        Write-Host "3. Testing first PHP installation..." -ForegroundColor Yellow
        try {
            $version = & $firstPhp --version
            Write-Host "✅ PHP working: $($version[0])" -ForegroundColor Green
            Write-Host ""
            Write-Host "You can use this path in your setup:" -ForegroundColor Cyan
            Write-Host "  $firstPhp" -ForegroundColor White
        } catch {
            Write-Host "❌ PHP test failed: $($_.Exception.Message)" -ForegroundColor Red
        }
    } else {
        Write-Host "❌ No PHP installations found in Laragon" -ForegroundColor Red
    }
} else {
    Write-Host "❌ Laragon PHP directory not found: $laragonPath" -ForegroundColor Red
}

Write-Host ""
Write-Host "4. Checking other common locations..." -ForegroundColor Yellow

$commonPaths = @(
    "C:\xampp\php\php.exe",
    "C:\wamp64\bin\php\php8.1.0\php.exe",
    "C:\wamp64\bin\php\php8.0.0\php.exe",
    "C:\Program Files\PHP\php.exe"
)

foreach ($path in $commonPaths) {
    if (Test-Path $path) {
        Write-Host "✅ Found: $path" -ForegroundColor Green
    } else {
        Write-Host "❌ Not found: $path" -ForegroundColor Gray
    }
}

Write-Host ""
Write-Host "Setup Instructions:" -ForegroundColor Cyan
Write-Host "1. Copy the working PHP path from above" -ForegroundColor White
Write-Host "2. Run: .\setup-all-widgets.bat" -ForegroundColor White
Write-Host "3. Or follow the manual setup guide" -ForegroundColor White
Write-Host ""

Read-Host "Press Enter to exit"
