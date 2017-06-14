<?php
return [
  'determineRouteBeforeAppMiddleware' => false,
  'outputBuffering' => false,
  'displayErrorDetails' => true,
  'db' => [
    'driver' => 'pgsql',
    'host' => 'localhost',
    'port' => '5432',
    'database' => 'smartgarden_slim',
    'username' => 'smartgarden_slim',
    'password' => 'smartgarden_slim',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci'
  ]
];
