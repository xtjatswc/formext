<?php
$where_TherapyStatus_value = "0";
    if(isset($_POST["where_TherapyStatus"])){
        $where_TherapyStatus_value = $_POST["where_TherapyStatus"];
    }

    $where_Sex = array();
    if(isset($_POST["where_Sex"])){
        $where_Sex = $_POST["where_Sex"];
    }
?>
    
    <label for="where_TherapyStatus" title="" >在院状态</label>
    <select id="where_TherapyStatus" name="where_TherapyStatus">
        <option value ="-1">全部</option>
        <option value ="0">在院</option>
        <option value="9">出院</option>
    </select>
    <script>
        $("#where_TherapyStatus").val(<?php echo $where_TherapyStatus_value?>);
    </script>

    <label for="where_Sex_M" title="" >男</label>
    <input id="where_Sex_M" name="where_Sex[]" type="checkbox" value="M" <?php if(in_array('M', $where_Sex)) echo("checked");?> />
    <label for="where_Sex_F" title="" >女</label>
    <input id="where_Sex_F" name="where_Sex[]" type="checkbox" value="F" <?php if(in_array('F', $where_Sex)) echo("checked");?> />

