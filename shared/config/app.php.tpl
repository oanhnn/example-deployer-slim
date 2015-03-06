<?php

// define application environment: development or production
defined('APP_ENV') || define('APP_ENV', getenv('APP_ENV') ? getenv('APP_ENV') : {{app.mode}});

return [
    'App' => [
        // Application
        'mode'                => APP_ENV,
        // Debugging
        'debug'               => {{app.debug}},
        // Logging
//        'log.writer'          => null,
//        'log.level'           => \Slim\Log::DEBUG,
//        'log.enabled'         => true,
        // View
        'templates.path'      => realpath(APP_PATH . '/src/templates'),
        //'view' => '\Slim\View',
        // Cookies
        'cookies.encrypt'     => {{app.cookies.encrypt}},
        'cookies.lifetime'    => {{app.cookies.lifetime}},
        'cookies.path'        => {{app.cookies.path}},
        'cookies.domain'      => {{app.cookies.domain}},
        'cookies.secure'      => {{app.cookies.secure}},
        'cookies.httponly'    => {{app.cookies.httponly}},
        // Encryption
        'cookies.secret_key'  => {{app.cookies.secret_key}},
        'cookies.cipher'      => MCRYPT_RIJNDAEL_256,
        'cookies.cipher_mode' => MCRYPT_MODE_CBC,
        // HTTP
        'http.version'        => '1.1',
    ],
    'DB'  => [
        'dsn'      => "mysql:host={{app.mysql.host}};post={{app.mysql.port}};dbname={{app.mysql.dbname}};charset=utf8",
        'username' => {{app.mysql.username}},
        'password' => {{app.mysql.password}},
        'options'  => {{app.mysql.options}}
    ]
];