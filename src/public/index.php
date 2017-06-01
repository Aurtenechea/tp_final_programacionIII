<?php
/* get put post delete*/
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

require_once('../models/Car.php');
require_once('../models/Employee.php');
require_once('../models/User.php');

$app = new \Slim\App;
/****************************************/
/*  Funciones de manejo de la clase Car */
/****************************************/
$app->get('/car', function (Request $request, Response $response){
    $cars = null;
    $cars = Car::getAll();
    // $response->getBody()->write("get para get all");
    $json = json_encode(    array(  'cars' => $cars),
                            JSON_FORCE_OBJECT);
    return $json;
});
$app->get('/car/{car_id}', function (Request $request, Response $response){
    $car_id = $request->getAttribute('car_id');
    $car = Car::getFromId($car_id);
    // var_dump($car);
    // $response->getBody()->write("Hello, ");
    $json = json_encode(    array(  'car' => $car),
                            JSON_FORCE_OBJECT);
    return $json;
});
$app->put('/car', function (Request $request, Response $response){
    $preJSON = array(   'updated' => false,
                        'car' => NULL );
    $params = $request->getParsedBody();
    $car = Car::getFromId($params['id']);
    $car->setLicense($params['license']);
    $car->setColor($params['color']);
    $car->setModel($params['model']);
    $car->setOwner_id($params['owner_id']);
    $car->setComment($params['comment']);
    $updated_id=Car::updateFromId($car);
    // $response->getBody()->write("Color" . $params['color']);
    if($updated_id){
        $preJSON['updated'] = true;
        $preJSON['car'] = $car;
    }
    $json = json_encode(    $preJSON,
                            JSON_FORCE_OBJECT);
    return $json;
});
$app->post('/car', function (Request $request, Response $response){
    $preJSON = array(   'saved' => false,
                        'car' => NULL );
    $params = $request->getParsedBody();
    $car = new Car();
    $car->setColor($params['color']);
    $car->setModel($params['model']);
    $car->setLicense($params['license']);
    $car->setComment($params['comment']);
    $car->setOwner_id($params['owner_id']);
    $saved_id = $car->save();
    // $response->getBody()->write("se inserto con el id: ".$saved_id);
    if($saved_id){
        $car->setId($saved_id);
        $preJSON['saved'] = true;
        $preJSON['car'] = $car;
    }
    $json = json_encode(  $preJSON,
                            JSON_FORCE_OBJECT);
    return $json;
});
$app->delete('/car/{car_id}', function (Request $request, Response $response){
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $car_id");
    return $response;
});



/**********************************************/
/*  Funciones de manejo de la clase Employee  */
/**********************************************/
$app->get('/employee', function (Request $request, Response $response){
    $employees = null;
    $employees = Employee::getAll();
    // $response->getBody()->write("get para get all");
    $json = json_encode(    array(  'employees' => $employees),
                            JSON_FORCE_OBJECT);
    return $json;
});
$app->get('/employee/{employee_id}', function (Request $request, Response $response){
    $employee_id = $request->getAttribute('employee_id');
    $employee = Employee::getFromId($employee_id);
    // var_dump($employee);
    // $response->getBody()->write("Hello, ");
    $json = json_encode(    array(  'employee' => $employee),
                            JSON_FORCE_OBJECT);
    return $json;
});
$app->put('/employee', function (Request $request, Response $response){
    $preJSON = array(   'updated' => false,
                        'employee' => NULL );
    $params = $request->getParsedBody();
    $employee = Employee::getFromId($params['id']);
    $employee->setFirst_name($params['first_name']);
    $employee->setLast_name($params['last_name']);
    $employee->setEmail($params['email']);
    $employee->setShift($params['shift']);
    $employee->setPassword($params['password']);
    $employee->setState($params['state']);
    $updated_id=Employee::updateFromId($employee);
    // $response->getBody()->write("Last_name" . $params['last_name']);
    if($updated_id){
        $preJSON['updated'] = true;
        $preJSON['employee'] = $employee;
    }
    $json = json_encode(    $preJSON,
                            JSON_FORCE_OBJECT);
    return $json;
});
$app->post('/employee', function (Request $request, Response $response){
    $preJSON = array(   'saved' => false,
                        'employee' => NULL );
    $params = $request->getParsedBody();
    // vd($params);die();
    $employee = new Employee();
    // $employee->setId($params['id']);
    $employee->setFirst_name($params['first_name']);
    $employee->setLast_name($params['last_name']);
    $employee->setEmail($params['email']);
    $employee->setShift($params['shift']);
    $employee->setPassword($params['password']);
    $employee->setState($params['state']);
    $saved_id = $employee->save();
    // $response->getBody()->write("se inserto con el id: ".$saved_id);
    if($saved_id){
        $employee->setId($saved_id);
        $preJSON['saved'] = true;
        $preJSON['employee'] = $employee;
    }
    $json = json_encode(  $preJSON,
                            JSON_FORCE_OBJECT);
    return $json;
});
$app->delete('/employee/{employee_id}', function (Request $request, Response $response){
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $car_id");
    return $response;
});



/*****************************************/
/*  Funciones de manejo de la clase User */
/*****************************************/
$app->get('/user', function (Request $request, Response $response){
    $users = User::getAll();
    var_dump($users);
    $response->getBody()->write("get para get all: ". json_encode($users[0]));
    return $response;
});
$app->get('/user/{user_id}', function (Request $request, Response $response){
    $user_id = $request->getAttribute('user_id');
    $user = User::getFromId($user_id);
    var_dump($user);
    // $response->getBody()->write("Hello, ");
    return $response;
});
$app->put('/user', function (Request $request, Response $response){
    $params = $request->getParsedBody();
    $user = User::getFromId($params['id']);
    $user->setMail($params['mail']);
    $user->setPass($params['pass']);
    $user->setState($params['state']);
    User::updateFromId($user);
    return $response;
});
$app->post('/user', function (Request $request, Response $response){
    $params = $request->getParsedBody();
    $user = new User();
    $user->setMail($params['mail']);
    $user->setPass($params['pass']);
    $user->setState($params['state']);
    $saved_id = $user->save();
    $response->getBody()->write("se inserto con el id: ".$saved_id);
    return $response;
});
$app->delete('/user/{user_id}', function (Request $request, Response $response){
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $user_id");
    return $response;
});


$app->run();
