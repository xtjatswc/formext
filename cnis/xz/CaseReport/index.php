<?php
require "../../../autoload.php";

//患者信息
$zyh = $_GET["zyh"];
$sql = "select *,DATE_FORMAT(b.InHospitalData, '%Y-%m-%d') InHospitalDate
,DATE_FORMAT(a.DateOfBirth, '%Y-%m-%d') DateOfBirth2
 from patientbasicinfo a inner join patienthospitalizebasicinfo b on a.PATIENT_DBKEY = b.PATIENT_DBKEY
left join department c on c.Department_DBKey = b.Department_DBKey
left join bednumber d on d.BedNumber_DBKey = b.BedNumber_DBKey
where b.HospitalizationNumber = '$zyh'";
$patientInfo = $db->fetch_all($sql);

foreach ($patientInfo as $key => $value) {
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
<h3>患者基本信息</h3>
<table class="gridtable" style="margin-top:-1px;">
<tr>
        <td class="td_field">姓名</td>                        
        <td> <?php echo $value["PatientName"] ?></td>    
        <td class="td_field">性别</td>                        
        <td> <?php echo $value["Gender"] == "M" ? "男" : "女" ?></td>    
        <td class="td_field">年龄</td>                        
        <td> <?php echo $value["Age"]."岁"?></td>    
        <td class="td_field">身高</td>                        
        <td> <?php echo $value["Height"]."cm"?></td>    
    </tr>
    <tr>
        <td class="td_field">住院号</td>                        
        <td> <?php echo $value["HospitalizationNumber"] ?></td>    
        <td class="td_field">科室</td>                        
        <td> <?php echo $value["DepartmentName"] ?></td>    
        <td class="td_field">床号</td>                        
        <td> <?php echo $value["BedCode"] ?></td>    
        <td class="td_field">体重</td>                        
        <td> <?php echo $value["Weight"]."kg" ?></td>    
    </tr>
    <tr>
        <td class="td_field">入院日期</td>                        
        <td> <?php echo $value["InHospitalDate"] ?></td>    
        <td class="td_field">出生年月</td>                        
        <td> <?php echo $value["DateOfBirth2"] ?></td>    
        <td class="td_field"></td>                        
        <td></td>    
        <td class="td_field"></td>                        
        <td></td>    
    </tr>
</table>

</div>
</body>
</html>

<?php
}