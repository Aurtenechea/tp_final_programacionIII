<?php
//  <Dependencies ----------------------------------------------
require_once("DBAccess.php");
require_once("lib.php");
//  </Dependencies ----------------------------------------------

class Employee implements JsonSerializable{
    // <attr ----------------------------------------------------
    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $shift;
    private $password;
    private $state;
    // </attr ----------------------------------------------------

    // <getters and setters --------------------------------------
    public function getId() { return $this->id; }
    public function getFirst_name() { return $this->first_name; }
    public function getLast_name() { return $this->last_name; }
    public function getEmail() { return $this->email; }
    public function getShift() { return $this->shift; }
    public function getPassword() { return $this->password; }
    public function getState() { return $this->state; }
    public function setId($value) { $this->id = $value; }
    public function setFirst_name($value) { $this->first_name = $value; }
    public function setLast_name($value) { $this->last_name = $value; }
    public function setEmail($value) { $this->email = $value; }
    public function setShift($value) { $this->shift = $value; }
    public function setPassword($value) { $this->password = $value; }
    public function setState($value) { $this->state = $value; }
    // </getters and setters --------------------------------------

    /* para poder serializar con atributos privados. */
    public function jsonSerialize(){
        return get_object_vars($this);
    }

    // <API methods **************************************
    public function save(){
        // vd($this);die();
        try{
            $dba = DBAccess::getDBAccessObj();
            $query = $dba->getQueryObj("INSERT INTO EMPLOYEE (  first_name,
                                                                last_name,
                                                                email,
                                                                shift,
                                                                password,
                                                                state)
                                            VALUES (:first_name,
                                                    :last_name,
                                                    :email,
                                                    :shift,
                                                    :password,
                                                    :state);"
                                            );
            $query->bindValue(':first_name',$this->first_name, PDO::PARAM_STR);
            $query->bindValue(':last_name',$this->last_name, PDO::PARAM_STR);
    		$query->bindValue(':email',$this->email, PDO::PARAM_STR);
    		$query->bindValue(':shift', $this->shift, PDO::PARAM_STR);
            $query->bindValue(':password', $this->password, PDO::PARAM_STR);
    		$query->bindValue(':state', $this->state, PDO::PARAM_STR);
            $query->execute();

            // $query = $dba->getQueryObj("select * from persona where dni = ".$this->dni);
            // $query->execute();
    		// $personaBuscada= $query->fetchObject('persona'); //devuelve una persona
        }catch(Exception $e){
            throw $e;
        }
        // $query = $dba->getQueryObj('select @last_insert_id_employee;');
        // $query->execute();
        // $id = $query->fetch(PDO::FETCH_ASSOC);
        // return $personaBuscada->id;

        return $dba->returnLastInsertId();
    }
    public static function getAll(){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM EMPLOYEE;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Employee");
        return $result;
    }
    public static function getFromId($employee_id){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM EMPLOYEE WHERE id = :id");
        $query->bindValue(':id',$employee_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Employee");
        // vd($employee);
        /*  si es un array vacio asiganarle null sino dejar el array */
        $result = empty($result) ? null : $result[0];
        // if (!isset($employee[0])){
        //     $employee = null;
        // }
        // else{
        //     $employee = $employee[0];
        // }
        // vd($result); die();
        return $result;
    }
    // public static function getFromEmail($email){
    //     $dba = DBAccess::getDBAccessObj();
    //     $query = $dba->getQueryObj("SELECT * FROM EMPLOYEE WHERE email = :email");
    //     $query->bindValue(':id',$email, PDO::PARAM_STR);
    //     $query->execute();
    //     $employee = $query->fetchAll(PDO::FETCH_CLASS, "Employee");
    //     // vd($employee);
    //     if (!isset($employee[0])){
    //         $employee = null;
    //     }
    //     else{
    //         $employee = $employee[0];
    //     }
    //     return $employee;
    // }
    public static function deleteFromId($employee_id){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("DELETE FROM EMPLOYEE WHERE id = :id");
		$query->bindValue(':id',$employee_id, PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount();
	}
    public static function updateFromId($user){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("UPDATE EMPLOYEE
                        				set
                                            first_name=:first_name,
                                            last_name=:last_name,
                                            email=:email,
                                            shift=:shift,
                                            password=:password,
                                            state=:state

                        			    WHERE id=:id ;"
                                );
        $query->bindValue(':id', $user->getId(), PDO::PARAM_INT);
		$query->bindValue(':first_name', $user->getFirst_name(), PDO::PARAM_STR);
        $query->bindValue(':last_name', $user->getLast_name(), PDO::PARAM_STR);
        $query->bindValue(':email', $user->getEmail(), PDO::PARAM_STR);
        $query->bindValue(':shift', $user->getShift(), PDO::PARAM_STR);
        $query->bindValue(':password', $user->getPassword(), PDO::PARAM_STR);
        $query->bindValue(':state', $user->getState(), PDO::PARAM_STR);
		$query->execute();
		return $query->rowCount();
	}

    public static function verify($email, $password){
        // session_start();
        $_SESSION["loged_in"] = false;
        if( isset($email) && isset($password)){
            $employee = self::getFromEmail($email);
            if(isset($employee) && $employee->getPassword() == $password){
                $_SESSION["loged_in"] = true;
                $_SESSION["id"] = $employee->getId();
                $_SESSION["email"] = $email;
                $_SESSION["first_name"] = $employee->getFirst_name();
                $_SESSION["last_name"] = $employee->getLast_name();
                $_SESSION["shift"] = $employee->getShift();
                $_SESSION["state"] = $employee->getState();
                $_SESSION["password"] = $employee->getPassword();
                $dba = DBAccess::getDBAccessObj();
                $query = $dba->getQueryObj("INSERT INTO EMP_LOG (
                                                                    emp_id,
                                                                    log_in
                                                                )
                                                VALUES (:emp_id,
                                                        NOW()
                                                    );"
                                                );
                $query->bindValue(':emp_id',$_SESSION["id"], PDO::PARAM_INT);
                $query->execute();
            }
        }
        return $_SESSION["loged_in"];
    }

    public static function getFromEmail($email){
        $employee = null;
        if( isset($email) ){
            try{
                $dba = DBAccess::getDBAccessObj();
                $query = $dba->getQueryObj("SELECT  id,
                                                    first_name,
                                                    last_name,
                                                    email,
                                                    shift,
                                                    password,
                                                    state
                                                FROM EMPLOYEE
                                                WHERE email = :email;"
                                            );
        		$query->bindValue(':email',$email, PDO::PARAM_STR);
                $query->execute();
                $employee = $query->fetchAll(PDO::FETCH_CLASS, "Employee");
            }catch(Exception $e){
                throw $e;
            }
            if (!isset($employee[0])){
                $employee = null;
            }
            else{
                $employee = $employee[0];
            }
        }
        return $employee;
    }
    // </API methods **************************************



}
