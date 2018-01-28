<?php
namespace form_rander;
require "../autoload.php";

$sql = $_POST["sql"];

$affectedCount = 0;
if(array_key_exists("para", $_POST)){
    $affectedCount = $db->query($sql, $_POST["para"]);
}else{
    $affectedCount = $db->query($sql);
}

echo json_encode(array(
    "success" => true, 
    "msg" => "执行成功！", 
    "affectedCount" => $affectedCount,
));

