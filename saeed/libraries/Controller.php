<?php
class Controller 
{
    function __construct() 
    { 
        if (isset($_GET['function']))
        {
            $function = $_GET['function'];
        }
        else
        {
            $function = "index";      
        }
        if (method_exists($this, $function))
        {
            $refl = new ReflectionMethod($this, $function);
            if (!$refl->isPrivate())
            {
                $this->{$function}();
            }
            else
            {
                $data['mainContent'] = 'error404_view';
                $this->loadView('template_view', $data);
            }
        }
        else
        {
            $data['mainContent'] = 'error404_view';
            $this->loadView('template_view', $data);
        }
    }
    function loadModel($model)
    {
        include_once("../models/{$model}.php");
    }
    function loadView($view, $data = null)
    {
        $data = $data;
        include_once("../views/{$view}.php");
    }
}

?>
