# Insurance Quotation API

A REST API for calculating insurance quotations based on age and travel duration. The API provides secure quotation creation, listing, and management using JWT authentication.

## Features

- JWT Authentication
- RESTful API endpoints
- Repository pattern
- Service layer architecture
- Age-based load calculation
- Multiple currency support
- Detailed API documentation

## Requirements

- PHP 8.1+
- Composer
- MySQL 5.7+
- Laravel Valet (recommended) or other local development environment

## Installation

1. Clone the project:
```bash
git clone <repository-url>
cd quotation-api
```

2. Install dependencies:
```bash
composer install
```

3. Set up environment variables:
```bash
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

4. Configure database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quotation_db
DB_USERNAME=root
DB_PASSWORD=
```

5. Valet setup (for MacOS):
```bash
composer global require laravel/valet
valet install
cd ~/path/to/project
valet link
valet secure
```

6. Run migrations and seeders:
```bash
php artisan migrate --seed
```

## API Usage

### Authentication

```bash
# Register
curl -X POST https://quotation-api.test/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password",
    "password_confirmation": "password"
  }'

# Login
curl -X POST https://quotation-api.test/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "john@example.com",
    "password": "password"
  }'
```

### Create Quotation

```bash
curl -X POST https://quotation-api.test/api/quotations \
  -H "Authorization: Bearer {your_token}" \
  -H "Content-Type: application/json" \
  -d '{
    "ages": "28,35",
    "currency_id": "EUR",
    "start_date": "2024-03-15",
    "end_date": "2024-03-20"
  }'
```

## API Endpoints

### Auth Endpoints
- `POST /api/register` - Register new user
- `POST /api/login` - Login user
- `POST /api/logout` - Logout user (requires authentication)

### Quotation Endpoints
- `POST /api/quotations` - Create new quotation
- `GET /api/quotations` - List all quotations
- `GET /api/quotations/{id}` - Get specific quotation
- `PUT /api/quotations/{id}` - Update quotation
- `DELETE /api/quotations/{id}` - Delete quotation

## Calculation Formula

The quotation amount is calculated using this formula:
```
Cost Per Person = Fixed Rate * Age Load * Number of Days
Total = Sum of All Person Costs
```

Example:
- Fixed Rate: 3
- Age Loads: 
  - 18-30: 0.6
  - 31-40: 0.7
  - 41-50: 0.8
  - 51-60: 0.9
  - 61-70: 1.0

## Testing

To run the tests:
```bash
php artisan test
```

## Architecture

- **Controllers**: Handle HTTP requests and responses
- **Services**: Contain business logic
- **Repositories**: Handle database operations
- **Models**: Represent database tables
- **Requests**: Handle request validation
- **Tests**: Feature and unit tests

## License

This project is licensed under the [MIT license](https://opensource.org/licenses/MIT).
