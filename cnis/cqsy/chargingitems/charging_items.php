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
    'editSql1' => "update chargingitems set {columnName} = :value where
    ChargingItemID = :ChargingItemID",

);

$form->_listColumnCfg = array(
    'ChargingItemID' => array('isDisplay' => '0','displayName' => 'ChargingItemID','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '0','editKey' => '', 'editSqlKey' => ''),
    'ChargingItemCode' => array('isDisplay' => '1','displayName' => '项目编码','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => 'ChargingItemID', 'editSqlKey' => 'editSql1'),
    'ChargingItemName' => array('isDisplay' => '1','displayName' => '项目名称','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => 'ChargingItemID', 'editSqlKey' => 'editSql1'),
    'ChargingItemSpec' => array('isDisplay' => '1','displayName' => '规格','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => 'ChargingItemID', 'editSqlKey' => 'editSql1'),
    'ChargingItemUnit' => array('isDisplay' => '1','displayName' => '单位','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => 'ChargingItemID', 'editSqlKey' => 'editSql1'),
    'ChargingItemPrice1' => array('isDisplay' => '1','displayName' => '单价1','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => 'ChargingItemID', 'editSqlKey' => 'editSql1'),
    'ChargingItemPrice2' => array('isDisplay' => '1','displayName' => '单价2','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => 'ChargingItemID', 'editSqlKey' => 'editSql1'),
    'SortNo' => array('isDisplay' => '1','displayName' => '排序编号','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '1','editKey' => 'ChargingItemID', 'editSqlKey' => 'editSql1'),
    'Enabled' => array('isDisplay' => '1','displayName' => '状态','width' => '','maxLength' => '','isPrint' => '1','allowEdit' => '0','editKey' => '', 'editSqlKey' => ''),

);

$form->_listDisplayCfg = array(
    'Enabled' => array('1' => '启用','0' => '禁用'),
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
    <br>
    <font id="productName" color="blue"></font>
    <div style="text-align:left;"><input type="button" value="保存对应关系" onclick="charging.saveRelation()" /></div>
<?php
}

function randerScriptCallBack(){
    echo '<script src="charging_items.js?v='.form_rander\form::$_pageCfg["version"].'"></script>';
}

function randerCellCallBack($row, $key, $value){

    return $value;
}




