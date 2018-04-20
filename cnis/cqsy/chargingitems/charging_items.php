<?php
require "../../../autoload.php";

form_rander\form::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "收费项目点选列表",
    'version' => $globalCfg["version"], //系统版本，变动时，js等缓存文件也会刷新
    'isPrintNo' => "0", //是否打印序号列
    'primaryKey' => "ChargingItemID", //主键，复选框对应的值
    'EnableDel' => "1", //是否启用删除按钮
    'pageSize' => 50, //每页显示记录条数
    'debug' => $globalCfg["debug"],
);

$form = new form_rander\form($db);

$form->_sqlCfg = array(
    'deleteSql' => "delete from chargingitems where ChargingItemID in ({0})", //删除sql
);

$form->_listColumnCfg = array(
    'ChargingItemID' => array('isDisplay' => '0','displayName' => 'ChargingItemID','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '0','editKey' => '', 'editSqlKey' => ''),
    'ChargingItemCode' => array('isDisplay' => '1','displayName' => '项目编码','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => '', 'editSqlKey' => ''),
    'ChargingItemName' => array('isDisplay' => '1','displayName' => '项目名称','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => '', 'editSqlKey' => ''),
    'ChargingItemSpec' => array('isDisplay' => '1','displayName' => '规格','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => '', 'editSqlKey' => ''),
    'ChargingItemUnit' => array('isDisplay' => '1','displayName' => '单位','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => '', 'editSqlKey' => ''),
    'ChargingItemPrice1' => array('isDisplay' => '1','displayName' => '单价1','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => '', 'editSqlKey' => ''),
    'ChargingItemPrice2' => array('isDisplay' => '1','displayName' => '单价2','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => '', 'editSqlKey' => ''),
    'SortNo' => array('isDisplay' => '1','displayName' => '排序编号','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => '', 'editSqlKey' => ''),
    'Enabled' => array('isDisplay' => '1','displayName' => '是否启用','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '0','editKey' => '', 'editSqlKey' => ''),

);

$form->_listDisplayCfg = array(
);

//Y-m-d H:i:s
$form->_searcher->_searchCfg = array(
);

$sql = 'select * from chargingitems order by SortNo asc '.$form->_pager->getLimit();

$rows = $form->randerForm($sql);
//$form->getColumns($rows);

function randerSearchCallBack(){
    // include_once("includeRanderSearchCallBack.php");
}

function randerSearchWhereCallBack($sql){
    // return include_once("includeRanderSearchWhereCallBack.php");
    return $sql;
}

function randerToolBarCallBack(){
    ?>
    
    <input type="button" value="打开" onclick="patientinfo.openInfo()"/>
<?php
}

function randerScriptCallBack(){
    echo '<script src="charging_items.js?v='.form_rander\form::$_pageCfg["version"].'"></script>';
}

function randerCellCallBack($row, $key, $value){

    return $value;
}


// //body
// function randerBodyCallBack()
// {
//     ?>
//     <div style="text-align:left;"><input type="button" value="保存对应关系" onclick="charging.saveRelation()" /></div>
//     <font id="productName" color="blue"></font>
//     <br/>
//     <br/>
//     收费项目列表：
//     <?php
//     global $db;
//     $sql = "select * from chargingitems";
//     $recipe = $db->fetch_all($sql);
//     echo "<ul>";
//     foreach ($recipe as $key => $value) {    
//        echo "<li><input type='checkbox' name='checkbox_charging_item' id='checkbox_charging_item_".$value["ChargingItemID"]."' ChargingItemID='".$value["ChargingItemID"]."'/><label for='checkbox_charging_item_".$value["ChargingItemID"]."'>".$value["ChargingItemName"]."</label></li>";
//     }
//     echo "</ul>";
//     ?>

// <?php

// }    



