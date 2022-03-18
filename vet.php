<?php include('config.php'); ?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    </style>
</head>

<body>
    <?php
    $date = $_GET['date'];
    $sql1 = "SELECT username FROM users WHERE id=?";
    $vet = getSingleRecord($sql1, 'i', [22]);
    $vet1 = $vet['username'];
    $sql2 = "SELECT username FROM users WHERE id=?";
    $vet = getSingleRecord($sql2, 'i', [23]);
    $vet2 = $vet['username'];

    echo "<div class='split left'>"; ?>
    <a href="<?php echo BASE_URL . 'index.php' ?>" class="normal-link1">Home</a>
    <?php
    echo "<div class='centered'><img src='image_avatar.png' alt='Avatar woman'><h2><a href='book.php?date=";
    echo $date;
    echo "&vet=";
    echo $vet1;
    echo "' class='normal-link1' style='text-transform: uppercase;'>DR. ";
    echo $vet1;
    echo "</a></h2>";
    echo "</div></div>";

    echo "<div class='split right'>";
    echo "<div class='centered'><img src='image_avatar2.png' alt='Avatar man'><h2><a href='book.php?date=";
    echo $date;
    echo "&vet=";
    echo $vet2;
    echo "' class='normal-link1' style='text-transform: uppercase;'>DR. ";
    echo $vet2;
    echo "</a></h2>";
    echo "</div></div>";
    ?>

</body>

</html>