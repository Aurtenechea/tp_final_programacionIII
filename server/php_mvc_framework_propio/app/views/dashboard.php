<html>
    <head>
        <title>Dashboard</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- bootstrap css-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- jquery -->
        <!-- CDN -->
        <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script> -->
        <!-- FILE -->
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/jquery-3.2.1.min.js"></script>

        <!--  jquery cookies plugin -->
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/jquery.cookie.js"></script>
        <!-- bootstrap js -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- styles -->
        <!-- <link rel="stylesheet" href="./style.css"> -->

        <!-- scripts -->
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/dashboard.js"></script>
        <!-- scripts typescript test -->
        <!-- <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/typescript/routes.js"></script>
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/typescript/ajax.js"></script>
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/typescript/lib.js"></script>
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/typescript/car.js"></script>
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/typescript/controladora.js"></script> -->
        <!-- <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/typescript/test.js"></script> -->

    </head>
    <body>
        <label for="license">License:</label><br>
        <input type="text" id="license" value="licenseA">
        <button id= "search_car">search</button>
        <br><br>
        <label for="brand">Brand:</label><br>
        <input type="text" id="brand" value="brandA">
        <br><br>
        <label for="color">Color:</label><br>
        <input type="text" id="color" value="colorA">
        <br><br>
        <input type="checkbox" id="disabled_parking_place" value="true">Disabled parking place<br>
        <br>
        <button id="park_button">park</button>
        <button id="logout_button">logout</button>
        <button id="fillCarsList_button">fcl</button>
        <br><br>
        <label for="license">Out car by License:</label><br>
        <input type="text" id="licence_outFromlicense" value="">
        <button id="outFromLicense">Out</button>

        <!-- <label id="result"></label><br> -->
        <h2>Still inside</h2>
        <table class="table table-striped" id="parked_cars_table">
          <thead >
            <tr id="parked_cars_tr_thead">
                <th>License</th>
                <th>Brand</th>
                <th>Color</th>
                <th>Disabled</th>
                <th>Location</th>
                <th>Check_in</th>
                <th>Options</th>
            </tr>
          </thead>
          <tbody id="parked_cars_tbody">

          </tbody>
        </table>

        <h2>Outed</h2>
        <table class="table table-striped" id="outed_cars_table">
          <thead >
            <tr id="outed_cars_tr_thead">
                <th>License</th>
                <th>Brand</th>
                <th>Color</th>
                <th>Disabled</th>
                <th>Location</th>
                <th>Check_in</th>
                <th>Check_out</th>
                <th>Cost</th>
            </tr>
          </thead>
          <tbody id="outed_cars_tbody">

          </tbody>
        </table>
    </body>
<footer>

<!-- <script>
console.log("loaded");
console.log(window.location);

function log(){
    // tomo las variables con jQuery..
    var user = $('#usuario').val();
    var pass = $('#pass').val();

    console.log(user + ": " + pass);

    //si se ejecuta el archivo index.html haciendo doble click desde el archivo
    //en el escritorio.
    //El json puede tener las propiedades con o sin comillas: {"usuario": user, "pass": pass}
    //una forma de usar el $.post
    var params = { usuario: user, pass: pass };
    var url = "/utn/laboratorio3/loginSystemPropio/php_mvc_framework_propio/public/home/verify";
    var callback = function( data ){
        console.log(Data);
        objData = JSON.parse(data);
        console.log(objData);
        if(objData.loged_in){
            window.location.replace("/utn/laboratorio3/loginSystemPropio/php_mvc_framework_propio/public/home/confidency/");
        }
    };
    $.post(url, params, callback);
}
</script> -->
</footer>

</html>
