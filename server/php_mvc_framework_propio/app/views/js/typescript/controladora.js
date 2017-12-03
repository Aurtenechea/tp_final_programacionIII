/// <reference path="routes.ts"/>
/// <reference path="ajax.ts"/>
/// <reference path="lib.ts"/>
/// <reference path="car.ts"/>
var Controladora = (function () {
    function Controladora() {
    }
    Controladora.search_car = function () {
        var license = $('license').value;
        $('brand').value = '';
        $('color').value = '';
        Car.search_car(license, function (car) {
            $('brand').value = car.brand;
            $('color').value = car.color;
            $('disabled_parking_place').checked = car.disabled == 0 ? false : true;
        });
    };
    return Controladora;
}());
