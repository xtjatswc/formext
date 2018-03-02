var printerSet = {};

$(function($){

    var timer1=window.setTimeout(function(){
        var PcSN = $.cookie("PcSN");
        if(PcSN){
            document.getElementById('T5').value = PcSN;
        }else{
            document.getElementById('T5').value = util.getSystemInfo('DiskDrive.1.SerialNumber',document.getElementById('T5'));
        }

        //打印机列表
        printerSet.CreatePrinterList();
        //打印方向列表
        $(".Orient").append("<option value='0'>0---方向不定，由操作者自行选择或按打印机缺省设置</option><option value='1'>1---纵向打印，固定纸张；</option><option value='2'>2---横向打印，固定纸张；</option><option value='3'>3---纵向打印，宽度固定，高度按打印内容的高度自适应</option>")

        var timer1=window.setTimeout(function(){
            if(!$.cookie("PcSN") && $("#T5").val() != ""){
                $.cookie('PcSN', $("#T5").val(), { expires: 180, path: '/' });
            }

            //回显
            var sql = "select * from printersetup where PcID = '{PcID}'";
            var sql2 = sql.format({PcID:$("#T5").val()});
            $.getJSON(pageExt.libPath + "query.php", {sql : sql2}, function( data, status, xhr ) {
                for(j = 0; j < data.length; j++) {        
                    $("#PrinterList" + data[j].PrinterType).find("option:contains('"+ data[j].PrinterName +"')").attr("selected",true);  
                    //设置纸张
                    printerSet.CreatePagSizeList(j + 1);
                } 
            });   

        },500); 

    },500); 

    $(".PrinterList").change(function(){
        printerSet.CreatePagSizeList($(this).attr("index"));
        $(this).css("background-color","#FFFFCC");
    });

});
    


printerSet.CreatePrinterList = function(){
    //if (document.getElementById('PrinterList').innerHTML!="") return;
    LODOP=getLodop(); 
    var iPrinterCount=LODOP.GET_PRINTER_COUNT();
    $(".PrinterList").append("<option value='-1'>#未设置#</option>");
    for(var i=0;i<iPrinterCount;i++){

           var option=document.createElement('option');
           option.innerHTML=LODOP.GET_PRINTER_NAME(i);
           option.value=i;
        //document.getElementById('PrinterList').appendChild(option);
        $(".PrinterList").append(option);
    };	
};

printerSet.CreatePagSizeList = function(index){
    LODOP=getLodop(); 
    //clearPageListChild();
    $("#PagSizeList" + index).empty();
    var strPageSizeList=LODOP.GET_PAGESIZES_LIST($("#PrinterList" + index).val(),"\n");
    var Options=new Array(); 
     Options=strPageSizeList.split("\n");   
     $('#PagSizeList' + index).append("<option value='-1'>#未设置#</option>");    
    for (i in Options)    
    {    
      var option=document.createElement('option');   
      option.innerHTML=Options[i];
      option.value=Options[i];
        //document.getElementById('PagSizeList' + index).appendChild(option);
        $('#PagSizeList' + index).append(option);

    }  
 }	

printerSet.saveSetting = function(){

    printerSet.singleSave(1);
    printerSet.singleSave(2);

    alert("保存成功！");
}

printerSet.singleSave = function(type){
    var sql = "insert into printersetup(PcID, PrinterType, PrinterName, Orient, PageName, PageWidth, PageHeigth) values('{PcID}',{PrinterType},'{PrinterName}', {Orient}, '{PageName}', '{PageWidth}', '{PageHeigth}') ON DUPLICATE KEY UPDATE PrinterName=VALUES(PrinterName),Orient=VALUES(Orient),PageName=VALUES(PageName),PageWidth=VALUES(PageWidth),PageHeigth=VALUES(PageHeigth);";

    var sql2 = sql.format({PcID:$("#T5").val(), PrinterType:type, PrinterName: $("#PrinterList" + type).find("option:selected").text(), Orient : $("#Orient" + type).val(), PageName:$("#PagSizeList" + type).find("option:selected").text(), PageWidth:$("#Width" + type).val(),PageHeigth:$("#Heigth" + type).val()});

    $.post(pageExt.libPath + "exec2.php", { sql:sql2 },function(data){
        var d = data;
    },"json");

}

