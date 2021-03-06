<?php
namespace form_rander;

class searcher
{
    public $_searchCfg;

    function __construct(){

    }

    public function rander(){
        if(empty($this->_searchCfg)){
            return;
        }
?>

<fieldset class="searchBox">
    <legend>查询条件</legend>
<?php
                foreach ($this->_searchCfg as $key => $value) {
                    $inputValue = "";
                    $dataType = $value["dataType"];
                    if(array_key_exists("where_$key", $_POST)){
                        $inputValue = $_POST["where_$key"];
                    }else{
                        //取默认值
                        $inputValue = $this->getDefaultValue($value);
                    }

                    //判断呈现方式
                    $randerCode = "";
                    if( $dataType == "date"){
                        $randerCode = 'onClick="WdatePicker({el:this,dateFmt:\'yyyy-MM-dd\'})"';
                    }else if($dataType == "datetime"){
                        $randerCode = 'onClick="WdatePicker({el:this,dateFmt:\'yyyy-MM-dd HH:mm:ss\'})"';
                    }
?>
    <label for="where_<?php echo $key?>" title="<?php echo $value["tooltip"] ?>" ><?php echo $value["labelName"]?></label>
    <input class="condition" id="where_<?php echo $key?>" name="where_<?php echo $key?>" type="text" value="<?php echo $inputValue ?>" <?php echo $randerCode ?> />
<?php
                    if($value["break"] == "1"){
                        echo "    <br/>
";
                    }
                }

                echo call_user_func("randerSearchCallBack");
?>
</fieldset>

<?php
    }

    //替换查询字符串
    public function randerWhere($sql){
        foreach ($this->_searchCfg as $key => $value) {
            $sWhere = "";
            $inputValue = "";
            if(array_key_exists("where_$key", $_POST)){
                $inputValue = trim($_POST["where_$key"]);   //去掉首尾空格
                $inputValue = rtrim($inputValue, ",");  //去掉最右边的逗号
            }else{
                //取默认值
                $inputValue = $this->getDefaultValue($value);
            }

            if(!empty($inputValue)){
                $sWhere = str_replace('{value}', $inputValue, $value["randerText"]);
            }

            $sql = str_replace("[w|$key]", $sWhere, $sql);
        }

        $sql = call_user_func("randerSearchWhereCallBack", $sql);
        return $sql;
    }

    private function getDefaultValue($value){
        $dataType = $value["dataType"];
        $inputValue = "";
        if($dataType == "string"){
            $inputValue = $value["defaultValue"];
        }else if($dataType == "date" || $dataType == "datetime"){
            $dateNow = date($value["format"],time());
            $dateNow = date($value["format"],strtotime("$dateNow ".$value["defaultValue"]." day"));
            $inputValue = $dateNow;
        }
        return $inputValue;
    }
    
}