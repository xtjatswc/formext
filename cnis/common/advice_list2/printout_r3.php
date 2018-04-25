<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "物料配制清单列表打印",
    'version' => $globalCfg["version"], //系统版本，变动时，js等缓存文件也会刷新
    'debug' => $globalCfg["debug"],
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack()
{
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<!-- <link id="cssPrint" rel="stylesheet" type="text/css" media="screen" href="printLabel.css?v=<?php echo $version ?>" />     -->
	<link id="cssPrint" rel="stylesheet" type="text/css" href="printout-v1.0.2.css" />
    <?php
}

//javascript
function randerJavascriptCallBack()
{
    global $globalCfg;
    $version = form_rander\page::$_pageCfg["version"];
    ?>
    <script language="javascript" type="text/javascript" src="printout_r3.js?v=<?php echo $version ?>"></script>
    <script type="text/javascript">
        pageExt.cnisPath="../../../../<?php echo $globalCfg["cnisDir"] ?>/";  
    </script>
    <?php
}

//body
function randerBodyCallBack()
{
    ?>
    <div>
        <input type="button" value="检测打印插件" onclick="util.CheckLodopIsInstall()" />
        <input type="button" value="设置" onclick="printout.printSetting()" />        
        <input type="button" value="打印维护" onclick="printout.printSetup()" />
        <input id="btnPrint" type="button" value="打印" onclick="printout.print()" />        
        <div style="display:none">
            <input type="button" value="打印设计" onclick="printout.printDesign()" />
            <input type="button" value="打印预览" onclick="printout.preview()" />
        </div>
    </div>
    <br/>
    <font id="printerSel"></font>
    打印机:      
    <font id="printerName" color="blue"></font>&nbsp;&nbsp;<font id="lsMsg" color="red"></font>
    <font id="labelTip" color="red"></font>
<?php
    global $db;
    $sql = "select SysConfigValue from sysconfig where SysConfigCode = 'SystemPrint'";
    $printTitle = $db->fetch_var($sql);
    $type = $_GET["type"];
?>
<div id="divRecipe">
<table id="tblAdviceList" class="gridtable">
    <thead>    
        <tr>
        <th style="text-align:center;margin-top:10px;font-size:16pt;font-weight: bold;border:none;background-color: white;" colspan="11">
        <?php echo $printTitle ?> 物料配制清单列表
        </th>
        </tr>
        <tr>
            <?php
            if($type == 1 || $type == 2){
                echo "<th>病区（科室）</th>";
            }

            if($type == 1){
                echo "<th>执行时间</th>";
            }
            ?>            
            <th>制剂名称</th>
            <th>规格</th>
            <th>总量</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <?php
}
?>
</table>
</div>
