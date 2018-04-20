var product = {};

$(function ($) {

    $(":radio").click(product.productClick);

});

product.productClick = function(){
    var RecipeAndProduct_DBKey = $(this).attr("id");
    MP.send('MP_RECIPE_PRODUCT_CHANGE', { RecipeAndProduct_DBKey: RecipeAndProduct_DBKey });
}