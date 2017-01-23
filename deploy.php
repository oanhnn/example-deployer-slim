<?php
namespace Deployer;

require 'recipe/common.php';

//set('ssh_type', 'ext-ssh2');

/**
 * Set parameters
 */
set('repository', 'git@github.com:oanhnn/slim-skeleton.git');
set('keep_releases', 5);
set('shared_dirs', ['tmp']);
set('shared_files', ['app/config/app.php']);
set('writable_dirs', ['tmp']);
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
 * Deploy configure
 */
desc('Make configure files for your stage');
task('deploy:configure', function() {
    /**
     * Paser value for template compiler
     *
     * @param array $matches
     * @return string
     */
    $paser = function($matches) {
        if (isset($matches[1])) {
            $value = get($matches[1]);
            if (is_null($value) || is_bool($value) || is_array($value)) {
                $value = var_export($value, true);
            }
        } else {
            $value = $matches[0];
        }
        return $value;
    };

    /**
     * Template compiler
     *
     * @param string $contents
     * @return string
     */
    $compiler = function ($contents) use ($paser) {
        $contents = preg_replace_callback('/\{\{\s*([\w\.]+)\s*\}\}/', $paser, $contents);

        return $contents;
    };

    $finder = new \Symfony\Component\Finder\Finder();
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
                    //run('mkdir -p {{deploy_path}}/shared/' . dirname($target));
                    upload($tmpFile, '{{deploy_path}}/shared/' . $target);
                    $success = true;
                }
            } catch (\Exception $e) {
                //throw new $e;
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
});

/**
 * Main task
 */
task('deploy', [
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
])->desc('Deploy your project');

before('deploy:configure', 'deploy:start');
after('deploy:failed', 'deploy:unlock');
after('deploy:shared', 'deploy:writable');
before('deploy', 'deploy:start');
after('deploy', 'success');

/**
 * Load stage and list server
 */
//foreach (glob(__DIR__ . '/stage/*.php') as $filename) {
//    include $filename;
//}
serverList(__DIR__ . '/stage/servers.yml');
