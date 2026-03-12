# Task Manager API

REST API built with **Laravel 12** for managing tasks with authentication, validation, database persistence, and integration with an external API.

This project was developed as part of a backend technical evaluation.

---

# Tech Stack

- PHP 8+
- Laravel 12
- MySQL
- Laravel Sanctum (Token Authentication)
- Laravel HTTP Client
- JSONPlaceholder (External API)

---

# Features

- REST API for task management
- Token-based authentication
- Full CRUD operations
- Data validation
- External API integration
- Pagination and filtering
- Clean architecture with Controllers, Services and Requests

---

# Installation

Clone the repository:

```bash
git clone https://github.com/edward0411/task-manager-api.git

Go to project directory:

cd task-manager-api

Install dependencies:

composer install

Copy environment file:

cp .env.example .env

Generate application key:

php artisan key:generate
Database Configuration

Update your .env file:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager_api
DB_USERNAME=root
DB_PASSWORD=

Run migrations:

php artisan migrate
Running the Project

Start the Laravel server:

php artisan serve

API base URL:

http://127.0.0.1:8000/api
Authentication

The API uses Laravel Sanctum with Bearer Token authentication.

After login or registration, a token will be returned:

Authorization: Bearer YOUR_TOKEN

Include this token in all protected requests.

API Endpoints
Authentication
Register
POST /api/auth/register

Request body:

{
  "name": "Edward",
  "email": "edward@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
Login
POST /api/auth/login

Request body:

{
  "email": "edward@example.com",
  "password": "password123"
}

Response:

{
  "data": {
    "token": "1|xxxxxxxxxxxxxxxx"
  }
}
Current User
GET /api/auth/me

Headers:

Authorization: Bearer TOKEN
Logout
POST /api/auth/logout

Headers:

Authorization: Bearer TOKEN
Task Endpoints

All task endpoints require authentication.

List Tasks
GET /api/tasks

Optional query parameters:

status=pending
priority=high
search=keyword
per_page=10

Example:

GET /api/tasks?status=pending&priority=high
Get Task
GET /api/tasks/{id}
Create Task
POST /api/tasks

Body:

{
  "title": "Prepare technical test",
  "description": "Build REST API with Laravel",
  "status": "pending",
  "priority": "high",
  "due_date": "2026-03-12"
}
Update Task
PUT /api/tasks/{id}

Body example:

{
  "title": "Finish API documentation",
  "status": "in_progress"
}
Delete Task
DELETE /api/tasks/{id}
External API Integration

This project integrates with the public API:

https://jsonplaceholder.typicode.com

Get External Posts
GET /api/external/posts

Optional filter:

userId=1
Get External Post by ID
GET /api/external/posts/{id}
Error Handling

The API uses standard HTTP response codes:

Code	Meaning
200	OK
201	Created
400	Bad Request
401	Unauthorized
404	Not Found
422	Validation Error
500	Internal Server Error

Project Structure
app
 ├── Http
 │    ├── Controllers
 │    ├── Requests
 │    └── Resources
 │
 ├── Models
 │
 ├── Services
 │
database
 └── migrations

routes
 └── api.php

Architecture layers:

Controllers handle HTTP requests

Services encapsulate business logic

Requests validate incoming data

Models manage database persistence

Running Tests

If tests are implemented:

php artisan test
Optional Improvements

Possible improvements for future versions:

Docker support

Swagger / OpenAPI documentation

Caching

Rate limiting

Task tagging system

Author

Developed by:

Edward Arevalo
Senior Software Developer
```
