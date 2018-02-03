
<?php
require "../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\",
    'libPath' => "..\\..\\form_rander\\",
    'Title' => "住院患者信息",
    'version' => "6", //系统版本，变动时，js等缓存文件也会刷新
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack(){
    //echo "css";
}

//javascript
function randerJavascriptCallBack(){
    //echo "javascript";

}

//body
function randerBodyCallBack(){
    //echo "helloworld";
}


