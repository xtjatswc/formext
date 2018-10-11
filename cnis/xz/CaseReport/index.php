<?php
require "../../../autoload.php";

//http://localhost/formext_svn/cnis/xz/CaseReport/index.php?zyh=782061

//患者信息
$zyh = $_GET["zyh"];
$sql = "select *,DATE_FORMAT(b.InHospitalData, '%Y-%m-%d') InHospitalDate
,DATE_FORMAT(a.DateOfBirth, '%Y-%m-%d') DateOfBirth2
 from patientbasicinfo a inner join patienthospitalizebasicinfo b on a.PATIENT_DBKEY = b.PATIENT_DBKEY
left join department c on c.Department_DBKey = b.Department_DBKey
left join bednumber d on d.BedNumber_DBKey = b.BedNumber_DBKey
where b.HospitalizationNumber = '$zyh' order by b.InHospitalData desc limit 0,1";
$patientInfo = $db->fetch_row($sql);
if($patientInfo == null){
    echo "未查询到住院号为：$zyh 的患者信息！";exit;
}
?>
<!DOCTYPE html>    
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title>营养病历报告</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>

<div style="width:1024px;margin-left:auto;margin-right:auto;">
<style id="style1">
    body {font-family:微软雅黑;}
    table {width:100%}
    td,th {font-size: 10.5pt;padding:3px;}
    h3,h4 {margin:0px;text-align:center;}
    table.gridtable {border-collapse:collapse;}
    table.gridtable td{text-align:left;border:1px solid black;}
    table.gridtable th,.td_field {
        border-width: 1px;
        padding: 2px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
        text-align:center;
        font-weight:bold;
    }    
</style>
<h3>营养病历报告</h3>
<br/>
<table class="gridtable" style="margin-top:-1px;">
<tr>
        <td class="td_field">姓名</td>                        
        <td> <?php echo $patientInfo["PatientName"] ?></td>    
        <td class="td_field">性别</td>                        
        <td> <?php echo $patientInfo["Gender"] == "M" ? "男" : "女" ?></td>    
        <td class="td_field">年龄</td>                        
        <td> <?php echo $patientInfo["Age"]."岁"?></td>    
        <td class="td_field">身高</td>                        
        <td> <?php echo $patientInfo["Height"]."cm"?></td>    
    </tr>
    <tr>
        <td class="td_field">住院号</td>                        
        <td> <?php echo $patientInfo["HospitalizationNumber"] ?></td>    
        <td class="td_field">科室</td>                        
        <td> <?php echo $patientInfo["DepartmentName"] ?></td>    
        <td class="td_field">床号</td>                        
        <td> <?php echo $patientInfo["BedCode"] ?></td>    
        <td class="td_field">体重</td>                        
        <td> <?php echo $patientInfo["Weight"]."kg" ?></td>    
    </tr>
    <tr>
        <td class="td_field">入院日期</td>                        
        <td> <?php echo $patientInfo["InHospitalDate"] ?></td>    
        <td class="td_field">出生年月</td>                        
        <td> <?php echo $patientInfo["DateOfBirth2"] ?></td>    
        <td class="td_field"></td>                        
        <td></td>    
        <td class="td_field"></td>                        
        <td></td>    
    </tr>
</table>
<table class="gridtable" style="margin-top:-1px;">
    <tr>
        <td class="td_field"><nobr>主诉</nobr></td>                        
        <td> <?php echo $patientInfo["ChiefComplaint"] ?></td>    
    </tr>
    <tr>
        <td class="td_field"><nobr>现病史</nobr></td>                        
        <td> <?php echo $patientInfo["MedicalHistory"] ?></td>    
    </tr>
    <tr>
        <td class="td_field"><nobr>临床诊断</nobr></td>                        
        <td> <?php echo $patientInfo["ClinicalDiagnosis"] ?></td>    
    </tr>
