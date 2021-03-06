
<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "标签打印",
    'version' => $globalCfg["version"], //系统版本，变动时，js等缓存文件也会刷新
    'debug' => $globalCfg["debug"],
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack(){
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<!-- <link id="cssPrint" rel="stylesheet" type="text/css" media="screen" href="printLabel.css?v=<?php echo $version ?>" />     -->
	<link id="cssPrint" rel="stylesheet" type="text/css" href="print.css" />    
    <?php
}

//javascript
function randerJavascriptCallBack(){
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<script language="javascript" type="text/javascript" src="printLabel.js?v=<?php echo $version ?>"></script>    
    <?php
}

//body
function randerBodyCallBack(){    
?>
<div>
    <input type="button" value="检测打印插件" onclick="util.CheckLodopIsInstall()" />
    <input type="button" value="设置" onclick="printLabel.printSetting()" />
    <input type="button" value="打印维护" onclick="printLabel.printSetup()" />
    <div style="display:none">
        <input type="button" value="打印设计" onclick="printLabel.printDesign()" />
        <input type="button" value="打印预览" onclick="printLabel.preview()" />
    </div>
</div>
<br/>
标签打印机:      
<font id="printerName" color="blue"></font>&nbsp;&nbsp;<font id="lsMsg" color="red"></font>
<br/>
<font id="labelTip" color="red"></font>

<p>
  <label for="spinnerPrintCopies">打印份数：</label>
  <input id="spinnerPrintCopies" name="spinnerPrintCopies" value="1" style="width:50px">
  <input id="btnPrint" type="button" value="加载中……" onclick="printLabel.print()" disabled="disabled" style="height:35px;width:150px;"/>    
</p>

<div id="divLabels" style="width:350px">
         
</div>

<?php
}    