<?php include('../../config.php'); ?>
<?php include(INCLUDE_PATH . '/logic/common_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/middleware.php'); ?>
<?php include(ROOT_PATH . '/admin/users/userLogic.php'); ?>
<?php
$sql = "SELECT id, username, email FROM users WHERE id=?";
$user = getSingleRecord($sql, 'i', [$_SESSION['user']['id']]);
$roles = getMultipleRecords("SELECT * FROM roles");

$user_id = $user['id'];
$username = $user['username'];
$email = $user['email'];
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Admin - Edit Profile</title>
  <!-- Bootstrap CSS
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> -->
  <!-- Custom styles -->
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>

<body>
  <?php include(INCLUDE_PATH . "/layouts/admin_navbar.php") ?>
  <div class="center-item">
    <div class="container">
      <div class="row">

        <form action="editProfile.php" method="post" enctype="multipart/form-data">
          <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
          <div class="col-md-8 col-md-offset-2">
            <h2 class="text-center">Edit Your Profile Info</h2>
            <hr><br>
            <div class="col-md-6">
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
              <div class="form-group <?php echo isset($errors['passwordOld']) ? 'has-error' : '' ?>" style="margin-bottom: 24pt;">
                <label class="control-label">Old Password</label>
                <input type="password" class="text-box" name="passwordOld" class="form-control">
                <?php if (isset($errors['passwordOld'])) : ?>
                  <span class="help-block"><?php echo $errors['passwordOld'] ?></span>
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
              <div class="form-group <?php echo isset($errors['role_id']) ? 'has-error' : '' ?>" style="margin-bottom: 24pt;">
                <label class="control-label">User Role</label>
                <select class="form-control" name="role_id">
                  <option value=""></option>
                  <?php foreach ($roles as $role) : ?>
                    <?php if ($role['name'] === $_SESSION['user']['role']) : ?>
                      <option value="<?php echo $role['id'] ?>" selected><?php echo $role['name'] ?></option>
                    <?php else : ?>
                      <option value="<?php echo $role['id'] ?>"><?php echo $role['name'] ?></option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </select>
                <?php if (isset($errors['role_id'])) : ?>
                  <span class="help-block"><?php echo $errors['role_id'] ?></span>
                <?php endif; ?>
              </div>
              <div class="btn-cont">
                <button type="submit" name="update_profile" class="btn btn-success">Update Profile</button>
              </div>
            </div>
          </div>
        </form>

      </div>
    </div>
    <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
  </div>
  <script type="text/javascript" src="../../assets/js/display_profile_image.js"></script>