

<?php
require __DIR__."/form_rander/autoload.php";

$form = new form_rander\form($db);

$form->_pageCfg = array(
    'Title' => "住院患者信息",
);

$form->_listColumnCfg = array(
    'PatientHospitalize_DBKey' => array('isDisplay' => '1','displayName' => 'PatientHospitalize_DBKey','width' => '','maxLength' => ''),
    'PatientName' => array('isDisplay' => '1','displayName' => '患者姓名','width' => '','maxLength' => ''),
    'PATIENT_DBKEY' => array('isDisplay' => '1','displayName' => 'PATIENT_DBKEY','width' => '','maxLength' => ''),
    'Department_DBKey' => array('isDisplay' => '1','displayName' => 'Department_DBKey','width' => '','maxLength' => ''),
    'BedNumber_DBKey' => array('isDisplay' => '1','displayName' => 'BedNumber_DBKey','width' => '','maxLength' => ''),
    'HospitalizationNumber' => array('isDisplay' => '1','displayName' => 'HospitalizationNumber','width' => '','maxLength' => ''),
    'InHospitalData' => array('isDisplay' => '1','displayName' => 'InHospitalData','width' => '','maxLength' => ''),
    'OutHospitalData' => array('isDisplay' => '1','displayName' => 'OutHospitalData','width' => '','maxLength' => ''),
    'TherapyStartTime' => array('isDisplay' => '1','displayName' => 'TherapyStartTime','width' => '','maxLength' => ''),
    'LastScreeningDate' => array('isDisplay' => '1','displayName' => 'LastScreeningDate','width' => '','maxLength' => ''),
    'NextScreeningDate' => array('isDisplay' => '1','displayName' => 'NextScreeningDate','width' => '','maxLength' => ''),
    'Height' => array('isDisplay' => '1','displayName' => 'Height','width' => '','maxLength' => ''),
    'Weight' => array('isDisplay' => '1','displayName' => 'Weight','width' => '','maxLength' => ''),
    'MedicalHistory' => array('isDisplay' => '1','displayName' => 'MedicalHistory','width' => '','maxLength' => '60'),
    'PastMedicalHistory' => array('isDisplay' => '1','displayName' => 'PastMedicalHistory','width' => '','maxLength' => ''),
    'ChiefComplaint' => array('isDisplay' => '1','displayName' => 'ChiefComplaint','width' => '','maxLength' => ''),
    'NutritionChiefComplaint' => array('isDisplay' => '1','displayName' => 'NutritionChiefComplaint','width' => '','maxLength' => ''),
    'NutritionCondition' => array('isDisplay' => '1','displayName' => 'NutritionCondition','width' => '','maxLength' => ''),
    'PhysicalActivityLevel' => array('isDisplay' => '1','displayName' => 'PhysicalActivityLevel','width' => '','maxLength' => ''),
    'PregnantCondition' => array('isDisplay' => '1','displayName' => 'PregnantCondition','width' => '','maxLength' => ''),
    'Staging' => array('isDisplay' => '1','displayName' => 'Staging','width' => '','maxLength' => ''),
    'RiskStratification' => array('isDisplay' => '1','displayName' => 'RiskStratification','width' => '','maxLength' => ''),
    'TherapyStatus' => array('isDisplay' => '1','displayName' => 'TherapyStatus','width' => '','maxLength' => ''),
    'ClinicalDietOrders' => array('isDisplay' => '1','displayName' => 'ClinicalDietOrders','width' => '','maxLength' => ''),
    'OutHospitalSummary' => array('isDisplay' => '1','displayName' => 'OutHospitalSummary','width' => '','maxLength' => ''),
    'Clinicist_DBKey' => array('isDisplay' => '1','displayName' => 'Clinicist_DBKey','width' => '','maxLength' => ''),
    'NutrientDoctor_DBKey' => array('isDisplay' => '1','displayName' => 'NutrientDoctor_DBKey','width' => '','maxLength' => ''),
    'ClinicalDiagnosis' => array('isDisplay' => '1','displayName' => 'ClinicalDiagnosis','width' => '','maxLength' => ''),
    'ClinicalTreatment' => array('isDisplay' => '1','displayName' => 'ClinicalTreatment','width' => '','maxLength' => ''),

);

$form->_listDisplayCfg = array(
    'Gender' => array('F' => '女','M' => '男'),
    'MaritalStatus' => array('1' => '已婚','2' => '离异'),
    'Department_DBKey' => $db->fetch_cols("select Department_DBKey '0', DepartmentName '1' from department"),
);

//Y-m-d H:i:s
$form->_searcher->_searchCfg = array(
    'HospitalizationNumber' => array('labelName' => '住院号','randerText' => " and a.HospitalizationNumber like '%{value}%' ",'dataType' => 'string', 'defaultValue' => '11','format' => '', 'break' => '0'),
    'InHospitalData' => array('labelName' => '入院日期 ','randerText' => " and a.InHospitalData >= '{value}' ",'dataType' => 'date',  'defaultValue' => '-100','format' => 'Y-m-d H:i:s', 'break' => '0'),
    'InHospitalData2' => array('labelName' => '至','randerText' => " and a.InHospitalData <= '{value}' ",'dataType' => 'date',  'defaultValue' => '0','format' => 'Y-m-d', 'break' => '1'),
    'PatientName' => array('labelName' => '患者姓名','randerText' => " and b.PatientName like '{value}%' ",'dataType' => 'string',  'defaultValue' => '','format' => '', 'break' => '0'),
);

$sql = 'select a.*, b.PatientName from patienthospitalizebasicinfo a inner join patientbasicinfo b on a.PATIENT_DBKEY = b.PATIENT_DBKEY where 1=1 [w|HospitalizationNumber] [w|InHospitalData] [w|InHospitalData2] [w|PatientName] order by  a.InHospitalData desc '.$form->_pager->getLimit();

$rows = $form->randerForm($sql);
//$form->getColumns($rows);

$db->disconnect();

?>

