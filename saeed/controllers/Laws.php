<?php

include_once ('../libraries/Controller.php');

class Laws extends Controller {

    function index() {
        $this->loadModel('Config_model');
        $model = new Config_model();
        $data['config'] = $model->getConfig();
        $data['mainContent'] = 'laws_view';
        $data['enableCaching'] = true;
        $data['title'] = 'قوانين الشركة';
        $this->loadView('template_view', $data);
    }

}

new Laws();
?>
