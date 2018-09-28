
var printout = {};
printout.urlParams = util.urlToObject(window.location.search);

$(function ($) {

    util.bootstrapLodop(2, function(){
        if(util.printerSetting.PrinterName){
            $("#printerName").html(util.printerSetting.PrinterName);
        }else{
            $("#printerName").html("#未设置#");
        }

        if ($("#printerName").html() == "#未设置#") {
            if (confirm("未设置打印机，是否输出到默认打印机？")) {
                $("#btnPrint").click();
            }
        } else {
            //$("#btnPrint").click();

        }

    });

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
    // if (!printout.isDomReady) {
    //     alert("页面没加载完，请重试！");
    //     return;
    // }

    printout.printLoad(4);
    //alert("请等待打印完毕后，再关闭该页面！");
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

    LODOP.PRINT_INITA(0, 0, "148mm", "160mm", "住院医嘱单打印");
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
    
    LODOP.ADD_PRINT_BARCODE("7.12mm","6.59mm","44.58mm","10.13mm","93Extended",printout.urlParams.recipeNo);

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
