<?php
//  <Dependencies ----------------------------------------------
require_once("DBAccess.php");
require_once("lib.php");
//  </Dependencies ----------------------------------------------

class Price implements JsonSerializable{
    // <attr ----------------------------------------------------
    private $id;
    private $hour;
    private $half_day;
    private $day;
    private $on_date;
    // </attr ----------------------------------------------------

    // <getters and setters --------------------------------------
    public function getId() { return $this->id; }
    public function getHour() { return $this->hour; }
    public function getHalf_day() { return $this->half_day; }
    public function getDay() { return $this->day; }
    public function getOn_date() { return $this->on_date; }
    public function setId($value) { $this->id = $value; }
    public function setHour($value) { $this->hour = $value; }
    public function setHalf_day($value) { $this->half_day = $value; }
    public function setDay($value) { $this->day = $value; }
    public function setOn_date($value) { $this->on_date = $value; }
    // </getters and setters --------------------------------------

    /* para poder serializar con atributos privados. */
    public function jsonSerialize(){
        return get_object_vars($this);
    }

    // <API methods **************************************
    public function save(){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("INSERT into PRICE (hour, half_day, day, on_date)
                                        values (:hour,
                                                :half_day,
                                                :day,
                                                NOW()
                                            );"
                                    );
        $query->bindValue(':hour',$this->hour, PDO::PARAM_STR);
        $query->bindValue(':half_day',$this->half_day, PDO::PARAM_STR);
		$query->bindValue(':day',$this->day, PDO::PARAM_STR);
        $query->execute();
        return $dba->returnLastInsertId();
    }
    /*  trae de la base de datos el auto con el id pasado como param.
        @return  un objeto auto o null. */
    public static function getPriceFromDate($datetime){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM PRICE WHERE on_date <= :datetime ORDER BY on_date DESC LIMIT 1;");
        $query->bindValue(':datetime', $datetime, PDO::PARAM_STR);
        $query->execute();
        /*  si la query devuelve una fila la convierto en un auto */
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Price");
        /*  si es un array vacio asiganarle null sino quitar el
            objeto del array y asignar solo el objeto*/
        $result = empty($result) ? null : $result[0];
        return $result;
    }
    /*  trae de la base de datos todos los autos.
        @return  array de autos o null. */
    public static function getAll(){
        /*  preseteo el valor de retorno a null */
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM PRICE;");
        $query->execute();
        /*  si la query devuelve alguna fila las convierto en un
            array de autos sino en un array vacio */
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Price");
        /*  si es un array vacio asiganarle null sino dejar el array */
        $result = empty($result) ? null : $result;
        /*  devuelvo un array de autos o null */
        return $result;
    }
    /*  trae de la base de datos el auto con el id pasado como param.
        @return  un objeto auto o null. */
    public static function getFromId($price_id){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM PRICE WHERE id = :id");
        $query->bindValue(':id', $price_id, PDO::PARAM_INT);
        $query->execute();
        /*  si la query devuelve una fila la convierto en un auto */
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Price");
        /*  si es un array vacio asiganarle null sino quitar el
            objeto del array y asignar solo el objeto*/
        $result = empty($result) ? null : $result[0];
        return $result;
    }
    public static function deleteFromId($price_id){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("DELETE FROM PRICE WHERE id = :id");
		$query->bindValue(':id',$price_id, PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount();
	}
    /*  devuelve el*/
    public static function updateFromId($price){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("UPDATE PRICE
                        				set
                                            hour=:hour,
                                            half_day=:half_day,
                                            day=:day,
                                            on_date=:on_date
                        			    WHERE id=:id ;"
                                );
        $query->bindValue(':id', $price->getId(), PDO::PARAM_INT);
		$query->bindValue(':hour', $price->getHour(), PDO::PARAM_STR);
        $query->bindValue(':half_day', $price->getHalf_day(), PDO::PARAM_STR);
        $query->bindValue(':day', $price->getDay(), PDO::PARAM_STR);
        $query->bindValue(':on_date', $price->getOn_date(), PDO::PARAM_INT);
		$query->execute();
        $result = $query->rowCount() ? true : false;
		return $result;
	}
    // </API methods **************************************
}
