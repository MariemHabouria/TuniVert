# How to Test GitHub Actions for TuniVert

## 🚀 Quick GitHub Actions Test

To know if your GitHub Actions work properly, follow these steps:

### 1. **Immediate Test - Push a Small Change**

```powershell
# Make a small change to trigger the workflow
echo "# GitHub Actions Test - $(Get-Date)" >> README.md

# Commit and push
git add README.md
git commit -m "test: trigger GitHub Actions workflow"
git push origin main
```

### 2. **Monitor the Workflow**

Visit your GitHub repository and check:
- Go to: `https://github.com/MariemHabouria/TuniVert/actions`
- Look for your recent commit
- Watch each step complete:

#### ✅ **Expected Workflow Steps:**
1. **Security Scan** (2-3 minutes)
   - Trivy vulnerability scanning
   - Results uploaded to Security tab

2. **PHP Tests** (3-5 minutes) 
   - PHP 8.3 environment setup
   - Composer dependencies installation
   - Laravel test execution with coverage
   - Coverage upload to Codecov

3. **Python Tests** (1-2 minutes)
   - Python 3.11 setup
   - AI service testing
   - Coverage reporting

4. **SonarQube Analysis** (2-3 minutes)
   - Code quality analysis
   - Security vulnerability scanning
   - Technical debt assessment

5. **Docker Build & Push** (5-8 minutes)
   - Laravel application image
   - Python AI service image
   - Push to GitHub Container Registry

6. **Deployment** (if main branch - 3-5 minutes)
   - Deploy to staging/production
   - Health checks

### 3. **Success Indicators**

#### ✅ **Green Checkmarks Mean:**
- All tests pass
- No security vulnerabilities found
- Code quality meets standards
- Docker images built successfully
- Deployment completed without errors

#### ❌ **Red X Marks Mean Issues With:**
- Test failures
- Security vulnerabilities
- Code quality problems
- Build failures
- Deployment errors

### 4. **Real-Time Monitoring Commands**

If you have GitHub CLI installed:

```powershell
# Install GitHub CLI (if not installed)
winget install GitHub.cli

# Login
gh auth login

# Monitor workflows
gh run list
gh run watch  # Watch current runs in real-time
```

### 5. **Current Status Check**

Based on your current setup, here's what should happen when you push:

#### **Your CI/CD Pipeline Will:**
1. ✅ Run security scans on your code
2. ✅ Test Laravel application (PHP 8.2, 8.3, 8.4)
3. ✅ Test Python AI service
4. ✅ Analyze code quality with SonarQube
5. ✅ Build Docker images for both services
6. ✅ Deploy to staging (if develop branch) or production (if main branch)
7. ✅ Send notifications to Slack

### 6. **Expected Timeline**

| Step | Duration | What It Does |
|------|----------|--------------|
| Security Scan | 2-3 min | Checks for vulnerabilities |
| PHP Tests | 3-5 min | Runs Laravel tests |
| Python Tests | 1-2 min | Tests AI service |
| SonarQube | 2-3 min | Code quality analysis |
| Docker Build | 5-8 min | Builds container images |
| Deploy | 3-5 min | Deploys to servers |

**Total Time: ~15-25 minutes**

### 7. **What to Look For**

#### **✅ Success Signs:**
- All workflow steps show green checkmarks
- Test coverage reports appear in Codecov
- SonarQube quality gate passes
- Docker images appear in GitHub Container Registry
- Application deployed and accessible
- Monitoring shows healthy metrics

#### **⚠️ Warning Signs:**
- Yellow warnings (non-critical issues)
- Skipped steps (missing dependencies/secrets)
- Slow performance (longer than expected times)

#### **❌ Failure Signs:**
- Red X marks on any step
- Test failures
- Security vulnerabilities
- Build errors
- Deployment failures

### 8. **Quick Fix Commands**

If something fails, try these:

```powershell
# Fix common issues locally first
composer install
composer dump-autoload
php artisan config:clear
php artisan cache:clear

# Run tests locally to verify
php artisan test --env=testing

# Check Docker builds locally
docker build -t test-app .
```

### 9. **GitHub Actions Status Dashboard**

Your workflow file (`.github/workflows/ci-cd.yml`) includes:
- ✅ Multi-PHP version testing (8.2, 8.3, 8.4)
- ✅ Security scanning with Trivy
- ✅ SonarQube integration
- ✅ Docker multi-stage builds
- ✅ Automated deployment
- ✅ Slack notifications

## 🎯 **Quick Test Right Now**

Want to test immediately? Run this:

```powershell
# Simple test push
echo "Test commit at $(Get-Date -Format 'yyyy-MM-dd HH:mm:ss')" >> .github-actions-test.txt
git add .github-actions-test.txt
git commit -m "test: verify GitHub Actions pipeline"
git push origin main
```

Then go to `https://github.com/MariemHabouria/TuniVert/actions` and watch the magic happen! 🚀

**Your GitHub Actions are working if all steps complete with green checkmarks within 15-25 minutes.**