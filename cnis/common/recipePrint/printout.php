<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "门诊医嘱单打印",
    'version' => $globalCfg["version"], //系统版本，变动时，js等缓存文件也会刷新
    'debug' => $globalCfg["debug"],
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack(){
    ?>
	<style id="style1">
    body {font-family:微软雅黑;}
    table {width:100%}
    td,th {font-size: 10.5pt;padding:3px;text-align:left}
    h3,h4 {margin:0px;}
    </style>
    <?php
}

//javascript
function randerJavascriptCallBack(){
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<script language="javascript" type="text/javascript" src="printout.js?v=<?php echo $version ?>"></script>    
    <?php
}

//body
function randerBodyCallBack(){    
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
    <br/>
    <font id="labelTip" color="red"></font>
    <br/>    
<?php
global $db;

$sql = "select SysConfigValue from sysconfig where SysConfigCode = 'SystemPrint'";
$printTitle = $db->fetch_var($sql);

$recipeNo = $_GET["recipeNo"];
$sql = "select a.NutrientAdviceSummary_DBKey, DATE_FORMAT(a.CreateTime, '%Y-%m-%d') CreateTime, b.HospitalizationNumber, c.PatientName, c.PatientNo,
c.Age, case c.Gender when 'M' then '男' else '女' end Gender,
 d.DepartmentName, e.UserName, b.DiseaseListVal from nutrientadvicesummary a
inner join patienthospitalizebasicinfo b on a.PatientHospitalize_DBKey = b.PatientHospitalize_DBKey
inner join patientbasicinfo c on b.PATIENT_DBKEY = c.PATIENT_DBKEY
inner join department d on d.Department_DBKey = b.Department_DBKey
left join user e on e.User_DBKey = a.CreateBy
where a.NutrientAdviceSummary_DBKey = $recipeNo";
$baseInfo = $db->fetch_row($sql);

?>
<div style="width:800px;border:1px solid black;padding:5px">
    <div id="divRecipe" style="padding:10px;padding-top:5px">
    <div style="text-align:center;font-size:15pt"><?php echo $printTitle ?></div>
    <h3 style="text-align:center">
    医疗处方单
    </h3>
    <table>
        <tr>
            <td>ID号：<?php echo $baseInfo["HospitalizationNumber"] ?></td>
            <td>病案号：<?php echo $baseInfo["PatientNo"] ?></td>
            <td>科别：<?php echo $baseInfo["DepartmentName"] ?></td>
        </tr>
        <tr>
            <td>姓名：<?php echo $baseInfo["PatientName"] ?></td>
            <td>性别：<?php echo $baseInfo["Gender"] ?></td>
            <td>年龄：<?php echo $baseInfo["Age"] ?>岁</td>
        </tr>
    </table>
    <hr/>
    疾病及诊断：<?php echo $baseInfo["DiseaseListVal"] ?>
    <hr/>
    <table>
        <tr>
            <th>药品名称</th>
            <th>规格</th>
            <th>单次剂量</th>
            <th>频次</th>
            <th>用法</th>
            <th>数量</th>
            <th>单价</th>
            <th>金额</th>
        </tr>
        <?php
$sql = "select d.RecipeAndProductName, c.Specification, c.SingleMetering, e.SysCodeName,
c.Directions, c.AdviceAmount, c.CurrentPrice, c.AdviceAmount * c.CurrentPrice TotalMoney from nutrientadvicesummary a 
inner join nutrientadvice b on a.NutrientAdviceSummary_DBKey = b.NutrientAdviceSummary_DBKey
inner join nutrientadvicedetail c on b.NutrientAdvice_DBKey = c.NutrientAdvice_DBKey
inner join recipeandproduct d on d.RecipeAndProduct_DBKey = c.RecipeAndProduct_DBKey
left join syscode e on e.SysCode = c.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
where a.NutrientAdviceSummary_DBKey = $recipeNo";
$recipeRecords = $db->fetch_all($sql);

        foreach ($recipeRecords as $key => $value) {
            echo "<tr>
            <td>".$value["RecipeAndProductName"]."</td>
            <td>".$value["Specification"]."</td>
            <td>".$value["SingleMetering"]."</td>
            <td>".$value["SysCodeName"]."</td>
            <td>".$value["Directions"]."</td>
            <td>".$value["AdviceAmount"]."</td>
            <td>".$value["CurrentPrice"]."</td>
            <td>".$value["TotalMoney"]."</td>
            </tr>";   
        }       

        $remaining = 5 - count($recipeRecords);
        if($remaining > 0){
            for($i = 0; $i < $remaining; $i++){
                echo "<tr><td>&nbsp;	</td></tr>
                ";
            }
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
            <td>药费：</td>
            <td>审核/收费：</td>
            <td>审核/调配：</td>
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
</div>
    <?php
}