<?php
session_start(); // start session
// connect to database
$mysqli = new mysqli("localhost", "root", "", "animalclinic");
// Check connection
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\Exception; 
 
require 'vendor/autoload.php';
 
$mail = new PHPMailer(true); 

$date = date('Y-m-d H:i:s', strtotime('-12 hours'));

$stmt = $mysqli->prepare("SELECT id FROM bookings WHERE created_at < '" . $date . "' AND approval='approved'");

if ($stmt->execute()) {
    $id = $stmt->get_result();
}
//$id = mysqli_query($mysqli, $query);
// $email = "SELECT users.email, users.id, bookings.user_id, bookings.id FROM users, bookings WHERE users.id = bookings.user_id AND bookings.id = $id";
while($row=$id->fetch_assoc()) {
  $bookingid=$row['id'];
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
  $stmt = $mysqli->prepare("SELECT users.email, users.id, bookings.user_id, bookings.id FROM users, bookings WHERE users.id = bookings.user_id AND bookings.id = '" . $row['id'] ."' ");

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
  $stmt = $mysqli->prepare("SELECT * FROM bookings WHERE id = $bookingid");

  if ($stmt->execute()) {
    $details = $stmt->get_result();

  }

  if ($details->num_rows > 0) {
    while ($row = $details->fetch_assoc()) {

      // Mail body content 
      $bodyContent = '<h1>Unfortunately, your appointment has been cancelled.</h1>';
      $bodyContent .= '<p>Owner Name: ' . $row['name'] . '</p>';
      $bodyContent .= '<p>Pet Name: ' . $row['petname'] . '</p>';
      $bodyContent .= '<p>Reason: ' . $row['reason'] . '</p>';
      $bodyContent .= '<p>Time slot: ' . $row['timeslot'] . '</p>';
      $bodyContent .= '<p>Appointment Date: ' . $row['date'] . '</p>';
      $bodyContent .= '<p>Vet: ' . $row['vet'] . '</p>';
      $bodyContent .= '<p>Contact: ' . $row['contact'] . '</p>';
      $bodyContent .= '<p>Appointment status: cancelled </p>';
      $bodyContent .= '<p>Please pay the deposit within 12 hours after the appointment has been approved next time. Thank you.</p>';
    }
  }
  $bodyContent .= '<p>This is an autogenerate email. <b>Please do not reply.</b></p>';
  $mail->Body = $bodyContent;
  $mail->AltBody = 'Body in plain text for non-HTML mail clients';
  $sql = "DELETE FROM bookings WHERE created_at < '" . $date . "' AND approval='approved'";
  mysqli_query($mysqli, $sql);
  // Send email 
  if (!$mail->send()) {
    echo 'Message could not be sent. Mailer Error: ' . $mail->ErrorInfo;
  } else {

  }

  $stmt->close();
  $mysqli->close();
}
// define global constants
define('ROOT_PATH', realpath(dirname(__FILE__))); // path to the root folder
define('INCLUDE_PATH', realpath(dirname(__FILE__) . '/includes')); // Path to includes folder
define('BASE_URL', 'http://localhost/3/'); // the home url of the website

function getMultipleRecords($sql, $types = null, $params = [])
{
  global $mysqli;
  $stmt = $mysqli->prepare($sql);
  if (!empty($params) && !empty($params)) { // parameters must exist before you call bind_param() method
    $stmt->bind_param($types, ...$params);
  }
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_all(MYSQLI_ASSOC);
  $stmt->close();
  return $user;
}
function getSingleRecord($sql, $types, $params)
{
  global $mysqli;
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param($types, ...$params);
  $stmt->execute();
  $result = $stmt->get_result();
  $user = $result->fetch_assoc();
  $stmt->close();
  return $user;
}
function modifyRecord($sql, $types, $params)
{
  global $mysqli;
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param($types, ...$params);
  $result = $stmt->execute();
  $stmt->close();
  return $result;
}
