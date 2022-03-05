<link rel="stylesheet" type="text/css" href="table.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<?php
// Include the database configuration file
require_once 'config.php';
$id = $_SESSION['user']['id'];
// Get image data from database 
$result_balance = $mysqli->query("SELECT * FROM ewallet WHERE user_id = $id");
// Import PHPMailer classes into the global namespace 
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

$mail = new PHPMailer(true);

if (isset($_POST['submit'])) {
    $id = $_SESSION['user']['id'];
    $card_name = $_POST['card_name'];
    $card_num = $_POST['card_num'];
    $card_expiry = $_POST['card_expiry'];
    $cvv = $_POST['cvv'];
    $created_at = date('Y-m-d H:i:s');
    $amount = $_POST['amount'];


    $stmt = $mysqli->prepare("select * from ewallet where id = ?");
    $stmt->bind_param('i', $id,);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows < 0) {
            $msg = "<div class='alert alert-danger'>Error finding user.</div>";
        }
        else {
            $stmt = $mysqli->prepare("UPDATE ewallet SET total=total+? WHERE user_id=$id");
            $stmt->bind_param('d', $amount);
            if ($stmt->execute()) {
                $create_transaction = "INSERT INTO transactions SET user_id=$id, card_name=?, card_num=?, card_expiry=?, cvv=?, created_at=?, amount=?";
                $create = $mysqli->prepare($create_transaction);
                $create->bind_param('sssssd', $card_name, $card_num, $card_expiry, $cvv, $created_at, $amount);
                $create->execute();
                $msg = "<div class='alert alert-success'>E-Wallet Balance Added.</div>";
                $result_balance = $mysqli->query("SELECT * FROM ewallet WHERE user_id = $id");
                $create->close();
            }
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
            $mail->Subject = 'Transaction Details';

            // Mail body content 
            $bodyContent = '<h1>Hello! This is your receipt from the top-up transaction :)</h1>';
            $bodyContent .= '<p>Card Number: '.$card_num.'</p>';
            $bodyContent .= '<p>Transaction Amount: '.$amount.'</p>';
            $bodyContent .= '<p>Transferred At: '.$created_at.'</p>';
            if(!empty($card_name)){
                $bodyContent .= '<p>Card Name: '.$card_name.'</p>';
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
        }
    } else {
        $msg = "<div class='alert alert-danger'><?php echo $id ?></div>";
    }
    header("location:customer-list.php");
}
?>

<?php if ($result_balance->num_rows > 0) { ?>
    <div class="gallery">
        <div class="col-md-12">
            <?php echo (isset($msg)) ? $msg : ""; ?>
        </div>
        <?php if ($result_balance->num_rows > 0) {
            while ($row = $result_balance->fetch_assoc()) { ?>
                <div>
                    <h1>E-Wallet Balance</h1><br>
                    <p><?php echo $row['total'] ?></p>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-4 col-md-offset-4">
                            <form class="form" action="" method="post" enctype="multipart/form-data">
                                <h2 class="text-center">Credit/Debit card</h2>
                                <hr>
                                <div class="form-group <?php echo isset($errors['card_name']) ? 'has-error' : '' ?>">
                                    <label class="control-label">Card name (optional)</label>
                                    <input type="text" name="card_name" class="form-control">
                                    <?php if (isset($errors['card_name'])) : ?>
                                        <span class="help-block"><?php echo $errors['card_name'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group <?php echo isset($errors['card_num']) ? 'has-error' : '' ?>">
                                    <label class="control-label">Card number</label>
                                    <input type="text" name="card_num" class="form-control">
                                    <?php if (isset($errors['card_num'])) : ?>
                                        <span class="help-block"><?php echo $errors['card_num'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group <?php echo isset($errors['card_expiry']) ? 'has-error' : '' ?>">
                                    <label class="control-label">Expiry date</label>
                                    <input type="text" name="card_expiry" class="form-control">
                                    <?php if (isset($errors['card_expiry'])) : ?>
                                        <span class="help-block"><?php echo $errors['card_expiry'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group <?php echo isset($errors['cvv']) ? 'has-error' : '' ?>">
                                    <label class="control-label">CVV</label>
                                    <input type="text" name="cvv" class="form-control">
                                    <?php if (isset($errors['cvv'])) : ?>
                                        <span class="help-block"><?php echo $errors['cvv'] ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="form-group <?php echo isset($errors['amount']) ? 'has-error' : '' ?>">
                                    <label class="control-label">Top-up amount</label>
                                    <input type="number" step="0.10" min="0" name="amount" id="amount" class="form-control">
                                </div>
                                <div class="form-group">
                                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    </div>

    </div>
<?php }
        } ?>
<?php } else { ?>
    <p class="status error">No appointments found...</p>
<?php } ?>