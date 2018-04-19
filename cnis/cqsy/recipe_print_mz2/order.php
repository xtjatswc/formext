<?php
require "../../../autoload.php";

$recipeNo = $_GET["recipeNo"];
$sql = "select a.NutrientAdviceSummary_DBKey, DATE_FORMAT(a.CreateTime, '%Y-%m-%d') CreateTime,DATE_FORMAT(sysdate(), '%Y-%m-%d') SysDate, b.HospitalizationNumber, c.PatientName, c.PatientNo,
c.Age, case c.Gender when 'M' then '男' else '女' end Gender,c.TelPhone,
 d.DepartmentName, e.UserName, b.DiseaseListVal from nutrientadvicesummary a
inner join patienthospitalizebasicinfo b on a.PatientHospitalize_DBKey = b.PatientHospitalize_DBKey
inner join patientbasicinfo c on b.PATIENT_DBKEY = c.PATIENT_DBKEY
inner join department d on d.Department_DBKey = b.Department_DBKey
left join user e on e.User_DBKey = a.CreateBy
where a.NutrientAdviceSummary_DBKey = $recipeNo";
$baseInfo = $db->fetch_row($sql);

$sql = "select f.ChargingItemName,f.ChargingPrice,f.ChargingItemSpec,f.ChargingNum,f.ChargingItemUnit,f.ChargingMoney, d.RecipeAndProductName,c.Unit, c.UnitKey, c.SingleMetering, e.SysCodeName,d.NutrientProductSpecification,d.MeasureUnit_DBKey,d.minUnit_DBKey,d.menuType,d.BaseUnit_DBKey,c.totalMoney,d.MinNum,d.wrapperType,c.NutrientAdviceDetail_DBKEY,d.RecipeAndProduct_DBKey,
cast(c.AdviceAmount as SIGNED INTEGER) AdviceAmount, c.CurrentPrice from nutrientadvicesummary a 
inner join nutrientadvice b on a.NutrientAdviceSummary_DBKey = b.NutrientAdviceSummary_DBKey
inner join nutrientadvicedetail c on b.NutrientAdvice_DBKey = c.NutrientAdvice_DBKey
inner join recipeandproduct d on d.RecipeAndProduct_DBKey = c.RecipeAndProduct_DBKey
left join syscode e on e.SysCode = c.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
inner join chargingadvicedetail f on f.NutrientAdviceDetail_DBKEY = c.NutrientAdviceDetail_DBKEY
where a.NutrientAdviceSummary_DBKey = $recipeNo order by d.RecipeAndProduct_DBKey";
$recipeRecords = $db->fetch_all($sql);

?>

<div style="text-align:center">
    <table style="width:auto;margin:auto;">
    <tr>
    <td><img src="logo.png"></img></td>
    <td><h3>重庆医科大学附属第三医院</h3></td>
    </tr>
    </table>
</div>



<div style="text-align:left">No.<?php echo $recipeNo ?></div>
<br/>
<h4>治疗单</h4>

<table class="orderTable">
<tr>
    <td>姓名：<?php echo $baseInfo["PatientName"]?></td>
    <td>性别：<?php echo $baseInfo["Gender"]?></td>
    <td>年龄：<?php echo $baseInfo["Age"]?></td>
</tr>
</table>

<table class="orderTable">
<tr>
    <td>联系电话：<?php echo $baseInfo["TelPhone"]?></td>
    <td>地址：</td>
</tr>
<tr>
    <td>科别：<?php echo $baseInfo["DepartmentName"]?></td>
    <td>门诊病历号：<?php echo $baseInfo["PatientNo"]?></td>
</tr>
<tr colspan="2">
    <td></td>
</tr>
</table>

<table>
<tr><td>临床诊断：<?php echo $baseInfo["DiseaseListVal"]?></td></tr>
<tr><td>开具日期：<?php echo $baseInfo["SysDate"]?></td></tr>
</table>



<h3 style="text-align:left;">RP</h3>

<table style="width:auto;margin-left:50px;">
<?php
$sn = 1;
$totalMoney = 0;
foreach ($recipeRecords as $key => $value) {
    $totalMoney = $totalMoney + $value["ChargingMoney"];

    //数量为0的不显示
    echo $value["ChargingNum"];
    if($value["ChargingMoney"] == "0" || $value["ChargingMoney"] == "")    
        continue;

    echo "<tr>
    <td>".$sn."、</td>
    <td>".$value["ChargingItemName"]."</td>
    <td>（¥".$value["ChargingPrice"]."）</td>
    <td> X ".$value["ChargingNum"]."</td>
    <td>".$value["ChargingItemUnit"]."</td>
    </tr>";
    $sn++;
}
?>
</table>

<br/>
<br/>

<table>
<tr>
<td>金额：<?php echo $totalMoney?>（元）</td>
<td></td>
<td>医师：</td>
<td></td>
</tr>
</table>

<h5>领取地点：门诊部一楼综合诊区8诊室</h5>
