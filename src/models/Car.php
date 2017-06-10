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
    private $brand;
    private $owner_id;
    private $comment;
    // </attr ----------------------------------------------------

    // <getters and setters --------------------------------------
    public function getId() { return $this->id; }
    public function getLicense() { return $this->license; }
    public function getColor() { return $this->color; }
    public function getBrand() { return $this->brand; }
    public function getOwner_id() { return $this->owner_id; }
    public function getComment() { return $this->comment; }
    public function setId($value) { $this->id = $value; }
    public function setLicense($value) { $this->license = $value; }
    public function setColor($value) { $this->color = $value; }
    public function setBrand($value) { $this->brand = $value; }
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
            $query = $dba->getQueryObj("INSERT into CAR (license, color, brand, owner_id, comment)
                                            values (:license,
                                                    :color,
                                                    :brand,
                                                    :owner_id,
                                                    :comment);"
                                        );
            $query->bindValue(':license',$this->license, PDO::PARAM_STR);
            $query->bindValue(':color',$this->color, PDO::PARAM_STR);
    		$query->bindValue(':brand',$this->brand, PDO::PARAM_STR);
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
    /*  trae de la base de datos todos los autos.
        @return  array de autos o null. */
    public static function getAll(){
        /*  preseteo el valor de retorno a null */
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM CAR;");
        $query->execute();
        /*  si la query devuelve alguna fila las convierto en un
            array de autos sino en un array vacio */
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Car");
        /*  si es un array vacio asiganarle null sino dejar el array */
        $result = empty($result) ? null : $result;
        /*  devuelvo un array de autos o null */
        return $result;
    }
    /*  trae de la base de datos el auto con el id pasado como param.
        @return  un objeto auto o null. */
    public static function getFromId($car_id){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM CAR WHERE id = :id");
        $query->bindValue(':id', $car_id, PDO::PARAM_INT);
        $query->execute();
        /*  si la query devuelve una fila la convierto en un auto */
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Car");
        /*  si es un array vacio asiganarle null sino quitar el
            objeto del array y asignar solo el objeto*/
        $result = empty($result) ? null : $result[0];
        return $result;
    }
    public static function deleteFromId($car_id){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("DELETE FROM CAR WHERE id = :id");
		$query->bindValue(':id',$car_id, PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount();
	}
    /*  devuelve el*/
    public static function updateFromId($car){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("UPDATE CAR
                        				set
                                            license=:license,
                                            color=:color,
                                            brand=:brand,
                                            owner_id=:owner_id,
                                            comment=:comment
                        			    WHERE id=:id ;"
                                );
        $query->bindValue(':id', $car->getId(), PDO::PARAM_INT);
		$query->bindValue(':license', $car->getLicense(), PDO::PARAM_STR);
        $query->bindValue(':color', $car->getColor(), PDO::PARAM_STR);
        $query->bindValue(':brand', $car->getBrand(), PDO::PARAM_STR);
        $query->bindValue(':owner_id', $car->getOwner_id(), PDO::PARAM_INT);
        $query->bindValue(':comment', $car->getComment(), PDO::PARAM_STR);
		$query->execute();
        $result = $query->rowCount() ? true : false;
		return $result;
	}
    // </API methods **************************************



}
