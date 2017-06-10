<?php


class App
{
    /*  controlador y metodo default. Si se requieren algunos que no existen
        se llamaran a los defaults. */
    protected $controller   = "HomeController";
    protected $method       = "actionIndex";
    protected $params       = [];

    public function __construct(){
        /*  devuelve los parametros de la url. Que estaban en este formato:
            public/home/index/1 */
        $url = $this->parseUrl();

        /*  con el primer parametro (por ej /home/) crea el nombre del controlador.
            por convencion HomeController. */
        $controllerName = ucfirst( strtolower($url[0]) ) . "Controller";

        /*  si existe el controlador que se pidio, se pisa el default */
        if( file_exists( APP_PATH . "controllers/" . $controllerName . ".php") ){
            $this->controller = $controllerName;
            /*  unset elimina el elemento pero matiene los indices de todos los demas.
                (no va a existir elemento 0 pero si 1 y 2). */
            unset($url[0]);
        }

        /*  se incluye el archivo del controlador que este en $this->controller */
        require APP_PATH . "controllers/" . $this->controller . ".php";
        /*  se instancia un controlador de ese tipo. Todo de forma dinamica. */
        $this->controller = new $this->controller;

        if( isset($url[1]) ){
            /*  si esta seteado el action recuperarlo al formato de la convencion.
            ej: actionIndex */
            $methodName = "action" . ucfirst( strtolower($url[1]) );
            /*  si el controlador tiene ese metodo, pisa el de default */
            if( method_exists($this->controller, $methodName) ){
                $this->method = $methodName;
                unset($url[1]);
            }
        }

        /*  guarda los parametros que quedan pero empezando por el indice cero. */
        $this->params = $url ? array_values($url) : $this->params;

        /*  llama al metodo de la clases (ambos pasados en el primer parametro)
            con los parametros del array del segundo parametro */
        call_user_func_array( [$this->controller, $this->method], $this->params );


    }

    /*  devuelve los parametros de la url. Que estaban en este formato:
        public/home/index/1 */
    public function parseUrl(){
        if ( isset($_GET['url']) ){
            $data = rtrim($_GET['url'], "/"); // quita la barra final.
            $data = filter_var($data , FILTER_SANITIZE_URL); // quita caracteres especiales.
            return explode("/", $data); // devuelve un array.
        }
    }
}
