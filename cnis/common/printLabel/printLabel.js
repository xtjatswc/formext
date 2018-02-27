var printLabel = {};

$(function($){

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
    

    $.getJSON(pageExt.libPath + "query.php", {sql : sql}, function( data, status, xhr ) {
        for(j = 0; j < data.length; j++) {

            $("#divLabels").append('<div class="label" id="divLabel_' + data[j].NutrientAdviceDetail_DBKEY + '"></div><br/>');
            $("#divLabel_" + data[j].NutrientAdviceDetail_DBKEY).load("singleLabel.php?v=" + Math.random() + "&detailDBKeys=" + data[j].NutrientAdviceDetail_DBKEY);

        } 
    });
});

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
        printLabel.printInit(this.innerHTML);
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

printLabel.printInit = function(labelInfo){

    LODOP.PRINT_INITA(0,0,"90.01mm","50.01mm","标签打印");
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

