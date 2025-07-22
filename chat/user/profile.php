<?php 
    include '../include/connection.php';
    include '../include/privacy.php'; 
    include '../include/links.php'; 

    $number = $_SESSION['number'];
    $query = mysqli_query($conn, "SELECT * FROM user WHERE number = '$number'");
    $result = mysqli_fetch_assoc($query);

    $receive_request_query = mysqli_query($conn, "SELECT * FROM add_requests WHERE (req_from = '$number' AND is_friend = 'yes') OR (req_to = '$number' AND is_friend = 'yes')");
    $has_request = mysqli_num_rows($receive_request_query);

    // $send_request_query = mysqli_query($conn, "SELECT * FROM add_requests WHERE ");
    // $has_send_request = mysqli_num_rows($send_request_query);
    // $send_request = mysqli_fetch_assoc($send_request_query);

    if(isset($_POST['save']))
    {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $new_number = mysqli_real_escape_string($conn, $_POST['number']); 
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']); 

        $number_exists = false;
    
        if ($new_number != $result['number'])
        { 
            $check_number = "SELECT number FROM `user` WHERE number = '$new_number'";
            $number_result = $conn->query($check_number);

            if ($number_result->num_rows > 0) 
            {
                echo "<script>alert('Phone Number is already exist! Try with different phone number')</script>";
                echo "<script>window.open('profile.php', '_self')</script>";
                exit(); 
            }
        }

        
        $update_query = mysqli_query($conn, "UPDATE `user` SET `name`='$name',`number`='$new_number',`email`='$email',`gender`='$gender' WHERE number = '$number'");

        // Update number in requests table
        $update_num_req1 = mysqli_query($conn, "UPDATE `add_requests` SET `req_from`='$new_number' WHERE req_from = '$number'");
        $update_num_req2 = mysqli_query($conn, "UPDATE `add_requests` SET `req_to`='$new_number' WHERE req_to = '$number'");

        // Update number is message table
        $update_num_mess1 = mysqli_query($conn, "UPDATE `messages` SET `sender`='$new_number' WHERE sender = '$number'");
        $update_num_mess2 = mysqli_query($conn, "UPDATE `messages` SET `receiver`='$new_number' WHERE receiver = '$number'");

        if($update_query && $update_num_req1 && $update_num_req2 && $update_num_mess1 && $update_num_mess2)
        {
            $_SESSION['number'] = $new_number;
            echo "<script>alert('Profile Updated')</script>";
            echo "<script>window.open('profile.php', '_self')</script>";
        }
        else
        {
            echo "<script>alert('An error occur: Database error " . addslashes(mysqli_error($conn)) . "')</script>";
            echo "<script>window.open('profile.php', '_self')</script>";
            exit();
        }
    }

    // Check total number of recieve requests
    $checkReq_query = mysqli_query($conn, "SELECT * FROM `add_requests` WHERE req_to = '$number' AND req_status = 'pending'");
    $checkReq_rows = mysqli_num_rows($checkReq_query);
