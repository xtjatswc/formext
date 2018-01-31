var patientinfo = {};

$(function($){

    //患者姓名
    var para = {
        id : "where_PatientName",
        url : "form_rander/query.php",
        delay : 1000,
        minLength : 2,
        multiple : false,
        category : false,
        sql : "select CONCAT(PatientName, '  [ ', PatientNameFirstLetter, ' ]') label, PatientName value from patientbasicinfo where (PatientName like :term or PatientNameFirstLetter like :term2) limit 0, 30",
        requestPara : '{":term" : "%{0}%", ":term2" : "{0}%"}',
    };
    util.autocomplete(para);

    //科室
    var para = {
        id : "where_Department",
        url : "form_rander/query.php",
        delay : 1000,
        minLength : 2,
        multiple : true,
        category : true,
        sql : "select CONCAT(DepartmentName, '  [ ', Department_DBKey, ' ]') label, Department_DBKey value, case when Department_DBKey > 170 then '分类1' else '分类2' end category from department where (DepartmentName like :term or Department_DBKey like :term2) limit 0, 30",
        requestPara : '{":term" : "%{0}%", ":term2" : "%{0}%"}',
    };
    util.autocomplete(para);

});

patientinfo.openInfo = function(){
    var str = formExt.getSelectRecords().selStr;

    alert(str);
}