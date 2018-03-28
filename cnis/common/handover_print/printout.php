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
    $sql = getSql();
    $recipe = $db->fetch_all($sql);

    $sql = "select SysConfigValue from sysconfig where SysConfigCode = 'SystemPrint'";
    $printTitle = $db->fetch_var($sql);
    ?>
<div id="divRecipe">
<h3 style="text-align:center;margin:0px;">
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
           echo "<td>".getCellValue($syscodevalue["SysCodeName"], $value["adviceDetails"])."</td>";
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

function getCellValue($takeOrder, $arrStr){
    $result = "";
    $arr = explode(",",$arrStr);
    foreach ($arr as $key => $value) {
        $arr2 = explode("#",$value);
        if($arr2[0] == $takeOrder){
            $result.=$arr2[1];
        }
    }
    return $result;
}

function getSql()
{

    $adviceDate = '2018-03-28';

    return "      
		select advicedate, DepartmentName, BedCode, PatientName,  GROUP_CONCAT(TakeOrder, '#', singleName,'（',spc,' * ',AdviceAmount,'）', '<br/>') adviceDetails,col1,col2 singleName from 
                (
                SELECT
                                    date_format(na.AdviceDate, '%Y-%m-%d') AS AdviceDate,
                                    -- 医嘱日期		
                                    b.DepartmentName,
                                     -- 科室
                                    b.BedCode,
                                     -- 床位号						
                                    b.PatientName,
                                     -- 姓名							 
                                    #nas.NutrientAdviceSummaryNo AS NutrientAdvice_DBKey,
                                    -- 医嘱单号							 
                                    nad.TakeOrder,
                                     -- 时间段		
                                        CASE
                                    WHEN nad.Medicine_DBKey IS NOT NULL
                                    AND nad.Medicine_DBKey <> 0 THEN
                                        m.MedicineName
                                    ELSE
                                        rap.RecipeAndProductName
                                    END singleName,
                                     -- 单品名称
                                    sum(nad.AdviceAmount) AdviceAmount,
                                     -- 数量
                                    nad.Specification spc,
                                     -- 规格							
                                    '' col1,
                                    '' col2/*,
                                    nad.NutrientProductCompleteNo,							 
                                    -- 成品单号
                                    nad.PreparationName,
                                    -- 成品名称
                                    date_format(
                                        nad.PreparationData,
                                        '%Y-%m-%d'
                                    ) AS PreparationData,
                                    -- 配置日期
                                nad.AdviceAmount as totle,
                                 -- 总量
                                u3.UserName PreparationPerson,
                                 -- 制剂员
                                u1.UserName PreparationID,
                                 -- 配送员
                                u2.UserName HandoverPeople, -- 接收员
                                CASE WHEN u1.UserName IS NOT NULL AND u1.UserName <> '' THEN
                                    '已配送'
                                ELSE
                                    '未配送'
                                END state */
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
                                LEFT JOIN (
                                    SELECT
                                        syscode,
                                        syscodename
                                    FROM
                                        syscode
                                    WHERE
                                        SystemCodeTypeName = 'doTime'
                                    OR SystemCodeTypeName = 'ENTime'
                                ) w ON nad.AdviceDoTimeSegmental = w.syscode
                                WHERE nad.RefundStatus>=0 
                                     and date_format(na.AdviceDate , '%Y-%m-%d') = '2018-03-28'  group by advicedate, DepartmentName, BedCode, PatientName, singleName
                ) tb
                group by advicedate, DepartmentName, BedCode, PatientName
                ORDER BY DepartmentName,PatientName,singleName";
}
