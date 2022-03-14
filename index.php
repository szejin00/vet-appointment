<?php include("config.php") ?>
<?php include(INCLUDE_PATH . "/logic/common_functions.php"); ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>UserAccounts - Home</title>
  <!-- Bootstrap CSS -->
  <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" /> -->
  <!-- Custome styles -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
  <?php include(INCLUDE_PATH . "/layouts/navbar.php") ?>
  <div class="center-item">
    <?php include(INCLUDE_PATH . "/layouts/messages.php") ?>
    <div class="slideshow-container">

      <div>
        <div class="mySlides fade">
          <img src="dog.jpg">
          <div class="text">
            <h3>Welcome to our website!</h3>
          </div>
        </div>

        <div class="mySlides fade">
          <img src="meow.jpg">
          <div class="text">
            <h3>Book your appointment today!</h3>
          </div>
        </div>

        <div class="mySlides fade">
          <img src="rwabbit.jpg">
          <div class="text">
            <h3>Book your appointment through our website!</h3>
          </div>
        </div>

        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
      </div><br>
      <div style="text-align:center">
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
        <span class="dot" onclick="currentSlide(3)"></span>
      </div>
    </div><br><br>

    <div class="container">
      <p>
      <h3>About Us</h3>
      </p>
      <p>
        We were founded on 2010, our duty is to save as many animal's lifes as we can. <br>
        This website is to let all the pet owners or customers to bring indanger strays <br>
        or pet to our clinic with the provided clinic operation information. Customers <br>
        are welcome to bring your baby pet to our clinic for checkup after making <br>
        appointment at our website. Saving animal's lifes is our duty and motivation.
      </p><br><hr>
      <p>
      <h3>Terms and Conditions</h3>
      </p>
      <p>
         1) ...<br>
         2) ...<br>
      </p>
    </div>
    <script>
      var slideIndex = 1;
      showSlides(slideIndex);

      function plusSlides(n) {
        showSlides(slideIndex += n);
      }

      function currentSlide(n) {
        showSlides(slideIndex = n);
      }

      function showSlides(n) {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        if (n > slides.length) {
          slideIndex = 1
        }
        if (n < 1) {
          slideIndex = slides.length
        }
        for (i = 0; i < slides.length; i++) {
          slides[i].style.display = "none";
        }
        for (i = 0; i < dots.length; i++) {
          dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideIndex - 1].style.display = "block";
        dots[slideIndex - 1].className += " active";
      }

      var slideId = 0;
      changeSlides();

      function changeSlides() {
        var i;
        var slides = document.getElementsByClassName("mySlides");
        var dots = document.getElementsByClassName("dot");
        for (i = 0; i < slides.length; i++) {
          slides[i].style.display = "none";
        }
        slideId++;
        if (slideId > slides.length) {
          slideId = 1
        }
        for (i = 0; i < dots.length; i++) {
          dots[i].className = dots[i].className.replace(" active", "");
        }
        slides[slideId - 1].style.display = "block";
        dots[slideId - 1].className += " active";
        setTimeout(changeSlides, 4000); // Change image every 2 seconds
      }
    </script>

    <?php include(INCLUDE_PATH . "/layouts/footer.php") ?>
  </div>
</body>

</html>