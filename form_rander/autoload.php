<?php
function __autoload($class){
    if(file_exists($class.".php")){
        require_once($class.".php");
    }else{
        die("文件$class.php不存在！");
    }
}

$db = new form_rander\dbhelper();
$db->connect('pdo', 'mysql', '127.0.0.1', 'root', 'root', 'cnis', 3306);



