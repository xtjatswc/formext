<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "住院医嘱单打印",
    'version' => $globalCfg["version"], //系统版本，变动时，js等缓存文件也会刷新
    'debug' => $globalCfg["debug"],
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack()
{
    ?>
	<style id="style1">
    body {font-family:微软雅黑;}
    table {width:100%}
    td,th {font-size: 10.5pt;padding:3px;}
    h3,h4 {margin:0px;text-align:center;}
    table.gridtable {width:auto;}
    table.gridtable td{padding:5px;}
    input[type='text'] {width:50px;text-align:center;}
    table.adviceList {width:100%;border-collapse:collapse;}
    table.adviceList td{text-align:left;border:1px solid black;}
    table.adviceList th {
        border-width: 1px;
        padding: 2px;
        border-style: solid;
        border-color: #666666;
        background-color: #dedede;
        text-align:center;
    }
    </style>
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
        <input id="btnSave" type="button" value="保存预览" onclick="printout.save()" />
        <input id="btnPrint" type="button" value="打印" onclick="printout.print()" />
        <div style="display:none">
            <input type="button" value="打印设计" onclick="printout.printDesign()" />
            <input type="button" value="打印预览" onclick="printout.preview()" />
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
//取单位
    $sql = "select MeasureUnit_DBKey '0',MeasureUnitName '1' from measureunit";
    $unitDict = $db->fetch_cols($sql);

    $sql = "select d.RecipeAndProductName,c.Unit, c.UnitKey, c.SingleMetering, e.SysCodeName,e.SysCodeShortName,d.NutrientProductSpecification,d.MeasureUnit_DBKey,d.minUnit_DBKey,d.menuType,d.BaseUnit_DBKey,c.totalMoney,d.MinNum,d.wrapperType,c.NutrientAdviceDetail_DBKEY,d.RecipeAndProduct_DBKey,f.SysCodeName PreparationMode,
cast(c.AdviceAmount as SIGNED INTEGER) AdviceAmount, c.CurrentPrice,DATE_FORMAT(a.AdviceBeginDate, '%Y-%m-%d') AdviceBeginDate,DATE_FORMAT(a.AdviceEndDate, '%Y-%m-%d') AdviceEndDate,datediff(a.AdviceEndDate, a.AdviceBeginDate) + 1 AdviceDays from nutrientadvicesummary a
inner join nutrientadvice b on a.NutrientAdviceSummary_DBKey = b.NutrientAdviceSummary_DBKey
inner join nutrientadvicedetail c on b.NutrientAdvice_DBKey = c.NutrientAdvice_DBKey
inner join recipeandproduct d on d.RecipeAndProduct_DBKey = c.RecipeAndProduct_DBKey
left join syscode e on e.SysCode = c.AdviceDoTimeSegmental and e.SystemCodeTypeName = 'ENTime'
left join syscode f on f.SysCode = c.PreparationMode and f.SystemCodeTypeName = 'PreparationMode'
where a.NutrientAdviceSummary_DBKey = $recipeNo  and c.CreateProgram is not null  order by c.NutrientAdviceDetail_DBKEY";
    $recipeRecords = $db->fetch_all($sql);
    ?>
医嘱单号：<?php echo $recipeNo ?> &nbsp;&nbsp;&nbsp;
<?php
if ($recipeRecords[0]["AdviceDays"] == "1") {
        ?>
医嘱日期：<?php echo $recipeRecords[0]["AdviceBeginDate"] ?>&nbsp;&nbsp;&nbsp;
<?php
} else {
        ?>
开始日期：<?php echo $recipeRecords[0]["AdviceBeginDate"] ?>&nbsp;&nbsp;&nbsp;结束日期：<?php echo $recipeRecords[0]["AdviceEndDate"] ?>&nbsp;&nbsp;&nbsp;
    <?php
}
    ?>
共<font id="theAdviceDays"><?php echo $recipeRecords[0]["AdviceDays"] ?></font>天
    <table class="gridtable ">
        <thead>
            <tr>
            <th>品名</th>
            <th>规格</th>
            <th>数量</th>
            <th>制剂方式</th>
            <th>频次</th>
            <th>总量</th>
            <th>收费项目</th>
            <th>规格</th>
            <th>单价</th>
            <th>数量</th>
            <th>单位</th>
            <th>金额</th>
            </tr>
        </thead>
        <tbody>
        <?php
foreach ($recipeRecords as $key => $value) {
        $specification = ""; //规格
        $array = explode("_", $value["UnitKey"]);

        if ($array[1] == "A" && $value["menuType"] == "2") {
            //组合配方

            $specification = $value["NutrientProductSpecification"] . " " . $unitDict[$value["MeasureUnit_DBKey"]] . "/" . $unitDict[$value["BaseUnit_DBKey"]];
        } else if ($array[1] == "A" && $value["menuType"] == "1") {
            //倍康素 箱
            $specification = $value["MinNum"] . " " . $unitDict[$value["minUnit_DBKey"]] . "/" . $unitDict[$value["BaseUnit_DBKey"]];
        } else if ($array[1] == "B") {
            //倍康素 罐
            $specification = $value["NutrientProductSpecification"] . " " . $unitDict[$value["MeasureUnit_DBKey"]] . "/" . $unitDict[$value["minUnit_DBKey"]];
        } else if ($array[1] == "C" || $array[1] == "E") {
            //佳膳 拆
            $specification = $value["NutrientProductSpecification"] . " " . $unitDict[$value["MeasureUnit_DBKey"]] . "/" . $unitDict[$value["minUnit_DBKey"]];
        }

        //总量
        $totalNum = 0;
        if ($value["PreparationMode"] == "自助冲剂") {
            $totalNum = $value["AdviceAmount"];
        } else {
            $totalNum = $value["AdviceAmount"] * $value["SysCodeShortName"];
        }

        echo "<tr NutrientAdviceDetail_DBKEY=" . $value["NutrientAdviceDetail_DBKEY"] . " RecipeAndProduct_DBKey=" . $value["RecipeAndProduct_DBKey"] . ">
            <td>" . $value["RecipeAndProductName"] . "</td>
            <td>" . $specification . "</td>
            <td>" . $value["AdviceAmount"] . " " . $value["Unit"] . "</td>
            <td>" . $value["PreparationMode"] . "</td>
            <td>" . $value["SysCodeName"] . "</td>
            <td>" . $totalNum . " " . $value["Unit"] . "</td>
            <td>" . getChargingItems($value["RecipeAndProduct_DBKey"], $value["NutrientAdviceDetail_DBKEY"]) . "</td>
            <td>
                <select name='select_spec' id='select_spec_" . $value["NutrientAdviceDetail_DBKEY"] . "' >
                </select>
            </td>
            <td><input id='text_price_" . $value["NutrientAdviceDetail_DBKEY"] . "' type='text' value='' disabled='disabled'/></td>
            <td><input name='text_num' id='text_num_" . $value["NutrientAdviceDetail_DBKEY"] . "' type='text' value='1'/></td>
            <td><input name='text_unit' id='text_unit_" . $value["NutrientAdviceDetail_DBKEY"] . "' type='text' value='' disabled='disabled'/></td>
            <td><input name='text_money' id='text_money_" . $value["NutrientAdviceDetail_DBKEY"] . "' type='text' value='' disabled='disabled'/></td>
            </tr>
            ";
    }
    ?>
        <tr><td colspan='12' style="text-align:right"><font id="label_totalMoney" color="blue">总金额：_ 元</font></td></tr>
        </tbody>
    </table>
    <br/>
    <br/>
    <br/>
    <div  style="border:1px solid black; width:750px;">
        <div id="divRecipe" style="text-align:left;padding:5px"></div>
    </div>
<?php

}

