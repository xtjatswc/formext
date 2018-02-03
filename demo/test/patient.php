
<?php
require "../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\",
    'libPath' => "..\\..\\form_rander\\",
    'Title' => "住院患者信息",
    'version' => "6", //系统版本，变动时，js等缓存文件也会刷新
);

$page = new form_rander\page($db);
$page->randerPage();

//css样式
function randerStylesheetCallBack(){
    //echo "css";
}

//javascript
function randerJavascriptCallBack(){
    ?>
<script language="javascript" type="text/javascript">    
	function CheckIsInstall() {	 
		try{ 
		     var LODOP=getLodop(); 
			if (LODOP.VERSION) {
				 if (LODOP.CVERSION)
				 alert("当前有C-Lodop云打印可用!\n C-Lodop版本:"+LODOP.CVERSION+"(内含Lodop"+LODOP.VERSION+")"); 
				 else
				 alert("本机已成功安装了Lodop控件！\n 版本号:"+LODOP.VERSION); 

			};
		 }catch(err){ 
 		 } 
	}; 
</script>
    <?php

}

//body
function randerBodyCallBack(){
    echo '现在测试一下：<a href="javascript:CheckIsInstall()">查看本机是否安装了控件或云打印</a>';
}


