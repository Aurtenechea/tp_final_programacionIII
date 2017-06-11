<?php
class UserController
{
    public function actionLogin(){
        Response::render('login', []);
    }

    /*  esto no esta funcioando ya que la verificacion se esta realizando en la
        api y se logea en esta. Por lo tanto desde el server se puede decir que
        la peticion siempre es valida.. si quieren el login se les da, y si
        quieren el dashboard tamb. */
    // public function actionVerify(){
    // /*  se deberia hacer uso de la clase user y buscar el usuario en la base
    //     de datos. Verificar la contrasena y luego responder el objeto json. */
    //     session_start();
    //
    //     if( isset($_POST["usuario"]) && isset($_POST["password"]) ){
    //         $user = new stdClass();
    //         $user->loged_in = false;
    //         $user->name =  $_POST['email'];
    //
    //         $_SESSION["loged_in"] = false;
    //         $_SESSION["name"] = $_POST['email'];
    //         // $_SESSION["email"] = $_POST['email'];
    //
    //     	if( $_POST["usuario"] == "admin" &&  $_POST["password"]== "admin"){
    //             $user->loged_in = true;
    //             $_SESSION["loged_in"] = true;
    //             return true;
    //         }
    //         else {return false;}
    //         $userJson = json_encode($user, JSON_FORCE_OBJECT);
    //         echo $userJson;
    //     }
    // }
}
