<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

session_start();

require '../vendor/autoload.php';

require '../app/handlers/exceptions.php';

$config = include('../app/config.php');

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();

$container['view'] = function ($container) {
  $view = new \Slim\Views\Twig(__DIR__ . '/../resources/views', [
    'cache' => false
  ]);

  $view ->addExtension(new \Slim\Views\TwigExtension(
      $container->router,
      $container->request->getUri()
  ));

  return $view;
};

$container['validator'] = function ($container) {
  return new App\Validation\Validator;
};

$container['HomeController'] = function ($container) {
  return new \App\Controllers\HomeController($container);
};

$container['AuthController'] = function ($container) {
  return new \App\Controllers\Auth\AuthController($container);
};

$app->add(new \App\Middleware\ValidationErrorsMiddleware($container));

// Illuminate is handling all the sql commands.
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule->getContainer()->singleton(
  Illuminate\Contracts\Debug\ExceptionHandler::class,
  App\Exceptions\Handler::class
);

$container['db'] = function ($container) use ($capsule) {
  return $capsule;
};


require __DIR__ . '/../app/routes.php';
