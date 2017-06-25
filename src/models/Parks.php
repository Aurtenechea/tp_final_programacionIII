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
    /*  guarda un parks para eso necesita el id del auto, de la ubicacion y el
        id del empleado actual. Devuelve 0 si no guado y
        el id del parks si se guardo. */
    public function save(){
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
        $query->bindValue(':emp_id_chek_in', $this->emp_id_chek_in, PDO::PARAM_INT);
        $query->execute();
        return $dba->returnLastInsertId();
    }
    /*  quita un auto del estacionamiento seteando el horario de salida, el
        id del empleado actual y el monto a cobrar segun el precio que se haya
        seteado para la fecha de entrada del vehiculo. Si es un auto de
        discapacitado el monto es cero. */
    public function outCar(){
        $result = 0;
        $dba = DBAccess::getDBAccessObj();
        $parks = Parks::getFromId($this->id);
        $car = Car::getFromId($this->car_id);
        $price = Price::getPriceFromDate($parks->getCheck_in());

        if( !empty($parks) ){
            $query = $dba->getQueryObj("UPDATE PARKS SET    check_out =          NOW(),
                                                            emp_id_chek_out =   :emp_id_chek_out
                                            WHERE id = :id;"
                                        );
            $query->bindValue(':id',$this->id, PDO::PARAM_INT);
            $query->bindValue(':emp_id_chek_out', $this->emp_id_chek_out, PDO::PARAM_INT);
            $query->execute();

            $parks = Parks::getFromId($this->id);
            $cost = $car->getDisabled() ? 0 :$parks->calculateCost($price);

            $query = $dba->getQueryObj("UPDATE PARKS SET    cost = :cost
                                            WHERE id = :id;"
                                        );
            $query->bindValue(':id', $this->id, PDO::PARAM_INT);
            $query->bindValue(':cost', $cost, PDO::PARAM_STR);
            $query->execute();
            $parks = Parks::getFromId($this->id);
            // vd($parks);die();
        }
        return $parks;
    }
    /*  calcula el costo del estacionamiento (this) por la diferencia de tiempo
        entre check_in y check_out, segun el precio para la fecha de check_in
        pasada como parametro. */
    public function calculateCost($priceObj){
        $result = 0;
        if(!empty($this->check_in) && !empty($this->check_out)){
            $dba = DBAccess::getDBAccessObj();
            $query = $dba->getQueryObj("SELECT TIMESTAMPDIFF(   DAY,
                                                                :check_in,
                                                                :check_out) AS day_dif,
                                                TIMESTAMPDIFF(   MINUTE,
                                                                :check_inn,
                                                                :check_outt) AS minut_dif;"
                                        );
            $query->bindValue(':check_in',$this->check_in, PDO::PARAM_STR);
            $query->bindValue(':check_out',$this->check_out, PDO::PARAM_STR);
            $query->bindValue(':check_inn',$this->check_in, PDO::PARAM_STR);
            $query->bindValue(':check_outt',$this->check_out, PDO::PARAM_STR);
            $query->execute();
            $result = $query->fetch(PDO::FETCH_ASSOC);
            $day_dif = $result['day_dif'];
            // float
            $hour_dif = (((int)$result['minut_dif']) - ($day_dif * 24 * 60)) / 60;
            $half_day_dif = 0;
            if($hour_dif > 12){
                $half_day_dif = 1;
                $hour_dif = $hour_dif - 12;
            }
            $total = 0;
            $total = $priceObj->getDay() * $day_dif;
            $total += $priceObj->getHalf_day() * $half_day_dif;
            $total += $priceObj->getHour() * $hour_dif;
            // vd($day_dif);
            // vd($priceObj);
            // vd($total);die();
            return $total;
        }
    }
    /*  trae de la base de datos el parks con el id pasado como param.
        @return  un objeto auto o null. */
    public static function getFromId($parks_id){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM PARKS WHERE id = :id");
        $query->bindValue(':id',$parks_id, PDO::PARAM_INT);
        $query->execute();
        $parks = $query->fetchAll(PDO::FETCH_CLASS, "Parks");
        // vd($parks);die();
        /*  si es un array vacio asiganarle null sino dejar el array */
        $parks = empty($parks) ? null : $parks[0];
        return $parks;
    }
    /*  trae de la base de datos el parks con el id pasado como param.
        @return  un objeto auto o null. */
    public static function getFromLicense($car_license){
        $parks = null;
        $dba = DBAccess::getDBAccessObj();
        $car = Car::getFromLicense($car_license);
        if(isset($car)){
            $query = $dba->getQueryObj("SELECT * FROM PARKS WHERE car_id = :car_id AND ISNULL(check_out);");
            $query->bindValue(':car_id', $car->getId(), PDO::PARAM_INT);
            $query->execute();
            $parks = $query->fetchAll(PDO::FETCH_CLASS, "Parks");
        }
        // vd($parks);die();
        /*  si es un array vacio asiganarle null sino dejar el array */
        $parks = empty($parks) ? null : $parks[0];
        return $parks;
    }
    /*  trae de la base de datos los parks que no tengan fecha de salida con.
        @return  un array de autos o null. */
    public static function getAllStillIn(){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM PARKS WHERE ISNULL(check_out);");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Parks");
        // $responseArray = array();
        /*  si es un array vacio asiganarle null sino dejar el array */
        $result = empty($result) ? null : $result;
        return $result;
    }
    /*  devuelve un array de los ultimos 5 parks cerrados o null. */
    public static function getAllOuted(){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM PARKS WHERE NOT ISNULL(check_out) ORDER BY check_out DESC LIMIT 5;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS, "Parks");
        // $responseArray = array();
        /*  si es un array vacio asiganarle null sino dejar el array */
        $result = empty($result) ? null : $result;
        return $result;
    }
    /*  devuelve true o false segun si existe un auto que este estacionado
        con esa patenta. */
    public static function isStillInFromLicense($car_license){
        $car = Car::getFromLicense($car_license);
        if(!empty($car)){
            $dba = DBAccess::getDBAccessObj();
            $query = $dba->getQueryObj("SELECT * FROM PARKS WHERE car_id = :car_id AND ISNULL(check_out) limit 1");
            $query->bindValue(':car_id', $car->getId(), PDO::PARAM_INT);
            $query->execute();
            /*  si la query devuelve una fila la convierto en un auto */
            $result = $query->fetchAll(PDO::FETCH_CLASS, "Car");
            /*  si es un array vacio asiganarle null sino quitar el
                objeto del array y asignar solo el objeto*/
        }
        $result = !empty($result) ? true : false;
        return $result;
    }
    // </API methods **************************************
}
