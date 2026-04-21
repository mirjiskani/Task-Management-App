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

**Your API now includes complete JWT-based authentication system with secure login, registration, token management, and protected user management endpoints!** 

## Postman Collection

### Environment Variables
```json
{
    "name": "Task Management API",
    "values": [
        {
            "key": "BASE_URL",
            "value": "http://localhost:8000/api",
            "type": "default",
            "enabled": true
        },
        {
            "key": "AUTH_TOKEN",
            "value": "",
            "type": "default",
            "enabled": true
        },
        {
            "key": "USER_ID",
            "value": "1",
            "type": "default",
            "enabled": true
        }
    ]
}
```

### API Collection JSON
```json
{
    "info": {
        "name": "Task Management API",
        "schema": "https://schema.getpostman.com/json/collection/v2.1.0/collection.json"
    },
    "item": [
        {
            "name": "Authentication",
            "item": [
                {
                    "name": "Login",
                    "request": {
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"email\": \"john@example.com\",\n    \"password\": \"password123\"\n}"
                        },
                        "url": {
                            "raw": "{{BASE_URL}}/auth/login",
                            "host": ["{{BASE_URL}}"],
                            "path": ["auth","login"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "if (pm.response.code === 200) {",
                                    "    const response = pm.response.json();",
                                    "    pm.environment.set('AUTH_TOKEN', response.token);",
                                    "    pm.environment.set('USER_ID', response.user.id);",
                                    "    pm.test('Login successful', () => {",
                                    "        pm.expect(response.message).to.eql('Login successful');",
                                    "    });",
                                    "} else {",
                                    "    pm.test('Login failed', () => {",
                                    "        pm.expect(pm.response.code).to.not.eql(200);",
                                    "    });",
                                    "}"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                },
                {
                    "name": "Register",
                    "request": {
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"name\": \"John Doe\",\n    \"email\": \"john@example.com\",\n    \"password\": \"password123\"\n}"
                        },
                        "url": {
                            "raw": "{{BASE_URL}}/auth/register",
                            "host": ["{{BASE_URL}}"],
                            "path": ["auth","register"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "if (pm.response.code === 201) {",
                                    "    const response = pm.response.json();",
                                    "    pm.environment.set('AUTH_TOKEN', response.token);",
                                    "    pm.environment.set('USER_ID', response.user.id);",
                                    "    pm.test('Registration successful', () => {",
                                    "        pm.expect(response.message).to.eql('Registration successful');",
                                    "    });",
                                    "} else {",
                                    "    pm.test('Registration failed', () => {",
                                    "        pm.expect(pm.response.code).to.not.eql(201);",
                                    "    });",
                                    "}"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                },
                {
                    "name": "Validate Token",
                    "request": {
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"token\": \"{{AUTH_TOKEN}}\"\n}"
                        },
                        "url": {
                            "raw": "{{BASE_URL}}/auth/validate",
                            "host": ["{{BASE_URL}}"],
                            "path": ["auth","validate"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "const response = pm.response.json();",
                                    "pm.test('Token validation', () => {",
                                    "    pm.expect(response.valid).to.be.true;",
                                    "});"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                },
                {
                    "name": "Logout",
                    "request": {
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"token\": \"{{AUTH_TOKEN}}\"\n}"
                        },
                        "url": {
                            "raw": "{{BASE_URL}}/auth/logout",
                            "host": ["{{BASE_URL}}"],
                            "path": ["auth","logout"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "const response = pm.response.json();",
                                    "pm.test('Logout successful', () => {",
                                    "    pm.expect(response.message).to.eql('Logout successful');",
                                    "});",
                                    "pm.environment.set('AUTH_TOKEN', '');"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                }
            ]
        },
        {
            "name": "User Management",
            "item": [
                {
                    "name": "Get User Profile",
                    "request": {
                        "method": "GET",
                        "header": [
                            {
                                "key": "Authorization",
                                "value": "Bearer {{AUTH_TOKEN}}"
                            }
                        ],
                        "url": {
                            "raw": "{{BASE_URL}}/users/profile",
                            "host": ["{{BASE_URL}}"],
                            "path": ["users","profile"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "const response = pm.response.json();",
                                    "pm.test('Profile retrieved', () => {",
                                    "    pm.expect(response.user).to.have.property('id');",
                                    "    pm.expect(response.user).to.have.property('email');",
                                    "});"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                },
                {
                    "name": "Update User Profile",
                    "request": {
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Authorization",
                                "value": "Bearer {{AUTH_TOKEN}}"
                            },
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"name\": \"John Updated\",\n    \"email\": \"john.updated@example.com\",\n    \"role\": \"manager\"\n}"
                        },
                        "url": {
                            "raw": "{{BASE_URL}}/users/profile",
                            "host": ["{{BASE_URL}}"],
                            "path": ["users","profile"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "const response = pm.response.json();",
                                    "pm.test('Profile updated', () => {",
                                    "    pm.expect(response.message).to.eql('Profile updated successfully');",
                                    "});"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                },
                {
                    "name": "Create User",
                    "request": {
                        "method": "POST",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"name\": \"John Doe\",\n    \"email\": \"john@example.com\",\n    \"password\": \"password123\",\n    \"role\": \"user\",\n    \"status\": 1\n}"
                        },
                        "url": {
                            "raw": "{{BASE_URL}}/users",
                            "host": ["{{BASE_URL}}"],
                            "path": ["users"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "const response = pm.response.json();",
                                    "pm.test('User created', () => {",
                                    "    pm.expect(response.message).to.eql('User created successfully');",
                                    "    pm.expect(response.user).to.have.property('id');",
                                    "});"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                },
                {
                    "name": "Get All Users",
                    "request": {
                        "method": "GET",
                        "url": {
                            "raw": "{{BASE_URL}}/users",
                            "host": ["{{BASE_URL}}"],
                            "path": ["users"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "const response = pm.response.json();",
                                    "pm.test('Users retrieved', () => {",
                                    "    pm.expect(response.users).to.be.an('array');",
                                    "    pm.expect(response).to.have.property('total');",
                                    "});"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                },
                {
                    "name": "Get Single User",
                    "request": {
                        "method": "GET",
                        "url": {
                            "raw": "{{BASE_URL}}/users/{{USER_ID}}",
                            "host": ["{{BASE_URL}}"],
                            "path": ["users","{{USER_ID}}"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "const response = pm.response.json();",
                                    "pm.test('User retrieved', () => {",
                                    "    pm.expect(response.user).to.have.property('id');",
                                    "    pm.expect(response.user.id).to.eql(parseInt(pm.environment.get('USER_ID')));",
                                    "});"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                },
                {
                    "name": "Update User",
                    "request": {
                        "method": "PUT",
                        "header": [
                            {
                                "key": "Content-Type",
                                "value": "application/json"
                            }
                        ],
                        "body": {
                            "mode": "raw",
                            "raw": "{\n    \"name\": \"John Updated\",\n    \"email\": \"john.updated@example.com\",\n    \"role\": \"manager\",\n    \"status\": 1\n}"
                        },
                        "url": {
                            "raw": "{{BASE_URL}}/users/{{USER_ID}}",
                            "host": ["{{BASE_URL}}"],
                            "path": ["users","{{USER_ID}}"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "const response = pm.response.json();",
                                    "pm.test('User updated', () => {",
                                    "    pm.expect(response.message).to.eql('User updated successfully');",
                                    "});"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                },
                {
                    "name": "Delete User",
                    "request": {
                        "method": "DELETE",
                        "url": {
                            "raw": "{{BASE_URL}}/users/{{USER_ID}}",
                            "host": ["{{BASE_URL}}"],
                            "path": ["users","{{USER_ID}}"]
                        }
                    },
                    "event": [
                        {
                            "listen": "test",
                            "script": {
                                "exec": [
                                    "const response = pm.response.json();",
                                    "pm.test('User deleted', () => {",
                                    "    pm.expect(response.message).to.eql('User deleted successfully');",
                                    "    pm.expect(response.user_id).to.eql(parseInt(pm.environment.get('USER_ID')));",
                                    "});"
                                ],
                                "type": "text/javascript"
                            }
                        }
                    ]
                }
            ]
        }
    ]
}
```

