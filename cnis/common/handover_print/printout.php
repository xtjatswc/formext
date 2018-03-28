<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "交接单打印",
    'version' => $globalCfg["version"], //系统版本，变动时，js等缓存文件也会刷新
    'debug' => $globalCfg["debug"],
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack()
{
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<!-- <link id="cssPrint" rel="stylesheet" type="text/css" media="screen" href="printLabel.css?v=<?php echo $version ?>" />     -->
	<link id="cssPrint" rel="stylesheet" type="text/css" href="printout.css" />
    <?php
}

//javascript
function randerJavascriptCallBack()
{
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<script language="javascript" type="text/javascript" src="printout.js?v=<?php echo $version ?>"></script>
    <?php
}

//body
function randerBodyCallBack()
{
    ?>
    <div>
        <input type="button" value="检测打印插件" onclick="util.CheckLodopIsInstall()" />
        <input type="button" value="设置" onclick="printout.printSetting()" />
        <input type="button" value="打印维护" onclick="printout.printSetup()" />
        <div style="display:none2">
            <input type="button" value="打印设计" onclick="printout.printDesign()" />
            <input type="button" value="打印预览" onclick="printout.preview()" />
            <input id="btnPrint" type="button" value="打印" onclick="printout.print()" />
        </div>
    </div>
    <br/>
    打印机:      
    <font id="printerName" color="blue"></font>&nbsp;&nbsp;<font id="lsMsg" color="red"></font>
    <font id="labelTip" color="red"></font>

    <?php

    global $db;
    $adviceDate = $_GET["adviceDate"];
    $sql = getSql($adviceDate);
    $recipe = $db->fetch_all($sql);

    $sql = getDetailSql($adviceDate);
    $recipeDetail = $db->fetch_all($sql);

    $sql = "select SysConfigValue from sysconfig where SysConfigCode = 'SystemPrint'";
    $printTitle = $db->fetch_var($sql);
    ?>
<div id="divRecipe">
<h3 style="text-align:center;margin-top:10px;">
    <?php echo $printTitle ?> 医嘱交接单
</h3>
<table>
    <tr>
        <td>医嘱日期</td>
        <td>科室</td>
        <td>床号</td>
        <td>患者姓名</td>
        <?php
        $sql = "select * from syscode where SystemCodeTypeName = 'doTime' order by OrderBy";
        $syscode = $db->fetch_all($sql);
        foreach ($syscode as $syscodekey => $syscodevalue) {
           echo "<td>".$syscodevalue["SysCodeName"]."</td>";
        }
        ?>
    </tr>
<?php
foreach ($recipe as $key => $value) {
        ?>
    <tr>
        <td><?php echo $value["advicedate"] ?></td>
        <td><?php echo $value["DepartmentName"] ?></td>
        <td><?php echo $value["BedCode"] ?></td>
        <td><?php echo $value["PatientName"] ?></td>
        <?php
        foreach ($syscode as $syscodekey => $syscodevalue) {
           echo "<td>".getCellValue($recipeDetail, $syscodevalue["SysCodeName"], $value["NutrientAdviceSummary_DBKey"])."</td>";
        }
        ?>            
    </tr>
<?php
}
}
?>
</table>
</div>
<?php

function getCellValue($recipeDetail, $takeOrder, $NutrientAdviceSummary_DBKey){
    foreach ($recipeDetail as $key => $value) {
        if($value["NutrientAdviceSummary_DBKey"] == $NutrientAdviceSummary_DBKey && $value["TakeOrder"] == $takeOrder){
            return $value["RecipeAndProductName"];
        }
    }
    return "";
}

