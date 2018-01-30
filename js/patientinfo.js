//患者姓名
$(function($){
    var para = {
        id : "where_PatientName",
        url : "form_rander/query.php",
        delay : 1000,
        minLength : 2,
        multiple : true,
        sql : "select CONCAT(PatientName, '  [ ', PatientNameFirstLetter, ' ]') label, PatientName value from patientbasicinfo where (PatientName like :term or PatientNameFirstLetter like :term2) limit 0, 30",
        requestPara : '{":term" : "%{0}%", ":term2" : "{0}%"}',
    };
    util.autocomplete(para);
});

//科室
$(function($){
    var para = {
        id : "where_Department",
        url : "form_rander/query.php",
        delay : 1000,
        minLength : 2,
        multiple : true,
        sql : "select CONCAT(DepartmentName, '  [ ', Department_DBKey, ' ]') label, Department_DBKey value from department where (DepartmentName like :term or Department_DBKey like :term2) limit 0, 30",
        requestPara : '{":term" : "%{0}%", ":term2" : "%{0}%"}',
    };
    util.autocomplete(para);
});