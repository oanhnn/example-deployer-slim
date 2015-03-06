<?php

server('dev-svr', '192.168.11.3', 22)
    ->user('dev')
    ->forwardAgent()
    ->stage(['dev'])
    ->env('deploy_path', '/var/www/apps/slim')
    ->env('branch', 'master')
    
    ->env('app.mode', 'development')
    ->env('app.debug', true)
    
    ->env('app.cookies.encrypt', false)
    ->env('app.cookies.lifetime', '2 hours')
    ->env('app.cookies.path', '/')
    ->env('app.cookies.domain', null)
    ->env('app.cookies.secure', false)
    ->env('app.cookies.httponly', false)
    ->env('app.cookies.secret_key', '83cugZvQ67Cm39P2RN7x81G67i37RXlq')
    
    ->env('app.mysql.host', '127.0.0.1')
    ->env('app.mysql.port', '3306')
    ->env('app.mysql.username', 'root')
    ->env('app.mysql.password', '')
    ->env('app.mysql.dbname', 'test')
    ->env('app.mysql.options', [])
;
