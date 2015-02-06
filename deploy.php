<?php

require_once __DIR__ . '/bin/symfony.php';
require_once __DIR__ . '/vendor/autoload.php';

$yaml = new \Symfony\Component\Yaml\Yaml();
$parameters = $yaml->parse(__DIR__ . '/app/config/parameters.yml');

server('theatre', $parameters['parameters']['prod_server_ip'])
    ->path('/var/www/' . $parameters['parameters']['domain'])
    ->user('root', $parameters['parameters']['prod_server_pass'])
;

set('repository', $parameters['parameters']['https_github_repository_url']);
set('env', 'dev');

task('deploy:theatre:end', function () {
    run('chmod 777 -R current/app/cache current/app/logs');
    run('cp current/web/app.php current/web/app_dev.php');
    run("sed -i -e 's,prod,dev,g' current/web/app_dev.php");
    run("sed -i -e 's,app.php,app_dev.php,g' current/web/.htaccess");
});

/**
 * Main task
 */
task('deploy:staging', [
    'deploy:start',
    'deploy:prepare',
    'deploy:update_code',
    'deploy:shared',
    'deploy:vendors',
    'fixtures:reload',
    'deploy:symlink',
    'deploy:theatre:end',
    'cleanup',
    'deploy:end'
])->desc('Deploy your project');

/**
 * Success message
 */
after('deploy:staging', function () {
    $host = config()->getHost();
    writeln("<info>Successfully deployed on</info> <fg=cyan>$host</fg=cyan>");
});

