<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "肠内制剂点选列表",
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
	<script language="javascript" type="text/javascript" src="recipe_product.js?v=<?php echo $version ?>"></script>
    <?php
}

//body
function randerBodyCallBack()
{
    ?>
    肠内制剂：
    <?php
    global $db;
    $sql = "select RecipeAndProduct_DBKey, RecipeAndProductName from recipeandproduct where RecipProductTableInsideType = 1 order by RecipeAndProductName";
    $recipe = $db->fetch_all($sql);
    echo "<ul>";
    foreach ($recipe as $key => $value) {    
       echo "<li><input type='radio' name='radio_recipe_product' id='".$value["RecipeAndProduct_DBKey"]."' productName='".$value["RecipeAndProductName"]."'/><label for='".$value["RecipeAndProduct_DBKey"]."'>".$value["RecipeAndProductName"]."</label></li>";
    }
    echo "</ul>";    
    ?>

<?php

}    

