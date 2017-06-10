<?php
/* get put post delete*/
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';

require_once('../models/Car.php');
require_once('../models/Employee.php');
require_once('../models/User.php');
require_once('../models/Location.php');
require_once('../models/Parks.php');



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
    // $response->getBody()->write("get para get all");
});
$app->get('/car/{car_id}', function (Request $request, Response $response){
    $car_id = $request->getAttribute('car_id');
    /*  la funcion devuelve un array o null */
    $car = Car::getFromId($car_id);
    /*  devuelvo un json de un array con la clave 'cars' con un objeto
        auto o null. */
    $json = json_encode( array(  'car' => $car),
                            JSON_FORCE_OBJECT);
    return $json;
    // $response->getBody()->write("Hello, ");
});
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
    // $response->getBody()->write("Color" . $params['color']);
    if($updated_id){
        $preJSON['updated'] = true;
        $preJSON['car'] = $car;
    }
    $json = json_encode($preJSON);
    return $json;
});
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
    // vd($car);
    // vd($_SESSION);


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
    $preJSON = array(   'deleted' => false,
                        'car' => NULL );
    $car_id = $request->getAttribute('car_id');
    $car = Car::getFromId($car_id);
    $deleted = 0;
    if(isset($car)){
        $deleted = Car::deleteFromId($car->getId());
    }
    // $a = $response->getBody()->write("Hello, $car_id");
    if($deleted){
        $preJSON['deleted'] = true;
        $preJSON['car'] = $car;
    }
    $json = json_encode(  $preJSON,
                            JSON_FORCE_OBJECT);
    return $json;
});



