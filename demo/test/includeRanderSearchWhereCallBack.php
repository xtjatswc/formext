<?php
$where_TherapyStatus_value = "0";
if(isset($_POST["where_TherapyStatus"])){
    $where_TherapyStatus_value = $_POST["where_TherapyStatus"];
}

$where_TherapyStatus = "";
if($where_TherapyStatus_value == "0"){
    $where_TherapyStatus = " and a.TherapyStatus <> 9 ";
}else if($where_TherapyStatus_value == "9"){
    $where_TherapyStatus = " and a.TherapyStatus = 9 ";
}

$where_Sex = array();
$where_Sql = "";
if(isset($_POST["where_Sex"])){
    $where_Sex = $_POST["where_Sex"];
    $where_Sql = "  and b.Gender in (";
    foreach ($where_Sex as $sexKey => $sexValue) {
        $where_Sql .= "'".$sexValue."',";
    }
    $where_Sql = rtrim($where_Sql, ",").")";    
}

$sql = str_replace("[w|TherapyStatus]", $where_TherapyStatus, $sql);
$sql = str_replace("[w|Sex]", $where_Sql, $sql);

return $sql;