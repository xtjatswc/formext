
<?php
require "../../autoload.php";

form_rander\page::$_pageCfg = array(
    'rootPath' => "..\\..\\",
    'libPath' => "..\\..\\form_rander\\",
    'Title' => "住院患者信息",
    'version' => $globalCfg["version"], //系统版本，变动时，js等缓存文件也会刷新
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
	<script language="javascript" type="text/javascript" src="js\patient.js"></script>    

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
	?>
<form id="formAnswer" action="" method="post">
<div class="divQuestion">

<div class="divQuestionHeader">
	<span>1</span>&nbsp; 您是&nbsp;

			<span class="asterisk">(必选)</span>
</div>

	<ul class="answers-list radio-list">
					<li class="answer-item radio-item"><input id="radio_1" questionid="1" name="radio_group_1" type="radio" value="1"><label for="radio_1" class="answertext">医生</label></li>
					<li class="answer-item radio-item"><input id="radio_2" questionid="1" name="radio_group_1" type="radio" value="2"><label for="radio_2" class="answertext">护士</label></li>
					<li class="answer-item radio-item"><input id="radio_3" questionid="1" name="radio_group_1" type="radio" value="3"><label for="radio_3" class="answertext">医技人员</label></li>
	</ul>

</div>
<div class="divQuestion">

                <div class="divQuestionHeader">
                    <span></span>&nbsp; 您的营养知识来源为（可多选）&nbsp;

                            <span class="asterisk">(必选)</span>
                </div>

                    <ul class="answers-list radio-list">
                                    <li class="answer-item">
                                        <input id="check_145" questionid="45" name="check_group_45" type="checkbox" value="145" /> <label for="check_145" class="answertext">A 在学校时学习</label>
                                    </li>
                                    <li class="answer-item">
                                        <input id="check_146" questionid="45" name="check_group_45" type="checkbox" value="146" /> <label for="check_146" class="answertext">B 工作实践中摸索、积累</label>
                                    </li>
                                    <li class="answer-item">
                                        <input id="check_147" questionid="45" name="check_group_45" type="checkbox" value="147" /> <label for="check_147" class="answertext">C 阅读有关书籍、学术期刊</label>
                                    </li>
                                    <li class="answer-item">
                                        <input id="check_148" questionid="45" name="check_group_45" type="checkbox" value="148" /> <label for="check_148" class="answertext">D 同专业人员交流</label>
                                    </li>
                                    <li class="answer-item">
                                        <input id="check_149" questionid="45" name="check_group_45" type="checkbox" value="149" /> <label for="check_149" class="answertext">E 营养专业人员交流</label>
                                    </li>
                                    <li class="answer-item">
                                        <input id="check_150" questionid="45" name="check_group_45" type="checkbox" value="150" /> <label for="check_150" class="answertext">F 上级医师指导</label>
                                    </li>
                                    <li class="answer-item">
                                        <input id="check_151" questionid="45" name="check_group_45" type="checkbox" value="151" /> <label for="check_151" class="answertext">G 参加会议、培训</label>
                                    </li>
                                    <li class="answer-item">
                                        <input id="check_152" questionid="45" name="check_group_45" type="checkbox" value="152" /> <label for="check_152" class="answertext">H 相关平面媒体介绍</label>
                                    </li>
                                    <li class="answer-item">
                                        <input id="check_153" questionid="45" name="check_group_45" type="checkbox" value="153" /> <label for="check_153" class="answertext">I 医学专业网站和其他互联网</label>
                                    </li>
                                    <li class="answer-item">
                                        <input id="check_154" questionid="45" name="check_group_45" type="checkbox" value="154" /> <label for="check_154" class="answertext">J 其他</label>
                                            <input id="text_154" type="text" questionid="45" QuestionOptionID="154" />
                                    </li>
                    </ul>

            </div>
            <div class="divQuestion">

                <div class="divQuestionHeader">
                    <span></span>&nbsp; 您所在医院的名称&nbsp;

                            <span class="asterisk">(必填)</span>
                </div>

                        <input id="text_155" name="text_155" class="text-option" type="text" questionid="5" QuestionOptionID="155" /><label for="text_155" class="answertext"></label>

            </div>
            <div class="divQuestion">

                <div class="divQuestionHeader">
                    <span></span>&nbsp; 手机&nbsp;

                            <span class="asterisk">(必填)</span>
                </div>

                        <input id="text_156" name="text_156" class="text-option" type="text" questionid="6" QuestionOptionID="156" /><label for="text_156" class="answertext"></label>

            </div>
            <div class="divQuestion">

                <div class="divQuestionHeader">
                    <span></span>&nbsp; E-mail&nbsp;

                            <span class="asterisk">(必填)</span>
                </div>

                        <input id="text_157" name="text_157" class="text-option" type="text" questionid="7" QuestionOptionID="157" /><label for="text_157" class="answertext"></label>

            </div>

</form>
<div class="divQuestion" style="background:#eefaff;text-align:center;padding:8px;">
        <button id="btnSubmit" type="button" class="quesBtn">提交</button>
</div>

	<?php
}


