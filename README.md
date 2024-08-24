PHPBurst is a high-performance framework that leverages libraries from Laravel, Symfony, and other sources to prioritize speed and efficiency in development.

**Key Dependencies:**
--------

# PHPBurst Framework Dependencies

* **PHP**: Version 8.0 or higher.
* **[Defuse PHP Encryption](https://github.com/defuse/php-encryption)**: For secure session encryption.
* **[Whoops](https://github.com/filp/whoops)**: For error handling and debugging.
* **[Illuminate Database](https://laravel.com/docs/8.x/eloquent)**: Laravel's ORM and query builder.
* **[FastRoute](https://github.com/nikic/FastRoute)**: For fast routing.
* **[Predis](https://github.com/predis/predis)**: Redis client for caching.
* **[Symfony Console](https://symfony.com/components/console)**: For command management and tasks.
* **[Symfony Dependency Injection](https://symfony.com/components/dependency_injection)**: For dependency injection.
* **[Twig](https://twig.symfony.com/)**: Flexible templating engine.
* **[Dotenv](https://github.com/vlucas/phpdotenv)**: For managing environment variables.
* **[Workerman](https://github.com/walkor/workerman)**: For asynchronous operations and session management.
* **Supported Session Handlers**: Supports  File storage, and Redis. (
I will create another custom handler for the session but not in the near future.)



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