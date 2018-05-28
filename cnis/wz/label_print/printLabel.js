var printLabel = {};

//页面是否加载完毕
printLabel.isDomReady = false;



$(function ($) {

    util.bootstrapLodop(1, function(){

        if(util.printerSetting.PrinterName){
            $("#printerName").html(util.printerSetting.PrinterName);
        }else{
            $("#printerName").html("#未设置#");
        }
                
    });

    var urlParams = util.urlToObject(window.location.search);

    var sql = "SELECT \
	tb.*, GROUP_CONCAT(NutrientAdviceDetail_DBKEY) NutrientAdviceDetail_DBKEY, \
	GROUP_CONCAT( \
		NutrientAdviceDetail_DBKEY SEPARATOR '_' \
	) NutrientAdviceDetail_DBKEY2 \
FROM \
	( \
		SELECT \
			c.NutrientAdviceSummary_DBKey, \
			b.AdviceDate, \
			a.TakeOrder, \
			a.PreparationMode, \
			CASE \
		WHEN a.PreparationMode IN (2, 7) THEN \
			a.PreparationMode \
		ELSE \
			a.NutrientAdviceDetail_DBKEY \
		END PreparationMode2, \
		a.NutrientAdviceDetail_DBKEY \
	FROM \
		nutrientadvicedetail a \
	INNER JOIN nutrientadvice b ON a.NutrientAdvice_DBKey = b.NutrientAdvice_DBKey \
	INNER JOIN nutrientadvicesummary c ON b.NutrientAdviceSummary_DBKey = c.NutrientAdviceSummary_DBKey \
	WHERE \
		a.NutrientAdviceDetail_DBKEY IN (" + urlParams.detailDBKeys + ") \
	) tb \
GROUP BY \
	NutrientAdviceSummary_DBKey, \
	AdviceDate, \
	TakeOrder, \
    PreparationMode2";        

    var labelCount = -1;
    $.getJSON(pageExt.libPath + "query.php", { sql: sql }, function (data, status, xhr) {
        labelCount = data.length;
        for (j = 0; j < data.length; j++) {

            $("#labelTip").html("正在加载标签内容，请稍候……");
            $("#divLabels").append('<div class="label" id="divLabel_' + data[j].NutrientAdviceDetail_DBKEY2 + '"><div class="unload"></div></div><br/>');
            $("#divLabel_" + data[j].NutrientAdviceDetail_DBKEY2).load("singleLabel.php?v=" + Math.random() + "&detailDBKeys=" + data[j].NutrientAdviceDetail_DBKEY);

        }
    });

    //检查dom是否加载完毕
    var timer3 = window.setInterval(function () {
        $labels = $(".labelContent");
        if ($labels.length == labelCount && $("#printerName").html() != "") {
            //alert("加载完毕");
            printLabel.isDomReady = true;
            window.clearInterval(timer3);

            $("#labelTip").html("");

            // if ($("#printerName").html() == "#未设置#") {
            //     if (confirm("未设置打印机，是否输出到默认打印机？")) {
            //         $("#btnPrint").click();
            //     }
            // } else {
            //     $("#btnPrint").click();
            // }

            $("td").prop("contentEditable", true);
        }
    }, 500);


});

printLabel.printSetting = function () {
    window.open("../../../../formext/form_rander/printerSet.php");
}

printLabel.printDesign = function () {
    printLabel.printLoad(1);
}

printLabel.printSetup = function () {
    printLabel.printLoad(2);
}

printLabel.preview = function () {
    printLabel.printLoad(3);
}

printLabel.print = function () {

    if ($("#printerName").html() == "#未设置#") {
        if (!confirm("未设置打印机，是否输出到默认打印机？")) {
            return;
        }
    } 

    if (!printLabel.isDomReady) {
        alert("页面没加载完，请重试！");
        return;
    }

    printLabel.printLoad(4);
    alert("请等待打印完毕后，再关闭该页面！");
}

printLabel.printLoad = function (flag) {
    // LODOP = getLodop();

    var $labels = $("#divLabels").children(".label");
    $labels.each(function (e) {
        printLabel.createPrintPage(this.innerHTML);
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
    });
}

printLabel.createPrintPage = function (labelInfo) {

    LODOP.PRINT_INITA(0, 0, "180mm", "100mm", "标签打印");
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
    LODOP.SET_PRINT_MODE("POS_BASEON_PAPER", false);
    var strStyle = document.getElementById("cssPrint").outerHTML;//"<style> table,td,th {border-width: 1px;border-style: solid;border-collapse: collapse}</style>"

    LODOP.ADD_PRINT_HTM("1.01mm", "1.01mm", "85mm", "91mm", strStyle + labelInfo);
    // LODOP.SET_PRINT_STYLEA(0,"Horient",3); //别水平拉伸，不然死活看不见页脚
    LODOP.SET_PRINT_STYLEA(0, "Vorient", 3);

    //LODOP.ADD_PRINT_TABLE("1.59mm","1.01mm","85.01mm","28mm",strStyle + document.getElementById("tblNutrientadvicedetail").outerHTML);
    //LODOP.SET_PRINT_STYLEA(0,"Vorient",3);
    //LODOP.SET_PRINT_STYLEA(0,"Offset2Top","-10mm"); //设置次页偏移把区域向上扩
    //LODOP.SET_PRINT_STYLEA(0,"LinkedItem",-1);

    // LODOP.ADD_PRINT_TEXT("95mm", "1.32mm", "21.17mm", "5.29mm", "第#页/共&页");
    // LODOP.SET_PRINT_STYLEA(0, "Vorient", 1);
    // LODOP.SET_PRINT_STYLEA(0, "ItemType", 2);
    // LODOP.SET_PRINT_STYLEA(0, "LinkedItem", -1);

    LODOP.SET_PRINT_STYLE("FontSize",12);

    LODOP.ADD_PRINT_TEXT("95mm", "1.22mm", "47.1mm", "5.29mm", "制作日期：" + util.getDate());
    LODOP.SET_PRINT_STYLEA(0, "Vorient", 1);
    LODOP.SET_PRINT_STYLEA(0, "ItemType", 1);
    LODOP.SET_PRINT_STYLEA(0, "LinkedItem", -2);

    LODOP.ADD_PRINT_TEXT("95mm","53mm","35.45mm", "5.29mm","签名：");
    LODOP.SET_PRINT_STYLEA(0,"Vorient",1);
    LODOP.SET_PRINT_STYLEA(0, "ItemType", 1);
    LODOP.SET_PRINT_STYLEA(0, "LinkedItem", -3);

    LODOP.SET_SHOW_MODE("SHOW_SCALEBAR",true);//语句控制显示标尺
    LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED", 1);//横向时的正向显示

}

