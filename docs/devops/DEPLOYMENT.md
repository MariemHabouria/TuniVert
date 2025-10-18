# TuniVert Deployment Guide

## 🎯 Server Requirements

### Minimum Requirements
- **CPU:** 2 cores
- **RAM:** 4GB
- **Storage:** 50GB SSD
- **OS:** Ubuntu 20.04+ or CentOS 8+

### Recommended Requirements
- **CPU:** 4 cores
- **RAM:** 8GB
- **Storage:** 100GB SSD
- **OS:** Ubuntu 22.04 LTS

## 📦 Initial Server Setup

### 1. Update System
```bash
sudo apt update && sudo apt upgrade -y
sudo apt install -y curl wget git unzip
```

### 2. Install Docker
```bash
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh
sudo usermod -aG docker $USER
```

### 3. Install Docker Compose
```bash
sudo curl -L "https://github.com/docker/compose/releases/download/v2.23.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
sudo chmod +x /usr/local/bin/docker-compose
```

### 4. Create Deployment User
```bash
sudo adduser deploy
sudo usermod -aG docker deploy
sudo usermod -aG sudo deploy
```

### 5. Setup SSH Keys
```bash
# On your local machine
ssh-copy-id deploy@your-server-ip

# Or manually copy your public key
sudo mkdir -p /home/deploy/.ssh
sudo cp ~/.ssh/authorized_keys /home/deploy/.ssh/
sudo chown -R deploy:deploy /home/deploy/.ssh
sudo chmod 700 /home/deploy/.ssh
sudo chmod 600 /home/deploy/.ssh/authorized_keys
```

## 🚀 Production Deployment

### 1. Clone Repository
```bash
sudo mkdir -p /var/www
sudo chown deploy:deploy /var/www
cd /var/www
git clone https://github.com/MariemHabouria/TuniVert.git tunivert-production
cd tunivert-production
```

### 2. Setup Environment
```bash
# Copy and configure environment
cp .env.production.example .env.production

# Edit with your production values
nano .env.production
```

### 3. Generate SSL Certificates (Let's Encrypt)
```bash
sudo apt install -y certbot
sudo certbot certonly --standalone -d your-domain.com -d www.your-domain.com

# Copy certificates to project
sudo mkdir -p ssl
sudo cp /etc/letsencrypt/live/your-domain.com/fullchain.pem ssl/
sudo cp /etc/letsencrypt/live/your-domain.com/privkey.pem ssl/
sudo chown -R deploy:deploy ssl/
```

### 4. Start Production Services
```bash
# Build and start containers
docker-compose -f docker-compose.prod.yml build
docker-compose -f docker-compose.prod.yml up -d

# Wait for services to start
sleep 30

# Run Laravel setup
docker-compose -f docker-compose.prod.yml exec app composer install --no-dev --optimize-autoloader
docker-compose -f docker-compose.prod.yml exec app php artisan key:generate
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

### 5. Configure Reverse Proxy (if needed)
```nginx
# /etc/nginx/sites-available/tunivert
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;

    ssl_certificate /var/www/tunivert-production/ssl/fullchain.pem;
    ssl_certificate_key /var/www/tunivert-production/ssl/privkey.pem;

    location / {
        proxy_pass http://localhost:80;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;
    }
}
```

## 📊 Monitoring Setup

### 1. Configure Grafana
```bash
# Access Grafana at http://your-domain.com:3000
# Default login: admin/admin (change immediately)

# Import dashboards from monitoring/grafana/dashboards/
```

### 2. Setup Alerting
```bash
# Configure Slack webhook in alertmanager.yml
# Set up email SMTP settings
# Test alerts with:
docker-compose -f docker-compose.prod.yml exec prometheus promtool query instant 'up'
```

### 3. Configure Log Rotation
```bash
# Add to /etc/logrotate.d/tunivert
/var/www/tunivert-production/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    notifempty
    create 0644 www-data www-data
    postrotate
        docker-compose -f /var/www/tunivert-production/docker-compose.prod.yml restart app
    endscript
}
```

## 🔄 Zero-Downtime Deployment

### 1. Blue-Green Deployment Setup
```bash
# Create blue and green directories
sudo mkdir -p /var/www/tunivert-blue /var/www/tunivert-green

# Setup load balancer configuration
# Use HAProxy or Nginx to switch between versions
```

### 2. Rolling Update Script
```bash
#!/bin/bash
# rolling-update.sh

set -e

echo "Starting rolling update..."

# Pull latest changes
git pull origin main

# Build new images
docker-compose -f docker-compose.prod.yml build

# Update services one by one
services=("app" "donation-ai" "nginx")

for service in "${services[@]}"; do
    echo "Updating $service..."
    docker-compose -f docker-compose.prod.yml up -d --no-deps $service
    sleep 10
    
    # Health check
    if ! docker-compose -f docker-compose.prod.yml exec $service curl -f http://localhost/health; then
        echo "Health check failed for $service"
        exit 1
    fi
done

