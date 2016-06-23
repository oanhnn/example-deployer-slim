<?php

server('dev-svr', '192.168.11.3', 22)
    ->user('dev')
    ->forwardAgent()
    ->stage(['dev'])
    ->env('deploy_path', '/var/www/apps/slim')
    ->env('branch', '3.x')

    ->env('app.debug', true)

    ->env('app.mysql.host', '127.0.0.1')
    ->env('app.mysql.port', '3306')
    ->env('app.mysql.username', 'root')
    ->env('app.mysql.password', '')
    ->env('app.mysql.dbname', 'test')
;
