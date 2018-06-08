var inbody770 = {};
inbody770.urlParams = util.urlToObject(window.location.search);
inbody770.patient = null;
inbody770.report = null;

$(function ($) {

    util.bootstrapLodop(3, function(){

        if(util.printerSetting.PrinterName){
            $("#printerName").html(util.printerSetting.PrinterName);
        }else{
            $("#printerName").html("#未设置#");
        }

        var timer1 = window.setInterval(function () {
            if(inbody770.patient && inbody770.report){

                window.clearInterval(timer1);
                if ($("#printerName").html() == "#未设置#") {
                    if (confirm("未设置打印机，是否输出到默认打印机？")) {
                        $("#preview").click();
                    }
                } else {
                    $("#preview").click();
                }    
            }
        }, 500);

    });

    if(!inbody770.urlParams || typeof(inbody770.urlParams.reportId) == "undefined"){
        alert("未获取到报告ID！");
        return;
    }

    var sql = "select a.*,b.Height,b.HospitalizationNumber,date_format(c.DateOfBirth,'%Y-%m-%d') DateOfBirth from inbodyreport a inner join patienthospitalizebasicinfo b on a.PatientHospitalize_DBKey = b.PatientHospitalize_DBKey inner join patientbasicinfo c on c.PATIENT_DBKEY = b.PATIENT_DBKEY where  a.InBodyReport_DBKey = " + inbody770.urlParams.reportId + ";";
    $.getJSON(pageExt.libPath + "query.php", { sql: sql }, function (data, status, xhr) {
        inbody770.patient = data[0];
    });

    sql = "select a.*,b.ItemName from inbodyresult a inner join inbodyitem b on a.ItemCode = b.ItemCode and b.InbodyModel = '770' where InBodyReport_DBKey = " + inbody770.urlParams.reportId + ";";
    $.getJSON(pageExt.libPath + "query.php", { sql: sql }, function (data, status, xhr) {
        inbody770.report = data;
    });


});
    

inbody770.printSetting = function () {
    window.open(pageExt.libPath + "printerSet.php");
}

inbody770.printDesign = function () {
    inbody770.printLoad(1);
}

inbody770.printSetup = function () {
    inbody770.printLoad(2);
}

inbody770.preview = function () {
    inbody770.printLoad(3);
}

inbody770.print = function () {

    inbody770.printLoad(4);
    // alert("请等待打印完毕后，再关闭该页面！");
}

