PHPBurst leverages Workerman for speed and efficiency, while drawing on well-known frameworks to simplify development and debugging, thanks to their extensive online communities.

**Key Dependencies:**
--------

# PHPBurst Framework Dependencies

# Library Choices for PHPBurst

## PHP (Version 8.0 or Higher)
- Requires PHP 8.0+ for modern features and better performance.

## [Defuse PHP Encryption](https://github.com/defuse/php-encryption)
- Provides secure encryption for session data.

## [Illuminate Database](https://laravel.com/docs/8.x/eloquent)
- Uses Laravel's ORM and query builder due to its extensive documentation and strong community support.

## [FastRoute](https://github.com/nikic/FastRoute)
- Offers high-performance routing for handling HTTP requests efficiently.

## [Symfony Dependency Injection](https://symfony.com/components/dependency_injection)
- Manages class dependencies and injections efficiently.

## [Twig](https://twig.symfony.com/)
- A powerful templating engine for generating HTML views.

## [Workerman](https://github.com/walkor/workerman)
- Handles asynchronous operations and session management effectively.

## Supported Session Handlers
- Supports file storage and Redis; a custom handler may be added later for flexibility.





Installation
------------

To get started with PHPBurst, follow these steps:

1.  **Clone the Repository**

    ```bash
    git clone https://github.com/febriantokristiandev/PHPBurst.git
    cd phpburst
    ```

2.  **Install Dependencies**

    Ensure you have [Composer](https://getcomposer.org) installed, then run:

    ```bash
        composer install
    ```
3.  **Set Up Environment**

    Create a `.env` file in the root directory and configure your environment variables as needed.

4.  **Run the Application**

    To start the application, use the following Composer command:
    ```bash
    composer start
    ```
    This will run `php bootstrap/app.php` to initialize the application.





## Commands

| Command          | Description                                  |
|------------------|----------------------------------------------|
| `php burst start` or `composer start` | Start the PHPBurst application.              |        |
| `php burst key:generate` | Generate a new encryption key for the application. |




Under Development
-----------------

Please note that PHPBurst is currently under development. While the core functionality is operational, there may be features and improvements added in the future.