<?php

include_once ('../libraries/Controller.php');

class ManageNews extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageNews']) && $userPermessionsArray['manageNews'] = true);
            if ($userHasPermession) {
                $data['mainContent'] = 'manage_news_view';
                $data['title'] = 'إدارة الأخبار';
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

    function addNews() {
        $this->loadModel('News_model');
        $model = new News_model();
        $newsID = $model->addNews();
        if ($newsID > 0) {
            $msg = "تمت الإضافة بنجاح";
        } else {
            $msg = "فشلت عملية الاضافة";
        }
        echo $msg;
    }

    function getNewsForAjax() {
        $newsID = $_GET['newsID'];
        $this->loadModel('News_model');
        $model = new News_model();
        $news = $model->getNews($newsID);
        if ($news == null) {
            echo 0;
        } else {
            echo json_encode($news);
        }
    }

    function editNews() {
        $newsID = $_POST['newsID2'];
        $this->loadModel('News_model');
        $model = new News_model();
        $success = $model->editNews($newsID);
        if ($success) {
            $msg = "تم تعديل المعلومات بنجاح";
        } else {
            $msg = "فشلت عملية التعديل";
        }
        echo $msg;
    }

    function deleteNews() {
        $newsID = $_POST['newsID'];
        $this->loadModel('News_model');
        $model = new News_model();
        $success = $model->deleteNews($newsID);
        if ($success) {
            $msg = "تم حذف الخبر بنجاح";
        } else {
            $msg = "لم يتم حذف الخبر";
        }
        echo $msg;
    }

    function getAllNewsForDataTables() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['manageNews']) && $userPermessionsArray['manageNews'] = true);
            if ($userHasPermession) {
                $this->loadModel('News_model');
                $model = new News_model();
                echo $model->getAllNewsForDataTables();
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

new ManageNews();
?>
