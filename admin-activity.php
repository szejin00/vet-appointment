<?php
// Include the database configuration file
require_once 'config.php';

// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

$id = $_SESSION['user']['id'];
$result_approved = $mysqli->query("SELECT * FROM bookings WHERE approval='approved' AND user_id = $id");
$result_pending = $mysqli->query("SELECT * FROM bookings WHERE approval='pending' AND user_id = $id");
$result_paid = $mysqli->query("SELECT * FROM bookings WHERE approval='paid' AND user_id = $id");
// $result_balance = $mysqli->query("SELECT * FROM ewallet WHERE user_id = $id");

if (isset($_POST['submit'])) {
    $row_id = $_POST['id'];
    $amount = 10;
    $approval = 'paid';
    $stmt = $mysqli->prepare("select * from ewallet where id = ?");
    $stmt->bind_param('i', $id,);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows < 0) {
            $msg = "<div class='alert alert-danger'>Error finding user.</div>";
        } else {
            $stmt = $mysqli->prepare("UPDATE ewallet SET total=total-? WHERE user_id=$id");
            $stmt->bind_param('d', $amount);
            $stmt->execute();

            $stmt = $mysqli->prepare("UPDATE bookings SET approval=? WHERE user_id=$id AND id=$row_id");
            $stmt->bind_param('s', $approval);
            $stmt->execute();

            $msg = "<div class='alert alert-success'>E-Wallet Balance Deduced.</div>";
            // $result_balance = $mysqli->query("SELECT * FROM ewallet WHERE user_id = $id");
            $result_approved = $mysqli->query("SELECT * FROM bookings WHERE approval='approved' AND user_id = $id");

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
            $mail->setFrom('szejin2000@gmail.com', 'Animal Clinic');
            // $mail->addReplyTo('reply@codexworld.com', 'CodexWorld'); 

            // Add a recipient
            // $mail->addAddress('szejin2010@gmail.com');

            $stmt = $mysqli->prepare("SELECT email FROM users WHERE id = $id");

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
            $mail->Subject = 'Appointment Details';

            $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE user_id = $id");

            if ($stmt->execute()) {
                $details = $stmt->get_result();
            }

            if ($details->num_rows > 0) {
                while ($row = $details->fetch_assoc()) {

                    // Mail body content 
                    $bodyContent = '<h1>Hi! This is your appointment detail :)</h1>';
                    $bodyContent .= '<p>Owner Name: ' . $row['name'] . '</p>';
                    $bodyContent .= '<p>Pet Name: ' . $row['petname'] . '</p>';
                    $bodyContent .= '<p>Reason: ' . $row['reason'] . '</p>';
                    $bodyContent .= '<p>Time slot: ' . $row['timeslot'] . '</p>';
                    $bodyContent .= '<p>Appointment Date: ' . $row['date'] . '</p>';
                    $bodyContent .= '<p>Vet: ' . $row['vet'] . '</p>';
                    $bodyContent .= '<p>Contact: ' . $row['contact'] . '</p>';
                    $bodyContent .= '<p>Appointment status: ' . $row['approval'] . '</p>';
                    $bodyContent .= '<p>Paid at: ' . $row['created_at'] . '</p>';
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
            header("location: admin-activity.php");
        }
    }
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
                            <th class="textsize">Owner</th>
                            <th class="textsize">Petname</th>
                            <th class="textsize">Reason</th>
                            <th class="textsize">Date</th>
                            <th class="textsize">Timeslot</th>
                            <th class="textsize">Vet</th>
                            <th class="text-center textsize">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php while ($row = $result_approved->fetch_assoc()) { ?>

                                <td class="textsize"><?php echo $row['id'] ?></td>
                                <td class="textsize"><?php echo $row['name'] ?></td>
                                <td class="textsize"><?php echo $row['petname'] ?></td>
                                <td class="textsize"><?php echo $row['reason'] ?></td>
                                <td class="textsize"><?php echo $row['date'] ?></td>
                                <td class="textsize"><?php echo $row['timeslot'] ?></td>
                                <td class="textsize"><?php echo $row['vet'] ?></td>
                                <td class="textsize">
                                    <center>
                                        <form action="" method="post" enctype="multipart/form-data">
                                            <input name="id" type="hidden" value=" <?php echo $row['id'] ?> ">
                                            <input type="submit" name="submit" value="Pay">
                                        </form>
                                    </center>
                                </td>
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
                            <th class="textsize">Owner</th>
                            <th class="textsize">Petname</th>
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
                            <th class="textsize">Owner</th>
                            <th class="textsize">Petname</th>
                            <th class="textsize">Reason</th>
                            <th class="textsize">Date</th>
                            <th class="textsize">Timeslot</th>
                            <th class="textsize">Vet</th>
                            <th colspan="2" class="text-center textsize">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php while ($row = $result_paid->fetch_assoc()) { ?>

                                <td class="textsize"><?php echo $row['id'] ?></td>
                                <td class="textsize"><?php echo $row['name'] ?></td>
                                <td class="textsize"><?php echo $row['petname'] ?></td>
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