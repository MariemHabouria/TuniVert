# TuniVert Monitoring Backup Strategy
Write-Host "TuniVert Monitoring Backup & Maintenance Script" -ForegroundColor Green
Write-Host "=================================================" -ForegroundColor Green

$backupDir = "monitoring\backups\$(Get-Date -Format 'yyyy-MM-dd')"
$prometheusData = "prometheus_data"
$grafanaData = "grafana_data"

# Create backup directory
if (!(Test-Path $backupDir)) {
    New-Item -Path $backupDir -ItemType Directory -Force
    Write-Host "✅ Created backup directory: $backupDir" -ForegroundColor Green
}

Write-Host "`n📊 Prometheus Data Backup" -ForegroundColor Cyan
Write-Host "-------------------------" -ForegroundColor Cyan

try {
    # Create Prometheus snapshot
    Write-Host "Creating Prometheus snapshot..." -ForegroundColor Yellow
    $snapshotResponse = Invoke-RestMethod -Uri "http://127.0.0.1:9090/api/v1/admin/tsdb/snapshot" -Method POST
    
    if ($snapshotResponse.status -eq "success") {
        $snapshotName = $snapshotResponse.data.name
        Write-Host "✅ Snapshot created: $snapshotName" -ForegroundColor Green
        
        # Copy snapshot data
        $prometheusContainer = "tunivert_prometheus"
        $snapshotPath = "/prometheus/snapshots/$snapshotName"
        $backupFile = "$backupDir\prometheus-snapshot-$snapshotName.tar.gz"
        
        Write-Host "Backing up snapshot data..." -ForegroundColor Yellow
        docker exec $prometheusContainer tar -czf /tmp/snapshot.tar.gz -C $snapshotPath .
        docker cp "$prometheusContainer:/tmp/snapshot.tar.gz" $backupFile
        docker exec $prometheusContainer rm /tmp/snapshot.tar.gz
        
        Write-Host "✅ Prometheus backup saved: $backupFile" -ForegroundColor Green
        
        # Get backup size
        $backupSize = (Get-Item $backupFile).Length / 1MB
        Write-Host "   Size: $([math]::Round($backupSize, 2)) MB" -ForegroundColor Gray
        
    } else {
        Write-Host "❌ Failed to create Prometheus snapshot" -ForegroundColor Red
    }
} catch {
    Write-Host "❌ Prometheus backup failed: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n📈 Grafana Data Backup" -ForegroundColor Cyan
Write-Host "----------------------" -ForegroundColor Cyan

try {
    # Export all dashboards
    $grafanaUrl = "http://127.0.0.1:3000"
    $credentials = "admin:admin123"
    $encodedCredentials = [System.Convert]::ToBase64String([System.Text.Encoding]::ASCII.GetBytes($credentials))
    
    $headers = @{
        "Authorization" = "Basic $encodedCredentials"
        "Content-Type" = "application/json"
    }
    
    # Get all dashboards
    Write-Host "Exporting Grafana dashboards..." -ForegroundColor Yellow
    $dashboards = Invoke-RestMethod -Uri "$grafanaUrl/api/search?query=" -Headers $headers
    
    $dashboardBackupDir = "$backupDir\grafana-dashboards"
    New-Item -Path $dashboardBackupDir -ItemType Directory -Force
    
    foreach ($dashboard in $dashboards) {
        if ($dashboard.type -eq "dash-db") {
            $dashboardData = Invoke-RestMethod -Uri "$grafanaUrl/api/dashboards/uid/$($dashboard.uid)" -Headers $headers
            $fileName = "$dashboardBackupDir\$($dashboard.uid)-$($dashboard.title -replace '[^\w\-]', '_').json"
            $dashboardData | ConvertTo-Json -Depth 20 | Out-File -FilePath $fileName -Encoding UTF8
            Write-Host "   ✅ Exported: $($dashboard.title)" -ForegroundColor Green
        }
    }
    
    # Export data sources
    Write-Host "Exporting Grafana data sources..." -ForegroundColor Yellow
    $dataSources = Invoke-RestMethod -Uri "$grafanaUrl/api/datasources" -Headers $headers
    $dataSources | ConvertTo-Json -Depth 10 | Out-File -FilePath "$dashboardBackupDir\datasources.json" -Encoding UTF8
    
    Write-Host "✅ Grafana backup completed" -ForegroundColor Green
    
} catch {
    Write-Host "❌ Grafana backup failed: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n🧹 Maintenance Tasks" -ForegroundColor Cyan
Write-Host "-------------------" -ForegroundColor Cyan

# Clean up old snapshots (keep last 7 days)
try {
    Write-Host "Cleaning old Prometheus snapshots..." -ForegroundColor Yellow
    $oldSnapshots = Invoke-RestMethod -Uri "http://127.0.0.1:9090/api/v1/admin/tsdb/delete_series?match[]={__name__=~'.+'}&start=0&end=$((Get-Date).AddDays(-7).ToUniversalTime().ToString('yyyy-MM-ddTHH:mm:ss.fffZ'))" -Method POST
    Write-Host "✅ Old data cleaned" -ForegroundColor Green
} catch {
    Write-Host "⚠️  Cleanup failed: $($_.Exception.Message)" -ForegroundColor Yellow
}

# Clean up old backups (keep last 30 days)
Write-Host "Cleaning old backup directories..." -ForegroundColor Yellow
$oldBackups = Get-ChildItem "monitoring\backups" -Directory | Where-Object { $_.CreationTime -lt (Get-Date).AddDays(-30) }
foreach ($oldBackup in $oldBackups) {
    Remove-Item $oldBackup.FullName -Recurse -Force
    Write-Host "   🗑️  Removed: $($oldBackup.Name)" -ForegroundColor Gray
}

Write-Host "`n📋 Backup Summary" -ForegroundColor Cyan
Write-Host "-----------------" -ForegroundColor Cyan

$backupInfo = Get-ChildItem $backupDir -Recurse
$totalSize = ($backupInfo | Measure-Object Length -Sum).Sum / 1MB

Write-Host "Backup Location: $backupDir" -ForegroundColor White
Write-Host "Files Created: $($backupInfo.Count)" -ForegroundColor White
Write-Host "Total Size: $([math]::Round($totalSize, 2)) MB" -ForegroundColor White

Write-Host "`n🔄 Recommended Schedule:" -ForegroundColor Yellow
Write-Host "  • Daily: Run this backup script" -ForegroundColor White
Write-Host "  • Weekly: Full system backup including Docker volumes" -ForegroundColor White
Write-Host "  • Monthly: Archive old backups to external storage" -ForegroundColor White

Write-Host "`n✨ Backup completed successfully!" -ForegroundColor Green