echo "Rolling update completed successfully!"
```

## 🔐 Security Hardening

### 1. Firewall Configuration
```bash
sudo ufw enable
sudo ufw allow ssh
sudo ufw allow 80
sudo ufw allow 443
sudo ufw allow 3000  # Grafana (restrict to specific IPs)
sudo ufw allow 9090  # Prometheus (restrict to specific IPs)
```

### 2. Docker Security
```bash
# Enable Docker content trust
export DOCKER_CONTENT_TRUST=1

# Scan images for vulnerabilities
docker run --rm -v /var/run/docker.sock:/var/run/docker.sock aquasec/trivy image tunivert:latest
```

### 3. System Hardening
```bash
# Disable root SSH
sudo sed -i 's/PermitRootLogin yes/PermitRootLogin no/' /etc/ssh/sshd_config
sudo systemctl restart ssh

# Setup fail2ban
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## 📋 Backup Strategy

### 1. Database Backup
```bash
#!/bin/bash
# backup-database.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/tunivert"
mkdir -p $BACKUP_DIR

# Backup MySQL
docker-compose -f docker-compose.prod.yml exec mysql mysqldump -u root -p$DB_ROOT_PASSWORD tunivert > $BACKUP_DIR/database_$DATE.sql

# Compress backup
gzip $BACKUP_DIR/database_$DATE.sql

# Keep only last 30 days
find $BACKUP_DIR -name "database_*.sql.gz" -mtime +30 -delete

# Upload to S3 (optional)
# aws s3 cp $BACKUP_DIR/database_$DATE.sql.gz s3://your-backup-bucket/
```

### 2. Application Files Backup
```bash
#!/bin/bash
# backup-files.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/tunivert"

# Backup storage and uploads
tar -czf $BACKUP_DIR/storage_$DATE.tar.gz -C /var/www/tunivert-production storage/app/public/

# Keep only last 30 days
find $BACKUP_DIR -name "storage_*.tar.gz" -mtime +30 -delete
```

### 3. Automated Backup Cron
```bash
# Add to crontab: sudo crontab -e
# Daily database backup at 2 AM
0 2 * * * /var/www/tunivert-production/scripts/backup-database.sh

# Weekly file backup at 3 AM on Sundays
0 3 * * 0 /var/www/tunivert-production/scripts/backup-files.sh
```

## 🔍 Health Monitoring

### 1. Application Health Checks
```bash
#!/bin/bash
# health-check.sh

# Check application response
if ! curl -f -s http://localhost/health > /dev/null; then
    echo "Application health check failed"
    # Send alert
    curl -X POST $SLACK_WEBHOOK -d '{"text":"TuniVert application health check failed"}'
    exit 1
fi

# Check database connectivity
if ! docker-compose -f docker-compose.prod.yml exec mysql mysqladmin ping -h localhost; then
    echo "Database health check failed"
    exit 1
fi

echo "All health checks passed"
```

### 2. Performance Monitoring
```bash
#!/bin/bash
# performance-check.sh

# Check response time
RESPONSE_TIME=$(curl -o /dev/null -s -w "%{time_total}\n" http://localhost/)

if (( $(echo "$RESPONSE_TIME > 2.0" | bc -l) )); then
    echo "High response time detected: ${RESPONSE_TIME}s"
    # Send alert
fi

# Check disk usage
DISK_USAGE=$(df / | awk 'NR==2{print $5}' | sed 's/%//')

if [ $DISK_USAGE -gt 85 ]; then
    echo "High disk usage: ${DISK_USAGE}%"
    # Send alert
fi
```

## 🚨 Disaster Recovery

### 1. Recovery Procedure
```bash
# 1. Stop all services
docker-compose -f docker-compose.prod.yml down

# 2. Restore database from backup
docker-compose -f docker-compose.prod.yml up -d mysql
sleep 30
gunzip -c /var/backups/tunivert/database_YYYYMMDD_HHMMSS.sql.gz | \
docker-compose -f docker-compose.prod.yml exec -T mysql mysql -u root -p$DB_ROOT_PASSWORD tunivert

# 3. Restore application files
tar -xzf /var/backups/tunivert/storage_YYYYMMDD_HHMMSS.tar.gz -C /var/www/tunivert-production/

# 4. Start all services
docker-compose -f docker-compose.prod.yml up -d
```

### 2. Recovery Testing
```bash
# Monthly recovery test
# 1. Create test environment
# 2. Restore from latest backup
# 3. Verify application functionality
# 4. Document any issues
```

## 📞 Emergency Contacts

- **DevOps Team:** devops@tunivert.com
- **Hosting Provider:** [Provider Support]
- **Domain Registrar:** [Registrar Support]
- **SSL Certificate:** [Certificate Provider]

## 📝 Deployment Checklist

### Pre-Deployment
- [ ] Code reviewed and approved
- [ ] Tests passing
- [ ] Security scan completed
- [ ] Database migration tested
- [ ] Backup completed
- [ ] Monitoring alerts configured

### During Deployment
- [ ] Maintenance mode enabled
- [ ] Services updated
- [ ] Database migrations run
- [ ] Configuration cached
- [ ] Health checks passed
- [ ] Maintenance mode disabled

### Post-Deployment
- [ ] Application accessible
- [ ] All features working
- [ ] Performance metrics normal
- [ ] Error logs checked
- [ ] Monitoring alerts active
- [ ] Team notified