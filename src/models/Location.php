<?php
//  <Dependencies ----------------------------------------------
require_once("DBAccess.php");
require_once("lib.php");
//  </Dependencies ----------------------------------------------

class Location implements JsonSerializable{
    // <attr ----------------------------------------------------
    private $id;
    private $floor;
    private $sector;
    private $number;
    private $reserved;
    // </attr ----------------------------------------------------

    // <getters and setters --------------------------------------
    public function getId() { return $this->id; }
    public function getFloor() { return $this->floor; }
    public function getSector() { return $this->sector; }
    public function getNumber() { return $this->number; }
    public function getReserved() { return $this->reserved; }
    public function setId($value) { $this->id = $value; }
    public function setFloor($value) { $this->floor = $value; }
    public function setSector($value) { $this->sector = $value; }
    public function setNumber($value) { $this->number = $value; }
    public function setReserved($value) { $this->reserved = $value; }
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
            $query = $dba->getQueryObj("INSERT INTO LOCATION (  floor,
                                                                sector,
                                                                number,
                                                                reserved
                                                            )
                                            VALUES (:floor,
                                                    :sector,
                                                    :number,
                                                    :reserved
                                                );"
                                            );
            $query->bindValue(':floor',$this->floor, PDO::PARAM_INT);
            $query->bindValue(':sector',$this->sector, PDO::PARAM_STR);
    		$query->bindValue(':number',$this->number, PDO::PARAM_STR);
    		$query->bindValue(':reserved', $this->reserved, PDO::PARAM_BOOL);
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
    public static function getAll(){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM LOCATION;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Location");
        return $result;
    }
    public static function getFromId($location_id){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM LOCATION WHERE id = :id");
        $query->bindValue(':id',$location_id, PDO::PARAM_INT);
        $query->execute();
        $location = $query->fetchAll(PDO::FETCH_CLASS, "Location");
        // vd($location);
        if (!isset($location[0])){
            $location = null;
        }
        else{
            $location = $location[0];
        }
        return $location;
    }
    public static function deleteFromId($location_id){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("DELETE FROM LOCATION WHERE id = :id");
		$query->bindValue(':id',$location_id, PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount();
	}
    public static function updateFromId($user){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("UPDATE LOCATION
                        				set
                                            floor=:floor,
                                            sector=:sector,
                                            number=:number,
                                            reserved=:reserved
                        			    WHERE id=:id ;"
                                );
        $query->bindValue(':id', $user->getId(), PDO::PARAM_INT);
		$query->bindValue(':floor', $user->getFloor(), PDO::PARAM_INT);
        $query->bindValue(':sector', $user->getSector(), PDO::PARAM_STR);
        $query->bindValue(':number', $user->getNumber(), PDO::PARAM_STR);
        $query->bindValue(':reserved', $user->getReserved(), PDO::PARAM_BOOL);
		$query->execute();
		return $query->rowCount();
	}
    public static function getFreeUnreserved(){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("SELECT L.*
                                        FROM LOCATION AS L
                                        LEFT JOIN PARKS AS P
                                            ON L.ID = P.location_id
                                        WHERE
                                            reserved = 0
                                            AND (
                                                ISNULL(P.check_in)
                                                OR
                                                NOT ISNULL(P.check_in)
                                                AND NOT ISNULL(P.check_out)
                                            )
                                        LIMIT 1;"
                                    );
		$query->execute();
        $location = $query->fetchAll(PDO::FETCH_CLASS, "Location");
        if (!isset($location[0])){
            $location = null;
        }
        else{
            $location = $location[0];
        }
        // vd( $location);die();
        return $location;
	}


    // </API methods **************************************

}
