# Task Management API

A robust RESTful API built with Symfony 7 for managing tasks and users with JWT authentication and comprehensive CRUD operations.

## Tech Stack

### Backend
- **Framework**: Symfony 7.4
- **PHP**: 8.2+
- **Database**: MySQL 8.0
- **ORM**: Doctrine
- **Authentication**: JWT (JSON Web Tokens)

### Frontend (Planned)
- **Framework**: React JS
- **State Management**: Redux
- **HTTP Client**: Axios
- **UI**: Material-UI or Tailwind CSS

## Features

### Authentication System
- **User Registration** - Create new user accounts
- **User Login** - Secure authentication with JWT tokens
- **Token Validation** - Verify and refresh tokens
- **Logout** - Secure token revocation
- **Protected Routes** - Authentication required for sensitive operations

### User Management
- **Create Users** - Add new users to the system
- **Get All Users** - List all users with pagination
- **Get Single User** - Retrieve specific user details
- **Update Users** - Modify user information
- **Delete Users** - Remove users from system
- **Profile Management** - Users can update their own profiles

### Task Management
- **Create Tasks** - Add new tasks assigned to users
- **Get All Tasks** - List all tasks with user information
- **Get Single Task** - Retrieve detailed task information with assigned user
- **Update Tasks** - Modify task details and reassign to different users
- **Delete Tasks** - Remove tasks from system
- **User-Task Relationship** - Tasks are linked to users with foreign key constraints

## API Documentation

### Base URL
```
http://localhost:8000/api
```

### Authentication Endpoints

#### Login
```http
POST /api/auth/login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}
```

#### Register
```http
POST /api/auth/register
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
}
```

#### Validate Token
```http
POST /api/auth/validate
Content-Type: application/json

{
    "token": "your-jwt-token"
}
```

#### Logout
```http
POST /api/auth/logout
Content-Type: application/json

{
    "token": "your-jwt-token"
}
```

### User Management Endpoints

#### Get All Users
```http
GET /api/users
```

#### Get Single User
```http
GET /api/users/{id}
```

#### Create User
```http
POST /api/users
Content-Type: application/json

{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "role": "user",
    "status": 1
}
```

#### Update User
```http
PUT /api/users/{id}
Content-Type: application/json

{
    "name": "John Updated",
    "email": "john.updated@example.com",
    "role": "manager",
    "status": 1
}
```

#### Delete User
```http
DELETE /api/users/{id}
```

#### Get User Profile (Protected)
```http
GET /api/users/profile
Authorization: Bearer {token}
```

#### Update User Profile (Protected)
```http
PUT /api/users/profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "John Updated",
    "email": "john.updated@example.com"
}
```

### Task Management Endpoints

#### Get All Tasks
```http
GET /api/tasks
```

Response:
```json
{
    "tasks": [
        {
            "id": 1,
            "task": "Task description here",
            "user_id": 1,
            "user": {
                "id": 1,
                "name": "John Doe",
                "email": "john@example.com",
                "role": "user"
            },
            "created_at": "2026-04-22 10:30:00",
            "updated_at": "2026-04-22 10:30:00"
        }
    ],
    "total": 1
}
```

#### Get Single Task
```http
GET /api/tasks/{id}
```

#### Create Task
```http
POST /api/tasks
Content-Type: application/json

{
    "user_id": 1,
    "task": "Task description with at least 10 characters required"
}
```

Response:
```json
{
    "message": "Task created successfully",
    "task": {
        "id": 1,
        "task": "Task description with at least 10 characters required",
        "user_id": 1,
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "user"
        },
        "created_at": "2026-04-22 10:30:00",
        "updated_at": "2026-04-22 10:30:00"
    }
}
```

#### Update Task
```http
PUT /api/tasks/{id}
Content-Type: application/json

{
    "user_id": 1,
    "task": "Updated task description with at least 10 characters"
}
```

#### Delete Task
```http
DELETE /api/tasks/{id}
```

## Installation

### Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Symfony CLI (optional)

### Setup Instructions

1. **Clone the repository**
```bash
git clone <repository-url>
cd task-management-application
```

2. **Install dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
# Edit .env with your database credentials
```

4. **Create database**
```bash
php bin/console doctrine:database:create
```

5. **Run migrations**
```bash
php bin/console doctrine:migrations:migrate
```

6. **Clear cache**
```bash
php bin/console cache:clear
```

7. **Start development server**
```bash
php -S localhost:8000 -t public
```

## Environment Variables

Create a `.env` file with the following variables:

```env
# Database Configuration
DATABASE_URL="mysql://username:password@127.0.0.1:3306/task_management?serverVersion=8.0"

# Symfony Configuration
APP_ENV=dev
APP_SECRET=your-secret-key-here

# JWT Configuration
JWT_SECRET=your-jwt-secret-key
TOKEN_EXPIRATION=3600
```

## Testing

### Run Unit Tests
```bash
php bin/phpunit
```

### Run API Tests
```bash
# Test authentication
php test_auth_api.php

# Test user endpoints
php test_user_api.php

# Test task endpoints
php test_task_api.php
```

### Postman Collection

Import the provided Postman collection for easy API testing:

1. Open Postman
2. Click "Import"
3. Select the `API_DOCUMENTATION.md` file or copy the collection JSON
4. Set up environment variables:
   - `BASE_URL`: `http://localhost:8000/api`
   - `AUTH_TOKEN`: Will be auto-set after login

