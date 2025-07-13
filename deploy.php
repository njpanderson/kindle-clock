<?php

namespace Deployer;

require 'recipe/laravel.php';

// Project name
set('application', 'kindle-clock');

// Project repository
set('repository', 'git@github.com:njpanderson/kindle-clock.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Hosts
host('nevis')
    ->set('deploy_path', '/var/www/vhosts/kindle-clock.no36.uk');

// Shared files/dirs between deploys
set('shared_files', [
    '.env',
    'database.sqlite'
]);

set('shared_dirs', [
    'storage',
    'kindle_ssh_keys',
]);

// Write permissions on dirs
// add('writable_dirs', [
//     'database'
// ]);

// Build assets locally before deployment starts
task('build:assets', function () {
    runLocally('npm run build');
});

// Upload built assets before symlink
task('upload:assets', function () {
    upload('public/build', '{{release_path}}/public');
});
before('deploy:symlink', 'upload:assets');

// Tasks
desc('Deploy your project');
task('deploy', [
    'build:assets',
    'deploy:prepare',
    'deploy:vendors',
    'artisan:storage:link',
    'artisan:config:cache',
    'artisan:route:cache',
    'artisan:view:cache',
    'artisan:event:cache',
    'artisan:migrate',
    'deploy:publish'
]);