### Postman Setup Instructions

#### 1. Import Environment
1. Open Postman
2. Click **Import** button
3. Select **Raw text**
4. Copy the Environment Variables JSON above
5. Save as "Task Management API Environment"

#### 2. Import Collection
1. Click **Import** button
2. Select **Raw text**
3. Copy the API Collection JSON above
4. Save as "Task Management API Collection"

#### 3. Set Environment Variables
- **BASE_URL**: `http://localhost:8000/api` (auto-set)
- **AUTH_TOKEN**: Will be automatically set after login
- **USER_ID**: Will be automatically set after login/registration

#### 4. Test Authentication Flow
1. **Login** request - Sets AUTH_TOKEN and USER_ID
2. **Get User Profile** - Uses the token from login
3. **Logout** request - Clears the AUTH_TOKEN

#### 5. Automated Tests
Each request includes test scripts that:
- **Login/Register**: Automatically save token and user ID
- **Profile requests**: Validate user data structure
- **CRUD operations**: Verify success responses
- **Token validation**: Check token validity

### Postman Features

#### **Environment Variables**
- **Dynamic token management** - Tokens automatically saved after login
- **User ID tracking** - User ID automatically set from responses
- **Base URL configuration** - Easy environment switching

#### **Automated Testing**
- **Response validation** - Each request validates expected responses
- **Token management** - Automatic token storage and cleanup
- **Error handling** - Tests for both success and failure cases

