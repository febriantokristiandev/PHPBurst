PHPBurst is a high-performance framework that leverages libraries from Laravel, Symfony, and other sources to prioritize speed and efficiency in development.

**Key Dependencies:**
--------

* **PHP:** Version 8.0 or higher.
* **Defuse PHP Encryption:** For secure session encryption.
* **Whoops:** For error handling and debugging.
* **Illuminate Database:** Laravel's ORM and query builder.
* **FastRoute:** For fast routing.
* **Predis:** Redis client for caching.
* **Symfony Console:** For command management and tasks.
* **Symfony Dependency Injection:** For dependency injection.
* **Laminas Session:** For session management.
* **Twig:** Flexible templating engine.
* **Dotenv:** For managing environment variables.
* **Workerman:** For asynchronous operations.


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