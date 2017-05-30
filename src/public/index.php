<?php
/* get put post delete*/
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

require_once('../models/Car.php');

$app = new \Slim\App;

$app->get('/car', function (Request $request, Response $response){
    $cars = Car::getAll();
    var_dump($cars);
    $response->getBody()->write("get para get all");
    return $response;
});

$app->get('/car/{car_id}', function (Request $request, Response $response){
    $car_id = $request->getAttribute('car_id');
    $car = Car::getFromId($car_id);
    var_dump($car);
    // $response->getBody()->write("Hello, ");
    return $response;
});

$app->put('/car/{car_id}', function (Request $request, Response $response){
    $car_id = $app->$request->put('color');
    $params = $request->getParsedBody();
    // var_dump($params);
    $car = Car::getFromId($car_id);
    // var_dump($car);
    $response->getBody()->write("Color, $car_id" . $params['color']);
    return $response;
});

$app->post('/car', function (Request $request, Response $response){
    $params = $request->getParsedBody();
    $car = new Car();
    $car->setColor($params['color']);
    $car->setModel($params['model']);
    $car->setLicense($params['license']);
    $car->setComment($params['comment']);
    $car->setOwner_id($params['owner_id']);
    $saved_id = $car->save();
    $response->getBody()->write("se inserto con el id: ".$saved_id);
    return $response;
});

$app->delete('/car/{car_id}', function (Request $request, Response $response){
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $car_id");
    return $response;
});

$app->run();