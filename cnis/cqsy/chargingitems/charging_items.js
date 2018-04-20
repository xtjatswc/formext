var charging = {};

$(function ($) {

    MP.on('MP_RECIPE_PRODUCT_CHANGE', function (data) {

        $("#productName").text(data.productName);
        $("#productName").attr("RecipeAndProduct_DBKey", data.RecipeAndProduct_DBKey);

        //回显制剂对应的收费项目
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

//保存对应关系
charging.saveRelation = function(){

    var RecipeAndProduct_DBKey = $("#productName").attr("RecipeAndProduct_DBKey");
    if(!RecipeAndProduct_DBKey){    
        alert("请在左侧列表中点选肠内制剂名称！");
        return;
    }

    $.ajaxSetup({
        async: false
    });

    //先删除
    var sql = "delete from chargingitemsrelation where RecipeAndProduct_DBKey = " + RecipeAndProduct_DBKey;
    $.post(pageExt.libPath + "exec.php", { sql:sql },function(data){
        console.log(data);
    },"json");

    //遍历保存明细
    $("input:checked").each(function(){

        var ChargingItemID = $(this).attr("ChargingItemID");
        var sql2 = "insert into chargingitemsrelation (RecipeAndProduct_DBKey, ChargingItemID) values('{RecipeAndProduct_DBKey}', '{ChargingItemID}') ON DUPLICATE KEY UPDATE ChargingItemID=VALUES(ChargingItemID);";
        var sql2 = sql2.format({RecipeAndProduct_DBKey:RecipeAndProduct_DBKey, ChargingItemID:ChargingItemID});

        $.post(pageExt.libPath + "exec2.php", { sql:sql2 },function(data){
            console.log(data);
        },"json");

    });

    $.ajaxSetup({
        async: true
    });

    alert("保存成功！");
}