
var util = {};

/*
两种调用方式
var template1="我是{0}，今年{1}了";
var template2="我是{name}，今年{age}了";
var result1=template1.format("loogn",22);
var result2=template2.format({name:"loogn",age:22});
两个结果都是"我是loogn，今年22了"
*/
String.prototype.format = function (args) {
    var result = this;
    if (arguments.length > 0) {
        if (arguments.length == 1 && typeof (args) == "object") {
            for (var key in args) {
                args[key] = args[key] ? args[key] : "";
                var reg = new RegExp("({" + key + "})", "g");
                result = result.replace(reg, args[key]);
            }
        }
        else {
            for (var i = 0; i < arguments.length; i++) {
                arguments[key] = arguments[key] ? arguments[key] : "";
                var reg = new RegExp("({)" + i + "(})", "g");
                result = result.replace(reg, arguments[i]);
            }
        }
    }
    return result;
}


/*   
jQuery实现点击复选框即高亮显示选中行 全选、反选   
*/
$(function ($) {
    $.fn.extend({
        "alterBgColor": function (options) {
            var prevselitem = null;
            //设置默认值  
            options = $.extend({
                style: "4",
                odd: "odd", /* 偶数行样式*/
                even: "even", /* 奇数行样式*/
                over: "over", /* 鼠标掠过*/
                selected: "selected" /* 选中行样式*/
            }, options);
            //交替背景  
            $("tbody>tr:odd", this).addClass(options.odd);
            $("tbody>tr:even", this).addClass(options.even);
            //鼠标移动背景  
            $("tbody>tr", this).hover(function () { $(this).addClass(options.over); }, function () { $(this).removeClass(options.over); });
            //初始背景 (判断第一列中有被选中的话就高亮)  
            $("tbody>tr td:first-child:has(:checked)", this).parents('tr').addClass(options.selected);

            //样式1  
            if (options.style == "1") {
                $("tbody>tr", this).click(function () { $(this).toggleClass(options.selected); });
            }

            //样式2  
            if (options.style == "2") {
                $('tbody>tr', this).click(function () {
                    //判断当前是否选中  
                    var hasSelected = $(this).hasClass(options.selected);
                    //如果选中，则移出selected类，否则就加上selected类  
                    $(this)[hasSelected ? "removeClass" : "addClass"](options.selected)
                        //查找内部的checkbox,设置对应的属性。  
                        .find(':checkbox:first').prop('checked', !hasSelected);
                });
            }

            //样式3  
            if (options.style == "3") {
                // 如果单选框默认情况下是选择的，则高色.  
                $('tbody>tr', this).click(function () {
                    $(this).siblings().removeClass(options.selected); //除掉同胞的样式。  
                    $(this).addClass(options.selected).find(':radio:first').prop('checked', true);
                });
            }

            //样式4  
            if (options.style == "4") {
                //第一列 为多选  
                $('tbody>tr td:first-child', this).click(function () {
                    var p = $(this).parents("tr");
                    var hasSelected = p.hasClass(options.selected);
                    p[hasSelected ? "removeClass" : "addClass"](options.selected).find(':checkbox:first').prop('checked', !hasSelected);

                });
                //其他列 为单选  
                $('tbody>tr td:not(:first-child)', this).click(function () {
                    var p = $(this).parents("tr");
                    p.addClass(options.selected).siblings().removeClass(options.selected).find(':checkbox:first').prop('checked', false);
                    p.find(':checkbox:first').prop('checked', true);
                });
            }


            //表头中的checkbox （全选 反选）  
            $("thead>tr th:first-child :checkbox:first", this).click(function () {
                //判断当前是否选中  
                var hasSelected = $(this).prop('checked');
                //如果选中，则移出selected类，否则就加上selected类  
                var tab = $(this).parents("table");
                tab.find("tbody>tr")[!hasSelected ? "removeClass" : "addClass"](options.selected);
                tab.find('tbody>tr td:first-child :checkbox').prop("checked", hasSelected ? true : false);

            });
            return this;  //返回this，使方法可链。  
        }
    });
    $(".gridtable").alterBgColor({ style: "4" }); //可以链式操作  .find("th").css("color", "red")

    $.datepicker.regional['zh-CN'] = {
        clearText: '清除',
        clearStatus: '清除已选日期',
        closeText: '关闭',
        closeStatus: '不改变当前选择',
        prevText: '< 上月',
        prevStatus: '显示上月',
        prevBigText: '<<',
        prevBigStatus: '显示上一年',
        nextText: '下月>',
        nextStatus: '显示下月',
        nextBigText: '>>',
        nextBigStatus: '显示下一年',
        currentText: '今天',
        currentStatus: '显示本月',
        monthNames: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        monthNamesShort: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        monthStatus: '选择月份',
        yearStatus: '选择年份',
        weekHeader: '周',
        weekStatus: '年内周次',
        dayNames: ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'],
        dayNamesShort: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
        dayNamesMin: ['日', '一', '二', '三', '四', '五', '六'],
        dayStatus: '设置 DD 为一周起始',
        dateStatus: '选择 m月 d日, DD',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        initStatus: '请选择日期',
        isRTL: false
    };
    $.datepicker.setDefaults($.datepicker.regional['zh-CN']);

    //Autocomplete 分类效果逻辑
    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _create: function () {
            this._super();
            this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
        },
        _renderMenu: function (ul, items) {
            var that = this,
                currentCategory = "";
            $.each(items, function (index, item) {
                var li;
                if (item.category != currentCategory) {
                    ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                    currentCategory = item.category;
                }
                li = that._renderItemData(ul, item);
                if (item.category) {
                    li.attr("aria-label", item.category + " : " + item.label);
                }
            });
        }
    });
});

