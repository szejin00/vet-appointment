<?php
    require_once "configStripe.php";
?>
<?php include('../config.php'); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pricing Page</title>
    <link rel="stylesheet" href="bootstrap-4.0.0-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style type="text/css">
        /* .container {
            margin-top: 100px;
            align-items: center;
            align-content: center;
        } */

        .card {
            width: 300px;
        }

        .card:hover {
            -webkit-transform: scale(1.05);
            -moz-transform: scale(1.05);
            -ms-transform: scale(1.05);
            -o-transform: scale(1.05);
            transform: scale(1.05);
            -webkit-transition: all .3s ease-in-out;
            -moz-transition: all .3s ease-in-out;
            -ms-transition: all .3s ease-in-out;
            -o-transition: all .3s ease-in-out;
            transition: all .3s ease-in-out;
        }

        .list-group-item {
            border: 0px;
            padding: 5px;
        }

        .price {
            font-size: 72px;
        }

        .currency {
            position: relative;
            font-size: 25px;
            top: -31px;
        }
    </style>
</head>
<body id="background-gradient">
<div class="container">
<a href="<?php echo BASE_URL . 'customer-list.php' ?>" class="normal-link1">Back</a>
    <?php
            echo '<center>
                <div class="col-md-4">
                <form action="stripeIPN.php" method="POST">
                    <div class="card">
                        <div class="card-header text-center">
                            <input type="number" min="0" steps="0.10" name="amount" id="amount">
                        </div>
                        <div class="card-body text-center">
                            <div class="card-title">
                                <h2>Top-Up Amount</h2>
                            </div>
                            <ul class="list-group">
                            ';

                        echo '
                            </ul>
                            <br>
                            
                              <script
                                src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                data-key="'.$stripeDetails['publishableKey'].'"
                                data-amount=document.getElementById("amount").value
                                data-name="Top-Up Amount"
                                data-description="Widget"
                                data-image="https://stripe.com/img/documentation/checkout/marketplace.png"
                                data-locale="auto">
                              </script>
                            </form>
                        </div>
                    </div>
                </div>
            </center>';

    ?>
</div>
</body>
</html>