<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "住院医嘱单打印",
    'version' => $globalCfg["version"], //系统版本，变动时，js等缓存文件也会刷新
    'debug' => $globalCfg["debug"],
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack(){
    ?>
	<style id="style1">
    body {font-family:微软雅黑;}
    table {width:100%}
    td,th {font-size: 10.5pt;padding:3px;text-align:left}
    h3,h4 {margin:0px;}
    </style>
    <?php
}

//javascript
function randerJavascriptCallBack(){
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<script language="javascript" type="text/javascript" src="printout.js?v=<?php echo $version ?>"></script>    
    <?php
}

//body
function randerBodyCallBack(){    
    ?>
    <div>
        <input type="button" value="检测打印插件" onclick="util.CheckLodopIsInstall()" />
        <input type="button" value="设置" onclick="printout.printSetting()" />
        <input type="button" value="打印维护" onclick="printout.printSetup()" />
        <input id="btnPrint" type="button" value="打印" onclick="printout.print()" />
        <div style="display:none">
            <input type="button" value="打印设计" onclick="printout.printDesign()" />
            <input type="button" value="打印预览" onclick="printout.preview()" />
        </div>
    </div>
    <br/>
    打印机:      
    <font id="printerName" color="blue"></font>&nbsp;&nbsp;<font id="lsMsg" color="red"></font>
    <br/>
    <font id="labelTip" color="red"></font>
    <br/>    
<?php
global $db;

$sql = "select SysConfigValue from sysconfig where SysConfigCode = 'SystemPrint'";
$printTitle = $db->fetch_var($sql);

$recipeNo = $_GET["recipeNo"];
$sql = "select a.NutrientAdviceSummary_DBKey, DATE_FORMAT(a.CreateTime, '%Y-%m-%d') CreateTime, 
DATE_FORMAT(SYSDATE(), '%Y-%m-%d') PrintTime,b.HospitalizationNumber, c.PatientName, c.PatientNo,b.Height,b.Weight,
c.Age, case c.Gender when 'M' then '男' else '女' end Gender,
 d.DepartmentName, e.UserName, b.DiseaseListVal, f.BedCode, DATE_FORMAT(a.AdviceBeginDate, '%Y-%m-%d') AdviceBeginDate, DATE_FORMAT(a.AdviceEndDate, '%Y-%m-%d') AdviceEndDate  
 , datediff(a.AdviceEndDate, a.AdviceBeginDate) + 1 AdviceDays
 from nutrientadvicesummary a
inner join patienthospitalizebasicinfo b on a.PatientHospitalize_DBKey = b.PatientHospitalize_DBKey
inner join patientbasicinfo c on b.PATIENT_DBKEY = c.PATIENT_DBKEY
left join department d on d.Department_DBKey = b.Department_DBKey
left join user e on e.User_DBKey = a.CreateBy
left join bednumber f on f.BedNumber_DBKey = b.BedNumber_DBKey
where a.NutrientAdviceSummary_DBKey = $recipeNo";
$baseInfo = $db->fetch_row($sql);

//bmi = BMI的计算公式: 体质指数(BMI)=体重(kg)÷身高^2(m)
$bmi = "";
if($baseInfo["Height"] != 0 && $baseInfo["Weight"] != 0){
    $bmi = $baseInfo["Weight"] / ($baseInfo["Height"]*$baseInfo["Height"]) * 10000;
    $bmi = round($bmi, 2);
}
?>
<div style="width:800px;border:1px solid black;padding:5px">
    <div id="divRecipe" style="padding:10px;padding-top:5px">
    <h2 style="text-align:center">
    <?php echo $printTitle ?>
    </h2>
    <div style="text-align:center;font-size:16pt">肠内营养医嘱单首页</div>
    <hr/>
    <table>
        <tr>
            <td>姓名：<?php echo $baseInfo["PatientName"] ?></td>
            <td>科室：<?php echo $baseInfo["DepartmentName"] ?></td>
            <td>性别：<?php echo $baseInfo["Gender"] ?></td>
            <td>日期：<?php echo $baseInfo["CreateTime"] ?></td>
        </tr>
        <tr>
            <td>年龄：<?php echo $baseInfo["Age"] ?>岁</td>
            <td>身高：<?php echo $baseInfo["Height"] ?>cm</td>
            <td>体重：<?php echo $baseInfo["Weight"] ?>kg</td>
            <td>BMI：<?php echo $bmi ?>kg/㎡</td>
        </tr>
        <tr>
            <td>床号：<?php echo $baseInfo["BedCode"] ?></td>
            <td>住院号：<?php echo $baseInfo["HospitalizationNumber"] ?></td>
            <td>开始日期：<?php echo $baseInfo["AdviceBeginDate"] ?></td>
            <td>结束日期：<?php echo $baseInfo["AdviceEndDate"] ?></td>
        </tr>

    </table>
    <!-- 疾病及诊断：<?php echo $baseInfo["DiseaseListVal"] ?>
    <hr/> -->
    <table>
        <tr>
            <th>制剂名称</th>
            <th style="display:none">规格</th>
            <th style="display:none">频次</th>
            <th style="display:none">每次数量</th>
            <th>剂量</th>
            <th>输注方式</th>
            <th>单位</th>
            <th>单价</th>
            <th>金额</th>
        </tr>
        <tr>
        <td colspan="6"><hr style="margin:0px"/></td>
        </tr>
        <?php
$sql = "select d.RecipeAndProductName, concat( c.Specification, f.MeasureUnitName,'/' ,g.MeasureUnitName, '（', case d.wrapperType when 1 then '整包装' else '拆分包装' end,'）') 
Specification, c.SingleMetering, e.SysCodeName,
case c.Directions when 1 then '口服' else '管饲' end Directions, c.AdviceAmount, c.CurrentPrice, 
case d.wrapperType when 1 then c.AdviceAmount * e.SysCodeShortName else c.AdviceAmount / c.Specification * e.SysCodeShortName end Dose,
case d.wrapperType when 1 then c.AdviceAmount * c.CurrentPrice * e.SysCodeShortName else c.AdviceAmount / c.Specification * c.CurrentPrice * e.SysCodeShortName end TotalMoney
, f.MeasureUnitName, g.MeasureUnitName minUnitName,d.wrapperType
,i.Energy,c.netContent,c.TakeOrder,i.Protein,i.Fat,i.Carbohydrate,i.Ca,i.K,i.Na,i.P
from nutrientadvicesummary a 
inner join nutrientadvice b on a.NutrientAdviceSummary_DBKey = b.NutrientAdviceSummary_DBKey
inner join nutrientadvicedetail c on b.NutrientAdvice_DBKey = c.NutrientAdvice_DBKey
inner join recipeandproduct d on d.RecipeAndProduct_DBKey = c.RecipeAndProduct_DBKey
inner join recipefoodrelation h on h.RecipeAndProduct_DBKey = d.RecipeAndProduct_DBKey
inner join chinafoodcomposition i on i.ChinaFoodComposition_DBKey = h.ChinaFoodComposition_DBKey
left join syscode e on e.SysCode = c.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
left join measureunit f on f.MeasureUnit_DBKey = d.MeasureUnit_DBKey
left join measureunit g on g.MeasureUnit_DBKey = d.minUnit_DBKey
where c.Directions is not null and a.NutrientAdviceSummary_DBKey = $recipeNo";
$recipeRecords = $db->fetch_all($sql);

        $TMoney = 0.0;
        $Energy = 0;
        $Protein = 0;
        $Fat = 0;
        $Carbohydrate = 0;    
        $ProteinRgb = 0;
        $FatRgb = 0;
        $CarbohydrateRgb = 0;    
        $Ca = 0;    
        $K = 0;    
        $Na = 0;    
        $P = 0;    
        $nitrogen = 0; //氮 = 蛋白质 / 6.25
        $nitrogenEnergy; //氮/能量 = 总能量 / 氮
        foreach ($recipeRecords as $key => $value) {
            $unit = "";
            if($value["wrapperType"] == "1"){
                $unit = $value["minUnitName"];
            }else{
                $unit = $value["MeasureUnitName"];
            }

            echo "<tr>
            <td>".$value["RecipeAndProductName"]."</td>
            <td style='display:none'>".$value["Specification"]."</td>
            <td style='display:none'>".$value["SysCodeName"]."</td>
            <td style='display:none'>".$value["AdviceAmount"]."</td>
            <td>".$value["Dose"]."</td>
            <td>".$value["Directions"]."</td>
            <td>".$unit."</td>
            <td>".$value["CurrentPrice"]." 元/".$value["minUnitName"]."</td>
            <td>".round($value["TotalMoney"], 3)." 元</td>
            </tr>";   
            $TMoney = $TMoney + $value["TotalMoney"];

            //计算能量、蛋脂糖
            $nutrientsNum = $value["netContent"];  //总g数 ml数
            $nutrientsNum = $nutrientsNum * count(explode(',', $value["TakeOrder"]));

            $Energy += $nutrientsNum * $value["Energy"] / 100;
            $Protein += $nutrientsNum * $value["Protein"] / 100;
            $Fat += $nutrientsNum * $value["Fat"] / 100;
            $Carbohydrate += $nutrientsNum * $value["Carbohydrate"] / 100;
            $Ca += $nutrientsNum * $value["Ca"] / 100;
            $K += $nutrientsNum * $value["K"] / 100;
            $Na += $nutrientsNum * $value["Na"] / 100;
            $P += $nutrientsNum * $value["P"] / 100;
        }      
        $Energy = round($Energy, 2);
        $Protein = round($Protein, 2);
        $Fat = round($Fat, 2);
        $Carbohydrate = round($Carbohydrate, 2);
        $Ca = round($Ca, 2);
        $K = round($K, 2);
        $Na = round($Na, 2);
        $P = round($P, 2);

        $ProteinRgb = $Protein * 4 / ($Protein * 4 + $Fat * 9 + $Carbohydrate * 4) * 100;
        $ProteinRgb = round($ProteinRgb, 2);
        $FatRgb = $Fat * 9 / ($Protein * 4 + $Fat * 9 + $Carbohydrate * 4) * 100;
        $FatRgb = round($FatRgb, 2);
        $CarbohydrateRgb = $Carbohydrate * 4 / ($Protein * 4 + $Fat * 9 + $Carbohydrate * 4) * 100;
        $CarbohydrateRgb = round($CarbohydrateRgb, 2);
        $nitrogen = round($Protein / 6.25, 2);
        $nitrogenEnergy = round($Energy / $nitrogen, 2);
        ?>
        <tr>
            <th colspan="4"></th>
            <th>合计：</th>
            <th><?php echo round($TMoney * $baseInfo["AdviceDays"], 2) ?> 元（<?php echo $baseInfo["AdviceDays"]?>天）</th>
        </tr>
    </table>

    营养计算
    <hr/>
    <table>
    <tr>
    <td>总能量：<?php echo $Energy?>kcal</td>
    <td>蛋白质供热比：<?php echo $ProteinRgb?>%</td>
    <td>脂肪供能比：<?php echo $FatRgb?>%</td>
    <td>碳水化合物供热比：<?php echo $CarbohydrateRgb?>%</td>
    </tr>
    <tr>
    <td>总氮：<?php echo $nitrogen?>g</td>
    <td>蛋白质摄入量：<?php echo $Protein?>g</td>
    <td>脂肪摄入量：<?php echo $Fat?>g</td>
    <td>碳水化合物摄入量：<?php echo $Carbohydrate?>g</td>
    </tr>
    <tr>
    <td>钙：<?php echo $Ca?>mg</td>
    <td>钾：<?php echo $K?>mg</td>
    <td>钠：<?php echo $Na?>mg</td>
    <td>磷：<?php echo $P?>mg</td>
    </tr>
    <tr>
    <td>氮/能量：1：<?php echo $nitrogenEnergy?></td>
    <td></td>
    <td></td>
    <td></td>
    </tr>
    </table>

    <table>
        <tr>
            <td>营养医生（师）签字：</td>
            <td style="width:40%"></td>
            <td>打印日期：<?php echo $baseInfo["PrintTime"] ?></td>
        </tr>
    </table>

    </div>
</div>
    <?php
}