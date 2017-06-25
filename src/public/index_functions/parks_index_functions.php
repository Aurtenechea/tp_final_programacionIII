<?php
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
