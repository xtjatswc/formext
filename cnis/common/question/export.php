<?php
require "../../../autoload.php";

global $db;

//http://localhost/formext/cnis/route.php?opt=question/export&d=2018-01-01
$date = '2018-01-01';
if(is_array($_GET)&&count($_GET)>0)//先判断是否通过get传值了
{
	if(isset($_GET["d"]))//是否存在"d"的参数
	{
		$date=$_GET["d"];//存在
	}
}

$sql = "select distinct g.DepartmentName 科室名称, b.HospitalizationNumber 住院号, c.PatientName 患者姓名,case c.Gender when 'M' then '男' else '女' end 性别,
b.Height 身高,b.Weight 体重,ROUND(b.weight / (b.Height * b.Height) * 10000, 2) BMI,
c.Age 年龄,a.ScreeningDate 筛查日期, f.propertyName 问卷类型, case f.propertyValue when 1 then  a.NSR2002Score else a.PGSGAScore end 得分,
h.DiseaseName 诊断
 from patientquestionnaire a 
inner join patienthospitalizebasicinfo b on a.PatientHospitalize_DBKey = b.PatientHospitalize_DBKey
inner join patientbasicinfo c on b.PATIENT_DBKEY = c.PATIENT_DBKEY
inner join patientquestion d on d.PatientQuestionnaire_DBKey = a.PatientQuestionnaire_DBKey
inner join questiondetail e on d.QuestionnaireQuestion_DBKey = e.QuestionnaireQuestion_DBKey
inner join questiondetailtype f on f.propertyValue = e.QuestionProperty
inner join department g on g.Department_DBKey = b.Department_DBKey
inner join diseaseicd10 h on h.Disease_DBKEY = b.Disease_DBKEY
where a.ScreeningDate >= '$date'
order by a.ScreeningDate desc";
$recipeRecords = $db->fetch_all($sql);
?>
<style id="style1">
body {font-family:微软雅黑;}
table {width:100%}
td,th {font-size: 10.5pt;padding:3px;}
table {width:70%;border-collapse:collapse;}
table td{text-align:left;border:1px solid black;}
table th {
	border-width: 1px;
	padding: 2px;
	border-style: solid;
	border-color: #666666;
	background-color: #dedede;
	text-align:center;
}
</style>
<table class="adviceList">
<tr>
    <th>科室名称</th>
    <th>住院号</th>
    <th>患者姓名</th>
    <th>性别</th>
    <th>身高</th>
    <th>体重</th>
    <th>BMI</th>
    <th>年龄</th>
    <th>筛查日期</th>
    <th>问卷类型</th>
    <th>得分</th>
    <th>诊断</th>
</tr>
<?php
foreach ($recipeRecords as $key => $value) {
	
    echo "    
    <tr>
    <td>".$value["科室名称"]."</td>
    <td>".$value["住院号"]."</td>
    <td>".$value["患者姓名"]."</td>
    <td>".$value["性别"]."</td>
    <td>".$value["身高"]."</td>
    <td>".$value["体重"]."</td>
    <td>".$value["BMI"]."</td>
    <td>".$value["年龄"]."</td>
    <td>".$value["筛查日期"]."</td>
    <td>".$value["问卷类型"]."</td>
    <td>".$value["得分"]."</td>
    <td>".$value["诊断"]."</td>
    </tr>";
}
?>
</table>


