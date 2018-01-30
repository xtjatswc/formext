$(function($){
    var para = {
        id : "where_PatientName",
        url : "form_rander/query.php",
        minLength : 2,
        multiple : true,
        sql : "select CONCAT(PatientName, '  [ ', PatientNameFirstLetter, ' ]') label, PatientName value from patientbasicinfo where (PatientName like :term or PatientNameFirstLetter like :term2) limit 0, 30",
        requestPara : '{":term" : "%{0}%", ":term2" : "{0}%"}',
    };
    util.autocomplete(para);
});
