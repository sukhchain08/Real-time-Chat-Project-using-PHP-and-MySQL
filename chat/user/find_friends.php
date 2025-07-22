<?php
    include '../include/privacy.php';
    include '../include/connection.php';
    include '../include/links.php'; 
    include '../include/search_user.php'; 

    $number = $_SESSION['number'];
    $query = mysqli_query($conn, "SELECT * FROM user WHERE number = '$number'");
    $result = mysqli_fetch_assoc($query);

    $search_value = $_GET['query']; 
    $search_query = mysqli_query($conn, "SELECT * FROM user WHERE name LIKE '%$search_value%' AND number != '$number'");

    // For send friend request  
    if(isset($_POST['send_req']))
    {
        $receiver_number = $_POST['user_number'];
        $status = "pending";
        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-y h:i A');

        $req_query = mysqli_query($conn, "INSERT INTO `add_requests`(`req_from`, `req_to`, `req_status`, `req_sent_date`) VALUES ('$number','$receiver_number','$status','$date')");
        if($req_query)
        {
            echo "<script>alert('Request Send')</script>";
            echo "<script>window.open('find_friends.php?query=".urlencode($search_value)."', '_self')</script>";
            exit(); 
        }
        else
        {
            echo "<script>alert('Request not sent')</script>";
            echo "<script>window.open('find_friends.php?query=".urlencode($search_value)."', '_self')</script>";
            exit(); 
        }
    }

    // For delete friend request 
    if(isset($_POST['delete_req']))
    {
        $receiver_number = $_POST['user_number'];
        $delete_query = mysqli_query($conn, "DELETE FROM `add_requests` WHERE req_from = '$number' AND req_to = '$receiver_number'");
        if($delete_query)
        {
            echo "<script>alert('Request Deleted')</script>";
            echo "<script>window.open('find_friends.php?query=".urlencode($search_value)."', '_self')</script>";
            exit(); 
        }
        else
        {
            echo "<script>alert('Request not deleted')</script>";
            echo "<script>window.open('find_friends.php?query=".urlencode($search_value)."', '_self')</script>";
            exit(); 
        }
    }
