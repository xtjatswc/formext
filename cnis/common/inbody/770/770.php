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
	<style id="style1">
    table,td,th {border-width: 1px;border:1px solid black;border-collapse: collapse;font-size: 8pt;padding:1px;}
    body{text-align: center;}
    </style>
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
        <input type="button" value="打印预览" onclick="inbody770.preview()" />
        <input id="btnPrint" type="button" value="打印" onclick="inbody770.print()" />
        <div style="display:none">
            <input type="button" value="打印设计" onclick="inbody770.printDesign()" />
        </div>
    </div>
    <br/>
    打印机:      
    <font id="printerName" color="blue"></font>&nbsp;&nbsp;<font id="lsMsg" color="red"></font>
    <br/>
    <font id="labelTip" color="red"></font>
    <br/>    

<div style="display:none">
<?php
    global $db;
    //取医院名称
    $sql = "select SysConfigValue from sysconfig where SysConfigCode = 'SystemPrint'";
    $printTitle = $db->fetch_var($sql);
?>
<div id="reportTitle" style="font-size:12pt;font-weight:bold;text-align: center;">
<?php echo $printTitle;?><br/>
临床营养科<br/>
人体成分测定报告<br/>
</div>

<?php
$arr = array(
    1 => "1Khz",
    5 => "5Khz",
    50 => "50Khz",
    250 => "250KHz",
    500 => "500KHz",
    1000 => "1000KHz"
);
?>
<div id="divDzk">
<table>
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
    <td>{<?php echo $key?>khz-RA Impedance}</td>
    <td>{<?php echo $key?>khz-LA Impedance}</td>
    <td>{<?php echo $key?>khz-TR Impedance}</td>
    <td>{<?php echo $key?>khz-RL Impedance}</td>
    <td>{<?php echo $key?>khz-LL Impedance}</td>
</tr>
<?php 
}
?>
</table>
</div>

</div>

    <?php
}