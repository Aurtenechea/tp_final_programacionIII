<?php
class DB
{
    public function __construct(){

    }

    public static function query($sql, $params = []){
        /*  es como self::connection... */
        $statement = static::connection()->prepare($sql);
        $statement->execute($params);
        $result = $statement->fetch();

        return $result;
    }

    private static function connection(){
        return new PDO("mysql:host=localhost;dbname=test_php_mvc_framework", "root", "qweasdzxc");
    }
}
