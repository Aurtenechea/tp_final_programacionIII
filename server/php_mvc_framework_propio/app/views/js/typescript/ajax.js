var Ajax = (function () {
    // public headers Array<string>;
    function Ajax() {
        var _this = this;
        this.get = function (ruta, success, params, error) {
            var parametros = (typeof (params) != "undefined" && params !== null) ? params : "";
            ruta = parametros.length > 0 ? ruta + "?" + parametros : ruta;
            _this.xhr.open('GET', ruta);
            _this.xhr.onreadystatechange = function () {
                var DONE = 4; // readyState 4 means the request is done.
                var OK = 200; // status 200 is a successful return.
                if (_this.xhr.readyState === DONE) {
                    if (_this.xhr.status === OK) {
                        success(_this.xhr.responseText);
                    }
                    else {
                        if (error) {
                            error(_this.xhr.status);
                        }
                    }
                }
            };
        };
        // this.xhr.send(parametros);
        this.post = function (ruta, success, error) {
            _this.xhr.open('POST', ruta, true);
            _this.xhr.onreadystatechange = function () {
                var DONE = 4; // readyState 4 means the request is done.
                var OK = 200; // status 200 is a successful return.
                if (_this.xhr.readyState === DONE) {
                    if (_this.xhr.status === OK) {
                        success(_this.xhr.responseText);
                    }
                    else {
                        if (error) {
                            error(_this.xhr.status);
                        }
                    }
                }
            };
        };
        this.send = function (params) {
            if (typeof (params) != "undefined" && params !== null) {
                _this.xhr.send(params);
            }
            else {
                _this.xhr.send();
            }
        };
        this.xhr = new XMLHttpRequest();
        // this.headers = null;
    }
    return Ajax;
}());
/*  Forma de uso. */
// ajax = new Ajax();
// ajax.post(url , sCallback, eCallback);
// ajax.xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
// ajax.send(params);
//
// ajax = new Ajax();
// ajax.get(url , sCallback, eCallback);
// ajax.send();
