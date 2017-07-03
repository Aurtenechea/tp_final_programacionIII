/// <reference path="routes.ts"/>
/// <reference path="ajax.ts"/>
/// <reference path="lib.ts"/>

// declare var $ :any;

class Car{
    public id;
    public license;
    public color;
    public brand;
    public owner_id;
    public comment;
    public disabled;

    constructor(id :string, license :string, color :string,
                brand :string, owner_id :string, comment :string,
                disabled :string){
        this.id = id;
        this.license = license;
        this.color = color;
        this.brand = brand;
        this.owner_id = owner_id;
        this.comment = comment;
        this.disabled = disabled;
    }

    /*  consulta la api y devuelve el auto con esa licencia si existe. */
    public static search_car(license :string){
        var result :any;
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
        let ax :Ajax = new Ajax();
        let url = root_url_api + "/car/" + license;
        let exito = function( data ){
            let id, license, brand, color, owner_id, comment, disabled;
            let dataJson = JSON.parse(data);
            if(typeof(dataJson.car) != "undefined" && dataJson.car !== null){
                // console.log(dataJson.car);
                id = dataJson.car.id;
                license = dataJson.car.license;
                brand = dataJson.car.brand;
                color = dataJson.car.color;
                owner_id = dataJson.car.owner_id;
                comment = dataJson.car.comment;
                disabled = dataJson.car.disabled;
            }
            let car :Car = new Car(id, license, brand, color, owner_id, comment ,disabled);

            // console.log("ENTRO a EXITO" + car)
            result = car;
        };
        ax.get(url ,exito);
        // // FIN CON Ajax.ts
        return result;
    }



    public toJson(){
        return JSON.stringify(this);
    }


    // public park(){
    //     let result :any;
    //     // let id, license, brand, color, owner_id, comment, disabled;
    //     /* GUARDO EL AUTO */
    //     /*  tomo los valores ingresados. */
    //     /*  preparo el json */
    //     // var params = {  "license": this.license,
    //     //                 "brand": this.brand,
    //     //                 "color": this.color,
    //     //                 "owner_id": this.owner_id,
    //     //                 "comment": "undefined",
    //     //                 "disabled" : this.disabled
    //     //             };
    //     /*  seteo la url. */
    //     var url = root_url_api + "/parks";
    //     /*  crea el objeto ajax */
    //     var parkCarAjax = $.ajax(
    //         {
    //             type: 'POST',
    //             url: url,
    //             data: this
    //         }
    //     );
    //     /*  CALLBACK SUCCES de crear un auto. Si se guardo hace un ajax para crear
    //         el parks. */
    //     var callbackSucces = function( data ){
    //         // console.log(data);
    //         result = data;
    //         // dataJson = JSON.parse(data);
    //         // console.log(dataJson);
    //         // if(dataJson.saved){
    //         //         console.log("se refrezca la lista.");
    //         //         fillCarsList();
    //         //     }
    //         // else{
    //         //     alert("No se pudo guardar el auto.");
    //         // }
    //     };
    //     /*  CALLBACK ERROR de crear un auto */
    //     function callbackError(error){
    //         console.log("parkCallbackError: " + error);
    //         result = null;
    //     }
    //     /*  ejecuta el ajax */
    //     parkCarAjax.then(callbackSucces, callbackError); // params: donecallbak, fail callback
    //     return result;
    // }

}
