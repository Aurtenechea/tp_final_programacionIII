<?php
class Model
{
    protected $table;
    protected $primaryKey = 'id';


    public static function find($id){
        /* a las variables del tipo :id se le llama place holder.*/

        /* la idea es que se herede de esta clase y aca se cree un objeto del tipo
            del que esta heredando. Como pasa en usuario. */
        $model = new static();
        $sql = "SELECT * FROM " . $model->table . " WHERE " . $model->primaryKey . " = :id";
        $params = ["id" => $id];
        /* aca se hace como un bind con el array de claves  */
        $result = DB::query($sql, $params);

        /*  carga el objeto con los valores de la fila obtenida. */
        foreach ($result as $key => $value) {
            $model->$key = $value;
        }
        return $model;
    }
}