inbody770.printLoad = function (flag) {
    // LODOP = getLodop();

    inbody770.createPrintPage();
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

inbody770.createPrintPage = function () {

    LODOP.PRINT_INITA(0, 0, "210mm", "297mm", "InBody770报告纸打印");
    if (util.printerSetting.PrinterName == "#未设置#") {
        $("#lsMsg").html("尚未设置默认的人体成分报告打印机！");
    } else {
        if (!LODOP.SET_PRINTER_INDEXA(util.printerSetting.PrinterName)) {
            $("#lsMsg").html("未检测到该打印机，将输出到默认打印机！");
        }
    }

    //LODOP.SET_PRINT_PAGESIZE(0,0,0,getSelectedPageSize());
    LODOP.SET_PRINT_PAGESIZE(util.printerSetting.Orient, util.printerSetting.PageWidth, util.printerSetting.PageHeigth, util.printerSetting.PageName);

    LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='../通用模板.jpg?v=3'>");
    LODOP.SET_SHOW_MODE("BKIMG_WIDTH","210mm");
    LODOP.SET_SHOW_MODE("BKIMG_HEIGHT","297mm");
    LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",true);
    LODOP.SET_SHOW_MODE("BKIMG_PRINT",true);    
    LODOP.SET_SHOW_MODE("SHOW_SCALEBAR",true);  //语句控制显示标尺
    LODOP.SET_SHOW_MODE("PREVIEW_NO_MINIMIZE",true);    //设置预览窗口禁止最小化，并始终在各个窗口的最前面        
    LODOP.SET_PRINT_MODE("POS_BASEON_PAPER", true); //是否控制位置基点，true时，对套打有利        
    LODOP.SET_PRINT_MODE("RESELECT_PRINTER",true); //是否可以重新选择打印机
    
    LODOP.SET_PRINT_STYLE("FontName", "微软雅黑");
    LODOP.SET_PRINT_STYLE("FontSize", "10.5");



    LODOP.ADD_PRINT_TEXT(100,64,100,20,inbody770.patient.HospitalizationNumber); //ID
    LODOP.ADD_PRINT_TEXT(119,64,100,20,"(" + inbody770.patient.PatientName + ")"); //姓名
    LODOP.ADD_PRINT_TEXT(110,193,100,20,inbody770.report[4].ItemValue + "cm"); //身高
    LODOP.ADD_PRINT_TEXT(107,269,100,20,inbody770.report[5].ItemValue); //年龄
    LODOP.ADD_PRINT_TEXT(122,268,100,20,"(" + inbody770.patient.DateOfBirth + ")"); //生日
    LODOP.ADD_PRINT_TEXT(108,337,100,20,inbody770.patient.Gender == "M" ? "男" : "女"); //性别
    LODOP.ADD_PRINT_TEXT(112,374,166,20,inbody770.patient.TestTime); //测试时间
    LODOP.ADD_PRINT_HTML(35,526,254,75,$("#reportTitle")[0].outerHTML); //Title
    LODOP.ADD_PRINT_TEXT(176,154,150,20,inbody770.report[19].ItemValue); //身体总水分 测量值
    LODOP.ADD_PRINT_TEXT(189,130,150,20,"(" + inbody770.report[174].ItemValue + "~" + inbody770.report[175].ItemValue + ")"); //身体总水分 范围
    LODOP.ADD_PRINT_TEXT(181,221,150,20,inbody770.report[19].ItemValue); //身体总水分
    LODOP.ADD_PRINT_TEXT(203,154,150,20,inbody770.report[16].ItemValue); //蛋白质
    LODOP.ADD_PRINT_TEXT(216,130,150,20,"(" + inbody770.report[149].ItemValue + "~" + inbody770.report[148].ItemValue + ")"); //蛋白质 范围
    LODOP.ADD_PRINT_TEXT(229,154,150,20,inbody770.report[17].ItemValue); //无机盐
    LODOP.ADD_PRINT_TEXT(242,130,150,20,"(" + inbody770.report[151].ItemValue + "~" + inbody770.report[150].ItemValue + ")"); //无机盐 范围
    LODOP.ADD_PRINT_TEXT(255,154,150,20,inbody770.report[18].ItemValue); //体脂肪
    LODOP.ADD_PRINT_TEXT(268,130,150,20,"(" + inbody770.report[170].ItemValue + "~" + inbody770.report[171].ItemValue + ")"); //体脂肪 范围
    LODOP.ADD_PRINT_TEXT(189,300,150,20,inbody770.report[22].ItemValue); //肌肉量
    LODOP.ADD_PRINT_TEXT(202,276,150,20,"(" + inbody770.report[178].ItemValue + "~" + inbody770.report[179].ItemValue + ")"); //肌肉量 范围
    LODOP.ADD_PRINT_TEXT(209,375,150,20,inbody770.report[20].ItemValue); //去脂体重
    LODOP.ADD_PRINT_TEXT(222,351,150,20,"(" + inbody770.report[176].ItemValue + "~" + inbody770.report[177].ItemValue + ")"); //去脂体重 范围
    LODOP.ADD_PRINT_TEXT(229,446,150,20,inbody770.report[6].ItemValue); //体重
    LODOP.ADD_PRINT_TEXT(242,422,150,20,"(" + inbody770.report[155].ItemValue + "~" + inbody770.report[154].ItemValue + ")"); //体重 范围

    var leftMargin = 145;
    //肌肉脂肪分析
    var w = inbody770.rangeWidth2(67, 85, 115);
    LODOP.ADD_PRINT_SHAPE(4,344,132,w,12,0,1,"#808080"); //体重 %
    LODOP.ADD_PRINT_TEXT(344,leftMargin + w,150,20,inbody770.report[6].ItemValue); //体重 kg    

    w = inbody770.rangeWidth2(62, 90, 110);
    LODOP.ADD_PRINT_SHAPE(4,376,132,w,12,0,1,"#808080"); //骨骼肌 %
    LODOP.ADD_PRINT_TEXT(376,leftMargin + w,150,20,inbody770.report[21].ItemValue); //骨骼肌 kg    

    w = inbody770.rangeWidth2(61, 80, 160);
    LODOP.ADD_PRINT_SHAPE(4,407,132,w,12,0,1,"#808080"); //体脂肪 %
    LODOP.ADD_PRINT_TEXT(407,leftMargin + w,150,20,inbody770.report[18].ItemValue); //体脂肪 kg    

    //肥胖分析
    w = inbody770.rangeWidth2(23, 18.5, 23.9);
    LODOP.ADD_PRINT_SHAPE(4,491,132,w,12,0,1,"#808080"); //身体质量指数
    LODOP.ADD_PRINT_TEXT(491,leftMargin + w,150,20,inbody770.report[23].ItemValue); //身体质量指数 值

    w = inbody770.rangeWidth2(24, 10, 20);
    LODOP.ADD_PRINT_SHAPE(4,522,132,w,12,0,1,"#808080"); //体脂百分比
    LODOP.ADD_PRINT_TEXT(522,leftMargin + w,150,20,inbody770.report[24].ItemValue); //体脂百分比 值

    //肌肉均衡
    w = inbody770.rangeWidth2(73, 85, 115);
    LODOP.ADD_PRINT_SHAPE(4,611,132,w,12,0,1,"#808080"); //右上肢 %
    LODOP.ADD_PRINT_TEXT(611,leftMargin + w,150,20,inbody770.report[73].ItemValue); //右上肢 值

    w = inbody770.rangeWidth2(74, 85, 115);
    LODOP.ADD_PRINT_SHAPE(4,646,132,w,12,0,1,"#808080"); //左上肢 %
    LODOP.ADD_PRINT_TEXT(646,leftMargin + w,150,20,inbody770.report[74].ItemValue); //左上肢 值

    w = inbody770.rangeWidth2(75, 90, 110);
    LODOP.ADD_PRINT_SHAPE(4,683,132,w,12,0,1,"#808080"); //躯干 %
    LODOP.ADD_PRINT_TEXT(683,leftMargin + w,150,20,inbody770.report[75].ItemValue); //躯干 值

    w = inbody770.rangeWidth2(76, 90, 110);
    LODOP.ADD_PRINT_SHAPE(4,718,132,w,12,0,1,"#808080"); //右下肢 %
    LODOP.ADD_PRINT_TEXT(718,leftMargin + w,150,20,inbody770.report[76].ItemValue); //右下肢 值

    w = inbody770.rangeWidth2(77, 90, 110);
    LODOP.ADD_PRINT_SHAPE(4,754,132,w,12,0,1,"#808080"); //左下肢 %
    LODOP.ADD_PRINT_TEXT(754,leftMargin + w,150,20,inbody770.report[77].ItemValue); //左下肢 值

    //细胞外水分比率分析
    w = inbody770.rangeWidth2(103, 0.36, 0.39);
    LODOP.ADD_PRINT_SHAPE(4,842,132,w,12,0,1,"#808080"); //细胞外水分比率分析 线
    LODOP.ADD_PRINT_TEXT(842,leftMargin + w,150,20,inbody770.report[103].ItemValue); //细胞外水分比率分析 值

    //历史折线图
    inbody770.loadChart();

    //右边栏
    LODOP.ADD_PRINT_TEXT(197,585,100,20,inbody770.patient.HealthScore); //分值
    LODOP.SET_PRINT_STYLEA(0,"FontSize",18.5);

    //内脏脂肪面积
    var vfa = inbody770.report[30].ItemValue;
    var age = inbody770.report[5].ItemValue;
    var xZero = 546;
    var x100 = 721;
    var yZero = 386;
    var y200 = 276;
    var x = xZero + (x100 - xZero) / 100 * age;
    var y = yZero + (y200 - yZero) / 200 * vfa;
    LODOP.ADD_PRINT_TEXT(y,x,100,20,"+"); 
    LODOP.SET_PRINT_STYLEA(0,"FontSize",18.5);

    //体型
    var bmi = inbody770.report[23].ItemValue;
    var PBF = inbody770.report[24].ItemValue;
    var x10 = 590;
    var x20 = 696;
    var y185 = 604;
    var y239 = 497;
    var xx = (x20 - x10) / 10; //每个刻度的长度
    var yy = (y239 - y185) / 5.4; //每个刻度的长度
    x = (x10 - 10 * xx) + xx * PBF;
    y = (y185 - 18.5 * yy) + yy * bmi;
    LODOP.ADD_PRINT_TEXT(y,x,100,20,"+"); 
    LODOP.SET_PRINT_STYLEA(0,"FontSize",23.5);

    //体重控制
    LODOP.ADD_PRINT_TEXT(720,621,100,20,inbody770.report[204].ItemValue + " kg"); //目标体重
    LODOP.ADD_PRINT_TEXT(740,621,100,20,inbody770.report[205].ItemValue + " kg"); //体重控制
    LODOP.ADD_PRINT_TEXT(760,621,100,20,inbody770.report[206].ItemValue + " kg"); //脂肪控制
    LODOP.ADD_PRINT_TEXT(780,621,100,20,inbody770.report[207].ItemValue + " kg"); //肌肉控制

    //研究项目
    LODOP.ADD_PRINT_TEXT(819,621,100,20,inbody770.report[28].ItemValue + " kcal"); //基础代谢率
    LODOP.ADD_PRINT_TEXT(839,621,142,20,inbody770.report[25].ItemValue + " （" + inbody770.report[163].ItemValue + "~" + inbody770.report[162].ItemValue + ")"); //腰臀比
    LODOP.ADD_PRINT_TEXT(859,621,100,20,inbody770.report[30].ItemValue + " c㎡"); //内脏脂肪面积
    var itemValue = inbody770.report[20].ItemValue / Math.pow(inbody770.report[4].ItemValue / 100,2)
    LODOP.ADD_PRINT_TEXT(879,621,100,20,itemValue.toFixed(2) + " kg/㎡"); //去脂体重指数 去脂体重/身高平方

    //全身相位角
    LODOP.ADD_PRINT_TEXT(914,647,100,20,inbody770.report[487].ItemValue + " °"); 
    LODOP.SET_PRINT_STYLEA(0,"FontSize",15.5);

    var dzk = inbody770.loadDZk();
    LODOP.ADD_PRINT_HTM("253mm","138mm","64mm","43mm", dzk);
}

inbody770.toFixed2 = function(index){
    return parseFloat(inbody770.report[index].ItemValue).toFixed(2);
}

inbody770.range = function(min, max){
    return parseFloat(inbody770.report[min].ItemValue).toFixed(2)  + "~" + parseFloat(inbody770.report[max].ItemValue).toFixed(2);
}

inbody770.rangeWidth = function(value, min, max){
    var s1 = inbody770.toFixed2(min);
    var s2 =inbody770.toFixed2(max);

    return inbody770.rangeWidth2(value, s1, s2);
}

inbody770.rangeWidth2 = function(value, s1, s2){
    var w = inbody770.toFixed2(value);

    var dw = 83;//mm 低标准的范围宽度
    var ww = 62;//mm 标准值的范围宽度

    var width = dw + (w - s1) * ww / (s2 - s1);

    return width;
}

inbody770.loadDZk = function(){
    var dzkObj = {};
    for(var i = 344; i <= 383; i++){
        dzkObj[inbody770.report[i].ItemName] = inbody770.toFixed2(i);
    }
    var style = $("#style1")[0].outerHTML;
    var dzk = $("#divDzk").html().format(dzkObj);
    return style + dzk;
}

//历史折线图
;inbody770.loadChart = (function(){
    var chart = {};
    chart.getChart = function(){

        var weightArr = chart.getArr();      
        var weightSection = chart.getMaxMin(weightArr);  
        var left = 162; //第一个点的左边距
        var space = 42; //点之间的间隙
        var yTop = 922; //最上端点的y轴坐标
        var yBottom = 939;//最下端点的y轴坐标
        var previousTop,previousLeft;
        for (var index = 0; index < weightArr.length; index++) {

            var yk = (yBottom - yTop) / (weightSection.max - weightSection.min); //每个y轴刻度的像素值
            var top = yTop + (weightSection.max - weightArr[index].ItemValue) * yk;

            LODOP.ADD_PRINT_TEXT(top-10,left-15,100,20,weightArr[index].ItemValue);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",8.5);
            //画圆点
            LODOP.ADD_PRINT_SHAPE(5,top,left,6,7,0,1,"#000000");        
            if(index > 0){
                //画折线
                LODOP.ADD_PRINT_LINE(previousTop+3,previousLeft+3,top+3,left+3,0,1);
            }
            previousTop = top;
            previousLeft = left;
            left += space;    
        }
    }
    
    //获取最大值和最小值
    chart.getMaxMin = function(weightArr){
        var max = weightArr[0].ItemValue;
        var min = weightArr[0].ItemValue;
        for (var index = 0; index < weightArr.length; index++) {
            if(max < weightArr[index].ItemValue){
                max = weightArr[index].ItemValue;
            }            

            if(min > weightArr[index].ItemValue){
                min = weightArr[index].ItemValue;
            }
        }

        return {max:max, min:min};
    }

    chart.getArr = function(){
        var data = chart.getData();
        var arr1 = [];
        for (var index = 0; index < data.length; index++) {

            var item = chart.getItem(data, index, 6);    
            if(item) {arr1.push(item)};

            //最多显示8项
            if(arr1.length == 8)
                break;
        }

        return arr1;
    }

    //获取折线图数据
    chart.getData = function(){
        var d = null;
        $.ajaxSetup({async: false});
        var sql = "select a.TestTime,b.* from inbodyreport a inner join inbodyresult b on a.InBodyReport_DBKey = b.InBodyReport_DBKey where a.InbodyModel = '770' and b.ItemCode in (6, 21, 24, 103) order by a.TestTime, b.ItemCode limit 0,32;";
        $.getJSON(pageExt.libPath + "query.php", { sql: sql }, function (data, status, xhr) {
            d = data;
        });
        $.ajaxSetup({async: true});

        return d;
    }

    chart.getItem = function(data, index, ItemCode){
        if(data[index].ItemCode == ItemCode){
            var item = {};
            item.TestTime = data[index].TestTime;;
            item.ItemValue = data[index].ItemValue;
            return item;
        }
        return null;
    }

    return chart.getChart;
}());
