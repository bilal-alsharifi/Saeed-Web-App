<?php

include_once ('../libraries/Controller.php');

class IndexPage extends Controller {

    function index() {
        $this->loadModel('News_model');
        $model = new News_model();
        $data['news'] = $model->getAllNews();
        $data['mainContent'] = 'index_page_view';
        $data['title'] = 'الصفحة الرئيسية';
        $data['viewSlider'] = true;
        $data['enableCaching'] = true;
        $this->loadView('template_view', $data);
    }

}

new IndexPage();
?>
