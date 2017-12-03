<?php
class LocationRoutesActions
{
 	public function getAll($request, $response, $args){
        $locations = null;
        $locations = Location::getAll();
        $json = json_encode( array(  'locations' => $locations) );
        return $json;
    }

    public function getFromId($request, $response, $args) {
        $location_id = $request->getAttribute('location_id');
        $location = Location::getFromId($location_id);
        $json = json_encode( array(  'location' => $location));
        return $json;
    }

    public function updateFromId($request, $response, $args) {
        $preJSON = array(   'updated' => false,
                            'location' => NULL );
        $params = $request->getParsedBody();
        $location = Location::getFromId($params['id']);
        $location->setFloor($params['floor']);
        $location->setSector($params['sector']);
        $location->setNumber($params['number']);
        $location->setReserved($params['reserved']);
        $updated_id=Location::updateFromId($location);
        if($updated_id){
            $preJSON['updated'] = true;
            $preJSON['location'] = $location;
        }
        $json = json_encode($preJSON);
        return $json;
    }
    public function save($request, $response, $args) {
        $preJSON = array(   'saved' => false,
                            'location' => NULL );
        $params = $request->getParsedBody();
        $location = new Location();
        $location->setFloor($params['floor']);
        $location->setSector($params['sector']);
        $location->setNumber($params['number']);
        $location->setReserved($params['reserved']);
        $saved_id = $location->save();
        if($saved_id){
            $location->setId($saved_id);
            $preJSON['saved'] = true;
            $preJSON['location'] = $location;
        }
        $json = json_encode($preJSON);
        return $json;
    }
    public function delete($request, $response, $args) {
        $preJSON = array(   'deleted' => false,
                            'location' => NULL );
        $location_id = $request->getAttribute('location_id');
        $location = Location::getFromId($location_id);
        $deleted = 0;
        if(isset($location)){
            $deleted = Location::deleteFromId($location->getId());
        }
        if($deleted){
            $preJSON['deleted'] = true;
            $preJSON['location'] = $location;
        }
        $json = json_encode($preJSON);
        return $json;
    }



    public function getMostUsed($request, $response, $args) {
        $preJSON = array( 'location' => NULL );
        $location = Location::getMostUsed();
        if(isset($location)){
            $deleted = Location::deleteFromId($location->getId());
        }
        if($deleted){
            $preJSON['deleted'] = true;
            $preJSON['location'] = $location;
        }
        $json = json_encode($preJSON);
        return $json;
    }

    // public static function getFromId($employee_id){
    //   $dba = DBAccess::getDBAccessObj();
    //   $query = $dba->getQueryObj("SELECT * FROM EMPLOYEE WHERE id = :id");
    //   $query->bindValue(':id',$employee_id, PDO::PARAM_INT);
    //   $query->execute();
    //   $result = $query->fetchAll(PDO::FETCH_CLASS, "Employee");
    //   /*  si es un array vacio asiganarle null sino dejar el array */
    //   $result = empty($result) ? null : $result[0];
    //   return $result;
    // }

}