//封装jquery-ui Autocomplete
util.autocomplete = function (para) {
    /*     var para = {
            id : "where_PatientName",
            url : "form_rander/query.php",
        };
    */   /*  var availableTags = [
        "ActionScript",
        "AppleScript",
        "Asp",
        "BASIC",
        "C",
        "C++",
        "Clojure",
        "COBOL",
        "ColdFusion",
        "Erlang",
        "Fortran",
        "Groovy",
        "Haskell",
        "Java",
        "JavaScript",
        "Lisp",
        "Perl",
        "PHP",
        "Python",
        "Ruby",
        "Scala",
        "Scheme"
        ]; */
    function split(val) {
        return val.split(/,\s*/);
    }
    function extractLast(term) {
        return split(term).pop();
    }

    var dataSource = para.source || function (request, response) {

        var term = extractLast(request.term);

        //是否需要从缓存取数据
        if (para.urlsource.cache) {
            if (term in para.urlsource.cachedata) {
                response(para.urlsource.cachedata[term]);
                return;
            }
        }

        var params = eval("(" + para.urlsource.requestPara.format(term) + ")");

        $.getJSON(para.urlsource.url, {
            sql: para.urlsource.sql,
            para: params
        }, function (data, status, xhr) {
            para.urlsource.cachedata[term] = data;
            response(data);
        });
    };

    var selectMethod = para.select || function (event, ui) {
        if (!para.multiple) {
            //单选
            return true;
        }
        var terms = split(this.value);
        // remove the current input
        terms.pop();
        // add the selected item
        terms.push(ui.item.value);
        // add placeholder to get the comma-and-space at the end
        terms.push("");
        this.value = terms.join(", ");
        return false;
    };

    var methodPara = {
        minLength: para.minLength,
        delay: para.delay,
        source: dataSource,
        focus: function () {
            // prevent value inserted on focus
            // 不让多选时，点选的值覆盖掉之前已选的值
            return false;
        },
        select: selectMethod,
    };

    var $input = $("#" + para.id);
    // don't navigate away from the field on tab when selecting an item
    $input.on("keydown", function (event) {
        if (event.keyCode === $.ui.keyCode.TAB &&
            $(this).autocomplete("instance").menu.active) {
            event.preventDefault();
        }
    });

    if (para.category) {
        $input.catcomplete(methodPara);
        if (para.renderitem)
            $input.catcomplete("instance")._renderItem = para.renderitem;
    } else {
        $input.autocomplete(methodPara);
        if (para.renderitem)
            $input.autocomplete("instance")._renderItem = para.renderitem;
    }

}

//前面补0
util.prefixZero = function(num, length) {
    return (Array(length).join('0') + num).slice(-length);
}