//查找收费项目
function getChargingItems($RecipeAndProduct_DBKey, $NutrientAdviceDetail_DBKEY)
{
    global $db;
    $select = "<select name='select_chargingitem' id='select_chargingitem_" . $NutrientAdviceDetail_DBKEY . "'>";
    $sql = "select b.* from chargingitemsrelation a inner join chargingitems b on a.ChargingItemID = b.ChargingItemID  where RecipeAndProduct_DBKey = $RecipeAndProduct_DBKey";
    $chargingItems = $db->fetch_all($sql);

    foreach ($chargingItems as $key => $value) {
        //单一规格的项目，在名称后面加上规格，便于识别
        $ChargingItemName = $value["ChargingItemName"];
        if(strpos($value["ChargingItemSpec"],'#')===false){
            $ChargingItemName = $ChargingItemName . " " . $value["ChargingItemSpec"];
        }

        $select .= "<option value='" . $value["ChargingItemID"] . "' ChargingItemID='" . $value["ChargingItemID"] . "' ChargingItemName='".$value["ChargingItemName"]."' spec='" . $value["ChargingItemSpec"] . "' price1='" . $value["ChargingItemPrice1"] . "' price2='" . $value["ChargingItemPrice2"] . "' unit='" . $value["ChargingItemUnit"] . "' >" . $ChargingItemName . "</option>";
    }
    $select .= "</select>";
    return $select;
}

?>
