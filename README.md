# Backend Books - Laravel API

## Description
Backend Books is a RESTful API built with Laravel for managing books, authors, and related entities. It provides CRUD operations and authentication features.

## Features
- User authentication (JWT-based)
- CRUD operations for books and authors
- Middleware for API security
- Database migration and seeding
- Laravel Sanctum for authentication

## Installation
### Prerequisites
- PHP >= 8.0
- Composer
- MySQL or PostgreSQL
- Laravel 10

### Setup
1. Clone the repository:
   ```sh
   git clone https://github.com/DavidLC578/backend-books.git
   cd backend-books
   ```
2. Install dependencies:
   ```sh
   composer install
   ```
3. Copy and configure the environment file:
   ```sh
   cp .env.example .env
   ```
   Update the `.env` file with your database credentials.
4. Generate the application key:
   ```sh
   php artisan key:generate
   ```
5. Generate the application key:
   ```sh
   php artisan storage:link
   ```
6. Run migrations and seed database:
   ```sh
   php artisan migrate
   ```
7. Start the development server:
   ```sh
   php artisan serve
   ```

## API Endpoints
| Method | Endpoint | Description |
|--------|-------------|-------------|
| POST | /api/register | Register a new user |
| POST | /api/login | User authentication |
| GET | /api/books | Get all books |
| GET | /api/books/{book} | Get a book |
| GET | /api/books/{book}/download | Download a book |
| POST | /api/books | Add a new book |
| PUT | /api/books/{id} | Update book details |
| DELETE | /api/books/{id} | Delete a book |

## License
This project is open-source and available under the [MIT License](LICENSE.md).


