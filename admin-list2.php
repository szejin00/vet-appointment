<?php
// Include the database configuration file
require_once 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

$sql1 = "SELECT username FROM users WHERE id=?";
$vet = getSingleRecord($sql1, 'i', [22]);
$vet1 = $vet['username'];
$sql2 = "SELECT username FROM users WHERE id=?";
$vet = getSingleRecord($sql2, 'i', [23]);
$vet2 = $vet['username'];

$date = $_GET['date'];
$result = $mysqli->query("SELECT * FROM bookings WHERE approval='pending' AND vet='$vet1' AND date='$date'");
$result_vet2 = $mysqli->query("SELECT * FROM bookings WHERE approval='pending' and vet='$vet2' AND date='$date'");
$apt_vet1 = $mysqli->query("SELECT * FROM bookings WHERE approval='paid' and vet='$vet1' AND date='$date'");
$apt_vet2 = $mysqli->query("SELECT * FROM bookings WHERE approval='paid' and vet='$vet2' AND date='$date'");
$approved_vet1 = $mysqli->query("SELECT * FROM bookings WHERE approval='approved' and vet='$vet1' AND date='$date'");
$approved_vet2 = $mysqli->query("SELECT * FROM bookings WHERE approval='approved' and vet='$vet2' AND date='$date'");
$result_completed1 = $mysqli->query("SELECT * FROM bookings WHERE approval='completed' and vet='$vet1' AND date='$date'");
$result_completed2 = $mysqli->query("SELECT * FROM bookings WHERE approval='completed' and vet='$vet2' AND date='$date'");

// echo "<a href='operation.php?date=" . $date . "&vet=" . $vet1 . "'>Vet 1</a>";
// echo "<a href='book.php?date=" . $date . "&vet=" . $vet2 . "'>Vet 2</a>";

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    $approval = $_POST['approval'];
    $stmt = $mysqli->prepare("select * from bookings where id = ?");
    $stmt->bind_param('i', $id,);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows < 0) {
            $msg = "<div class='alert alert-danger'>No booking found.</div>";
        } else {
            $stmt = $mysqli->prepare("UPDATE bookings SET approval=? WHERE id=$id");
            $stmt->bind_param('s', $approval);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>Booking Successfull Updated.</div>";
            $result = $mysqli->query("SELECT * FROM bookings WHERE approval='pending' AND vet='vet1' AND date='$date'");
            $result_vet2 = $mysqli->query("SELECT * FROM bookings WHERE approval='pending' and vet='vet2' AND date='$date'");

            //send email

            // $mail->SMTPDebug = 2;
            $mail->isSMTP();                      // Set mailer to use SMTP 
            $mail->Host = 'smtp.gmail.com';       // Specify main and backup SMTP servers 
            $mail->SMTPAuth = true;               // Enable SMTP authentication 
            $mail->Username = 'animalclinicfyp@gmail.com';   // SMTP username 
            $mail->Password = 'fyptest123.';   // SMTP password 
            $mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
            $mail->Port = 587;                    // TCP port to connect to 

            // Sender info 
            $mail->setFrom('animalclinicfyp@gmail.com', 'Animal Clinic');
            // $mail->addReplyTo('reply@codexworld.com', 'CodexWorld'); 

            // Add a recipient
            // $mail->addAddress('szejin2010@gmail.com');
            $stmt = $mysqli->prepare("SELECT users.email, users.id, bookings.user_id, bookings.id FROM users, bookings WHERE users.id = bookings.user_id AND bookings.id = $id");

            if ($stmt->execute()) {
                $recipient = $stmt->get_result();
            }

            if ($recipient->num_rows > 0) {
                while ($row = $recipient->fetch_assoc()) {
                    $mail->addAddress($row['email']);
                }
            }

            //$mail->addCC('cc@example.com'); 
            //$mail->addBCC('bcc@example.com'); 

            // Set email format to HTML 
            $mail->isHTML(true);

            // Mail subject 
            $mail->Subject = 'Appointment Status';

            // $stmt = $mysqli->prepare("SELECT bookings.*, users.id FROM bookings WHERE users.id = bookings.user_id AND bookings.id = $id");
            $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE id = $id");

            if ($stmt->execute()) {
                $details = $stmt->get_result();
            }

            if ($details->num_rows > 0) {
                while ($row = $details->fetch_assoc()) {

                    // Mail body content 
                    $bodyContent = '<h1>Hi! Please check your appointment status.</h1>';
                    $bodyContent .= '<p>Pet Owner Name: ' . $row['name'] . '</p>';
                    $bodyContent .= '<p>Pet Name: ' . $row['petname'] . '</p>';
                    $bodyContent .= '<p>Reason: ' . $row['reason'] . '</p>';
                    $bodyContent .= '<p>Time slot: ' . $row['timeslot'] . '</p>';
                    $bodyContent .= '<p>Appointment Date: ' . $row['date'] . '</p>';
                    $bodyContent .= '<p>Vet: ' . $row['vet'] . '</p>';
                    $bodyContent .= '<p>Contact: ' . $row['contact'] . '</p>';
                    $bodyContent .= '<p>Appointment status: ' . $row['approval'] . '</p>';
                    $bodyContent .= '<p>Thank you.</p>';
                }
            }
            $bodyContent .= '<p>This is an autogenerate email. <b>Please do not reply.</b></p>';
            $mail->Body = $bodyContent;
            $mail->AltBody = 'Body in plain text for non-HTML mail clients';

            // Send email 
            if (!$mail->send()) {
                echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
            } else {
                // echo 'Message has been sent.';
            }

            $stmt->close();
            $mysqli->close();
            header("location: admin-calendar.php");
        }
    }
}

