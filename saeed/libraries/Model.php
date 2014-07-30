<?php
class Model 
{
    public $db;
    function __construct() 
    {
        include_once ('../config/config.php');
        $this->db = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS); 
        $this->db->exec("set names utf8");  
    }
}

?>
