<?php 
    include '../include/privacy.php'; 
    include '../include/connection.php'; 
    include '../include/links.php'; 
    include '../include/search_user.php'; 

    $number = $_SESSION['number'];
    $query = mysqli_query($conn, "SELECT * FROM user WHERE number = '$number'");
    $result = mysqli_fetch_assoc($query);

    // Check total number of recieve requests
    $checkReq_query = mysqli_query($conn, "SELECT * FROM `add_requests` WHERE req_to = '$number' AND req_status = 'pending'");
    $checkReq_rows = mysqli_num_rows($checkReq_query);
?>
<body class="px-2 py-1">
    <section class="container-fluid p-2">
        <?php include '../include/header.php'; ?>

        <!-- Navbar for small screen  -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary d-block d-md-none">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse mt-4" id="navbarText">
                    <h5 class="fw-semibold">Quick Links</h5>
                    <div class="mt-3 d-flex flex-wrap user-action-btns">
                        <a href="dashboard.php"><button class="btn btn-success me-2 mb-3">Dashboard</button></a>
                        <a href="profile.php"><button class="btn btn-outline-success me-2 mb-3">Profile</button></a>
                        <a href="friends.php"><button class="btn btn-outline-success me-2 mb-3">Friends</button></a>
                        <a href="message.php"><button class="btn btn-outline-success me-2 mb-3">Messages</button></a>
                    </div>
                </div>
            </div>
        </nav>
        <span class="d-block d-lg-none mt-3">
            <form onsubmit="return search_validate2();" method="POST" class="d-flex col-12">
                <input type="search" class="form-control me-2" name="search_value" id="search_value_2" placeholder="Search New Friends" autocomplete="off"> 
                <input type="submit" value="Search" class="btn btn-primary" name="search">
            </form>
            <p class="text-end mt-3">
                <a href="request.php">
                    <?php 
                        if($checkReq_rows != 0)
                        {
                            echo "<i class='fa-solid fa-bell-ring btn btn-outline-success'></i>";
                        }
                        else
                        {
                            echo "<i class='fa-solid fa-bell btn btn-outline-success'></i>";
                        }
                    ?>
                </a>
            </p>
        </span>

        <!-- Navbar for long screen  -->
        <nav class="navbar d-none d-md-flex justify-content-around user-action-btns">
            <a href="dashboard.php"><button class="btn btn-success">Dashboard</button></a>
            <a href="profile.php"><button class="btn btn-outline-success">Profile</button></a>
            <a href="friends.php"><button class="btn btn-outline-success">Friends</button></a>
            <a href="message.php"><button class="btn btn-outline-success">Messages</button></a>
            <a href="request.php">
                <?php 
                    if($checkReq_rows != 0)
                    {
                        echo "<i class='fa-solid fa-bell-ring btn btn-outline-success'></i>";
                    }
                    else
                    {
                        echo "<i class='fa-solid fa-bell btn btn-outline-success'></i>";
                    }
                ?>
            </a>
        </nav>
    </section>

    <script src="../script/user.js"></script>
</body>
</html>