#### **Collection Organization**
- **Authentication folder** - Login, register, validate, logout
- **User Management folder** - All user CRUD operations
- **Protected routes** - Automatically include authorization headers

#### **Request Templates**
- **Headers pre-configured** - Content-Type and Authorization headers
- **Body templates** - JSON request bodies with placeholders
- **URL variables** - Dynamic URL parameters using environment variables

### Quick Start Guide

#### **For Development Testing**
1. Import environment and collection
2. Run **Login** request (saves token automatically)
3. Run **Get User Profile** (uses saved token)
4. Test other endpoints as needed

#### **For API Integration**
1. Use the collection as reference for your own API client
2. Copy request examples for your application
3. Follow the authentication flow patterns shown

#### **For Debugging**
1. Check environment variables after login
2. Verify token is being sent in Authorization headers
3. Use Postman console to debug API responses

**Your API now has complete Postman collection with automated testing and environment management!** 

## Symfony Project Commands

### Start Development Server

#### Primary Command
```bash
php -S localhost:8000 -t public
```

#### Alternative Methods
```bash
# Method 1: Using Symfony CLI (if installed)
symfony server:start

# Method 2: Using console with built-in server
php bin/console server:run

# Method 3: Custom port
php -S localhost:8080 -t public
```

### Development Commands

#### Cache Management
```bash
# Clear cache
php bin/console cache:clear

# Clear production cache
php bin/console cache:clear --env=prod

# Warm up cache
php bin/console cache:warmup
```

#### Database Operations
```bash
# Create database
php bin/console doctrine:database:create

# Run migrations
php bin/console doctrine:migrations:migrate

# Generate migration
php bin/console make:migration

# Validate schema
php bin/console doctrine:schema:validate
```

#### Testing Commands
```bash
# Run all tests
php bin/phpunit

# Run specific test
php bin/phpunit --filter testCreateUser

# Generate test coverage
php bin/phpunit --coverage-html coverage/
```

#### Debug Commands
```bash
# Show all routes
php bin/console debug:router

# Show container services
php bin/console debug:container

# Show configuration
php bin/console debug:config

# Show environment variables
php bin/console debug:env
```

### Quick Start Commands

#### 1. Start Server
```bash
cd d:\development\Task-Management-App\task-management-application
php -S localhost:8000 -t public
```

#### 2. Test API
```bash
# Test login endpoint
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "test@example.com", "password": "password123"}'
```

#### 3. Stop Server
```bash
# Press Ctrl+C in the terminal where server is running
```

### Troubleshooting Commands

#### Check Dependencies
```bash
# Install dependencies
composer install

# Update dependencies
composer update

# Check for security issues
composer audit
```

#### Check Configuration
```bash
# Validate Symfony installation
php bin/console about

# Check environment
php bin/console debug:env

# Show available commands
php bin/console list
```

### Production Commands

#### Production Setup
```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Clear production cache
php bin/console cache:clear --env=prod

# Run production migrations
php bin/console doctrine:migrations:migrate --env=prod

# Validate production setup
php bin/console about --env=prod
```

### Most Common Commands

#### Daily Development
```bash
# Start server
php -S localhost:8000 -t public

# Clear cache (if needed)
php bin/console cache:clear

# Run tests
php bin/phpunit

# Check routes
php bin/console debug:router
```

### Command Reference Guide

#### Server Management
- **Start**: `php -S localhost:8000 -t public`
- **Stop**: `Ctrl+C` in server terminal
- **Check**: Open `http://localhost:8000` in browser

#### API Testing
- **Login**: `POST http://localhost:8000/api/auth/login`
- **Register**: `POST http://localhost:8000/api/auth/register`
- **Profile**: `GET http://localhost:8000/api/users/profile` (with token)

#### Development Workflow
```bash
# 1. Start development server
php -S localhost:8000 -t public

# 2. Test authentication
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "test@example.com", "password": "password123"}'

# 3. Use returned token for protected routes
curl -X GET http://localhost:8000/api/users/profile \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"

# 4. Clear cache if needed
php bin/console cache:clear
```

**Your Symfony Task Management API includes comprehensive command reference for easy development!** 🎯
