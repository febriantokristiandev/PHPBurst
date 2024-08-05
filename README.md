PHPBurst is a fast and lightweight PHP framework inspired by Laravel, built on Workerman. PHPBurst provides a robust MVC architecture with features like fast routing, templating, ORM, and middleware, all while supporting asynchronous operations for optimal performance.

Features
--------

*   **Asynchronous Operations:** Leverages Workerman for handling asynchronous tasks and high-performance HTTP requests.
*   **Fast Routing:** Integrated with FastRoute for efficient and speedy routing.
*   **Templating:** Uses Twig for flexible and powerful templating.
*   **ORM:** Supports Eloquent ORM for database interactions.
*   **Database Abstraction:** Default support for PDO to simplify database operations.
*   **Configuration Management:** Utilizes Dotenv for managing environment variables.
*   **Error Handling:** Uses Whoops for comprehensive error handling and debugging.
*   **Security:** Incorporates Defuse PHP Encryption for secure data handling.
*   **Caching:** Integrates Redis for enhanced performance through caching.
*   **Testing:** Includes PHPUnit for reliable and thorough testing.

Installation
------------

To get started with PHPBurst, follow these steps:

1.  **Clone the Repository**

        ```bash
        git clone https://github.com/yourusername/phpburst.git
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


Under Development
-----------------

Please note that PHPBurst is currently under development. While the core functionality is operational, there may be features and improvements added in the future. We welcome contributions and feedback to help enhance the framework.