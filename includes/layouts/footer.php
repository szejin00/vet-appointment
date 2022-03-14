<!DOCTYPE html>
<html>

<head>
    <title>Footer</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <style>
        footer {
            position: relative;
            left: auto;
            bottom: auto;
            height: 100px;
            width: 300px;
            color: white;
            text-align: center;
            padding: 20px;
        }

        #foot {
            padding: 5px;
            margin-top: 5px;
            transition: 200ms all ease-in-out;
            color: #ececec;
        }

        #foot:hover {
            color: #DB5C93;
        }

        #apt-icon {
            padding: 5px;
            margin-top: 5px;
            transition: 200ms all ease-in-out;
            color: #581331;
        }

        #apt-icon:hover {
            color: #A96080;
        }

        .s-label {
            padding: 5px;
            font-size: 14px;
            border-bottom: 2px solid #581331;
            outline: none;
            line-height: 24pt;
            width: 100%;
        }

        #footer-text {
            padding: 0;
            font-size: 14px;
            outline: none;
            line-height: 15pt;
            width: 100%;
        }

        .icon-link:active,
        .icon-link:hover,
        .icon-link:visited,
        .icon-link:link {
            color: white;
        }
    </style>
</head>

<body>
    <footer class="social">
        <label for="social" class="s-label">Our Social Media</label>
        <div id="icons">
            <a href="https://discord.gg/KwQUzypC" class="icon-link"><i class="material-icons" id="foot">discord</i></a>
            <a href="https://www.reddit.com" class="icon-link"><i class="material-icons" id="foot">reddit</i></a>
        </div>
        <p id="footer-text">@Animal Clinic. Copyrighted.</p>
    </footer>

    <!-- JQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>