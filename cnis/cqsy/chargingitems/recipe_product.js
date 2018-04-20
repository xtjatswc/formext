var product = {};

$(function ($) {

    $(":radio").click(product.productClick);

});

product.productClick = function(){
    MP.send('msg', { sayHi: "hello world" });
}