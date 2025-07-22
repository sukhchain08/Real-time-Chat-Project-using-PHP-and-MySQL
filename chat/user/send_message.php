<?php
    include '../include/connection.php';
    session_start();

    $sender = $_SESSION['number'];
    $receiver = $_POST['receiver'];
    $message = trim($_POST['message']);

    date_default_timezone_set('Asia/Kolkata');
    $date = date('d-m-y h:i A');

    if (!empty($message)) 
    {
        $message = mysqli_real_escape_string($conn, $message);
        mysqli_query($conn, "INSERT INTO messages (sender, receiver, message, date) VALUES ('$sender', '$receiver', '$message', '$date')");
    }

    header("Location: message.php?user=$receiver");
    exit;
?>
