# TuniVert Monitoring Dashboard Guide

## 📊 Grafana Dashboard Overview

### Access Information
- **URL:** http://your-domain.com:3000
- **Default Login:** admin/admin (change immediately)
- **Organization:** TuniVert

## 🎯 Pre-configured Dashboards

### 1. TuniVert Application Overview
**Dashboard ID:** tunivert-app-overview

**Panels:**
- **HTTP Request Rate:** Requests per second
- **Response Time:** P50, P95, P99 percentiles
- **Error Rate:** HTTP 4xx/5xx errors percentage
- **Active Users:** Current concurrent users
- **Donation Metrics:** Donations per hour/day
- **AI Service Performance:** Python service response times

**Key Metrics:**
```promql
# Request rate
rate(nginx_http_requests_total[5m])

# Response time
histogram_quantile(0.95, rate(nginx_http_request_duration_seconds_bucket[5m]))

# Error rate
rate(nginx_http_requests_total{status=~"5.."}[5m]) / rate(nginx_http_requests_total[5m])
```

### 2. Infrastructure Health
**Dashboard ID:** infrastructure-health

**Panels:**
- **CPU Usage:** System and container CPU utilization
- **Memory Usage:** RAM consumption by service
- **Disk I/O:** Read/write operations
- **Network Traffic:** Bandwidth utilization
- **Container Status:** Running/stopped containers

**Key Metrics:**
```promql
# CPU usage
100 - (avg(rate(node_cpu_seconds_total{mode="idle"}[5m])) * 100)

# Memory usage
(node_memory_MemTotal_bytes - node_memory_MemAvailable_bytes) / node_memory_MemTotal_bytes * 100

# Disk usage
(node_filesystem_size_bytes - node_filesystem_avail_bytes) / node_filesystem_size_bytes * 100
```

### 3. Database Performance
**Dashboard ID:** database-performance

**Panels:**
- **Query Performance:** Slow queries and execution time
- **Connection Pool:** Active/idle connections
- **InnoDB Metrics:** Buffer pool usage, lock waits
- **Database Size:** Table sizes and growth
- **Replication Status:** If using replication

**Key Metrics:**
```promql
# Query rate
rate(mysql_global_status_queries[5m])

# Slow queries
rate(mysql_global_status_slow_queries[5m])

# Connection usage
mysql_global_status_threads_connected / mysql_global_variables_max_connections * 100
```

### 4. Redis Cache Performance
**Dashboard ID:** redis-performance

**Panels:**
- **Cache Hit Rate:** Hit/miss ratio
- **Memory Usage:** Used memory vs available
- **Operations Rate:** Commands per second
- **Client Connections:** Connected clients
- **Key Expiration:** Keys expired per second

**Key Metrics:**
```promql
# Cache hit rate
rate(redis_keyspace_hits_total[5m]) / (rate(redis_keyspace_hits_total[5m]) + rate(redis_keyspace_misses_total[5m])) * 100

# Memory usage
redis_memory_used_bytes / redis_memory_max_bytes * 100
```

### 5. Application Logs Analysis
**Dashboard ID:** logs-analysis

**Panels:**
- **Log Volume:** Logs per service and level
- **Error Trends:** Error rate over time
- **Performance Logs:** Slow operations
- **Security Events:** Authentication failures, suspicious activity

**LogQL Queries:**
```logql
# Error rate
rate({job="laravel"} |= "ERROR" [5m])

# Slow queries
{job="laravel"} |= "slow" | json | duration > 1000ms

# Failed logins
{job="nginx"} |= "login" |= "failed"
```

## 🔔 Alert Configuration

### Critical Alerts
1. **Application Down**
   ```promql
   up{job="tunivert-app"} == 0
   ```

2. **High Error Rate**
   ```promql
   rate(nginx_http_requests_total{status=~"5.."}[5m]) / rate(nginx_http_requests_total[5m]) > 0.05
   ```

3. **Database Connection Issues**
   ```promql
   mysql_up == 0
   ```

4. **Disk Space Critical**
   ```promql
   (node_filesystem_avail_bytes{mountpoint="/"} / node_filesystem_size_bytes{mountpoint="/"}) * 100 < 10
   ```

### Warning Alerts
1. **High CPU Usage**
   ```promql
   100 - (avg(rate(node_cpu_seconds_total{mode="idle"}[5m])) * 100) > 80
   ```

2. **High Memory Usage**
   ```promql
   (node_memory_MemTotal_bytes - node_memory_MemAvailable_bytes) / node_memory_MemTotal_bytes * 100 > 85
   ```

3. **Slow Response Time**
   ```promql
   histogram_quantile(0.95, rate(nginx_http_request_duration_seconds_bucket[5m])) > 2
   ```

## 📈 Custom Metrics

### Laravel Application Metrics
Add these to your Laravel application for better monitoring:

