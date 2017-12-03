<?php
class CarRoutesActions
{
 	public function getAll($request, $response, $args) {
        /*  preseteo el valor de retorno a null */
        $result = null;
        /*  getAll devuelve un array de autos o bien null. */
        $cars = Car::getAll();
        /*  lo convierto a json siendo $cars un array o null. */
        $result = json_encode( array('cars' => $cars) );
        /*  devuelvo un json de un array con la clave 'cars' con un array
            de autos o null. */
        return $result;
    }
    public function getFromLicense($request, $response, $args) {
        $car_license = $request->getAttribute('car_license');
        /*  la funcion devuelve un array o null */
        // vd($car_license);die;
        $car = Car::getFromLicense($car_license);
        /*  devuelvo un json de un array con la clave 'cars' con un objeto
            auto o null. */
        $json = json_encode( array('car' => $car) );
        return $json;
    }

    public function updateFromId($request, $response, $args) {
        $preJSON = array(   'updated' => false,
                            'car' => NULL );
        $params = $request->getParsedBody();
        $car = Car::getFromId($params['id']);
        $car->setLicense($params['license']);
        $car->setColor($params['color']);
        $car->setBrand($params['brand']);
        // $car->setOwner_id($params['owner_id']);
        $car->setComment($params['comment']);
        $car->setDisabled($params['disabled']);
        $updated_id = Car::updateFromId($car);
        if($updated_id){
            $preJSON['updated'] = true;
            $preJSON['car'] = $car;
        }
        $json = json_encode($preJSON);
        return $json;
    }
    /*  NO FUNCIONA. */
    public function save($request, $response, $args) {
        $preJSON = array(   'saved' => false,
                            'car' => NULL );
        $params = $request->getParsedBody();
        $car = new Car();
        $car->setColor($params['color']);
        $car->setBrand($params['brand']);
        $car->setLicense($params['license']);
        $car->setComment($params['comment']);
        $car->setDisabled($params['disabled']);
        // $car->setOwner_id($_SESSION['id']); // el id del empleado.

        // $employee = $request->getAttribute('employee');
        // $car->setOwner_id($employee['id']); // el id del empleado.

        $saved_id = $car->save();
        if($saved_id){
            $car->setId($saved_id);
            $car = Car::getFromId($saved_id);
            $preJSON['saved'] = true;
            $preJSON['car'] = $car;
        }
        $json = json_encode($preJSON);
        return $json;
    }

    public function deleteFromId($request, $response, $args) {
        $preJSON = array(   'deleted' => false,
                            'car' => NULL );
        $car_id = $request->getAttribute('car_id');
        $car = Car::getFromId($car_id);
        $deleted = 0;
        if(isset($car)){
            $deleted = Car::deleteFromId($car->getId());
        }
        if($deleted){
            $preJSON['deleted'] = true;
            $preJSON['car'] = $car;
        }
        $json = json_encode($preJSON);
        return $json;
    }
}
