var s10 = {};
s10.urlParams = util.urlToObject(window.location.search);
s10.patient = null;
s10.report = null;

$(function ($) {

    util.bootstrapLodop(3, function(){

        if(util.printerSetting.PrinterName){
            $("#printerName").html(util.printerSetting.PrinterName);
        }else{
            $("#printerName").html("#未设置#");
        }

        var timer1 = window.setInterval(function () {
            if(s10.patient && s10.report){

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

    if(!s10.urlParams || typeof(s10.urlParams.reportId) == "undefined"){
        alert("未获取到报告ID！");
        return;
    }

    var sql = "select a.*,b.Height,b.HospitalizationNumber,date_format(c.DateOfBirth,'%Y-%m-%d') DateOfBirth from inbodyreport a inner join patienthospitalizebasicinfo b on a.PatientHospitalize_DBKey = b.PatientHospitalize_DBKey inner join patientbasicinfo c on c.PATIENT_DBKEY = b.PATIENT_DBKEY where  a.InBodyReport_DBKey = " + s10.urlParams.reportId + ";";
    $.getJSON(pageExt.libPath + "query.php", { sql: sql }, function (data, status, xhr) {
        s10.patient = data[0];
    });

    sql = "select a.*,b.ItemName from inbodyresult a inner join inbodyitem b on a.ItemCode = b.ItemCode and b.InbodyModel = 's10' where InBodyReport_DBKey = " + s10.urlParams.reportId + ";";
    $.getJSON(pageExt.libPath + "query.php", { sql: sql }, function (data, status, xhr) {
        s10.report = data;
    });


});
    

s10.printSetting = function () {
    window.open(pageExt.libPath + "printerSet.php");
}

s10.printDesign = function () {
    s10.printLoad(1);
}

s10.printSetup = function () {
    s10.printLoad(2);
}

s10.preview = function () {
    s10.printLoad(3);
}

s10.print = function () {

    s10.printLoad(4);
    // alert("请等待打印完毕后，再关闭该页面！");
}

s10.printLoad = function (flag) {
    // LODOP = getLodop();

    s10.createPrintPage();
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

s10.createPrintPage = function () {

    LODOP.PRINT_INITA(0, 0, "210mm", "297mm", "InBody s10报告纸打印");
    if (util.printerSetting.PrinterName == "#未设置#") {
        $("#lsMsg").html("尚未设置默认的人体成分报告打印机！");
    } else {
        if (!LODOP.SET_PRINTER_INDEXA(util.printerSetting.PrinterName)) {
            $("#lsMsg").html("未检测到该打印机，将输出到默认打印机！");
        }
    }

    //LODOP.SET_PRINT_PAGESIZE(0,0,0,getSelectedPageSize());
    LODOP.SET_PRINT_PAGESIZE(util.printerSetting.Orient, util.printerSetting.PageWidth, util.printerSetting.PageHeigth, util.printerSetting.PageName);

    LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='s10模板.jpg?v=7'>");
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



    LODOP.ADD_PRINT_TEXT(100,64,100,20,s10.patient.HospitalizationNumber); //ID
    LODOP.ADD_PRINT_TEXT(119,64,100,20,"(" + s10.patient.PatientName + ")"); //姓名
    LODOP.ADD_PRINT_TEXT(110,193,100,20,s10.patient.Height + "cm"); //身高
    LODOP.ADD_PRINT_TEXT(107,269,100,20,s10.patient.Age); //年龄
    // LODOP.ADD_PRINT_TEXT(122,268,100,20,"(" + s10.patient.DateOfBirth + ")"); //生日
    LODOP.ADD_PRINT_TEXT(108,337,100,20,s10.patient.Gender == "M" ? "男" : "女"); //性别
    LODOP.ADD_PRINT_TEXT(112,374,166,20,s10.patient.TestTime); //测试时间
    LODOP.ADD_PRINT_HTML(35,526,254,75,$("#reportTitle")[0].outerHTML); //Title
    LODOP.ADD_PRINT_TEXT(176,154,150,20,s10.toFixed2(7)); //身体总水分 测量值

    //身体总水分 范围
    var min = s10.toFixed2(136) + s10.toFixed2(138) + s10.toFixed2(140);
    var max = s10.toFixed2(137) + s10.toFixed2(139) + s10.toFixed2(141);
    LODOP.ADD_PRINT_TEXT(189,130,150,20,"(" + util.round(min,2) + "~" + util.round(max,2) + ")"); //身体总水分 范围

    LODOP.ADD_PRINT_TEXT(181,221,150,20,s10.toFixed2(7)); //身体总水分
    LODOP.ADD_PRINT_TEXT(203,154,150,20,s10.toFixed2(4)); //蛋白质
    LODOP.ADD_PRINT_TEXT(216,130,150,20,s10.range(74, 73)); //蛋白质 范围
    LODOP.ADD_PRINT_TEXT(229,154,150,20,s10.toFixed2(5)); //无机盐
    LODOP.ADD_PRINT_TEXT(242,130,150,20,s10.range(76, 75)); //无机盐 范围
    LODOP.ADD_PRINT_TEXT(255,154,150,20,s10.toFixed2(6)); //体脂肪
    LODOP.ADD_PRINT_TEXT(268,130,150,20,s10.range(91, 92)); //体脂肪 范围
    LODOP.ADD_PRINT_TEXT(189,300,150,20,s10.toFixed2(8)); //肌肉量
    LODOP.ADD_PRINT_TEXT(202,276,150,20,s10.range(128, 129)); //肌肉量 范围
    LODOP.ADD_PRINT_TEXT(209,375,150,20,s10.toFixed2(9)); //去脂体重

    // 去脂体重 范围，接口未提供，通过公式计算：去脂体重 = 体重 - 体脂肪
    var min = s10.toFixed2(78) - s10.toFixed2(91);
    var max = s10.toFixed2(77) - s10.toFixed2(92);
    LODOP.ADD_PRINT_TEXT(222,351,150,20,"(" + util.round(min,2) + "~" + util.round(max,2) + ")"); //去脂体重 范围
    
    LODOP.ADD_PRINT_TEXT(229,446,150,20,s10.toFixed2(1)); //体重
    LODOP.ADD_PRINT_TEXT(242,422,150,20,s10.range(78, 77)); //体重 范围

    var leftMargin = 145;
    //肌肉脂肪分析
    var w = s10.rangeWidth2(11, 85, 115);
    LODOP.ADD_PRINT_SHAPE(4,351,132,w,9,0,1,"#808080"); //体重 %
    LODOP.ADD_PRINT_TEXT(351,leftMargin + w,150,20,s10.toFixed2(1)); //体重 kg    

    w = s10.rangeWidth2(13, 90, 110);
    LODOP.ADD_PRINT_SHAPE(4,383,132,w,9,0,1,"#808080"); //骨骼肌 %
    LODOP.ADD_PRINT_TEXT(383,leftMargin + w,150,20,s10.toFixed2(12)); //骨骼肌 kg    

    w = s10.rangeWidth2(14, 80, 160);
    LODOP.ADD_PRINT_SHAPE(4,414,132,w,9,0,1,"#808080"); //体脂肪 %
    LODOP.ADD_PRINT_TEXT(414,leftMargin + w,150,20,s10.toFixed2(6)); //体脂肪 kg    

    //肥胖分析
    w = s10.rangeWidth(15, 82, 81);
    LODOP.ADD_PRINT_SHAPE(4,498,132,w,9,0,1,"#808080"); //身体质量指数
    LODOP.ADD_PRINT_TEXT(498,leftMargin + w,150,20,s10.toFixed2(15)); //身体质量指数 值

    w = s10.rangeWidth(16, 84, 83);
    LODOP.ADD_PRINT_SHAPE(4,529,132,w,9,0,1,"#808080"); //体脂百分比
    LODOP.ADD_PRINT_TEXT(529,leftMargin + w,150,20,s10.toFixed2(16)); //体脂百分比 值

    //肌肉均衡
    w = s10.rangeWidth2(23, 85, 115);
    LODOP.ADD_PRINT_SHAPE(4,618,132,w,9,0,1,"#808080"); //右上肢 %
    LODOP.ADD_PRINT_TEXT(618,leftMargin + w,150,20,s10.toFixed2(23)); //右上肢 值
    LODOP.ADD_PRINT_TEXT(618,457,150,20,s10.toFixed2(28)); //细胞外水分比率 右上肢

    w = s10.rangeWidth2(24, 85, 115);
    LODOP.ADD_PRINT_SHAPE(4,653,132,w,9,0,1,"#808080"); //左上肢 %
    LODOP.ADD_PRINT_TEXT(653,leftMargin + w,150,20,s10.toFixed2(24)); //左上肢 值
    LODOP.ADD_PRINT_TEXT(653,457,150,20,s10.toFixed2(29)); //细胞外水分比率 左上肢

    w = s10.rangeWidth2(25, 90, 110);
    LODOP.ADD_PRINT_SHAPE(4,690,132,w,9,0,1,"#808080"); //躯干 %
    LODOP.ADD_PRINT_TEXT(690,leftMargin + w,150,20,s10.toFixed2(25)); //躯干 值
    LODOP.ADD_PRINT_TEXT(690,457,150,20,s10.toFixed2(30)); //细胞外水分比率 躯干

    w = s10.rangeWidth2(26, 90, 110);
    LODOP.ADD_PRINT_SHAPE(4,725,132,w,9,0,1,"#808080"); //右下肢 %
    LODOP.ADD_PRINT_TEXT(725,leftMargin + w,150,20,s10.toFixed2(26)); //右下肢 值
    LODOP.ADD_PRINT_TEXT(725,457,150,20,s10.toFixed2(31)); //细胞外水分比率 右下肢

    w = s10.rangeWidth2(27, 90, 110);
    LODOP.ADD_PRINT_SHAPE(4,761,132,w,9,0,1,"#808080"); //左下肢 %
    LODOP.ADD_PRINT_TEXT(761,leftMargin + w,150,20,s10.toFixed2(27)); //左下肢 值
    LODOP.ADD_PRINT_TEXT(761,457,150,20,s10.toFixed2(32)); //细胞外水分比率 左下肢

    //细胞外水分比率分析
    w = s10.rangeWidth2(33, 0.36, 0.39);
    LODOP.ADD_PRINT_SHAPE(4,849,132,w,9,0,1,"#808080"); //细胞外水分比率分析 线
    LODOP.ADD_PRINT_TEXT(849,leftMargin + w,150,20,s10.toFixed2(33)); //细胞外水分比率分析 值

    // //历史折线图
    // s10.loadChart();

    // //右边栏
    // LODOP.ADD_PRINT_TEXT(197,585,100,20,s10.patient.HealthScore); //分值
    // LODOP.SET_PRINT_STYLEA(0,"FontSize",18.5);

    // //内脏脂肪面积
    // var vfa = s10.report[30].ItemValue;
    // var age = s10.report[5].ItemValue;
    // var xZero = 546;
    // var x100 = 721;
    // var yZero = 386;
    // var y200 = 276;
    // var x = xZero + (x100 - xZero) / 100 * age;
    // var y = yZero + (y200 - yZero) / 200 * vfa;
    // LODOP.ADD_PRINT_TEXT(y,x,100,20,"+"); 
    // LODOP.SET_PRINT_STYLEA(0,"FontSize",18.5);

    // //体型
    // var bmi = s10.report[23].ItemValue;
    // var PBF = s10.report[24].ItemValue;
    // var x10 = 590;
    // var x20 = 696;
    // var y185 = 604;
    // var y239 = 497;
    // var xx = (x20 - x10) / 10; //每个刻度的长度
    // var yy = (y239 - y185) / 5.4; //每个刻度的长度
    // x = (x10 - 10 * xx) + xx * PBF;
    // y = (y185 - 18.5 * yy) + yy * bmi;
    // LODOP.ADD_PRINT_TEXT(y,x,100,20,"+"); 
    // LODOP.SET_PRINT_STYLEA(0,"FontSize",23.5);

    // //体重控制
    // LODOP.ADD_PRINT_TEXT(720,621,100,20,s10.report[204].ItemValue + " kg"); //目标体重
    // LODOP.ADD_PRINT_TEXT(740,621,100,20,s10.report[205].ItemValue + " kg"); //体重控制
    // LODOP.ADD_PRINT_TEXT(760,621,100,20,s10.report[206].ItemValue + " kg"); //脂肪控制
    // LODOP.ADD_PRINT_TEXT(780,621,100,20,s10.report[207].ItemValue + " kg"); //肌肉控制

    // //研究项目
    // LODOP.ADD_PRINT_TEXT(819,621,100,20,s10.report[28].ItemValue + " kcal"); //基础代谢率
    // LODOP.ADD_PRINT_TEXT(839,621,142,20,s10.report[25].ItemValue + " （" + s10.report[163].ItemValue + "~" + s10.report[162].ItemValue + ")"); //腰臀比
    // LODOP.ADD_PRINT_TEXT(859,621,100,20,s10.report[30].ItemValue + " c㎡"); //内脏脂肪面积
    // var itemValue = s10.report[20].ItemValue / Math.pow(s10.report[4].ItemValue / 100,2)
    // LODOP.ADD_PRINT_TEXT(879,621,100,20,itemValue.toFixed(2) + " kg/㎡"); //去脂体重指数 去脂体重/身高平方

    // //全身相位角
    // LODOP.ADD_PRINT_TEXT(914,647,100,20,s10.report[487].ItemValue + " °"); 
    // LODOP.SET_PRINT_STYLEA(0,"FontSize",15.5);

    // var dzk = s10.loadDZk();
    // LODOP.ADD_PRINT_HTM("253mm","138mm","64mm","43mm", dzk);
}

s10.toFixed2 = function(index){
    //return parseFloat(s10.report[index].ItemValue).toFixed(2) / 1;
    return util.round(s10.report[index].ItemValue, 2);
}

s10.range = function(min, max){
    //return parseFloat(s10.report[min].ItemValue).toFixed(2)  + "~" + parseFloat(s10.report[max].ItemValue).toFixed(2);
    return util.round(s10.report[min].ItemValue, 2) + "~" + util.round(s10.report[max].ItemValue, 2);
}

s10.rangeWidth = function(value, min, max){
    var s1 = s10.toFixed2(min);
    var s2 =s10.toFixed2(max);

    return s10.rangeWidth2(value, s1, s2);
}

s10.rangeWidth2 = function(value, s1, s2){
    var w = s10.toFixed2(value);

    var dw = 83;//mm 低标准的范围宽度
    var ww = 62;//mm 标准值的范围宽度

    var width = dw + (w - s1) * ww / (s2 - s1);

    return width;
}

s10.loadDZk = function(){
    var dzkObj = {};
    for(var i = 344; i <= 383; i++){
        dzkObj[s10.report[i].ItemName] = s10.toFixed2(i);
    }
    var style = $("#style1")[0].outerHTML;
    var dzk = $("#divDzk").html().format(dzkObj);
    return style + dzk;
}

//历史折线图
;s10.loadChart = (function(){
    var chart = {};
    chart.legendArr = [6, 21, 24, 103];

    chart.getChart = function(){
        var legendData = chart.getLegendData();  
        var deviation = 0;  //画完一条线之后，整体往下偏离的距离
        for (var index = 0; index < chart.legendArr.length; index++) {
            var arr = legendData[chart.legendArr[index]];
            chart.drawLine(arr, deviation);
            deviation += 38;
        }
        
        //画日期
        chart.drawDate(legendData[6]);
    }

    chart.drawLine = function(arr, deviation){
        var weightSection = chart.getMaxMin(arr);  
        var left = 162; //第一个点的左边距
        var space = 42; //点之间的间隙
        var yTop = 922 + deviation; //最上端点的y轴坐标
        var yBottom = 939 + deviation;//最下端点的y轴坐标
        var previousTop,previousLeft;
        for (var index = 0; index < arr.length; index++) {

            var yk = (yBottom - yTop) / (weightSection.max - weightSection.min); //每个y轴刻度的像素值
            var top = yTop + (weightSection.max - arr[index].ItemValue) * yk;

            LODOP.ADD_PRINT_TEXT(top-10,left-15,100,20,arr[index].ItemValue);
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

    chart.drawDate = function(arr){
        var leftMargin = 142;
        for (var index = 0; index < arr.length; index++) {
            LODOP.ADD_PRINT_TEXT(1064,leftMargin,100,20,arr[index].TestTime);      
            LODOP.SET_PRINT_STYLEA(0,"FontSize",6);   
            leftMargin += 45;   
        }
    }
    
    //获取最大值和最小值
    chart.getMaxMin = function(weightArr){
        var max = parseFloat(weightArr[0].ItemValue);
        var min = parseFloat(weightArr[0].ItemValue);
        for (var index = 0; index < weightArr.length; index++) {
            if(max < parseFloat(weightArr[index].ItemValue)){
                max = parseFloat(weightArr[index].ItemValue);
            }            

            if(min > parseFloat(weightArr[index].ItemValue)){
                min = parseFloat(weightArr[index].ItemValue);
            }
        }

        return {max:max, min:min};
    }

    chart.getLegendData = function(){
        var data = chart.getData();
        var legendData = {};
        for (var i = 0; i < chart.legendArr.length; i++) {
            var legend = chart.legendArr[i];
            var arr = [];
            for (var index = data.length - 1; index >=0 ; index--) {

                var item = chart.getItem(data, index, legend);    
                if(item) {arr.push(item)};
    
                //最多显示8项
                if(arr.length == 8)
                    break;
            }
            legendData[legend] = arr;
        }

        return legendData;
    }

    //获取折线图数据
    chart.getData = function(){
        var d = null;
        $.ajaxSetup({async: false});
        var sql = "select date_format(a.TestTime,'%y.%m.%d\r\n%H:%m') TestTime,b.* from inbodyreport a inner join inbodyresult b on a.InBodyReport_DBKey = b.InBodyReport_DBKey where a.InbodyModel = '770' and b.ItemCode in (6, 21, 24, 103) order by a.TestTime desc, b.ItemCode limit 0,32;";
        $.getJSON(pageExt.libPath + "query.php", { sql: sql }, function (data, status, xhr) {
            d = data;
        });
        $.ajaxSetup({async: true});

        return d;
    }

    chart.getItem = function(data, index, ItemCode){
        if(data[index].ItemCode == ItemCode){
            var item = {};
            item.TestTime = data[index].TestTime;
            item.ItemValue = data[index].ItemValue;
            return item;
        }
        return null;
    }

    return chart.getChart;
}());
