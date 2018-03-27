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
    case 'recipe_print_zy':
        //住院医嘱单打印
        $recipeNo = $_GET["recipeNo"];
        header("location: $clientFlag/recipe_print_zy/printout.php?recipeNo=$recipeNo");
        break;
    
    default:
        # code...
        break;
}

exit;
