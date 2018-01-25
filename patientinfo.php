

<?php
require "autoload.php";

$form = new form_rander\form($db);

$form->_pageCfg = array(
    'Title' => "住院患者信息",
    'version' => "5", //系统版本，变动时，js等缓存文件也会刷新
    'isPrintNo' => "0", //是否打印序号列
    'primaryKey' => "PatientHospitalize_DBKey", //主键，复选框对应的值
    'deleteSql' => "delete a.*,b.* from patienthospitalizebasicinfo  a
                    inner join patientbasicinfo b on a.PATIENT_DBKEY = b.PATIENT_DBKEY where PatientHospitalize_DBKey in ({0})", //删除sql
);

$form->_listColumnCfg = array(
    'PatientHospitalize_DBKey' => array('isDisplay' => '1','displayName' => 'PatientHospitalize_DBKey','width' => '','maxLength' => '','isPrint' => '1'),
    'PATIENT_DBKEY' => array('isDisplay' => '1','displayName' => 'PATIENT_DBKEY','width' => '','maxLength' => '','isPrint' => '1'),
    'PatientName' => array('isDisplay' => '1','displayName' => 'PatientName','width' => '','maxLength' => '','isPrint' => '0'),
    'Department_DBKey' => array('isDisplay' => '1','displayName' => 'Department_DBKey','width' => '','maxLength' => '','isPrint' => '1'),
    'BedNumber_DBKey' => array('isDisplay' => '1','displayName' => 'BedNumber_DBKey','width' => '','maxLength' => '','isPrint' => '1'),
    'HospitalizationNumber' => array('isDisplay' => '1','displayName' => 'HospitalizationNumber','width' => '','maxLength' => '','isPrint' => '1'),
    'InHospitalData' => array('isDisplay' => '1','displayName' => 'InHospitalData','width' => '','maxLength' => '','isPrint' => '1'),
    'PregnantCondition' => array('isDisplay' => '1','displayName' => 'PregnantCondition','width' => '','maxLength' => '','isPrint' => '1'),
    'Staging' => array('isDisplay' => '1','displayName' => 'Staging','width' => '','maxLength' => '','isPrint' => '0'),
    'RiskStratification' => array('isDisplay' => '1','displayName' => 'RiskStratification','width' => '','maxLength' => '','isPrint' => '1'),
    'TherapyStatus' => array('isDisplay' => '1','displayName' => 'TherapyStatus','width' => '','maxLength' => '','isPrint' => '1'),
    'ClinicalDietOrders' => array('isDisplay' => '1','displayName' => 'ClinicalDietOrders','width' => '','maxLength' => '','isPrint' => '1'),
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

$sql = 'select a.*, b.PatientName,case when a.PatientHospitalize_DBKey > 135659 then 1 else 0 end isChecked from patienthospitalizebasicinfo a inner join patientbasicinfo b on a.PATIENT_DBKEY = b.PATIENT_DBKEY where 1=1 [w|HospitalizationNumber] [w|InHospitalData] [w|InHospitalData2] [w|PatientName] order by  a.InHospitalData desc '.$form->_pager->getLimit();

$rows = $form->randerForm($sql);
//$form->getColumns($rows);

$db->disconnect();

?>

