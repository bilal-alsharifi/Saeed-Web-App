<?php

include_once ('../libraries/Controller.php');

class BrowsePaymentsForPatient extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['browsePaymentsForPatient']) && $userPermessionsArray['browsePaymentsForPatient'] = true);
            if ($userHasPermession) {
                $data['mainContent'] = 'browse_payments_for_patient_view';
                $data['title'] = 'استعراض الدفعات الخاصة بمريض';
                $this->loadView('template_view', $data);
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getAllPatientDoctorVisitsPaymentForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['browsePaymentsForPatient']) && $userPermessionsArray['browsePaymentsForPatient'] = true);
            if ($userHasPermession) {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $nationalNumber = helper::sanitize($_GET['nationalNumber']);
                $patient = $model->getPatientInfo($nationalNumber);
                $patient_id = $patient['id'];
                $from = helper::sanitize($_GET['from']);
                $to = helper::sanitize($_GET['to']);
                echo $model->getAllPatientDoctorVisitsPaymentForDataTables($patient_id, $from, $to);
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getPatientForAjax() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['browsePaymentsForPatient']) && $userPermessionsArray['browsePaymentsForPatient'] = true);
            if ($userHasPermession) {
                $nationalNumber = $_GET['nationalNumber'];
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $patient = $model->getPatientInfo($nationalNumber);
                if ($patient == null) {
                    echo 0;
                } else {
                    echo json_encode($patient);
                }
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getAllPatientMedicinePaymentForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['browsePaymentsForPatient']) && $userPermessionsArray['browsePaymentsForPatient'] = true);
            if ($userHasPermession) {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $nationalNumber = helper::sanitize($_GET['nationalNumber']);
                $patient = $model->getPatientInfo($nationalNumber);
                $patient_id = $patient['id'];
                $from = helper::sanitize($_GET['from']);
                $to = helper::sanitize($_GET['to']);
                $PatientMedicinePayments = $model->getAllPatientMedicinePaymentForDataTables($patient_id, $from, $to);
                if ($PatientMedicinePayments == null) {
                    echo 0;
                } else {
                    echo json_encode($PatientMedicinePayments);
                }
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getAllPatientHospitalServicePaymentForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['browsePaymentsForPatient']) && $userPermessionsArray['browsePaymentsForPatient'] = true);
            if ($userHasPermession) {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $nationalNumber = helper::sanitize($_GET['nationalNumber']);
                $patient = $model->getPatientInfo($nationalNumber);
                $patient_id = $patient['id'];
                $from = helper::sanitize($_GET['from']);
                $to = helper::sanitize($_GET['to']);
                $PatientHospitalServicePayments = $model->getAllPatientHospitalServicePaymentForDataTables($patient_id, $from, $to);
                if ($PatientHospitalServicePayments == null) {
                    echo 0;
                } else {
                    echo json_encode($PatientHospitalServicePayments);
                }
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }
    
}

new BrowsePaymentsForPatient();
?>
