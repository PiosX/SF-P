const $ = require('jquery');

$.fn.myfunction = function () {
    console.log(this.length);
};

$('.foo').myfunction();