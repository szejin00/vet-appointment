<?php include('config.php'); ?>
<?php
/*$mysqli = new mysqli('localhost', 'root', '', 'bookingcalendar');*/
if (isset($_GET['date'])) {
    $date = $_GET['date'];
    $vet = $_GET['vet'];
    $stmt = $mysqli->prepare("select * from bookings where date = ? AND vet = ?");
    $stmt->bind_param('ss', $date, $vet);
    $bookings = array();
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $bookings[] = $row['timeslot'];
            }

            $stmt->close();
        }
    }
}

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $reason = $_POST['reason'];
    $petname = $_POST['petname'];
    $pettype = $_POST['pettype'];
    $contact = $_POST['contact'];
    $timeslot = $_POST['timeslot'];
    $created_at = date('Y-m-d H:i:s');
    $vet = $_GET['vet'];
    $approval = "pending";
    $user_id = $_SESSION['user']['id'];
    $stmt = $mysqli->prepare("select * from bookings where date = ? AND timeslot=? AND vet=?");
    $stmt->bind_param('sss', $date, $timeslot, $vet);
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $msg = "<div class='alert alert-danger'>Already Booked</div>";
        } else {
            $stmt = $mysqli->prepare("INSERT INTO bookings (name, timeslot, reason, date, vet, petname, pettype, contact, approval, user_id, created_at) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param('sssssssssis', $name, $timeslot, $reason, $date, $vet, $petname, $pettype, $contact, $approval, $user_id, $created_at);
            $stmt->execute();
            $msg = "<div class='alert alert-success'>Booking Successful</div>";
            $bookings[] = $timeslot;
            $stmt->close();
            $mysqli->close();
        }
    }
}

$duration = 120;
$cleanup = 0;
$start = "10:00";
$end = "19:00";

function timeslots($duration, $cleanup, $start, $end)
{
    $start = new DateTime($start);
    $end = new DateTime($end);
    $lunchstart = new DateTime("14:00");
    $lunchend = new DateTime("14:30");
    $interval = new DateInterval("PT" . $duration . "M");
    $cleanupInterval = new DateInterval("PT" . $cleanup . "M");
    $slots = array();

    for ($intStart = $start; $intStart < $end; $intStart->add($interval)->add($cleanupInterval)) {
        $endPeriod = clone $intStart;
        $endPeriod->add($interval);
        if ($intStart == $lunchstart || $intStart == $lunchend) {
        } else {
            $slots[] = $intStart->format("H:iA") . " - " . $endPeriod->format("H:iA");
        };

        if ($endPeriod > $end) {
            break;
        }
    }

    return $slots;
}

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Book Operation</title>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body id="background-gradient">
    <div class="container">
    <a href="<?php echo BASE_URL . 'index.php' ?>" class="normal-link1">Home</a>
        <h1 class="text-center">Book for Date: <?php echo date('m/d/Y', strtotime($date)); ?></h1>
        <h5 class="text-center">This booking is for operation/surgery purposes.</h5>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <?php echo (isset($msg)) ? $msg : ""; ?>
            </div>
            <?php $timeslots = timeslots($duration, $cleanup, $start, $end);
            foreach ($timeslots as $ts) {
            ?>
                <div class="col-md-2">
                    <div class="form-group">
                        <?php if (in_array($ts, $bookings)) { ?>
                            <button class="btn btn-danger"><?php echo $ts; ?></button>
                        <?php } else { ?>
                            <button class="btn btn-success book" data-timeslot="<?php echo $ts; ?>" vet-value="<?php echo $vet ?>"><?php echo $ts; ?></button>
                        <?php }  ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Booking for: <span id="slot"></span></h4>
                    <h5>This booking is for operation/surgery purposes.</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="" method="post">
                                <div class="form-group">
                                    <label for="">Time Slot</label>
                                    <input readonly type="text" class="form-control" id="timeslot" name="timeslot">
                                </div>
                                <div class="form-group">
                                    <label for="">Vet</label>
                                    <input readonly type="text" class="form-control" value=<?php echo $vet; ?>>
                                </div>
                                <div class="form-group">
                                    <label for="">Owner's Name</label>
                                    <input required type="text" class="form-control" name="name" >
                                </div>
                                <div class="form-group">
                                    <label for="">Reason</label>
                                    <select id="bookselect" name="reason" class="form-control">
                                        <option value="Spay/Neuter surgery" name="spay">Spay/Neuter surgery</option>
                                        <option value="Bone surgery" name="bone">Bone surgery</option>
                                        <option value="Dental surgery" name="dental">Dental surgery</option>
                                        <option value="Eye surgery" name="dental">Eye surgery</option>
                                        <option value="Respitory system surgery" name="dental">Respitory surgery</option>
                                    </select>
                                    <!-- <input required type="text" class="form-control" name="reason" maxlength="25" placeholder="max 25 words"> -->
                                </div>
                                <div class="form-group">
                                    <label for="">Contact</label>
                                    <input required type="text" class="form-control" name="contact" pattern="^(\+?6?01)[0-46-9]-*[0-9]{7,8}$">
                                </div>
                                <div class="form-group">
                                    <label for="">Pet's Name</label>
                                    <input required type="text" class="form-control" name="petname">
                                </div>
                                <div class="form-group">
                                    <label for="">Pet Type</label>
                                    <select id="bookselect" name="pettype" class="form-control">
                                        <option value="Dog">Dog</option>
                                        <option value="Cat">Cat</option>
                                        <option value="Raptile">Raptile</option>
                                        <option value="Guinea Pig">Guinea Pig</option>
                                        <option value="Rabbit">Rabbit</option>
                                        <option value="Bird">Bird</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    <!-- <input required type="text" class="form-control" name="reason" maxlength="25" placeholder="Dog/Cat/Rabbit/etc."> -->
                                </div>
                                <div class="form-group pull-right">
                                    <button name="submit" type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script>
        $(".book").click(function() {
            var timeslot = $(this).attr('data-timeslot');
            $("#slot").html(timeslot);
            $("#timeslot").val(timeslot);
            $("#myModal").modal("show");
        });
    </script>
</body>

</html>