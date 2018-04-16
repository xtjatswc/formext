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
    td,th {font-size: 10.5pt;padding:3px;}
    h3,h4 {margin:0px;}
    table.gridtable {width:auto;}
    table.gridtable td{padding:5px;}
    input[type='text'] {width:50px;text-align:center;}
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
        <input id="btnPrint" type="button" value="打印" onclick="printout.print()" />        
        <div style="display:none">
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

//取单位
$sql = "select MeasureUnit_DBKey '0',MeasureUnitName '1' from measureunit";
$unitDict = $db->fetch_cols($sql);

$sql = "select d.RecipeAndProductName,c.Unit, c.UnitKey, c.SingleMetering, e.SysCodeName,d.NutrientProductSpecification,d.MeasureUnit_DBKey,d.minUnit_DBKey,d.menuType,d.BaseUnit_DBKey,c.totalMoney,d.MinNum,d.wrapperType,f.ChargingItemName,f.ChargingItemPrice1,f.ChargingItemPrice2,f.ChargingItemPrice3,f.ChargingItemPrice4,c.NutrientAdviceDetail_DBKEY,
cast(c.AdviceAmount as SIGNED INTEGER) AdviceAmount, c.CurrentPrice from nutrientadvicesummary a 
inner join nutrientadvice b on a.NutrientAdviceSummary_DBKey = b.NutrientAdviceSummary_DBKey
inner join nutrientadvicedetail c on b.NutrientAdvice_DBKey = c.NutrientAdvice_DBKey
inner join recipeandproduct d on d.RecipeAndProduct_DBKey = c.RecipeAndProduct_DBKey
left join syscode e on e.SysCode = c.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
left join chargingitems f on f.RecipeAndProduct_DBKey = d.RecipeAndProduct_DBKey
where a.NutrientAdviceSummary_DBKey = $recipeNo order by d.RecipeAndProduct_DBKey";
$recipeRecords = $db->fetch_all($sql);

?>
    <table class="gridtable ">
        <thead>
            <tr>
            <th>品名</th>
            <th>规格</th>
            <th>数量</th>
            <th>收费项目</th>
            <th>数量</th>
            <th>单价A</th>
            <th>单价B</th>
            <th>单价C</th>
            <th>单价D</th>
            <th>金额</th>
            </tr>
        </thead>
        <tbody>
        <?php
        foreach ($recipeRecords as $key => $value) {
            $specification = "";//规格
            $array = explode("_", $value["UnitKey"]);
            if($array[1] == "A" &&  $value["menuType"] == "2"){
                //组合配方
                $specification = $value["NutrientProductSpecification"]." ".$unitDict[$value["MeasureUnit_DBKey"]]."/".$unitDict[$value["BaseUnit_DBKey"]];    
            }else if($array[1] == "A" &&  $value["menuType"] == "1"){          
                //倍康素 箱      
                $specification = $value["MinNum"]." ".$unitDict[$value["minUnit_DBKey"]]."/".$unitDict[$value["BaseUnit_DBKey"]];    
            }else if($array[1] == "B"){
                //倍康素 罐
                $specification = $value["NutrientProductSpecification"]." ".$unitDict[$value["MeasureUnit_DBKey"]]."/".$unitDict[$value["minUnit_DBKey"]];    
            }else if($array[1] == "C"){
                //佳膳 拆
                $specification = $value["NutrientProductSpecification"]." ".$unitDict[$value["MeasureUnit_DBKey"]]."/".$unitDict[$value["minUnit_DBKey"]];   
            }

            echo "<tr>
            <td>".$value["RecipeAndProductName"]."</td>
            <td>".$specification."</td>
            <td>".$value["AdviceAmount"]." ".$value["Unit"]."</td>
            <td>".$value["ChargingItemName"]."</td>
            <td><input type='text' value='1'/></td>
            <td><input name='radio_".$value["NutrientAdviceDetail_DBKEY"]."' id='radio_A_".$value["NutrientAdviceDetail_DBKEY"]."' type='radio' /><label for='radio_A_".$value["NutrientAdviceDetail_DBKEY"]."'>".$value["ChargingItemPrice1"]." 元</label></td>
            <td><input name='radio_".$value["NutrientAdviceDetail_DBKEY"]."' id='radio_B_".$value["NutrientAdviceDetail_DBKEY"]."' type='radio' /><label for='radio_B_".$value["NutrientAdviceDetail_DBKEY"]."'>".$value["ChargingItemPrice2"]." 元</label></td>
            <td><input name='radio_".$value["NutrientAdviceDetail_DBKEY"]."' id='radio_C_".$value["NutrientAdviceDetail_DBKEY"]."' type='radio' /><label for='radio_C_".$value["NutrientAdviceDetail_DBKEY"]."'>".$value["ChargingItemPrice3"]." 元</label></td>
            <td><input name='radio_".$value["NutrientAdviceDetail_DBKEY"]."' id='radio_D_".$value["NutrientAdviceDetail_DBKEY"]."' type='radio' /><label for='radio_D_".$value["NutrientAdviceDetail_DBKEY"]."'>".$value["ChargingItemPrice4"]." 元</label></td>
            <td>111</td>
            </tr>";
        }
        ?>            
        </tbody>
    </table>
<?php
global $db;
}
?>
