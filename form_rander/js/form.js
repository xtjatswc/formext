
var formExt = {};

$(function($){
    formExt.pageIndexCtrl = $("#hidPageIndex")[0];

    $("td").focusin(function() {
        //console.log("focusin：" + $(this).html());

        $(this).children("p").removeClass("breviary");

    });

    $("td").focusout(function() {
        $td = $(this);
        //console.log("focusout：" + $td.html());
        $p = $td.children("p[title]");
        $p.addClass("breviary");
        $p.prop("title", $td.text());

        //保存
        var sql = $td.attr("editSql");
        if(!sql) return;

        //取值
        var newValue = "";
        if($p.length == 1){
            newValue = $p.html();
        }else{
            newValue = $td.html();
        }


        sql = sql.format(newValue);
        $.post("form_rander/exec.php", { sql:sql },function(data){
            console.log(data);
        });        
    });

});

//第一页
formExt.doFristPage = function(){
    this.pageIndexCtrl.value = 0;
}

//上一页
formExt.doPreviousPage = function(){
    var pageIndex = parseInt(this.pageIndexCtrl.value);
    if(pageIndex > 0){
        pageIndex = pageIndex - 1;
    }
    this.pageIndexCtrl.value = pageIndex;
}

//下一页
formExt.doNextPage = function(){
    var pageIndex = parseInt(this.pageIndexCtrl.value);
    pageIndex = pageIndex + 1;
    this.pageIndexCtrl.value = pageIndex;
}

//打印
formExt.doPrint = function(){
    window.print();
}

//导出excel
formExt.exportExcel = function(){
    $("#mainGridTable").table2excel({
        //exclude: ".noExl",
        name: document.title,
        filename: document.title + "_" + formExt.getTime(),
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true
    });
}

//获取时间
formExt.getTime = function(){
    var date = new Date();
    var year = date.getFullYear();
    var month = date.getMonth()+1;
    var day = date.getDate();
    var hour = date.getHours();
    var minute = date.getMinutes();
    var second = date.getSeconds();
    return year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
}

//删除选中记录
formExt.deleteRecords = function(){
    var $records = $("#mainGridTable> tbody>tr td:first-child :checkbox:checked");
    var str="";
    $records.each(function(){
        str += $(this).val() + ",";
    });
    str = str.substring(0, str.length - 1);

    if(str == ""){
        alert("请选中要删除的记录！")
        return;
    }

    if(!confirm("确定删除选中的" + $records.length + "条记录吗？")){
        return;
    }

    formExt.deleteSql = formExt.deleteSql.format(str);

    $.post("form_rander/exec.php", { sql:formExt.deleteSql },function(data){
        $records.parents("tr").remove();
    });
}
