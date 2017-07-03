function $(id) {
    return document.getElementById(id);
}
function readLS(key) {
    var loc = localStorage.getItem(key);
    if (loc != null) {
        loc = JSON.parse(loc);
    }
    return loc;
}
function serialize(obj, prefix) {
    var str = [], p;
    for (p in obj) {
        if (obj.hasOwnProperty(p)) {
            var k = prefix ? prefix + "[" + p + "]" : p, v = obj[p];
            str.push((v !== null && typeof v === "object") ?
                serialize(v, k) :
                encodeURIComponent(k) + "=" + encodeURIComponent(v));
        }
    }
    return str.join("&");
}
