<?php
class ParksRoutesActions
{

 	public function getAllStillIn($request, $response, $args) {
        /*  preseteo el array de respuesta. */
        $responseArray = [];
        /*  getAll devuelve un array de parks o bien null. */
        $result = Parks::getAllStillIn();
        /*  si no esta vacio lo recorro. */
        if( !empty($result) ){
            /*  Recorre los parks y va creando cada objeto con los valores necesarios,
                reemplazando location id y car id por el objeto correspondiente. */
            foreach ($result as $item) {
                $item->emp_chek_in = Employee::getFromId( $item->getEmp_id_chek_in() );
                $item->car = Car::getFromId($item->getCar_id());
                $item->location = Location::getFromId($item->getLocation_id());
                $responseArray[] = $item;
            }
        }
        $responseArray = empty($responseArray) ? null : $responseArray;
        /*  devuelvo un json de un array con la clave 'parks' con un array
            de parks o null. */
        $result = json_encode(array('parks' => $responseArray));
        return $result;
    }

    public function deleteFromId($request, $response, $args) {
        $preJSON = array(   'deleted' => false,
                            'parks' => NULL );
        $parks_id = $request->getAttribute('parks_id');
        $parks = Parks::getFromId($parks_id);
        $deleted = 0;
        if(isset($parks)){
            $deleted = Parks::deleteFromId($parks->getId());
        }
        if($deleted){
            $preJSON['deleted'] = true;
            $preJSON['parks'] = $parks;
        }
        $json = json_encode($preJSON);
        return $json;
    }

    public function getAllOuted($request, $response, $args) {
        /*  preseteo el array de respuesta. */
        $responseArray = [];
        /*  getAll devuelve un array de parks o bien null. */
        $result = Parks::getAllOuted();
        /*  si no esta vacio lo recorro. */
        if( !empty($result) ){
            /*  Recorre los parks y va creando cada objeto con los valores necesarios,
                reemplazando location id y car id por el objeto correspondiente. */
            foreach ($result as $item) {
                $item->emp_chek_in = Employee::getFromId( $item->getEmp_id_chek_in() );
                $item->car = Car::getFromId($item->getCar_id());
                $item->location = Location::getFromId($item->getLocation_id());
                $responseArray[] = $item;
            }
        }
        $responseArray = empty($responseArray) ? null : $responseArray;
        /*  devuelvo un json de un array con la clave 'parks' con un array
            de parks o null. */
        $result = json_encode(array('parks' => $responseArray));
        return $result;
    }
    //
    public function outCarFromLicense($request, $response, $args) {
        /*  preseteo la respuesta. */
        $preJSON = array(   'outed' => false,
                            'parks' => NULL );
        $car_license = $request->getAttribute('car_license');
        /*  traigo el objeto parks a modificar. si no existe devuelve null. */
        // $is_parked = Parks::isStillInFromLicense($car_license);
        $parks = Parks::getFromLicense($car_license);
        if(isset($parks)){
            // $parks->setEmp_id_chek_out($_SESSION['id']);
            // para probar con postman. borrar y habilitar la de arriba
            // $parks->setEmp_id_chek_out(30);
            $parks->setEmp_id_chek_out(Employee::getCurrentUserId());

            $outed = $parks->outCar();
            if($outed){
                $preJSON['outed'] = true;
                $preJSON['parks'] = Parks::getFromId($parks->getId());
            }
        }
        $json = json_encode($preJSON);
        return $json;
    }
    public function outCarFromParksId($request, $response, $args) {
        /*  preseteo la respuesta. */
        $preJSON = array(   'outed' => false,
                            'parks' => NULL );
          $parks_id = $request->getAttribute('parks_id');
        /*  traigo el objeto parks a modificar. si no existe devuelve null. */
        $parks = Parks::getFromId($parks_id);
        if(isset($parks)){
            // $parks->setEmp_id_chek_out($_SESSION['id']);
            // para probar con postman. borrar y habilitar la de arriba
            // $parks->setEmp_id_chek_out(30);
            $parks->setEmp_id_chek_out(Employee::getCurrentUserId());
            $outed = $parks->outCar();
            if($outed){
                $preJSON['outed'] = true;
                $preJSON['parks'] = Parks::getFromId($parks_id);
            }
        }
        $json = json_encode($preJSON);
        return $json;
    }
    //
    public function save($request, $response, $args) {
        $preJSON = array(   'saved'     => false,
                            'car'       => NULL,
                            'location'  => NULL,
                            'parks'     => NULL
                        );
        $params = $request->getParsedBody();
        $car = Car::getFromLicense($params['license']);
        $isIn = Parks::isStillInFromLicense($params['license']);
        if(!$isIn){
            if(empty($car)){
                $car = new Car();
                $car->setLicense($params['license']);
                $car->setBrand($params['brand']);
                $car->setColor($params['color']);
                $car->setDisabled( json_decode($params['disabled']) ? '1' : '0' );
                /*  esto no va.. el awner id no sirve mas. */
                // $car->setOwner_id($_SESSION['id']);
                // $car->setOwner_id(30);
                $car->save();
                $car = Car::getFromLicense($params['license']);
            }
            $location = $car->getDisabled() ? Location::getFreeReserved() : Location::getFreeUnreserved();
            if(!empty($car) && !empty($location)){
                $parks = new Parks();
                $parks->setCar_id($car->getId());
                $parks->setLocation_id($location->getId());
                // $parks->setEmp_id_chek_in($_SESSION['id']);

                // $parks->setEmp_id_chek_in('30');
                $parks->setEmp_id_chek_in(Employee::getCurrentUserId());
                $parks_insert_id = $parks->save();
                // si es cero no deberia traer nada...
                $parks = $parks->getFromId($parks_insert_id);
                // ...y no deberia entrar aca.
                if(!empty($parks)){
                    $preJSON['saved'] = true;
                    $preJSON['car'] = $car;
                    $preJSON['location'] = $location;
                    $preJSON['parks'] = $parks;
                }
            }
        }
        $json = json_encode($preJSON);
        return $json;
    }

