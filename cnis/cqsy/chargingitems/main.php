<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "制剂-收费项关联",
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
        .wrapper{
			padding: 10px;
			margin: 0 auto;
			width: 900px;
			min-height: 500px;
			border:1px solid #ccc;
			border-radius: 5px;
		}
		.left{
			float: left;
		}
		.right{
			float: left;
			margin-left: 20px;
		}
    </style>
    <?php
}

//javascript
function randerJavascriptCallBack()
{
    $version = form_rander\page::$_pageCfg["version"];
    ?>
    <?php
}

//body
function randerBodyCallBack()
{
?>
<div class="wrapper">
    <div class="left">
        <iframe src="recipe_product.php" frameborder="1" width="200" height="500"></iframe>
    </div>
    <div class="right">
        <iframe src="charging_items.php" frameborder="1" width="300" height="500"></iframe>
    </div>
</div>
<?php

}    

