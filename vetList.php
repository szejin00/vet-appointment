<?php
// Include the database configuration file
require_once 'config.php';
// Get image data from database 

// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';


$id = $_SESSION['user']['username'];
$result_approved = $mysqli->query("SELECT * FROM bookings WHERE approval='approved' AND vet='". $id ."' ORDER BY date DESC");
$result_pending = $mysqli->query("SELECT * FROM bookings WHERE approval='pending' AND vet='". $id ."' ORDER BY date DESC");
$result_paid = $mysqli->query("SELECT * FROM bookings WHERE approval='paid' AND vet='". $id ."' ORDER BY date DESC");
$result_completed = $mysqli->query("SELECT * FROM bookings WHERE approval='completed' AND vet='". $id ."' ORDER BY date DESC");


if (isset($_POST['submit'])) {
    $row_id = $_POST['id'];
    $approval = 'completed';
    $stmt = $mysqli->prepare("UPDATE bookings SET approval=? WHERE id=$row_id");
    $stmt->bind_param('s', $approval);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
    header("location: vet2apt.php");
}
?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    </style>
</head>

<body>

    <div class="center-item">
        <div class="container" id="customer-list-container">
            <a href="<?php echo BASE_URL . 'index.php' ?>" class="normal-link">Home</a><br><br><br>

            <?php if ($result_approved->num_rows > 0) { ?>
                <h4>Appointment Approved</h4>
                <hr>
                <table class="table table-bordered" id="userlist">
                    <thead>
                        <tr>
                            <th class="textsize">ID</th>
                            <th class="textsize">Pet Owner</th>
                            <th class="textsize">Pet Name</th>
                            <th class="textsize">Pet Type</th>
                            <th class="textsize">Reason</th>
                            <th class="textsize">Date</th>
                            <th class="textsize">Timeslot</th>
                            <th class="textsize">Vet</th>
                            <th colspan="2" class="text-center textsize">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php while ($row = $result_approved->fetch_assoc()) { ?>
                                <td class="textsize"><?php echo $row['id'] ?></td>
                                <td class="textsize"><?php echo $row['name'] ?></td>
                                <td class="textsize"><?php echo $row['petname'] ?></td>
                                <td class="textsize"><?php echo $row['pettype'] ?></td>
                                <td class="textsize"><?php echo $row['reason'] ?></td>
                                <td class="textsize"><?php echo $row['date'] ?></td>
                                <td class="textsize"><?php echo $row['timeslot'] ?></td>
                                <td class="textsize"><?php echo $row['vet'] ?></td>
                                <td class="textsize"><?php echo $row['approval'] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>

            <?php } ?>
            <?php if ($result_pending->num_rows > 0) { ?>
                <h4>Appointment Pending</h4>
                <hr>
                <table class="table table-bordered" id="userlist">
                    <thead>
                        <tr>
                            <th class="textsize">ID</th>
                            <th class="textsize">Pet Owner</th>
                            <th class="textsize">Pet Name</th>
                            <th class="textsize">Pet Type</th>
                            <th class="textsize">Reason</th>
                            <th class="textsize">Date</th>
                            <th class="textsize">Timeslot</th>
                            <th class="textsize">Vet</th>
                            <th colspan="2" class="text-center textsize">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php while ($row = $result_pending->fetch_assoc()) { ?>

                                <td class="textsize"><?php echo $row['id'] ?></td>
                                <td class="textsize"><?php echo $row['name'] ?></td>
                                <td class="textsize"><?php echo $row['petname'] ?></td>
                                <td class="textsize"><?php echo $row['pettype'] ?></td>
                                <td class="textsize"><?php echo $row['reason'] ?></td>
                                <td class="textsize"><?php echo $row['date'] ?></td>
                                <td class="textsize"><?php echo $row['timeslot'] ?></td>
                                <td class="textsize"><?php echo $row['vet'] ?></td>
                                <td class="textsize"><?php echo $row['approval'] ?></td>

                        </tr>

                    <?php } ?>
                    </tbody>
                </table>

            <?php } else { ?>

            <?php } ?>


            <?php if ($result_paid->num_rows > 0) { ?>
                <h4>Appointment Paid</h4>
                <hr>
                <table class="table table-bordered" id="userlist">
                    <thead>
                        <tr>
                            <th class="textsize">ID</th>
                            <th class="textsize">Pet Owner</th>
                            <th class="textsize">Pet Name</th>
                            <th class="textsize">Pet Type</th>
                            <th class="textsize">Reason</th>
                            <th class="textsize">Date</th>
                            <th class="textsize">Timeslot</th>
                            <th class="textsize">Vet</th>
                            <th class="text-center textsize">Status</th>
                            <th class="text-center textsize">Complete Appointment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php while ($row = $result_paid->fetch_assoc()) { ?>

                                <td class="textsize"><?php echo $row['id'] ?></td>
                                <td class="textsize"><?php echo $row['name'] ?></td>
                                <td class="textsize"><?php echo $row['petname'] ?></td>
                                <td class="textsize"><?php echo $row['pettype'] ?></td>
                                <td class="textsize"><?php echo $row['reason'] ?></td>
                                <td class="textsize"><?php echo $row['date'] ?></td>
                                <td class="textsize"><?php echo $row['timeslot'] ?></td>
                                <td class="textsize"><?php echo $row['vet'] ?></td>
                                <td class="textsize"><?php echo $row['approval'] ?></td>
                                <td class="text-center textsize">
                                    <center>
                                        <form style="margin-top: 10px"action="" method="post" enctype="multipart/form-data">
                                            <input name="id" type="hidden" value=" <?php echo $row['id'] ?> ">
                                            <input name="approval" type="hidden" value="completed">
                                            <input type="submit" name="submit" value="Completed">
                                    </center>
                                    </form>
                                </td>
                        </tr>

                    <?php } ?>
                    </tbody>
                </table>
            <?php } else { ?>

            <?php } ?>
            <?php if ($result_completed->num_rows > 0) { ?>
                <h4>Appointment Completed</h4>
                <hr>
                <table class="table table-bordered" id="userlist">
                    <thead>
                        <tr>
                            <th class="textsize">ID</th>
                            <th class="textsize">Pet Owner</th>
                            <th class="textsize">Pet Name</th>
                            <th class="textsize">Pet Type</th>
                            <th class="textsize">Reason</th>
                            <th class="textsize">Date</th>
                            <th class="textsize">Timeslot</th>
                            <th class="textsize">Vet</th>
                            <th colspan="2" class="text-center textsize">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php while ($row = $result_completed->fetch_assoc()) { ?>

                                <td class="textsize"><?php echo $row['id'] ?></td>
                                <td class="textsize"><?php echo $row['name'] ?></td>
                                <td class="textsize"><?php echo $row['petname'] ?></td>
                                <td class="textsize"><?php echo $row['pettype'] ?></td>
                                <td class="textsize"><?php echo $row['reason'] ?></td>
                                <td class="textsize"><?php echo $row['date'] ?></td>
                                <td class="textsize"><?php echo $row['timeslot'] ?></td>
                                <td class="textsize"><?php echo $row['vet'] ?></td>
                                <td class="textsize"><?php echo $row['approval'] ?></td>

                        </tr>

                    <?php } ?>
                    </tbody>
                </table>

            <?php } else { ?>

            <?php } ?>

        </div>
    </div>
</body>

</html>