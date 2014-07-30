<?php

include_once ('../libraries/Controller.php');

class ManagePatients extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['managePatients']) && $userPermessionsArray['managePatients'] = true);
            if ($userHasPermession) {
                $this->loadModel('Patients_model');
                $model = new Patients_model();
                $data['companies'] = $model->getAllCompanies();
                $data['mainContent'] = 'manage_patients_view';
                $data['title'] = 'ادارة المرضى';
                $data['msg'] = isset($_GET['msg']) ? $_GET['msg'] : "";
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

    function renewAccount() {
        $patientID = $_GET['patientID'];
        $this->loadModel('Patients_model');
        $model = new Patients_model();
        $success = $model->renewAccount($patientID);
        if ($success) {
            $msg = "تم تعديل المعلموات بنجاح";
        } else {
            $msg = "فشلت عملية التعديل";
        }
        include_once '../helpers/helper.php';
        helper::redirect("ManagePatients.php?msg={$msg}");
    }

    function addPatient() {
        $this->loadModel('Patients_model');
        $model = new Patients_model();
        $patientID = $model->addPatient();
        if ($patientID > 0) {
            $msg = "تمت الااضافة بنجاح";
        } else {
            $msg = "فشلت عملية الاضافة";
        }
        include_once '../helpers/helper.php';
        helper::redirect("ManagePatients.php?msg={$msg}");
    }

    function getPatientForAjax() {
        $patientID = $_GET['patientID'];
        $this->loadModel('Patients_model');
        $model = new Patients_model();
        $patient = $model->getPatient($patientID);
        if ($patient == null) {
            echo 0;
        } else {
            echo json_encode($patient);
        }
    }

    function editPatient() {
        $patientID = $_POST['patientID2'];
        $this->loadModel('Patients_model');
        $model = new Patients_model();
        $success = $model->editPatient($patientID);
        if ($success) {
            $msg = "تم تعديل المعلموات بنجاح";
        } else {
            $msg = "فشلت عملية التعديل";
        }
        include_once '../helpers/helper.php';
        helper::redirect("ManagePatients.php?msg={$msg}");
    }

    function deletePatient() {
        $patientID = $_GET['patientID'];
        $this->loadModel('Patients_model');
        $model = new Patients_model();
        $success = $model->deletePatient($patientID);
        if ($success) {
            $msg = "تم حذف العضو بنجاح";
        } else {
            $msg = "لم يتم حذف العضو";
        }
        include_once '../helpers/helper.php';
        helper::redirect("ManagePatients.php?msg={$msg}");
    }

    function getAllPatientsForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['managePatients']) && $userPermessionsArray['managePatients'] = true);
            if ($userHasPermession) {
                $this->loadModel('Patients_model');
                $model = new Patients_model();
                echo $model->getAllPatientsForDatatables();
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

new ManagePatients();
?>
