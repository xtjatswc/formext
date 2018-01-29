<?php
namespace form_rander;
require "../autoload.php";

$sql = $_GET["sql"];

$result = array();
if(array_key_exists("para", $_GET)){
    $result = $db->fetch_all($sql, $_GET["para"]);
}else{
    $result = $db->fetch_all($sql);
}
echo json_encode($result);





