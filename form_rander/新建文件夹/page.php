<?php
namespace form_rander;

class page
{
    public static $_pageCfg;
    function __construct($db){

    }

    public function randerPage(){
        $version = self::$_pageCfg["version"];
        $root = self::$_pageCfg["rootPath"];
        $lib = self::$_pageCfg["libPath"]; 
        $debug = self::$_pageCfg["debug"]; 
        $min = "";
        if($debug != "1"){
            $min = ".min";
        }
    ?>
<!DOCTYPE html>    
<html>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<title><?php echo  self::$_pageCfg["Title"] ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $lib ?>css\style.css?v=<?php echo $version?>" />
<link rel="stylesheet" type="text/css" media="print" href="<?php echo $lib ?>css\style-print.css?v=<?php echo $version?>" />
<link href="<?php echo $lib ?>js\jquery-ui-1.12.1\jquery-ui.css" rel="stylesheet">
<?php
    echo call_user_func("randerStylesheetCallBack");
?>

<script src="<?php echo $lib ?>js\jquery-3.3.1<?php echo $min?>.js"></script>
<script src="<?php echo $lib ?>js\jquery.validate.js"></script>
<script src="<?php echo $lib ?>js\jquery.validate.unobtrusive.js"></script>
<script src="<?php echo $lib ?>js\jquery-ui-1.12.1\jquery-ui<?php echo $min?>.js"></script>
<script src="<?php echo $lib ?>js\jquery.cookie.js"></script>
<script src="<?php echo $lib ?>js\tablesMergeCell.js"></script>
<script src="<?php echo $lib ?>js\mp.js"></script>
<script src="<?php echo $lib ?>lodop\LodopFuncs.js?v=<?php echo $version?>"></script>
<script src="<?php echo $lib ?>js\head<?php echo $min?>.js"></script>
<script src="<?php echo $lib ?>js\My97DatePicker\WdatePicker.js"></script>
<script src="<?php echo $lib ?>js\jquery.table2excel.js?v=<?php echo $version?>"></script>
<script src="<?php echo $lib ?>js\util<?php echo $min?>.js?v=<?php echo $version?>"></script>
<script src="<?php echo $lib ?>js\page<?php echo $min?>.js?v=<?php echo $version?>"></script>
<script type="text/javascript">
    <?php 
        echo "pageExt.rootPath='".str_replace("\\", "/", $root)."';";
        echo "\r\n";
        echo "pageExt.libPath='".str_replace("\\", "/", $lib)."';";
    ?>    

</script>
<?php
    echo call_user_func("randerJavascriptCallBack");
?>

</head>
<body>
<?php
    echo call_user_func("randerBodyCallBack");
?>

</body>
</html>
    <?php
    }
        
}


