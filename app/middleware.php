<?php
require_once '../app/core/App.php';

class middleware{
    function checkLogin(){
        if(!isset($_SESSION['username'])){
            header('Location: /home/login');
            exit();
        }
    }
}

?>