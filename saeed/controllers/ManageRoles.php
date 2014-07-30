<?php

include_once ('../libraries/Controller.php');

class ManageRoles extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageRoles']) && $userPermessionsArray['manageRoles'] = true);
            if ($userHasPermession) {
                $data['mainContent'] = 'manage_roles_view';
                $data['title'] = 'إدارة الادوار';
                $data['msg'] = isset($_GET['msg']) ? $_GET['msg'] : "";
                $this->loadModel('Membership_model');
                $model = new Membership_model();
                $data['permissions'] = $model->getAllPermissions();
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

    function addRole() {
        $this->loadModel('Membership_model');
        $model = new Membership_model();
        $roleID = $model->addRole();
        if ($roleID > 0) {
            $msg = "تمت الااضافة بنجاح";
        } else {
            $msg = "فشلت عملية الاضافة";
        }
        include_once '../helpers/helper.php';
        helper::redirect("ManageRoles.php?msg={$msg}");
    }

    function getRoleForAjax() {
        $roleName = $_GET['roleName'];
        $this->loadModel('Membership_model');
        $model = new Membership_model();
        $role = $model->getRole($roleName);
        if ($role == null) {
            echo 0;
        } else {
            $role['permissions'] = $model->getPermissions($role['id']);
            echo json_encode($role);
        }
    }

    function editRole() {
        $roleID = $_POST['roleID2'];
        $this->loadModel('Membership_model');
        $model = new Membership_model();
        $success = $model->editRole($roleID);
        if ($success) {
            $msg = "تم تعديل المعلموات بنجاح";
        } else {
            $msg = "فشلت عملية التعديل";
        }
        include_once '../helpers/helper.php';
        helper::redirect("ManageRoles.php?msg={$msg}");
    }

    function deleteRole() {
        $roleID = $_GET['roleID'];
        $this->loadModel('Membership_model');
        $model = new Membership_model();
        $success = $model->deleteRole($roleID);
        if ($success) {
            $msg = "تم حذف الدور بنجاح";
        } else {
            $msg = "لم يتم حذف الدور";
        }
        include_once '../helpers/helper.php';
        helper::redirect("ManageRoles.php?msg={$msg}");
    }

    function addPermissions() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageRoles']) && $userPermessionsArray['manageRoles'] = true);
            if ($userHasPermession) {
                $roleID = $_POST['roleID3'];
                $permissions = $_POST['permissions'];
                $this->loadModel('Membership_model');
                $model = new Membership_model();
                $success = $model->addPermissions($roleID, $permissions);
                if ($success) {
                    $msg = "تم تعديل الصلاحيات بنجاح";
                } else {
                    $msg = "فشلت عملية تعديل الصلاحيات";
                }
                include_once '../helpers/helper.php';
                helper::redirect("ManageRoles.php?msg={$msg}");
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getAllRolesForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageRoles']) && $userPermessionsArray['manageRoles'] = true);
            if ($userHasPermession) {
                $this->loadModel('Membership_model');
                $model = new Membership_model();
                echo $model->getAllRolesForDatatables();
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

new ManageRoles();
?>
