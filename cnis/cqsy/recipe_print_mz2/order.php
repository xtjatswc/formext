<?php
require "../../../autoload.php";

$recipeNo = $_GET["recipeNo"];

?>

<div style="text-align:center">
    <table style="width:auto;margin:auto;">
    <tr>
    <td><img src="logo.png"></img></td>
    <td><h3>重庆医科大学附属第三医院</h3></td>
    </tr>
    </table>
</div>



<div style="text-align:left">No.<?php echo $recipeNo ?></div>

<h4>治疗单</h4>

<table class="orderTable">
<tr>
    <td>姓名：</td>
    <td>性别：</td>
    <td>年龄：</td>
</tr>
</table>

<table class="orderTable">
<tr>
    <td>联系电话：</td>
    <td>地址：</td>
</tr>
<tr>
    <td>科别：</td>
    <td>门诊病历号：</td>
</tr>
<tr colspan="2">
    <td>临床诊断：</td>
</tr>
<tr>
    <td>开具日期：</td>
    <td></td>
</tr>
</table>

<h3 style="text-align:left;">RP</h3>

<table>
<tr>
<td>金额（元）</td>
<td></td>
<td>医师：</td>
<td></td>
</tr>
</table>

<h5>领取地点：门诊部一楼综合诊区8诊室</h5>
