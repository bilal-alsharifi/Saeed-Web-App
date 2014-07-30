<?php

include_once ('../libraries/Controller.php');

class DoctorPanel extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'طبيب') {
                $data['mainContent'] = 'doctor_panel_view';
                $data['title'] = 'لوحة تحكم الطبيب';
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

    function getPatientForAjax() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'طبيب') {
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

    function getAllDoctorVisitsForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'طبيب') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                echo $model->getDoctorVisitsForPatient($user['id']);
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function addDoctorVisit() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'طبيب') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $result = $model->addDoctorVisit($user['id']);
                if ($result > 0) {
                    echo "تمت الااضافة بنجاح";
                } else {
                    echo $result; //"فشلت عملية الإضافة بسبب انتهاء التأمين أو تجاوز عدد الزيارات الأعظمي";
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

    function addMedicine() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'طبيب') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $result = $model->addMedicine($user['id']);
                if ($result > 0) {
                    echo "تمت الااضافة بنجاح";
                } else {
                    echo "فشلت عملية الإضافة";
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

    function addHospitalService() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'طبيب') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $result = $model->addHospitalService($user['id']);
                if ($result > 0) {
                    echo "تمت الااضافة بنجاح";
                } else {
                    echo "فشلت عملية الإضافة";
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

    function getAllDoctorVisitMedicinesForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'طبيب') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                echo $model->getAllDoctorVisitMedicines($user['id']);
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getAllDoctorVisitHospitalServiceForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            if ($user['type'] == 'طبيب') {
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                echo $model->getAllDoctorVisitHospitalServiceForDataTables($user['id']);
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function printPrescription() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $user = $model->getUser();
            $this->loadModel('Users_model');
            $model = new Users_model();
            $doctor = $model->getDoctor($user['id']);
            if ($user['type'] == 'طبيب') {
                $data['name'] = $_GET['patientName'];
                $this->loadModel('Patient_model');
                $model = new Patient_model();
                $nationalNumber = $_GET['nationalNumber'];
                $patient = $model->getPatientInfo($nationalNumber);
                $data['gender'] = $patient['gender'];
                $data['address'] = $patient['address'];
                $data['date'] = date("Y-m-d");
                $data['doctorName'] = $doctor['firstName']." ".$doctor['lastName'];
                $visitMedicines = $model->getMedicinesForPatient($user['id'],$patient['id']);
                $data['visitMedicines'] = $visitMedicines;
                $visitHospitalServices = $model->getHospitalServicesForPatient($user['id'],$patient['id']);
                $data['visitHospitalServices'] = $visitHospitalServices;
                $this->loadView('prescription', $data);
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

new DoctorPanel();
?>
