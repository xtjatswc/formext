var printLabel = {};

printLabel.printDesign = function () {
    printLabel.printInit();
    LODOP.PRINT_DESIGN();
}

printLabel.printSetup = function () {
    printLabel.printInit();
    LODOP.PRINT_SETUP();
}

printLabel.preview = function () {
    printLabel.printInit();
    LODOP.PREVIEW();
}

printLabel.print = function () {
    printLabel.printInit();
    LODOP.PRINT();
}

printLabel.printInit = function(){
    LODOP = getLodop();
    //指定整体偏移量、可编辑区域大小，默认单位px，名称很重要，打印维护保存的时候作为ID，所以程序修改发布后，如果想让客户端暂存失效，就改一下这个名
    LODOP.PRINT_INITA(0, 0, "180mm", "100mm", "标签打印");
    //设置纸张大小，默认单位mm
    LODOP.SET_PRINT_PAGESIZE(2, 900, 500, "");
    //
    LODOP.ADD_PRINT_HTM(-2, -1, 260, 39, "打印页面部分内容a");
    //强制分页，但别把它放到页眉代码下面，好像那头互相干扰不起作用
    LODOP.NewPage();
    //用ADD_PRINT_TABLE输出表格，固定高度表格，表格内容超过高度会自动分页
    LODOP.ADD_PRINT_TABLE(42, -6, "80mm", "20mm", document.getElementById("tblNutrientadvicedetail").outerHTML);
    //表格<thead>标记表头，用如下配置，会显示在每一页
    LODOP.SET_PRINT_STYLEA(0, "TableHeightScope", 1);

    //设置页眉、页脚的代码放到最后，才会起作用，不知道是咱回事，这是重点
    //显示总页码，默认为阿拉伯数字，format='ChineseNum'表示显示成汉字
    //也有示例用这个显示页码
    //LODOP.ADD_PRINT_TEXT(421,542,165,22,"右下脚的页号：第#页/共&页");
    //LODOP.SET_PRINT_STYLEA(0,"ItemType",2);

    LODOP.ADD_PRINT_HTM(1, 600, 300, 100, "总页号：<font color='#0000ff' format='ChineseNum'><span tdata='pageNO'>第##页</span>/<span tdata='pageCount'>共##页</span></font>");
    LODOP.SET_PRINT_STYLEA(0, "ItemType", 1);
    //位置锁定，右边距锁定
    LODOP.SET_PRINT_STYLEA(0, "Horient", 1);

    LODOP.ADD_PRINT_TEXT(15, -1, 260, 39, "打印页面部分内容");
    LODOP.SET_PRINT_STYLEA(0, "ItemType", 1);

    LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);//横向时的正向显示

}

