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
// $app->get('/car/{car_id}', function (Request $request, Response $response){
//     $car_id = $request->getAttribute('car_id');
//     /*  la funcion devuelve un array o null */
//     $car = Car::getFromId($car_id);
//     /*  devuelvo un json de un array con la clave 'cars' con un objeto
//         auto o null. */
//     $json = json_encode( array(  'car' => $car),
//                             JSON_FORCE_OBJECT);
//     return $json;
//     // $response->getBody()->write("Hello, ");
// });
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
            // $stdClass = new stdClass();
            // $stdClass->id = $item->getId();
            // $stdClass->check_in = $item->getCheck_in();
            // $stdClass->emp_chek_in = Employee::getFromId( $item->getEmp_id_chek_in() );
            // $stdClass->car = Car::getFromId($item->getCar_id());
            // $stdClass->location = Location::getFromId($item->getLocation_id());
            // $responseArray[] = $stdClass;
            // prueba
            // $stdClass = new stdClass();
            // $stdClass->id = $item->getId();
            // $stdClass->check_in = $item->getCheck_in();
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
/*  devuelve un json de un array de los autos que estan estacionados y no
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
            // $stdClass = new stdClass();
            // $stdClass->id = $item->getId();
            // $stdClass->check_in = $item->getCheck_in();
            // $stdClass->emp_chek_in = Employee::getFromId( $item->getEmp_id_chek_in() );
            // $stdClass->car = Car::getFromId($item->getCar_id());
            // $stdClass->location = Location::getFromId($item->getLocation_id());
            // $responseArray[] = $stdClass;
            // prueba
            // $stdClass = new stdClass();
            // $stdClass->id = $item->getId();
            // $stdClass->check_in = $item->getCheck_in();
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
/*  devuelve un json con un booleano en outed y el parks cerrado o null. */
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
/*  devuelve un json con un booleano en outed y el parks cerrado o null. */
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
        // if($params['disabled'])
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
// $app->post('/parks', function (Request $request, Response $response){
//     $preJSON = array(   'saved'     => false,
//                         'car'       => NULL,
//                         'location'  => NULL,
//                         'parks'     => NULL
//                     );
//     $params = $request->getParsedBody();
//     $car = Car::getFromId($params['id']);
//     // vd($car); die();
//     $location = Location::getFreeUnreserved();
//     // vd($location);
//     if(isset($car) && isset($location)){
//         $parks = new Parks();
//         $parks->setCar_id($car->getId());
//         $parks->setLocation_id($location->getId());
//         $parks->setEmp_id_chek_in($_SESSION['id']);
//         // $parks->setEmp_id_chek_in('14');
//         $parks_insert_id = $parks->save();
//         // vd($parks_insert_id); die();
//         // si es cero no deberia traer nada.
//         $parks = $parks->getFromId($parks_insert_id);
//         // vd ($parks); die();
//
//         // y no deberia entrar aca.
//         if(isset($parks)){
//             // $parks->setId($saved_id);
//             $preJSON['saved'] = true;
//             $preJSON['car'] = $car;
//             $preJSON['location'] = $location;
//             $preJSON['parks'] = $parks;
//         }
//     }
//     $json = json_encode($preJSON);
//     return $json;
// });
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

$app->get('employee/logout', function (Request $request, Response $response){
    // if (ini_get("session.use_cookies")) {
    //     $params = session_get_cookie_params();
    //     setcookie(session_name(), '', time() - 42000,
    //         $params["path"], $params["domain"],
    //         $params["secure"], $params["httponly"]
    //     );
    // }
    session_unset($_SESSION['loged_in']);
    // $_SESSION['loged_in'] = false;
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
