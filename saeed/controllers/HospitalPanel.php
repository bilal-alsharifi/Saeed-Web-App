<?php
include_once ('../libraries/Controller.php');
class HospitalPanel extends Controller
{
    function index()
    {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0)
        {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'مشفى')
            {
                $data['mainContent'] = 'hospital_panel_view';
                $data['title']= 'لوحة تحكم المشفى';
                $this->loadView('template_view', $data);
            }
            else
            {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        }
        else
        {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getAllHospitalServixesForUserForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'مشفى') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $nationalNumber = helper::sanitize($_GET['nationalNumber']);
                $patient = $model->getPatientInfo($nationalNumber);
                $patient_id = $patient['id'];
                echo $model->getAllHospitalServixesForUserForDataTables($patient_id);
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getHospitalForAjax() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'مشفى') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $nationalNumber = helper::sanitize($_GET['nationalNumber']);
                $patient = $model->getPatientInfo($nationalNumber);
                $patient_id = $patient['id'];
                $hospital = $model->getHospital($patient_id);
                if ($hospital == null) {
                    echo 0;
                } else {
                    echo json_encode($hospital);
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

    function saveChangesOnHospital()
    {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'مشفى') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $result = $model->saveChangesOnHospital($user['id']);
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
new HospitalPanel();
?>
