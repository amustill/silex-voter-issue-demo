<?php

require_once __DIR__ . '/../vendor/autoload.php';

// We really should autoload these, but not for this demo
require_once __DIR__ . '/../src/TestVoter.php';
require_once __DIR__ . '/../src/TestVoterWithDecisionManager.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\SecurityServiceProvider, [
    'security.firewalls' => [
        'admin' => [
            'pattern' => '^/admin',
            'http' => true,
            'users' => [
                'admin' => ['ROLE_ADMIN', '$2y$10$3i9/lVd8UOFIJ6PAMFt8gu3/r5g0qeCJvoSlLCsvMTythye19F77a']
            ]
        ]
    ]
]);

$app->extend('security.voters', function($voters) use ($app) {
    $voters[] = new TestVoterWithDecisionManager( $app['security.access_manager'] ); // Kills the app
    $voters[] = new TestVoter(); // Doesn't kill it
    return $voters;
});

$app->get('/', function() {
    return 'Hello World!';
});

$app->get('/admin', function() {
    return 'Hello Admin!';
});

$app->run();