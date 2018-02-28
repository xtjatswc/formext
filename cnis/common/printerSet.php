
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
    <li>如果未设置，则按电脑默认打印机执行打印任务！</li>
</ul>    
</font>
<input type="button" value="保存设置" onclick="printerSet.saveSetting()"/>

<table class="gridtable ">
<thead>
    <tr>
    <th>类别</th>
    <th>打印机名称</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>标签打印机</td>
        <td><select id="PrinterList1" size="1"></select>
        </td>
    </tr>
    <tr>
        <td>肠内医嘱单打印机</td>
        <td><select id="PrinterList2" size="1"></select></td>
    </tr>
</tbody>
</table>

<?php
}    