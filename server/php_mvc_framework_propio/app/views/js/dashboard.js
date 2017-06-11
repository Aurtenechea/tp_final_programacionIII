console.log("dashboard.js loaded");
var root_url_api = "/utn/tp_final_programacionIII/src/public";
var root_url_server = "/utn/tp_final_programacionIII/server/php_mvc_framework_propio/public";

check_loged_in();

$(document).ready(function() {
    $("#park_button").click(park);
    $("#logout_button").click(logout);
    $("#fillCarsList_button").click(fillCarsList);

    /*  Aca deberia haber una peticion ajax a la api para veificar si esta
        maquina se logueo, sino se deberia redireccionar al login. */

});

/*  Se dispara cuando se quiere guardar un auto.
    - se envia un ajax para guardar el auto.
    - si este se guado se envia un ajax para guardar un parks.
    - se refrezca la lista de autos. */
function park(){
    /* GUARDO EL AUTO */

    /*  tomo los valores ingresados. */
    var license = $('#license').val();
    var brand = $('#brand').val();
    var color = $('#color').val();
    /*  preparo el json */
    var params = {  "license": license,
                    "brand": brand,
                    "color": color,
                    "owner_id": 0,
                    "comment": "undefined"
                };

    /*  seteo la url. */
    var url = root_url_api + "/car";

    /*  crea el objeto ajax */
    var parkCarAjax = $.ajax(
        {
            type: 'POST',
            url: url,
            data: params,
        }
    );

    /*  CALLBACK SUCCES de crear un auto. Si se guardo hace un ajax para crear
        el parks. */
    var callbackSucces = function( data ){
        console.log(data);
        dataJson = JSON.parse(data);
        if(dataJson.saved){
            if(create_park(dataJson.car)){
                /*  refrescar la lista. */
                fillCarsList();
            }
        }
        else{
            alert("No se pudo guardar el auto1.");
        }

    };
    /*  CALLBACK ERROR de crear un auto */
    function callbackError(error){
        console.log("parkCallbackError: " + error);
    }
    /*  ejecuta el ajax */
    parkCarAjax.then(callbackSucces, callbackError); // params: donecallbak, fail callback

    /*  GUARDO EL PARK */

}

/*  Esta funcion intenta hacer un ajax para crear el park a partir de un un auto
    pasado como parametro. retorna true o false segun si logra hacerlo. */
function create_park(param_car){
    /*  setea el valor de retorno. Este se puede modificar en el callback succes */
    var result = false;
    /*  prepara los parametros del auto. En realida solo necesita el id. */
    var params = param_car;

    /*  prepara la url de la api */
    var url = root_url_api + "/parks";

    /*  creo el objeto ajax. */
    var parkCarAjax = $.ajax(
        {
            type: 'POST',
            url: url,
            data: params,
        }
    );

    /*  CALLBACK SUCCES. Si se dispara y se creo el parks, setea el result a true. */
    var callbackSucces = function( data ){
        console.log(data);
        dataJson = JSON.parse(data);
        if(dataJson.saved){
            result = true;
        }
        else{
            alert("No se pudo guardar el auto2.");
        }
    };
    /*  CALLBACK ERROR */
    function callbackError(error){
        console.log("parkCallbackError: desde el create_park" + error);
    }
    /*  ejecuto el ajax */
    parkCarAjax.then(callbackSucces, callbackError);
    return result;
}
// deleteCar_button (esto estaba en cualquier lado y daba error).
function fillCarsList(){
    url = root_url_api + "/car";
    var ajax = $.ajax(
        {
            type: 'GET',
            url: url,
        }
    );
    var ajaxSucces = function( data ){
        dataJson = JSON.parse(data)
        console.log(dataJson);
        if(dataJson.cars != null){
            var i ;
            var str = '';
            var cars = dataJson.cars
            for(i=0; i < cars.length; i++){
                str += "<tr>";
                str +=  "<td>"+cars[i].license+"</td>";
                str +=  "<td>"+cars[i].brand+"</td>";
                str +=  "<td>"+cars[i].color+"</td>";
                str +=  "<td>"+
                            // "<button  \
                            //     class='deleteCar_button'  \
                            //     value='"+cars[i].id+"'  \
                            //     type='button'>Delete \
                            // </button>"+
                            "<button  \
                                class='outCar_button'  \
                                value='"+cars[i].id+"'  \
                                type='button'>Out \
                            </button>"+
                        "</td>";
                str += "</tr>";
            }
            // console.log(str);
            $('#parked_cars_tbody').html(str);
            // $(".deleteCar_button").click(deleteCar);
            $(".outCar_button").click(outCar);
        }
    };
    function ajaxError(error){
        console.log("ajaxErrorFillList: " + error);
    }
    ajax.then(ajaxSucces, ajaxError);
}

/*  Se destruye la sesion en la api. Esta no contestara mas las paticiones.
    Tambien se redirecciona al login. */
function logout(){
    $.removeCookie("PHPSESSID", {path: '/'});
    url = root_url_api + "/employee/logout";
    var ajax = $.ajax(
        {
            type: 'GET',
            url: url
        }
    );

    /*  CALLBACK SUCCES redirecciona al login. */
    var callbackSucces = function(){
        window.location.replace("http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/public/user/login/");
    };
    /*  CALLBACK ERROR. */
    function callbackError(error){
        console.log("ajax: " + error);
    }
    /*  Ejecuto el ajax. */
    ajax.then(callbackSucces, callbackError);
}

/*  Esta funcion deberia updatear el parks y setearle la hora usuario de salida
    y el costo. */
function outCar(){
    console.log("outCar");
    console.log(this);
}


/* Esta funcion no se si tiene sentido. Eliminar un auto...*/
// function deleteCar(){
//     console.log(this);
//     url = root_url_api + "/car/" + this.value;
//     var ajax = $.ajax(
//         {
//             type: 'DELETE',
//             url: url
//         }
//     );
//     var ajaxSucces = function( data ){
//         fillCarsList();
//     };
//     function ajaxError(error){
//         console.log("ajax: " + error);
//     }
//     ajax.then(ajaxSucces, ajaxError);
// }

/*  Esta funcion hace una peticion a la api para que si NO esta
    logueado el usuario vaya directo al login. */
function check_loged_in(){
    /*  prepara la url de la api */
    var url = root_url_api + "/employee/check";
    /*  creo el objeto ajax. */
    var parkCarAjax = $.ajax(
        {
            type: 'GET',
            url: url,
        }
    );
    /*  CALLBACK SUCCES. Si se dispara y se creo el parks, setea el result a true. */
    var callbackSucces = function( data ){
        console.log(data);
        dataJson = JSON.parse(data);
        if(!dataJson.loged_in){
            window.location.replace(root_url_server + "/user/login/");
        }
    };
    /*  CALLBACK ERROR */
    function callbackError(error){
        console.log("CallbackError:" + error);
    }
    /*  ejecuto el ajax */
    parkCarAjax.then(callbackSucces, callbackError);
};