```php
// app/Http/Middleware/PrometheusMetrics.php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PrometheusMetrics
{
    public function handle(Request $request, Closure $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        
        // Log metrics for Promtail to pick up
        Log::channel('metrics')->info('request_duration', [
            'method' => $request->method(),
            'route' => $request->route()?->getName() ?? 'unknown',
            'status' => $response->getStatusCode(),
            'duration' => $duration,
        ]);
        
        return $response;
    }
}
```

### Business Metrics

```php
// Track donation metrics
Log::channel('metrics')->info('donation_created', [
    'amount' => $donation->montant,
    'method' => $donation->payment_method,
    'event_id' => $donation->event_id,
]);

// Track AI suggestion usage
Log::channel('metrics')->info('ai_suggestion_shown', [
    'event_id' => $eventId,
    'user_id' => $userId,
    'suggestion_amount' => $suggestionAmount,
]);
```

## 🎨 Dashboard Customization

### Creating Custom Panels

1. **Click "Add Panel"**
2. **Choose Visualization Type:**
   - Time series (for trends)
   - Stat (for current values)
   - Gauge (for thresholds)
   - Bar chart (for distributions)
   - Table (for detailed data)

3. **Configure Query:**
   ```promql
   # Example: Donations per hour
   increase(donations_total[1h])
   
   # Example: Average response time by endpoint
   avg by (route) (rate(http_request_duration_seconds_sum[5m]) / rate(http_request_duration_seconds_count[5m]))
   ```

4. **Set Thresholds and Colors**
5. **Add Annotations and Links**

### Dashboard Variables

```yaml
# Create variables for dynamic dashboards
Variables:
  - name: environment
    type: query
    query: label_values(up, env)
  
  - name: service
    type: query
    query: label_values(up{env="$environment"}, job)
  
  - name: interval
    type: interval
    values: [1m, 5m, 15m, 30m, 1h]
```

## 📱 Mobile Dashboard

### Key Mobile Metrics
- Application status (up/down)
- Current error rate
- Response time
- Active alerts count

### Mobile App Configuration
1. **Install Grafana Mobile App**
2. **Add your Grafana instance**
3. **Configure push notifications**
4. **Set up offline access**

## 🔧 Troubleshooting Dashboards

### Common Issues

1. **No Data Showing:**
   ```bash
   # Check Prometheus targets
   curl http://localhost:9090/api/v1/targets
   
   # Check metric names
   curl http://localhost:9090/api/v1/label/__name__/values
   ```

2. **Slow Dashboard Loading:**
   - Reduce time range
   - Optimize queries
   - Use recording rules for complex metrics

3. **Missing Metrics:**
   ```bash
   # Check exporters are running
   docker-compose ps
   
   # Check exporter endpoints
   curl http://localhost:9100/metrics
   ```

### Performance Optimization

1. **Use Recording Rules:**
   ```yaml
   # prometheus/rules/recording.yml
   groups:
     - name: tunivert_recording_rules
       rules:
         - record: tunivert:request_rate
           expr: rate(nginx_http_requests_total[5m])
         
         - record: tunivert:error_rate
           expr: rate(nginx_http_requests_total{status=~"5.."}[5m]) / rate(nginx_http_requests_total[5m])
   ```

2. **Dashboard Caching:**
   - Set appropriate refresh intervals
   - Use relative time ranges
   - Cache expensive queries

## 📊 Reporting and Analytics

### Automated Reports

```bash
#!/bin/bash
# generate-report.sh

# Generate daily report
curl -H "Authorization: Bearer $GRAFANA_API_KEY" \
     "http://localhost:3000/api/dashboards/uid/tunivert-app-overview" | \
     jq '.dashboard' > daily-report.json

# Email report
mail -s "TuniVert Daily Report" admin@tunivert.com < daily-report.json
```

### SLA Monitoring

```promql
# Availability SLA (99.9%)
(
  (count(up{job="tunivert-app"} == 1) / count(up{job="tunivert-app"})) * 100
)

# Performance SLA (95% of requests < 2s)
(
  histogram_quantile(0.95, rate(nginx_http_request_duration_seconds_bucket[24h])) < 2
)
```

## 🔐 Security and Access Control

### User Management
1. **Create Team for TuniVert**
2. **Set Permissions:**
   - Admins: Full access
   - Developers: View + edit dashboards
   - Operations: View only

### API Keys
```bash
# Create API key for automation
curl -X POST \
  http://admin:admin@localhost:3000/api/auth/keys \
  -H 'Content-Type: application/json' \
  -d '{
    "name": "automation-key",
    "role": "Viewer"
  }'
```

## 📞 Support and Maintenance

### Regular Maintenance Tasks
- Weekly dashboard review
- Monthly query optimization
- Quarterly metric cleanup
- Annual dashboard redesign

### Getting Help
- Grafana Documentation: https://grafana.com/docs/
- Prometheus Documentation: https://prometheus.io/docs/
- Community Forums: https://community.grafana.com/

### Emergency Procedures
1. **Dashboard Not Loading:**
   - Check Grafana service status
   - Verify Prometheus connection
   - Review error logs

2. **Missing Data:**
   - Check data source health
   - Verify metric scraping
   - Review retention policies