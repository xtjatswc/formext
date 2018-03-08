var s10 = {};

s10.patient = null;
s10.report = null;

$(function ($) {

    util.bootstrapLodop(3, function(){

        if(util.printerSetting.PrinterName){
            $("#printerName").html(util.printerSetting.PrinterName);
        }else{
            $("#printerName").html("#未设置#");
        }

        var timer1 = window.setInterval(function () {
            if(s10.patient && s10.report){

                window.clearInterval(timer1);
                if ($("#printerName").html() == "#未设置#") {
                    if (confirm("未设置打印机，是否输出到默认打印机？")) {
                        $("#btnPrint").click();
                    }
                } else {
                    $("#btnPrint").click();
    
                }
    
            }
        }, 500);

    });

    var urlParams = util.urlToObject(window.location.search);

    if(!urlParams || typeof(urlParams.reportId) == "undefined"){
        alert("未获取到报告ID！");
        return;
    }

    var sql = "select a.*,b.Height from inbodyreport a inner join patienthospitalizebasicinfo b on a.PatientHospitalize_DBKey = b.PatientHospitalize_DBKey where  a.InBodyReport_DBKey = " + urlParams.reportId + ";";
    $.getJSON(pageExt.libPath + "query.php", { sql: sql }, function (data, status, xhr) {
        s10.patient = data[0];
    });

    sql = "select * from inbodyresult where InBodyReport_DBKey = " + urlParams.reportId + ";";
    $.getJSON(pageExt.libPath + "query.php", { sql: sql }, function (data, status, xhr) {
        s10.report = data;
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

    s10.printLoad(4);
    // alert("请等待打印完毕后，再关闭该页面！");
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
        LODOP.PREVIEW();
        return false;
    } else if (flag == 4) {
        LODOP.PRINT();
    }
}

s10.createPrintPage = function () {

    LODOP.PRINT_INITA(0, 0, "210mm", "297mm", "InBody报告纸打印");
    if (util.printerSetting.PrinterName == "#未设置#") {
        $("#lsMsg").html("尚未设置默认的标签打印机！");
    } else {
        if (!LODOP.SET_PRINTER_INDEXA(util.printerSetting.PrinterName)) {
            $("#lsMsg").html("未检测到该打印机，将输出到默认打印机！");
        }
    }

    //LODOP.SET_PRINT_PAGESIZE(0,0,0,getSelectedPageSize());
    LODOP.SET_PRINT_PAGESIZE(util.printerSetting.Orient, util.printerSetting.PageWidth, util.printerSetting.PageHeigth, util.printerSetting.PageName);

    LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='s10模板.jpg'>");
    LODOP.SET_SHOW_MODE("BKIMG_WIDTH","210mm");
    LODOP.SET_SHOW_MODE("BKIMG_HEIGHT","297mm");
    LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",true);
    LODOP.SET_SHOW_MODE("BKIMG_PRINT",true);    
    LODOP.SET_SHOW_MODE("SHOW_SCALEBAR",true);  //语句控制显示标尺
    LODOP.SET_SHOW_MODE("PREVIEW_NO_MINIMIZE",true);    //设置预览窗口禁止最小化，并始终在各个窗口的最前面        
    LODOP.SET_PRINT_MODE("POS_BASEON_PAPER", true); //是否控制位置基点，true时，对套打有利        
    LODOP.SET_PRINT_MODE("RESELECT_PRINTER",false); //是否可以重新选择打印机
    
    // LODOP.SET_PRINT_STYLE("FontName", "微软雅黑");
    // LODOP.SET_PRINT_STYLE("FontSize", "10.5");

    LODOP.ADD_PRINT_TEXT(94,68,100,20,s10.patient.InBodyReport_DBKey); 
    LODOP.ADD_PRINT_TEXT(116,68,50,20,s10.patient.Age);
    LODOP.ADD_PRINT_TEXT(94,237,50,20,s10.patient.Height + "cm");
    LODOP.ADD_PRINT_TEXT(116,237,50,20,s10.patient.Gender == "M" ? "男" : "女");
    LODOP.ADD_PRINT_TEXT(94,388,150,20,s10.patient.TestTime.split(" ")[0]);
    LODOP.ADD_PRINT_TEXT(116,389,150,20,s10.patient.TestTime.split(" ")[1]);
    LODOP.ADD_PRINT_TEXT(209,697,100,20,s10.toFixed2(1) + "kg");
    LODOP.ADD_PRINT_TEXT(208,617,100,20,s10.toFixed2(9) + "kg");
    LODOP.ADD_PRINT_TEXT(208,548,100,20,s10.toFixed2(8) + "kg");
    LODOP.ADD_PRINT_TEXT(191,476,100,20,s10.toFixed2(7) + "kg");
    LODOP.ADD_PRINT_TEXT(185,304,100,20,s10.range(70, 69));
    LODOP.ADD_PRINT_TEXT(205,304,100,20,s10.range(72, 71));
    LODOP.ADD_PRINT_TEXT(226,303,100,20,s10.range(74, 73));
    LODOP.ADD_PRINT_TEXT(247,303,100,20,s10.range(76, 75));
    LODOP.ADD_PRINT_TEXT(267,303,100,20,s10.range(91, 92));
    LODOP.ADD_PRINT_TEXT(184,230,50,20,s10.toFixed2(2));
    LODOP.ADD_PRINT_TEXT(204,230,50,20,s10.toFixed2(3));
    LODOP.ADD_PRINT_TEXT(225,229,50,20,s10.toFixed2(4));
    LODOP.ADD_PRINT_TEXT(246,229,50,20,s10.toFixed2(5));
    LODOP.ADD_PRINT_TEXT(266,229,50,20,s10.toFixed2(6));
    LODOP.ADD_PRINT_TEXT(338,227,50,20,s10.toFixed2(1));
    LODOP.ADD_PRINT_TEXT(363,227,50,20,s10.toFixed2(12));
    LODOP.ADD_PRINT_TEXT(387,226,50,20,s10.toFixed2(6));
    LODOP.ADD_PRINT_TEXT(414,226,50,20,s10.toFixed2(16));
    LODOP.ADD_PRINT_TEXT(443,226,50,20,s10.toFixed2(15));
    LODOP.ADD_PRINT_TEXT(339,301,100,20,s10.range(78, 77));
    LODOP.ADD_PRINT_TEXT(364,301,100,20,s10.range(80, 79));
    LODOP.ADD_PRINT_TEXT(388,300,100,20,s10.range(91, 92));
    LODOP.ADD_PRINT_TEXT(415,300,100,20,s10.range(84, 83));
    LODOP.ADD_PRINT_TEXT(444,300,100,20,s10.range(82, 81));
    LODOP.ADD_PRINT_TEXT(518,227,50,20,s10.toFixed2(18));
    LODOP.ADD_PRINT_TEXT(544,227,50,20,s10.toFixed2(19));
    LODOP.ADD_PRINT_TEXT(568,226,50,20,s10.toFixed2(20));
    LODOP.ADD_PRINT_TEXT(594,226,50,20,s10.toFixed2(21));
    LODOP.ADD_PRINT_TEXT(623,226,50,20,s10.toFixed2(22));
    LODOP.ADD_PRINT_TEXT(519,301,100,20,s10.range(130, 131));
    LODOP.ADD_PRINT_TEXT(545,301,100,20,s10.range(130, 131));
    LODOP.ADD_PRINT_TEXT(569,300,100,20,s10.range(132, 133));
    LODOP.ADD_PRINT_TEXT(595,300,100,20,s10.range(134, 135));
    LODOP.ADD_PRINT_TEXT(624,300,100,20,s10.range(134, 135));
    LODOP.ADD_PRINT_TEXT(183,169,50,20,"L");
    LODOP.ADD_PRINT_TEXT(203,169,50,20,"L");
    LODOP.ADD_PRINT_TEXT(224,169,50,20,"kg");
    LODOP.ADD_PRINT_TEXT(246,168,50,20,"kg");
    LODOP.ADD_PRINT_TEXT(267,168,50,20,"kg");
    LODOP.ADD_PRINT_TEXT(338,168,50,20,"kg");
    LODOP.ADD_PRINT_TEXT(363,168,50,20,"kg");
    LODOP.ADD_PRINT_TEXT(387,167,50,20,"kg");
    LODOP.ADD_PRINT_TEXT(414,167,50,20,"%");
    LODOP.ADD_PRINT_TEXT(443,167,50,20,"kg/m²");
    LODOP.ADD_PRINT_TEXT(519,168,50,20,"kg");
    LODOP.ADD_PRINT_TEXT(545,168,50,20,"kg");
    LODOP.ADD_PRINT_TEXT(569,167,50,20,"kg");
    LODOP.ADD_PRINT_TEXT(595,167,50,20,"kg");
    LODOP.ADD_PRINT_TEXT(624,167,50,20,"kg");
}

s10.toFixed2 = function(index){
    return parseFloat(s10.report[index].ItemValue).toFixed(2);
}

s10.range = function(min, max){
    return parseFloat(s10.report[min].ItemValue).toFixed(2)  + "~" + parseFloat(s10.report[max].ItemValue).toFixed(2);
}