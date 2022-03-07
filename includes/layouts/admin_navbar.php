<!-- the whole site is wrapped in a container div to give it some margin on the sides -->
<!-- closing container div can be found in the footer -->

<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <!-- <div class="container">
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="<?php echo BASE_URL . 'admin/dashboard.php' ?>">Dashboard</a>
            </div> -->
    <nav class="navbar">
        <?php if (isset($_SESSION['user'])) : ?>
            <ul class="navbar-nav">
                <!-- <li class="nav-page"><a href="<?php echo BASE_URL . 'index.php' ?>" class="nav-link"><span class="glyphicon glyphicon-globe"></span></a></li> -->
                <!-- <li class="dropdown"> -->
                <li class="nav-page">
                    <a href="#" class="nav-link">
                        <span class="link-text"><?php echo $_SESSION['user']['username'] . ' (' . $_SESSION['user']['role'] . ')'; ?></span></a>
                </li>
                <!-- <ul class="dropdown-menu"> -->
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'admin/users/editProfile.php' ?>" class="nav-link"><span class="link-text">Profile</span></a></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'admin/dashboard.php' ?>" class="nav-link"><span class="link-text">Dashboard</span></a></li>
                    <li role="separator" class="divider"></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'logout.php' ?>" class="nav-link"><span class="link-text">Logout</span></a></li>
                <!-- </ul> -->
                <!-- </li> -->
            <?php endif; ?>
            </ul>
            <!-- </div> -->
    </nav>
    <?php include(INCLUDE_PATH . "/layouts/messages.php") ?>