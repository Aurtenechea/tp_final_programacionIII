<?php
/* get put post delete*/
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

require_once('../models/Car.php');
require_once('../models/Employee.php');
require_once('../models/Location.php');
require_once('../models/Parks.php');
require_once('../models/Price.php');

$app = new \Slim\App;
session_start();

/****************************************/
/*  Funciones de manejo de la clase Car */
/****************************************/
/*  devuelve un json de un array de autos o null */
$app->get('/car', function (Request $request, Response $response){
    /*  preseteo el valor de retorno a null */
    $result = null;
    /*  getAll devuelve un array de autos o bien null. */
    $cars = Car::getAll();
    /*  lo convierto a json siendo $cars un array o null. */
    $result = json_encode( array('cars' => $cars) );
    /*  devuelvo un json de un array con la clave 'cars' con un array
        de autos o null. */
    return $result;
});
/*  devuelve un json de un de auto o null */
$app->get('/car/{car_license}', function (Request $request, Response $response){
    $car_license = $request->getAttribute('car_license');
    /*  la funcion devuelve un array o null */
    // vd($car_license);die;
    $car = Car::getFromLicense($car_license);
    /*  devuelvo un json de un array con la clave 'cars' con un objeto
        auto o null. */
    $json = json_encode( array('car' => $car) );
    return $json;
});
/*  devuelve un json con un buleano en updated y el auto modificado en car o null */
$app->put('/car', function (Request $request, Response $response){
    $preJSON = array(   'updated' => false,
                        'car' => NULL );
    $params = $request->getParsedBody();
    $car = Car::getFromId($params['id']);
    $car->setLicense($params['license']);
    $car->setColor($params['color']);
    $car->setBrand($params['brand']);
    $car->setOwner_id($params['owner_id']);
    $car->setComment($params['comment']);
    $updated_id = Car::updateFromId($car);
    if($updated_id){
        $preJSON['updated'] = true;
        $preJSON['car'] = $car;
    }
    $json = json_encode($preJSON);
    return $json;
});
/*  devuelve un json con un booleado en saved y el auto estacionado. */
$app->post('/car', function (Request $request, Response $response){
    $preJSON = array(   'saved' => false,
                        'car' => NULL );
    $params = $request->getParsedBody();
    $car = new Car();
    $car->setColor($params['color']);
    $car->setBrand($params['brand']);
    $car->setLicense($params['license']);
    $car->setComment($params['comment']);
    $car->setOwner_id($_SESSION['id']); // el id del empleado.
    $saved_id = $car->save();
    if($saved_id){
        $car->setId($saved_id);
        $preJSON['saved'] = true;
        $preJSON['car'] = $car;
    }
    $json = json_encode($preJSON);
    return $json;
});
/*  devuelve un json con un booleado en deleted y el auto borrado. */
$app->delete('/car/{car_id}', function (Request $request, Response $response){
    $preJSON = array(   'deleted' => false,
                        'car' => NULL );
    $car_id = $request->getAttribute('car_id');
    $car = Car::getFromId($car_id);
    $deleted = 0;
    if(isset($car)){
        $deleted = Car::deleteFromId($car->getId());
    }
    if($deleted){
        $preJSON['deleted'] = true;
        $preJSON['car'] = $car;
    }
    $json = json_encode($preJSON);
    return $json;
});


/*******************************************/
/*  Funciones de manejo de la clase Parks  */
/*******************************************/
/*  devuelve un json de un array de los autos que estan estacionados y no
    salieron. */
$app->get('/parks/still_in', function (Request $request, Response $response){
    /*  preseteo el array de respuesta. */
    $responseArray = [];
    /*  getAll devuelve un array de parks o bien null. */
    $result = Parks::getAllStillIn();
    /*  si no esta vacio lo recorro. */
    if( !empty($result) ){
        /*  Recorre los parks y va creando cada objeto con los valores necesarios,
            reemplazando location id y car id por el objeto correspondiente. */
        foreach ($result as $item) {
            $item->emp_chek_in = Employee::getFromId( $item->getEmp_id_chek_in() );
            $item->car = Car::getFromId($item->getCar_id());
            $item->location = Location::getFromId($item->getLocation_id());
            $responseArray[] = $item;
        }
    }
    $responseArray = empty($responseArray) ? null : $responseArray;
    /*  devuelvo un json de un array con la clave 'parks' con un array
        de parks o null. */
    $result = json_encode(array('parks' => $responseArray));
    return $result;
});
/*  devuelve un json de un array de los autos que estan estacionados y ya
    salieron. */
