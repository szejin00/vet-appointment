<?php include('config.php'); ?>
<?php include(INCLUDE_PATH . '/logic/userSignup.php'); ?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>UserAccounts - Sign up</title>
    <!-- Bootstrap CSS -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> -->
    <!-- Custom styles -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <?php include(INCLUDE_PATH . "/layouts/navbar.php") ?>
    <div class="center-item">
        <div class="container">
            <!-- <div class="row">
            <div class="col-md-4 col-md-offset-4"> -->
            <form class="form" action="signup.php" method="post" enctype="multipart/form-data">
                <h2 class="text-center">Sign up</h2>
                <hr>
                <div class="form-group <?php echo isset($errors['username']) ? 'has-error' : '' ?>" style="margin-bottom: 24pt;">
                    <label class="control-label">Username</label>
                    <input type="text" class="text-box" name="username" value="<?php echo $username; ?>" class="form-control">
                    <?php if (isset($errors['username'])) : ?>
                        <span class="help-block"><?php echo $errors['username'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group <?php echo isset($errors['email']) ? 'has-error' : '' ?>" style="margin-bottom: 24pt;">
                    <label class="control-label">Email Address</label>
                    <input type="email" class="text-box" name="email" value="<?php echo $email; ?>" class="form-control">
                    <?php if (isset($errors['email'])) : ?>
                        <span class="help-block"><?php echo $errors['email'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group <?php echo isset($errors['password']) ? 'has-error' : '' ?>" style="margin-bottom: 24pt;">
                    <label class="control-label">Password</label>
                    <input type="password" class="text-box" name="password" class="form-control">
                    <?php if (isset($errors['password'])) : ?>
                        <span class="help-block"><?php echo $errors['password'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group <?php echo isset($errors['passwordConf']) ? 'has-error' : '' ?>" style="margin-bottom: 24pt;">
                    <label class="control-label">Password confirmation</label>
                    <input type="password" class="text-box" name="passwordConf" class="form-control">
                    <?php if (isset($errors['passwordConf'])) : ?>
                        <span class="help-block"><?php echo $errors['passwordConf'] ?></span>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <button type="submit" id="borderline" name="signup_btn" class="btn btn-success btn-block">Sign up</button>
                </div>
                <p>Aready have an account? <a href="login.php" class="normal-link">Sign in</a></p>
            </form>
            <!-- </div> -->
            <!-- </div> -->
        </div>
        <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
    </div>
</body>

</html>