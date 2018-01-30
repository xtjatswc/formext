<?php
    function __autoload($class){
    $filePath = __DIR__.DIRECTORY_SEPARATOR.$class.".php";
    if(file_exists($filePath)){
        require_once($filePath);
    }else{
        die("文件".$filePath."不存在！");
    }
}

require_once("form_rander/errhandler.php");

$db = new form_rander\dbhelper();
$db->connect('pdo', 'mysql', '127.0.0.1', 'root', 'root', 'cnis', 3306);



