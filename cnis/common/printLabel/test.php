
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
    $version = form_rander\page::$_pageCfg["version"];
    ?>
	<link id="cssPrint" rel="stylesheet" type="text/css" media="screen" href="printLabel.css?v=<?php echo $version ?>" />    
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
    <input type="button" value="打印设计" onclick="printLabel.printDesign()" />
    <input type="button" value="打印维护" onclick="printLabel.printSetup()" />
    <input type="button" value="打印预览" onclick="printLabel.preview()" />
    <input type="button" value="打印" onclick="printLabel.print()" />
</div>

<div style="width:300px">
    <div> 
            <div id="divBaseInfo" >
            <div style="float:left">姓名:张三&nbsp;</div> 
            <div style="float:left">科室:肿瘤内科&nbsp;</div> 
            <div style="float:left">床号:a12536&nbsp;</div> 
            <div style="float:left">住院号:0001254521&nbsp;</div> 
    </div>

    <table  id="tblNutrientadvicedetail" style="width:90%">
        <thead>
            <tr>
            <td>品名</td>
            <td><nobr>数量</nobr></td>
            <td>规格</td>
            <td>备注</td>
            </tr>
        </thead>
        <tbody>
            <tr>
            <td>倍康素倍康素倍康素</td>
            <td>2</td>
            <td>250ml</td>
            <td>加500ml水</td>
            </tr>
            <tr>
            <td>倍康素2</td>
            <td>2</td>
            <td>250ml</td>
            <td></td>
            </tr>
            <tr>
            <td>倍康素3</td>
            <td>2</td>
            <td>250ml</td>
            <td></td>
            </tr>
            <tr>
            <td>倍康素4</td>
            <td>2</td>
            <td>250ml</td>
            <td></td>
            </tr>
            <tr>
            <td>倍康素5</td>
            <td>2</td>
            <td>250ml</td>
            <td></td>
            </tr>
            <tr>
            <td>倍康素6</td>
            <td>2</td>
            <td>250ml</td>
            <td></td>
            </tr>
            <tr>
            <td>倍康素7</td>
            <td>2</td>
            <td>250ml</td>
            <td></td>
            </tr>
            <tr>
            <td>倍康素8</td>
            <td>2</td>
            <td>250ml</td>
            <td></td>
            </tr>
            <tr>
            <td>倍康素9</td>
            <td>2</td>
            <td>250ml</td>
            <td></td>
            </tr>
            <tr>
            <td>倍康素</td>
            <td>2</td>
            <td>250ml</td>
            <td></td>
            </tr>
        </tbody>
    </table>
</div>

<?php
}    