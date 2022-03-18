<?php include('../config.php') ?>
<?php require_once 'middleware.php'; ?>
<?php 
$sql1 = "SELECT username FROM users WHERE id=?";
$vet = getSingleRecord($sql1, 'i', [22]);
$vet1 = $vet['username'];
$sql2 = "SELECT username FROM users WHERE id=?";
$vet = getSingleRecord($sql2, 'i', [23]);
$vet2 = $vet['username'];
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> -->
    <!-- Custome styles -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <?php include(INCLUDE_PATH . "/layouts/admin_navbar.php") ?>
    <div class="center-item">
        <?php include(INCLUDE_PATH . "/layouts/messages.php") ?>
        <div class="container">
            <div class="col-md-4 col-md-offset-4">
                <h1 class="text-center">Admin</h1>
                <br />
                <ul class="list-grp">
                    <a href="<?php echo BASE_URL . 'admin-calendar.php' ?>" class="list-grp-item">Book appointment</a>
                    <a href="<?php echo BASE_URL . 'vet1apt.php' ?>" class="list-grp-item">DR. <?php echo $vet1 ?> appointment</a>
                    <a href="<?php echo BASE_URL . 'vet2apt.php' ?>" class="list-grp-item">DR. <?php echo $vet2 ?> appointment</a>
                    <a href="<?php echo BASE_URL . 'admin-activity.php' ?>" class="list-grp-item">Activity</a>
                    <a href="<?php echo BASE_URL . 'admin/users/userList.php' ?>" class="list-grp-item">Manage Admin Users</a>
                    <a href="<?php echo BASE_URL . 'admin/users/vetuserList.php' ?>" class="list-grp-item">Manage Vet Users</a>
                    <a href="<?php echo BASE_URL . 'admin/users/customerList.php' ?>" class="list-grp-item">Manage Customer Users</a>
                    <a href="<?php echo BASE_URL . 'editClinic.php' ?>" class="list-grp-item">Edit Operation Details</a>
                </ul>
            </div>
        </div>
        <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
    </div>
</body>

</html>