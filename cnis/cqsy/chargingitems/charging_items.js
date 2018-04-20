var charging = {};

$(function ($) {

    MP.on('MP_RECIPE_PRODUCT_CHANGE', function (data) {
        var sql = "select * from chargingitemsrelation where RecipeAndProduct_DBKey = '{RecipeAndProduct_DBKey}'";
        var sql2 = sql.format({RecipeAndProduct_DBKey:data.RecipeAndProduct_DBKey});
        $(":checkbox").prop("checked", false);
        $.getJSON(pageExt.libPath + "query.php", {sql : sql2}, function( data, status, xhr ) {
            for(j = 0; j < data.length; j++) {   
                $("#checkbox_charging_item_" + data[j].ChargingItemID).prop("checked", "true");
            } 
        });   


    });
    
});
