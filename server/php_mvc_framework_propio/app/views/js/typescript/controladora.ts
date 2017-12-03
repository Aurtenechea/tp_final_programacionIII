/// <reference path="routes.ts"/>
/// <reference path="ajax.ts"/>
/// <reference path="lib.ts"/>
/// <reference path="car.ts"/>

class Controladora{
    public static search_car(){
        let license :string = $('license').value;
        $('brand').value = '';
        $('color').value = '';

        Car.search_car(license, function(car :Car){
            $('brand').value = car.brand;
            $('color').value = car.color;
            $('disabled_parking_place').checked = car.disabled == 0 ? false : true;
        });
    }
}
