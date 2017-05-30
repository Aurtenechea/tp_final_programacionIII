<?php
    error_reporting (E_ALL);
    ini_set('display_errors', TRUE);
    ini_set('display_startup_errors', TRUE);

    const SLASH = "/";

    function vd($array){
        echo "<pre>";
        var_dump($array);
        echo "</pre>";
    }
    function pr($array){
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }
    //define("MAXSIZE", 100);  //otra forma;
    const BR = "<br />";
    const BR2 = "<br /><br />";
    const BR3 = "<br /><br /><br />";

    function br( $cant=1 ){
        for ($i=0; $i < $cant; $i++) {
            echo "<br />\n";
        }
    }


    /*  mueve el archivo subido de la carpeta temporal a una ubicacion permanente
        files array con los datos de la foto temporal.
        directorio donde se quiere guardar.
        newName es el nuvo nombre sin la extension.*/
    function moveTempToPerm($files, $permDir, $newName){
        //valido parametros
        if( empty($files) || empty($permDir) || empty($newName) || !isset($files['name']) || !isset($files['tmp_name']))
            return "";

        //creo carpeta
        if(!is_dir($permDir))
            mkdir($permDir);

        $arr = pathinfo($files['name']);
        $ext = $arr['extension'];
        //seteo el nombre permanente
        $nuevaRuta =  $permDir . SLASH . $newName .".".  $ext;

        //si existe lo mando a la carpeta backup
        if(file_exists($nuevaRuta)){
            if(!is_dir($permDir.SLASH."backup")){
                mkdir($permDir.SLASH."backup");
            }
            $nuevaRuta = $permDir . SLASH . "backup" . SLASH . $newName ."_". time() .".".  $ext;
        }

        echo "DEBUG:".BR;
        vd($files).BR;
        vd($permDir).BR;
        vd($newName).BR;
        echo "nueva ruta" . $nuevaRuta.BR;
        echo "la funcion devuelve: " . move_uploaded_file($files['tmp_name'], $nuevaRuta).BR;

        if( move_uploaded_file($files['tmp_name'], $nuevaRuta) )
            return $nuevaRuta;
        else
            throw new Exception("Error guardando el archivo");
        }



 ?>