//获取时间
util.getTime = function () {
    var date = new Date();
    var year = date.getFullYear();
    var month = util.prefixZero(date.getMonth() + 1, 2);
    var day = util.prefixZero(date.getDate(), 2);
    var hour = util.prefixZero(date.getHours(), 2);
    var minute = util.prefixZero(date.getMinutes(), 2);
    var second = util.prefixZero(date.getSeconds(), 2);
    return year + '-' + month + '-' + day + ' ' + hour + ':' + minute + ':' + second;
}

//获取日期
util.getDate = function () {
    var date = new Date();
    var year = date.getFullYear();
    var month = util.prefixZero(date.getMonth() + 1, 2);
    var day = util.prefixZero(date.getDate(), 2);
    return year + '-' + month + '-' + day;
}

//将URL中的参数提取出来作为对象
util.urlToObject = function (url) {
    var urlObject = {};
    if (/\?/.test(url)) {
        var urlString = url.substring(url.indexOf("?") + 1);
        var urlArray = urlString.split("&");
        for (var i = 0, len = urlArray.length; i < len; i++) {
            var urlItem = urlArray[i];
            var item = urlItem.split("=");
            urlObject[item[0]] = item[1];
        }
        return urlObject;
    }
};

//lodop 获取客户端信息
util.getSystemInfo = function (strINFOType, callback) {
    //var LODOP = getLodop();
    if (LODOP.CVERSION) CLODOP.On_Return = function (TaskID, Value) { if (callback) callback(Value); };
    var strResult = LODOP.GET_SYSTEM_INFO(strINFOType);
    if (!LODOP.CVERSION) return strResult; else return "";
};

util.CheckLodopIsInstall = function () {
    try {
        var LODOP = getLodop();
        if (LODOP.VERSION) {
            if (LODOP.CVERSION)
                alert("当前有C-Lodop云打印可用!\n C-Lodop版本:" + LODOP.CVERSION + "(内含Lodop" + LODOP.VERSION + ")");
            else
                alert("本机已成功安装了Lodop控件！\n 版本号:" + LODOP.VERSION);
            return true;
        };
    } catch (err) {
    }

    return false;
}; 

//获取select控件的选中项
util.getSelectOptionByText = function (selector, optionText){
    var $option =$(selector + " option").map(function(){   
        if ($(this).text() == optionText) {  
            return this;  
        }  
    });  
    return $option;
}

util.getSelectOptionByValue = function (selector, optionValue){
    var $option =$(selector + " option").map(function(){   
        if ($(this).val() == optionValue) {  
            return this;  
        }  
    });  
    return $option;
}

//打印设置
util.printerSetting = {
    PrinterTypeKey: "", //存打印设置的cookie键
    PrinterType: "",
    PrinterName: "",
    Orient: 0,
    PageName: "",
    PageWidth: "",
    PageHeigth: "",
};

//引导lodop
util.PcSN = "";
util.bootstrapLodop = function(printerType, callback){
    util.printerSetting.PrinterType = printerType;

    var timer = window.setInterval(function () {
        if(document.readyState==="complete"){
            //全局变量
            LODOP = getLodop();
            if(!LODOP || !LODOP.GET_SYSTEM_INFO){
                window.clearInterval(timer);
                return;
            }

            //获取硬盘序列号
            if (!util.PcSN) {
                util.PcSN = $.cookie("PcSN");
            }

            if (!util.PcSN) {
                util.PcSN = util.getSystemInfo('DiskDrive.1.SerialNumber', function(retValue){
                    if(!util.PcSN){
                        util.PcSN = retValue;
                        $.cookie('PcSN', util.PcSN, { expires: 180, path: '/' });
                    }
                });
            }

            if(!util.PcSN){
                return;
            }
    
            window.clearInterval(timer);

            //获取打印设置
            util.printersetup(callback);
                
        }
    },500); 
}

//获取打印设置
util.printersetup = function(callback){
    var sql = "select * from printersetup where PcID = '{PcID}' and printerType=" + util.printerSetting.PrinterType;
    var sql2 = sql.format({ PcID: util.PcSN });
    $.getJSON(pageExt.libPath + "query.php", { sql: sql2 }, function (data, status, xhr) {
        if (data.length > 0) {
              
            util.printerSetting.PrinterName = data[0].PrinterName;
            util.printerSetting.Orient = data[0].Orient;
            if(data[0].PageName != "#未设置#"){
                util.printerSetting.PageName = data[0].PageName;
            }

            if(data[0].PageWidth != ""){
                util.printerSetting.PageWidth = data[0].PageWidth + "mm";
            }

            if(data[0].PageHeigth != ""){
                util.printerSetting.PageHeigth = data[0].PageHeigth + "mm";
            }
        }

        callback();
    });
}

