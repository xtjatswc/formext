<?php
require "../../../autoload.php";

global $db;

    $sql = "select SysConfigValue from sysconfig where SysConfigCode = 'SystemPrint'";
    $printTitle = $db->fetch_var($sql);

    $recipeNo = $_GET["recipeNo"];
    $sql = "select a.NutrientAdviceSummary_DBKey, DATE_FORMAT(a.CreateTime, '%Y-%m-%d') CreateTime, b.HospitalizationNumber, c.PatientName, c.PatientNo,
c.Age, case c.Gender when 'M' then '男' else '女' end Gender,
 d.DepartmentName, e.UserName, b.DiseaseListVal, f.BedCode, DATE_FORMAT(a.AdviceBeginDate, '%Y-%m-%d') AdviceBeginDate, DATE_FORMAT(a.AdviceEndDate, '%Y-%m-%d') AdviceEndDate
 , datediff(a.AdviceEndDate, a.AdviceBeginDate) + 1 AdviceDays
 from nutrientadvicesummary a
inner join patienthospitalizebasicinfo b on a.PatientHospitalize_DBKey = b.PatientHospitalize_DBKey
inner join patientbasicinfo c on b.PATIENT_DBKEY = c.PATIENT_DBKEY
inner join department d on d.Department_DBKey = b.Department_DBKey
left join user e on e.User_DBKey = a.CreateBy
left join bednumber f on f.BedNumber_DBKey = b.BedNumber_DBKey
where a.NutrientAdviceSummary_DBKey = $recipeNo";
    $baseInfo = $db->fetch_row($sql);

    ?>
    <div>
    <div style="text-align:center;font-size:15pt"><?php echo $printTitle ?></div>
    <h3 style="text-align:center">
    肠内营养(EN)医嘱单首页
    </h3>
    <table>
        <tr>
            <td>住院号：<?php echo $baseInfo["HospitalizationNumber"] ?></td>
            <td>科室：<?php echo $baseInfo["DepartmentName"] ?></td>
            <td>床号：<?php echo $baseInfo["BedCode"] ?></td>
        </tr>
        <tr>
            <td>姓名：<?php echo $baseInfo["PatientName"] ?></td>
            <td>性别：<?php echo $baseInfo["Gender"] ?></td>
            <td>年龄：<?php echo $baseInfo["Age"] ?>岁</td>
        </tr>
        <tr>
            <td>开始日期：<?php echo $baseInfo["AdviceBeginDate"] ?></td>
            <td>结束日期：<?php echo $baseInfo["AdviceEndDate"] ?></td>
            <td></td>
        </tr>        
    </table>
    <hr/>
    <!-- 疾病及诊断：<?php echo $baseInfo["DiseaseListVal"] ?>
    <hr/> -->
        <?php
$sql = "select f.ChargingItemName,f.ChargingPrice,f.ChargingItemSpec,f.ChargingNum,f.ChargingItemUnit,f.ChargingMoney, d.RecipeAndProductName,c.Unit, c.UnitKey, c.SingleMetering, e.SysCodeName,d.NutrientProductSpecification,d.MeasureUnit_DBKey,d.minUnit_DBKey,d.menuType,d.BaseUnit_DBKey,c.totalMoney,d.MinNum,d.wrapperType,c.NutrientAdviceDetail_DBKEY,d.RecipeAndProduct_DBKey,g.SysCodeName PreparationMode,case c.Directions when 1 then '口服' else '管伺' end Directions,
cast(c.AdviceAmount as SIGNED INTEGER) AdviceAmount, c.CurrentPrice from nutrientadvicesummary a 
inner join nutrientadvice b on a.NutrientAdviceSummary_DBKey = b.NutrientAdviceSummary_DBKey
inner join nutrientadvicedetail c on b.NutrientAdvice_DBKey = c.NutrientAdvice_DBKey
inner join recipeandproduct d on d.RecipeAndProduct_DBKey = c.RecipeAndProduct_DBKey
left join syscode e on e.SysCode = c.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
inner join chargingadvicedetail f on f.NutrientAdviceDetail_DBKEY = c.NutrientAdviceDetail_DBKEY
left join syscode g on g.SysCode = c.PreparationMode and g.SystemCodeTypeName = 'PreparationMode'
where a.NutrientAdviceSummary_DBKey = $recipeNo   and c.CreateProgram is not null order by d.RecipeAndProduct_DBKey,f.NutrientAdviceDetail_DBKEY";
$recipeRecords = $db->fetch_all($sql);
?>
<table class="adviceList">
<?php
$sn = 1;
$totalMoney = 0;
foreach ($recipeRecords as $key => $value) {
    $totalMoney = $totalMoney + $value["ChargingMoney"];

    //数量为0的不显示
    if($value["ChargingMoney"] == "0" || $value["ChargingMoney"] == "")    
        continue;

    echo "<tr>
    <td>".$sn."、</td>
    <td>".$value["ChargingItemName"]."</td>
    <td>".$value["ChargingPrice"]." 元</td>
    <td>".$value["ChargingNum"]."</td>
    <td>".$value["ChargingItemUnit"]."</td>
    <td>".$value["SysCodeName"]."</td>
    <td>".$value["PreparationMode"]."</td>
    <td>".$value["Directions"]."</td>
    <td>".$value["ChargingMoney"]." 元</td>
    </tr>";
    $sn++;
}
?>
</table>
    <table>
        <tr>
            <td>医师：<?php echo $baseInfo["UserName"] ?></td>
            <td style="width:40%">签章：</td>
            <td>日期：<?php echo $baseInfo["CreateTime"] ?></td>
        </tr>
    </table>
    <hr/>
    <table>
        <tr>
            <td>药费：<?php echo round($totalMoney * $baseInfo["AdviceDays"], 2) ?> 元（共<?php echo $baseInfo["AdviceDays"] ?>天）</td>
            <td>审核/收费：</td>
            <td>核对/发药：</td>
        </tr>
    </table>
    <font style="font-size:10pt">
    根据《中国食药局》相关要求：为保障患者食品安全，除食品质量原因外，食品一经发出，不得退换。
    </font>
    <h4>
    注：价格以收费时为准 当天交费，过期无效
    </h4>
    </div>

