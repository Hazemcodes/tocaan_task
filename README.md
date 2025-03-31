# Laravel Order & Payment API

## Project Overview
This project provides a RESTful API for managing users, orders, items, and payments. It follows best practices, including authentication, authorization, and extensible payment processing.

## Setup Instructions
1. Clone the repository:
   ```bash
   git clone <your-repo-link>
   cd <your-project-folder>
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Set up environment variables:
   ```bash
   cp .env.example .env
   ```
4. Generate application key:
   ```bash
   php artisan key:generate
   ```
5. Set up the database:
   ```bash
   php artisan migrate --seed
   ```
6. Install JWT authentication:
   ```bash
   php artisan jwt:secret
   ```
7. Run the application:
   ```bash
   php artisan serve
   ```

## Authentication & Authorization
- Users authenticate using JWT tokens.
- Role-based permissions are managed using **Spatie Roles & Permissions**.

## Payment Gateway Extensibility
- The system allows integration with multiple payment gateways.
- The `Payment` model is designed to be extendable for different payment providers.

## Libraries Used
- **Spatie Media Library** - Handles user profile images.
- **Spatie Roles & Permissions** - Manages user roles.
- **Scramble** - Secures sensitive data.
- **PHPOpenSourceSaver JWT Auth** - Handles authentication using JSON Web Tokens.

## API Endpoints
### Authentication
| Method | Endpoint          | Description |
|--------|------------------|-------------|
| POST   | `/api/register`  | Register a new user |
| POST   | `/api/login`     | Authenticate a user |

### Orders
| Method | Endpoint          | Description |
|--------|------------------|-------------|
| GET    | `/api/orders`    | Retrieve all orders (paginated) |
| POST   | `/api/orders`    | Create a new order |
| GET    | `/api/orders/{id}` | Retrieve order details |
| PUT    | `/api/orders/{id}` | Update an order |
| DELETE | `/api/orders/{id}` | Delete an order |

### Items
| Method | Endpoint          | Description |
|--------|------------------|-------------|
| GET    | `/api/items`     | Retrieve all items |
| POST   | `/api/items`     | Create a new item |
| GET    | `/api/items/{id}` | Retrieve item details |
| PUT    | `/api/items/{id}` | Update an item |
| DELETE | `/api/items/{id}` | Delete an item |

### Payments
| Method | Endpoint                       | Description |
|--------|--------------------------------|-------------|
| POST   | `/api/payments`                | Process a payment |
| GET    | `/api/payments`                | Retrieve all payments |
| GET    | `/api/payments/order/{order_id}` | Retrieve payments by order |

## Notes
- Ensure that `.env` file has correct database and JWT configurations.
- API requests require authentication via Bearer Token.
- Orders and items follow a many-to-many relationship.

