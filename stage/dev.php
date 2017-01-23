<?php

server('dev-svr', '192.168.11.3', 22)
    ->user('dev')
    ->forwardAgent()
    ->stage(['dev'])
    ->set('deploy_path', '/var/www/apps/slim')
    ->set('branch', '3.x')

    ->set('app.debug', true)
    ->set('app.domain', 'slim-app.dev')

    ->set('app.mysql.host', '127.0.0.1')
    ->set('app.mysql.port', '3306')
    ->set('app.mysql.username', 'root')
    ->set('app.mysql.password', '')
    ->set('app.mysql.dbname', 'test')
;
