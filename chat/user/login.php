<?php 
    include '../include/connection.php';
    session_start();
    
    if(isset($_POST['login']))
    {
        $number = $_POST['number'];
        $password = $_POST['password'];
        
        $query = mysqli_query($conn, "SELECT * FROM user WHERE number = '$number'");
        if(mysqli_num_rows($query) > 0)
        {
            $row = mysqli_fetch_assoc($query);
            $enter_enc_password = md5($password);
            $enc_password = $row['password'];
            if($enter_enc_password === $enc_password)
            {
                $_SESSION['user_logged'] = true;
                $_SESSION['number'] = $number;

                $status = "online";
                $update_status = mysqli_query($conn, "UPDATE `user` SET `status`='$status' WHERE number = '$number'");
                if($update_status)
                {
                    echo "<script>window.open('dashboard.php', '_self')</script>";
                }
            }
            else 
            {
                echo "<script>alert('Password Incorrect')</script>";
                echo "<script>window.open('login.php', '_self')</script>";
            }
        }
        else
        {
            echo "<script>alert('User not found')</script>";
            echo "<script>window.open('login.php', '_self')</script>";
        }
    }
?>

<?php include '../include/links.php'; ?>
<body class="p-2 d-flex justify-content-md-center align-items-md-center vh-100">
    <section class="col-12 col-md-4 p-3 main-page-section">
        <div class="form">
            <div>
                <h1 class="text-success font-semibold">User Login</h1>
            </div> <hr>
            <form method="POST" onsubmit="return login_validate();" class="d-flex flex-wrap">
                <div class="form-floating col-12 mb-3">
                    <input type="text" name="number" class="form-control" id="number" inputmode="numeric" autocomplete="off" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="10" placeholder="Phone Number">
                    <label>Phone Number</label>
                </div>
                <div class="form-floating col-12 mb-3 position-relative field">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    <label>Password</label>
                    <i class="fas fa-eye toggle-password" style="position: absolute; top: 50%; right: 15px; transform: translateY(-50%); cursor: pointer;"></i>
                </div>
                <div class="d-flex col-lg-12 fw-semibold my-2" style="font-size: 16px;">
                    <a href="../index.php" class="ms-2" style="color: #1264a3;">Create Account</a>
                </div>
                <div class="input-group col-12 mb-3">
                    <input type="submit" class="w-100 btn btn-primary fw-semibold" value="Login" name="login">
                </div>
            </form>
        </div>
    </section>
    
    <script src="../script/signup.js"></script>
</body>
</html>