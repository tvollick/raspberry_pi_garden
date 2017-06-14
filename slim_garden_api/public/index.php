<?php
require '../vendor/autoload.php';

// Models
require '../src/models/user.php';
require '../src/models/zone.php';
require '../src/models/pi.php';

require '../src/handlers/exceptions.php';

$config = include('../src/config.php');

$app = new \Slim\App(['settings' => $config]);

$container = $app->getContainer();
$capsule = new \Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container['settings']['db']);
$capsule->setAsGlobal();
$capsule->bootEloquent();
$capsule->getContainer()->singleton(
  Illuminate\Contracts\Debug\ExceptionHandler::class,
  App\Exceptions\Handler::class
);

$app->get('/user/', function($request, $response) {

  // THIS IS NOT WHAT WE WANT TO DO...
  return $response->getBody()->write(User::all()->toJson());
});

$app->get('/user/{id}', function ($request, $response, $args) {
  $id = $args['id'];
  $user = User::find($id);
  $response->getBody()->write($user->toJson());
  return $response;
});

// add user
$app->post('/user/', function ($request, $response, $args) {
  $data = $request->getParsedBody();
  $user = new User();
  $user->name = $data['name'];
  $user->email = $data['email'];
  $user->phone = $data['phone'];

  $user->save();

  return $response->withStatus(201)->getBody()->write($user->toJson());
});

$app->delete('/user/{id}/', function ($request, $response, $args) {
  $id = $args['id'];
  $user = User::find($id);
  $user->delete();

  return $response->withStatus(200);
});

$app->put('/user/{id}', function ($request, $response, $args) {
  $id = $args['id'];
  $data = $request->getParsedBody();
  $user = User::find($id);
  $user->name = $data['name'] ?: $user->name;
  $user->email = $data['email'] ?: $user->email;
  $user->phone = $data['phone'] ?: $user->phone;

  $user->save();
  return $response->getBody()->write($user->toJson());
});



// Add Pi
$app->post('/pi/', function ($request, $response, $args){
  $data = $request->getParsedBody();
  $pi = new Pi();
  $pi->name = $data['name'];

  $pi->save();

  return $response->withStatus(201)->getBody()->write($pi->toJson());
});

// Update Pi settings
$app->put('/pi/{id}', function ($request, $response, $args) {
  $id = $args['id'];
  $data = $request->getParsedBody();
  $pi = Pi::find($id);
  $pi->name = $data['name'] ?: $pi->name;
  $pi->zip = $data['zip'] ?: $pi->zip;

  // todo: need to  solve owner_id issue array vs int
  $pi->owner_id = $data['owner_id'] ?: $pi->owner_id;

  $pi->save();
  return $response->getBody()->write($pi->toJson());
});

// Add Zone
$app->post('/zone/', function ($request, $response, $args) {
  $data = $request->getParsedBody();
  $zone = new Zone();

  $zone->save();
  return $response->withStatus(201)->getBody()->write($zone->toJson()); 
});

// UPDATE Zone Settings
$app->put('/zone/{id}', function ($request, $response, $args) {
  $id = $args['id'];
  $data = $request->getParsedBody();
  $zone = Zone::find($id);
  $zone->name = $data['name'] ?: $zone->name;
  $zone->watered_daily = $data['watered_daily'] ?: $zone->watered_daily;
  $zone->watering_time = $data['watering_time'] ?: $zone->watering_time;
  $zone->watering_duration = $data['watering_duration'] ?: $zone->watering_duration;
  $zone->pi_id = $data['pi_id'] ?: $zone->pi_id;

  $zone->save();
  return $response->getBody()->write($zone->toJson());
});

$app->run();
