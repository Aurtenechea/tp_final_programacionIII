<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

require_once '../models/lib.php';
require_once('../models/Car.php');
require_once('../models/Employee.php');
require_once('../models/Location.php');
require_once('../models/Parks.php');
require_once('../models/Price.php');
require_once '../models/JWToken.php';
require_once '../models/MWAuthorizer.php';
require_once '../models/CarRoutesActions.php';
require_once '../models/ParksRoutesActions.php';
require_once '../models/EmployeeRoutesActions.php';
require_once '../models/LocationRoutesActions.php';
require_once '../models/PriceRoutesActions.php';


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);
session_start();


$app->add(\MWAuthorizer::class . ':userVerification');

/****************************************/
/*  Funciones de manejo de la clase Car */
/****************************************/
$app->group('/car', function(){
    /*  devuelve un json de un array de autos o null */
    $this->get('[/]', \CarRoutesActions::class . ':getAll');
    /*  devuelve un json de un de auto o null */
    $this->get('/{car_license}[/]', \CarRoutesActions::class . ':getFromLicense');
    /*  devuelve un json con un buleano en updated y el auto modificado en car o null */
    $this->put('[/]', \CarRoutesActions::class . ':updateFromId');
    /*  devuelve un json con un booleado en saved y el auto estacionado. */
    $this->post('[/]', \CarRoutesActions::class . ':save');
    /*  devuelve un json con un booleado en deleted y el auto borrado. */
    $this->delete('/{car_id}[/]', \CarRoutesActions::class . ':deleteFromId');
});

/*******************************************/
/*  Funciones de manejo de la clase Parks  */
/*******************************************/
$app->group('/parks', function(){
    /*  devuelve un json de un array de los autos que estan estacionados y no
    salieron. */
    $this->get('/still_in[/]', \ParksRoutesActions::class . ':getAllStillIn');
    /*  devuelve un json de un array de los autos que estan estacionados y ya
        salieron. */
    $this->get('/outed[/]', \ParksRoutesActions::class . ':getAllOuted');
    /*  para sacar un auto del estacionamiento. devuelve un json con un booleano en
        outed y el parks cerrado o null. */
    $this->get('/out_car/license/{car_license}[/]', \ParksRoutesActions::class . ':outCarFromLicense');
    /*  sacar un auto del estacionamiento. devuelve un json con un booleano en
        outed y el parks cerrado o null. */
    $this->get('/out_car/{parks_id}[/]', \ParksRoutesActions::class . ':outCarFromParksId');
    /*  devuelve un json con un booleado en saved y null o el objeto en car,
        location y parks. */
    $this->post('[/]', \ParksRoutesActions::class . ':save');
    /*  devuelve todos los parks. */
    $this->get('[/]', \ParksRoutesActions::class . ':getAll');

});

