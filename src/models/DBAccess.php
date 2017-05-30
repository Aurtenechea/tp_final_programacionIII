<?php
    class DBAccess{
        /*  el objeto DBAccess va a ser almacenado de forma estatica.
            la idea es instanciarlo solo una vez. */
        public static $DBAccessObj;
        private $PDO;

        /*  El constructor es privado, solo se puede llamar desde dentro de
            la clase. Este creara un objeto PDO para poder conectarse a la DB.*/
        private function __construct(){
            try {
                $this->PDO = new PDO('mysql:host=localhost;dbname=PARKING_SYSTEM;charset=utf8', 'root', 'qweasdzxc', array(PDO::ATTR_EMULATE_PREPARES => false,PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                $this->PDO->exec("SET CHARACTER SET utf8");
            }
            catch (PDOException $e){
                print "Error!: " . $e->getMessage();
                die();
            }
        }

        /*  Cuando uno necesita conectarse a la DB pide un objeto DBAccess.
            Esta funcion verificara si ya esta creado el objeto DBAccess,
            o hay que crearlo. Si hay que crearlo llama al constructor de si
            mismo. Esto creara un objeto PDO para para manejar internamente
            las consultas. */
        public static function getDBAccessObj()
        {
            if (!isset(self::$DBAccessObj)) {
                self::$DBAccessObj = new DBAccess();
            }
            return self::$DBAccessObj;
        }

        public function returnLastInsertId()
        {
            $dba = DBAccess::getDBAccessObj();
            $query = $dba->getQueryObj("select LAST_INSERT_ID() as id;");
            $query->execute();
            $id = $query->fetch(PDO::FETCH_ASSOC);
            return $id['id'];
        //    return $this->PDO->lastInsertId();

        }

        /*  Cuando se desee hacer una consulta, a travez del objeto DBAccess,
            que esta almacenado en el atributo estatico de clase. Se llamara a
            este metodo con el texto sql correspondinte. Esto devolvera un obj
            consulta. */
        public function getQueryObj($sql)
        {
            return $this->PDO->prepare($sql);
        }

        // public function prueba(){
        //     $this->PDO->execute("CALL InsertarPersona ('jose','garcia','123')");
        //      vd("HOLA: ".$this->PDO->lastInsertId());
        //
        //     // return $dba->returnLastInsertId();
        // }
    }
