<?php
class PriceRoutesActions
{
 	public function save($request, $response, $args) {
        $preJSON = array(   'saved' => false,
                            'price' => NULL );
        $params = $request->getParsedBody();
        $price = new Price();
        $price->setHour($params['hour']);
        $price->setHalf_day($params['half_day']);
        $price->setDay($params['day']);
        $saved_id = $price->save();
        if($saved_id){
            $price->setId($saved_id);
            $preJSON['saved'] = true;
            $preJSON['price'] = $price;
        }
        $json = json_encode($preJSON);
        return $json;
    }
}