$app->get('/parks/outed', function (Request $request, Response $response){
    /*  preseteo el array de respuesta. */
    $responseArray = [];
    /*  getAll devuelve un array de parks o bien null. */
    $result = Parks::getAllOuted();
    /*  si no esta vacio lo recorro. */
    if( !empty($result) ){
        /*  Recorre los parks y va creando cada objeto con los valores necesarios,
            reemplazando location id y car id por el objeto correspondiente. */
        foreach ($result as $item) {
            $item->emp_chek_in = Employee::getFromId( $item->getEmp_id_chek_in() );
            $item->car = Car::getFromId($item->getCar_id());
            $item->location = Location::getFromId($item->getLocation_id());
            $responseArray[] = $item;
        }
    }
    $responseArray = empty($responseArray) ? null : $responseArray;
    /*  devuelvo un json de un array con la clave 'parks' con un array
        de parks o null. */
    $result = json_encode(array('parks' => $responseArray));
    return $result;
});
/*  es para sacar un auto del estacionamiento. devuelve un json con un booleano en
    outed y el parks cerrado o null. */
$app->get('/parks/out_car/license/{car_license}', function (Request $request, Response $response){
    /*  preseteo la respuesta. */
    $preJSON = array(   'outed' => false,
                        'parks' => NULL );
    $car_license = $request->getAttribute('car_license');
    /*  traigo el objeto parks a modificar. si no existe devuelve null. */
    // $is_parked = Parks::isStillInFromLicense($car_license);
    $parks = Parks::getFromLicense($car_license);
    if(isset($parks)){
        // $parks->setEmp_id_chek_out($_SESSION['id']);
        // para probar con postman. borrar y habilitar la de arriba
        $parks->setEmp_id_chek_out(30);
        $outed = $parks->outCar();
        if($outed){
            $preJSON['outed'] = true;
            $preJSON['parks'] = Parks::getFromId($parks->getId());
        }
    }
    $json = json_encode($preJSON);
    return $json;
});
/*  es para sacar un auto del estacionamiento. devuelve un json con un booleano en outed y el parks cerrado o null. */
$app->get('/parks/out_car/{parks_id}', function (Request $request, Response $response){
    /*  preseteo la respuesta. */
    $preJSON = array(   'outed' => false,
                        'parks' => NULL );
    $parks_id = $request->getAttribute('parks_id');
    /*  traigo el objeto parks a modificar. si no existe devuelve null. */
    $parks = Parks::getFromId($parks_id);
    if(isset($parks)){
        // $parks->setEmp_id_chek_out($_SESSION['id']);
        // para probar con postman. borrar y habilitar la de arriba
        $parks->setEmp_id_chek_out(30);
        $outed = $parks->outCar();
        if($outed){
            $preJSON['outed'] = true;
            $preJSON['parks'] = Parks::getFromId($parks_id);
        }
    }
    $json = json_encode($preJSON);
    return $json;
});
/*  devuelve un json con un booleado en saved y null o el objeto en car,
    location y parks. */
