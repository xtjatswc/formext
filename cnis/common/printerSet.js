var printerSet = {};

$(function($){

    var timer1=window.setTimeout(function(){
        document.getElementById('T5').value = util.getSystemInfo('DiskDrive.1.SerialNumber',document.getElementById('T5'))
        printerSet.CreatePrinterList();
    },1000); 

});
    


printerSet.CreatePrinterList = function(){
    //if (document.getElementById('PrinterList').innerHTML!="") return;
    LODOP=getLodop(); 
    var iPrinterCount=LODOP.GET_PRINTER_COUNT();
    for(var i=0;i<iPrinterCount;i++){

           var option=document.createElement('option');
           option.innerHTML=LODOP.GET_PRINTER_NAME(i);
           option.value=i;
        //document.getElementById('PrinterList').appendChild(option);
        $("select").append(option);
    };	
};

printerSet.saveSetting = function(){

    $.ajaxSetup({ 
        async : false 
    });

    var sql = "delete from printersetup where PcID = '{PcID}' and PrinterType = {PrinterType};insert into printersetup(PcID, PrinterType, PrinterName) values({PcID},{PrinterType},'{PrinterName}');";

    var sql2 = sql.format({PcID:$("#T5").val(), PrinterType:1, PrinterName: $("#PrinterList1").find("option:selected").text()});

    $.post(pageExt.libPath + "exec2.php", { sql:sql2 },function(data){
        var d = data;
    },"json");

}
