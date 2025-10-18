# SonarQube Analysis Script for TuniVert
# This script runs SonarQube analysis on the TuniVert project

Write-Host "Starting SonarQube Analysis for TuniVert..." -ForegroundColor Green

# Check if sonar-scanner is installed
if (!(Get-Command "sonar-scanner" -ErrorAction SilentlyContinue)) {
    Write-Host "Installing SonarQube Scanner..." -ForegroundColor Yellow
    
    # Download and install sonar-scanner
    $scannerVersion = "5.0.1.3006"
    $downloadUrl = "https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-${scannerVersion}-windows.zip"
    $zipPath = "$env:TEMP\sonar-scanner.zip"
    $extractPath = "$env:USERPROFILE\sonar-scanner"
    
    Write-Host "Downloading SonarQube Scanner..." -ForegroundColor Yellow
    Invoke-WebRequest -Uri $downloadUrl -OutFile $zipPath
    
    Write-Host "Extracting SonarQube Scanner..." -ForegroundColor Yellow
    Expand-Archive -Path $zipPath -DestinationPath $extractPath -Force
    
    # Add to PATH
    $scannerBin = "$extractPath\sonar-scanner-${scannerVersion}-windows\bin"
    $currentPath = [Environment]::GetEnvironmentVariable("PATH", "User")
    if ($currentPath -notlike "*$scannerBin*") {
        [Environment]::SetEnvironmentVariable("PATH", "$currentPath;$scannerBin", "User")
        $env:PATH += ";$scannerBin"
    }
    
    Write-Host "SonarQube Scanner installed successfully!" -ForegroundColor Green
}

# Wait for SonarQube server to be ready
Write-Host "Waiting for SonarQube server to be ready..." -ForegroundColor Yellow
$maxAttempts = 30
$attempt = 0
do {
    try {
        $response = Invoke-WebRequest -Uri "http://127.0.0.1:9000/api/system/status" -UseBasicParsing -TimeoutSec 5
        $status = ($response.Content | ConvertFrom-Json).status
        if ($status -eq "UP") {
            Write-Host "SonarQube server is ready!" -ForegroundColor Green
            break
        }
    } catch {
        # Server not ready yet
    }
    Start-Sleep -Seconds 2
    $attempt++
} while ($attempt -lt $maxAttempts)

if ($attempt -ge $maxAttempts) {
    Write-Host "SonarQube server is not responding. Please check if it's running." -ForegroundColor Red
    exit 1
}

# Run Laravel tests to generate coverage
Write-Host "Running Laravel tests to generate coverage..." -ForegroundColor Yellow
docker-compose exec app php artisan test --coverage --coverage-clover=coverage.xml

# Run Python tests to generate coverage
Write-Host "Running Python tests to generate coverage..." -ForegroundColor Yellow
docker-compose exec donation-ai python -m pytest --cov=. --cov-report=xml --cov-report=html

# Run SonarQube analysis
Write-Host "Running SonarQube analysis..." -ForegroundColor Yellow
sonar-scanner `
    -Dsonar.projectKey=tunivert `
    -Dsonar.sources=app,resources,python `
    -Dsonar.host.url=http://127.0.0.1:9000 `
    -Dsonar.token=sqa_4c5d6e7f8a9b0c1d2e3f4a5b6c7d8e9f0a1b2c3d `
    -Dsonar.exclusions=vendor/**,node_modules/**,public/**,storage/**,bootstrap/cache/**,**/*.blade.php,tests/** `
    -Dsonar.php.coverage.reportPaths=coverage.xml `
    -Dsonar.python.coverage.reportPaths=python/coverage.xml

Write-Host "SonarQube analysis completed!" -ForegroundColor Green
Write-Host "Visit http://127.0.0.1:9000 to view the results." -ForegroundColor Cyan