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
$db->connect('pdo', 'mysql', '127.0.0.1', 'cnis', 'cnis', 'cnis', 3306);

$globalCfg = array(
    'clientFlag' => "cqsy", //
    'cnisDir' => "cnis",  //apache www下的目录名称，ajax调接口的时候用
    'version' => "1", //系统版本，变动时，js等缓存文件也会刷新
    'debug' => "0",
);

