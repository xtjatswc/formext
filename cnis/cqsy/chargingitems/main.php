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
*{ margin:0; padding:0}  
body, html{ height:100%; width:100%; overflow:hidden;} /*这个高度很重要*/  
#frametable .header{ height:40px; background:#ddd; border-bottom:2px solid #999;}  
#frametable .left{ width:250px; border-right:2px solid #999; background:#ddd; height:100%;}  
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
<table id="frametable" cellpadding="0" cellspacing="0" width="100%" height="100%" style="width: 100%;">  
    <tr>  
        <td colspan="2" height="40">  
            <div class="header">  
                <!-- header menu -->  
            </div>  
        </td>  
    </tr>  
    <tr>  
        <td valign="top" width="150" height="100%"> <!--这个高度很重要-->  
            <div class="left">
                <iframe src="recipe_product.php" frameborder="1" width="100%" allowtransparency="true" height="100%"  frameborder="0" scrolling="yes" style="overflow:visible;"></iframe>
            </div>  
        </td>  
        <td valign="top" width="100%" height="100%"> <!--这个高度很重要-->  
            <iframe id="iframe" name="main" src="charging_items.php" width="100%" allowtransparency="true" height="100%" frameborder="0" scrolling="yes" style="overflow:visible;"></iframe>  
        </td>  
    </tr>  
</table>  

<!-- <div class="wrapper">
    <div class="left">
        <iframe src="recipe_product.php" frameborder="1" width="300" height="600"></iframe>
    </div>
    <div class="right">
        <iframe src="charging_items.php" frameborder="1" width="1000" height="600"></iframe>
    </div>
</div> -->
<?php

}    

