<html>
    <head>
        <title>Admin_panel</title>
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
        <script src="http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/app/views/js/admin_panel.js"></script>

    </head>
    <body>
        <label for="rol">Rol:</label><br>
        <input type="text" id="rol" value="employee">
        <br><br>
        <label for="first_name">first_name:</label><br>
        <input type="text" id="first_name" value="jose">
        <br><br>
        <label for="last_name">last_name:</label><br>
        <input type="text" id="last_name" value="garcia">
        <br><br>
        <label for="email">email:</label><br>
        <input type="text" id="email" value="jose">
        <br><br>
        <label for="shift">shift:</label><br>
        <input type="text" id="shift" value="tarde">
        <br><br>
        <label for="password">password:</label><br>
        <input type="text" id="password" value="jose">
        <br><br>
        <label for="state">state:</label><br>
        <input type="text" id="state" value="active">
        <br><br>
        <button id="save_emp">Create employee</button>
        <br><br>

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
<!--
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
<footer> -->

</footer>

</html>