## Project Structure

```
task-management-application/
|
|-- src/
|   |-- Controller/
|   |   |-- AuthController.php
|   |   |-- UserController.php
|   |   |-- TaskController.php
|   |-- Entity/
|   |   |-- Users.php
|   |   |-- Tasks.php
|   |-- Repository/
|   |   |-- UsersRepository.php
|   |   |-- TasksRepository.php
|   |-- Service/
|   |   |-- AuthService.php
|   |   |-- UserService.php
|   |   |-- TaskService.php
|   |   |-- TokenManager.php
|   |-- Middleware/
|   |   |-- AuthMiddleware.php
|
|-- tests/
|   |-- Unit/
|   |   |-- Service/
|   |   |   |-- UserServiceTest.php
|   |   |   |-- TaskServiceTest.php
|   |-- Integration/
|   |   |-- Controller/
|   |   |   |-- UserControllerTest.php
|
|-- config/
|   |-- packages/
|   |   |-- doctrine.yaml
|   |   |-- framework.yaml
|   |-- routes.yaml
|   |-- services.yaml
|
|-- public/
|   |-- index.php
|
|-- API_DOCUMENTATION.md
|-- README.md
|-- composer.json
|-- .env.example
```

## Database Schema

### Tables

#### Users Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL (admin, manager, user),
    status SMALLINT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL
)
```

#### Tasks Table
```sql
CREATE TABLE tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    task LONGTEXT NOT NULL,
    user_id INT NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)
```

### Relationships
- **One-to-Many**: One User can have multiple Tasks
- **Foreign Key**: Tasks.user_id references Users.id
- **Cascade Delete**: Deleting a user automatically deletes all their tasks

## Security Features

### Authentication & Authorization
- **JWT Token Authentication** - Stateless authentication with JSON Web Tokens
- **Password Hashing** - Secure password storage using bcrypt
- **Token Expiration** - Configurable token lifetime
- **Role-Based Access Control** - Admin, Manager, and User roles
- **Protected Routes** - Authentication required for sensitive operations

### Data Validation
- **Input Validation** - Symfony Validator component for request validation
- **Entity Constraints** - Database-level constraints for data integrity
- **Error Handling** - Comprehensive error responses with proper HTTP status codes

### Security Headers
```http
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
```

## API Response Format

### Success Response
```json
{
    "message": "Operation successful",
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

### Error Response
```json
{
    "error": "Validation failed",
    "errors": {
        "email": "Invalid email format",
        "password": "Password must be at least 6 characters"
    }
}
```

### Pagination Response
```json
{
    "data": [...],
    "pagination": {
        "total": 100,
        "page": 1,
        "limit": 20,
        "pages": 5
    }
}
```

## Development Workflow

### Daily Development Commands
```bash
# Start server
php -S localhost:8000 -t public

# Clear cache
php bin/console cache:clear

# Run tests
php bin/phpunit

# Check routes
php bin/console debug:router

# Validate schema
php bin/console doctrine:schema:validate
```

### Code Quality
```bash
# Run PHPStan for static analysis
vendor/bin/phpstan analyse

# Run PHP CS Fixer
vendor/bin/php-cs-fixer fix

# Check for security issues
composer audit
```

## Deployment

### Production Setup
```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Clear production cache
php bin/console cache:clear --env=prod

# Run production migrations
php bin/console doctrine:migrations:migrate --env=prod

# Warm up production cache
php bin/console cache:warmup --env=prod
```

### Docker Deployment
```dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application files
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Code Standards
- Follow PSR-12 coding standards
- Write unit tests for new features
- Update documentation for API changes
- Use meaningful commit messages

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support and questions:
- Create an issue in the GitHub repository
- Check the [API Documentation](API_DOCUMENTATION.md)
- Review the test files for usage examples

## Roadmap

### Phase 1: Core API (Complete)
- [x] User authentication system
- [x] User management CRUD
- [x] Task management CRUD
- [x] JWT token authentication
- [x] API documentation

### Phase 2: Enhanced Features
- [ ] Task assignments and notifications
- [ ] File uploads for tasks
- [ ] Advanced filtering and search
- [ ] API rate limiting
- [ ] Comprehensive logging

### Phase 3: Frontend Integration
- [ ] React frontend application
- [ ] Redux state management
- [ ] Real-time updates with WebSockets
- [ ] Progressive Web App (PWA) features

### Phase 4: Advanced Features
- [ ] Multi-tenant support
- [ ] Advanced permissions system
- [ ] Email notifications
- [ ] Task templates and workflows
- [ ] Analytics and reporting

## Performance

### Optimization Features
- **Database Indexing** - Optimized queries with proper indexes
- **Caching Strategy** - Redis integration for frequently accessed data
- **API Response Compression** - Gzip compression for faster responses
- **Connection Pooling** - Efficient database connection management

### Monitoring
- **Health Check Endpoints** - Monitor application status
- **Performance Metrics** - Track API response times
- **Error Logging** - Comprehensive error tracking
- **Database Query Logging** - Monitor query performance

---

**Built with Symfony 7, PHP 8.2, and MySQL** | **API Documentation**: [API_DOCUMENTATION.md](API_DOCUMENTATION.md)