if (isset($_POST['compsubmit'])) {
    $row_id = $_POST['id'];
    $approval = 'completed';
    $stmt = $mysqli->prepare("UPDATE bookings SET approval=? WHERE id=$row_id");
    $stmt->bind_param('s', $approval);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
    header("location: vet1apt.php");
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
    
    <div class="container">
    <a href="<?php echo BASE_URL . 'index.php' ?>" class="normal-link1">Home</a><br><br>
    <a href='operation.php?date=<?php echo $date?>&vet=<?php echo $vet1?>' class='normal-link2'>DR. <?php echo $vet1 ?></a>
    <a href='book.php?date=<?php echo $date?>&vet=<?php echo $vet2?>' class='normal-link2'>DR. <?php echo $vet2 ?></a>
    <?php if ($apt_vet1->num_rows > 0) { ?>
        <h4>Appointment [DR. <?php echo $vet1 ?>] </h4>
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
                            <?php while ($row = $apt_vet1->fetch_assoc()) { ?>
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
                                            <input type="submit" name="compsubmit" value="Completed">
                                    </center>
                                    </form>
                                </td>
                            </tr>
            <?php } ?>
            </tbody>
                </table>
    <?php } else { ?>

    <?php } ?>

    <?php if ($result->num_rows > 0) { ?>
        <h4>Pending Approval [DR. <?php echo $vet1 ?>] </h4>
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
                <?php echo (isset($msg)) ? $msg : ""; ?>
            
            <?php while ($row = $result->fetch_assoc()) { ?>
                
                            <td class="textsize"><?php echo $row['id'] ?></td>
                            <td class="textsize"><?php echo $row['name'] ?></td>
                            <td class="textsize"><?php echo $row['petname'] ?></td>
                            <td class="textsize"><?php echo $row['pettype'] ?></td>
                            <td class="textsize"><?php echo $row['reason'] ?></td>
                            <td class="textsize"><?php echo $row['date'] ?></td>
                            <td class="textsize"><?php echo $row['timeslot'] ?></td>
                            <td class="textsize"><?php echo $row['vet'] ?></td>
                            <td class="textsize"><center>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input name="id" type="hidden" value=" <?php echo $row['id'] ?> ">
                                    <input name="approval" type="hidden" value="approved">
                                    <input type="submit" name="submit" value="Approve">
                                </form>
                           
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input name="id" type="hidden" value=" <?php echo $row['id'] ?> ">
                                    <input name="approval" type="hidden" value="denied">
                                    <input type="submit" name="submit" value="Deny">
                                </form></center>
                            </td>
                            </tr>
            <?php } ?>
            </tbody>
                </table>
    <?php } else { ?>

    <?php } ?>

    <?php if ($approved_vet1->num_rows > 0) { ?>
        <h4>Pending Deposit Payment [DR. <?php echo $vet1 ?>] </h4>
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
            <?php while ($row = $approved_vet1->fetch_assoc()) { ?>
                
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

    <?php if ($result_completed1->num_rows > 0) { ?>
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
                        <?php while ($row = $result_completed1->fetch_assoc()) { ?>

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

    <?php if ($apt_vet2->num_rows > 0) { ?>
        <h4>Appointment [DR. <?php echo $vet2 ?>] </h4>
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
                            <?php while ($row2 = $apt_vet2->fetch_assoc()) { ?>
                            <td class="textsize"><?php echo $row2['id'] ?></td>
                            <td class="textsize"><?php echo $row2['name'] ?></td>
                            <td class="textsize"><?php echo $row2['petname'] ?></td>
                            <td class="textsize"><?php echo $row2['pettype'] ?></td>
                            <td class="textsize"><?php echo $row2['reason'] ?></td>
                            <td class="textsize"><?php echo $row2['date'] ?></td>
                            <td class="textsize"><?php echo $row2['timeslot'] ?></td>
                            <td class="textsize"><?php echo $row2['vet'] ?></td>
                            <td class="textsize"><?php echo $row2['approval'] ?></td>
                            <td class="text-center textsize">
                                    <center>
                                        <form style="margin-top: 10px"action="" method="post" enctype="multipart/form-data">
                                            <input name="id" type="hidden" value=" <?php echo $row['id'] ?> ">
                                            <input name="approval" type="hidden" value="completed">
                                            <input type="submit" name="compsubmit" value="Completed">
                                    </center>
                                    </form>
                                </td>
                            </tr>
            <?php } ?>
            </tbody>
                </table>
    <?php } else { ?>

    <?php } ?>

    <?php if ($result_vet2->num_rows > 0) { ?>
        <h4>Pending Approval [DR. <?php echo $vet2 ?>] </h4>
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
                <?php echo (isset($msg)) ? $msg : ""; ?>
            
            <?php while ($row2 = $result_vet2->fetch_assoc()) { ?>
                
                            <td class="textsize"><?php echo $row2['id'] ?></td>
                            <td class="textsize"><?php echo $row2['name'] ?></td>
                            <td class="textsize"><?php echo $row2['petname'] ?></td>
                            <td class="textsize"><?php echo $row2['pettype'] ?></td>
                            <td class="textsize"><?php echo $row2['reason'] ?></td>
                            <td class="textsize"><?php echo $row2['date'] ?></td>
                            <td class="textsize"><?php echo $row2['timeslot'] ?></td>
                            <td class="textsize"><?php echo $row2['vet'] ?></td>
                            <td class="textsize"><center>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input name="id" type="hidden" value=" <?php echo $row2['id'] ?> ">
                                    <input name="approval" type="hidden" value="approved">
                                    <input type="submit" name="submit" value="Approve">
                                </form>
                           
                                <form action="" method="post" enctype="multipart/form-data">
                                    <input name="id" type="hidden" value=" <?php echo $row2['id'] ?> ">
                                    <input name="approval" type="hidden" value="denied">
                                    <input type="submit" name="submit" value="Deny">
                                </form></center>
                            </td>
                            </tr>
            <?php } ?>
            </tbody>
                </table>
    <?php } else { ?>

    <?php } ?>

    <?php if ($approved_vet2->num_rows > 0) { ?>
        <h4>Pending Deposit Payment [DR. <?php echo $vet2 ?>] </h4>
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
            <?php while ($row2 = $approved_vet2->fetch_assoc()) { ?>
                
                            <td class="textsize"><?php echo $row2['id'] ?></td>
                            <td class="textsize"><?php echo $row2['name'] ?></td>
                            <td class="textsize"><?php echo $row2['petname'] ?></td>
                            <td class="textsize"><?php echo $row2['pettype'] ?></td>
                            <td class="textsize"><?php echo $row2['reason'] ?></td>
                            <td class="textsize"><?php echo $row2['date'] ?></td>
                            <td class="textsize"><?php echo $row2['timeslot'] ?></td>
                            <td class="textsize"><?php echo $row2['vet'] ?></td>
                            <td class="textsize"><?php echo $row2['approval'] ?></td>
                            </tr>
            <?php } ?>
            </tbody>
                </table>
    <?php } else { ?>

    <?php } ?>

    <?php if ($result_completed2->num_rows > 0) { ?>
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
                        <?php while ($row2 = $result_completed2->fetch_assoc()) { ?>

                            <td class="textsize"><?php echo $row2['id'] ?></td>
                            <td class="textsize"><?php echo $row2['name'] ?></td>
                            <td class="textsize"><?php echo $row2['petname'] ?></td>
                            <td class="textsize"><?php echo $row2['pettype'] ?></td>
                            <td class="textsize"><?php echo $row2['reason'] ?></td>
                            <td class="textsize"><?php echo $row2['date'] ?></td>
                            <td class="textsize"><?php echo $row2['timeslot'] ?></td>
                            <td class="textsize"><?php echo $row2['vet'] ?></td>
                            <td class="textsize"><?php echo $row2['approval'] ?></td>

                    </tr>

                <?php } ?>
                </tbody>
            </table>

        <?php } else { ?>

        <?php } ?>
    </div>
</body>

</html>