<?php include('config.php'); ?>

<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
    </style>
</head>

<body>
    <div class="container">
        <?php
        $date = $_GET['date'];
        $vet1 = "vet1";
        $vet2 = "vet2";
        echo "<div class='split left'>"; ?>
        <a href="<?php echo BASE_URL . 'index.php' ?>" class="normal-link1">Home</a>
        <?php
        echo "<div class='centered'><img src='image_avatar2.png' alt='Avatar man'><h2><a href='book.php?date=" . $date . "&vet=" . $vet1 . "' class='normal-link1'>Vet 1</a></h2>";
        echo "<p>Dr. Melvin is specialize with small animals such as rabbit, rodents, and raptiles as well.</p></div></div>";

        echo "<div class='split right'>";
        echo "<div class='centered'><img src='image_avatar.png' alt='Avatar woman'><h2><a href='operation.php?date=" . $date . "&vet=" . $vet2 . "' class='normal-link1'>Vet 2</a></h2>";
        echo "<p>Dr. Shisha is specialize with dogs and cats.</p></div></div>";
        ?>
    </div>
</body>

</html>