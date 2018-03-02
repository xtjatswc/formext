
<?php
require "../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\",
    'libPath' => "..\\..\\form_rander\\",
    'Title' => "打印机设置",
    'version' => $globalCfg["version"],
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack(){
}

//javascript
function randerJavascriptCallBack(){
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<script language="javascript" type="text/javascript" src="printerSet.js?v=<?php echo $version ?>"></script>    
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
    <th>宽度</th>
    <th>高度</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>标签打印机</td>
        <td><select class="PrinterList" id="PrinterList1" index="1" size="1"></select>
        </td>
        <td><select class="Orient" id="Orient1" size="1"></select>
        </td>
        <td><select class="PagSizeList" id="PagSizeList1" size="1"></select>
        </td>
        <td><input id="Width1" type="text" /></select>
        </td>
        <td><input id="Heigth1" type="text" /></select>
        </td>
    </tr>
    <tr>
        <td>肠内医嘱单打印机</td>
        <td><select class="PrinterList" id="PrinterList2" index="2" size="1"></select></td>
        <td><select class="Orient" id="Orient2" size="1"></select>
        </td>
        <td><select class="PagSizeList" id="PagSizeList2" size="1"></select>
        </td>
        <td><input id="Width2" type="text" /></select>
        </td>
        <td><input id="Heigth2" type="text" /></select>
        </td>
    </tr>
</tbody>
</table>

<?php
}    