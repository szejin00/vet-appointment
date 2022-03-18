<?php include('config.php'); ?>
<?php include(INCLUDE_PATH . '/logic/common_functions.php'); ?>
<?php include(ROOT_PATH . '/admin/users/userLogic.php'); ?>
<?php
$sql = "SELECT * FROM clinicop WHERE id=?";
$data = getSingleRecord($sql, 'i', [1]);

$add = $data['clinic_add'];
$cont = $data['clinic_contact'];

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

        <div class="col-md-8 col-md-offset-2">
          <h2 class="text-center">Edit Clinic Information</h2>
          <hr>
          <div class="col-md-6">
            <div class="form-group" style="margin-bottom: 24pt;">
              <label class="control-label">Clinic's Address</label><br><br>
              <label><b><?php echo $add; ?></b></label>
            </div><br><br>
            <div class="form-group" style="margin-bottom: 24pt;">
              <label class="control-label">Clinic's Contact</label><br><br>
              <label><b><?php echo $cont; ?></b></label>
            </div>
          </div>
        </div>


      <!-- </div> -->
    </div>

    <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
  </div>
  <!-- <script type="text/javascript" src="../../assets/js/display_profile_image.js"></script> -->
</body>

</html>