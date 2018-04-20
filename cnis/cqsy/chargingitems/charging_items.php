<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "收费项目点选列表",
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
    </style>
    <?php
}

//javascript
function randerJavascriptCallBack()
{
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<script language="javascript" type="text/javascript" src="charging_items.js?v=<?php echo $version ?>"></script>
    <?php
}


//body
function randerBodyCallBack()
{
    ?>
    <div style="text-align:center;"><input type="button" value="保存对应关系" onclick="charging.saveRelation()" /></div>
    <font id="productName" color="blue"></font>
    <br/>
    <br/>
    收费项目列表：
    <?php
    global $db;
    $sql = "select * from chargingitems";
    $recipe = $db->fetch_all($sql);
    echo "<ul>";
    foreach ($recipe as $key => $value) {    
       echo "<li><input type='checkbox' name='checkbox_charging_item' id='checkbox_charging_item_".$value["ChargingItemID"]."' ChargingItemID='".$value["ChargingItemID"]."'/><label for='checkbox_charging_item_".$value["ChargingItemID"]."'>".$value["ChargingItemName"]."</label></li>";
    }
    echo "</ul>";
    ?>

<?php

}    



