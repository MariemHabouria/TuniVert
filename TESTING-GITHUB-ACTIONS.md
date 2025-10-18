# Testing GitHub Actions - Complete Guide

## 🎯 How to Know if GitHub Actions Work Good

### 1. **Local Pre-Testing (Before Pushing)**

#### A. Test PHP Components Locally
```powershell
# Run Laravel tests locally
php artisan test

# Run with coverage
php artisan test --coverage

# Test specific features
php artisan test --filter DonationTest
```

#### B. Test Python AI Service Locally  
```powershell
# Activate Python environment
.\.venv\Scripts\Activate.ps1

# Run Python tests
cd python
pytest --cov=. --cov-report=html

# Test the AI service directly
python -m pytest test_donation_ai_service.py -v
```

#### C. Test Docker Build Process
```powershell
# Test main application build
docker build -t tunivert-test .

# Test Python service build
docker build -t tunivert-python-test ./python

# Test full stack
docker-compose up --build -d
docker-compose exec app php artisan test
docker-compose down
```

### 2. **GitHub Actions Workflow Testing**

#### A. Check Workflow Files Syntax
```powershell
# Install GitHub CLI (if not installed)
# winget install GitHub.cli

# Login to GitHub
gh auth login

# Validate workflow syntax
gh workflow list
gh workflow view ci-cd.yml
```

#### B. Trigger Workflows Manually
```powershell
# Push changes to trigger workflows
git add .
git commit -m "test: trigger CI/CD pipeline"
git push origin main

# Or trigger manually via GitHub CLI
gh workflow run ci-cd.yml
```

### 3. **Monitor GitHub Actions Execution**

#### A. Real-time Monitoring
```powershell
# Watch workflow runs
gh run list --workflow=ci-cd.yml

# View specific run details
gh run view [RUN_ID]

# View logs for specific job
gh run view [RUN_ID] --log
```

#### B. Check via Web Interface
- Go to: `https://github.com/MariemHabouria/TuniVert/actions`
- Monitor each workflow step:
  - ✅ Security Scan
  - ✅ PHP Tests  
  - ✅ Python Tests
  - ✅ SonarQube Analysis
  - ✅ Docker Build & Push
  - ✅ Deploy to Staging/Production

### 4. **Key Success Indicators**

#### ✅ **All Jobs Should Pass**
- **Security Scan**: No critical vulnerabilities
- **PHP Tests**: All tests pass with >80% coverage
- **Python Tests**: All AI service tests pass
- **SonarQube**: Quality gate passes
- **Docker Build**: Images built and pushed successfully
- **Deployment**: Applications deployed without errors

#### ✅ **Expected Outputs**
- **Test Coverage Reports**: Available in Codecov
- **Security Scan Results**: Visible in GitHub Security tab
- **SonarQube Analysis**: Quality metrics updated
- **Docker Images**: Available in GitHub Container Registry
- **Deployment Logs**: Successful migration and cache clearing

### 5. **Common Issues & Solutions**

#### ❌ **Test Failures**
```bash
# If PHP tests fail:
- Check database migrations
- Verify .env.example completeness
- Check service dependencies (MySQL, Redis)

# If Python tests fail:
- Verify requirements.txt
- Check Python version compatibility
- Validate AI service endpoints
```

#### ❌ **Docker Build Failures**
```bash
# If Docker build fails:
- Check Dockerfile syntax
- Verify base image availability
- Check build context and .dockerignore
- Validate multi-stage build steps
```

#### ❌ **Deployment Failures**
```bash
# If deployment fails:
- Verify server SSH keys in secrets
- Check server disk space and resources
- Validate docker-compose.prod.yml
- Check database migration issues
```

### 6. **Secrets Configuration Required**

#### **Required GitHub Secrets:**
```
SONAR_TOKEN=your_sonar_token
SONAR_HOST_URL=your_sonar_url
STAGING_HOST=staging.server.com
STAGING_USERNAME=deploy_user
STAGING_SSH_KEY=-----BEGIN PRIVATE KEY-----
PRODUCTION_HOST=production.server.com
PRODUCTION_USERNAME=deploy_user
PRODUCTION_SSH_KEY=-----BEGIN PRIVATE KEY-----
PRODUCTION_URL=https://tunivert.com
SLACK_WEBHOOK_URL=https://hooks.slack.com/...
```

### 7. **Testing Commands Checklist**

#### **Before Each Push:**
```powershell
# 1. Local tests
php artisan test
cd python && pytest

# 2. Docker verification  
docker-compose up --build -d
docker-compose exec app php artisan test
docker-compose down

# 3. Code quality
.\run-sonar-analysis.ps1

# 4. Security check
# Run Trivy or similar locally

# 5. Push and monitor
git add .
git commit -m "feat: new feature with tests"
git push origin main
gh run watch
```

### 8. **Success Verification Steps**

#### **After Push - Check These:**

1. **GitHub Actions Dashboard**: All workflows green ✅
2. **Codecov Reports**: Coverage maintained/improved
3. **SonarQube Dashboard**: Quality gate passed
4. **GitHub Security**: No new vulnerabilities
5. **Container Registry**: New images available
6. **Staging Environment**: Application running properly
7. **Production Environment**: (if main branch) Deployed successfully
8. **Monitoring**: Prometheus/Grafana showing healthy metrics

### 9. **Performance Testing Integration**

```powershell
# Add performance tests to your workflow
# Create tests/Performance/LoadTest.php

# Monitor metrics during CI/CD
curl -s http://localhost:8000/metrics | findstr "tunivert_"

# Verify after deployment
curl -s https://your-staging.com/metrics
```

### 10. **Rollback Strategy**

```powershell
# If deployment fails, rollback:
git revert HEAD
git push origin main

# Or use GitHub CLI
gh run cancel [RUN_ID]
gh workflow run ci-cd.yml --ref previous-working-commit
```

## 🚀 Quick Test Commands

```powershell
# Complete local test suite
php artisan test && cd python && pytest && cd .. && docker-compose up -d && sleep 30 && curl -f http://localhost:8000/health && docker-compose down

# Check GitHub Actions status
gh run list --limit 5

# View latest run logs
gh run view --log

# Trigger manual workflow
gh workflow run ci-cd.yml --ref main
```

---

## 📊 **Expected GitHub Actions Timeline**

| Job | Duration | Success Criteria |
|-----|----------|-----------------|
| Security Scan | ~2-3 min | No critical vulnerabilities |
| PHP Tests | ~3-5 min | All tests pass, >80% coverage |
| Python Tests | ~1-2 min | All AI tests pass |
| SonarQube | ~2-3 min | Quality gate passes |
| Docker Build | ~5-8 min | Images built and pushed |
| Deploy Staging | ~3-5 min | Application accessible |
| Deploy Production | ~3-5 min | Production deployment successful |

**Total Pipeline Duration**: ~15-25 minutes

---

## ✅ **Success Confirmation Checklist**

- [ ] All workflow jobs complete successfully
- [ ] Test coverage reports generated  
- [ ] Security scans pass without critical issues
- [ ] SonarQube quality gate passes
- [ ] Docker images pushed to registry
- [ ] Staging deployment successful
- [ ] Production deployment (if main branch) successful
- [ ] Application health checks pass
- [ ] Monitoring metrics show healthy status
- [ ] No rollbacks required

Your GitHub Actions are working properly when all these indicators show green! 🎉