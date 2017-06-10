<?php
//  <Dependencies ----------------------------------------------
require_once("DBAccess.php");
require_once("lib.php");
//  </Dependencies ----------------------------------------------

class Parks implements JsonSerializable{
    // <attr ----------------------------------------------------
    private $id;
    private $car_id;
    private $location_id;
    private $check_in;
    private $check_out;
    private $emp_id_chek_in;
    private $emp_id_chek_out;
    private $cost;
    // </attr ----------------------------------------------------

    // <getters and setters --------------------------------------
    public function getId() { return $this->id; }
    public function getCar_id() { return $this->car_id; }
    public function getLocation_id() { return $this->location_id; }
    public function getCheck_in() { return $this->check_in; }
    public function getCheck_out() { return $this->check_out; }
    public function getEmp_id_chek_in() { return $this->emp_id_chek_in; }
    public function getEmp_id_chek_out() { return $this->emp_id_chek_out; }
    public function getCost() { return $this->cost; }
    public function setId($value) { $this->id = $value; }
    public function setCar_id($value) { $this->car_id = $value; }
    public function setLocation_id($value) { $this->location_id = $value; }
    public function setCheck_in($value) { $this->check_in = $value; }
    public function setCheck_out($value) { $this->check_out = $value; }
    public function setEmp_id_chek_in($value) { $this->emp_id_chek_in = $value; }
    public function setEmp_id_chek_out($value) { $this->emp_id_chek_out = $value; }
    public function setCost($value) { $this->cost = $value; }
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
            $query = $dba->getQueryObj("INSERT INTO PARKS (     car_id,
                                                                location_id,
                                                                check_in,
                                                                emp_id_chek_in
                                                            )
                                            VALUES (:car_id,
                                                    :location_id,
                                                    NOW(),
                                                    :emp_id_chek_in
                                                );"
                                            );
            $query->bindValue(':car_id',$this->car_id, PDO::PARAM_INT);
            $query->bindValue(':location_id',$this->location_id, PDO::PARAM_INT);
    		// $query->bindValue(':check_in',$this->check_in, PDO::PARAM_STR);
            $query->bindValue(':emp_id_chek_in', $this->emp_id_chek_in, PDO::PARAM_INT);
    		// $query->bindValue(':cost', $this->cost, PDO::PARAM_STR);
            $query->execute();

            // $query = $dba->getQueryObj("select * from persona where dni = ".$this->dni);
            // $query->execute();
    		// $personaBuscada= $query->fetchObject('persona'); //devuelve una persona
        }catch(Exception $e){
            throw $e;
        }
        // $query = $dba->getQueryObj('select @last_insert_id_location;');
        // $query->execute();
        // $id = $query->fetch(PDO::FETCH_ASSOC);
        // return $personaBuscada->id;
        return $dba->returnLastInsertId();
    }

    public static function getFromId($parks_id){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM PARKS WHERE id = :id");
        $query->bindValue(':id',$parks_id, PDO::PARAM_INT);
        $query->execute();
        $parks = $query->fetchAll(PDO::FETCH_CLASS, "Parks");
        // vd($parks);die();
        if (!isset($parks[0])){
            $parks = null;
        }
        else{
            $parks = $parks[0];
        }
        return $parks;
    }

    // public static function getAll(){
    //     $dba = DBAccess::getDBAccessObj();
    //     $query = $dba->getQueryObj("SELECT * FROM PARKS;");
    //     $query->execute();
    //     $result = $query->fetchAll(PDO::FETCH_CLASS, "Parks");
    //     return $result;
    // }
    //
    // public static function deleteFromId($parks_id){
	// 	$dba = DBAccess::getDBAccessObj();
	// 	$query = $dba->getQueryObj("DELETE FROM PARKS WHERE id = :id");
	// 	$query->bindValue(':id',$parks_id, PDO::PARAM_INT);
	// 	$query->execute();
	// 	return $query->rowCount();
	// }
    // public static function updateFromId($user){
	// 	$dba = DBAccess::getDBAccessObj();
	// 	$query = $dba->getQueryObj("UPDATE PARKS
    //                     				set
    //                                         car_id=:car_id,
    //                                         location_id=:location_id,
    //                                         check_in=:check_in,
    //                                         check_out=:check_out,
    //                                         emp_id_chek_in=:emp_id_chek_in,
    //                                         emp_id_chek_out=:emp_id_chek_out,
    //                                         cost=:cost
    //                     			    WHERE id=:id ;"
    //                             );
    //     $query->bindValue(':id', $user->getId(), PDO::PARAM_INT);
	// 	$query->bindValue(':car_id', $user->getCar_id(), PDO::PARAM_INT);
    //     $query->bindValue(':location_id', $user->getLocation_id(), PDO::PARAM_STR);
    //     $query->bindValue(':check_in', $user->getCheck_in(), PDO::PARAM_STR);
    //     $query->bindValue(':check_out', $user->getCheck_out(), PDO::PARAM_BOOL);
    //     $query->bindValue(':emp_id_chek_in', $user->getEmp_id_chek_in(), PDO::PARAM_BOOL);
    //     $query->bindValue(':emp_id_chek_out', $user->getEmp_id_chek_out(), PDO::PARAM_BOOL);
    //     $query->bindValue(':cost', $user->getCost(), PDO::PARAM_BOOL);
	// 	$query->execute();
	// 	return $query->rowCount();
	// }
    // public static function getFreeUnreserved($user){
	// 	$dba = DBAccess::getDBAccessObj();
	// 	$query = $dba->getQueryObj("SELECT *
    //                                     FROM PARKS AS L
    //                                     LEFT JOIN PARKS AS P
    //                                         ON L.ID = P.location_id
    //                                     WHERE
    //                                         reserved = 0
    //                                         AND ISNULL(P.check_out)
    //                                     limit 1;"
    //                             );
	// 	$query->execute();
    //     $parks = $query->fetchAll(PDO::FETCH_CLASS, "Parks");
    //     if (!isset($parks[0])){
    //         $parks = null;
    //     }
    //     else{
    //         $parks = $parks[0];
    //     }
    //     return $parks;
	// }


    // </API methods **************************************

}
