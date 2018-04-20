var charging = {};

$(function ($) {

    MP.on('MP_RECIPE_PRODUCT_CHANGE', function (data) {
        console.log(data.RecipeAndProduct_DBKey);

        var sql = "select * from chargingadvicedetail where NutrientAdviceDetail_DBKEY = '{NutrientAdviceDetail_DBKEY}'";
        var sql2 = sql.format({NutrientAdviceDetail_DBKEY:detailId});
        $.getJSON(pageExt.libPath + "query.php", {sql : sql2}, function( data, status, xhr ) {
            for(j = 0; j < data.length; j++) {        
                util.getSelectOptionByValue("#select_chargingitem_" + detailId, data[j].ChargingItemID).attr("selected",true);
                util.getSelectOptionByText("#select_spec_" + detailId, data[j].ChargingItemSpec).attr("selected",true);
                $("#text_price_" + detailId).val(data[j].ChargingPrice);
                $("#text_num_" + detailId).val(data[j].ChargingNum);
                $("#text_unit_" + detailId).val(data[j].ChargingItemUnit);
                $("#text_money_" + detailId).val(data[j].ChargingMoney);
            } 

            printout.calcTotalMoney();
        });   


    });
    
});
