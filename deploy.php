<?php
// require common recipe
require 'recipe/common.php';
require __DIR__ . '/stage/dev.php';

/**
 * Set parameters
 */
set('repository', 'git@github.com:oanhnn/slim-skeleton.git');
set('keep_releases', 5);
set('shared_dirs', [
    'tmp/cache',
    'tmp/logs',
    'vendor',
    'webroot/upload',
]);
set('shared_files', [
    'config/app.php',
    'composer.lock',
]);
set('writable_dirs', [
    'tmp/cache',
    'tmp/logs',
    'webroot/upload',
]);
set('writable_use_sudo', false); // Using sudo in writable commands?

/**
 * Deploy start, prepare deploy directory
 */
task('deploy:start', function() {
    cd('~');
    run("if [ ! -d {deploy_path} ]; then mkdir -p {deploy_path}; fi");
    cd('{deploy_path}');
})->setPrivate();

/**
 * Make shared_dirs and configure files from templates
 */
task('configure', function () {

    /**
     * Compiler template of configure files
     * 
     * @param string $contents
     * @return string
     */
    $compiler = function ($contents) {
        if (preg_match_all('/\{\{(.+?)\}\}/', $contents, $matches)) {
            foreach ($matches[1] as $name) {
                $value = env()->get($name);
                if (is_null($value) || is_bool($value) || is_array($value) || is_string($value)) {
                    $value = var_export($value, true);
                }
                $contents = str_replace('{{' . $name . '}}', $value, $contents);
            }
        }
        return $contents;
    };

    // Create shared dir if does not exist
    foreach (get('shared_dirs') as $dir) {
        run("mkdir -p {deploy_path}/shared/$dir");
    }

    $finder   = new \Symfony\Component\Finder\Finder();
    $iterator = $finder
        ->files()
        ->name('*.tpl')
        ->in(__DIR__ . '/shared');

    $tmpDir = sys_get_temp_dir();

    /* @var $file \Symfony\Component\Finder\SplFileInfo */
    foreach ($iterator as $file) {
        $success = false;
        // Make tmp file
        $tmpFile = tempnam($tmpDir, 'tmp');
        if (!empty($tmpFile)) {
            try {
                $contents = $compiler($file->getContents());
                $target   = preg_replace('/\.tpl$/', '', $file->getRelativePathname());
                // Put contents and upload tmp file to server
                if (file_put_contents($tmpFile, $contents) > 0) {
                    upload($tmpFile, 'shared/' . $target);
                    $success = true;
                }
            } catch (\Exception $e) {
                $success = false;
            }
            // Delete tmp file
            unlink($tmpFile);
        }
        if ($success) {
            writeln(sprintf("<info>✔</info> %s", $file->getRelativePathname()));
        } else {
            writeln(sprintf("<fg=red>✘</fg=red> %s", $file->getRelativePathname()));
        }
    }
})->desc('Generate and upload configure files to `shared` folder');

/**
 * Main task
 */
task('deploy', [
    'deploy:start',
    'deploy:prepare',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:symlink',
    'cleanup',
    'success',
])->desc('Deploy your project');

before('configure', 'deploy:start');