<?php 
    include '../include/privacy.php'; 
    include '../include/connection.php'; 
    include '../include/links.php'; 
    include '../include/search_user.php'; 

    $number = $_SESSION['number'];
    $query = mysqli_query($conn, "SELECT * FROM user WHERE number = '$number'");
    $result = mysqli_fetch_assoc($query);

    $search_value = "";
    if (isset($_GET['search_friend'])) {
        $search_value = mysqli_real_escape_string($conn, $_GET['search_friend']);
    }
    if (!empty($search_value)) 
    {
        $all_users_query = mysqli_query($conn, "SELECT * FROM user WHERE number != '$number' AND name LIKE '%$search_value%'");
    } 
    else 
    {
        $all_users_query = mysqli_query($conn, "SELECT * FROM user WHERE number != '$number'");
    }
    $all_user_row = mysqli_num_rows($all_users_query);

    // For delete request
    if(isset($_POST['delete_req']))
    {
        $user_num = $_POST['user'];
        $frnd_delete_query1 = mysqli_query($conn, "DELETE FROM `add_requests` WHERE req_from = '$user_num' AND req_to = '$number'");
        $frnd_delete_query2 = mysqli_query($conn, "DELETE FROM `add_requests` WHERE req_from = '$number' AND req_to = '$user_num'");
        if($frnd_delete_query1 && $frnd_delete_query2)
        {
            echo "<script>alert('Removed')</script>";
            echo "<script>window.open('friends.php', '_self')</script>";
            exit(); 
        }
        else
        {
            echo "<script>alert('Not removed. Try again later')</script>";
            echo "<script>window.open('friends.php', '_self')</script>";
            exit();
        }
    }
    
    if(isset($_POST['message_friend']))
    {
        $user = $_POST['user'];
        echo "<script>window.open('message.php?user=".htmlspecialchars($user)."', '_self')</script>";

    }
?>
<body class="px-2 py-1 justify-content-md-center align-items-md-center vh-100">
    <h2 class="text-center text-success fw-bold mt-1" style="letter-spacing: .5px;">Real Time Chat Application</h2> <hr>
    <section class="container-fluid p-2 col-12 col-md-5 p-3">
        <div>
            <div class="col-12">
                <h4 class="col-10 fw-semibold pb-2">My Friends</h4>
                <form  method="GET" class="d-flex col-12">
                    <input type="search" class="form-control me-2" value="<?php echo htmlspecialchars($search_value); ?>" name="search_friend" placeholder="Search Friends" autocomplete="off"> 
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-search"></i></button>
                </form> 
            </div> <hr>
            <div>
                <?php 
                    if($all_user_row != 0)
                    {
                        while($all_user = mysqli_fetch_assoc($all_users_query))
                        {
                            $imagePath = '../'.$all_user['image'];

                            $friend_query = mysqli_query($conn, "SELECT * FROM `add_requests` WHERE ((req_from = $number AND req_to = {$all_user['number']} AND is_friend = 'yes') OR (req_from = {$all_user['number']} AND req_to = $number AND is_friend = 'yes'))");
                            $friend_result = mysqli_fetch_assoc($friend_query);
                            
                            if($friend_result = mysqli_num_rows($friend_query) != 0)
                            {
                                echo 
                                "
                                    <div class='d-flex col-12 mb-3'>
                                        <span class='col-2 d-flex'>
                                            <img src='".$imagePath."' width='50px' height='50px' style='border-radius: 50%;'>
                                        </span>
                                        <span class='col-6 fw-semibold ms-3 ms-md-0' style='font-size: 18px;'>".htmlspecialchars($all_user['name'])." <br>";
                                            if($all_user['gender'] == '')
                                            {
                                                echo "-";
                                            }
                                            else
                                            {
                                                echo 
                                                "
                                                    <p class='fw-normal' style='font-size: 17px;'>".$all_user['gender']."</p>
                                                ";
                                            }
                                            echo "
                                        </span>
                                        <span class='col-4 d-flex align-items-center justify-content-end'>
                                            <a href='message.php?user=".htmlspecialchars($all_user['number'])."' class='btn btn-primary text-decoration-none fw-semibold me-2'>
                                                <p class='d-none d-md-inline'>Message</p> 
                                                <i class='fa-solid fa-comment-dots ms-md-2'></i>
                                            </a>
                                            <form method='POST' style='display:inline;'>
                                                <input type='hidden' value='".htmlspecialchars($all_user['number'])."' name='user'>
                                                <button type='submit' name='delete_req' class='btn btn-danger fw-semibold' onclick='return confirm(\"If you change your mind, you will have to request to add again?\")'>
                                                    <i class='fa-regular fa-trash'></i>
                                                </button>
                                            </form>
                                        </span>
                                    </div> <hr>
                                ";
                            }
                        }
                        echo "<a href='dashboard.php' class='fw-semibold'>Go Back</a>";
                    }
                    else
                    {
                        echo 
                        "
                            <h5 class='col-12 mt-2'>User Not Found</h5> 
                            <a href='dashboard.php' class='fw-semibold'>Go Back</a>    
                        ";
                    }
                ?>
            </div>
        </div>
    </section>
</body>
</html>