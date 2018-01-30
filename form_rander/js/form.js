
var formExt = {};
formExt.sqlCfg = {};

$(function($){

    //jquery-ui tooltip
    $( document ).tooltip();    

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
        var editSqlKey = $td.attr("editSqlKey");
        var sql = formExt.sqlCfg[editSqlKey]; 
        if(!sql) return;
        sql = sql.format({"columnName" : $td.attr("columnName")});

        //取值
        var para = {":value" : $td.text()};

        //取其它列值
        var editKey = $td.attr("editKey");
        if(editKey){
            var arr = editKey.split(",");
            for (let index = 0; index < arr.length; index++) {
                var key = arr[index];
                var theValue = $td.parent().children("[columnName='"+ key +"']").text();
                para[":" + key] = theValue;
            }
        }

        $.post("form_rander/exec.php", { sql:sql, para:para },function(data){
            if(data.success){
                console.log(data.msg + data.affectedCount);
            }else{
                console.log("保存失败！");
                console.log(sql);
                console.log(para);
                console.log(data.msg);
            }
        }, "json");        
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
        filename: document.title + "_" + util.getTime(),
        fileext: ".xls",
        exclude_img: true,
        exclude_links: true,
        exclude_inputs: true
    });
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

    formExt.deleteSql = formExt.sqlCfg["deleteSql"].format(str);

    $.post("form_rander/exec.php", { sql:formExt.deleteSql },function(data){
        if(data.success){
            console.log(data.msg + data.affectedCount);
            $records.parents("tr").remove();
        }else{
            console.log("删除失败！");
            console.log(formExt.sqlCfg["deleteSql"]);
            console.log(data.msg);
        }
    },"json");
}

