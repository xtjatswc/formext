var printerSet = {};

$(function($){

    var timer1=window.setTimeout(function(){
        var PcSN = $.cookie("PcSN");
        if(PcSN){
            document.getElementById('T5').value = PcSN;
        }else{
            document.getElementById('T5').value = util.getSystemInfo('DiskDrive.1.SerialNumber',document.getElementById('T5'));
        }

        printerSet.CreatePrinterList();

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
                } 
            });   

        },500); 

    },500); 

});
    


printerSet.CreatePrinterList = function(){
    //if (document.getElementById('PrinterList').innerHTML!="") return;
    LODOP=getLodop(); 
    var iPrinterCount=LODOP.GET_PRINTER_COUNT();
    $("select").append("<option value='-1'>#未设置#</option>");
    for(var i=0;i<iPrinterCount;i++){

           var option=document.createElement('option');
           option.innerHTML=LODOP.GET_PRINTER_NAME(i);
           option.value=i;
        //document.getElementById('PrinterList').appendChild(option);
        $("select").append(option);
    };	
};

printerSet.saveSetting = function(){

    printerSet.singleSave(1);
    printerSet.singleSave(2);

    alert("保存成功！");
}

printerSet.singleSave = function(type){
    var sql = "delete from printersetup where PcID = '{PcID}' and PrinterType = {PrinterType};insert into printersetup(PcID, PrinterType, PrinterName) values({PcID},{PrinterType},'{PrinterName}');";

    var sql2 = sql.format({PcID:$("#T5").val(), PrinterType:type, PrinterName: $("#PrinterList" + type).find("option:selected").text()});

    $.post(pageExt.libPath + "exec2.php", { sql:sql2 },function(data){
        var d = data;
    },"json");

}