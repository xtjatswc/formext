<?php
namespace form_rander;

class pager
{
    public $_pageSize = 30;
    public $_pageIndex = 0;

    function __construct(){
        if(array_key_exists("txtPageSize",$_POST)){
            $this->_pageSize = $_POST["txtPageSize"];
            $this->_pageIndex = $_POST["hidPageIndex"];
        }
    }

    public function rander($pageRecordsCount){

        $previousDisabled = "";
        $nextDisabled = "";
        if($this->_pageIndex == "0"){
            $previousDisabled = 'disabled="disabled"';
        }else if($pageRecordsCount == 0){
            $nextDisabled = 'disabled="disabled"';
        }

        ?>
        <div class="pager">
            <input name="txtPageSize" type="text" value="<?php echo $this->_pageSize ?>" style="width:50px;text-align:center"/>
            <input type="submit" value="第一页" onclick="return formExt.doFristPage()"/>            
            <input type="submit" value="上一页" onclick="return formExt.doPreviousPage()" <?php echo $previousDisabled ?>/>            
            <input type="text" value="第<?php echo $this->_pageIndex + 1?>页" disabled="disabled"  style="width:90px;text-align:center"/>
            <input id="hidPageIndex" name="hidPageIndex" type="hidden" value="<?php echo $this->_pageIndex ?>"/>
            <input type="submit" value="下一页" onclick="return formExt.doNextPage()"  <?php echo $nextDisabled ?>/>
            <input type="submit" value="查询"/>
            <input type="button" value="打印"  onclick="formExt.doPrint()"/>
            <input type="button" value="导出excel"  onclick="formExt.exportExcel()"/>
            <input type="button" value="删除"  onclick="formExt.deleteRecords()"/>
        </div>
        <?php
    }

    function getLimit(){        
        return " limit ".($this->_pageIndex * $this->_pageSize).",".$this->_pageSize;
    }

}