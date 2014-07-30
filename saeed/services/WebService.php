<?php

include_once ('../config/config.php');
$server = new SoapServer(BASE_URL . 'services/wsdl.wsdl');
$server->addFunction('getAllPatients');
$server->addFunction('getPatient');
$server->handle();

function getAllPatients($companyID) {
    include_once ('../config/config.php');
    $db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $db->exec("set names utf8");
    $query = "SELECT * FROM patient WHERE company_id = :companyID ;";
    $sth = $db->prepare($query);
    $sth->bindParam('companyID', $companyID);
    $sth->execute();
    $patients = $sth->fetchAll();
    for ($i = 0; $i < count($patients); $i++) {
        $patients[$i]['doctorVisits'] = getDoctorVisits($patients[$i]['id']);
    }
    return json_encode($patients);
}

function getPatient($companyID, $email) {
    include_once ('../config/config.php');
    $db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $db->exec("set names utf8");
    $query = "SELECT * FROM patient WHERE email = :email AND company_id = :companyID LIMIT 1;";
    $sth = $db->prepare($query);
    $sth->bindParam('companyID', $companyID);
    $sth->bindParam('email', $email);
    $sth->execute();
    $rows = $sth->fetchAll();
    $patient = $rows[0];
    $patient['doctorVisits'] = getDoctorVisits($patient['id']);
    return json_encode($patient);
}

function getDoctorVisits($patientID) {
    include_once ('../config/config.php');
    $db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $db->exec("set names utf8");
    $query = "SELECT * FROM doctorvisit WHERE patient_id = :patientID;";
    $sth = $db->prepare($query);
    $sth->bindParam('patientID', $patientID);
    $sth->execute();
    $rows = $sth->fetchAll();
    for ($i = 0; $i < count($rows); $i++) {
        $rows[$i]['hospitalServices'] = getHospitalServices($rows[$i]['id']);
        $rows[$i]['medicines'] = getMedicines($rows[$i]['id']);
    }
    return $rows;
}

function getHospitalServices($doctorVisitID) {
    include_once ('../config/config.php');
    $db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $db->exec("set names utf8");
    $query = "SELECT * FROM hospitalservice WHERE doctorVisit_id = :doctorVisitID;";
    $sth = $db->prepare($query);
    $sth->bindParam('doctorVisitID', $doctorVisitID);
    $sth->execute();
    $rows = $sth->fetchAll();
    return $rows;
}

function getMedicines($doctorVisitID) {
    include_once ('../config/config.php');
    $db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $db->exec("set names utf8");
    $query = "SELECT * FROM medicine WHERE doctorVisit_id = :doctorVisitID;";
    $sth = $db->prepare($query);
    $sth->bindParam('doctorVisitID', $doctorVisitID);
    $sth->execute();
    $rows = $sth->fetchAll();
    return $rows;
}

?>
