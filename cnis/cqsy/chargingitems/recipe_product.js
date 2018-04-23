var product = {};

$(function ($) {

    $(":radio").click(product.productClick);

    $( ".controlgroup-vertical" ).controlgroup({
        "direction": "vertical"
    });

});

product.productClick = function(){
    var RecipeAndProduct_DBKey = $(this).attr("id");
    var productName = $(this).attr("productName");
    MP.send('MP_RECIPE_PRODUCT_CHANGE', { RecipeAndProduct_DBKey: RecipeAndProduct_DBKey, productName : productName });
}