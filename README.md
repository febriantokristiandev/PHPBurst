PHPBurst leverages Workerman for speed and efficiency, while drawing on well-known frameworks to simplify development and debugging, thanks to their extensive online communities.

# Important Libraries

**[Defuse PHP Encryption](https://github.com/defuse/php-encryption)** - Provides secure encryption for session data.

**[Illuminate Database](https://laravel.com/docs/8.x/eloquent)** Uses Laravel's ORM and query builder due to its extensive documentation and strong community support.

**[FastRoute](https://github.com/nikic/FastRoute)** Offers high-performance routing for handling HTTP requests efficiently.

**[Symfony Dependency Injection](https://symfony.com/components/dependency_injection)** Manages class dependencies and injections efficiently.

**[Twig](https://twig.symfony.com/)** A powerful templating engine for generating HTML views.

**[Workerman](https://github.com/walkor/workerman)** Handles asynchronous operations and session management effectively.

**Supported Session Handlers** Supports file storage and Redis; a custom handler may be added later for flexibility.





# Installation

To get started with PHPBurst, follow these steps:

Requires PHP 8.0+

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



# Documentation

## Methods

| Method   | Description                                      | Parameters                                                                                       | Return                             | Example Usage                                                 |
|----------|--------------------------------------------------|-------------------------------------------------------------------------------------------------|------------------------------------|---------------------------------------------------------------|
| `view`   | Generates an HTML response with a Twig view      | `$viewName` (string): View file name<br>$data (array, optional): Data to pass to the view<br>`$req` (object, optional): Request object needed for sessions and CSRF tokens | `Response` object with HTML content| `return response($req)->view('home', ['name' => 'PHPBurst']);`<br>or<br>`return response()->view('home', ['name' => 'PHPBurst']);` if no session or CSRF is required |
| `json`   | Generates a JSON response                        | `$data` (mixed): Data to encode as JSON<br>$status (int, optional): HTTP status (default: 200)  | `Response` object with JSON content| `return response()->json(['success' => true, 'data' => $data], 200);` |
| `redirect` | Generates an HTTP redirect response              | `$url` (string): Redirect URL<br>$status (int, optional): HTTP status (default: 302)            | `Response` object with `Location` header | `return response()->redirect('https://example.com', 302);` |
| `jsonp`  | Generates a JSONP response                       | `$callback` (string): Callback function name<br>$data (mixed): Data to encode as JSON           | `Response` object with JSONP content | `return response()->jsonp('myCallback', ['key' => 'value']);` |
| `custom` | Generates a response with custom content         | `$content` (string): Response content<br>$status (int, optional): HTTP status (default: 200)<br>$headers (array, optional): Additional headers | `Response` object with custom content and headers | `return response()->custom('<html><body>Hello</body></html>', 200, ['Content-Type' => 'text/html']);` |

## Usage Note

The methods `view`, `json`, `redirect`, `jsonp`, and `custom` are designed to be used with `return response()`. These methods are part of the `ResponseHelper` class and provide convenient ways to generate various types of HTTP responses. They are not intended to be used directly with the `Workerman\Protocols\Http\Response` class.

**Correct Usage:**

```php
return response()->view('home', ['name' => 'PHPBurst']);
return response()->json(['success' => true, 'data' => $data]);
return response()->redirect('https://example.com');
return response()->jsonp('myCallback', ['key' => 'value']);
return response()->custom('<html><body>Hello</body></html>', 200, ['Content-Type' => 'text/html']);
```

## Workerman Chaining Methods

| Method                | Description                                         | Parameters                               | Example Usage                                              |
|-----------------------|-----------------------------------------------------|------------------------------------------|------------------------------------------------------------|
| `->withStatus($status)` | Sets the HTTP status code                          | `$status` (int): HTTP status code         | `response()->view('home')->withStatus(404);` |
| `->header($name, $value)` | Sets a single response header                       | `$name` (string): Header name<br>$value` (string): Header value | `response()->view('home')->header('Content-Type', 'text/html');` |
| `->withHeaders($headers)` | Sets multiple response headers                      | `$headers` (array): Associative array of headers | `response()->view('home')->withHeaders(['Content-Type' => 'application/json', 'X-Header-One' => 'Header Value']);` |
| `->cookie($name, $value)` | Sets a cookie                                      | `$name` (string): Cookie name<br>$value` (string): Cookie value | `response()->view('home')->cookie('name', 'tom');` |
| `->withFile($file)`     | Sets the response to be a file download               | `$file` (string): File path               | `response()->custom('file content')->withFile('/path/to/file');` |

For more detailed information on Workerman's response chaining methods, visit [Workerman Response Manual](https://manual.workerman.net/doc/en/http/response.html) or check the file located at `vendor/workerman/workerman/Protocols/Http/Response.php`.

## CSRF Token Usage

To include a CSRF token in your forms, you can use the `csrf` function provided by Twig Extension. Here's an example of how to include the CSRF token in a form:

```html
<form method="post" action="/submit">
    {{ csrf() }}
    <!-- Form fields here -->
    <button type="submit">Submit</button>
</form>
```

This will generate the necessary CSRF token for the form submission.


## Laravel Query Builder

To use Laravel's Query Builder, you can use the `DB` facade. Here is an example:

```php
use Flareon\Support\Facades\DB;

public function getDataQueryBuilder() {
    return DB::table('users')->get();
```

For more information on Laravel's Query Builder, refer to the [Laravel Query Builder Documentation](https://laravel.com/docs/11.x/queries).

## Laravel ORM

For Laravel's Eloquent ORM, you should use your model classes. Here's an example:

```php
use App\Models\User;

public function getDataORM() {
    return User::all();
}

```

For more detailed information on Laravel's Eloquent ORM, check out the [Laravel Eloquent Documentation](https://laravel.com/docs/5.0/eloquent).

## Logging

### Overview

The logging system in this application is simplified using the `Flareon\Support\Facades\Log` facade. This setup allows for easy configuration and usage of logging throughout your application. All configurations are managed via the `config/logging.php` file, and changes are automatically reflected without needing to modify the logging setup code directly.

### Configuration

#### `config/logging.php`

The `logging.php` configuration file allows you to specify the logging channels, paths, and levels. The configuration is as follows:

```php
return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],

        'single' => [
            'driver' => 'single',
            'path' => __DIR__ . '/../storage/logs/monolog.log',
            'level' => 'debug',
        ],

        // Additional channels can be added here
    ],
];
```

- **`default`**: The default log channel to use.
- **`channels`**: Defines different logging channels and their settings.
    - **`stack`**: Allows you to stack multiple channels.
    - **`single`**: Logs messages to a single file.

### Usage

#### Basic Logging

You can use the `Log` facade to log messages at different levels. Here’s how you can use it:

```php
use Flareon\Support\Facades\Log;

// Log an informational message
Log::info('This is an informational message.');

// Log a warning message
Log::warning('This is a warning message.');

// Log an error message
Log::error('This is an error message.');
```

### Customizing Log Channels

To customize log channels, modify the `config/logging.php` file:

- **Log Path**: Adjust the `path` value to specify where the logs should be saved.
- **Log Level**: Set the `level` to control the verbosity of the logs. Options include `debug`, `info`, `notice`, `warning`, `error`, `critical`, `alert`, and `emergency`.

### Example of Log Configuration

To log messages to a database, you can add a custom channel to the configuration:

```php
'channels' => [
    'database' => [
        'driver' => 'custom',
        'handler' => \Monolog\Handler\SomeDatabaseHandler::class,
        'level' => 'error',
        'connection' => [
            'host' => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'logs'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
        ],
    ],
],
```

### Notes

- Ensure that any changes to the `logging.php` file are correctly reflected in your application's logging behavior.
- The `Log` facade is a simple way to interact with your logging setup without needing to directly handle Monolog instances.

For more detailed configuration options and advanced usage, refer to the Monolog documentation: [Monolog Documentation](https://github.com/Seldaek/monolog).

