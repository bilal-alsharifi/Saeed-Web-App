<?php

include_once ('../libraries/Controller.php');

class ManageConfig extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageConfig']) && $userPermessionsArray['manageConfig'] = true);
            if ($userHasPermession) {
                $data['mainContent'] = 'manage_config_view';
                $data['title'] = 'ادارة اعدادات النظام';
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

    function getAllConfigurationForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageConfig']) && $userPermessionsArray['manageConfig'] = true);
            if ($userHasPermession) {
                $this->loadModel('Config_model');
                $model = new Config_model();
                echo $model->getAllConfigurationForDataTables();
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getConfigForAjax() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageConfig']) && $userPermessionsArray['manageConfig'] = true);
            if ($userHasPermession) {
                $this->loadModel('Config_model');
                $model = new Config_model();
                $config = $model->getConfigForAjax();
                if ($config == null) {
                    echo 0;
                } else {
                    echo json_encode($config);
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

    function saveChangesOnConfig()
    {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageConfig']) && $userPermessionsArray['manageConfig'] = true);
            if ($userHasPermession) {
                $this->loadModel('Config_model');
                $model = new Config_model();
                $result = $model->saveChangesOnConfig();
                if ($result > 0) {
                    echo "تم التعديل بنجاح";
                } else {
                    echo "فشلت عملية التعديل";
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

new ManageConfig();
?>
