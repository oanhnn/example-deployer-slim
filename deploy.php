<?php
// require common recipe
require 'recipe/common.php';
require 'vendor/deployphp/recipes/recipes/configure.php';

//set('ssh_type', 'ext-ssh2');

/**
 * Set parameters
 */
set('repository', 'git@github.com:oanhnn/slim-skeleton.git');
set('keep_releases', 5);
set('shared_dirs', [
    'tmp',
    'public/upload',
]);
set('shared_files', [
    'app/config/app.php',
]);
set('writable_dirs', [
    'tmp',
    'public/upload',
]);
set('writable_use_sudo', false); // Using sudo in writable commands?

/**
 * Deploy start, prepare deploy directory
 */
task('deploy:start', function() {
    cd('~');
    run("if [ ! -d {{deploy_path}} ]; then mkdir -p {{deploy_path}}; fi");
    cd('{{deploy_path}}');
})->setPrivate();

/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:symlink',
    'cleanup',
])->desc('Deploy your project');

before('deploy:configure', 'deploy:start');
before('deploy:prepare', 'deploy:start');
after('deploy:shared', 'deploy:writable');
after('deploy', 'success');

/**
 * Load stage and list server
 */
//foreach (glob(__DIR__ . '/stage/*.php') as $filename) {
//    include $filename;
//}
serverList(__DIR__ . '/stage/servers.yml');