<?php
class helper 
{
    static function redirect($controller)
    {
        include_once ('../config/config.php');
        header('location: '.BASE_URL.'controllers/'.$controller);
    }
    static function sanitize($v)
    {
        $v1 = htmlspecialchars($v);
        //$result = mysql_real_escape_string($v1);
        $result = $v1;
        if ($result=="")
        {
            $result = NULL;
        }
        return $result;
    }
    static function userLoggedIn()
    {
        if(!isset($_SESSION)) 
        { 
            session_start(); 
        } 
        if(isset($_SESSION['userID']) && isset($_SESSION['email']))
        {
            return $_SESSION['userID'];
        }
        else
        {
            return 0;
        }
    }
}
?>
