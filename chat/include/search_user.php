<?php 
    include 'connection.php';

    if(isset($_POST['search']))
    {
        $search = $_POST['search_value'];
        echo "<script>window.open('find_friends.php?query=".htmlspecialchars($search)."', '_self')</script>";

    }
?>