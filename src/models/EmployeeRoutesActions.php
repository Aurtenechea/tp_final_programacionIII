<?php
class EmployeeRoutesActions
{
 	public function suspendEmployeeFromEmail($request, $response, $args) {
        // $employee_id = $request->getAttribute('employee_id');
        $params = $request->getParsedBody();
        $employee = Employee::getFromEmail($params['email']);
        $employee->setState('suspend');
        $result = Employee::updateFromId($employee);
        /*  si se suspendio, osea se modifico el empleado, volver a
            traerlo de la db. */
        $employee = $result ? Employee::getFromEmail($params['email']) : $employee;
        $json = json_encode(array('employee' => $employee));
        return $json;
    }

    public function check($request, $response, $args) {
        $employee = $request->getAttribute('employee');
        $preJSON = array(   'loged_in' => true,
                            'employee' => $employee );
        $response = $response->withJson($preJSON);
        return $response;
    }

    public function cantOperationsFromDate($request, $response, $args) {
        $date = $request->getAttribute('date');
        $emp_id = $request->getAttribute('employee_id');

        $result = Employee::getCantOperationsCheckInFromDate($date, $emp_id);
        $result += Employee::getCantOperationsCheckOutFromDate($date, $emp_id);
        $json = json_encode(array('cant_operations' => $result));
        return $json;
    }
    public function cantOperationsFromRange($request, $response, $args) {
        $date_from = $request->getAttribute('date_from');
        $date_to = $request->getAttribute('date_to');
        $emp_id = $request->getAttribute('employee_id');

        $result = Employee::getCantOperationsCheckInFromRange($date_from, $date_to, $emp_id);
        $result += Employee::getCantOperationsCheckOutFromRange($date_from, $date_to, $emp_id);
        $json = json_encode(array('cant_operations' => $result));
        return $json;
    }



    public function logsFromDate($request, $response, $args) {
        $date = $request->getAttribute('date');
        $result = Employee::getLogsFromDate($date);
        $json = json_encode(array('employees_logs' => $result));
        return $json;
    }
    public function logsFromRange($request, $response, $args) {
        $date_from = $request->getAttribute('date_from');
        $date_to = $request->getAttribute('date_to');
        $result = Employee::getLogsFromRange($date_from, $date_to);
        $json = json_encode(array('employees_logs' => $result));
        return $json;
    }
    public function getFromId($request, $response, $args) {
        $employee_id = $request->getAttribute('employee_id');
        $employee = Employee::getFromId($employee_id);
        $json = json_encode(array('employee' => $employee));
        return $json;
    }
    public function getAll($request, $response, $args) {
        $employees = null;
        $employees = Employee::getAll();
        $json = json_encode( array(  'employees' => $employees) );
        return $json;
    }
    //
    public function updateFromId($request, $response, $args) {
        $preJSON = array(   'updated' => false,
                            'employee' => NULL );
        $params = $request->getParsedBody();
        $employee_id = $request->getAttribute('employee_id');

        $employee = Employee::getFromId($params['id']);
        $employee->setFirst_name($params['first_name']);
        $employee->setLast_name($params['last_name']);
        $employee->setEmail($params['email']);
        $employee->setShift($params['shift']);
        $employee->setPassword($params['password']);
        $employee->setState($params['state']);
        $updated_id = Employee::updateFromId($employee);
        if($updated_id){
            $preJSON['updated'] = true;
            $preJSON['employee'] = $employee;
        }
        $json = json_encode(    $preJSON,
                                JSON_FORCE_OBJECT);
        return $json;
    }

    public function save($request, $response, $args) {
        // return 'hola';
        $preJSON = array(   'saved' => false,
                            'employee' => NULL );
        $params = $request->getParsedBody();
        $employee = new Employee();
        $employee->setRol($params['rol']);
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
        $json = json_encode($preJSON);
        return $json;
    }

    public function delete($request, $response, $args) {
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
    }

    public function verify($request, $response, $args) {
        $preJSON = array(   'loged_in' => false,
                            'employee' => NULL );
        $params = $request->getParsedBody();
        // echo('hola');
        // vd($params);die;
        $employee = new Employee();
        $employee->setEmail($params['email']);
        $employee->setPassword($params['password']);
        $loged_id = $employee->verify($params['email'], $params['password']);
        if($loged_id){
            // $employee->saveLog();

            $employee = Employee::getFromEmail($employee->getEmail());
            $employee->setPassword('');
            $empJson = json_encode($employee);
            $token = JWToken::create($employee);

            $preJSON['loged_in'] = true;
            $preJSON['jwt'] = $token;
            $preJSON['employee'] = $employee;
        }
        $json = json_encode($preJSON);
        return $json;
    }

}
