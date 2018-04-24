<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "医嘱列表打印",
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
    <script language="javascript" type="text/javascript" src="printout.js?v=<?php echo $version ?>"></script>
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
    <?php
    global $printer;
    ?>
    <label for="SelPrinterSet">切换打印设置</label>
    <select name="SelPrinterSet" id="SelPrinterSet">
      <option disabled selected>当前报表的默认打印设置...</option>
      <optgroup disabled label="固定设置">
      <?php
        foreach ($printer as $key => $value) {
            if($key <= 100)
                echo "<option disabled value='$key'>$value</option>";
        }
      ?>
      </optgroup>
      <optgroup label="预留设置">
      <?php
        foreach ($printer as $key => $value) {
            if($key > 100)
                echo "<option value='$key'>$value</option>";
        }
      ?>
      </optgroup>
    </select>
    打印机:      
    <font id="printerName" color="blue"></font>&nbsp;&nbsp;<font id="lsMsg" color="red"></font>
    <font id="labelTip" color="red"></font>
<?php
    global $db;
    $sql = "select SysConfigValue from sysconfig where SysConfigCode = 'SystemPrint'";
    $printTitle = $db->fetch_var($sql);

?>
<div id="divRecipe">
<table id="tblAdviceList" class="gridtable">
    <caption>
        <h1 style="text-align:center;margin-top:10px;">
        <?php echo $printTitle ?> 肠内医嘱列表
        </h1>
    </capiton>
    <thead>    
        <tr>
            <th>医嘱日期</th>
            <th>病区（科室）</th>
            <th>床号</th>
            <th>姓名</th>
            <th>年龄</th>
            <th>住院号</th>
            <th>收费名称</th>
            <th>规格</th>
            <th>数量</th>
            <th>总量</th>
            <th>制剂名称</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
    <?php
}
?>
</table>
</div>
