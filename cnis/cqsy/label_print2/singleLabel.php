<?php
require "../../../autoload.php";

$detailDBKeys = $_GET["detailDBKeys"];

$sql = "select  c.NutrientAdviceSummary_DBKey,d.HospitalizationNumber, e.PatientName, f.DepartmentName, g.Bed, h.SysCodeName PreparationMode,a.TakeOrder,
case a.Directions when 1 then '口服' else '管伺' end Directions
 from nutrientadvicedetail a INNER JOIN nutrientadvice b on a.NutrientAdvice_DBKey = b.NutrientAdvice_DBKey
inner JOIN nutrientadvicesummary c on b.NutrientAdviceSummary_DBKey = c.NutrientAdviceSummary_DBKey
inner join patienthospitalizebasicinfo d on d.PatientHospitalize_DBKey = c.PatientHospitalize_DBKey
inner join patientbasicinfo e on e.PATIENT_DBKEY = d.PATIENT_DBKEY
left join department f on f.Department_DBKey = d.Department_DBKey
left join bednumber g on g.BedNumber_DBKey = d.BedNumber_DBKey
left join syscode h on h.SysCode = a.PreparationMode and h.SystemCodeTypeName = 'PreparationMode'
where a.NutrientAdviceDetail_DBKEY in ($detailDBKeys) limit 0,1";
$result = $db->fetch_row($sql);

//制剂数据
$sql = "select f.ChargingItemName,f.ChargingPrice,f.ChargingItemSpec,f.ChargingNum,f.ChargingItemUnit,f.ChargingMoney, b.RecipeAndProductName,a.AdviceAmount,case a.NutrientAdviceDetailRemark when '无' then '' else a.NutrientAdviceDetailRemark end NutrientAdviceDetailRemark,c.MeasureUnitName,d.MeasureUnitName minUnitName, b.wrapperType, e.SysCodeName,e.SysCodeShortName,a.Unit from nutrientadvicedetail a
INNER JOIN recipeandproduct b on a.RecipeAndProduct_DBKey = b.RecipeAndProduct_DBKey
left join measureunit c on c.MeasureUnit_DBKey = b.MeasureUnit_DBKey
left join measureunit d on d.MeasureUnit_DBKey = b.minUnit_DBKey
left join syscode e on e.SysCode = a.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
left join chargingadvicedetail f on f.NutrientAdviceDetail_DBKEY = a.NutrientAdviceDetail_DBKEY
where a.NutrientAdviceDetail_DBKEY in ($detailDBKeys) and f.ChargingNum <> 0";
$tblDetail = $db->fetch_all($sql);
?>

<div class="labelContent">

<table class="baseTable">
    <tr>
    <td>姓名：<?php echo $result["PatientName"] ?></td>
    <td>科室：<?php echo $result["DepartmentName"] ?></td>
    <td>床号：<?php echo $result["Bed"] ?></td>
    </tr>
</table>
<table  style="margin-top:-1px" >
    <tr>
    <td style="border-right:none">品名</td>
    <td style="padding:0px">
        <table style="margin-top:-1px;margin-bottom:-1px">
            <tr>
                <td style="padding:0px">
                <table  class="recipeTable" id="tblNutrientadvicedetail" >
    <tbody>
        <?php
foreach ($tblDetail as $key => $value) {
    //除自助冲剂外，数量要除以频次
    $ChargingNum = round($value["ChargingNum"], 1);
    if($result["PreparationMode"] != "自助冲剂"){
        $ChargingNum = round($value["ChargingNum"] / $value["SysCodeShortName"], 1);
    }
    //规格 液 or 粉
    $ChargingItemSpec = $value["ChargingItemSpec"];
    if(strpos($value["ChargingItemName"],'肠内营养液')!==false && $value["Unit"] != "ml(液)"){
        $ChargingItemSpec = "";
    }

    echo "<tr>
            <td>" . $value["ChargingItemName"] . " ". $ChargingItemSpec . "</td>
            <td>" . $ChargingNum . " ". $value["ChargingItemUnit"] . "</td>
          </tr>";
}
?>
    </tbody>
</table>
                </td>
            </tr>
            <tr>
                <td style="padding:0px">
                    <table style="margin-top:-1px;">
                        <tr>
                        <td>
                            <nobr>制剂方式：<?php echo $result["PreparationMode"] ?></nobr>
                            <nobr>途径：<?php echo $result["Directions"] ?></nobr>
                        </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
    </tr>
</table>
<table style="margin-top:-1px" >
    <tr>
    <td>能量：<br/><?php echo $result["PatientName"] ?></td>
    <td>蛋白质：<br/><?php echo $result["DepartmentName"] ?></td>
    <td>脂肪：<br/><?php echo $result["Bed"] ?></td>
    <td>碳水化合物：<br/><?php echo $result["Bed"] ?></td>
    </tr>
</table>
</div>