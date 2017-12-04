<?php
// require_once '../vendor/autoload.php';
use Firebase\JWT\JWT;

class JWToken
{
    private static $secret = 'ClaveSuperSecreta@';
    private static $hashingAlgorithm = ['HS256'];
    private static $aud = null;

    public static function create($data)
    {
        $now = time();
        $payload = array(
        	'iat'=>$now,
            // 'exp' => $now + (60 * 60 * 1000 ),  // una hora
            'aud' => self::createAud(), // en sha1
            'employee' => $data,
            'app'=> "API REST 2017"
        );
        return JWT::encode($payload, self::$secret);
    }
    public static function verify($token)
    {
        if(empty($token)){
            throw new Exception("El token esta vacio.");
        }
        // si el token es invalido lanza un error.
        try{
            $decodedToken = JWT::decode(
                                    $token,
                                    self::$secret,
                                    self::$hashingAlgorithm
                            );
        }
        catch(Exception $e){
            throw $e;
        }
        /*  si no da error, verifico los datos de AUD para saber si viene de la
            misma audiencia. los dos estan con los valores hasheados en sha1. */
        if($decodedToken->aud !== self::createAud()){
            throw new Exception("No es el usuario valido");
        }
    }
    public static function getPayload($token){
        return JWT::decode(
            $token,
            self::$secret,
            self::$hashingAlgorithm
        );
    }
    public static function getData($token){
      echo($token);
        var_dump( JWT::decode( $token, self::$secret, self::$hashingAlgorithm )->employee );die;
        return JWT::decode(
            $token,
            self::$secret,
            self::$hashingAlgorithm
        )->employee;
    }
    /*  esta funcion sirve para setear quien es la audiencia,
        osea quien recibira el token. O lo que es lo mismo, quien hizo el request.
        Esa informacion deberia estar almacenada en $_SERVER. */
    private static function createAud()
    {
        $aud = '';
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        $aud .= @$_SERVER['HTTP_USER_AGENT'];  // ignore errors.
        $aud .= gethostname();
        return sha1($aud);
    }
}
