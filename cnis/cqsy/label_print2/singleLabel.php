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
    if(strpos($value["ChargingItemName"],'肠内营养液')!==false){
        if($value["Unit"] == "ml(液)"){
            //液 不显示数量
            $ChargingNum = "";
        }else{
            //粉 不显示规格(隐掉100ml 250ml)
            $ChargingItemSpec = "";
        }
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
<?php
$nutrients = calc_recipe_nutrients($detailDBKeys);
?>
<table style="margin-top:-1px" >
    <tr>
    <td>能量：<br/><?php echo $nutrients["Energy"] ?> kcal</td>
    <td>蛋白质：<br/><?php echo $nutrients["Protein"] ?> g</td>
    <td>脂肪：<br/><?php echo $nutrients["Fat"] ?> g</td>
    <td>碳水化合物：<br/><?php echo $nutrients["Carbohydrate"] ?> g</td>
    </tr>
</table>
</div>
<?php

function calc_recipe_nutrients($detailDBKeys){
    global $db;
    $Energy = 0;
    $Protein = 0;
    $Fat = 0;
    $Carbohydrate = 0;

    $sql = "select a.RecipeAndProductName,d.UnitKey,d.AdviceAmount,d.netContent,d.netContentUnit,a.NutrientProductSpecification
    ,b.Energy,b.Protein,b.Fat,b.Carbohydrate,e.SysCodeShortName,h.SysCodeName PreparationMode from recipeandproduct a 
    inner join recipefoodrelation c on c.RecipeAndProduct_DBKey = a.RecipeAndProduct_DBKey
    inner join chinafoodcomposition b on b.ChinaFoodComposition_DBKey = c.ChinaFoodComposition_DBKey
    inner join nutrientadvicedetail d on d.RecipeAndProduct_DBKey = a.RecipeAndProduct_DBKey
    left join syscode e on e.SysCode = d.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
    left join syscode h on h.SysCode = d.PreparationMode and h.SystemCodeTypeName = 'PreparationMode'
    where d.NutrientAdviceDetail_DBKEY in ($detailDBKeys)";
    $tblDetail = $db->fetch_all($sql);
    foreach ($tblDetail as $key => $value) {
        $nutrientsNum = $value["netContent"];  //总g数 ml数
        if($value["PreparationMode"] == "自助冲剂"){
            //自助冲剂需要除以频次
            $nutrientsNum = round($nutrientsNum / $value["SysCodeShortName"]);
        }

        // $array = explode("_", $value["UnitKey"]);
        // if ($array[1] == "B") {
        //     //倍康素 罐
        //     $nutrientsNum = $value["AdviceAmount"] * $value["NutrientProductSpecification"];
        // }else if ($array[1] == "C") {
        //     //佳膳 拆
        //     $nutrientsNum = $value["AdviceAmount"];
        // }

        $Energy += round($nutrientsNum * $value["Energy"] / 100, 1);
        $Protein += round($nutrientsNum * $value["Protein"] / 100, 1);
        $Fat += round($nutrientsNum * $value["Fat"] / 100, 1);
        $Carbohydrate += round($nutrientsNum * $value["Carbohydrate"] / 100, 1);
    }

    return array(
        "Energy" => $Energy,
        "Protein" => $Protein,
        "Fat" => $Fat,
        "Carbohydrate" => $Carbohydrate,
    );
}