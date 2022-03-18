<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <!-- <div class="container">
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">UserAccounts</a>
            </div> -->
    <!-- <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">Page 1</a></li>
        <li><a href="#">Page 2</a></li>
      </ul> -->
    <nav class="navbar">
        <?php if (isset($_SESSION['user'])) : ?>
            <ul class="navbar-nav">
                <li class="nav-page">
                    <a href="#" class="nav-link">
                        <span class="link-text"><?php echo $_SESSION['user']['username'] ?> </span></a>
                </li>
                <?php if (isAdmin($_SESSION['user']['id'])) : ?>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'admin/users/editProfile.php' ?>" class="nav-link"><span class="link-text">Profile</span></a></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'admin/dashboard.php' ?>" class="nav-link"><span class="link-text">Dashboard</span></a></li>
                    <li role="separator" class="divider"></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'logout.php' ?>" class="nav-link"><span class="link-text">Logout</span></a></li>
                <?php elseif (isVet($_SESSION['user']['id'])) : ?>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'vetProfile.php' ?>" class="nav-link"><span class="link-text">Profile</span></a></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'vetList.php' ?>" class="nav-link"><span class="link-text">Appointment</span></a></li>
                    <li role="separator" class="divider"></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'logout.php' ?>" class="nav-link"><span class="link-text">Logout</span></a></li>
                <?php else : ?>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'index.php' ?>" class="nav-link"><span class="link-text">Home</span></a></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'calendar.php' ?>" class="nav-link"><span class="link-text">Book Appointment</span></a></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'customer-list.php' ?>" class="nav-link"><span class="link-text">Activity</span></a></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'clinicinfo.php' ?>" class="nav-link"><span class="link-text">Clinic Info</span></a></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'profile.php' ?>" class="nav-link"><span class="link-text">Profile</span></a></li>
                    <li role="separator" class="divider"></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'logout.php' ?>" class="nav-link"><span class="link-text">Logout</span></a></li>
                <?php endif; ?>
            <?php else : ?>

                <ul class="navbar-nav">
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'signup.php' ?>" class="nav-link"><span class="link-text"> Sign Up</span></a></li>
                    <li class="nav-page"><a href="<?php echo BASE_URL . 'login.php' ?>" class="nav-link"><span class="link-text"> Login</span></a></li>
                </ul>
            <?php endif; ?>
            </ul>
    </nav>