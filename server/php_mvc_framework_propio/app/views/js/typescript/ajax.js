var Ajax = (function () {
    function Ajax() {
        var _this = this;
        this.mensaje = "hola";
        this.get = function (ruta, success, params, error) {
            // PROFESOR
            // let parametros:string = params.length > 0 ? params : "";
            // ruta = params.length > 0 ? ruta + "?" + parametros : ruta;
            // MIO...
            var parametros = (typeof (params) != "undefined" && params !== null) ? params : "";
            ruta = parametros.length > 0 ? ruta + "?" + parametros : ruta;
            _this.xhr = new XMLHttpRequest();
            _this.xhr.open('GET', ruta);
            _this.xhr.send(null);
            _this.xhr.onreadystatechange = function () {
                var DONE = 4; // readyState 4 means the request is done.
                var OK = 200; // status 200 is a successful return.
                if (_this.xhr.readyState === DONE) {
                    if (_this.xhr.status === OK) {
                        success(_this.xhr.responseText);
                    }
                    else {
                        if (error)
                            error(_this.xhr.status);
                    }
                }
            };
        };
        this.post = function (ruta, success, params, error) {
            var parametros = params.length > 0 ? params : "";
            _this.xhr = new XMLHttpRequest();
            _this.xhr.open('POST', ruta, true);
            _this.xhr.setRequestHeader("content-type", "application/x-www-form-urlencoded");
            _this.xhr.send(parametros);
            _this.xhr.onreadystatechange = function () {
                var DONE = 4; // readyState 4 means the request is done.
                var OK = 200; // status 200 is a successful return.
                if (_this.xhr.readyState === DONE) {
                    if (_this.xhr.status === OK) {
                        success(_this.xhr.responseText);
                    }
                    else {
                        if (error)
                            error(_this.xhr.status);
                    }
                }
            };
        };
    }
    return Ajax;
}());
