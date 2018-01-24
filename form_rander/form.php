<?php
namespace form_rander;

class form
{
    public $_listColumnCfg;
    public $_pageCfg;
    public $_listDisplayCfg;
    public $_pager;
    public $_searcher;
    public $_db;


    function __construct($db){
        $this->_pager = new pager();
        $this->_searcher = new searcher();     
        $this->_db = $db;   
    }

    //一次性生成表单列配置
    public function getColumns($rows)
    {
        if(empty($rows)){
            echo "需要至少查询出一行数据！";exit;
        }

        $columus = array_keys($rows[0]);

        echo "\$form->_listColumnCfg = array(</br>";
        foreach ($columus as $key => $value) {
            echo "&nbsp;&nbsp;&nbsp;&nbsp;'$value' => array('isDisplay' => '1','displayName' => '$value','width' => '','maxLength' => '','isPrint' => '1'),</br>";
        }
        echo "</br>);";
    }

    public function randerForm($sql)
    {
        $this->header();

        //显示查询框
        $this->_searcher->rander();

        $sql = $this->_searcher->randerWhere($sql);
        $rows = $this->_db->fetch_all($sql);

//显示分页
//是否打印序号列
$printNoClass = "";
if($this->_pageCfg["isPrintNo"] == "0"){
    $printNoClass = "noPrint";
}

$this->_pager->rander(count($rows));
echo "<table id='mainGridTable' class='gridtable'>";
echo "<thead>";
echo "<tr>
<th class='noPrint'><input name='tablechoice1' type='checkbox'/></th>
<th class='$printNoClass'>序号</th>";

//循环列头
foreach ($this->_listColumnCfg as $ckey => $cvalue) {
    $width = empty($cvalue["width"]) ? "" : "width:".$cvalue['width'].";";
    $isDisplay = $cvalue["isDisplay"] == "1" ? "" : "display:none;";
    //是否打印列
    $printThClass = "";
    if($cvalue["isPrint"] == "0"){
        $printThClass = "noPrint";
    }

    echo "<th class='$printThClass' style='$width $isDisplay'>";
    echo $cvalue["displayName"];
    echo "</th>";
}
echo "</tr>
    </thead>
    <tbody>
";

if(!empty($rows)){
    //取真实列
    $columes = array_keys($rows[0]);
}

$rowNumber = 0;
//循环行
foreach ($rows as $rkey => $rvalue) {

    echo "
    <tr>
    <td class='noPrint'><input type='checkbox' name='choice'/></td>
    ";
    //序号列
    $rowNumber += 1;
    echo "<td class='$printNoClass'>".($rowNumber + $this->_pager->_pageSize * $this->_pager->_pageIndex)."</td>";

    //循环列
    foreach ($this->_listColumnCfg as $ckey => $cvalue) {
        //判断列在sql中是否存在
        if(!in_array($ckey, $columes)){
            echo "列".$ckey."在sql中不存在！";exit;
        }

        //是否显示列
        $isDisplay = $cvalue["isDisplay"] == "1" ? "" : "display:none;";
        //是否打印列
        $printTdClass = "";
        if($cvalue["isPrint"] == "0"){
            $printTdClass = "noPrint";
        }
        
        echo "<td class='$printTdClass' style='$isDisplay'>";

        //列别名
        $value = $this->displayValue($rvalue[$ckey], $ckey);

        //长度截取
        $value = $this->subLength($value, $cvalue["maxLength"]);

        echo $value;
        echo "</td>";
    }
    echo "</tr>";
    
}
echo "</tbody></table>";

?>
</form>
</body>
</html>
<?php

        return $rows;
    }

    //获取显示值
    private function displayValue($value, $columnName){
        if(in_array($columnName, array_keys($this->_listDisplayCfg))){
            $items = $this->_listDisplayCfg[$columnName];
            if(in_array($value,array_keys($items))){
                return $items[$value];
            }else{ 
                return $value;
            }
        }else{
            return $value;
        }

    }

    //长度截取
    private function subLength($value, $maxLength){
        if(!empty($maxLength) && strlen($value) > $maxLength){
            $newValue = substr($value, 0, $maxLength)."……";
            $newValue = "<div title='$value'>$newValue</div>";
            return $newValue;
        }

        return $value;
    }

    private function header(){
        ?>
        <html>
        <head>
            <meta charset="utf-8" />
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <title><?php echo  $this->_pageCfg["Title"] ?></title>
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <link rel="stylesheet" type="text/css" media="screen" href="form_rander\style.css" />
            <link rel="stylesheet" type="text/css" media="print" href="form_rander\style-print.css" />
            <script src="form_rander\jquery-3.3.1.min.js"></script>
            <script src="form_rander\jquery.table2excel.js"></script>
            <script src="form_rander\form.js"></script>
        </head>
        <body>
        <form method="post" action="">
        
        <?php        
    }


}

