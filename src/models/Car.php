<?php
//  <Dependencies ----------------------------------------------
require_once("DBAccess.php");
require_once("lib.php");
//  </Dependencies ----------------------------------------------

class Car{
    // <attr ----------------------------------------------------
    private $id;
    private $license;
    private $color;
    private $model;
    private $owner_id;
    private $comment;
    // </attr ----------------------------------------------------

    // <getters and setters --------------------------------------
    public function getId() { return $this->id; }
    public function getLicense() { return $this->license; }
    public function getColor() { return $this->color; }
    public function getModel() { return $this->model; }
    public function getOwner_id() { return $this->owner_id; }
    public function getComment() { return $this->comment; }
    public function setId($value) { $this->id = $value; }
    public function setLicense($value) { $this->license = $value; }
    public function setColor($value) { $this->color = $value; }
    public function setModel($value) { $this->model = $value; }
    public function setOwner_id($value) { $this->owner_id = $value; }
    public function setComment($value) { $this->comment = $value; }
    // </getters and setters --------------------------------------

    public function save(){
        try{
            $dba = DBAccess::getDBAccessObj();
            $query = $dba->getQueryObj("CALL saveCar(:license,
                                                    :color,
                                                    :model,
                                                    :owner_id,
                                                    :comment)");
            $query->bindValue(':license',$this->license, PDO::PARAM_STR);
            $query->bindValue(':color',$this->color, PDO::PARAM_STR);
    		$query->bindValue(':model',$this->model, PDO::PARAM_STR);
    		$query->bindValue(':owner_id', $this->owner_id, PDO::PARAM_INT);
    		$query->bindValue(':comment', $this->comment, PDO::PARAM_STR);
            $query->execute();
            // $query = $dba->getQueryObj("select * from persona where dni = ".$this->dni);
            // $query->execute();
    		// $personaBuscada= $query->fetchObject('persona'); //devuelve una persona
        }catch(Exception $e){
            throw $e;
        }
        // $query = $dba->getQueryObj('select @last_insert_id_car;');
        // $query->execute();
        // $id = $query->fetch(PDO::FETCH_ASSOC);

        // return $personaBuscada->id;
        return $dba->returnLastInsertId();
    }

    public static function getAll(){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM CAR;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Car");
        return $result;
    }

    public static function getFromId($car_id){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM CAR WHERE id = :id");
        $query->bindValue(':id',$car_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Car");
        return $result;
    }

    public static function deleteFromId($car_id)
	{
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("DELETE FROM CAR WHERE id = :id");
		$query->bindValue(':id',$car_id, PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount();
	}

}
