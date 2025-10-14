# Event Connect API

A comprehensive event management system built with Laravel 12.33.0, featuring user authentication, event management, participation tracking, feedback system, and certificate generation.

## 🚀 Features

### Authentication & Profile Management
- User registration and login
- Password reset functionality
- Profile management (view/edit)
- Organizer status management

### Event Management
- Create, read, update, delete events
- Event search and filtering
- Category management
- QR code generation for events
- Image upload support

### Event Participation
- Join/cancel event participation
- QR-based attendance tracking
- Payment processing (ready for integration)
- Participation history

### Feedback & Certificates
- Submit event feedback (required for certificates)
- PDF certificate generation
- Rating system (1-5 stars)
- Certificate download

### Notifications
- Event reminders (1 day & 1 hour before)
- System notifications
- Read/unread status tracking

## 🛠️ Technology Stack

- **Backend:** Laravel 12.33.0
- **Database:** MySQL
- **Authentication:** Laravel Sanctum
- **QR Code:** SimpleSoftwareIO/simple-qrcode
- **PDF Generation:** barryvdh/laravel-dompdf
- **Frontend Documentation:** Tailwind CSS

## 📋 Requirements

- PHP 8.3+
- MySQL 5.7+
- Composer
- Laravel 12.33.0

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd event_connect
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database configuration**
   Update `.env` file with your MySQL credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=8889
   DB_DATABASE=event_connect
   DB_USERNAME=root
   DB_PASSWORD=root
   ```

5. **Run migrations and seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Start the server**
   ```bash
   php artisan serve --port=8003
   ```

## 📚 API Documentation

Visit the interactive API documentation at:
```
http://127.0.0.1:8003/api-docs
```

## 🔗 API Endpoints

### Base URL
```
http://127.0.0.1:8003/api
```

### Authentication
- `POST /auth/register` - Register new user
- `POST /auth/login` - User login
- `POST /auth/logout` - User logout
- `GET /auth/me` - Get current user

### Profile Management
- `GET /profile` - Get user profile
- `PUT /profile` - Update profile
- `POST /profile/change-password` - Change password
- `POST /profile/update-organizer-status` - Update organizer status

### Events
- `GET /events` - List all events (homepage)
- `GET /events/{id}` - Get event details
- `POST /events` - Create event (organizer only)
- `PUT /events/{id}` - Update event (organizer only)
- `DELETE /events/{id}` - Delete event (organizer only)
- `GET /events/my-events` - Get user's created events
- `GET /events/participating` - Get user's participating events

### Event Participation
- `POST /participants/join/{event_id}` - Join event
- `POST /participants/cancel/{event_id}` - Cancel participation
- `POST /participants/attendance` - Mark attendance via QR
- `GET /participants/my-participations` - Get user's participations

### Feedback & Certificates
- `POST /feedbacks/{event_id}` - Submit feedback
- `GET /feedbacks/my-feedbacks` - Get user's feedbacks
- `GET /feedbacks/certificate/{event_id}/download` - Download certificate
- `GET /feedbacks/certificate/{event_id}/url` - Get certificate URL

### Notifications
- `GET /notifications` - Get user notifications
- `POST /notifications/{id}/read` - Mark notification as read
- `POST /notifications/mark-all-read` - Mark all as read
- `GET /notifications/unread-count` - Get unread count

### Categories
- `GET /categories` - Get all categories
- `POST /categories` - Create category (admin only)
- `PUT /categories/{id}` - Update category (admin only)
- `DELETE /categories/{id}` - Delete category (admin only)

## 🔐 Authentication

The API uses Laravel Sanctum for authentication. Include the Bearer token in the Authorization header:

```bash
Authorization: Bearer {your-token-here}
```

## 📊 Database Schema

### Tables
- `users` - User accounts and profiles
- `categories` - Event categories
- `events` - Event information
- `event_participants` - User-event relationships
- `feedbacks` - Event feedback and certificates
- `notifications` - System notifications
- `personal_access_tokens` - API authentication tokens

## 🧪 Testing

Test the API using curl or any HTTP client:

```bash
# Register a new user
curl -X POST "http://127.0.0.1:8003/api/auth/register" \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'

# Login
curl -X POST "http://127.0.0.1:8003/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Get categories
curl -X GET "http://127.0.0.1:8003/api/categories"
```

## 📝 Sample Data

The seeder creates sample categories:
- Technology
- Business
- Education
- Health & Wellness
- Arts & Culture
- Sports

## 🔧 Configuration

### Environment Variables
```env
APP_NAME="Event Connect"
APP_ENV=local
APP_KEY=base64:your-app-key
APP_DEBUG=true
APP_URL=http://127.0.0.1:8003

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=event_connect
DB_USERNAME=root
DB_PASSWORD=root

SANCTUM_STATEFUL_DOMAINS=127.0.0.1:8003
```

## 📱 Features Overview

### For Regular Users
- Browse and search events
- Join events (free and paid)
- Mark attendance via QR code
- Submit feedback and get certificates
- Manage profile and notifications

### For Event Organizers
- Create and manage events
- Generate QR codes for attendance
- View participant lists
- Process payments
- Manage event categories

### For Administrators
- Manage event categories
- Monitor system activity
- User management

## 🚀 Deployment

1. Set up production environment
2. Configure database and environment variables
3. Run migrations and seeders
4. Set up web server (Apache/Nginx)
5. Configure SSL certificates
6. Set up monitoring and logging

## 📄 License

This project is licensed under the MIT License.

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📞 Support

For support and questions, please contact the development team or create an issue in the repository.

---

**Event Connect API** - Built with ❤️ using Laravel# event-connect
