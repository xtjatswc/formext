<?php

function exception_handler($e) {
        echo "Sorry, something is wrong. Please try again, or contact us if the problem persists. Thanks!<br/>";
        $str = 'Unhandled Exception:' . $e->getMessage() . '<br/>in file:' . $e->getFile() . '<br/>on line:' . $e->getLine();
        echo $str;
        //error_log($str);//保存一条错误信息字符串到web服务器的error_log文档里。
        error_log($str);
        die();
}
set_exception_handler('exception_handler');

function shutdown_function()  
{  
    $e = error_get_last();    
    print_r($e);  
    die();
}
register_shutdown_function('shutdown_function');  

function error_handler($errno, $errstr, $errfile, $errline) {
    echo "<b>Custom error:</b> [$errno] $errstr<br>";
    echo " Error on line $errline in $errfile<br>";
    die();
}
set_error_handler('error_handler');
