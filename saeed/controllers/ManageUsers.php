<?php

include_once ('../libraries/Controller.php');

class ManageUsers extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $this->loadModel('Membership_model');
            $model = new Membership_model();
            $data['roles'] = $model->getAllRoles();
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageUsers']) && $userPermessionsArray['manageUsers'] = true);
            if ($userHasPermession) {
                $this->loadModel('Users_model');
                $model = new Users_model();
                $data['specializations'] = $model->getSpecializations();
                $data['mainContent'] = 'manage_users_view';
                $data['title'] = 'ادارة المستخدمين';
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

    function addRoles() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageUsers']) && $userPermessionsArray['manageUsers'] = true);
            if ($userHasPermession) {
                $userID = $_POST['userID3'];
                $roles = $_POST['roles'];
                $this->loadModel('Membership_model');
                $model = new Membership_model();
                $success = $model->addRoles($userID, $roles);
                if ($success) {
                    $msg = "تم تعديل الصلاحيات بنجاح";
                } else {
                    $msg = "فشلت عملية تعديل الصلاحيات";
                }
                include_once '../helpers/helper.php';
                helper::redirect("ManageUsers.php?msg={$msg}");
            } else {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function getUserForAjax() {
        $email = $_GET['email'];
        $this->loadModel('Users_model');
        $model = new Users_model();
        $user = $model->getUser($email);
        $userSon = null;
        if ($user == null) {
            echo 0;
        } else {
            switch ($user['type']) {
                case 'مستخدم':
                    break;
                case 'طبيب':
                    $userSon = $model->getDoctor($user['id']);
                    $user['firstName'] = $userSon['firstName'];
                    $user['lastName'] = $userSon['lastName'];
                    $user['gender'] = $userSon['gender'];
                    $user['specialization_id'] = $userSon['specialization_id'];
                    break;
                case 'صيدلية':
                    $userSon = $model->getPharmacy($user['id']);
                    $user['pharmacyName'] = $userSon['name'];
                    break;
                case 'مشفى':
                    $userSon = $model->getHospital($user['id']);
                    $user['hospitalName'] = $userSon['name'];
                    break;
            }
            $this->loadModel('Membership_model');
            $model2 = new Membership_model();
            $user['roles'] = $model2->getRoles($user['id']);
            echo json_encode($user);
        }
    }

    function addUser() {
        $success = true;
        $this->loadModel('Users_model');
        $model = new Users_model();
        $userID = $model->addUser();
        switch ($_POST['type']) {
            case 'طبيب':
                $success = $model->addDoctor($userID);
                break;
            case 'صيدلية':
                $scucess = $model->addPharmacy($userID);
                break;
            case 'مشفى':
                $success = $model->addHospital($userID);
                break;
            default:
                break;
        }
        if ($success && $userID > 0) {
            $msg = "تمت الااضافة بنجاح";
        } else {
            $msg = "فشلت عملية الاضافة";
        }
        include_once '../helpers/helper.php';
        helper::redirect("ManageUsers.php?msg={$msg}");
    }

    function editUser() {
        $userID = $_POST['userID2'];
        $type = $_POST['type2'];
        $this->loadModel('Users_model');
        $model = new Users_model();
        $success1 = $model->editUser($userID);
        $success2 = true;
        switch ($type) {
            case 'طبيب':
                $success2 = $model->editDoctor($userID);
                break;
            case 'صيدلية':
                $success2 = $model->editPharmacy($userID);
                break;
            case 'مشفى':
                $success2 = $model->editHospital($userID);
                break;
            default:
                break;
        }
        if ($success1 && $success2 > 0) {
            $msg = "تم تعديل المعلموات بنجاح";
        } else {
            $msg = "فشلت عملية التعديل";
        }
        include_once '../helpers/helper.php';
        helper::redirect("ManageUsers.php?msg={$msg}");
    }

    function deleteUser() {
        $userID = $_GET['userID'];
        $this->loadModel('Users_model');
        $model = new Users_model();
        $success = $model->deleteUser($userID);
        if ($success) {
            $msg = "تم حذف العضو بنجاح";
        } else {
            $msg = "لم يتم حذف العضو";
        }
        include_once '../helpers/helper.php';
        helper::redirect("ManageUsers.php?msg={$msg}");
    }

    function getAllUsersForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageUsers']) && $userPermessionsArray['manageUsers'] = true);
            if ($userHasPermession) {
                $this->loadModel('Users_model');
                $model = new Users_model();
                echo $model->getAllUsersForDatatables();
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

new ManageUsers();
?>
