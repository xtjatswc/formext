
var printout = {};
printout.urlParams = util.urlToObject(window.location.search);
printout.isDomReady = false;

$(function ($) {

    util.bootstrapLodop(2, function(){
        if(util.printerSetting.PrinterName){
            $("#printerName").html(util.printerSetting.PrinterName);
        }else{
            $("#printerName").html("#未设置#");
        }

        printout.isDomReady = true;
    });

    // $(":radio").click(printout.calcMoney);
    $(":text").keyup(printout.calcMoney);
    $("select").change(printout.calcMoney);
    printout.calcMoney();
    printout.reload();
});

printout.printSetting = function () {
    window.open("../../../../formext/form_rander/printerSet.php");
}

printout.printDesign = function () {
    printout.printLoad(1);
}

printout.printSetup = function () {
    printout.printLoad(2);
}

printout.preview = function () {
    printout.printLoad(3);
}

printout.print = function () {
    if (!printout.isDomReady) {
        alert("页面没加载完，请重试！");
        return;
    }

    //先保存
    $("#btnSave").click();

    if ($("#printerName").html() == "#未设置#") {
        if (confirm("未设置打印机，是否输出到默认打印机？")) {
            printout.printLoad(4);
        }
    }else
        printout.printLoad(4);

}

printout.printLoad = function (flag) {

    var $divRecipe = $("#divRecipe");
    printout.createPrintPage($divRecipe[0].outerHTML);
    if (flag == 1) {
        LODOP.PRINT_DESIGN();
        return false;
    } else if (flag == 2) {
        LODOP.PRINT_SETUP();
        return false;
    } else if (flag == 3) {
        LODOP.PREVIEW();
        return false;
    } else if (flag == 4) {
        LODOP.PRINT();
    }
}

printout.createPrintPage = function (divRecipe) {

    LODOP.PRINT_INITA(0, 0, "148mm", "160mm", "门诊医嘱单打印");
    //LODOP.SET_PRINTER_INDEX(getSelectedPrintIndex());    
    if (util.printerSetting.PrinterName == "#未设置#") {
        $("#lsMsg").html("尚未设置默认的医嘱单打印机！");
    } else {
        if (!LODOP.SET_PRINTER_INDEXA(util.printerSetting.PrinterName)) {
            $("#lsMsg").html("未检测到该打印机，将输出到默认打印机！");
        }
    }

    //LODOP.SET_PRINT_PAGESIZE(0,0,0,getSelectedPageSize());
    LODOP.SET_PRINT_PAGESIZE(util.printerSetting.Orient, util.printerSetting.PageWidth, util.printerSetting.PageHeigth, util.printerSetting.PageName);
    //是否控制位置基点，true时，对套打有利
    LODOP.SET_PRINT_MODE("POS_BASEON_PAPER", false);
    var strStyle = document.getElementById("style1").outerHTML;//"<style> table,td,th {border-width: 1px;border-style: solid;border-collapse: collapse}</style>"
    
    //LODOP.ADD_PRINT_BARCODE("28mm","22.6mm","35.58mm","10.13mm","93Extended",printout.urlParams.recipeNo);

    LODOP.ADD_PRINT_HTM("1.01mm", "1.01mm", "145mm", "150mm", strStyle + divRecipe);
    LODOP.SET_PRINT_STYLEA(0,"Horient",3); 
    LODOP.SET_PRINT_STYLEA(0, "Vorient", 3);

    //LODOP.ADD_PRINT_TABLE("1.59mm","1.01mm","85.01mm","28mm",strStyle + document.getElementById("tblNutrientadvicedetail").outerHTML);
    //LODOP.SET_PRINT_STYLEA(0,"Vorient",3);
    //LODOP.SET_PRINT_STYLEA(0,"Offset2Top","-10mm"); //设置次页偏移把区域向上扩
    //LODOP.SET_PRINT_STYLEA(0,"LinkedItem",-1);

    LODOP.SET_PRINT_STYLE("FontName", "微软雅黑");
    LODOP.SET_PRINT_STYLE("FontSize", "10.5");

    LODOP.ADD_PRINT_TEXT("153.07mm","108.74mm","31.49mm","5.29mm","第#页/共&页");
    LODOP.SET_PRINT_STYLEA(0,"Horient",1);
    LODOP.SET_PRINT_STYLEA(0, "Vorient", 1);
    LODOP.SET_PRINT_STYLEA(0, "ItemType", 2);
    LODOP.SET_PRINT_STYLEA(0, "LinkedItem", -1);

    LODOP.ADD_PRINT_TEXT("153.07mm","45.64mm","60.85mm","5.29mm","打印时间：" + util.getTime());
    LODOP.SET_PRINT_STYLEA(0,"Horient",1);
    LODOP.SET_PRINT_STYLEA(0, "Vorient", 1);
    LODOP.SET_PRINT_STYLEA(0, "ItemType", 1);
    LODOP.SET_PRINT_STYLEA(0, "LinkedItem", -2);

    LODOP.SET_SHOW_MODE("SHOW_SCALEBAR",true);//语句控制显示标尺
    LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED", 1);//横向时的正向显示

}

