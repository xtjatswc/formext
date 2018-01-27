<?php
namespace form_rander;
require "../autoload.php";

$sql = $_POST["sql"];
$db->query($sql);
echo json_encode(array("success" => true));

