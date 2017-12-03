/// <reference path="routes.ts"/>
/// <reference path="ajax.ts"/>
/// <reference path="lib.ts"/>
// declare var $ :any;
var Car = (function () {
    function Car(id, license, color, brand, owner_id, comment, disabled) {
        this.id = id;
        this.license = license;
        this.color = color;
        this.brand = brand;
        this.owner_id = owner_id;
        this.comment = comment;
        this.disabled = disabled;
    }
    /*  consulta la api y devuelve el auto con esa licencia si existe. */
    Car.search_car = function (license, callback) {
        var ax = new Ajax();
        var url = root_url_api + "/car/" + license;
        var exito = function (data) {
            console.log(data);
            var id, license, brand, color, owner_id, comment, disabled;
            var dataJson = JSON.parse(data);
            var car;
            if (typeof (dataJson.car) != "undefined" && dataJson.car !== null) {
                // console.log(dataJson.car);
                id = dataJson.car.id;
                license = dataJson.car.license;
                brand = dataJson.car.brand;
                color = dataJson.car.color;
                owner_id = dataJson.car.owner_id;
                comment = dataJson.car.comment;
                disabled = dataJson.car.disabled;
            }
            car = new Car(id, license, brand, color, owner_id, comment, disabled);
            callback(car);
        };
        ax.get(url, exito);
        ax.xhr.setRequestHeader("Authorization", "Bearer " + localStorage.getItem('jwt'));
        ax.send();
    };
    Car.prototype.toJson = function () {
        return JSON.stringify(this);
    };
    return Car;
}());