printout.calcMoney = function(){

    //触发事件控件
    var eventName = $(this).attr("name");

    //遍历所有收费项目
    $("select[name='select_chargingitem']").each(function(){
        $tr = $(this).parents("tr");
        var detailId = $tr.attr("NutrientAdviceDetail_DBKEY");
        var RecipeAndProduct_DBKey = $tr.attr("RecipeAndProduct_DBKey");
    
        var $option = $(this).children(":selected");
        var spec = $option.attr("spec");
        var price1 = $option.attr("price1");
        var price2 = $option.attr("price2");
        var unit = $option.attr("unit");

        if(typeof(spec) == "undefined") return true; //// false时相当于break, 如果return true 就相当于continure。 

        //加载对应规格
        var specArr = spec.split("#");
        if(!eventName || eventName == "select_chargingitem"){
            $("#select_spec_" + detailId).empty();
            for (var index = 0; index < specArr.length; index++) {
                $("#select_spec_" + detailId).append("<option>" + specArr[index] + "</option>");
            }
        }

        //根据规格取单价
        var price = price1;
        if(specArr.length == 2 &&  $("#select_spec_" + detailId).prop('selectedIndex') == 1){
            price = price2;
        }
        $("#text_price_" + detailId).val(price);

        //金额
        var num = $("#text_num_" + detailId).val();
        var money = num * price;
        $("#text_money_" + detailId).val(money.toFixed(2));

        //单位
        $("#text_unit_" + detailId).val(unit);

        //计算总金额
        printout.calcTotalMoney();
    });
}

//计算总金额
printout.calcTotalMoney = function(){
    var totalMoney = 0;
    $(":text[name='text_money']").each(function(){
        var value = parseFloat(this.value);
        if(value)
            totalMoney += value;
    });
    $("#label_totalMoney").text("总金额：" + totalMoney.toFixed(2) + " 元");
}

//回显收费信息
printout.reload = function(){
    //遍历所有收费项目
    $("select[name='select_chargingitem']").each(function(){
        $tr = $(this).parents("tr");
        var detailId = $tr.attr("NutrientAdviceDetail_DBKEY");
        var RecipeAndProduct_DBKey = $tr.attr("RecipeAndProduct_DBKey");

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
        
}

printout.save = function(){

    $.ajaxSetup({
        async: false
    });

    //遍历所有收费项目
    $("select[name='select_chargingitem']").each(function(){
        $tr = $(this).parents("tr");
        var detailId = $tr.attr("NutrientAdviceDetail_DBKEY");
        var RecipeAndProduct_DBKey = $tr.attr("RecipeAndProduct_DBKey");   
        
        var num = $("#text_num_" + detailId).val();
        var price = $("#text_price_" + detailId).val();
        var money = $("#text_money_" + detailId).val();
        var ChargingItemUnit = $("#text_unit_" + detailId).val();

        var $option = $(this).children(":selected");
        var ChargingItemID = $option.attr("ChargingItemID");
        var ChargingItemName = $option.text();
        var ChargingItemSpec = $("#select_spec_" + detailId).children(":selected").text();

        //保存结果
        var sql = "insert into chargingadvicedetail(NutrientAdviceDetail_DBKEY,RecipeAndProduct_DBKey,ChargingItemID,ChargingItemName,ChargingItemSpec,ChargingItemUnit,ChargingNum,ChargingPrice,ChargingMoney) values('{NutrientAdviceDetail_DBKEY}','{RecipeAndProduct_DBKey}','{ChargingItemID}','{ChargingItemName}','{ChargingItemSpec}','{ChargingItemUnit}','{ChargingNum}','{ChargingPrice}','{ChargingMoney}') ON DUPLICATE KEY UPDATE RecipeAndProduct_DBKey=VALUES(RecipeAndProduct_DBKey),ChargingItemID=VALUES(ChargingItemID),ChargingItemName=VALUES(ChargingItemName),ChargingItemSpec=VALUES(ChargingItemSpec),ChargingItemUnit=VALUES(ChargingItemUnit),ChargingNum=VALUES(ChargingNum),ChargingPrice=VALUES(ChargingPrice),ChargingMoney=VALUES(ChargingMoney);";

        var sql2 = sql.format({NutrientAdviceDetail_DBKEY:detailId, RecipeAndProduct_DBKey:RecipeAndProduct_DBKey,ChargingItemID:ChargingItemID,ChargingItemName:ChargingItemName,ChargingItemSpec:ChargingItemSpec, ChargingItemUnit:ChargingItemUnit, ChargingNum: num, ChargingPrice : price, ChargingMoney:money});

        $.post(pageExt.libPath + "exec2.php", { sql:sql2 },function(data){
            var d = data;
        },"json");

    });

    //alert("保存成功！");

    $("#divRecipe").load("order.php?recipeNo=" + printout.urlParams.recipeNo);

    $.ajaxSetup({
        async: true
    });

}