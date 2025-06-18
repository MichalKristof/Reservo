# Reservo

Reservo is a simple web application for managing table reservations in a restaurant.  
Built with Laravel and MySQL, it allows users to register, log in, and book available tables.  
Includes a user-friendly interface and full test coverage.

This project was developed as part of the Laravel Developer task for Snadnee.

---

## Application Stack

**Backend**

- **Laravel** (v12)
- **PHP** (v8.2)
- **MySQL** (v8.0) – development
- **MySQL** (v8.0) – testing (`mysql_test` service)
- **Redis** (Alpine) for cache/session
- **Inertia.js** for server‑driven SPA

**Frontend**

- **Vue 3**
- **Vite** (via `laravel-vite-plugin` v1.2)
- **Tailwind CSS** (v4)
- **@inertiajs/vue3**, **@vuepic/vue-datepicker**, **axios**, **lodash**

**Dev Tools & Testing**

- **Docker Compose** (`docker-compose.dev.yaml`)
- **PestPHP** + **PHPUnit**
- **Xdebug** (coverage, debug)
- **Laravel Pint**, **IDE Helper**, **Pail**

## Prerequisites

- Docker ≥ 20.10
- Docker Compose plugin
- Composer ≥ 2.0
- Node.js ≥ 18 (For local development)

## Environment Variables

Copy `.env.example` to `.env` and update as needed.

```bash
cp .env.example .env
```

For tests, see `.env.testing`:

## Development Setup

This project uses Docker for local development. The development environment is orchestrated via
`docker-compose.dev.yaml`.

Follow these steps to start the application:

```bash
# 1. Build the Docker images
make build

# 2. Start all containers in detached mode
make up

# 3. Reset the database and seed demo data
make migrate-fresh

# 4. Open a bash shell inside the workspace container
make bash

# 5. Install dependencies inside the container
composer install
npm install

# 5. From inside the container, start the frontend dev server
npm run dev
```

## Users

The application comes with a few pre-defined users for testing purposes:

- **Admin User**:
    - Email: `admin@example.com`
    - Password: `universal`
    - Role: Admin (can see tables occupancy)

- **Regular User**:
    - Email: `test@example.com`
    - Password: `universal`
    - Role: Regular user (can make reservations)

## Running Tests

The backend unit and feature tests (PestPHP + PHPUnit) will automatically spin up the PHP‑FPM and MySQL test containers,
run migrations, execute tests, and tear everything down when complete:

```bash
# Run all tests
make test
```

## Application Structure and API Routes

The application backend is built with Laravel and follows a RESTful API design for managing authentication,
reservations, tables, and health checks.

Here is an overview of the main API routes and their purposes:

### Public (Guest) Routes

- `GET /`  
  Loads the home page (Inertia component: `Home`).

- `GET /register`  
  Registration page (Inertia component: `Auth/Register`).

- `POST /register`  
  Handles user registration (via `RegisterController`).

- `GET /login`  
  Login page (Inertia component: `Auth/Login`).

- `POST /login`  
  Handles user login (via `LoginController`).

---

### Authenticated Routes (Require login)

- `POST /logout`  
  Logs out the authenticated user (`LogoutController`).

- `GET /dashboard`  
  User dashboard page (Inertia component: `Dashboard`).

- `GET /reservations`  
  Lists the user's reservations (`ReservationController@index`).

- `GET /reservations/create`  
  Shows the form to create a new reservation (`ReservationController@create`).

- `POST /reservations`  
  Creates a new reservation (`ReservationController@store`).

- `POST /reservations/available-times`  
  Returns available times for reservations (`ReservationAvailabilityController@availableTimes`).

- `POST /reservations/available-durations`  
  Returns available durations (`ReservationAvailabilityController@availableDurations`).

- `POST /reservations/available-people`  
  Returns available number-of-people options (`ReservationAvailabilityController@availablePeople`).

---

### Admin Routes (Require user to have admin privileges)

- `GET /tables`  
  Lists all tables for management (`TableController@index`).

---

### Utility Routes

- `GET /info`  
  Displays PHP info page (for debugging).

- `GET /health`  
  Returns JSON status of the application's health checks including:
    - Database connection
    - Redis cache connectivity
    - Storage accessibility

  Responds with HTTP 200 if all services are OK, or 503 if any service is failing.

---

This routing structure ensures clean separation of public, authenticated, and admin functionalities, and includes useful
health endpoints for monitoring.

---
Feel free to reach out for any questions or contributions!

**Author:**  Michal Krištof
**Email:** michal.kristof.email@gmail.com
**License:** MIT

