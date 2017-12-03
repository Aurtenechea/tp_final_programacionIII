class Ajax {
    public xhr: XMLHttpRequest;
    public getParams : string;
    public postParams : any;
    // public headers Array<string>;

    constructor() {
        this.xhr = new XMLHttpRequest();
        // this.headers = null;
    }

    public get = (ruta: string, success: Function, params?:string, error?: Function) => {
        let parametros:string = (typeof(params) != "undefined" && params !== null) ? params : "";
        ruta = parametros.length > 0 ? ruta + "?" + parametros : ruta;
        this.xhr.open('GET', ruta);
        this.xhr.onreadystatechange = () => {
            var DONE = 4; // readyState 4 means the request is done.
            var OK = 200; // status 200 is a successful return.
            if (this.xhr.readyState === DONE) {
                if (this.xhr.status === OK) {
                    success(this.xhr.responseText);
                }
                else {
                    if(error){
                        error(this.xhr.status);
                    }
                }
            }
        };
    }
    // this.xhr.send(parametros);
    public post = (ruta: string, success: Function, error?: Function) => {
        this.xhr.open('POST', ruta, true);
        this.xhr.onreadystatechange = () => {
            var DONE = 4; // readyState 4 means the request is done.
            var OK = 200; // status 200 is a successful return.
            if (this.xhr.readyState === DONE) {
                if (this.xhr.status === OK) {
                    success(this.xhr.responseText);
                }
                else {
                    if(error){
                        error(this.xhr.status);
                    }
                }
            }
        };
    }

    public send = (params? :any) => {
        if(typeof(params) != "undefined" && params !== null){
            this.xhr.send(params);
        }
        else{
            this.xhr.send();
        }
    }
}

/*  Forma de uso. */
// ajax = new Ajax();
// ajax.post(url , sCallback, eCallback);
// ajax.xhr.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
// ajax.send(params);
//
// ajax = new Ajax();
// ajax.get(url , sCallback, eCallback);
// ajax.send();
