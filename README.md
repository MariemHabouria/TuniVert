# 🌱 TuniVert - Environmental Platform for Tunisia

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Docker](https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)

**TuniVert** is a comprehensive environmental platform designed to promote environmental awareness and action in Tunisia. The platform connects environmentally conscious individuals, organizations, and communities through donations, events, challenges, and educational resources.

## 🌟 Features

### 🎯 Core Functionality
- **Environmental Events Management** - Create, manage, and participate in eco-friendly events
- **Donation System** - Multiple payment gateways (Stripe, PayPal, Paymee, Bank Transfer)
- **Environmental Challenges** - Gamified challenges to encourage sustainable behaviors
- **Educational Resources** - Training materials and environmental education content
- **Community Forums** - Discussion spaces for environmental topics and alerts

### 💰 Payment Integration
- **Stripe** - International card payments
- **PayPal** - Global payment processing
- **Paymee (e-DINAR)** - Tunisian local payment method
- **Bank Transfer** - Traditional banking integration
- **QR Code Verification** - Secure donation verification system

### 🎮 Gamification System
- **Points & Badges** - Reward system for environmental actions
- **Leaderboards** - Community engagement through friendly competition
- **Achievement Tracking** - Progress monitoring for users

### 🤖 AI-Powered Features
- **Smart Donation Suggestions** - AI-powered donation amount recommendations
- **Chatbot Support** - OpenAI-powered assistance for users
- **Analytics & Insights** - Data-driven environmental impact tracking

### 🔧 Admin Dashboard
- **Comprehensive Analytics** - Real-time statistics and charts
- **Event Management** - Full CRUD operations for events
- **User Management** - Role-based access control (Admin, Association, User)
- **Challenge Administration** - Create and manage environmental challenges
- **Forum Moderation** - Content management and community oversight

## 🚀 Getting Started

### Prerequisites
- PHP 8.3+
- Composer
- Node.js & NPM
- MySQL 8.0+
- Docker (optional but recommended)

### Installation

#### Option 1: Docker Setup (Recommended)
```bash
# Clone the repository
git clone https://github.com/MariemHabouria/TuniVert.git
cd TuniVert

# Start the application with Docker
docker-compose up -d

# Access the application
# Web: http://localhost:8080
# Admin: http://localhost:8080/admin
```

#### Option 2: Local Development Setup
```bash
# Clone the repository
git clone https://github.com/MariemHabouria/TuniVert.git
cd TuniVert

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Configure your database in .env file
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=tunivert
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Run database migrations and seeders
php artisan migrate --seed

# Build frontend assets
npm run build

# Start the development server
php artisan serve
```

### Database Setup
For a complete database setup with sample data, use our provided scripts:

**Windows:**
```powershell
.\seed-database.ps1
```

**Linux/Mac:**
```bash
chmod +x seed-database.sh
./seed-database.sh
```

## 🔧 Configuration

### Payment Gateways
Configure your payment providers in `.env`:

```env
# Stripe
STRIPE_KEY=your_stripe_public_key
STRIPE_SECRET=your_stripe_secret_key
STRIPE_CURRENCY=USD

# PayPal
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_SECRET=your_paypal_secret
PAYPAL_MODE=sandbox  # or live
PAYPAL_CURRENCY=USD

# Paymee (Tunisia)
PAYMEE_API_KEY=your_paymee_api_key
PAYMEE_MODE=test  # or live
PAYMEE_CURRENCY=TND
```

### AI Services
Enable AI features by configuring:

```env
# OpenAI for Chatbot
OPENAI_API_KEY=your_openai_api_key

# Donation AI Service (Optional)
DONATION_AI_URL=http://127.0.0.1:8085
```

### Email Configuration
```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@tunivert.tn
MAIL_FROM_NAME="TuniVert"
```

## 🎯 Usage

### For Users
1. **Register/Login** - Create an account or sign in
2. **Explore Events** - Browse and join environmental events
3. **Make Donations** - Support environmental causes
4. **Participate in Challenges** - Earn points and badges
5. **Access Resources** - Learn about environmental topics

### For Associations
1. **Create Events** - Organize environmental activities
2. **Manage Participants** - Track event attendance
3. **Monitor Donations** - View funding support
4. **Engage Community** - Create challenges and content

### For Administrators
1. **Dashboard Analytics** - Monitor platform statistics
2. **User Management** - Manage user roles and permissions
3. **Content Moderation** - Oversee forums and content
4. **System Configuration** - Configure platform settings

## 🏗️ Architecture

### Backend (Laravel)
- **MVC Architecture** - Clean separation of concerns
- **Service Layer** - Business logic abstraction
- **Repository Pattern** - Data access abstraction
- **Event-Driven** - Decoupled system components

### Frontend
- **Blade Templates** - Server-side rendering
- **Bootstrap 5** - Responsive UI framework
- **Chart.js** - Interactive data visualizations
- **Alpine.js** - Lightweight JavaScript framework

### Database
- **MySQL** - Primary database
- **Redis** - Caching and sessions
- **Migrations** - Version-controlled schema changes
- **Seeders** - Sample data for development

### External Services
- **Payment Gateways** - Multiple payment providers
- **Email Service** - Transactional emails
- **AI Services** - Optional AI enhancements
- **File Storage** - Asset management

## 🔒 Security Features

- **Role-Based Access Control** - Fine-grained permissions
- **CSRF Protection** - Cross-site request forgery prevention
- **Input Validation** - Comprehensive data validation
- **Secure Payments** - PCI-compliant payment processing
- **QR Code Verification** - Donation authenticity verification

## 📊 Monitoring & Analytics

### Built-in Analytics
- Real-time donation tracking
- Event participation metrics
- User engagement statistics
- Environmental impact measurements

### External Monitoring (Optional)
- **Prometheus** - Metrics collection
- **Grafana** - Visualization dashboards
- **Docker Health Checks** - Service monitoring

## 🤝 Contributing

We welcome contributions to TuniVert! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Development Guidelines
- Follow PSR-12 coding standards
- Write tests for new features
- Update documentation as needed
- Use conventional commit messages

## 📝 API Documentation

The platform provides RESTful APIs for:
- Event management
- Donation processing
- User management
- Challenge participation

API documentation is available at `/api/documentation` when in development mode.

## 🧪 Testing

Run the test suite:
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 📱 Mobile Support

TuniVert is fully responsive and optimized for:
- ✅ Desktop browsers
- ✅ Tablet devices
- ✅ Mobile phones
- ✅ Progressive Web App capabilities

## 🌍 Localization

Currently supported languages:
- 🇫🇷 French (Primary)
- 🇹🇳 Arabic (Tunisia dialect support)

## 📞 Support

For support and questions:
- **Email**: support@tunivert.tn
- **Documentation**: [docs.tunivert.tn](https://docs.tunivert.tn)
- **Issues**: [GitHub Issues](https://github.com/MariemHabouria/TuniVert/issues)

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- **Laravel Community** - Excellent framework and ecosystem
- **Environmental Organizations** - Inspiration and guidance
- **Open Source Contributors** - Various packages and tools
- **Tunisia Tech Community** - Local support and collaboration

## 🎯 Roadmap

### Upcoming Features
- [ ] Mobile application (React Native)
- [ ] Advanced AI analytics
- [ ] Blockchain integration for transparency
- [ ] Carbon footprint calculator
- [ ] International expansion
- [ ] Multi-language support
- [ ] Social media integration
- [ ] Real-time notifications

---

**Made with 💚 for the environment in Tunisia**

*TuniVert - Together for a greener Tunisia* 🇹🇳🌱