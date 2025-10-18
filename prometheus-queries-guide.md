# TuniVert Prometheus Queries Guide

## Basic Application Metrics

### Application Status
- **App Running**: `tunivert_app_up`
- **Database Connection**: `tunivert_db_up`
- **AI Service Status**: `tunivert_ai_service_up`

### Performance Metrics
- **Memory Usage (MB)**: `tunivert_memory_usage_bytes / 1024 / 1024`
- **Request Duration**: `tunivert_request_duration_seconds`
- **Average Response Time (5min)**: `rate(tunivert_request_duration_seconds[5m])`

### Sample Metrics for Testing
- **Sample Counter Rate**: `rate(tunivert_sample_counter[5m])`
- **Sample Gauge**: `tunivert_sample_gauge`

## Advanced Queries

### Service Health Dashboard
```promql
# Overall system health (0-1 scale)
(tunivert_app_up + tunivert_db_up + tunivert_ai_service_up) / 3
```

### Memory Usage Percentage (if you know the limit)
```promql
# Assuming 128MB limit
(tunivert_memory_usage_bytes / (128 * 1024 * 1024)) * 100
```

### Uptime Monitoring
```promql
# Application uptime (when it's been up for the query range)
increase(tunivert_timestamp[1h]) > 0
```

## Alerting Rules

### Critical Alerts
1. **Application Down**: `tunivert_app_up == 0`
2. **Database Disconnected**: `tunivert_db_up == 0`
3. **High Memory Usage**: `tunivert_memory_usage_bytes > 100 * 1024 * 1024`
4. **Slow Requests**: `tunivert_request_duration_seconds > 5`

### Warning Alerts
1. **AI Service Down**: `tunivert_ai_service_up == 0`
2. **Memory Warning**: `tunivert_memory_usage_bytes > 80 * 1024 * 1024`

## How to Use These Queries

1. **In Prometheus**: Go to http://127.0.0.1:9090/graph and paste any query
2. **In Grafana**: Use these as data source queries in panels
3. **For Alerts**: Add these to Prometheus alerting rules

## Testing Your Metrics

Run these commands to test metrics collection:

```bash
# Test metrics endpoint
curl http://127.0.0.1:8000/metrics

# Check specific metric
curl http://127.0.0.1:8000/metrics | grep "tunivert_app_up"

# Test in Prometheus (PowerShell)
$response = Invoke-WebRequest -Uri "http://127.0.0.1:9090/api/v1/query?query=tunivert_app_up" -UseBasicParsing
$response.Content | ConvertFrom-Json
```

## Dashboard Panels Suggestions

1. **Single Stat Panels**: App status, DB status, AI service status
2. **Graph Panels**: Memory usage over time, request duration
3. **Table Panels**: All metrics summary
4. **Heat Maps**: Request duration distribution

## Next Steps

1. Add real donation metrics when database connection is fixed
2. Set up alerting rules in Prometheus
3. Create notification channels in Grafana
4. Add more application-specific metrics