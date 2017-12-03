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
        $location = empty($location) ? null : $location[0];
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
        $query = $dba->getQueryObj("SELECT  L.*
                                        FROM LOCATION AS L
                                        LEFT JOIN
                                            (SELECT * FROM PARKS WHERE ISNULL(check_out))AS P
                                                ON L.ID = P.location_id
                                        WHERE
                                            ISNULL(P.check_in)
                                        AND
                                            reserved = 0
                                            limit 1;"
                                    );
		$query->execute();
        $location = $query->fetchAll(PDO::FETCH_CLASS, "Location");
        $location = empty($location) ? null : $location[0];
        return $location;
	}
    public static function getFreeReserved(){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT  L.*
                                        FROM LOCATION AS L
                                        LEFT JOIN
                                            (SELECT * FROM PARKS WHERE ISNULL(check_out))AS P
                                                ON L.ID = P.location_id
                                        WHERE
                                            ISNULL(P.check_in)
                                        AND
                                            reserved = 1
                                            limit 1;"
                                    );
        $query->execute();
        $location = $query->fetchAll(PDO::FETCH_CLASS, "Location");
        $location = empty($location) ? null : $location[0];
        return $location;
    }
    public static function getMostUsed(){
      $dba = DBAccess::getDBAccessObj();
      $query = $dba->getQueryObj("SELECT count(*) AS cant, L.*, P.location_id FROM PARKS AS P INNER JOIN LOCATION AS L ON L.id = P.location_id GROUP BY location_id ORDER BY cant DESC LIMIT 1;" );
      $query->execute();
      $location = $query->fetchAll(PDO::FETCH_CLASS, "Location");
      $location = empty($location) ? null : $location[0];
      return $location;
    }

    // TODO, seleccionar una location en la que el id no este en la lista de
    // location_ids usadas en PARKS   OR   la que count sea menor.
    public static function getLeastUsed(){
      $dba = DBAccess::getDBAccessObj();
      $query = $dba->getQueryObj("SELECT count(*) AS cant, L.*, P.location_id FROM PARKS AS P INNER JOIN LOCATION AS L ON L.id = P.location_id GROUP BY location_id ORDER BY cant ASC LIMIT 1;" );
      $query->execute();
      $location = $query->fetchAll(PDO::FETCH_CLASS, "Location");
      $location = empty($location) ? null : $location[0];
      return $location;
    }
    // </API methods **************************************
}