?>
<link rel="stylesheet" href="../include/style.css">
<body class="p-2" style="background-color: #fff;">
    <h2 class="text-center text-danger fw-bold" style="letter-spacing: .5px;">Real Time Chat Application</h2> <hr>

    <nav class="navbar navbar-expand-lg bg-body-tertiary d-block d-md-none">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse mt-4" id="navbarText">
                <h5 class="fw-semibold">Quick Links</h5>
                <div class="mt-3 d-flex flex-wrap user-action-btns">
                    <a href="dashboard.php"><button class="btn btn-outline-success me-2 mb-3">Dashboard</button></a>
                    <a href="profile.php"><button class="btn btn-success me-2 mb-3">Profile</button></a>
                    <a href="friends.php"><button class="btn btn-outline-success me-2 mb-3">Friends</button></a>
                    <a href="message.php"><button class="btn btn-outline-success me-2 mb-3">Messages</button></a>
                    <a href="logout.php" onclick="return confirm('Are you sure to logout?')"><button class="btn btn-danger">Log Out</button></a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Navbar for long screen  -->
    <nav class="navbar d-none d-md-flex justify-content-around user-action-btns" style="background-color: ">
        <a href="dashboard.php"><button class="btn btn-outline-success">Dashboard</button></a>
        <a href="profile.php"><button class="btn btn-success">Profile</button></a>
        <a href="friends.php"><button class="btn btn-outline-success">Friends</button></a>
        <a href="message.php"><button class="btn btn-outline-success">Messages</button></a>
        <a href="logout.php" onclick="return confirm('Are you sure to logout?')"><button class="btn btn-danger">Log Out</button></a>
    </nav> <hr>
    <section class="col-12 col-md-5 p-3 mt-2 container d-flex justify-content-center" style="background-color: #f7f7f7">
        <div class="form">
            <div>
                <h3 class="text-primary fw-semibold">User Profile</h3> 
            </div> <hr>
            <div class="col-12 mb-3 d-flex">
                <div class="col-4">
                    <img src="<?php echo "../".$result['image'] ?>" width="100px" height="100px" style="border-radius: 50%;">
                </div>
                <div class="col-8 pt-3 d-flex">
                    <a href="friends.php" class="text-decoration-none text-dark">
                        <div class="profile-links ms-3 ms-md-0 me-3 me-md-5">
                            <p class="fw-bold">Friends</p>
                            <p><?php echo $has_request; ?></p>
                        </div>
                    </a>
                    <a href="request.php" class="text-decoration-none text-dark">
                        <div class="profile-links me-3 me-md-4">
                            <p class="fw-bold">Requests</p>
                            <p><?php echo $checkReq_rows; ?></p>
                        </div>
                    </a>
                </div>
            </div>
            <form action="" method="POST" onsubmit="return update_validate();" class="d-flex flex-wrap">
                <div class="form-floating col-7 mb-3 text-start">
                    <input type="name" name="name" value="<?php echo $result['name'] ?>" class="form-control" id="name" autocomplete="off" placeholder="Full Name">
                    <label>Full Name</label>
                </div>
                <div class="form-floating col-5 mb-3 text-end ms-auto">
                    <input type="text" name="number" value="<?php echo $result['number'] ?>" class="form-control" id="number" inputmode="numeric" autocomplete="off" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="10" placeholder="Phone Number">
                    <label>Number</label>
                </div>
                <div class="form-floating col-12 mb-3">
                    <input type="email" name="email" value="<?php echo $result['email'] ?>" class="form-control" id="email" autocomplete="off" placeholder="Email Address">
                    <label>Email Address</label>
                </div>
                <div class="form-floating col-5 mb-3">
                    <select name="gender" class="form-select" id="gender">
                        <option value="" <?php if (empty($result['gender'])) echo 'selected'; ?>>Select Gender</option>
                        <option value="Male" <?php if ($result['gender'] === 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if ($result['gender'] === 'Female') echo 'selected'; ?>>Female</option>
                    </select>
                    <label for="floatingSelectGrid">Gender</label>
                </div>
                <div class="form-floating col-7 mb-3">
                    <input value="<?php echo $result['joined_date'] ?>" class="form-control" readonly>
                    <label>Joined Date</label>
                </div>
                <div class="input-group col-12 mb-3">
                    <input type="submit" class="w-100 btn btn-primary fw-semibold" onclick="return confirm('Are you sure to update profile?')" value="Save Changes" name="save">
                </div>
            </form>
        </div>
    </section>

    <script>
        function update_validate(){
            const nameField = document.getElementById('name');
            const numberField = document.getElementById('number');
            const emailField = document.getElementById('email');
            var genderField = document.getElementById("gender").value;

            if (nameField.value.trim() === '') {
                alert('Please enter your Name.');
                return false;
            }

            if (numberField.value.trim() === '') {
                alert('Please enter your Mobile Number.');
                return false;
            }

            if (emailField.value.trim() === '') {
                alert('Please enter your Email.');
                return false;
            }
            if (genderField === '') {
                alert('Please select your gender');
                return false;
            }
            return true;
        }
    </script>
</body>
</html>