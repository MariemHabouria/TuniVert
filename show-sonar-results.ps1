# SonarQube Analysis Results for TuniVert
# This script fetches and displays comprehensive analysis results

Write-Host "=======================================" -ForegroundColor Green
Write-Host "    TuniVert SonarQube Analysis Results" -ForegroundColor Green  
Write-Host "=======================================" -ForegroundColor Green

$token = "sqa_dece4bc44132963e3724bd78aef0dd9418317f76"
$headers = @{Authorization="Bearer $token"}
$baseUrl = "http://127.0.0.1:9000/api"

try {
    # Get basic project metrics
    Write-Host "`n📊 Project Overview:" -ForegroundColor Yellow
    $metricsUrl = "$baseUrl/measures/component?component=tunivert" + "&" + "metricKeys=ncloc,bugs,vulnerabilities,code_smells,coverage,duplicated_lines_density,sqale_index,reliability_rating,security_rating,maintainability_rating"
    $metricsResponse = Invoke-WebRequest -Uri $metricsUrl -Headers $headers -UseBasicParsing
    $metrics = ($metricsResponse.Content | ConvertFrom-Json).component.measures
    
    foreach ($measure in $metrics) {
        switch ($measure.metric) {
            "ncloc" { Write-Host "  📏 Lines of Code: $($measure.value)" -ForegroundColor White }
            "bugs" { 
                $color = if ([int]$measure.value -gt 0) { "Red" } else { "Green" }
                Write-Host "  🐛 Bugs: $($measure.value)" -ForegroundColor $color 
            }
            "vulnerabilities" { 
                $color = if ([int]$measure.value -gt 0) { "Red" } else { "Green" }
                Write-Host "  🔒 Vulnerabilities: $($measure.value)" -ForegroundColor $color 
            }
            "code_smells" { 
                $color = if ([int]$measure.value -gt 50) { "Yellow" } elseif ([int]$measure.value -gt 0) { "Cyan" } else { "Green" }
                Write-Host "  👃 Code Smells: $($measure.value)" -ForegroundColor $color 
            }
            "coverage" { 
                $coverageValue = [float]$measure.value
                $color = if ($coverageValue -lt 50) { "Red" } elseif ($coverageValue -lt 80) { "Yellow" } else { "Green" }
                Write-Host "  🎯 Test Coverage: $($measure.value)%" -ForegroundColor $color 
            }
            "duplicated_lines_density" { 
                $dupValue = [float]$measure.value
                $color = if ($dupValue -gt 10) { "Red" } elseif ($dupValue -gt 3) { "Yellow" } else { "Green" }
                Write-Host "  🔄 Code Duplication: $($measure.value)%" -ForegroundColor $color 
            }
            "sqale_index" { Write-Host "  ⏱️  Technical Debt: $($measure.value) minutes" -ForegroundColor Cyan }
            "reliability_rating" { 
                $rating = switch ($measure.value) {
                    "1.0" { "A (Best)" }
                    "2.0" { "B (Good)" }
                    "3.0" { "C (Fair)" }
                    "4.0" { "D (Poor)" }
                    "5.0" { "E (Worst)" }
                    default { $measure.value }
                }
                Write-Host "  ⭐ Reliability Rating: $rating" -ForegroundColor White
            }
            "security_rating" { 
                $rating = switch ($measure.value) {
                    "1.0" { "A (Best)" }
                    "2.0" { "B (Good)" }
                    "3.0" { "C (Fair)" }
                    "4.0" { "D (Poor)" }
                    "5.0" { "E (Worst)" }
                    default { $measure.value }
                }
                Write-Host "  🛡️  Security Rating: $rating" -ForegroundColor White
            }
            "maintainability_rating" { 
                $rating = switch ($measure.value) {
                    "1.0" { "A (Best)" }
                    "2.0" { "B (Good)" }
                    "3.0" { "C (Fair)" }
                    "4.0" { "D (Poor)" }
                    "5.0" { "E (Worst)" }
                    default { $measure.value }
                }
                Write-Host "  🔧 Maintainability Rating: $rating" -ForegroundColor White
            }
        }
    }

    # Get issue breakdown
    Write-Host "`n🔍 Issue Breakdown by Severity:" -ForegroundColor Yellow
    $issuesUrl = "$baseUrl/issues/search?component=tunivert" + "&" + "facets=severities,types"
    $issuesResponse = Invoke-WebRequest -Uri $issuesUrl -Headers $headers -UseBasicParsing
    $issues = $issuesResponse.Content | ConvertFrom-Json
    
    foreach ($facet in $issues.facets) {
        if ($facet.property -eq "severities") {
            foreach ($value in $facet.values) {
                $color = switch ($value.val.ToLower()) {
                    "blocker" { "Red" }
                    "critical" { "Red" }
                    "major" { "Yellow" }
                    "minor" { "Cyan" }
                    "info" { "White" }
                    default { "White" }
                }
                Write-Host "  📋 $($value.val): $($value.count) issues" -ForegroundColor $color
            }
        }
    }

    # Quality Gate Status
    Write-Host "`n✅ Quality Gate:" -ForegroundColor Yellow
    $qgResponse = Invoke-WebRequest -Uri "$baseUrl/qualitygates/project_status?projectKey=tunivert" -Headers $headers -UseBasicParsing
    $qgStatus = ($qgResponse.Content | ConvertFrom-Json).projectStatus.status
    $qgColor = if ($qgStatus -eq "OK") { "Green" } else { "Red" }
    Write-Host "  🎯 Status: $qgStatus" -ForegroundColor $qgColor

    Write-Host "`n🌐 Dashboard Links:" -ForegroundColor Yellow
    Write-Host "  📈 Main Dashboard: http://127.0.0.1:9000/dashboard?id=tunivert" -ForegroundColor Cyan
    Write-Host "  🐛 Issues: http://127.0.0.1:9000/project/issues?id=tunivert" -ForegroundColor Cyan
    Write-Host "  📊 Measures: http://127.0.0.1:9000/component_measures?id=tunivert" -ForegroundColor Cyan
    Write-Host "  🔒 Security: http://127.0.0.1:9000/security_hotspots?id=tunivert" -ForegroundColor Cyan

} catch {
    Write-Host "❌ Error fetching SonarQube data: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host "`n=======================================" -ForegroundColor Green