<?php
namespace form_rander;
require "../autoload.php";

$sql = $_POST["sql"];
echo $db->query($sql);