/**********************************************/
/*  Funciones de manejo de la clase Employee  */
/**********************************************/
$app->group('/employee', function () {
    // $this->post('/suspend', function (Request $request, Response $response){
    //     // $employee_id = $request->getAttribute('employee_id');
    //     $params = $request->getParsedBody();
    //     $employee = Employee::getFromEmail($params['email']);
    //     $employee->setState('suspend');
    //     $result = Employee::updateFromId($employee);
    //     /*  si se suspendio, osea se modifico el empleado, volver a
    //         traerlo de la db. */
    //     $employee = $result ? Employee::getFromEmail($params['email']) : $employee;
    //     $json = json_encode(array('employee' => $employee));
    //     return $json;
    // });
    $this->post('/suspend[/]', \EmployeeRoutesActions::class . ':suspendEmployeeFromEmail');

    $this->get('/logout', function (Request $request, Response $response){
        session_unset($_SESSION['loged_in']);
        session_destroy();
    });

    // $this->get('/check', function (Request $request, Response $response){
    //     $log = array(  'loged_in' => false );
    //     if( isset($_SESSION['loged_in']) && $_SESSION['loged_in'] ){
    //         $log['loged_in'] = true;
    //     }
    //     $json = json_encode($log);
    //     return $json;
    // });
    $this->get('/check[/]', \EmployeeRoutesActions::class . ':check');

    // $this->get('/{employee_id}', function (Request $request, Response $response){
    //     $employee_id = $request->getAttribute('employee_id');
    //     $employee = Employee::getFromId($employee_id);
    //     $json = json_encode(array('employee' => $employee));
    //     return $json;
    // });
    $this->get('/{employee_id}[/]', \EmployeeRoutesActions::class . ':getFromId');


    // $this->get('', function (Request $request, Response $response){
    //     $employees = null;
    //     $employees = Employee::getAll();
    //     $json = json_encode( array(  'employees' => $employees) );
    //     return $json;
    // });
    $this->get('[/]', \EmployeeRoutesActions::class . ':getAll');

    // $this->put('', function (Request $request, Response $response){
    //     $preJSON = array(   'updated' => false,
    //                         'employee' => NULL );
    //     $params = $request->getParsedBody();
    //     $employee = Employee::getFromId($params['id']);
    //     $employee->setFirst_name($params['first_name']);
    //     $employee->setLast_name($params['last_name']);
    //     $employee->setEmail($params['email']);
    //     $employee->setShift($params['shift']);
    //     $employee->setPassword($params['password']);
    //     $employee->setState($params['state']);
    //     $updated_id=Employee::updateFromId($employee);
    //     if($updated_id){
    //         $preJSON['updated'] = true;
    //         $preJSON['employee'] = $employee;
    //     }
    //     $json = json_encode(    $preJSON,
    //                             JSON_FORCE_OBJECT);
    //     return $json;
    // });
    $this->put('[/]', \EmployeeRoutesActions::class . ':updateFromId');

    // $this->post('', function (Request $request, Response $response){
    //     $preJSON = array(   'saved' => false,
    //                         'employee' => NULL );
    //     $params = $request->getParsedBody();
    //     $employee = new Employee();
    //     $employee->setRol($params['rol']);
    //     $employee->setFirst_name($params['first_name']);
    //     $employee->setLast_name($params['last_name']);
    //     $employee->setEmail($params['email']);
    //     $employee->setShift($params['shift']);
    //     $employee->setPassword($params['password']);
    //     $employee->setState($params['state']);
    //     $saved_id = $employee->save();
    //     if($saved_id){
    //         $employee->setId($saved_id);
    //         $preJSON['saved'] = true;
    //         $preJSON['employee'] = $employee;
    //     }
    //     $json = json_encode($preJSON);
    //     return $json;
    // });
    $this->post('[/]', \EmployeeRoutesActions::class . ':save');

    // $this->delete('/{employee_id}', function (Request $request, Response $response){
    //     $preJSON = array(   'deleted' => false,
    //                         'employee' => NULL );
    //     $employee_id = $request->getAttribute('employee_id');
    //     $employee = Employee::getFromId($employee_id);
    //     $deleted = 0;
    //     if(isset($employee)){
    //         $deleted = Employee::deleteFromId($employee->getId());
    //     }
    //     if($deleted){
    //         $preJSON['deleted'] = true;
    //         $preJSON['employee'] = $employee;
    //     }
    //     $json = json_encode(  $preJSON,
    //                             JSON_FORCE_OBJECT);
    //     return $json;
    // });
    $this->delete('/{employee_id}[/]', \EmployeeRoutesActions::class . ':delete');

    /*  logueo de un empleado. */
    // $this->post('/verify', function (Request $request, Response $response){
    //     $preJSON = array(   'loged_in' => false,
    //                         'employee' => NULL );
    //     $params = $request->getParsedBody();
    //     $employee = new Employee();
    //     $employee->setEmail($params['email']);
    //     $employee->setPassword($params['password']);
    //     $loged_id = $employee->verify($params['email'], $params['password']);
    //     if($loged_id){
    //         $employee = Employee::getFromEmail($employee->getEmail());
    //         $employee->setPassword('');
    //         $preJSON['loged_in'] = true;
    //         $preJSON['employee'] = $employee;
    //     }
    //     $json = json_encode(  $preJSON);
    //     return $json;
    // });

    // $this->post('/verify', function (Request $request, Response $response){
    //     $preJSON = array(   'loged_in' => false,
    //                         'employee' => NULL );
    //     $params = $request->getParsedBody();
    //     $employee = new Employee();
    //     $employee->setEmail($params['email']);
    //     $employee->setPassword($params['password']);
    //     $loged_id = $employee->verify($params['email'], $params['password']);
    //     if($loged_id){
    //         $employee = Employee::getFromEmail($employee->getEmail());
    //         $employee->setPassword('');
    //         $empJson = json_encode($employee);
    //         $token = JWToken::create($employee);
    //
    //         $preJSON['loged_in'] = true;
    //         $preJSON['jwt'] = $token;
    //         $preJSON['employee'] = $employee;
    //     }
    //     $json = json_encode(  $preJSON);
    //     return $json;
    // });
    $this->post('/verify[/]', \EmployeeRoutesActions::class . ':verify');

});

