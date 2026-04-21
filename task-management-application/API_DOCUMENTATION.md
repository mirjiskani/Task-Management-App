# Task Management API Documentation

## Base URL
```
http://localhost:8000/api
```

## Authentication API Endpoints

### 1. User Login
**POST** `/api/auth/login`

**Request Body:**
```json
{
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (200 OK):**
```json
{
    "message": "Login successful",
    "token": "a1b2c3d4e5f6789012345678901234567890abcd",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user"
    }
}
```

**Validation Errors (400 Bad Request):**
```json
{
    "error": "Email and password are required"
}
```

**Invalid Credentials (401 Unauthorized):**
```json
{
    "error": "Login failed"
}
```

### 2. User Registration
**POST** `/api/auth/register`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
}
```

**Response (201 Created):**
```json
{
    "message": "Registration successful",
    "token": "a1b2c3d4e5f6789012345678901234567890abcd",
    "user": {
        "id": 2,
        "name": "John Doe",
        "email": "john@example.com"
    }
}
```

**Validation Errors (400 Bad Request):**
```json
{
    "error": "Name, email, and password are required"
}
```

### 3. Token Validation
**POST** `/api/auth/validate`

**Request Body:**
```json
{
    "token": "a1b2c3d4e5f6789012345678901234567890abcd"
}
```

**Response (200 OK):**
```json
{
    "valid": true,
    "user": {
        "id": 1,
        "name": "Demo User",
        "email": "demo@example.com"
    }
}
```

**Invalid Token (400 Bad Request):**
```json
{
    "valid": false,
    "error": "Invalid token format"
}
```

### 4. User Logout
**POST** `/api/auth/logout`

**Request Body:**
```json
{
    "token": "a1b2c3d4e5f6789012345678901234567890abcd"
}
```

**Response (200 OK):**
```json
{
    "message": "Logout successful"
}
```

**Validation Errors (400 Bad Request):**
```json
{
    "error": "Token required"
}
```

## Users API Endpoints

### 5. Get User Profile
**GET** `/api/users/profile`

**Headers:**
```
Authorization: Bearer a1b2c3d4e5f6789012345678901234567890abcd
```

**Response (200 OK):**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "status": 1,
        "created_at": "2024-04-21 01:00:00",
        "updated_at": "2024-04-21 01:00:00"
    }
}
```

**Unauthorized (401 Unauthorized):**
```json
{
    "error": "Authentication token required"
}
```

### 6. Update User Profile
**PUT** `/api/users/profile`

**Headers:**
```
Authorization: Bearer a1b2c3d4e5f6789012345678901234567890abcd
```

**Request Body:**
```json
{
    "name": "John Updated",
    "email": "john.updated@example.com",
    "role": "manager"
}
```

**Response (200 OK):**
```json
{
    "message": "Profile updated successfully",
    "user": {
        "id": 1,
        "name": "John Updated",
        "email": "john.updated@example.com",
        "role": "manager",
        "updated_at": "2024-04-21 01:05:00"
    }
}
```

### 7. Create User
**POST** `/api/users`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "role": "user",
    "status": 1
}
```

**Response (201 Created):**
```json
{
    "message": "User created successfully",
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "status": 1,
        "created_at": "2024-04-21 01:00:00"
    }
}
```

**Validation Errors (400 Bad Request):**
```json
{
    "errors": {
        "name": "Name cannot be empty",
        "email": "Please enter a valid email address",
        "password": "Password must be at least 6 characters"
    }
}
```

**Conflict (409 Conflict):**
```json
{
    "error": "Email already exists"
}
```

### 8. Get All Users
**GET** `/api/users`

**Response (200 OK):**
```json
{
    "users": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "user",
            "status": 1,
            "created_at": "2024-04-21 01:00:00",
            "updated_at": "2024-04-21 01:00:00"
        }
    ],
    "total": 1
}
```

### 9. Get Single User
**GET** `/api/users/{id}`

**Response (200 OK):**
```json
{
    "user": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "status": 1,
        "created_at": "2024-04-21 01:00:00",
        "updated_at": "2024-04-21 01:00:00"
    }
}
```

**Not Found (404 Not Found):**
```json
{
    "error": "User not found"
}
```

### 10. Update User
**PUT** `/api/users/{id}`

**Request Body:**
```json
{
    "name": "John Updated",
    "email": "john.updated@example.com",
    "role": "manager",
    "status": 1
}
```

**Response (200 OK):**
```json
{
    "message": "User updated successfully",
    "user": {
        "id": 1,
        "name": "John Updated",
        "email": "john.updated@example.com",
        "role": "manager",
        "status": 1,
        "updated_at": "2024-04-21 01:05:00"
    }
}
```

### 11. Delete User
**DELETE** `/api/users/{id}`

**Response (200 OK):**
```json
{
    "message": "User deleted successfully",
    "user_id": 1
}
```

**Not Found (404 Not Found):**
```json
{
    "error": "User not found"
}
```

## Field Validations

### Authentication Fields
- **email**: Required, valid email format
- **password**: Required, minimum 6 characters
- **name**: Required for registration, 2-255 characters
- **token**: Required for logout and validation, 64-character hex string

### User Fields
- **name**: Required, 2-255 characters
- **email**: Required, valid email format, unique
- **password**: Required, minimum 6 characters (hashed automatically)
- **role**: Required, must be one of: 'admin', 'manager', 'user'
- **status**: Optional, integer (0 = inactive, 1 = active)

