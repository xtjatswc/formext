<?php
namespace form_rander;
require "../autoload.php";

$sql = $_POST["sql"];

if(array_key_exists("para", $_POST)){
    $db->query($sql, $_POST["para"]);
}else{
    $db->query($sql);
}

echo json_encode(array("success" => true));

