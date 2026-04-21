# Tasks API Documentation

## Base URL
```
http://localhost:8000/api/tasks
```

## Tasks API Endpoints

### 1. Create Task
**POST** `/api/tasks`

**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "role": "user",
    "task": "Complete the project documentation and ensure all API endpoints are working correctly."
}
```

**Response (201 Created):**
```json
{
    "message": "Task created successfully",
    "task": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "task": "Complete the project documentation and ensure all API endpoints are working correctly.",
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
        "task": "Task description must be at least 10 characters"
    }
}
```

### 2. Get All Tasks
**GET** `/api/tasks`

**Response (200 OK):**
```json
{
    "tasks": [
        {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "user",
            "task": "Complete the project documentation and ensure all API endpoints are working correctly.",
            "created_at": "2024-04-21 01:00:00",
            "updated_at": "2024-04-21 01:00:00"
        }
    ],
    "total": 1
}
```

### 3. Get Single Task
**GET** `/api/tasks/{id}`

**Response (200 OK):**
```json
{
    "task": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "user",
        "task": "Complete the project documentation and ensure all API endpoints are working correctly.",
        "created_at": "2024-04-21 01:00:00",
        "updated_at": "2024-04-21 01:00:00"
    }
}
```

**Not Found (404 Not Found):**
```json
{
    "error": "Task not found"
}
```

### 4. Update Task
**PUT** `/api/tasks/{id}`

**Request Body:**
```json
{
    "name": "John Updated",
    "task": "Updated task description: Complete the project documentation with additional security features."
}
```

**Response (200 OK):**
```json
{
    "message": "Task updated successfully",
    "task": {
        "id": 1,
        "name": "John Updated",
        "email": "john@example.com",
        "role": "user",
        "task": "Updated task description: Complete the project documentation with additional security features.",
        "updated_at": "2024-04-21 01:05:00"
    }
}
```

### 5. Delete Task
**DELETE** `/api/tasks/{id}`

**Response (200 OK):**
```json
{
    "message": "Task deleted successfully",
    "task_id": 1
}
```

**Not Found (404 Not Found):**
```json
{
    "error": "Task not found"
}
```

## Field Validations

### Task Fields
- **name**: Required, 2-255 characters
- **email**: Required, valid email format
- **role**: Required, must be one of: 'admin', 'manager', 'user'
- **task**: Required, minimum 10 characters (task description)

## HTTP Status Codes

- **200 OK**: Request successful
- **201 Created**: Resource created successfully
- **400 Bad Request**: Validation errors or invalid JSON
- **404 Not Found**: Resource not found
- **500 Internal Server Error**: Server error occurred

## How to Test with curl

### Create Task
```bash
curl -X POST http://localhost:8000/api/tasks \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com", 
    "role": "user",
    "task": "Complete the project documentation and ensure all API endpoints are working correctly."
  }'
```

### Get All Tasks
```bash
curl -X GET http://localhost:8000/api/tasks
```

### Get Single Task
```bash
curl -X GET http://localhost:8000/api/tasks/1
```

### Update Task
```bash
curl -X PUT http://localhost:8000/api/tasks/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Updated",
    "task": "Updated task description with more details."
  }'
```

### Delete Task
```bash
curl -X DELETE http://localhost:8000/api/tasks/1
```

## Security Notes

- All inputs are validated using Symfony Validator component
- Proper HTTP status codes are returned for different scenarios
- JSON responses are consistent across all endpoints
- Error handling for database operations included
