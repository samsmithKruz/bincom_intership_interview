<?php
session_start();
require_once "config.php";


spl_autoload_register(function($className){
    require_once 'lib/'.$className.'.php';
})

?>