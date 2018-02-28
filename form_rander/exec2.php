<?php
namespace form_rander;
require "../autoload.php";

$sql = $_POST["sql"];

$affectedCount = 0;
$affectedCount = $db->exec($sql);

echo json_encode(array(
    "success" => true, 
    "msg" => "执行成功！", 
    "affectedCount" => $affectedCount,
));

