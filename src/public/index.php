<?php
// Disponibilidad para trabajar inmediata.
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


// $app->add(\MWAuthorizer::class . ':userVerification');
// ->add(\MWAuthorizer::class . ':userVerification');

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
})->add(\MWAuthorizer::class . ':userVerification');

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

    // los autos que entraro y salieron para esa fecha/rango.
    $this->get('/outed/{date_from}/{date_to}[/]', \ParksRoutesActions::class . ':getOutedFromRange')->add(\MWAuthorizer::class . ':userVerification');
    $this->get('/outed/{date}[/]', \ParksRoutesActions::class . ':getOutedFromDate')->add(\MWAuthorizer::class . ':userVerification');

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
    /*  devuelve un json de un park o null */
    $this->get('/{parks_id}[/]', \ParksRoutesActions::class . ':getFromId');

    $this->delete('/{parks_id}[/]', \ParksRoutesActions::class . ':deleteFromId');

    // TODO, borrar
    // esto esta aca solo para testear el calculateCost a travez de
    // get -> parks/test_price/:parks_id
    $this->get('/test_price/{parks_id}[/]', \ParksRoutesActions::class . ':calculateCost');


})->add(\MWAuthorizer::class . ':userVerification');

/**********************************************/
/*  Funciones de manejo de la clase Employee  */
/**********************************************/
$app->group('/employee', function () {
    $this->post('/suspend[/]', \EmployeeRoutesActions::class . ':suspendEmployeeFromEmail')->add(\MWAuthorizer::class . ':userVerification');

    // $this->get('/logout', function (Request $request, Response $response){
    //     session_unset($_SESSION['loged_in']);
    //     session_destroy();
    // })->add(\MWAuthorizer::class . ':userVerification');

    $this->get('/check[/]', \EmployeeRoutesActions::class . ':check')->add(\MWAuthorizer::class . ':userVerification');

    $this->get('/{employee_id}[/]', \EmployeeRoutesActions::class . ':getFromId')->add(\MWAuthorizer::class . ':userVerification');

    $this->get('[/]', \EmployeeRoutesActions::class . ':getAll')->add(\MWAuthorizer::class . ':userVerification');

    $this->put('[/]', \EmployeeRoutesActions::class . ':updateFromId')->add(\MWAuthorizer::class . ':userVerification');

    $this->post('[/]', \EmployeeRoutesActions::class . ':save')->add(\MWAuthorizer::class . ':userVerification');

    $this->delete('/{employee_id}[/]', \EmployeeRoutesActions::class . ':delete')->add(\MWAuthorizer::class . ':userVerification');

    $this->post('/verify[/]', \EmployeeRoutesActions::class . ':verify');

    // get logs
    $this->get('/logs/{date_from}/{date_to}[/]', \EmployeeRoutesActions::class . ':logsFromRange')->add(\MWAuthorizer::class . ':userVerification');
    $this->get('/logs/{date}[/]', \EmployeeRoutesActions::class . ':logsFromDate')->add(\MWAuthorizer::class . ':userVerification');
    // cantidad de operacions por empleado
    $this->get('/{employee_id}/operations/{date_from}/{date_to}[/]', \EmployeeRoutesActions::class . ':cantOperationsFromRange')->add(\MWAuthorizer::class . ':userVerification');
    $this->get('/{employee_id}/operations/{date}[/]', \EmployeeRoutesActions::class . ':cantOperationsFromDate')->add(\MWAuthorizer::class . ':userVerification');

});

/*** PRUEBA ********///////////
// $app->get('/ejecutarCodigoConVerificacion', function (Request $request, Response $response) {
//     // $bodyParams = $request->getParsedBody();
//     $response->getBody()->write("SE EJECUTA CODIGO CON VERIFICACION.");
//     /*  tomar un valor pasado por el MW. */
//     $employee = $request->getAttribute('employee');
//     /*  el MW me paso los datos del empleado que mando la solicitud con su jwt. */
//     return $response;
// })->add(\MWAuthorizer::class . ':userVerification');

/**********************************************/
/*  Funciones de manejo de la clase Location  */
/**********************************************/
$app->group('/location', function() {
    $this->get('[/]', \LocationRoutesActions::class . ':getAll');

    $this->get('/most_used/{date}[/]', \LocationRoutesActions::class . ':getMostUsedFromDate');
    $this->get('/most_used/{date_from}/{date_to}[/]', \LocationRoutesActions::class . ':getMostUsedFromRange');

    $this->get('/least_used/{date}[/]', \LocationRoutesActions::class . ':getLeastUsedFromDate');
    $this->get('/least_used/{date_from}/{date_to}[/]', \LocationRoutesActions::class . ':getLeastUsedFromRange');

    $this->get('/unused/{date}[/]', \LocationRoutesActions::class . ':getUnusedFromDate');
    $this->get('/unused/{date_from}/{date_to}[/]', \LocationRoutesActions::class . ':getUnusedFromRange');

    $this->get('/most_used[/]', \LocationRoutesActions::class . ':getMostUsed');
    $this->get('/least_used[/]', \LocationRoutesActions::class . ':getLeastUsed');
    $this->get('/unused[/]', \LocationRoutesActions::class . ':getUnused');

    $this->get('/{location_id}[/]', \LocationRoutesActions::class . ':getFromId');

    $this->put('[/]', \LocationRoutesActions::class . ':updateFromId');

    $this->post('[/]', \LocationRoutesActions::class . ':save');

    $this->delete('/{location_id}[/]', \LocationRoutesActions::class . ':delete');

})->add(\MWAuthorizer::class . ':userVerification');
/******************************************/
/*  Funciones de manejo de la clase Price */
/******************************************/
$app->group('/price', function () {
    $this->post('[/]', \PriceRoutesActions::class . ':save');
})->add(\MWAuthorizer::class . ':userVerification');

$app->run();
