<?php
/*  Hace que todas las rutas sean relativas a la raiz del sitio.
    dirname() devuelve la ruta del directorio sin el archivo en la ruta
    pasada como parametro. (puede ser absoluta o relativa).
    chdir() cambia el directorio en el que php dice estar parado.
    */
chdir( dirname( __DIR__ ) );

/*  define las carpetas como constantes. */
define("SYS_PATH", "lib/");
define("APP_PATH", "app/");

/*  antes de llamar a algun metodo se incluyen todos los archivos. */
require( SYS_PATH . "init.php");


/*  punto de partida de la aplicacion. */
$app = new App;
