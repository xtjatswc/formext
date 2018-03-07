var s10 = {};

$(function ($) {

    util.bootstrapLodop(3, function(){

        var a = util.PcSN
    });

});
    

s10.printSetting = function () {
    window.open("../../../../formext/form_rander/printerSet.php");
}

s10.printDesign = function () {
    s10.printLoad(1);
}

s10.printSetup = function () {
    s10.printLoad(2);
}

s10.preview = function () {
    s10.printLoad(3);
}

s10.print = function () {
    if (!s10.isDomReady) {
        alert("页面没加载完，请重试！");
        return;
    }

    s10.printLoad(4);
    alert("请等待打印完毕后，再关闭该页面！");
}

s10.printLoad = function (flag) {
    // LODOP = getLodop();

    s10.createPrintPage();
    if (flag == 1) {
        LODOP.PRINT_DESIGN();
        return false;
    } else if (flag == 2) {
        LODOP.PRINT_SETUP();
        return false;
    } else if (flag == 3) {
        LODOP.SET_SHOW_MODE("SHOW_SCALEBAR",true);//语句控制显示标尺
        LODOP.PREVIEW();
        return false;
    } else if (flag == 4) {
        LODOP.PRINT();
    }
}

s10.createPrintPage = function () {

    LODOP.PRINT_INITA(0, 0, "210mm", "297mm", "InBody报告纸打印");
    //LODOP.SET_PRINTER_INDEX(getSelectedPrintIndex());    
    if (util.printerSetting.PrinterName == "#未设置#") {
        $("#lsMsg").html("尚未设置默认的标签打印机！");
    } else {
        if (!LODOP.SET_PRINTER_INDEXA(util.printerSetting.PrinterName)) {
            $("#lsMsg").html("未检测到该打印机，将输出到默认打印机！");
        }
    }

    //LODOP.SET_PRINT_PAGESIZE(0,0,0,getSelectedPageSize());
    LODOP.SET_PRINT_PAGESIZE(util.printerSetting.Orient, util.printerSetting.PageWidth, util.printerSetting.PageHeigth, util.printerSetting.PageName);
    //是否控制位置基点，true时，对套打有利
    LODOP.SET_PRINT_MODE("POS_BASEON_PAPER", true);
    //是否可以重新选择打印机
    LODOP.SET_PRINT_MODE("RESELECT_PRINTER",false);

    LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='s10模板.jpg'>");
    LODOP.SET_SHOW_MODE("BKIMG_WIDTH","210mm");
    LODOP.SET_SHOW_MODE("BKIMG_HEIGHT","297mm");
    LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",true);
    LODOP.SET_SHOW_MODE("BKIMG_PRINT",true);

}