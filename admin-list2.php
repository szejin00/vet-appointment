<link rel="stylesheet" type="text/css" href="table.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<?php
// Include the database configuration file
require_once 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

$date = $_GET['date'];
$result = $mysqli->query("SELECT * FROM bookings WHERE approval='pending' AND vet='vet1' AND date='$date'");
$result_vet2 = $mysqli->query("SELECT * FROM bookings WHERE approval='pending' and vet='vet2' AND date='$date'");
$apt_vet1 = $mysqli->query("SELECT * FROM bookings WHERE approval='paid' and vet='vet1' AND date='$date'");
$apt_vet2 = $mysqli->query("SELECT * FROM bookings WHERE approval='paid' and vet='vet2' AND date='$date'");
$approved_vet1 = $mysqli->query("SELECT * FROM bookings WHERE approval='approved' and vet='vet1' AND date='$date'");
$approved_vet2 = $mysqli->query("SELECT * FROM bookings WHERE approval='approved' and vet='vet2' AND date='$date'");

$vet1 = "vet1";
$vet2 = "vet2";
echo "<a href='operation.php?date=" . $date . "&vet=" . $vet1 . "'>Vet 1</a>";
echo "<a href='book.php?date=" . $date . "&vet=" . $vet2 . "'>Vet 2</a>";

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
            $mail->Username = 'szejin2000@gmail.com';   // SMTP username 
            $mail->Password = 'boo000420.';   // SMTP password 
            $mail->SMTPSecure = 'tls';            // Enable TLS encryption, `ssl` also accepted 
            $mail->Port = 587;                    // TCP port to connect to 

            // Sender info 
            $mail->setFrom('szejin2000@gmail.com', 'Sze Jin');
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
                    $bodyContent = '<h1>Hi! Your appointment has been approved, please proceed to deposit payment.</h1>';
                    $bodyContent .= '<p>Owner Name: ' . $row['name'] . '</p>';
                    $bodyContent .= '<p>Pet Name: ' . $row['petname'] . '</p>';
                    $bodyContent .= '<p>Reason: ' . $row['email'] . '</p>';
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
?>

<?php if ($result->num_rows > 0) { ?>
    <div class="gallery">
        <div class="col-md-12">
            <?php echo (isset($msg)) ? $msg : ""; ?>
        </div>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <div class="container">
                <ul class="responsive-table">
                    <li class="table-header">
                        <div class="col col-1"><?php echo $row['id'] ?></div>
                        <div class="col col-2"><?php echo $row['name'] ?></div>
                        <div class="col col-2"><?php echo $row['date'] ?></div>
                        <div class="col col-3"><?php echo $row['timeslot'] ?></div>
                        <div class="col col-4"><?php echo $row2['vet'] ?></div>
                        <div class="col col-5">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input name="id" type="hidden" value=" <?php echo $row['id'] ?> ">
                                <input name="approval" type="hidden" value="approved">
                                <input type="submit" name="submit" value="Approve">
                            </form>
                            <form action="" method="post" enctype="multipart/form-data">
                                <input name="id" type="hidden" value=" <?php echo $row['id'] ?> ">
                                <input name="approval" type="hidden" value="denied">
                                <input type="submit" name="submit" value="Deny">
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <p class="status error">No appointments found...</p>
<?php } ?>

<?php if ($result_vet2->num_rows > 0) { ?>
    <div class="gallery">
        <?php while ($row2 = $result_vet2->fetch_assoc()) { ?>
            <div class="container">
                <ul class="responsive-table">
                    <li class="table-header">
                        <div class="col col-1"><?php echo $row2['id'] ?></div>
                        <div class="col col-2"><?php echo $row2['name'] ?></div>
                        <div class="col col-2"><?php echo $row2['date'] ?></div>
                        <div class="col col-3"><?php echo $row2['timeslot'] ?></div>
                        <div class="col col-4"><?php echo $row2['vet'] ?></div>
                        <div class="col col-5">
                            <form action="" method="post" enctype="multipart/form-data">
                                <input name="id" type="hidden" value=" <?php echo $row2['id'] ?> ">
                                <input name="approval" type="hidden" value="approved">
                                <input type="submit" name="submit" value="Approve">
                            </form>
                            <form action="" method="post" enctype="multipart/form-data">
                                <input name="id" type="hidden" value=" <?php echo $row2['id'] ?> ">
                                <input name="approval" type="hidden" value="denied">
                                <input type="submit" name="submit" value="Deny">
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <p class="status error">No appointments found...</p>
<?php } ?>

<?php if ($apt_vet1->num_rows > 0) { ?>
    <div class="gallery">
        <?php while ($row = $apt_vet1->fetch_assoc()) { ?>
            <div class="container">
                <ul class="responsive-table">
                    <li class="table-header">
                        <div class="col col-1"><?php echo $row['id'] ?></div>
                        <div class="col col-2"><?php echo $row['name'] ?></div>
                        <div class="col col-2"><?php echo $row['date'] ?></div>
                        <div class="col col-3"><?php echo $row['timeslot'] ?></div>
                        <div class="col col-4"><?php echo $row['vet'] ?></div>
                        <div class="col col-5"><?php echo $row['approval'] ?></div>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <p class="status error">No appointments found...</p>
<?php } ?>

<?php if ($apt_vet2->num_rows > 0) { ?>
    <div class="gallery">
        <?php while ($row2 = $apt_vet2->fetch_assoc()) { ?>
            <div class="container">
                <ul class="responsive-table">
                    <li class="table-header">
                        <div class="col col-1"><?php echo $row2['id'] ?></div>
                        <div class="col col-2"><?php echo $row2['name'] ?></div>
                        <div class="col col-2"><?php echo $row2['date'] ?></div>
                        <div class="col col-3"><?php echo $row2['timeslot'] ?></div>
                        <div class="col col-4"><?php echo $row2['vet'] ?></div>
                        <div class="col col-5"><?php echo $row2['approval'] ?></div>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <p class="status error">No appointments found...</p>
<?php } ?>

<?php if ($approved_vet1->num_rows > 0) { ?>
    <div class="gallery">
        <?php while ($row = $approved_vet1->fetch_assoc()) { ?>
            <div class="container">
                <ul class="responsive-table">
                    <li class="table-header">
                        <div class="col col-1"><?php echo $row['id'] ?></div>
                        <div class="col col-2"><?php echo $row['name'] ?></div>
                        <div class="col col-2"><?php echo $row['date'] ?></div>
                        <div class="col col-3"><?php echo $row['timeslot'] ?></div>
                        <div class="col col-4"><?php echo $row['vet'] ?></div>
                        <div class="col col-5"><?php echo $row['approval'] ?></div>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <p class="status error">No appointments found...</p>
<?php } ?>

<?php if ($approved_vet2->num_rows > 0) { ?>
    <div class="gallery">
        <?php while ($row2 = $approved_vet2->fetch_assoc()) { ?>
            <div class="container">
                <ul class="responsive-table">
                    <li class="table-header">
                        <div class="col col-1"><?php echo $row2['id'] ?></div>
                        <div class="col col-2"><?php echo $row2['name'] ?></div>
                        <div class="col col-2"><?php echo $row2['date'] ?></div>
                        <div class="col col-3"><?php echo $row2['timeslot'] ?></div>
                        <div class="col col-4"><?php echo $row2['vet'] ?></div>
                        <div class="col col-5"><?php echo $row2['approval'] ?></div>
                    </li>
                </ul>
            </div>
        <?php } ?>
    </div>
<?php } else { ?>
    <p class="status error">No appointments found...</p>
<?php } ?>