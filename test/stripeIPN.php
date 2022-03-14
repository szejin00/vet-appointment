<?php
	require_once "../config.php";
	require_once "configStripe.php";
	\Stripe\Stripe::setVerifySslCerts(false);
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	require '../vendor/autoload.php';
	
	$mail = new PHPMailer(true);
	// Token is created using Checkout or Elements!
	// Get the payment token ID submitted by the form:
	$amount = $_POST['amount'];
		$description = "Top-Up for Online Vet System";
	if (!isset($_POST['stripeToken'])) {
		header("Location: pricing.php");
		exit();
	}

	$token = $_POST['stripeToken'];
	$email = $_POST["stripeEmail"];

	// Charge the user's card:
	$charge = \Stripe\Charge::create(array(
		"amount" => $amount*100,
		"currency" => "myr",
		"description" => $description,
		"source" => $token,
	));

	$id = $_SESSION['user']['id'];
    $created_at = date('Y-m-d H:i:s');

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
                $create_transaction = "INSERT INTO transactions SET user_id=$id, created_at=?, amount=?";
                $create = $mysqli->prepare($create_transaction);
                $create->bind_param('sd', $created_at, $amount);
                $create->execute();
                $create->close();
            }
		
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
            $mail->Subject = 'Transaction Details';

            // Mail body content 
            $bodyContent = '<h1>Hello! This is your receipt from the top-up transaction :)</h1>';
            $bodyContent .= '<p>Transaction Amount: '.$amount.'</p>';
            $bodyContent .= '<p>Transferred At: '.$created_at.'</p>';
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
            header("location: ../customer-list.php");
		}
	}
	//send an email
	//store information to the database
	echo 'Success! You have been charged $' . ($amount);
?>