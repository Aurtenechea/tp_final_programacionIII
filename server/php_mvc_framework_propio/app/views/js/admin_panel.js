console.log("admin_panel.js loaded");
var root_url_api = "/utn/tp_final_programacionIII/src/public";
var root_url_server = "/utn/tp_final_programacionIII/server/php_mvc_framework_propio/public";

/*  Esta funcion hace una peticion a la api para que si NO esta
    logueado el usuario vaya directo al login. */
check_loged_in();

/*  */
$(document).ready(function() {
    $("#save_emp").click(save_emp);
});


function save_emp(){
    /* GUARDO EL AUTO */
    /*  tomo los valores ingresados. */
    var rol = $('#rol').val();
    var first_name = $('#first_name').val();
    var last_name = $('#last_name').val();
    var shift = $('#shift').val();
    var state = $('#state').val();
    var color = $('#color').val();
    var email = $('#email').val();
    var password = $('#password').val();
    /*  preparo el json */
    var params = {
                     'rol' :  rol,
                     'first_name' :  first_name,
                     'last_name' :  last_name,
                     'shift' :  shift,
                     'state' :  state,
                     'color' :  color,
                     'email' :  email,
                     'password' :  password
        };

    /*  seteo la url. */
    var url = root_url_api + "/employee";
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
        dataJson = JSON.parse(data);
        console.log(dataJson);
        if(dataJson.saved){
                console.log("El empleado a sido creado.");
            }
        else{
            alert("No se pudo crear el empleado.");
        }
    };
    /*  CALLBACK ERROR de crear un auto */
    function callbackError(error){
        console.log("parkCallbackError: " + error);
    }
    /*  ejecuta el ajax */
    parkCarAjax.then(callbackSucces, callbackError); // params: donecallbak, fail callback
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