$app->post('/parks', function (Request $request, Response $response){
    $preJSON = array(   'saved'     => false,
                        'car'       => NULL,
                        'location'  => NULL,
                        'parks'     => NULL
                    );
    $params = $request->getParsedBody();
    $car = Car::getFromLicense($params['license']);
    $isIn = Parks::isStillInFromLicense($params['license']);
    if(!$isIn){
        if(empty($car)){
            $car = new Car();
            $car->setLicense($params['license']);
            $car->setBrand($params['brand']);
            $car->setColor($params['color']);
            $car->setDisabled( json_decode($params['disabled']) ? '1' : '0' );
            /*  esto no va.. el awner id no sirve mas. */
            // $car->setOwner_id($_SESSION['id']);
            $car->setOwner_id(30);
            $car->save();
            $car = Car::getFromLicense($params['license']);
        }
        $location = $car->getDisabled() ? Location::getFreeReserved() : Location::getFreeUnreserved();
        if(!empty($car) && !empty($location)){
            $parks = new Parks();
            $parks->setCar_id($car->getId());
            $parks->setLocation_id($location->getId());
            // $parks->setEmp_id_chek_in($_SESSION['id']);
            $parks->setEmp_id_chek_in('30');
            $parks_insert_id = $parks->save();
            // si es cero no deberia traer nada...
            $parks = $parks->getFromId($parks_insert_id);
            // ...y no deberia entrar aca.
            if(!empty($parks)){
                $preJSON['saved'] = true;
                $preJSON['car'] = $car;
                $preJSON['location'] = $location;
                $preJSON['parks'] = $parks;
            }
        }
    }
    $json = json_encode($preJSON);
    return $json;
});
/**********************************************/
/*  Funciones de manejo de la clase Employee  */
/**********************************************/
$app->get('/employee', function (Request $request, Response $response){
    $employees = null;
    $employees = Employee::getAll();
    $json = json_encode( array(  'employees' => $employees) );
    return $json;
});
$app->get('/employee/check', function (Request $request, Response $response){
    $log = array(  'loged_in' => false );
    if( isset($_SESSION['loged_in']) && $_SESSION['loged_in'] ){
        $log['loged_in'] = true;
    }
    $json = json_encode($log);
    return $json;
});
$app->get('/employee/{employee_id}', function (Request $request, Response $response){
    $employee_id = $request->getAttribute('employee_id');
    $employee = Employee::getFromId($employee_id);
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
    $employee = new Employee();
    $employee->setFirst_name($params['first_name']);
    $employee->setLast_name($params['last_name']);
    $employee->setEmail($params['email']);
    $employee->setShift($params['shift']);
    $employee->setPassword($params['password']);
    $employee->setState($params['state']);
    $saved_id = $employee->save();
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
    $preJSON = array(   'deleted' => false,
                        'employee' => NULL );
    $employee_id = $request->getAttribute('employee_id');
    $employee = Employee::getFromId($employee_id);
    $deleted = 0;
    if(isset($employee)){
        $deleted = Employee::deleteFromId($employee->getId());
    }
    if($deleted){
        $preJSON['deleted'] = true;
        $preJSON['employee'] = $employee;
    }
    $json = json_encode(  $preJSON,
                            JSON_FORCE_OBJECT);
    return $json;
});
/*  logueo de un empleado. */
$app->post('/employee/verify', function (Request $request, Response $response){
    $preJSON = array(   'loged_in' => false,
                        'employee' => NULL );
    $params = $request->getParsedBody();
    $employee = new Employee();
    $employee->setEmail($params['email']);
    $employee->setPassword($params['password']);
    $loged_id = $employee->verify($params['email'], $params['password']);
    if($loged_id){
        $preJSON['loged_in'] = true;
        $preJSON['employee'] = $employee;
    }
    $json = json_encode(  $preJSON);
    return $json;
});
$app->get('employee/logout', function (Request $request, Response $response){
    session_unset($_SESSION['loged_in']);
    session_destroy();
});
/**********************************************/
/*  Funciones de manejo de la clase Location  */
/**********************************************/
$app->get('/location', function (Request $request, Response $response){
    $locations = null;
    $locations = Location::getAll();
    $json = json_encode( array(  'locations' => $locations) );
    return $json;
});
$app->get('/location/{location_id}', function (Request $request, Response $response){
    $location_id = $request->getAttribute('location_id');
    $location = Location::getFromId($location_id);
    $json = json_encode( array(  'location' => $location));
    return $json;
});
$app->put('/location', function (Request $request, Response $response){
    $preJSON = array(   'updated' => false,
                        'location' => NULL );
    $params = $request->getParsedBody();
    $location = Location::getFromId($params['id']);
    $location->setFloor($params['floor']);
    $location->setSector($params['sector']);
    $location->setNumber($params['number']);
    $location->setReserved($params['reserved']);
    $updated_id=Location::updateFromId($location);
    if($updated_id){
        $preJSON['updated'] = true;
        $preJSON['location'] = $location;
    }
    $json = json_encode($preJSON);
    return $json;
});
$app->post('/location', function (Request $request, Response $response){
    $preJSON = array(   'saved' => false,
                        'location' => NULL );
    $params = $request->getParsedBody();
    $location = new Location();
    $location->setFloor($params['floor']);
    $location->setSector($params['sector']);
    $location->setNumber($params['number']);
    $location->setReserved($params['reserved']);
    $saved_id = $location->save();
    if($saved_id){
        $location->setId($saved_id);
        $preJSON['saved'] = true;
        $preJSON['location'] = $location;
    }
    $json = json_encode($preJSON);
    return $json;
});
$app->delete('/location/{location_id}', function (Request $request, Response $response){
    $preJSON = array(   'deleted' => false,
                        'location' => NULL );
    $location_id = $request->getAttribute('location_id');
    $location = Location::getFromId($location_id);
    $deleted = 0;
    if(isset($location)){
        $deleted = Location::deleteFromId($location->getId());
    }
    if($deleted){
        $preJSON['deleted'] = true;
        $preJSON['location'] = $location;
    }
    $json = json_encode($preJSON);
    return $json;
});
/******************************************/
/*  Funciones de manejo de la clase Price */
/******************************************/
$app->post('/price', function (Request $request, Response $response){
    $preJSON = array(   'saved' => false,
                        'price' => NULL );
    $params = $request->getParsedBody();
    $price = new Price();
    $price->setHour($params['hour']);
    $price->setHalf_day($params['half_day']);
    $price->setDay($params['day']);
    $saved_id = $price->save();
    if($saved_id){
        $price->setId($saved_id);
        $preJSON['saved'] = true;
        $preJSON['price'] = $price;
    }
    $json = json_encode($preJSON);
    return $json;
});
$app->run();
