# Blog API

A modern, robust Laravel-based REST API for a blog application with JWT authentication, comprehensive CRUD operations, and clean architecture principles.

## 🚀 Features

- **JWT Authentication** - Secure user authentication with JWT tokens
- **User Management** - Registration, login, and Google OAuth integration
- **Post CRUD Operations** - Complete blog post management
- **File Upload** - Image upload support for post covers
- **Soft Deletes** - Safe deletion with restore capability
- **Automatic Slug Generation** - SEO-friendly URLs
- **Comprehensive Testing** - 16 tests with 105 assertions
- **Clean Architecture** - Service layer, resources, and proper separation of concerns

## 📋 API Documentation

### Interactive API Documentation
Access the complete interactive API documentation at: **`/docs/api`**

The documentation includes:
- **Interactive API Explorer** - Test endpoints directly from the browser
- **Request/Response Examples** - Complete examples for all endpoints
- **Authentication Guide** - JWT token usage and examples
- **Error Handling** - Detailed error responses and status codes

### API Endpoints

### Authentication
```
POST /api/v1/auth/register     - User registration
POST /api/v1/auth/login        - User login
POST /api/v1/auth/google       - Google OAuth login
```

### Posts (Protected Routes)
```
GET    /api/v1/posts           - Get all posts (paginated)
GET    /api/v1/posts/{id}      - Get single post
POST   /api/v1/posts           - Create new post
PUT    /api/v1/posts/{id}      - Update post
DELETE /api/v1/posts/{id}      - Delete post (soft delete)
```

## 🛠️ Tech Stack

- **Framework:** Laravel 12.x
- **Database:** MySQL 8.0
- **Authentication:** JWT (tymon/jwt-auth)
- **File Storage:** Laravel Storage
- **Testing:** PHPUnit
- **Container:** Docker with Laravel Sail

## 📦 Installation

### Prerequisites
- Docker & Docker Compose
- PHP 8.2+
- Composer

### Setup

1. **Clone the repository**
```bash
git clone <repository-url>
cd blog-api
```

2. **Install dependencies**
```bash
composer install
```

3. **Environment setup**
```bash
cp .env.example .env
```

4. **Start the application with Sail**
```bash
./vendor/bin/sail up -d
```

5. **Run migrations**
```bash
./vendor/bin/sail artisan migrate
```

6. **Generate application key**
```bash
./vendor/bin/sail artisan key:generate
```

7. **Generate JWT secret**
```bash
./vendor/bin/sail artisan jwt:secret
```

## 🧪 Testing

Run the complete test suite:
```bash
./vendor/bin/sail test
```

Run specific test files:
```bash
./vendor/bin/sail test tests/Feature/PostTest.php
./vendor/bin/sail test tests/Feature/LoginTest.php
./vendor/bin/sail test tests/Feature/RegisterTest.php
```

### Test Coverage
- **16 tests** with **105 assertions**
- **Authentication tests** - Login, registration, Google OAuth
- **Post CRUD tests** - All CRUD operations with validation
- **Authorization tests** - Protected routes and permissions
- **Validation tests** - Input validation and error handling

## 📝 API Documentation

### Authentication

#### Register User
```http
POST /api/v1/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
}
```

#### Login User
```http
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "password"
}
```

### Posts

#### Create Post
```http
POST /api/v1/posts
Authorization: Bearer {jwt_token}
Content-Type: multipart/form-data

{
    "title": "My Awesome Post",
    "body": "This is the content of my post...",
    "cover_image": [file] // optional
}
```

#### Get All Posts
```http
GET /api/v1/posts
Authorization: Bearer {jwt_token}
```

#### Update Post
```http
PUT /api/v1/posts/{id}
Authorization: Bearer {jwt_token}
Content-Type: application/json

{
    "title": "Updated Title",
    "body": "Updated content..."
}
```

## 🏗️ Architecture

### Clean Architecture Implementation

```
app/
├── Http/
│   ├── Controllers/Api/     # API Controllers
│   ├── Requests/            # Form Request Validation
│   └── Resources/           # API Resources
├── Models/                  # Eloquent Models
├── Services/               # Business Logic Layer
└── Console/Commands/       # Artisan Commands
```

### Key Components

- **Controllers** - Handle HTTP requests and responses
- **Services** - Business logic separation
- **Resources** - API response formatting
- **Requests** - Input validation
- **Models** - Database relationships and logic

## 🔧 Configuration

### Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=blog_api
DB_USERNAME=sail
DB_PASSWORD=password

# JWT
JWT_SECRET=your-jwt-secret
JWT_TTL=60

# Storage
FILESYSTEM_DISK=public
```

### Docker Services

- **Laravel App** - PHP 8.4 with Laravel
- **MySQL** - Database server
- **Redis** - Cache and session storage (optional)

## 🚀 Deployment

### Production Setup

1. **Environment Configuration**
```bash
# Set production environment
APP_ENV=production
APP_DEBUG=false
```

2. **Optimize Application**
```bash
./vendor/bin/sail artisan config:cache
./vendor/bin/sail artisan route:cache
./vendor/bin/sail artisan view:cache
```

3. **Database Migration**
```bash
./vendor/bin/sail artisan migrate --force
```

## 📊 API Response Format

### Success Response
```json
{
    "success": true,
    "message": "Operation successful",
    "data": {
        // Response data
    }
}
```

### Error Response
```json
{
    "success": false,
    "message": "Error message",
    "errors": {
        // Validation errors
    }
}
```

## 🔒 Security Features

- **JWT Authentication** - Secure token-based auth
- **Input Validation** - Comprehensive request validation
- **CSRF Protection** - Cross-site request forgery protection
- **SQL Injection Prevention** - Eloquent ORM protection
- **File Upload Security** - Type and size validation

## 🧩 Development

### Code Quality

- **PSR-12 Coding Standards** - Follows PHP standards
- **Type Hints** - Full type declarations
- **Documentation** - Comprehensive code comments
- **Testing** - High test coverage

### Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new features
5. Ensure all tests pass
6. Submit a pull request

## 📈 Performance

- **Database Optimization** - Efficient queries and indexing
- **Caching** - Route and config caching
- **File Storage** - Optimized file handling
- **Pagination** - Efficient data loading

## 🐛 Troubleshooting

### Common Issues

1. **JWT Token Issues**
   - Ensure JWT_SECRET is set
   - Check token expiration

2. **Database Connection**
   - Verify MySQL service is running
   - Check database credentials

3. **File Upload Issues**
   - Ensure storage directory is writable
   - Check file size limits

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 👥 Authors

- **Wahid** - *Initial work* - [GitHub](https://github.com/wahid)

## 🙏 Acknowledgments

- Laravel Framework
- JWT Auth package
- Laravel Sail
- PHPUnit testing framework

---

**Built with ❤️ using Laravel**