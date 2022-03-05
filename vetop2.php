<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <style>
    </style>
</head>

<body>
<?php
    $date = $_GET['date'];
    $vet1 = "vet1";
    $vet2 = "vet2";
    echo "<a href='book.php?date=" . $date . "&vet=" . $vet1 . "'>Vet 1</a>";
    echo "<a href='operation.php?date=" . $date . "&vet=" . $vet2 . "'>Vet 2</a>";
?>

</body>

</html>