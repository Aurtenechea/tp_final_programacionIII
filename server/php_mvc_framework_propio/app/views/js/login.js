console.log("login.js loaded");

var root_url_server = "/utn/tp_final_programacionIII/server/php_mvc_framework_propio/public";
var root_url_api = "/utn/tp_final_programacionIII/src/public";
    /*  ANTES QUE NADA SE DEBERIA HACER UNA PETICION A LA API PARA QUE SI ESTA
        LOGUEADO EL USUARIO VAYA DIRECTO AL DASHBOARD SIN LOGEARSE. */
/*  Toma los valores del login, los envia via con ajax a la api.
    La api verifica si estan el usuario esta en la base de datos y retorna un json.
    Este contiene la info del usuario y si esta logueado o no.
    Si los datos son validos, esto inicia sesion en la api, con lo cual la api
    va a poder responder cualquier request. Y se redirecciona al dashboard.
    Sino no se inicia sesion en la api y esta no devolvera nada. Ademas de que no
    se redireccion al dashboard. Asi mismo si se puede acceder al dashboard si
    se conoce la url. Pero como los datos son pedidos a la api por ajax a travez
    de js, el dashboard no va a mostrar nada relevante. es decir es seguro. */
function log(){
    /*  toma los valores del formulario. */
    var email = $('#email').val();
    var password = $('#password').val();
    /*  arma el json. */
    var params = { "email": email, "password": password };

    /*  setea la url de la api.
        Ahi se corroborara si el usuario existe en la db. */
    var url = root_url_api + "/employee/verify";

    /*  callback del ajax de verificacion de usuario. */
    var callback = function( data ){
        var dataJson = JSON.parse(data);
        /*  si se pudieron corroborar los datos del usuario, se redirecciona
            al tablero de trabajo.
            SINO, SE DEBERIA MOSTRAR UN CARTEL DE ERROR */
        if( dataJson.loged_in ){
            window.location.replace(root_url_server + "/home/dashboard/");
        }
        else{
            alert("Los datos no son correctos. Vuelve a intentarlo.");
        }
    };
    /*  se ejecuta el ajax */
    $.post(url, params, callback);
}
