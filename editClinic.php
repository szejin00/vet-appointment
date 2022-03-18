<?php include('config.php'); ?>
<?php include(INCLUDE_PATH . '/logic/common_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/users/userLogic.php'); ?>
<?php
$sql = "SELECT * FROM clinicop WHERE id=?";
$data = getSingleRecord($sql, 'i', [1]);

$add = $data['clinic_add'];
$cont = $data['clinic_contact'];

if(isset($_POST['update_data'])){
    $add= $_POST['address'];
    $cont= $_POST['contact'];
    $stmt = $mysqli->prepare("UPDATE clinicop SET clinic_add=?,clinic_contact=? WHERE id=1");
    $stmt->bind_param('ss', $add, $cont);
    $stmt->execute();

}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Edit Clinic Info</title>
  <!-- Bootstrap CSS -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> -->
  <!-- Custom styles -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <?php include(INCLUDE_PATH . "/layouts/navbar.php")?>
  <div class="center-item">
    <div class="container">
      <!-- <div class="row"> -->

      <form action="" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?php echo $user_id ?>">
        <div class="col-md-8 col-md-offset-2">
          <h2 class="text-center">Edit Clinic Information</h2>
          <hr>
          <div class="col-md-6">
            <div class="form-group" style="margin-bottom: 24pt;">
              <label class="control-label">Clinic's Address</label>
              <textarea class="text-box" name="address" class="form-control" rows="4" style="resize:none;"><?php echo $add; ?></textarea>
            </div>
            <div class="form-group" style="margin-bottom: 24pt;">
              <label class="control-label">Clinic's Contact</label>
              <input type="text" class="text-box" name="contact" value="<?php echo $cont; ?>" class="form-control" pattern="^(\+?6?03)[0-46-9]-*[0-9]{7,8}$">
            </div>
            <div class="btn-cont">
              <button type="submit" id="borderline" name="update_data" class="btn btn-success">Update Info</button>
            </div>
          </div>
        </div>
      </form>

      <!-- </div> -->
    </div>

    <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
  </div>
  <!-- <script type="text/javascript" src="../../assets/js/display_profile_image.js"></script> -->
</body>

</html>