console.log("dashboard.js loaded");
var root_url_api = "/utn/tp_final_programacionIII/src/public";
var root_url_server = "/utn/tp_final_programacionIII/server/php_mvc_framework_propio/public";

/*  Esta funcion hace una peticion a la api para que si NO esta
    logueado el usuario vaya directo al login. */
check_loged_in();

/*  */
$(document).ready(function() {
    $("#park_button").click(park);
    $("#logout_button").click(logout);
    $("#fillCarsList_button").click( function(){
                                            fillCarsList();
                                            fillOutedCarsList();
                                        });
    $("#search_car").click(search_car);
    $("#outFromLicense").click(outCarFromLicense);
});

/*  buscar el auto por licencia. si se cargo alguna vez, trae los demas datos. */
function search_car(){
    var license = $('#license').val();
    var params = { "license": license };
    $('#brand').val('');
    $('#color').val('');
    /*  seteo la url. */
    var url = root_url_api + "/car/" + license;
    /*  crea el objeto ajax */
    var parkCarAjax = $.ajax(
        {
            type: 'GET',
            url: url,
            data: params,
        }
    );
    var callbackSucces = function( data ){
        dataJson = JSON.parse(data);
        if(typeof(dataJson.car) != "undefined" && dataJson.car !== null){
            console.log(dataJson.car);
            $('#brand').val(dataJson.car.brand);
            $('#color').val(dataJson.car.color);
            // alert(dataJson.car.disabled);
            $('#disabled_parking_place').prop('checked',(dataJson.car.disabled == 1))
        }

    };
    /*  CALLBACK ERROR de crear un auto */
    function callbackError(error){
        console.log("search_car Error: " + error);
    }
    /*  ejecuta el ajax */
    parkCarAjax.then(callbackSucces, callbackError); // params: donecallbak, fail callback
}
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
    var disabled = $('#disabled_parking_place')[0].checked
    /*  preparo el json */
    var params = {  "license": license,
                    "brand": brand,
                    "color": color,
                    "owner_id": 0,
                    "comment": "undefined",
                    "disabled" : disabled
                };

    /*  seteo la url. */
    var url = root_url_api + "/parks";
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
        console.log(dataJson);
        if(dataJson.saved){
                console.log("se refrezca la lista.");
                fillCarsList();
            }
        else{
            alert("No se pudo guardar el auto.");
        }
    };
    /*  CALLBACK ERROR de crear un auto */
    function callbackError(error){
        console.log("parkCallbackError: " + error);
    }
    /*  ejecuta el ajax */
    parkCarAjax.then(callbackSucces, callbackError); // params: donecallbak, fail callback
}
function fillCarsList(){

    url = root_url_api + "/parks/still_in";
    var ajax = $.ajax(
        {
            type: 'GET',
            url: url,
        }
    );
    var ajaxSucces = function( data ){
        // console.log(data);
        dataJson = JSON.parse(data);

        console.log(dataJson);
        if(dataJson.parks != null){
            var i ;
            var str = '';
            var parks = dataJson.parks
            for(i=0; i < parks.length; i++){
                str += "<tr>";
                str +=  "<td>"+parks[i].car.license+"</td>";
                str +=  "<td>"+parks[i].car.brand+"</td>";
                str +=  "<td>"+parks[i].car.color+"</td>";
                str +=  "<td>"+parks[i].car.disabled+"</td>";
                str +=  "<td>"+parks[i].location.floor+"-"+parks[i].location.sector+"-"+parks[i].location.number+"</td>";
                str +=  "<td>"+parks[i].check_in+"</td>";
                str +=  "<td>"+
                            // "<button  \
                            //     class='deleteCar_button'  \
                            //     value='"+parks[i].id+"'  \
                            //     type='button'>Delete \
                            // </button>"+
                            "<button  \
                                class='outCar_button'  \
                                value='"+parks[i].id+"'  \
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
        else{
            $('#parked_cars_tbody').html("");
        }
    };
    function ajaxError(error){
        console.log("ajaxErrorFillList: " + error);
    }
    ajax.then(ajaxSucces, ajaxError);
}

function fillOutedCarsList(){
    url = root_url_api + "/parks/outed";
    var ajax = $.ajax(
        {
            type: 'GET',
            url: url,
        }
    );
    var ajaxSucces = function( data ){
        // console.log(data);
        dataJson = JSON.parse(data);

        console.log(dataJson);
        if(dataJson.parks != null){
            var i ;
            var str = '';
            var parks = dataJson.parks
            for(i=0; i < parks.length; i++){
                str += "<tr>";
                str +=  "<td>"+parks[i].car.license+"</td>";
                str +=  "<td>"+parks[i].car.brand+"</td>";
                str +=  "<td>"+parks[i].car.color+"</td>";
                str +=  "<td>"+parks[i].car.disabled+"</td>";
                str +=  "<td>"+parks[i].location.floor+"-"+parks[i].location.sector+"-"+parks[i].location.number+"</td>";
                str +=  "<td>"+parks[i].check_in+"</td>";
                str +=  "<td>"+parks[i].check_out+"</td>";
                str +=  "<td>"+parks[i].cost+"</td>";
                str += "</tr>";
            }
            $('#outed_cars_tbody').html(str);
        }
        else{
            $('#outed_cars_tbody').html("");
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
    url = root_url_api + "/parks/out_car/" + this.value;
        var ajax = $.ajax(
            {
                type: 'GET',
                url: url
            }
        );
        var ajaxSucces = function( data ){
            console.log(data);
            fillCarsList();
            fillOutedCarsList();
        };
        function ajaxError(error){
            console.log("ajax: " + error);
        }
        ajax.then(ajaxSucces, ajaxError);
}
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


/*  Esta funcion hace una peticion a la api para que si NO esta
    logueado el usuario vaya directo al login. */
function outCarFromLicense(){
    var license = $('#licence_outFromlicense').val();
    /*  prepara la url de la api */
    var url = root_url_api + "/parks/out_car/license/" + license;
    /*  creo el objeto ajax. */
    var parkCarAjax = $.ajax(
        {
            type: 'GET',
            url: url,
        }
    );
    /*  CALLBACK SUCCES. */
    var callbackSucces = function( data ){
        dataJson = JSON.parse(data);
        console.log(dataJson);
        if(dataJson.outed){
            // console.log("El auto sale del park.");
            fillCarsList();
            fillOutedCarsList();
            // alert("El costo es de: $ " + dataJson.parks.cost);
        }
    };
    /*  CALLBACK ERROR */
    function callbackError(error){
        console.log("CallbackError:" + error);
    }
    /*  ejecuto el ajax */
    parkCarAjax.then(callbackSucces, callbackError);
};
