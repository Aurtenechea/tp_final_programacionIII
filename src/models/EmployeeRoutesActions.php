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
        $log = array(  'loged_in' => false );
        if( isset($_SESSION['loged_in']) && $_SESSION['loged_in'] ){
            $log['loged_in'] = true;
        }
        $json = json_encode($log);
        return $json;
    }
    //
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
    }

    public function save($request, $response, $args) {
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
        $employee = new Employee();
        $employee->setEmail($params['email']);
        $employee->setPassword($params['password']);
        $loged_id = $employee->verify($params['email'], $params['password']);
        if($loged_id){
            $employee = Employee::getFromEmail($employee->getEmail());
            $employee->setPassword('');
            $empJson = json_encode($employee);
            $token = JWToken::create($employee);

            $preJSON['loged_in'] = true;
            $preJSON['jwt'] = $token;
            $preJSON['employee'] = $employee;
        }
        $json = json_encode(  $preJSON);
        return $json;
    }

}
