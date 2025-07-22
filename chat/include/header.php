<?php include 'search_user.php'; ?>
<h2 class="text-center text-success fw-bold" style="letter-spacing: .5px;">Real Time Chat Application</h2> <hr>
<div class="d-flex justify-content-between col-12">
    <span>
        <h4 class=" d-flex flex-wrap top-username">
            <?php echo "Hello, " . $result['name']; ?>
        </h4>
    </span>
    <span class="d-none d-lg-flex">
        <form onsubmit="return search_validate1();" class="d-none d-lg-flex" method="POST">
            <input type="search" class="form-control me-2" name="search_value" id="search_value_1" autocomplete="off" style="width: 300px;" placeholder="Search New Friends"> 
            <input type="submit" value="Search" class="btn btn-primary" name="search">
        </form>
    </span>
    <span>
        <a href="logout.php" class="btn btn-danger text-decoration-none fw-semibold text-nowrap" onclick="return confirm('Are you sure to logout?')" >Log Out</a>
    </span>
</div> <hr>