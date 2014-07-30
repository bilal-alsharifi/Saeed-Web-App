<?php

include_once ('../libraries/Controller.php');

class Contact extends Controller {


    function index() {
        $data['mainContent'] = 'contact_view';
        $data['title'] = 'اتص بنا';
        $this->loadView('template_view', $data);
    }

    function sendMailUsForAjax() {
        $name = $_GET['name'];
        $phone = $_GET['phone'];
        $email = $_GET['email'];
        $text = $_GET['text'];
        $nameTitle = 'الاسم : ';
        $phoneTitle = 'الهاتف : ';
        $emailTitle = 'الربيد الالكتروني : ';
        $textTitle = 'نص الرسالة : ';
        $br="\n";
        include_once '../config/config.php';
        $to = SUPPORT_MAIL;
        $subject = 'رسالة دعم فني';
        $message = $nameTitle.$name.$br.$phoneTitle.$phone.$br.$emailTitle.$email.$br.$textTitle.$text;
        $from = $email;
        $headers = "From:" . $from;
        ini_set("sendmail_from", "webmaster@" . $_SERVER["SERVER_NAME"]);
        mail($to, $subject, $message, $headers);
        echo 1;
    }

}

new Contact();
?>
