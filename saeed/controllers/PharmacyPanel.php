<?php

include_once ('../libraries/Controller.php');

class PharmacyPanel extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'صيدلية') {
                $data['mainContent'] = 'pharmacy_panel_view';
                $data['title'] = 'لوحة تحكم الصيدلية';
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

    function getAllPatientMedicineForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'صيدلية') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $nationalNumber = helper::sanitize($_GET['nationalNumber']);
                $patient = $model->getPatientInfo($nationalNumber);
                $patient_id = $patient['id'];
                echo $model->getAllPatientMedicineForDataTables($patient_id);
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getMedicineForAjax() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'صيدلية') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $nationalNumber = helper::sanitize($_GET['nationalNumber']);
                $patient = $model->getPatientInfo($nationalNumber);
                $patient_id = $patient['id'];
                $medicine = $model->getMedicine($patient_id);
                if ($medicine == null) {
                    echo 0;
                } else {
                    echo json_encode($medicine);
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

    function saveChangesOnMedicine()
    {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'صيدلية') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $result = $model->saveChangesOnMedicine($user['id']);
                if ($result > 0) {
                    echo "تمت الااضافة بنجاح";
                } else {
                    echo $result; //"فشلت عملية الإضافة";
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

new PharmacyPanel();
?>
