<?php

include_once ('../libraries/Controller.php');

class ManageCompanies extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageCompanies']) && $userPermessionsArray['manageCompanies'] = true);
            if ($userHasPermession) {
                $data['mainContent'] = 'manage_companies_view';
                $data['title'] = 'إدارة الشركات';
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

    function addCompany() {
        $this->loadModel('Companies_model');
        $model = new Companies_model();
        $companyID = $model->addCompany();
        if ($companyID > 0) {
            $msg = "تمت الإضافة بنجاح";
        } else {
            $msg = "فشلت عملية الاضافة";
        }
        echo $msg;
    }

    function getCompanyForAjax() {
        $companyID = $_GET['companyID'];
        $this->loadModel('Companies_model');
        $model = new Companies_model();
        $company = $model->getCompany($companyID);
        if ($company == null) {
            echo 0;
        } else {
            echo json_encode($company);
        }
    }

    function editCompany() {
        $companyID = $_POST['companyID2'];
        $this->loadModel('Companies_model');
        $model = new Companies_model();
        $success = $model->editCompany($companyID);
        if ($success) {
            $msg = "تم تعديل المعلومات بنجاح";
        } else {
            $msg = "فشلت عملية التعديل";
        }
        echo $msg;
    }

    function deleteCompany() {
        $companyID = $_POST['companyID'];
        $this->loadModel('Companies_model');
        $model = new Companies_model();
        $success = $model->deleteCompany($companyID);
        if ($success) {
            $msg = "تم حذف الشركة بنجاح";
        } else {
            $msg = "لم يتم حذف الشركة";
        }
        echo $msg;
    }

    function getAllCompaniesForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageCompanies']) && $userPermessionsArray['manageCompanies'] = true);
            if ($userHasPermession) {
                $this->loadModel('Companies_model');
                $model = new Companies_model();
                echo $model->getAllCompaniesForDataTables();
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

new ManageCompanies();
?>
