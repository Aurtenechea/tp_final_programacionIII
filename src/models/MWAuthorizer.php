<?php
require_once "JWToken.php";
class MWAuthorizer{

	public function userVerification($request, $response, $next) {
		// $response->getBody()->write('Ejecucion del MW. Pre funcion.');
		// $request = $request->withAttribute('foo', 'value');
		// $response = $next($request, $response);
		// $response->getBody()->write('Ejecucion del MW.');
		$headers = getallheaders();
		if(!isset($headers['Authorization'])){
			$headers['Authorization'] = '';
		}
	    $token = $headers['Authorization'];
	    $token = explode(" ", $token);
		$token = array_reverse($token);
	    $token = $token[0];
		// $token = str_replace('"', '', $token);
	    $validation = false;
		// echo("El token es: " . $token);
		/*	JWToken::verify lanza un error si el token es invalido. */
	    try{
			JWToken::verify($token);
			$validation = true;
	    }
	    catch (Exception $e) {
			// echo($e);
			echo "Verification error.";
	    }
		/*	Si */
	    if($validation){
	        // echo "Valid Token.";
			$response = $next($request, $response);
	    }
	    else{
			echo "Invalid Token.";
	    }
		// $response->getBody()->write('Ejecucion del MW. Post funcion.');
		return $response;

		// if($request->isGet()){
		// // $response->getBody()->write('<p>NO necesita credenciales para los get </p>');
		//  $response = $next($request, $response);
		// }
		// else
		// {
		// 	//$response->getBody()->write('<p>verifico credenciales</p>');
		//
		// 	//perfil=Profesor (GET, POST)
		// 	//$datos = array('usuario' => 'rogelio@agua.com','perfil' => 'profe', 'alias' => "PinkBoy");
		//
		// 	//perfil=Administrador(todos)
		// 	$datos = array('usuario' => 'rogelio@agua.com','perfil' => 'Administrador', 'alias' => "PinkBoy");
		//
		// 	$token= JWToken::create($datos);
		//
		// 	//token vencido
		// 	//$token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0OTc1Njc5NjUsImV4cCI6MTQ5NzU2NDM2NSwiYXVkIjoiNGQ5ODU5ZGU4MjY4N2Y0YzEyMDg5NzY5MzQ2OGFhNzkyYTYxNTMwYSIsImRhdGEiOnsidXN1YXJpbyI6InJvZ2VsaW9AYWd1YS5jb20iLCJwZXJmaWwiOiJBZG1pbmlzdHJhZG9yIiwiYWxpYXMiOiJQaW5rQm95In0sImFwcCI6IkFQSSBSRVNUIENEIDIwMTcifQ.GSpkrzIp2UbJWNfC1brUF_O4h8PyqykmW18vte1bhMw";
		//
		// 	//token error
		// 	//$token="octavioAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0OTc1Njc5NjUsImV4cCI6MTQ5NzU2NDM2NSwiYXVkIjoiNGQ5ODU5ZGU4MjY4N2Y0YzEyMDg5NzY5MzQ2OGFhNzkyYTYxNTMwYSIsImRhdGEiOnsidXN1YXJpbyI6InJvZ2VsaW9AYWd1YS5jb20iLCJwZXJmaWwiOiJBZG1pbmlzdHJhZG9yIiwiYWxpYXMiOiJQaW5rQm95In0sImFwcCI6IkFQSSBSRVNUIENEIDIwMTcifQ.GSpkrzIp2UbJWNfC1brUF_O4h8PyqykmW18vte1bhMw";
		//
		// 	//tomo el token del header
		// 	/*
		// 		$arrayConToken = $request->getHeader('token');
		// 		$token=$arrayConToken[0];
		// 	*/
		// 	//var_dump($token);
		// 	$objDelaRespuesta->esValido=true;
		// 	try
		// 	{
		// 		//$token="";
		// 		JWToken::verify($token);
		// 		$objDelaRespuesta->esValido=true;
		// 	}
		// 	catch (Exception $e) {
		// 		//guardar en un log
		// 		$objDelaRespuesta->excepcion=$e->getMessage();
		// 		$objDelaRespuesta->esValido=false;
		// 	}
		//
		// 	if($objDelaRespuesta->esValido)
		// 	{
		// 		if($request->isPost())
		// 		{
		// 			// el post sirve para todos los logeados
		// 			$response = $next($request, $response);
		// 		}
		// 		else
		// 		{
		// 			$payload=JWToken::getData($token);
		// 			//var_dump($payload);
		// 			// DELETE,PUT y DELETE sirve para todos los logeados y admin
		// 			if($payload->perfil=="Administrador")
		// 			{
						// $response = $next($request, $response);
		// 			}
		// 			else
		// 			{
		// 				$objDelaRespuesta->respuesta="Solo administradores";
		// 			}
		// 		}
		// 	}
		// 	else
		// 	{
		// 		//   $response->getBody()->write('<p>no tenes habilitado el ingreso</p>');
		// 		$objDelaRespuesta->respuesta="Solo usuarios registrados";
		// 		$objDelaRespuesta->elToken=$token;
		//
		// 	}
		// }
		// if($objDelaRespuesta->respuesta!="")
		// {
		// 	$nueva=$response->withJson($objDelaRespuesta, 401);
		// 	return $nueva;
		// }
	 	//$response->getBody()->write('<p>vuelvo del verificador de credenciales</p>');
	}
}
