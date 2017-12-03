/// <reference path="routes.ts"/>
/// <reference path="car.ts"/>

function hacerConElAuto(car :Car){
    console.log(car.color);
    // alert(car.color);
}

Car.search_car("JJJ-123", hacerConElAuto);

// console.log(car);
