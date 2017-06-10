<?php
class Response
{
    private function __construct(){}

    /*  hace un require de un archivo de vista */
    public static function render( $view, $vars = [] ){
        /*  por cada parametro dentro de vars crea una variable con el
            mismo nombre de la clave. */
        foreach ($vars as $key => $value) {
            /* $$key produce que se generen variables con el nombre de lo que
                contiene la variable $key. */
            $$key = $value;
        }

        /*  llama a la vista que puede hacer uso de los params que se le pasaron
            a este Response. Aca hacer un require de un archivo html equivale a
            imprimir su contenido. Esto hace que se vea la vista en pantalla.*/
        require APP_PATH . "views/" . $view . ".php";
    }
}
