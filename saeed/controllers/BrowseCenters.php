<?php

include_once ('../libraries/Controller.php');

class BrowseCenters extends Controller {

    function index() {
        $data['mainContent'] = 'browse_centers_view';
        $data['title'] = 'المراكز المشتركة';
        $this->loadModel('Users_model');
        $model = new Users_model();
        $data['keywords'] = json_encode($model->getKeyWords());
        $this->loadView('template_view', $data);
    }

    function searchForUsersForAjax() {
        $name = $_GET['name'];
        $this->loadModel('Users_model');
        $model = new Users_model();
        $users = $model->SearchForUsers($name);
        echo json_encode($users);
    }

    function getUsersByType() {
        $type = $_GET['type'];
        $this->loadModel('Users_model');
        $model = new Users_model();
        $users = $model->getUsersByType($type);
        echo json_encode($users);
    }

}

new BrowseCenters();
?>
