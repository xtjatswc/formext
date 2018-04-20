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
    global $db;
    $sql = "select * from chargingitems";
    $recipe = $db->fetch_all($sql);
    echo "<ul>";
    foreach ($recipe as $key => $value) {    
       echo "<li><input type='checkbox' name='checkbox_charging_item' id='checkbox_charging_item_".$value["ChargingItemID"]."' /><label for='".$value["ChargingItemID"]."'>".$value["ChargingItemName"]."</label></li>";
    }
    echo "</ul>";
    ?>

<?php

}    



