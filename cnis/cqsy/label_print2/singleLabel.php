<?php
require "../../../autoload.php";

$detailDBKeys = $_GET["detailDBKeys"];

$sql = "select  c.NutrientAdviceSummary_DBKey,d.HospitalizationNumber, e.PatientName, f.DepartmentName, g.Bed, h.SysCodeName PreparationMode,a.TakeOrder,
case a.Directions when 1 then '口服' else '管饲' end Directions
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
$sql = "select f.ChargingItemName,f.ChargingPrice,f.ChargingItemSpec,f.ChargingNum,f.ChargingItemUnit,f.ChargingMoney, b.RecipeAndProductName,a.AdviceAmount,case a.NutrientAdviceDetailRemark when '无' then '' else a.NutrientAdviceDetailRemark end NutrientAdviceDetailRemark,c.MeasureUnitName,d.MeasureUnitName minUnitName, b.wrapperType, e.SysCodeName,e.SysCodeShortName,a.Unit,b.wrapperType from nutrientadvicedetail a
INNER JOIN recipeandproduct b on a.RecipeAndProduct_DBKey = b.RecipeAndProduct_DBKey
left join measureunit c on c.MeasureUnit_DBKey = b.MeasureUnit_DBKey
left join measureunit d on d.MeasureUnit_DBKey = b.minUnit_DBKey
left join syscode e on e.SysCode = a.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
left join chargingadvicedetail f on f.NutrientAdviceDetail_DBKEY = a.NutrientAdviceDetail_DBKEY
where a.NutrientAdviceDetail_DBKEY in ($detailDBKeys) and f.ChargingNum <> 0 order by a.NutrientAdviceDetail_DBKEY";
$tblDetail = $db->fetch_all($sql);

$isICU = false;
if($result["DepartmentName"] == "中心ICU"){
    $isICU = true;
}    

?>

<div class="labelContent">

<?php
if($isICU){
    loadPatientInfo($result, $isICU);    
    loadAdviceDetail($tblDetail, $result);
}else{
    loadAdviceDetail($tblDetail, $result);
    loadPatientInfo($result, $isICU);    
}

$nutrients = calc_recipe_nutrients($detailDBKeys);
?>

<table>
    <tr>
        <td>
<div>
<!-- <nobr><?php echo $result["PreparationMode"] ?></nobr>&nbsp;&nbsp;&nbsp; -->
<?php if(!$isICU):?>
<nobr>途径：<?php echo $result["Directions"] ?></nobr><br/>    
<?php endif;?>
</div>
        </td>
    </tr>
</table>

<table style="margin-top:5px;">
<?php if(!$isICU):?>
<tr>
<td colspan="4">以下为每袋产品营养成分</td>
</tr>
<?php endif;?>
<tr>
    <td>能量</td>
    <td><?php echo $nutrients["Energy"] ?>kcal</td>
    <td>蛋白质</td>
    <td><?php echo $nutrients["Protein"] ?>g</td>
    </tr>
<?php if(!$isICU):?>    
    <tr>
    <td>脂肪</td>
    <td><?php echo $nutrients["Fat"] ?>g</td>
    <td>碳水化合物</td>
    <td><?php echo $nutrients["Carbohydrate"] ?>g</td>
    </tr>
<?php endif;?>
</table>

<table style="margin-top:5px;">
    <tr>
        <td>
<?php
    $usage = usage($tblDetail);
    if($usage != ""){
        echo $usage;
    }
?>
        </td>
    </tr>
</table>

</div>
<!--end <div class="labelContent"> -->


<?php

//加载患者信息
function loadPatientInfo($result, $isICU){
    ?>
<table style="margin-top:5px;">
    <tr>
        <td>

<?php
if($isICU){
?>    
<div>
<div style="text-align:center;width:100%;font-size:42pt;line-height:44pt">
<?php echo $result["Bed"] ?>床<br/>
<?php echo $result["PatientName"] ?>
</div>    
住院号：<?php echo $result["HospitalizationNumber"] ?>
</div>
<?php
}else{
?>    
<div  style="font-size:12pt;">
科室：<?php echo $result["DepartmentName"] ?>&nbsp;&nbsp;&nbsp;床号：<?php echo $result["Bed"] ?><br/>
姓名：<?php echo $result["PatientName"] ?><br/>
住院号：<?php echo $result["HospitalizationNumber"] ?>
</div>
<?php
}
?>

        </td>
    </tr>
</table>    
    <?php
}

//加载医嘱明细
function loadAdviceDetail($tblDetail, $result){
    ?>
<table >
<?php
foreach ($tblDetail as $key => $value) {
    //除自助冲剂外，数量要除以频次
    $ChargingNum = round($value["ChargingNum"], 1);
    $ChargingItemUnit = $value["ChargingItemUnit"];
    if($result["PreparationMode"] != "自助冲剂"){
        $ChargingNum = round($value["ChargingNum"] / $value["SysCodeShortName"], 1);
    }

    //规格 液 or 粉
    $ChargingItemSpec = $value["ChargingItemSpec"];
    //if(strpos($value["ChargingItemName"],'肠内营养液')!==false){
        if($tblDetail[0]["Unit"] == "ml(液)"){
            //液 不显示数量、单位
            $ChargingNum = "";
            $ChargingItemUnit = "";
        }else{
            //粉 不显示规格(隐掉100ml 250ml)
            $ChargingItemSpec = "";
        }
    //}

    echo "<tr>
    <td>".$value["ChargingItemName"] . $ChargingItemSpec."</td>
    <td>".$ChargingNum . $ChargingItemUnit."</td>
    </tr>";
}
?>
</table> 
<?php 
}

