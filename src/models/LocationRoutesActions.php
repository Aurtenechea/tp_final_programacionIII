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
    $preJSON = array( 'Parks' => NULL );
    $parks = Location::getMostUsed();
    $parks->setEmp_id_chek_in( Employee::getFromId( $parks->getEmp_id_chek_in() ));
    $parks->setEmp_id_chek_out( Employee::getFromId( $parks->getEmp_id_chek_out() ));
    $parks->car = Car::getFromId($parks->getCar_id());
    $parks->location = Location::getFromId($parks->getLocation_id());
    if(isset($parks)){
      $preJSON['Parks'] = $parks;
    }
    $json = json_encode($preJSON);
    return $json;
  }

  public function getMostUsedFromDate($request, $response, $args) {
    $date = $request->getAttribute('date');
    $preJSON = array( 'Parks' => NULL );
    $parks = Location::getMostUsedFromDate($date);
    $parks->setEmp_id_chek_in( Employee::getFromId( $parks->getEmp_id_chek_in() ));
    $parks->setEmp_id_chek_out( Employee::getFromId( $parks->getEmp_id_chek_out() ));
    $parks->car = Car::getFromId($parks->getCar_id());
    $parks->location = Location::getFromId($parks->getLocation_id());
    if(isset($parks)){
      $preJSON['Parks'] = $parks;
    }
    $json = json_encode($preJSON);
    return $json;
  }

  public function getMostUsedFromRange($request, $response, $args) {
    $date_from = $request->getAttribute('date_from');
    $date_to = $request->getAttribute('date_to');
    $preJSON = array( 'Parks' => NULL );
    $parks = Location::getMostUsedFromRange($date_from, $date_to);
    $parks->setEmp_id_chek_in( Employee::getFromId( $parks->getEmp_id_chek_in() ));
    $parks->setEmp_id_chek_out( Employee::getFromId( $parks->getEmp_id_chek_out() ));
    $parks->car = Car::getFromId($parks->getCar_id());
    $parks->location = Location::getFromId($parks->getLocation_id());
    if(isset($parks)){
      $preJSON['Parks'] = $parks;
    }
    $json = json_encode($preJSON);
    return $json;
  }

  public function getLeastUsed($request, $response, $args) {
    $preJSON = array( 'Parks' => NULL );
    $parks = Location::getLeastUsed();
    // vd($parks);die;
    $parks->setEmp_id_chek_in( Employee::getFromId( $parks->getEmp_id_chek_in() ));
    $parks->setEmp_id_chek_out( Employee::getFromId( $parks->getEmp_id_chek_out() ));
    $parks->car = Car::getFromId($parks->getCar_id());
    $parks->location = Location::getFromId($parks->getLocation_id());
    if(isset($parks)){
      $preJSON['Parks'] = $parks;
    }
    $json = json_encode($preJSON);
    return $json;
  }

  public function getLeastUsedFromDate($request, $response, $args) {
    $date = $request->getAttribute('date');
    $preJSON = array( 'Parks' => NULL );
    $parks = Location::getLeastUsedFromDate($date);
    $parks->setEmp_id_chek_in( Employee::getFromId( $parks->getEmp_id_chek_in() ));
    $parks->setEmp_id_chek_out( Employee::getFromId( $parks->getEmp_id_chek_out() ));
    $parks->car = Car::getFromId($parks->getCar_id());
    $parks->location = Location::getFromId($parks->getLocation_id());
    if(isset($parks)){
      $preJSON['Parks'] = $parks;
    }
    $json = json_encode($preJSON);
    return $json;
  }

  public function getUnusedFromDate($request, $response, $args) {
    $date = $request->getAttribute('date');

    $preJSON = array( 'location' => NULL );
    $location = Location::getUnusedFromDate($date);
    if(isset($location)){
      $preJSON['location'] = $location;
    }
    $json = json_encode($preJSON);
    return $json;
  }

  public function getUnusedFromRange($request, $response, $args) {
    $date_from = $request->getAttribute('date_from');
    $date_to = $request->getAttribute('date_to');

    $preJSON = array( 'location' => NULL );
    $location = Location::getUnusedFromRange($date_from, $date_to);
    if(isset($location)){
      $preJSON['location'] = $location;
    }
    $json = json_encode($preJSON);
    return $json;
  }

  public function getLeastUsedFromRange($request, $response, $args) {
    $date_from = $request->getAttribute('date_from');
    $date_to = $request->getAttribute('date_to');
    $preJSON = array( 'Parks' => NULL );
    $parks = Location::getLeastUsedFromRange($date_from, $date_to);
    $parks->setEmp_id_chek_in( Employee::getFromId( $parks->getEmp_id_chek_in() ));
    $parks->setEmp_id_chek_out( Employee::getFromId( $parks->getEmp_id_chek_out() ));
    $parks->car = Car::getFromId($parks->getCar_id());
    $parks->location = Location::getFromId($parks->getLocation_id());
    if(isset($parks)){
      $preJSON['Parks'] = $parks;
    }
    $json = json_encode($preJSON);
    return $json;
  }

  public function getUnused($request, $response, $args) {
    $preJSON = array( 'location' => NULL );
    $location = Location::getUnused();
    if(isset($location)){
      $preJSON['location'] = $location;
    }
    $json = json_encode($preJSON);
    return $json;
  }

}
