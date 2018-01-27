<?php

function exception_handler($e) {
        //echo "Sorry, something is wrong. Please try again, or contact us if the problem persists. Thanks!<br/>";
        $msg = 'Unhandled Exception:' . $e->getMessage() . '<br/>in file:' . $e->getFile() . '<br/>on line:' . $e->getLine();
        echo json_encode(array("success" => false, "msg" => $msg));
        //error_log($str);//保存一条错误信息字符串到web服务器的error_log文档里。
        error_log($msg);
        die();
}
set_exception_handler('exception_handler');

function shutdown_function()  
{  
    $e = error_get_last();    
    if(empty($e)) return;
    echo json_encode(array("success" => false, "msg" => $e));
    die();
}
register_shutdown_function('shutdown_function');  

function error_handler($errno, $errstr, $errfile, $errline) {
    $msg = "<b>Custom error:</b> [$errno] $errstr<br>
Error on line $errline in $errfile<br>";
    echo json_encode(array("success" => false, "msg" => $msg));
    die();
}
set_error_handler('error_handler');
