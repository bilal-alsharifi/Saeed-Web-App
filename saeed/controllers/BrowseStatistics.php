<?php

include_once ('../libraries/Controller.php');

class BrowseStatistics extends Controller {

    function index() {
        include_once ('../helpers/helper.php');
        if (helper::userLoggedIn() > 0) {
            $userPermessionsArray = $_SESSION['userPermissionsArray'];
            $userHasPermession = (isset($userPermessionsArray['browseStatistics']) && $userPermessionsArray['browseStatistics'] = true);
            if ($userHasPermession) {
                $this->loadModel('Statistics_model');
                $model = new Statistics_model();
                $data['graphArray'] = $model->graphArray();
                $data['mainContent'] = 'browse_statistics_view';
                $data['title'] = 'الاحصائيات';
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

}

new BrowseStatistics();
?>