/*******************************************/
/*  Funciones de manejo de la clase Parks  */
/*******************************************/
$app->post('/parks', function (Request $request, Response $response){
    $preJSON = array(   'saved'     => false,
                        'car'       => NULL,
                        'location'  => NULL,
                        'parks'     => NULL
                    );
    $params = $request->getParsedBody();
    $car = Car::getFromId($params['id']);
    // vd($car); die();
    $location = Location::getFreeUnreserved();
    // vd($location);
    if(isset($car) && isset($location)){
        $parks = new Parks();
        $parks->setCar_id($car->getId());
        $parks->setLocation_id($location->getId());
        $parks->setEmp_id_chek_in($_SESSION['id']);
        // $parks->setEmp_id_chek_in('14');
        $parks_insert_id = $parks->save();
        // vd($parks_insert_id); die();
        // si es cero no deberia traer nada.
        $parks = $parks->getFromId($parks_insert_id);
        // vd ($parks); die();

        // y no deberia entrar aca.
        if(isset($parks)){
            // $parks->setId($saved_id);
            $preJSON['saved'] = true;
            $preJSON['car'] = $car;
            $preJSON['location'] = $location;
            $preJSON['parks'] = $parks;
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
    // $response->getBody()->write("get para get all");
    $json = json_encode( array(  'employees' => $employees) );
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
    $preJSON = array(   'deleted' => false,
                        'employee' => NULL );
    $employee_id = $request->getAttribute('employee_id');
    $employee = Employee::getFromId($employee_id);
    $deleted = 0;
    if(isset($employee)){
        $deleted = Employee::deleteFromId($employee->getId());
    }
    // $a = $response->getBody()->write("Hello, $employee_id");
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
    // vd($params);die();
    $employee = new Employee();
    // $employee->setId($params['id']);
    $employee->setEmail($params['email']);
    $employee->setPassword($params['password']);
    $loged_id = $employee->verify($params['email'], $params['password']);
    // $response->getBody()->write("se inserto con el id: ".$saved_id);
    if($loged_id){
        // $employee->setId($saved_id);
        $preJSON['loged_in'] = true;
        $preJSON['employee'] = $employee;
    }
    $json = json_encode(  $preJSON);
    return $json;
});

$app->get('emplyee/logout', function (Request $request, Response $response){
    session_destroy();
    // header('location:http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/public/employee/login/')
});




/**********************************************/
/*  Funciones de manejo de la clase Location  */
/**********************************************/
$app->get('/location', function (Request $request, Response $response){
    $locations = null;
    $locations = Location::getAll();
    // $response->getBody()->write("get para get all");
    // vd($locations);
    $json = json_encode( array(  'locations' => $locations) );
    return $json;
});
$app->get('/location/{location_id}', function (Request $request, Response $response){
    $location_id = $request->getAttribute('location_id');
    $location = Location::getFromId($location_id);
    // var_dump($location);
    // $response->getBody()->write("Hello, ");
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
    // $response->getBody()->write("Last_name" . $params['sector']);
    if($updated_id){
        $preJSON['updated'] = true;
        $preJSON['location'] = $location;
    }
    $json = json_encode(    $preJSON,
                            JSON_FORCE_OBJECT);
    return $json;
});
$app->post('/location', function (Request $request, Response $response){
    $preJSON = array(   'saved' => false,
                        'location' => NULL );
    $params = $request->getParsedBody();
    // vd($params);die();
    $location = new Location();
    // $location->setId($params['id']);
    $location->setFloor($params['floor']);
    $location->setSector($params['sector']);
    $location->setNumber($params['number']);
    $location->setReserved($params['reserved']);
    $saved_id = $location->save();
    // $response->getBody()->write("se inserto con el id: ".$saved_id);
    if($saved_id){
        $location->setId($saved_id);
        $preJSON['saved'] = true;
        $preJSON['location'] = $location;
    }
    $json = json_encode(  $preJSON,
                            JSON_FORCE_OBJECT);
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
    // $a = $response->getBody()->write("Hello, $location_id");
    if($deleted){
        $preJSON['deleted'] = true;
        $preJSON['location'] = $location;
    }
    $json = json_encode(    $preJSON,
                            JSON_FORCE_OBJECT);
    return $json;
});




//
//  HECHO EN CLASE
//
$app->post('/file', function (Request $request, Response $response){
    // $preJSON = array(   'saved' => false,
    //                     'location' => NULL );
    // $params = $request->getParsedBody();
    $files = $request->getUploadedFiles();

    $stream = $files['file']->getStream();
    $size = $files['file']->getSize();
    $error = $files['file']->getError();
    $filename = $files['file']->getClientFilename();
    $mediaType = $files['file']->getClientMediaType();
    $move = $files['file']->moveTo("/var/www/.temp_upload_php/sarasa.jpg");
    $contentType = $request->getContentType();

    echo('$files');
    vd($files['file']);
    echo('$stream');
    vd($stream);
    echo('$size');
    vd($size);
    echo('$error');
    vd($error);
    echo('$filename');
    vd($filename);
    echo('$mediaType');
    vd($mediaType);
    echo('$_FILES');
    vd($_FILES);
    echo('$move');
    vd($move);
    echo('$contentType');
    vd($contentType);
    die();
    // $location = new Location();
    // // $location->setId($params['id']);
    // $location->setFloor($params['floor']);
    // $location->setSector($params['sector']);
    // $location->setNumber($params['number']);
    // $location->setReserved($params['reserved']);
    // $saved_id = $location->save();
    // // $response->getBody()->write("se inserto con el id: ".$saved_id);
    // if($saved_id){
    //     $location->setId($saved_id);
    //     $preJSON['saved'] = true;
    //     $preJSON['location'] = $location;
    // }
    // $json = json_encode(  $preJSON,
    //                         JSON_FORCE_OBJECT);
    // return $json;
});


// /*****************************************/
// /*  Funciones de manejo de la clase User */
// /*****************************************/
// $app->get('/user', function (Request $request, Response $response){
//     $users = User::getAll();
//     var_dump($users);
//     $response->getBody()->write("get para get all: ". json_encode($users[0]));
//     return $response;
// });
// $app->get('/user/{user_id}', function (Request $request, Response $response){
//     $user_id = $request->getAttribute('user_id');
//     $user = User::getFromId($user_id);
//     var_dump($user);
//     // $response->getBody()->write("Hello, ");
//     return $response;
// });
// $app->put('/user', function (Request $request, Response $response){
//     $params = $request->getParsedBody();
//     $user = User::getFromId($params['id']);
//     $user->setMail($params['mail']);
//     $user->setPass($params['pass']);
//     $user->setState($params['state']);
//     User::updateFromId($user);
//     return $response;
// });
// $app->post('/user', function (Request $request, Response $response){
//     $params = $request->getParsedBody();
//     $user = new User();
//     $user->setMail($params['mail']);
//     $user->setPass($params['pass']);
//     $user->setState($params['state']);
//     $saved_id = $user->save();
//     $response->getBody()->write("se inserto con el id: ".$saved_id);
//     return $response;
// });
// $app->delete('/user/{user_id}', function (Request $request, Response $response){
//     $name = $request->getAttribute('name');
//     $response->getBody()->write("Hello, $user_id");
//     return $response;
// });


$app->run();
