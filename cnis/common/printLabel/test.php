
<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "标签打印",
    'version' => "1", //系统版本，变动时，js等缓存文件也会刷新
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack(){
    //echo "css";
}

//javascript
function randerJavascriptCallBack(){
    ?>
	<script language="javascript" type="text/javascript" src="printLabel.js"></script>    
    <?php
}

//body
function randerBodyCallBack(){
?>
<input type="button" value="打印设计" onclick="printLabel.printDesign()" />
<input type="button" value="打印维护" onclick="printLabel.printSetup()" />
<input type="button" value="打印预览" onclick="printLabel.preview()" />
<input type="button" value="打印" onclick="printLabel.print()" />
<table id="tblNutrientadvicedetail">
<thead>
<tr>
<td>品名</td>
<td>数量</td>
<td>规格</td>
<td>备注</td>
</tr>
</thead>
<tr>
<td>倍康素</td>
<td>2</td>
<td>250ml</td>
<td></td>
</tr>
</table>

<?php
}    