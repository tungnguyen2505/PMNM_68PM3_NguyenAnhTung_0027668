<?php
require_once '../app/core/App.php';
class middleware{

    function checkLogin(){
        $publicPages = ['/home/login', '/auth/login'];
        $currentUrl = $_GET['url'] ?? '';
        
        // Allow public pages without session
        if(in_array('/'.$currentUrl, $publicPages) || in_array('/'.strtok($currentUrl, '/'), $publicPages)){
            return;
        }
        
        // Require login for protected pages
        if(!isset($_SESSION['username'])){
            header('Location: /home/login');
            exit();
        }
    }
}

?>