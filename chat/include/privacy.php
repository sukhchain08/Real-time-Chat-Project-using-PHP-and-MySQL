<?php   
    // autometic logout hon de timing 
    ini_set('session.gc_maxlifetime', 3600); 
    session_set_cookie_params(3600);
    
    session_start();

    if(!isset($_SESSION['user_logged']) || $_SESSION['user_logged'] !== true)
    {
        echo "<script>alert('You cannot access this page without login')</script>";
        echo "<script>window.open('login.php', '_self')</script>";
        exit();
    }
?>