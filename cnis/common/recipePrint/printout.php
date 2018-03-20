<?php
require "../../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\..\\",
    'libPath' => "..\\..\\..\\form_rander\\",
    'Title' => "门诊医嘱单打印",
    'version' => $globalCfg["version"], //系统版本，变动时，js等缓存文件也会刷新
    'debug' => $globalCfg["debug"],
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack(){
    ?>
	<style id="style1">
    table {width:100%}
    td,th {font-size: 12pt;padding:3px;text-align:left}
    h2,h3 {margin:0px;}
    </style>
    <?php
}

//javascript
function randerJavascriptCallBack(){
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<script language="javascript" type="text/javascript" src="printout.js?v=<?php echo $version ?>"></script>    
    <?php
}

//body
function randerBodyCallBack(){    
    ?>
    <div>
        <input type="button" value="检测打印插件" onclick="util.CheckLodopIsInstall()" />
        <input type="button" value="设置" onclick="printout.printSetting()" />
        <input type="button" value="打印维护" onclick="printout.printSetup()" />
        <div style="display:none2">
            <input type="button" value="打印设计" onclick="printout.printDesign()" />
            <input type="button" value="打印预览" onclick="printout.preview()" />
            <input id="btnPrint" type="button" value="打印" onclick="printout.print()" />
        </div>
    </div>
    <br/>
    打印机:      
    <font id="printerName" color="blue"></font>&nbsp;&nbsp;<font id="lsMsg" color="red"></font>
    <br/>
    <font id="labelTip" color="red"></font>
    <br/>    

<div style="width:800px;border:1px solid black;padding:5px">
    <div id="divRecipe" style="padding:15px">
    营养科
    <hr/>
    <h2 style="text-align:center">
    医疗处方单
    </h2>
    <table>
        <tr>
            <td>ID号：</td>
            <td>病案号:</td>
            <td>科别：</td>
        </tr>
        <tr>
            <td>姓名：</td>
            <td>性别:</td>
            <td>年龄：</td>
        </tr>
    </table>
    <hr/>
    疾病及诊断：
    <hr/>
    <table>
        <tr>
            <th>药品名称</th>
            <th>规格</th>
            <th>单次剂量</th>
            <th>频次</th>
            <th>用法</th>
            <th>数量</th>
            <th>单价</th>
            <th>金额</th>
        </tr>
        <tr></tr>
    </table>
    <table>
        <tr>
            <td>医师：</td>
            <td>签章:</td>
            <td>日期：</td>
        </tr>
    </table>
    <hr/>
    <table>
        <tr>
            <td>药费：</td>
            <td>审核/收费:</td>
            <td>审核/调配：</td>
            <td>核对/发药：</td>
        </tr>
    </table>
    <font style="font-size:10pt">
    根据卫生部《医疗机构药事管理规定》要求：为保障患者用药安全，除药品质量原因外，药品一经发出，不得退换。
    </font>
    <h3>
    注：价格以收费时为准 当天交费，过期无效
    </h3>
    </div>
</div>
    <?php
}