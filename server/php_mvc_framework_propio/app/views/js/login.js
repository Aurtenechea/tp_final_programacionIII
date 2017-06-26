console.log("login.js loaded");

var root_url_server = "/utn/tp_final_programacionIII/server/php_mvc_framework_propio/public";
var root_url_api = "/utn/tp_final_programacionIII/src/public";

/*  Esta funcion hace una peticion a la api para que si esta
    logueado el usuario vaya directo al dashboard sin logearse. */
check_loged_in();


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
            console.log(dataJson);
            if(dataJson.employee.rol == 'admin'){
                window.location.replace(root_url_server + "/home/admin_panel/");
            }
            else{
                window.location.replace(root_url_server + "/home/dashboard/");
            }
        }
        else{
            alert("Los datos no son correctos. Vuelve a intentarlo.");
        }
    };
    /*  se ejecuta el ajax */
    $.post(url, params, callback);
}

/*  Esta funcion hace una peticion a la api para que si esta
    logueado el usuario vaya directo al dashboard sin logearse. */
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
        if(dataJson.loged_in){
            window.location.replace(root_url_server + "/home/dashboard/");
        }
    };
    /*  CALLBACK ERROR */
    function callbackError(error){
        console.log("CallbackError:" + error);
    }
    /*  ejecuto el ajax */
    parkCarAjax.then(callbackSucces, callbackError);
};
