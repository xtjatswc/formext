<?php
require "../autoload.php";

/*
localhost\formext\cnis\route.php?opt=zy_recipe_print&recipeNo=1147
*/

$clientFlag = $globalCfg["clientFlag"];
if ($clientFlag == "") {
    $clientFlag = "common";
}

$opt=$_GET["opt"];
switch ($opt) {
    case 'zy_recipe_print':
        //住院医嘱单打印
        $recipeNo = $_GET["recipeNo"];
        header("location: $clientFlag\zy_recipe_print\printout.php?recipeNo=$recipeNo");
        break;
    
    default:
        # code...
        break;
}

exit;
