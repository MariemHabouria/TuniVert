# TuniVert DevOps Setup Guide

## 🚀 Overview

This guide provides comprehensive instructions for setting up and managing the TuniVert DevOps infrastructure using:
- **Docker & Docker Compose** for containerization
- **GitHub Actions** for CI/CD pipeline
- **SonarQube** for code quality analysis
- **Prometheus & Grafana** for monitoring
- **Loki** for log aggregation
- **Alertmanager** for alerting

## 📋 Prerequisites

- Docker and Docker Compose installed
- Git configured
- GitHub repository access
- Server/VPS for production deployment

## 🐳 Docker Setup

### Development Environment

1. **Start the development environment:**
```bash
# Clone the repository
git clone https://github.com/MariemHabouria/TuniVert.git
cd TuniVert

# Copy environment file
cp .env.example .env

# Start all services
docker-compose up -d

# Install Laravel dependencies
docker-compose exec app composer install

# Generate app key
docker-compose exec app php artisan key:generate

# Run migrations
docker-compose exec app php artisan migrate --seed
```

2. **Access services:**
- Laravel App: http://localhost:8000
- phpMyAdmin: http://localhost:8080
- Mailhog: http://localhost:8025
- Python AI Service: http://localhost:8085

### Production Environment

1. **Prepare production environment:**
```bash
# Copy production environment file
cp .env.production.example .env.production

# Edit with your production values
nano .env.production

# Start production services
docker-compose -f docker-compose.prod.yml up -d
```

2. **Access production services:**
- Application: http://your-domain.com
- Grafana: http://your-domain.com:3000
- Prometheus: http://your-domain.com:9090
- SonarQube: http://your-domain.com:9000

## 🔄 CI/CD Pipeline

### GitHub Actions Workflows

1. **Main CI/CD Pipeline (.github/workflows/ci-cd.yml):**
   - Security scanning with Trivy
   - PHP tests with coverage
   - Python tests with coverage
   - SonarQube analysis
   - Docker image building
   - Automated deployment

2. **Dependencies Update (.github/workflows/dependencies.yml):**
   - Weekly dependency updates
   - Security audits
   - Automated PR creation

### Required GitHub Secrets

```bash
# Production Secrets
PRODUCTION_HOST=your-production-server-ip
PRODUCTION_USERNAME=deploy-user
PRODUCTION_SSH_KEY=your-ssh-private-key
PRODUCTION_URL=https://your-domain.com

# Staging Secrets
STAGING_HOST=your-staging-server-ip
STAGING_USERNAME=deploy-user
STAGING_SSH_KEY=your-ssh-private-key

# SonarQube
SONAR_TOKEN=your-sonarqube-token
SONAR_HOST_URL=http://your-sonarqube-url

# Notifications
SLACK_WEBHOOK_URL=your-slack-webhook-url
```

## 📊 Monitoring Setup

### Prometheus Configuration

Prometheus scrapes metrics from:
- Application endpoints (`/metrics`)
- Node Exporter (system metrics)
- MySQL Exporter (database metrics)
- Redis Exporter (cache metrics)
- Nginx Exporter (web server metrics)

### Grafana Dashboards

Pre-configured dashboards for:
- System Overview (CPU, Memory, Disk)
- Application Performance (Response times, Error rates)
- Database Monitoring (Connections, Queries)
- Infrastructure Health (Docker containers)

### Alerting Rules

Configured alerts for:
- High CPU/Memory usage
- Disk space warnings
- Application downtime
- Database issues
- High error rates

## 🔍 Code Quality (SonarQube)

### Quality Gates

- Coverage threshold: 80%
- Duplicated lines: < 3%
- Maintainability rating: A
- Reliability rating: A
- Security rating: A

### Running Analysis Locally

```bash
# Install SonarQube Scanner
wget https://binaries.sonarsource.com/Distribution/sonar-scanner-cli/sonar-scanner-cli-4.8.0.2856-linux.zip
unzip sonar-scanner-cli-4.8.0.2856-linux.zip

# Run analysis
./sonar-scanner/bin/sonar-scanner
```

## 🚀 Deployment Process

### Automatic Deployment

1. **Staging Deployment (develop branch):**
   - Push to `develop` branch
   - CI/CD pipeline runs automatically
   - Deploys to staging environment

2. **Production Deployment (main branch):**
   - Push to `main` branch
   - Full CI/CD pipeline including security scans
   - Deploys to production environment

### Manual Deployment

```bash
# SSH to production server
ssh deploy-user@your-production-server

# Navigate to application directory
cd /var/www/tunivert-production

# Pull latest changes
git pull origin main

# Update containers
docker-compose -f docker-compose.prod.yml pull
docker-compose -f docker-compose.prod.yml up -d

# Run Laravel optimizations
docker-compose -f docker-compose.prod.yml exec app php artisan migrate --force
docker-compose -f docker-compose.prod.yml exec app php artisan config:cache
docker-compose -f docker-compose.prod.yml exec app php artisan route:cache
docker-compose -f docker-compose.prod.yml exec app php artisan view:cache
```

## 🛠 Maintenance Commands

### Docker Maintenance

```bash
# View logs
docker-compose logs -f app

# Restart services
docker-compose restart app

# Clean up unused containers/images
docker system prune -f

# Backup database
docker-compose exec mysql mysqldump -u root -p tunivert > backup.sql
```

### Laravel Maintenance

```bash
# Clear caches
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Queue management
docker-compose exec app php artisan queue:work
docker-compose exec app php artisan queue:restart
```

## 📈 Performance Optimization

### Laravel Optimizations

1. **Enable OpCache in production**
2. **Use Redis for sessions and cache**
3. **Optimize Composer autoloader**
4. **Cache configuration, routes, and views**

### Database Optimizations

1. **Enable slow query log**
2. **Monitor connection usage**
3. **Regular backup schedule**
4. **Index optimization**

### Nginx Optimizations

1. **Enable gzip compression**
2. **Set proper cache headers**
3. **Use HTTP/2**
4. **Configure rate limiting**

## 🔐 Security Best Practices

### Container Security

1. **Use non-root users in containers**
2. **Scan images for vulnerabilities**
3. **Keep base images updated**
4. **Use secrets management**

### Application Security

1. **Regular dependency updates**
2. **Security headers configuration**
3. **Input validation and sanitization**
4. **HTTPS enforcement**

## 📞 Troubleshooting

### Common Issues

1. **Container Won't Start:**
   ```bash
   docker-compose logs service-name
   docker-compose ps
   ```

2. **Database Connection Issues:**
   ```bash
   docker-compose exec app php artisan tinker
   DB::connection()->getPdo();
   ```

3. **Permission Issues:**
   ```bash
   docker-compose exec app chown -R www-data:www-data storage bootstrap/cache
   ```

### Health Checks

```bash
# Application health
curl http://localhost:8000/health

# Database health
docker-compose exec mysql mysqladmin ping

# Redis health
docker-compose exec redis redis-cli ping
```

## 📧 Support

For issues and questions:
- Create GitHub Issues
- Contact DevOps team: devops@tunivert.com
- Slack: #devops-support

## 🔄 Updates

This documentation is updated with each release. Check the git history for changes:
```bash
git log --oneline -- docs/devops/
```