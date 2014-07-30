<?php

include_once ('../libraries/Controller.php');

class Profile extends Controller {

    function index() {
        include_once ('../helpers/helper.php');

        $allow = false;
        $model = null;
        $email = null;
        $user = null;
        if (helper::userLoggedIn() > 0) {
            $allow = true;
            $email = $_SESSION['email'];
            $this->loadModel('Users_model');
            $model = new Users_model();
            $user = $model->getUser($email);
        } else if (isset($_GET['email']) && isset($_GET['code'])) {
            $email = $_GET['email'];
            $code = $_GET['code'];
            $this->loadModel('Users_model');
            $model = new Users_model();
            $user = $model->getUser($email);
            if ($user['password'] == $code) {
                $allow = true;
            }
        }
        if ($allow) {
            $data['userID'] = $user['id'];
            $data['mainContent'] = 'profile_view';
            $data['title'] = 'الملف الشخصي';
            //get user info
            $userSon = null;
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
            $data['user'] = $user;
            //
            //get Specializations;
            $data['specializations'] = $model->getSpecializations();
            //
            $this->loadView('template_view', $data);
        } else {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }

    function editPasswordForAjax() {
        include_once ('../helpers/helper.php');
        $password = $_POST['password'];
        $userID = $_POST['userID'];
        $this->loadModel('Membership_model');
        $model = new Membership_model($userID);
        $result = $model->editPassword($password);
        if ($result) {
            echo 1;
        } else {
            echo 0;
        }
    }

}

new Profile();
?>