## HTTP Status Codes

- **200 OK**: Request successful
- **201 Created**: Resource created successfully
- **400 Bad Request**: Validation errors or invalid JSON
- **401 Unauthorized**: Authentication required or invalid token
- **404 Not Found**: Resource not found
- **409 Conflict**: Resource already exists (duplicate email)
- **500 Internal Server Error**: Server error occurred

## Authentication Flow

### 1. Login Flow
```bash
# Step 1: Login to get token
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "john@example.com", "password": "password123"}'

# Step 2: Use token for protected routes
curl -X GET http://localhost:8000/api/users/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Step 3: Logout when done
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Content-Type: application/json" \
  -d '{"token": "YOUR_TOKEN_HERE"}'
```

### 2. Registration Flow
```bash
# Step 1: Register new user
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'

# Step 2: Use returned token for protected routes
curl -X GET http://localhost:8000/api/users/profile \
  -H "Authorization: Bearer RETURNED_TOKEN_HERE"
```

## How to Test with curl

### Authentication Tests
```bash
# Login
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "john@example.com", "password": "password123"}'

# Register
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123"
  }'

# Validate Token
curl -X POST http://localhost:8000/api/auth/validate \
  -H "Content-Type: application/json" \
  -d '{"token": "YOUR_TOKEN_HERE"}'

# Logout
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Content-Type: application/json" \
  -d '{"token": "YOUR_TOKEN_HERE"}'
```

### User Management Tests
```bash
# Create User
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com", 
    "password": "password123",
    "role": "user",
    "status": 1
  }'

# Get All Users
curl -X GET http://localhost:8000/api/users

# Get Single User
curl -X GET http://localhost:8000/api/users/1

# Update User
curl -X PUT http://localhost:8000/api/users/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Updated",
    "email": "john.updated@example.com",
    "role": "manager"
  }'

# Delete User
curl -X DELETE http://localhost:8000/api/users/1

# Get User Profile (Protected)
curl -X GET http://localhost:8000/api/users/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# Update Profile (Protected)
curl -X PUT http://localhost:8000/api/users/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Updated",
    "email": "john.updated@example.com"
  }'
```

## Security Features

### JWT Token Authentication
- **Token Generation**: 64-character hex string generated on login/registration
- **Token Validation**: Validates token format and existence
- **Token Storage**: In-memory storage for development (use database in production)
- **Token Expiration**: Configurable token lifetime
- **Bearer Token**: Standard Authorization header format

### Password Security
- **Password Hashing**: Using PHP's `password_hash()` with bcrypt
- **Minimum Length**: 6 characters required
- **Password Validation**: Strength validation implemented

### Protected Routes
- **Authentication Required**: All `/api/users/profile` and `/api/users/profile/*` routes
- **Token Validation**: Middleware validates tokens before processing requests
- **User Injection**: Authenticated user data available in requests

### Role-Based Access
- **User Roles**: 'admin', 'manager', 'user'
- **Role Validation**: Role checking implemented
- **Access Control**: Different endpoints for different roles

## Error Handling

### Authentication Errors
- **Missing Credentials**: 400 Bad Request with descriptive message
- **Invalid Credentials**: 401 Unauthorized with generic error
- **Invalid Token**: 401 Unauthorized with token validation error
- **Expired Token**: 401 Unauthorized with expiration message

### Validation Errors
- **Required Fields**: 400 Bad Request with field-specific errors
- **Invalid Format**: 400 Bad Request with format validation
- **Duplicate Email**: 409 Conflict with email uniqueness error

### Server Errors
- **Database Errors**: 500 Internal Server Error with generic message
- **Service Errors**: 500 Internal Server Error with service-specific message

## Development Notes

### Token Management
- **Development**: In-memory token storage for testing
- **Production**: Use database or Redis for token persistence
- **Token Cleanup**: Implement token expiration and cleanup
- **Security**: Use HTTPS in production for token transmission

### Database Integration
- **User Repository**: Uses Doctrine ORM for data access
- **Transaction Management**: Proper database transaction handling
- **Entity Relationships**: User-Task relationships implemented
- **Data Validation**: Both application and database level validation

### Performance Considerations
- **Token Caching**: Cache frequently accessed user data
- **Database Indexing**: Proper indexes on email and ID fields
- **Rate Limiting**: Implement rate limiting for authentication endpoints
- **Connection Pooling**: Use database connection pooling

## Production Deployment

### Environment Variables
```env
DATABASE_URL="mysql://user:password@localhost:3306/task_management?serverVersion=8.0"
APP_ENV=prod
APP_SECRET="your-production-secret"
JWT_SECRET="your-jwt-secret"
TOKEN_EXPIRATION=3600
```

### Security Headers
```
Strict-Transport-Security: max-age=31536000; includeSubDomains
Content-Security-Policy: default-src 'self'
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
```

### SSL/TLS Configuration
- **HTTPS Required**: All API endpoints must use HTTPS
- **Certificate**: Valid SSL certificate required
- **HSTS**: HTTP Strict Transport Security enabled
- **Token Security**: Secure cookie and header transmission

**Your API now includes complete JWT-based authentication system with secure login, registration, token management, and protected user management endpoints!** 🎯
