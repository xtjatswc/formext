
<?php
require "../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\",
    'libPath' => "..\\form_rander\\",
    'Title' => "打印机设置",
    'version' => $globalCfg["version"],
    'debug' => $globalCfg["debug"],
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack(){
}

//javascript
function randerJavascriptCallBack(){
    $version = form_rander\page::$_pageCfg["version"];
    $debug = form_rander\page::$_pageCfg["debug"]; 
    $min = "";
    if($debug == "1"){
        $min = ".min";
    }
    ?>
	<script language="javascript" type="text/javascript" src="js/printerSet<?php echo $min?>.js?v=<?php echo $version ?>"></script>    
    <?php
}

//body
function randerBodyCallBack(){    
?>

<div>电脑编号：<input type="text" id="T5" size="47" disabled=disabled> </div>
<font color="red">备注：
<ul>
    <li>下面的设置仅对当前电脑有效！</li>
    <li>如果未设置，则按电脑默认配置执行打印任务！</li>
    <li>如果设置了纸张名称，就不需要设置纸张高度和宽度！</li>
</ul>    
</font>
<input type="button" value="保存设置" onclick="printerSet.saveSetting()"/>

<table class="gridtable ">
<thead>
    <tr>
    <th>类别</th>
    <th>打印机名称</th>
    <th>打印方向</th>
    <th>纸张名称</th>
    <th>宽度（mm）</th>
    <th>高度（mm）</th>
    </tr>
</thead>
<tbody>
<?php
$printer = array(
    1 => "标签打印机",
    2 => "肠内医嘱单打印机",
    3 => "人体成分报告打印机",
);
foreach ($printer as $key => $value) {
?>
    <tr>
        <td><?php echo $value?></td>
        <td><select class="PrinterList" id="PrinterList<?php echo $key?>" index="<?php echo $key?>" size="1"></select>
        </td>
        <td><select class="Orient" id="Orient<?php echo $key?>" size="1"></select>
        </td>
        <td><select class="PagSizeList" id="PagSizeList<?php echo $key?>" size="1"></select>
        </td>
        <td><input id="Width<?php echo $key?>" type="text" /></select>
        </td>
        <td><input id="Heigth<?php echo $key?>" type="text" /></select>
        </td>
    </tr>
<?php
} 
?>
</tbody>
</table>

<?php
}    