//用法用量
function usage($tblDetail){
    $isLiquid = false; //是否为液体
    //
    foreach ($tblDetail as $key => $value) {
        if($value["Unit"] == "ml(液)"){
            $isLiquid = true;
            break;
        }
    }

    if($isLiquid){
        //液体
        return "贮存方法：2-4℃冷藏，未开封冷藏保存48小时<br/>电话：60353060<br/>重医大附三院临床营养科制";
    }else if($tblDetail[0]["wrapperType"] == "1"){
        //整包装不用显示用法用量，流食也是整包装的
    }else{
        //粉剂
        return "用法用量：每袋加&nbsp;&nbsp;&nbsp;&nbsp;ml温开水，用清洁工具调配后口服<br/>电话：60353060<br/>重医大附三院临床营养科制";
    }

    return "电话：60353060<br/>重医大附三院临床营养科制";
}

function calc_recipe_nutrients($detailDBKeys){
    global $db;
    $Energy = 0;
    $Protein = 0;
    $Fat = 0;
    $Carbohydrate = 0;

    $singleFlag = true;

    $sql = "select a.RecipeAndProductName,d.UnitKey,d.AdviceAmount,d.netContent,d.netContentUnit,a.NutrientProductSpecification
    ,b.Energy,b.Protein,b.Fat,b.Carbohydrate,e.SysCodeShortName,f.ChargingNum,h.SysCodeName PreparationMode, d.Unit,d.TakeOrder from recipeandproduct a 
    inner join recipefoodrelation c on c.RecipeAndProduct_DBKey = a.RecipeAndProduct_DBKey
    inner join chinafoodcomposition b on b.ChinaFoodComposition_DBKey = c.ChinaFoodComposition_DBKey
    inner join nutrientadvicedetail d on d.RecipeAndProduct_DBKey = a.RecipeAndProduct_DBKey
		inner join chargingadvicedetail f on f.NutrientAdviceDetail_DBKEY = d.NutrientAdviceDetail_DBKEY
    left join syscode e on e.SysCode = d.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
    left join syscode h on h.SysCode = d.PreparationMode and h.SystemCodeTypeName = 'PreparationMode'
    where d.NutrientAdviceDetail_DBKEY in ($detailDBKeys) order by d.NutrientAdviceDetail_DBKEY ";
    $tblDetail = $db->fetch_all($sql);
    foreach ($tblDetail as $key => $value) {
        $nutrientsNum = $value["netContent"];  //总g数 ml数
        if($value["PreparationMode"] == "自助冲剂"){
            // //自助冲剂需要除以频次
            // $nutrientsNum = round($nutrientsNum / $value["SysCodeShortName"]);
            //自助冲剂每个标签计算数量1的能量、蛋脂糖
            //$nutrientsNum = round($nutrientsNum / $tblDetail[0]["ChargingNum"], 1);
            $nutrientsNum = $nutrientsNum * count(explode(',', $value["TakeOrder"]));
        }

        if($value["PreparationMode"] == "组合冲剂" && $value["Unit"] == "ml(液)"){
            $singleFlag = false;
        }

        // $array = explode("_", $value["UnitKey"]);
        // if ($array[1] == "B") {
        //     //倍康素 罐
        //     $nutrientsNum = $value["AdviceAmount"] * $value["NutrientProductSpecification"];
        // }else if ($array[1] == "C") {
        //     //佳膳 拆
        //     $nutrientsNum = $value["AdviceAmount"];
        // }

        $Energy += round($nutrientsNum * $value["Energy"] / 100, 2);
        $Protein += round($nutrientsNum * $value["Protein"] / 100, 2);
        $Fat += round($nutrientsNum * $value["Fat"] / 100, 2);
        $Carbohydrate += round($nutrientsNum * $value["Carbohydrate"] / 100, 2);
    }

    //如果是组合冲剂，并且不包含液体，则能量、蛋脂糖还需要除以'收费数量（取第一条明细的收费数量）'
    //if(($tblDetail[0]["PreparationMode"] == "组合冲剂" && $singleFlag) || $tblDetail[0]["PreparationMode"] == "自助冲剂"){

        $singleNum = $tblDetail[0]["ChargingNum"];
        $Energy = round($Energy / $singleNum, 2);
        $Protein = round($Protein / $singleNum, 2);
        $Fat = round($Fat / $singleNum, 2);
        $Carbohydrate = round($Carbohydrate / $singleNum, 2);
    //}

    return array(
        "Energy" => $Energy,
        "Protein" => $Protein,
        "Fat" => $Fat,
        "Carbohydrate" => $Carbohydrate,
    );
}