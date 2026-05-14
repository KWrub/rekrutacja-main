<?php

require dirname(__DIR__) . '/vendor/autoload.php';

// Force test environment when running tests
if (getenv('SYMFONY_ENV') === 'test' || getenv('APP_ENV') === 'test') {
    $_SERVER['APP_ENV'] = 'test';
    $_ENV['APP_ENV'] = 'test';
    putenv('APP_ENV=test');
    
    // Override DATABASE_URL for test environment
    $_SERVER['DATABASE_URL'] = 'postgres://postgres:postgres@symfony-db:5432/symfony_app_test';
    $_ENV['DATABASE_URL'] = 'postgres://postgres:postgres@symfony-db:5432/symfony_app_test';
    putenv('DATABASE_URL=postgres://postgres:postgres@symfony-db:5432/symfony_app_test');
}