    public function getFromId($request, $response, $args) {
        $parks_id = $request->getAttribute('parks_id');
        /*  la funcion devuelve un array o null */
        // vd($park_id);die;
        $parks = Parks::getFromId($parks_id);
        /*  devuelvo un json de un array con la clave 'parks' con un objeto
            auto o null. */

        $parks->emp_chek_in = Employee::getFromId( $parks->getEmp_id_chek_in() );
        $parks->car = Car::getFromId($parks->getCar_id());
        $parks->location = Location::getFromId($parks->getLocation_id());
        if(!empty($parks->getEmp_id_chek_out())){
            $parks-> setEmp_id_chek_out( Employee::getFromId( $parks->getEmp_id_chek_out() ));
        }

        $json = json_encode( array('parks' => $parks) );
        return $json;
    }

    public function getAll($request, $response, $args) {
        /*  preseteo el array de respuesta. */
        $responseArray = [];
        /*  getAll devuelve un array de parks o bien null. */
        $result = Parks::getAll();
        // vd($result);
        /*  si no esta vacio lo recorro. */
        if( !empty($result) ){
            /*  Recorre los parks y va creando cada objeto con los valores necesarios,
                reemplazando location id y car id por el objeto correspondiente. */
            foreach ($result as $item) {
                $item->emp_chek_in = Employee::getFromId( $item->getEmp_id_chek_in() );
                $item->car = Car::getFromId($item->getCar_id());
                $item->location = Location::getFromId($item->getLocation_id());
                if(!empty($item->getEmp_id_chek_out())){
                    $item-> setEmp_id_chek_out( Employee::getFromId( $item->getEmp_id_chek_out() ));
                }
                $responseArray[] = $item;
            }
        }
        $responseArray = empty($responseArray) ? null : $responseArray;
        /*  devuelvo un json de un array con la clave 'parks' con un array
            de parks o null. */
        $result = json_encode(array('parks' => $responseArray));
        return $result;
    }

    // TODO, borrar
    // esto esta aca solo para testear el calculateCost a travez de
    // get -> parks/test_price/:parks_id
    public function calculateCost($request, $response, $args) {
        $parks_id = $request->getAttribute('parks_id');
        $parks = Parks::getFromId($parks_id);
        $car = Car::getFromId($parks->getCar_id());
        $price = Price::getPriceFromDate($parks->getCheck_in());
        $cost = $car->getDisabled() ? 0 : $parks->calculateCost($price);

        return var_dump($cost);
    }
}
