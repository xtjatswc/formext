<?php
require "../../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\..\\",
    'libPath' => "..\\..\\..\\..\\form_rander\\",
    'Title' => "InBody770报告纸打印",
    'version' => $globalCfg["version"], //系统版本，变动时，js等缓存文件也会刷新
    'debug' => $globalCfg["debug"],
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack(){
    ?>
	<style id="style1">table,td,th {border-width: 1px;border:1px solid black;border-collapse: collapse;font-size: 12pt;padding:3px;}</style>
    <?php
}

//javascript
function randerJavascriptCallBack(){
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<script language="javascript" type="text/javascript" src="770.js?v=<?php echo $version ?>"></script>    
    <?php
}

//body
function randerBodyCallBack(){    
    ?>
    <div>
        <input type="button" value="检测打印插件" onclick="util.CheckLodopIsInstall()" />
        <input type="button" value="设置" onclick="inbody770.printSetting()" />
        <input type="button" value="打印维护" onclick="inbody770.printSetup()" />
        <div style="display:none2">
            <input type="button" value="打印设计" onclick="inbody770.printDesign()" />
            <input type="button" value="打印预览" onclick="inbody770.preview()" />
            <input id="btnPrint" type="button" value="打印" onclick="inbody770.print()" />
        </div>
    </div>
    <br/>
    打印机:      
    <font id="printerName" color="blue"></font>&nbsp;&nbsp;<font id="lsMsg" color="red"></font>
    <br/>
    <font id="labelTip" color="red"></font>
    <br/>    
<?php
$arr = array(
    1 => "1KHz",
    5 => "5KHz",
    50 => "50KHz",
    250 => "250KHz",
    500 => "500KHz",
    "1M" => "1000KHz"
);
?>
<div id="divDzk" style="display:none">
<table>
<tr><caption><h3>生物电阻抗</h3></caption></tr>
<tr>
<th></th>
<th>右上肢</th>
<th>左上肢</th>
<th>躯干</th>
<th>右下肢</th>
<th>左下肢</th>
</tr>
<?php
foreach ($arr as $key => $value) {
?>
<tr>
    <td><?php echo $value?></td>
    <td>{RA<?php echo $key?>}</td>
    <td>{LA<?php echo $key?>}</td>
    <td>{TR<?php echo $key?>}</td>
    <td>{RL<?php echo $key?>}</td>
    <td>{LL<?php echo $key?>}</td>
</tr>
<?php 
}
?>
</table>
</div>
    <?php
}