function getSql($adviceDate)
{
    return "      
	SELECT
	NutrientAdviceSummary_DBKey,
	advicedate,
	DepartmentName,
	BedCode,
	PatientName 
FROM
	(
SELECT
	nas.NutrientAdviceSummary_DBKey,
CASE	
	WHEN nas.AdviceBeginDate = nas.AdviceEndDate THEN
	date_format( nas.AdviceBeginDate, '%Y-%m-%d' ) ELSE CONCAT(
	date_format( nas.AdviceBeginDate, '%Y-%m-%d' ),
		' ~ ',
		date_format( nas.AdviceEndDate, '%Y-%m-%d' ),
		' 共',
		DATEDIFF( nas.AdviceEndDate, nas.AdviceBeginDate ) + 1,
		'天' 
		) 
	END AS AdviceDate,-- 医嘱日期
	b.DepartmentName,-- 科室
	b.BedCode,-- 床位号
	b.PatientName -- 姓名
	
FROM
	nutrientadvicesummary nas
	RIGHT JOIN nutrientadvice na ON nas.NutrientAdviceSummary_DBKey = na.NutrientAdviceSummary_DBKey
	RIGHT JOIN nutrientadvicedetail nad ON nad.NutrientAdvice_DBKey = na.NutrientAdvice_DBKey
	LEFT JOIN recipeandproduct rap ON rap.RecipeAndProduct_DBKey = nad.RecipeAndProduct_DBKey
	LEFT JOIN medicine m ON m.Medicine_DBKey = nad.Medicine_DBKey
	LEFT JOIN (
	SELECT
		Bed.BedCode AS BedCode,
		d.DepartmentName AS DepartmentName,
		phb.PatientHospitalize_DBKey AS PatientHospitalize_DBKey,
		pb.PatientName AS PatientName,
		phb.HospitalizationNumber,
		d.Department_DBKey AS DepartmentCode 
	FROM
		patienthospitalizebasicinfo phb
		LEFT JOIN bednumber bed ON phb.BedNumber_DBKey = Bed.BedNumber_DBKey
		LEFT JOIN department d ON d.Department_DBKey = phb.Department_DBKey
		LEFT JOIN patientbasicinfo pb ON phb.PATIENT_DBKEY = pb.PATIENT_DBKEY 
	) b ON b.PatientHospitalize_DBKey = nas.PatientHospitalize_DBKey
	LEFT JOIN USER u1 ON u1.User_DBKey = nad.PreparationID
	LEFT JOIN USER u2 ON u2.User_DBKey = nad.HandoverPeople
	LEFT JOIN USER u3 ON u3.User_DBKey = nad.PreparationPerson
	LEFT JOIN ( SELECT syscode, syscodename FROM syscode WHERE SystemCodeTypeName = 'doTime' OR SystemCodeTypeName = 'ENTime' ) w ON nad.AdviceDoTimeSegmental = w.syscode 
WHERE
	nad.RefundStatus >= 0 
	AND date_format( na.AdviceDate, '%Y-%m-%d' ) = '$adviceDate' 
	) tb 
GROUP BY
	NutrientAdviceSummary_DBKey,
	advicedate,
	DepartmentName,
	BedCode,
	PatientName 
ORDER BY
	DepartmentName,
	BedCode,
PatientName";
}

function getDetailSql($adviceDate){
    return "select nas.NutrientAdviceSummary_DBKey,nad.TakeOrder, GROUP_CONCAT(rap.RecipeAndProductName,' （',nad.Specification,' * ',nad.AdviceAmount, '）<br/>' separator '') RecipeAndProductName  from nutrientadvicedetail nad 
    inner JOIN nutrientadvice na ON na.NutrientAdvice_DBKey = nad.NutrientAdvice_DBKey
    inner join recipeandproduct rap ON rap.RecipeAndProduct_DBKey = nad.RecipeAndProduct_DBKey
    inner join nutrientadvicesummary nas on nas.NutrientAdviceSummary_DBKey = na.NutrientAdviceSummary_DBKey
    where nad.RefundStatus>=0 and date_format(na.AdviceDate , '%Y-%m-%d') = '$adviceDate'
    group by nas.NutrientAdviceSummary_DBKey, nad.TakeOrder";
}