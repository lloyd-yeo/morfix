<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
        ],

        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '52.221.60.235'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'insta_affiliate'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', 'inst@ffiliates123'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        
        'mysql_master' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_MASTER', '52.221.60.235'),
            'port' => env('DB_PORT_MASTER', '3306'),
            'database' => env('DB_DATABASE_MASTER', 'insta_affiliate'),
            'username' => env('DB_USERNAME_MASTER', 'root'),
            'password' => env('DB_PASSWORD_MASTER', 'inst@ffiliates123'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        
        'mysql_queue' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_QUEUE', '52.221.60.235'),
            'port' => env('DB_PORT_QUEUE', '3306'),
            'database' => env('DB_DATABASE_QUEUE', 'insta_affiliate'),
            'username' => env('DB_USERNAME_QUEUE', 'root'),
            'password' => env('DB_PASSWORD_QUEUE', 'inst@ffiliates123'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        
        'mysql_old' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_2', '52.221.60.235'),
            'port' => env('DB_PORT_2', '3306'),
            'database' => env('DB_DATABASE_2', 'insta_affiliate'),
            'username' => env('DB_USERNAME_2', 'root'),
            'password' => env('DB_PASSWORD_2', 'inst@ffiliates123'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        
        'mysql_master_igsession' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_MASTER_COOKIE', '52.221.60.235'),
            'port' => env('DB_PORT_MASTER_COOKIE', '3306'),
            'database' => env('DB_DATABASE_MASTER_COOKIE', 'morfix'),
            'username' => env('DB_USERNAME_MASTER_COOKIE', 'root'),
            'password' => env('DB_PASSWORD_MASTER_COOKIE', 'inst@ffiliates123'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
        
        'mysql_igsession' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST_3', '52.221.60.235'),
            'port' => env('DB_PORT_3', '3306'),
            'database' => env('DB_DATABASE_3', 'morfix'),
            'username' => env('DB_USERNAME_3', 'root'),
            'password' => env('DB_PASSWORD_3', 'inst@ffiliates123'),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],

        'mysql_add_profile_queues' => [
	        'driver' => 'mysql',
	        'host' => env('DB_HOST_PROFILE_REQUEST', '52.221.60.235'),
	        'port' => env('DB_PORT_PROFILE_REQUEST', '3306'),
	        'database' => env('DB_DATABASE_PROFILE_REQUEST', 'insta_affiliate'),
	        'username' => env('DB_USERNAME_PROFILE_REQUEST', 'root'),
	        'password' => env('DB_PASSWORD_PROFILE_REQUEST', 'inst@ffiliates123'),
	        'charset' => 'utf8mb4',
	        'collation' => 'utf8mb4_unicode_ci',
	        'prefix' => '',
	        'strict' => true,
	        'engine' => null,
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => 'predis',

        'default' => [
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', 6379),
            'database' => 0,
        ],

    ],

];
