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
        <fieldset>
            <legend>查询条件</legend>
<?php
                foreach ($this->_searchCfg as $key => $value) {
                    $inputValue = "";
                    if(array_key_exists("where_$key", $_POST)){
                        $inputValue = $_POST["where_$key"];
                    }else{
                        //取默认值
                        $inputValue = $this->getDefaultValue($value);
                    }
?>
                    <label for="where_<?php echo $key?>"><?php echo $value["labelName"]?></label>
                    <input class="condition" id="where_<?php echo $key?>" name="where_<?php echo $key?>" type="text" value="<?php echo $inputValue ?>"/>
<?php
                    if($value["break"] == "1"){
                        echo "<br/>";
                    }
                }
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
                $inputValue = $_POST["where_$key"];
            }else{
                //取默认值
                $inputValue = $this->getDefaultValue($value);
            }

            if(!empty($inputValue)){
                $sWhere = str_replace('{value}', $inputValue, $value["randerText"]);
            }

            $sql = str_replace("[w|$key]", $sWhere, $sql);
        }
        return $sql;
    }

    private function getDefaultValue($value){
        $dataType = $value["dataType"];
        $inputValue = "";
        if($dataType == "string"){
            $inputValue = $value["defaultValue"];
        }else if($dataType == "date"){
            $dateNow = date($value["format"],time());
            $dateNow = date($value["format"],strtotime("$dateNow ".$value["defaultValue"]." day"));
            $inputValue = $dateNow;
        }
        return $inputValue;
    }
    
}