//切换打印设置
util.printerchange = function(callback){
    util.getSelectOptionByValue("#SelPrinterSet", util.printerSetting.PrinterType).attr("selected",true);
    $( "#SelPrinterSet" ).selectmenu({
        change: function(event, ui) {
            util.printerSetting.PrinterType = ui.item.value;
            util.printersetup(callback);
        }
    });
}

util.bootstrap = function(){
    //加载打印设置下拉框
    if($("#printerSel").length > 0){
        $.ajaxSetup({
            async: false
        });    
        $("#printerSel").load(pageExt.libPath + "printerSel.php");
        $.ajaxSetup({
            async: true
        });    
    }

    if($("#SelPrinterSet").length > 0){
        if($.cookie(util.printerSetting.PrinterTypeKey)){
            util.printerSetting.PrinterType = $.cookie(util.printerSetting.PrinterTypeKey);
        }else{
            $.cookie(util.printerSetting.PrinterTypeKey, util.printerSetting.PrinterType, { expires: 180, path: '/' });
        }
    }

    util.bootstrapLodop(util.printerSetting.PrinterType, function(){
        if(util.printerSetting.PrinterName){
            $("#printerName").html(util.printerSetting.PrinterName);
        }else{
            $("#printerName").html("#未设置#");
        }

        //切换打印设置时
        if($("#SelPrinterSet").length > 0){
            util.printerchange(function(){
                if(util.printerSetting.PrinterName){
                    $("#printerName").html(util.printerSetting.PrinterName);
                }else{
                    $("#printerName").html("#未设置#");
                }
                $.cookie(util.printerSetting.PrinterTypeKey, util.printerSetting.PrinterType, { expires: 180, path: '/' });
            });    
        }
    });
}

util.initDialog = function(params){

    // dialog.dialog( "option", "title", "Dialog Title" );
    // params = {
    //     dialogID : "dialog1",
    //     context : "弹框内容！",
    //     countdown : 1000,
    //     cfg : {
    //         appendTo: "#divLabels", //弹框生成的代码追加到哪个元素的子级中，可以是div，form等，但不能是label，否则会出异常，后来理解这个属性的意义了：有时需要将表单元素放到form标签内做提交用
    //         title: "Dialog Title2",
    //         autoOpen: false, //默认关闭还是开启
    //         dialogClass: "no-close", //给dialog附加class
    //         resizable: true,
    //         closeText: "关闭",
    //         draggable: true, //是否允许移动位置
    //         //position: { my: "right bottom", at: "left+600px top+300px ", of: window  } , //dialog相对于窗口的位置：可以是'center', 'left', 'right', 'top', 'bottom',也可以是偏移量
    //         height: "auto", //auto或具体数值
    //         width: "90%",  
    //         // maxWidth: 600, 
    //         // maxHeight: 300,  
    //         // minWidth: 200, 
    //         // minHeight: 100,  
    //         modal: true,    //模态窗口        
    //         buttons: {
    //             "Delete all items": function() {
    //                 $(this).dialog( "close" );
    //             },
    //             确认: function() {
    //                 $(this).dialog( "close" );
    //             }
    //         },
    //         close: function() {
    //             //alert("colse!");
    //         },
    //         open: function() {
    //             //alert("open!");
    //         },
    //         show: {
    //             effect: "blind",
    //             duration: 200
    //         },
    //         hide: {
    //             effect: "clip",
    //             duration: 200
    //         }
    //     },
    // };

    var $dialog = $("#" + params.dialogID);
    if($dialog.length == 0){
        $dialog = $("<div id='" + params.dialogID + "'></div>").appendTo($("body"));    
        params.context && $dialog.html(params.context);    
    }

    if(!$dialog.attr("initialized")){
        //倒计时
        params.countdown && $dialog.on( "dialogopen", function( event, ui ) {
            window.setTimeout(function(){
                $dialog.dialog( "close" );
            },params.countdown); 
        } );

        params.cfg && $dialog.dialog(params.cfg);

        $dialog.attr("initialized", true);
    }

    return $dialog;
}