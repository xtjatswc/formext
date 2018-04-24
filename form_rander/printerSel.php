<?php
require "../autoload.php";

// global $printer;
?>
<label for="SelPrinterSet">切换打印设置</label>
<select name="SelPrinterSet" id="SelPrinterSet">
  <option disabled selected>当前报表的默认打印设置...</option>
  <optgroup disabled label="固定设置">
  <?php
    foreach ($printer as $key => $value) {
        if($key <= 100)
            echo "<option disabled value='$key'>$value</option>";
    }
  ?>
  </optgroup>
  <optgroup label="预留设置">
  <?php
    foreach ($printer as $key => $value) {
        if($key > 100)
            echo "<option value='$key'>$value</option>";
    }
  ?>
  </optgroup>
</select>