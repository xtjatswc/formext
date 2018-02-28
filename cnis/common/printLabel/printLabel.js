var printLabel = {};

$(function($){
    
    var timer1=window.setTimeout(function(){
        document.getElementById('PcSN').value = util.getSystemInfo('DiskDrive.1.SerialNumber',document.getElementById('PcSN'));

        var timer2=window.setTimeout(function(){

            //回显
            var sql = "select * from printersetup where PcID = '{PcID}' and printerType=1";
            var sql2 = sql.format({PcID:$("#PcSN").val()});
            $.getJSON(pageExt.libPath + "query.php", {sql : sql2}, function( data, status, xhr ) {
                if(data.length == 0){
                    $("#printerName").html("#未设置#");
                }else{
                    $("#printerName").html(data[0].PrinterName);
                }
            });   

        },500); 

    },500); 

    var urlParams = util.urlToObject(window.location.search);

    var sql = "SELECT \
	c.NutrientAdviceSummary_DBKey, \
	b.AdviceDate, \
	a.TakeOrder, \
	a.PreparationMode, \
	a.NutrientAdviceDetail_DBKEY \
FROM \
	nutrientadvicedetail a \
INNER JOIN nutrientadvice b ON a.NutrientAdvice_DBKey = b.NutrientAdvice_DBKey \
INNER JOIN nutrientadvicesummary c ON b.NutrientAdviceSummary_DBKey = c.NutrientAdviceSummary_DBKey \
WHERE \
    a.NutrientAdviceDetail_DBKEY IN (" + urlParams.detailDBKeys + ");";
    

    var labelCount = -1;
    $.getJSON(pageExt.libPath + "query.php", {sql : sql}, function( data, status, xhr ) {
        labelCount = data.length;
        for(j = 0; j < data.length; j++) {

            $("#divLabels").append('<div class="label" id="divLabel_' + data[j].NutrientAdviceDetail_DBKEY + '"><div class="unload"></div></div><br/>');
            $("#divLabel_" + data[j].NutrientAdviceDetail_DBKEY).load("singleLabel.php?v=" + Math.random() + "&detailDBKeys=" + data[j].NutrientAdviceDetail_DBKEY);

        } 
    });

    //检查dom是否加载完毕
    var timer3=window.setInterval(function(){
        $labels = $(".labelContent");
        if($labels.length == labelCount && $("#printerName").html() != ""){
            //alert("加载完毕");
            window.clearInterval(timer3);

            if($("#printerName").html() == "#未设置#"){
                if(confirm("未设置打印机，是否输出到默认打印机？")){
                    $("#btnPrint").click();
                }
            }else{
                $("#btnPrint").click();
            }
        
        }              
    },1000); 
    

});

printLabel.printSetting = function () {
    window.open("../printerSet.php");
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
    printLabel.printLoad(4);
}

printLabel.printLoad = function (flag) {
    LODOP = getLodop();

    var $labels = $("#divLabels").children(".label");
    $labels.each(function(e){  
        printLabel.createPrintPage(this.innerHTML);
        if(flag == 1){
            LODOP.PRINT_DESIGN();
            return false;
        }else if(flag == 2){
            LODOP.PRINT_SETUP();
            return false;
        }else if(flag == 3){
            LODOP.PREVIEW();
            return false;
        }else if(flag == 4){
            LODOP.PRINT(); 
        }           
    });
}

printLabel.createPrintPage = function(labelInfo){

    LODOP.PRINT_INITA(0,0,"90.01mm","50.01mm","标签打印");
    //LODOP.SET_PRINTER_INDEX(getSelectedPrintIndex());    
    if($("#printerName").html() == "#未设置#"){
        $("#lsMsg").html("尚未设置默认的标签打印机！");
    }else{
        if (!LODOP.SET_PRINTER_INDEXA($("#printerName").html())){
            $("#lsMsg").html("未检测到该打印机，将输出到默认打印机！");
        }
    }


    //LODOP.SET_PRINT_PAGESIZE(0,0,0,getSelectedPageSize());
    LODOP.SET_PRINT_PAGESIZE(2,900,500,"");
    var strStyle=  document.getElementById("cssPrint").outerHTML;//"<style> table,td,th {border-width: 1px;border-style: solid;border-collapse: collapse}</style>"

    LODOP.ADD_PRINT_HTM("1.01mm","1.01mm","82.01mm","42.49mm",strStyle + labelInfo);
    LODOP.SET_PRINT_STYLEA(0,"Vorient",3);

    //LODOP.ADD_PRINT_TABLE("1.59mm","1.01mm","85.01mm","28mm",strStyle + document.getElementById("tblNutrientadvicedetail").outerHTML);
    //LODOP.SET_PRINT_STYLEA(0,"Vorient",3);
    //LODOP.SET_PRINT_STYLEA(0,"Offset2Top","-10mm"); //设置次页偏移把区域向上扩
    //LODOP.SET_PRINT_STYLEA(0,"LinkedItem",-1);

    LODOP.ADD_PRINT_TEXT("44.24mm","1.32mm","21.17mm","5.29mm","第#页/共&页");
    LODOP.SET_PRINT_STYLEA(0,"Vorient",1);
    LODOP.SET_PRINT_STYLEA(0,"ItemType",2);
    LODOP.SET_PRINT_STYLEA(0,"LinkedItem",-1);

    LODOP.ADD_PRINT_TEXT("44.24mm","23.97mm","35.45mm","5.29mm","制作日期：" + util.getDate());
    LODOP.SET_PRINT_STYLEA(0,"Vorient",1);
    LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
    LODOP.SET_PRINT_STYLEA(0,"LinkedItem",-2);

    LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);//横向时的正向显示

}

