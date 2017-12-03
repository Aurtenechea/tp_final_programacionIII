/// <reference path="ajax.ts"/>
console.log("login.js loaded");

var root_url_server = "/utn/tp_final_programacionIII/server/php_mvc_framework_propio/public";
var root_url_api = "/utn/tp_final_programacionIII/src/public";

/*  hace que si esta logueado el usuario vaya directo al sitio correspondiente
    sin logearse. */
redirect_if_loged_in();

function log(){
    debugger;
    /*  toma los valores del formulario. */
    let email = $('email').value;
    let password = $('password').value;
    /*  arma el json. */
    let params = { email: email, password: password };
    // console.log(params);

    /*  OBTENER Y GUARDAR TOKEN EN LOCAL. */
    let url = root_url_api + '/employee/verify'
    let ajax = new Ajax();
    var sCallback = function (data) {
      debugger;
        let dataJson = JSON.parse(data);
        // console.log(dataJson);
        if( dataJson.loged_in ){
            let token = dataJson.jwt;
            // token = token.replace(/"/gi, '');
            console.log('guardando token en localStorage...');
            localStorage.setItem('jwt', token);
            // console.log(parseJwt(token));
            redirect_if_loged_in();
        }
        else{
            alert("Los datos no son correctos. Vuelve a intentarlo.");
        }
    };
    ajax.post(url, sCallback);
    ajax.xhr.setRequestHeader("Content-Type", "application/json");
    ajax.send(JSON.stringify(params));
}

/*  Esta funcion devuelve true o false segun si existe el token el localStorage. */
function loged_in(){
  debugger;
    return !(localStorage.getItem('jwt') == null);
};

function redirect_if_loged_in(){
    debugger;
    if(loged_in()){
        let token = localStorage.getItem('jwt');
        let payload = parseJwt(token);
        // console.log(payload.employee.rol);
        if(payload.employee.rol == 'admin'){
            window.location.replace(root_url_server + "/home/admin_panel/");
        }
        else{
            window.location.replace(root_url_server + "/home/dashboard/");
        }
    }
}
