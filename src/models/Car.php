<?php
//  <Dependencies ----------------------------------------------
require_once("DBAccess.php");
require_once("lib.php");
//  </Dependencies ----------------------------------------------

class Car implements JsonSerializable{
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

    /* para poder serializar con atributos privados. */
    public function jsonSerialize(){
        return get_object_vars($this);
    }

    // <API methods **************************************
    public function save(){
        try{
            $dba = DBAccess::getDBAccessObj();
            $query = $dba->getQueryObj("CALL saveCar(
                                                    :license,
                                                    :color,
                                                    :model,
                                                    :owner_id,
                                                    :comment
                                                );"
                                            );
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
        return json_encode($result);
    }
    public static function getFromId($car_id){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM CAR WHERE id = :id");
        $query->bindValue(':id',$car_id, PDO::PARAM_INT);
        $query->execute();
        $car = $query->fetchAll(PDO::FETCH_CLASS, "Car");
        // vd($car);
        if (!isset($car[0])){
            $car = array();
        }
        else{
            $car = $car[0];
        }
        return $car;
    }
    public static function deleteFromId($car_id){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("DELETE FROM CAR WHERE id = :id");
		$query->bindValue(':id',$car_id, PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount();
	}
    public static function updateFromId($user){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("UPDATE CAR
                        				set
                                            license=:license,
                                            color=:color,
                                            model=:model,
                                            owner_id=:owner_id,
                                            comment=:comment
                        			    WHERE id=:id ;"
                                );
        $query->bindValue(':id', $user->getId(), PDO::PARAM_INT);
		$query->bindValue(':license', $user->getLicense(), PDO::PARAM_STR);
        $query->bindValue(':color', $user->getColor(), PDO::PARAM_STR);
        $query->bindValue(':model', $user->getModel(), PDO::PARAM_STR);
        $query->bindValue(':owner_id', $user->getOwner_id(), PDO::PARAM_INT);
        $query->bindValue(':comment', $user->getComment(), PDO::PARAM_STR);
		$query->execute();
		return $query->rowCount();
	}
    // </API methods **************************************



}
