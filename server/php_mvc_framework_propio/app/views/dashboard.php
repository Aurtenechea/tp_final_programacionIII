<html>
    <head>
        <title>Dashboard</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- bootstrap css-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <!-- bootstrap js -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- styles -->
        <!-- <link rel="stylesheet" href="./style.css"> -->

        <!-- scripts -->
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/dashboard.js"></script>

    </head>
    <body>
        <label for="license">License:</label><br>
        <input type="text" id="license" value="licenseA">
        <br><br>
        <label for="brand">Brand:</label><br>
        <input type="text" id="brand" value="brandA">
        <br><br>
        <label for="color">Color:</label><br>
        <input type="text" id="color" value="colorA">
        <br><br>
        <button id="park_button">park</button>
        <button id="logout_button">logout</button>
        <button id="fillCarsList_button">fcl</button>

        <!-- <label id="result"></label><br> -->

        <table class="table table-striped" id="parked_cars_table">
          <thead >
            <tr id="parked_cars_tr_thead">
                <th>License</th>
                <th>Brand</th>
                <th>Color</th>
                <th>Options</th>
            </tr>
          </thead>
          <tbody id="parked_cars_tbody">

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
