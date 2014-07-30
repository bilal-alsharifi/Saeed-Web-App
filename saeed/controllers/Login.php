<?php

include_once ('../libraries/Controller.php');

class Login extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $data['mainContent'] = 'index_page_view';
            $data['title'] = 'الصفحة الرئيسية';
            $this->loadView('template_view', $data);
        } else {
            $data['mainContent'] = 'login_view';
            $data['title'] = 'تسجيل الدخول';
            $data['msg'] = isset($_GET['msg']) ? $_GET['msg'] : "";
            $this->loadView('template_view', $data);
        }
    }

    function validate() {
        include_once '../helpers/helper.php';
        $email = helper::sanitize($_POST['email']);
        $password = helper::sanitize($_POST['password']);
        $this->loadModel('Membership_model');
        $model = new Membership_model();
        $user = $model->validate($email, $password);
        if ($user != null) {
            if (!isset($_SESSION)) {
                session_start();
            }
            $_SESSION['userID'] = $user['id'];
            $_SESSION['email'] = $email;
            $_SESSION['type'] = $user['type'];
            $_SESSION['userPermissionsArray'] = $model->getUserPermissionsArray();
            include_once ('../helpers/helper.php');
            helper::redirect('IndexPage.php');
        } else {
            include_once ('../helpers/helper.php');
            $msg = 'هنالك خطأ في اسم المستخدم أو كلمة المرور';
            helper::redirect('Login.php' . '?msg=' . $msg);
        }
    }

    function logout() {
        if (!isset($_SESSION)) {
            session_start();
        }
        unset($_SESSION);
        session_destroy();
        include_once ('../helpers/helper.php');
        $msg = 'تم تسجيل الخروج بنجاح';
        helper::redirect('IndexPage.php' . '?msg=' . $msg);
    }

    function forgotPasswordForAjax() {
        $email = urldecode($_GET['email']);
        $this->loadModel('Users_model');
        $model = new Users_model();
        $user = $model->getUser($email);

        if ($user != null) {
            $password = $user['password'];

            //send email
            include_once '../config/config.php';
            $to = $email;
            $subject = 'استرجاع كلمة المرور';
            $message = 'اضغط على الرابط التالي لاستعادة كلمة المرور';
            $message .= BASE_URL . 'controllers/Profile.php?email=' . $email . '&code=' . $password;
            $from = SUPPORT_MAIL;
            $headers = "From:" . $from;
            ini_set("sendmail_from", "webmaster@" . $_SERVER["SERVER_NAME"]);
            mail($to, $subject, $message, $headers);
            echo 1;
        } else {
            echo 0;
        }
    }

}

new Login();
?>
