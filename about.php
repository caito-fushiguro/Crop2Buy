<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="about">

   <div class="row">

      <div class="box">
         <h3>why choose us?</h3>
         <p>At Crop2Buy, we're passionate about connecting you with the freshest, locally sourced crops from San Jose, Occidental Mindoro. We believe in supporting our local farmers and bringing you the best quality produce that contributes to a sustainable and thriving agricultural community.</p>

      <div class="box">
         <h3>what we provide?</h3>
         <p>At Crop2Buy, we're committed to providing businesses like yours with a comprehensive selection of fresh, locally sourced vegetables, fruits, and grains from the heart of San Jose, Occidental Mindoro. Our B2B platform empowers your business with the finest produce, ensuring you have the ingredients to meet your customers' demands and elevate your culinary offerings.</p>
         <a href="shop.php" class="btn">Our Market</a>
      </div>
      </div>
      </section>











<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>