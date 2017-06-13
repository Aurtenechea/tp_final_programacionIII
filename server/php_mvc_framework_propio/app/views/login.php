<html>
    <head>
        <title>Login</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- bootstrap css-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- jquery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <!-- FILE -->
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/jquery-3.2.1.min.js"></script>
        <!-- bootstrap js -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <!-- styles -->
        <!-- <link rel="stylesheet" href="./style.css"> -->

        <!-- scripts -->
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/login.js"></script>

    </head>
    <body>
        <label for="email">Email:</label><br>
        <input type="text" id="email" value="admin">
        <br><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" value="admin">
        <br><br>
        <button onclick="log()">Log</button>
        <label id="result"></label><br>

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
