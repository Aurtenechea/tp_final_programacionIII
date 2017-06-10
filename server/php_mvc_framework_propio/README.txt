desde index .php se incluye el archivo init.php

init.php hace un require de todos los archivos necesarios para arrancar el framework

luego se instancia un objeto app el cual incluye a los archivos de tipo
controller que se requieran para la peticion, y luego se crean instancias para
 llamar a sus metodos.
y dentro de los metodos del objeto va la logina necesaria, para finalmente
llamar al objeto Response y el metodo estatico render al cual se le pueden pasar
variables y el nombre de la view que se quiere mostrar.


=============================================================================

Circuito.
se ingresa una url separada por barras:
/home/index/2
por ejemplo
http://localhost/utn/laboratorio3/loginSystemPropio/php_mvc_framework_propio/public/user/login

se instancia el controlador HomeController y se llama a su metodo index.
Dentro del index se puede hacer uso de los modelos que pueden traer informacion
de la base de datos. Y tambien se llama luego al objeto Response que con su metodo
render crea variables para las vistas y incluye (es decis muestra) la vista.




Para hacer una pagina nueva se necesita:
- Crear el controlador. (o agregar un metodo a uno existente).
- Crear el metodo del controlador. Con las acciones a realizar y los parametros a recibir.
- Crear la vista, que es lo que se va a mostrar. Hacer uso de las variables que se le van a
    pasar al obj Response desde dentro del metodo del controlador.
- llamar al response desde metodo del controlador
