# Skeleton API by Laravel

An API skeleton built with Laravel, designed for personal API development with familiar tools pre-configured.

## Overview

This skeleton provides a solid foundation for building RESTful APIs with Laravel. It comes with many pre-configured tools, making the code more maintainable, testable, and scalable.

## Pre-configured

-   **PHP 8.2+** with strict typing features
-   **Laravel** with minimal, clean setup focused on API development
-   **RESTful API** endpoints with proper status codes and responses
-   **OpenAPI Documentation** via Scramble
-   **Authentication** via Laravel Sanctum
-   **RBAC** (Role-Based Access Control) implementation
-   **Queue Processing** with Horizon
-   **API Request Logging**
-   **Standardized Error Handling** with RFC7807 compliant responses
-   **Development/Debug Tools** (Telescope)
-   **Testing** with Pest PHP
-   **Dockerized** environment

## Architecture

-   **Action Pattern**: Business logic is encapsulated in single-purpose action classes
-   **Data Transfer Objects (DTOs)**: For type-safe data passing between layers
-   **CQRS principles**: Separation of read and write operations

## 📁 Project Structure

```
app/
  ├── Actions/            # Single-purpose business logic classes
  │   ├── Auth/           # Authentication actions
  │   ├── Profile/        # Profile-related actions
  │   └── User/           # User management actions
  ├── Attributes/         # PHP 8 attributes (used for roles, etc.)
  ├── Concerns/           # Shared traits
  │   ├── Filters/        # Query filtering traits
  │   └── Rbac/           # Role-based access control
  ├── DTOs/               # Value objects/DTOs
  ├── Enums/              # PHP 8 enums
  ├── Exceptions/         # Custom exceptions
  ├── Helpers/            # Helper functions
  ├── Http/               # HTTP layer
  │   ├── Controllers/    # Route controllers
  │   ├── Middleware/     # HTTP middleware
  │   ├── Requests/       # Form requests with validation
  │   ├── Resources/      # API resources (transformers)
  │   └── Responses/      # Standardized API responses
  ├── Jobs/               # Queue jobs
  ├── Models/             # Eloquent models
  ├── Policies/           # Authorization policies
  └── Providers/          # Service providers
```

## 🚀 Getting Started

### Prerequisites

-   PHP 8.2+
-   Composer 2+
-   Docker (optional)

### Installation

1. Clone this repository:

```bash
git clone https://your-repository-url.git my-api
cd my-api
```

```bash
docker-compose up -d
```

## 🧪 Format

```bash
composer pint
```

## 🧪 Testing

```bash
composer test
```

## 🛠️ Development Tools

-   **Laravel Horizon**: Queue monitoring `/horizon`
-   **Laravel Telescope**: Debugging and monitoring `/telescope` (enabled in local environment)
-   **Scramble**: API documentation `/docs/api`

## 📚 API Documentation

API documentation is automatically generated using Scramble. Access it at `/docs/api` when running the application.
