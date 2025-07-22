<?php 
    include 'include/connection.php';

    if(isset($_POST['create-account']))
    {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $number = mysqli_real_escape_string($conn, $_POST['number']); // This is the mobile number
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $gender = mysqli_real_escape_string($conn, $_POST['gender']);
        $enc_password = md5($password);

        $status = mysqli_real_escape_string($conn, $_POST['status']);

        date_default_timezone_set('Asia/Kolkata');
        $date = date('d-m-y h:i A');

        $number_exists = false;
    
        // Check number
        $check_number = "SELECT number FROM `user` WHERE number = '$number'";
        $number_result = $conn->query($check_number);
        if($number_result->num_rows > 0) 
        {
            echo "<script>alert('Phone number already exist! Try with different Nnunber')</script>";
            echo "<script>window.open('index.php', '_self')</script>";
            $number_exists = true;
        }

        if(!$number_exists)
        {
            // Image Upload Validation
            $filename = $_FILES["image"]["name"];
            $tempname = $_FILES["image"]["tmp_name"];
            $file_type = $_FILES['image']['type']; // Get the MIME type
            $file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION)); // Get the file extension

            $allowed_extensions = array('jpg', 'jpeg', 'png');
            $allowed_mime_types = array('image/jpeg', 'image/png');

            if (in_array($file_ext, $allowed_extensions) && in_array($file_type, $allowed_mime_types)) {
                $new_filename = $number . '.' . $file_ext;

                $folder = "image/".$new_filename; // Use the new filename here
                if (move_uploaded_file($tempname, $folder)) 
                {
                    $query = mysqli_query($conn, "INSERT INTO `user`(`name`, `number`, `email`, `password`, `image`, `gender`, `joined_date`, `status`) VALUES ('$name','$number','$email','$enc_password','$folder','$gender','$date','$status')");
                    if($query)
                    {
                        echo "<script>alert('Account Created. Click Ok for Login')</script>";
                        echo "<script>window.open('user/login.php', '_self')</script>";
                    }
                    else
                    {
                        echo "<script>alert('Failed to create new account: Database error " . addslashes(mysqli_error($conn)) . "')</script>";
                        echo "<script>window.open('index.php', '_self')</script>";
                    }
                } 
                else 
                {
                    echo "<script>alert('Failed to upload image. " . addslashes(mysqli_error($conn)) . "')</script>";
                    echo "<script>window.open('index.php', '_self')</script>";
                }
            } 
            else 
                {
                echo "<script>alert('Invalid image type. Only JPG, JPEG, and PNG images are allowed.')</script>";
                echo "<script>window.open('index.php', '_self')</script>";
            }
        }
    }
?>

<?php include 'include/links.php'; ?>
<body class="p-2 d-flex justify-content-md-center align-items-md-center">
    <section class="col-12 col-md-4 p-3 main-page-section">
        <div class="form">
            <div>
                <h1 class="text-success fw-semibold">User Signup</h1> 
            </div> <hr>
            <form action="" method="POST" onsubmit="return signup_validate();" class="d-flex flex-wrap" enctype="multipart/form-data">
                <div class="form-floating col-7 mb-3 text-start">
                    <input type="name" name="name" class="form-control" id="name" autocomplete="off" placeholder="Full Name">
                    <label>Full Name</label>
                </div>
                <div class="form-floating col-5 mb-3 text-end" style="position: relative; left: 2px;">
                    <input type="text" name="number" class="form-control" id="number" inputmode="numeric" autocomplete="off" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="10" placeholder="Number">
                    <label>Number</label>
                </div>
                <div class="form-floating col-12 mb-3">
                    <input type="email" name="email" class="form-control" id="email" autocomplete="off" placeholder="Email Address">
                    <label>Email Address</label>
                </div>
                <div class="form-floating col-12 mb-3">
                    <select name="gender" class="form-select" id="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                    <label for="floatingSelectGrid">Gender</label>
                </div>
                <div class="form-floating col-12 mb-3 position-relative field">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    <label>Password</label>
                    <i class="fas fa-eye toggle-password" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                </div>
                <div class="input-group col-12 mb-3">
                    <input type="file" name="image" class="form-control" id="inputGroupFile02" accept=".jpg, .jpeg, .png">
                </div>
                <input type="hidden" name="status" value="offline">
                <div class="d-flex col-lg-12 fw-semibold my-2" style="font-size: 16px;">
                    Already Signed Up?<a href="user/login.php" class="ms-2" style="color: #1264a3;">Login Now</a>
                </div>
                <div class="input-group col-12 mb-3">
                    <input type="submit" class="w-100 btn btn-primary" value="Create Account" name="create-account">
                </div>
            </form>
        </div>
    </section>

    <script src="script/signup.js"></script>
</body>
</html>