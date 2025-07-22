<?php 
    include '../include/privacy.php'; 
    include '../include/connection.php'; 
    include '../include/links.php'; 
    include '../include/search_user.php'; 

    $number = $_SESSION['number'];
    $query = mysqli_query($conn, "SELECT * FROM user WHERE number = '$number'");
    $result = mysqli_fetch_assoc($query);

    // For request accept
    if(isset($_POST['accept_req']))
    {
        $req_sender_number = $_POST['req_sender_number'];
        $req_status = "accept";
        $is_friend = "yes";
        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-y h:i A');

        $req_accept_query = mysqli_query($conn, "UPDATE `add_requests` SET `req_status`='$req_status', `is_friend`='$is_friend', `req_accept_date`='$date' WHERE req_from = '$req_sender_number' AND req_to = '$number'");
        if($req_accept_query)
        {
            echo "<script>alert('Request Accept')</script>";
            echo "<script>window.open('request.php', '_self')</script>";
            exit(); 
        }
        else
        {
            echo "<script>alert('Friend request not accept. Try again later')</script>";
            echo "<script>window.open('request.php', '_self')</script>";
            exit(); 
        }
    }

    // For delete request
    if(isset($_POST['delete_req']))
    {
        $req_sender_number = $_POST['req_sender_number'];

        $req_delete_query = mysqli_query($conn, "DELETE FROM `add_requests` WHERE req_from = '$req_sender_number' AND req_to = '$number'");
        if($req_delete_query)
        {
            echo "<script>alert('Request Deleted')</script>";
            echo "<script>window.open('request.php', '_self')</script>";
            exit(); 
        }
        else
        {
            echo "<script>alert('Request not delete. Try again later')</script>";
            echo "<script>window.open('request.php', '_self')</script>";
            exit(); 
        }
    }
?>
<body class="px-2 py-1 justify-content-md-center align-items-md-center vh-100">
    <h2 class="text-center text-success fw-bold mt-1" style="letter-spacing: .5px;">Real Time Chat Application</h2> <hr>
    <section class="container-fluid p-2 col-12 col-md-5 p-3">
        <div>
            <div class="col-12 d-flex">
                <h4 class="col-10 col-md-11">Recived Requests</h4>
                <a href="request.php" class="text-decoration-none btn btn-outline-primary"><i class="fa-solid fa-rotate-right"></i></a>
            </div> <hr>
            <div>
                <?php 
                    $req_query = mysqli_query($conn, "SELECT * FROM add_requests WHERE req_to = '$number' AND req_status != 'accept'");
                    $req_row = mysqli_num_rows($req_query);
                    if($req_row != 0)
                    {
                        while($req_result = mysqli_fetch_assoc($req_query))
                        {
                            $req_sender_num = $req_result['req_from'];
                            $req_sender_query = mysqli_query($conn, "SELECT * FROM user WHERE number = '$req_sender_num'");
                            $req_sender = mysqli_fetch_assoc($req_sender_query);
                            $imagePath = '../'.$req_sender['image'];

                            echo
                            "
                                <div class='d-flex col-12 mb-3'>
                                    <span class='col-2 d-flex'>
                                        <img src='".$imagePath."' width='50px' height='50px' style='border-radius: 50%;'>
                                    </span>
                                    <span class='col-6 fw-semibold ms-3 ms-md-0' style='font-size: 18px;'>".htmlspecialchars($req_sender['name'])." <br>";
                                        if($req_sender['gender'] == '')
                                        {
                                            echo "-";
                                        }
                                        else
                                        {
                                            echo 
                                            "
                                                <p class='fw-normal' style='font-size: 17px;'>".$req_sender['gender']."</p>
                                            ";
                                        }
                                        echo "
                                    </span>
                                    <span class='col-4 d-flex align-items-center justify-content-end'>";
                                        echo 
                                        "
                                            <form method='POST' style='display:inline;' class='me-2'>
                                                <input type='hidden' value='".htmlspecialchars($req_sender_num)."' name='req_sender_number'>
                                                <button type='submit' name='accept_req' class='btn btn-success fw-semibold'>
                                                    <i class='fa-regular fa-user-plus me-md-2'></i> 
                                                    <p class='d-none d-md-inline'>Accept</p>
                                                </button>
                                            </form>   
                                            
                                            <form method='POST' style='display:inline;'>
                                                <input type='hidden' value='".htmlspecialchars($req_sender_num)."' name='req_sender_number'>
                                                <button type='submit' name='delete_req' class='btn btn-danger fw-semibold' onclick='return confirm(\"Are you sure to delete request?\")'>
                                                    <i class='fa-regular fa-trash'></i>
                                                </button>
                                            </form>   
                                        ";
                                        echo "
                                    </span>
                                </div> <hr>
                            ";
                        }
                        echo "<a href='dashboard.php' class='fw-semibold'>Go Back</a>";
                    }  
                    else
                    {
                        echo "<h5 class='col-12 mt-2'>No request found</h5>";
                        echo "<a href='dashboard.php' class='fw-semibold'>Go Back</a>";
                    }
                ?>
            </div>
        </div>
    </section>
</body>