/*** PRUEBA ********///////////
$app->get('/ejecutarCodigoConVerificacion', function (Request $request, Response $response) {
    // $bodyParams = $request->getParsedBody();
    $response->getBody()->write("SE EJECUTA CODIGO CON VERIFICACION.");
    /*  tomar un valor pasado por el MW. */
    $employee = $request->getAttribute('employee');
    /*  el MW me paso los datos del empleado que mando la solicitud con su jwt. */
    vd($employee);
    // vd($bodyParams);
    return $response;
})->add(\MWAuthorizer::class . ':userVerification');

/**********************************************/
/*  Funciones de manejo de la clase Location  */
/**********************************************/
$app->group('/location', function () {
    // $this->get('', function (Request $request, Response $response){
    //     $locations = null;
    //     $locations = Location::getAll();
    //     $json = json_encode( array(  'locations' => $locations) );
    //     return $json;
    // });
    $this->get('[/]', \LocationRoutesActions::class . ':getAll');

    // $this->get('/{location_id}', function (Request $request, Response $response){
    //     $location_id = $request->getAttribute('location_id');
    //     $location = Location::getFromId($location_id);
    //     $json = json_encode( array(  'location' => $location));
    //     return $json;
    // });
    $this->get('/{location_id}[/]', \LocationRoutesActions::class . ':getFromId');

    // $this->put('', function (Request $request, Response $response){
    //     $preJSON = array(   'updated' => false,
    //                         'location' => NULL );
    //     $params = $request->getParsedBody();
    //     $location = Location::getFromId($params['id']);
    //     $location->setFloor($params['floor']);
    //     $location->setSector($params['sector']);
    //     $location->setNumber($params['number']);
    //     $location->setReserved($params['reserved']);
    //     $updated_id=Location::updateFromId($location);
    //     if($updated_id){
    //         $preJSON['updated'] = true;
    //         $preJSON['location'] = $location;
    //     }
    //     $json = json_encode($preJSON);
    //     return $json;
    // });
    $this->put('[/]', \LocationRoutesActions::class . ':updateFromId');

    // $this->post('', function (Request $request, Response $response){
    //     $preJSON = array(   'saved' => false,
    //                         'location' => NULL );
    //     $params = $request->getParsedBody();
    //     $location = new Location();
    //     $location->setFloor($params['floor']);
    //     $location->setSector($params['sector']);
    //     $location->setNumber($params['number']);
    //     $location->setReserved($params['reserved']);
    //     $saved_id = $location->save();
    //     if($saved_id){
    //         $location->setId($saved_id);
    //         $preJSON['saved'] = true;
    //         $preJSON['location'] = $location;
    //     }
    //     $json = json_encode($preJSON);
    //     return $json;
    // });
    $this->post('[/]', \LocationRoutesActions::class . ':save');

    // $this->delete('/{location_id}', function (Request $request, Response $response){
    //     $preJSON = array(   'deleted' => false,
    //                         'location' => NULL );
    //     $location_id = $request->getAttribute('location_id');
    //     $location = Location::getFromId($location_id);
    //     $deleted = 0;
    //     if(isset($location)){
    //         $deleted = Location::deleteFromId($location->getId());
    //     }
    //     if($deleted){
    //         $preJSON['deleted'] = true;
    //         $preJSON['location'] = $location;
    //     }
    //     $json = json_encode($preJSON);
    //     return $json;
    // });
    $this->delete('/{location_id}[/]', \LocationRoutesActions::class . ':delete');

});
/******************************************/
/*  Funciones de manejo de la clase Price */
/******************************************/
$app->group('/price', function () {
    // $this->post('', function (Request $request, Response $response){
    //     $preJSON = array(   'saved' => false,
    //                         'price' => NULL );
    //     $params = $request->getParsedBody();
    //     $price = new Price();
    //     $price->setHour($params['hour']);
    //     $price->setHalf_day($params['half_day']);
    //     $price->setDay($params['day']);
    //     $saved_id = $price->save();
    //     if($saved_id){
    //         $price->setId($saved_id);
    //         $preJSON['saved'] = true;
    //         $preJSON['price'] = $price;
    //     }
    //     $json = json_encode($preJSON);
    //     return $json;
    // });
    $this->post('[/]', \PriceRoutesActions::class . ':save');

});

$app->run();
