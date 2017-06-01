<?php
//  <Dependencies ----------------------------------------------
require_once("DBAccess.php");
require_once("lib.php");
//  </Dependencies ----------------------------------------------

class User implements JsonSerializable{
    // <attr ----------------------------------------------------
    private $id;
    private $mail;
    private $pass;
    private $state;
    // </attr ----------------------------------------------------

    // <getters and setters --------------------------------------
    public function getId() { return $this->id; }
    public function getMail() { return $this->mail; }
    public function getPass() { return $this->pass; }
    public function getState() { return $this->state; }
    public function setId($value) { $this->id = $value; }
    public function setMail($value) { $this->mail = $value; }
    public function setPass($value) { $this->pass = $value; }
    public function setState($value) { $this->state = $value; }
    // </getters and setters --------------------------------------

    /* para poder serializar con atributos privados. */
    public function jsonSerialize(){
        return get_object_vars($this);
    }

    // <API methods **************************************
    public function save(){
        try{
            $dba = DBAccess::getDBAccessObj();
            $query = $dba->getQueryObj("INSERT INTO USER(
                                                    mail,
                                                    pass,
                                                    state
                                                    )
                                            VALUES (:mail,
                                                    :pass,
                                                    :state
                                            );");
            $query->bindValue(':mail',$this->mail, PDO::PARAM_STR);
            $query->bindValue(':pass',$this->pass, PDO::PARAM_STR);
    		$query->bindValue(':state',$this->state, PDO::PARAM_STR);
            $query->execute();
        }catch(Exception $e){
            throw $e;
        }
        return $dba->returnLastInsertId();
    }
    public static function getAll(){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM USER;");
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS, "User");

        return json_encode($result);
    }
    public static function getFromId($user_id){
        $dba = DBAccess::getDBAccessObj();
        $query = $dba->getQueryObj("SELECT * FROM USER WHERE id = :id");
        $query->bindValue(':id',$user_id, PDO::PARAM_INT);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_CLASS, "User");
        return $result[0];
    }
    public static function deleteFromId($user_id){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("DELETE FROM user WHERE id = :id");
		$query->bindValue(':id',$user_id, PDO::PARAM_INT);
		$query->execute();
		return $query->rowCount();
	}
    public static function updateFromId($user){
		$dba = DBAccess::getDBAccessObj();
		$query = $dba->getQueryObj("UPDATE USER
                        				set
                                            mail= :mail,
                            				pass= :pass,
                            				state= :state
                        				WHERE id= :id ;"
                                    );
        $query->bindValue(':id', $user->getId(), PDO::PARAM_INT);
		$query->bindValue(':mail', $user->getMail(), PDO::PARAM_STR);
        $query->bindValue(':pass', $user->getPass(), PDO::PARAM_STR);
        $query->bindValue(':state', $user->getState(), PDO::PARAM_STR);
		$query->execute();
		return $query;
        // return 0;
	}
    // </API methods **************************************

}
