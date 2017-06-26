<?php
class HomeController
{
    public function actionIndex($id){

        $user = User::find($id);

        /* response se puede usar porque desde el index fue
        incluida (y este archivo tamb).
        Al Response se le pueden pasar parametros que pueden ser usados en
        las vistas como variables del nombre de la clave. */
        Response::render('home', [
                                'name' => $user->name,
                                'age' => $user->age,
                                'email'=> $user->email
                            ]);
    }

    /*  ESTA FUNCION NO DEBERIA VERIFICAR NADA, SIMPLEMENTE CONTESTAR LA PETICION
        ES EL .js EL QUE VA A VERIFICAR CON LA API Y REDICCIONAR SI ES NECESARIO. */
    public function actionDashboard(){
        // session_start();
        //
        // if( !isset($_SESSION['loged_in']) || !$_SESSION['loged_in']){
        //     header("location:http://localhost/utn/tp_final_programacionIII/server/php_mvc_framework_propio/public/user/login/");
        // }
        Response::render('dashboard', []);
    }

    /*  ESTA FUNCION NO DEBERIA VERIFICAR NADA, SIMPLEMENTE CONTESTAR LA PETICION
        ES EL .js EL QUE VA A VERIFICAR CON LA API Y REDICCIONAR SI ES NECESARIO. */
    public function actionAdmin_panel(){
        Response::render('admin_panel', []);
    }

    public function actionAbout(){
        echo "Hola desde el about";
    }
}