</table>
<?php
$PatientHospitalize_DBKey = $patientInfo["PatientHospitalize_DBKey"];
$sql = "select ScreeningDate, NSR2002Score from patientquestionnaire where PatientHospitalize_DBKey = $PatientHospitalize_DBKey and NSR2002Score is not null order by ScreeningDate desc limit 0,1";
$nrs = $db->fetch_row($sql);
?>
<br/>
<table class="gridtable" style="margin-top:-1px;">
    <tr>
        <td class="td_field">NRS2002（最近一次）</td>                        
        <td> <?php echo $nrs["ScreeningDate"] ?></td>    
        <td class="td_field">分数</td>                        
        <td> <?php echo $nrs["NSR2002Score"]."分"?></td>    
    </tr>
</table>
<br/>
<h4>营养医嘱</h4>
<?php 
$sql = "select NutrientAdviceSummary_DBKey,DATE_FORMAT(AdviceBeginDate, '%Y-%m-%d') AdviceBeginDate,DATE_FORMAT(AdviceEndDate, '%Y-%m-%d') AdviceEndDate from nutrientadvicesummary where PatientHospitalize_DBKey = $PatientHospitalize_DBKey order by AdviceBeginDate desc";
$recipeRecords = $db->fetch_all($sql);
foreach ($recipeRecords as $key => $value) {
    showAdiveDetail($value["NutrientAdviceSummary_DBKey"], $value["AdviceBeginDate"], $value["AdviceEndDate"]); 
}
?>

</div>
</body>
</html>

<?php
function showAdiveDetail($recipeNo, $AdviceBeginDate, $AdviceEndDate){
global $db;
$sql = "select d.RecipeAndProductName, concat( c.Specification, '（', case d.wrapperType when 1 then '整包装' else '拆分包装' end,'）') 
Specification, e.SysCodeName,
c.AdviceAmount, c.CurrentPrice, 
case d.wrapperType when 1 then c.AdviceAmount * c.CurrentPrice * c.Specification * e.SysCodeShortName / 100 else c.AdviceAmount * c.CurrentPrice * c.Specification * e.SysCodeShortName / 100 end TotalMoney
, f.MeasureUnitName,d.wrapperType
from nutrientadvicesummary a 
inner join nutrientadvice b on a.NutrientAdviceSummary_DBKey = b.NutrientAdviceSummary_DBKey
inner join nutrientadvicedetail c on b.NutrientAdvice_DBKey = c.NutrientAdvice_DBKey
inner join recipeandproduct d on d.RecipeAndProduct_DBKey = c.RecipeAndProduct_DBKey
left join syscode e on e.SysCode = c.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
left join measureunit f on f.MeasureUnit_DBKey = d.MeasureUnit_DBKey
where a.NutrientAdviceSummary_DBKey = $recipeNo  and c.CreateProgram is not null";
$recipeRecords = $db->fetch_all($sql);
if(count($recipeRecords) > 0){
?>
<br/>
医嘱单号：<?php echo $recipeNo ?>&nbsp;&nbsp;&nbsp;
开始日期：<?php echo $AdviceBeginDate ?>&nbsp;&nbsp;&nbsp;
结束日期：<?php echo $AdviceEndDate ?>&nbsp;&nbsp;&nbsp;
<table class="gridtable">
    <tr>
        <th>药品名称</th>
        <th>规格</th>
        <th>频次</th>
        <th>每次数量</th>
        <!-- <th>单位</th> -->
        <th>单价</th>
        <th>金额</th>
    </tr>
<?php
}
$TMoney = 0.0;
foreach ($recipeRecords as $key => $value) {
$unit = "";
if($value["wrapperType"] == "1"){
    $unit = $value["MeasureUnitName"];
}else{
    $unit = $value["MeasureUnitName"];
}

echo "<tr>
<td>".$value["RecipeAndProductName"]."</td>
<td>".$value["Specification"]."</td>
<td>".$value["SysCodeName"]."</td>
<td>".$value["AdviceAmount"]."</td>            
<td>".round($value["CurrentPrice"]*$value["Specification"]/100, 2)." 元</td>
<td>".round($value["TotalMoney"], 3)." 元</td>
</tr>";   
$TMoney = $TMoney + $value["TotalMoney"];
}       

?>
</table>
<?php
}