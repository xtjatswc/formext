<?php
require "../autoload.php";

//window.open("../../../formext/cnis/route.php?opt=recipe_print_zy/printout&recipeNo="+dbk);

$clientFlag = $globalCfg["clientFlag"];
if ($clientFlag == "") {
    $clientFlag = "common";
}

$opt=$_GET["opt"];

$url = "$clientFlag/$opt.php";
$para = str_replace("opt=$opt&", "", $_SERVER['QUERY_STRING']);
$para = str_replace("opt=$opt", "", $para);

if(file_exists($url)){
    header("location: $url?".$para);
}else{
    header("location: common/$opt.php?".$para);
}

exit;
