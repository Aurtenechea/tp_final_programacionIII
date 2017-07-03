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
    Car.search_car = function (license) {
        var result;
        //
        // // CON JQUERY
        //
        // var params = { "license": license };
        // /*  seteo la url. */
        // var url = root_url_api + "/car/" + license;
        // /*  crea el objeto ajax */
        // var parkCarAjax = $.ajax(
        //     {
        //         type: 'GET',
        //         url: url,
        //         data: params,
        //     }
        // );
        // var callbackSucces = function( data ){
        //     let id, license, brand, color, owner_id, comment, disabled;
        //
        //     let dataJson = JSON.parse(data);
        //
        //     if(typeof(dataJson.car) != "undefined" && dataJson.car !== null){
        //         console.log(dataJson.car);
        //         id = dataJson.car.id;
        //         license = dataJson.car.license;
        //         brand = dataJson.car.brand;
        //         color = dataJson.car.color;
        //         owner_id = dataJson.car.owner_id;
        //         comment = dataJson.car.comment;
        //         disabled = dataJson.car.disabled;
        //     }
        //     let car :Car = new Car(id, license, brand, color, owner_id, comment ,disabled);
        //     result = car;
        // };
        // /*  CALLBACK ERROR de crear un auto */
        // function callbackError(error){
        //     console.log("search_car Error: " + error);
        //     result = null;
        // }
        // /*  ejecuta el ajax */
        // parkCarAjax.then(callbackSucces, callbackError); // params: donecallbak, fail callback
        // //FIN CON JQUERY
        // // CON Ajax.ts
        var ax = new Ajax();
        var url = root_url_api + "/car/" + license;
        var exito = function (data) {
            var id, license, brand, color, owner_id, comment, disabled;
            var dataJson = JSON.parse(data);
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
            var car = new Car(id, license, brand, color, owner_id, comment, disabled);
            // console.log("ENTRO a EXITO" + car)
            result = car;
        };
        ax.get(url, exito);
        // // FIN CON Ajax.ts
        return result;
    };
    Car.prototype.toJson = function () {
        return JSON.stringify(this);
    };
    return Car;
}());
