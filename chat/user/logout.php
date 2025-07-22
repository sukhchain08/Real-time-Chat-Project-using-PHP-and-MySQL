<?php 
    session_start();
    
    include '../include/connection.php'; 
    $number = $_SESSION['number'];
    $status = "offline";
    $query = mysqli_query($conn, "UPDATE `user` SET `status`='$status' WHERE number = '$number'");
    if($query)
    {
        echo "<script>alert('Logout Success')</script>";
        echo "<script>window.open('login.php', '_self')</script>";
        session_destroy();
    }
    else
    {
        echo "<script>alert('Logout Failed, Error Occur')</script>";
        echo "<script>window.open('dashboard.php', '_self')</script>";
    }
?>