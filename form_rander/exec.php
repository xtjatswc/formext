<?php
namespace form_rander;
require "../autoload.php";

$sql = $_POST["sql"];
$para = $_POST["para"];
$db->query($sql, $para);
echo json_encode(array("success" => true));