?>
<body class="px-2 py-1 justify-content-md-center align-items-md-center vh-100">
    <h2 class="text-center text-success fw-bold mt-1" style="letter-spacing: .5px;">Real Time Chat Application</h2> <hr>
    <section class="container-fluid py-2 col-12 col-md-4 p-3">
        <div>
            <div class="col-12">
                <form onsubmit="return search_validate2();" method="POST" class="d-flex col-12">
                    <input type="search" class="form-control me-2" name="search_value" id="search_value_2" value="<?php echo $search_value ?>" placeholder="Search" autocomplete="off"> 
                    <button type="submit" class="btn btn-primary" name="search"><i class="fa-solid fa-search"></i></button>
                </form>
                <h4 class="col-7 mt-3">Result for <?php echo "<span class='text-success'>".htmlspecialchars($search_value)."</span>" ?></h4>
            </div> <hr>
            <div>
                <?php
                    $search_row = mysqli_num_rows($search_query);
                    if($search_row != 0)
                    {
                        while($search_result = mysqli_fetch_assoc($search_query))
                        {
                            $searched_user_number = $search_result['number'];
                            $imagePath = '../'.$search_result['image'];

                            $receive_request_query = mysqli_query($conn, "SELECT * FROM add_requests WHERE req_from = '$number' AND req_to = '$searched_user_number'");
                            $has_request = mysqli_num_rows($receive_request_query);
                            $request = mysqli_fetch_assoc($receive_request_query);

                            $send_request_query = mysqli_query($conn, "SELECT * FROM add_requests WHERE req_from = '$searched_user_number' AND req_to = '$number'");
                            $has_send_request = mysqli_num_rows($send_request_query);
                            $send_request = mysqli_fetch_assoc($send_request_query);

                            echo "
                                <div class='d-flex col-12 mb-3'>
                                    <span class='col-2 d-flex flex-wrap'>
                                        <img src='".$imagePath."' width='50px' height='50px' style='border-radius: 50%;'>
                                    </span>
                                    <span class='col-6 fw-semibold ps-2 ps-md-0' style='font-size: 18px;'>".htmlspecialchars($search_result['name'])." <br>";
                                        if($search_result['gender'] == '')
                                        {
                                            echo "-";
                                        }
                                        else
                                        {
                                            echo 
                                            "
                                                <p class='fw-normal' style='font-size: 17px;'>".$search_result['gender']."</p>
                                            ";
                                        }
                                        echo "
                                    </span>
                                    <span class='col-4 d-flex align-items-center justify-content-end'>";
                                        if ($has_request && $request['req_status'] == 'pending')                                            
                                        {
                                            echo 
                                            "
                                                <form method='POST' style='display:inline;' >
                                                        <input type='hidden' value='".htmlspecialchars($search_result['number'])."' name='user_number'>
                                                    <input type='hidden' value='".htmlspecialchars($search_value)."' name='current_search_query'>
                                                    <button type='submit' onclick='return confirm(\"Are you sure to delete request?\")' name='delete_req' class='btn btn-danger fw-semibold'>
                                                        Cancel<i class='fa-regular fa-trash ms-2'></i>
                                                    </button>
                                                </form>     
                                            ";
                                        }
                                        elseif ($has_send_request && $send_request['req_to'] == $number && $send_request['req_from'] == $searched_user_number && $send_request['req_status'] == 'accept')
                                        {
                                            echo 
                                            "
                                                <span>
                                                    <a href='message.php?user=".htmlspecialchars($search_result['number'])." &query=".htmlspecialchars($search_value)."' class='btn btn-primary text-decoration-none fw-semibold'>
                                                        <p class='d-none d-md-inline'>Message</p> 
                                                        <i class='fa-solid fa-comment-dots ms-md-2'></i>
                                                    </a>
                                                </span>     
                                            ";
                                        }
                                        elseif ($has_request && $request['req_status'] == 'accept' && $request['is_friend'] == 'yes')
                                        {
                                            echo 
                                            "
                                                <span>
                                                    <a href='message.php?user=".htmlspecialchars($search_result['number'])."' class='btn btn-primary text-decoration-none fw-semibold'>
                                                        <p class='d-none d-md-inline'>Message</p> 
                                                        <i class='fa-solid fa-comment-dots ms-md-2'></i>
                                                    </a>
                                                </span>     
                                            ";
                                        }
                                        elseif (!$has_request )
                                        {
                                            echo 
                                            "
                                                <form method='POST' style='display:inline;'>
                                                    <input type='hidden' value='".htmlspecialchars($search_result['number'])."' name='user_number'>
                                                    <input type='hidden' value='".htmlspecialchars($search_value)."' name='current_search_query'>
                                                    <button type='submit' name='send_req' class='btn btn-success fw-semibold'>
                                                        <span>Add<i class='fa-regular fa-user-plus ms-2'></i>
                                                    </button>
                                                </form>
                                            ";
                                        }
                                        else
                                        {
                                            echo 
                                            "
                                                <form method='POST' style='display:inline;'>
                                                    <input type='hidden' value='".htmlspecialchars($search_result['number'])."' name='user_number'>
                                                    <input type='hidden' value='".htmlspecialchars($search_value)."' name='current_search_query'>
                                                    <button type='submit' name='send_req' class='btn btn-success fw-semibold'>
                                                        <span>Add<i class='fa-regular fa-user-plus ms-2'></i>
                                                    </button>
                                                </form>
                                            ";   
                                        }
                                        echo "
                                    </span>
                                </div> <hr>
                            ";
                        }
                        echo "<a href='dashboard.php' class='fw-semibold'>Go Back</a>";
                    }
                    else
                    {
                        echo "<h5 class='col-12 mt-2'>User Not Found</h5>";
                        echo "<a href='dashboard.php' class='fw-semibold'>Go Back</a>";
                    }
                ?>
            </div>
        </div>
    </section>
    <script src="../script/user.js"></script>
</body>
</html>