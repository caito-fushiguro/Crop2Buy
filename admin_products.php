<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['add_product'])){
   
   $category = $_POST['Category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $productName = $_POST['ProductName'];
   $productName = filter_var($productName, FILTER_SANITIZE_STRING);

   $sellerName = $_POST['SellerName'];
   $sellerName = filter_var($sellerName, FILTER_SANITIZE_STRING);

   $unit = $_POST['Unit'];
   $unit = filter_var($unit, FILTER_SANITIZE_STRING);

   $quantity = $_POST['Quantity'];
   $quantity = filter_var($quantity, FILTER_SANITIZE_STRING);

   $price = $_POST['Price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);

   $shippingCharge = $_POST['ShippingCharge'];
   $shippingCharge = filter_var($shippingCharge, FILTER_SANITIZE_STRING);

   $productAvailability = $_POST['ProductAvailability'];
   $productAvailability = filter_var($productAvailability, FILTER_SANITIZE_STRING);

         $image1 = $_FILES['image1']['name'];
         $image1 = filter_var($image1, FILTER_SANITIZE_STRING);
         $image1_size = $_FILES['image1']['size'];
         $image1_tmp_name = $_FILES['image1']['tmp_name'];
         $image1_folder = 'uploaded_img/'.$image1;

         $image2 = $_FILES['image2']['name'];
         $image2 = filter_var($image2, FILTER_SANITIZE_STRING);
         $image2_size = $_FILES['image2']['size'];
         $image2_tmp_name = $_FILES['image2']['tmp_name'];
         $image2_folder = 'uploaded_img/'.$image2;

         $image3 = $_FILES['image3']['name'];
         $image3 = filter_var($image3, FILTER_SANITIZE_STRING);
         $image3_size = $_FILES['image3']['size'];
         $image3_tmp_name = $_FILES['image3']['tmp_name'];
         $image3_folder = 'uploaded_img/'.$image3;

   $details = $_POST['Details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   

   $select_products = $conn->prepare("SELECT * FROM `products` WHERE product_name = ?");
   $select_products->execute([$productName]);

   if($select_products->rowCount() > 0){
      $message[] = 'product name already exist!';
   }else{

      $insert_products = $conn->prepare("INSERT INTO `products`(category, product_name, seller_name, unit, quantity, price, shipping_charge, product_availability, image1, image2, image3, details) VALUES(?,?,?,?,?,?,?,?,?,?,?,?)");
      $insert_products->execute([$category, $productName, $sellerName, $unit, $quantity, $price, $shippingCharge, $productAvailability, $image1, $image2, $image3, $details]);

      if($insert_products){
         if($image1_size > 2000000){
            $message[] = 'image size is too large!';
         }else{
            move_uploaded_file($image1_tmp_name, $image1_folder);
            move_uploaded_file($image2_tmp_name, $image2_folder);
            move_uploaded_file($image3_tmp_name, $image3_folder);
            $message[] = 'new product added!';
         }

      }

   }

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];

   $select_delete_image = $conn->prepare("SELECT image1 FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image1']);

   $select_delete_image = $conn->prepare("SELECT image2 FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image2']);

   $select_delete_image = $conn->prepare("SELECT image3 FROM `products` WHERE id = ?");
   $select_delete_image->execute([$delete_id]);
   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image3']);



   $delete_products = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_products->execute([$delete_id]);
   $delete_wishlist = $conn->prepare("DELETE FROM `wishlist` WHERE pid = ?");
   $delete_wishlist->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_products.php');


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="add-products">

   <h1 class="title">add new product</h1>

   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">

         <select name="Category" class="box" required>
            <option value="" selected disabled>select category</option>
               <option value="Fruits">Fruits</option>
               <option value="Vegetables">Vegetables</option>
               <option value="Grains">Grains</option>
         </select>

         <input type="text" name="ProductName" class="box" required placeholder="enter product name">

         <input type="text" name="SellerName" class="box" required placeholder="enter seller name">
         
         <select name="Unit" class="box" required>
            <option value="" selected disabled>select unit</option>
            <option value="Per Sack">Per Sack</option>
            <option value="Per Piece">Per Piece</option>
            <option value="Per Kilo">Per Kilo</option>
         </select>

         
        
         </div>
         <div class="inputBox">
         <input type="number" name="Quantity" placeholder="Enter Product Quantity" class="box" required>
         <input type="number" min="0" name="Price" class="box" required placeholder="enter product price">
         <input type="number" min="0" name="ShippingCharge" class="box" required placeholder="enter shipping charge">

         <select name="ProductAvailability" class="box" required>
            <option value="" selected disabled>Select Product Availability</option>
            <option value="Per Sack">In Stock</option>
            <option value="Per Piece">Out of Stock</option>
         </select>

         
         </div>
         <input type="file" name="image1" required class="box" accept="image/jpg, image/jpeg, image/png">
         <input type="file" name="image2" required class="box" accept="image/jpg, image/jpeg, image/png">
         <input type="file" name="image3" required class="box" accept="image/jpg, image/jpeg, image/png">
      </div>
      <textarea name="Details" class="box" required placeholder="enter product details" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="add product" name="add_product">
   </form>

</section>

<section class="show-products">

   <h1 class="title">products added</h1>

   <div class="box-container">

   <?php
      $show_products = $conn->prepare("SELECT * FROM `products`");
      $show_products->execute();
      if($show_products->rowCount() > 0){
         while($fetch_products = $show_products->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <div class="box">
      <div class="price">₱<?= $fetch_products['price']; ?></div>
      <img src="uploaded_img/<?= $fetch_products['image1']; ?>" alt="">
      <div class="ProductName"><?= $fetch_products['product_name']; ?></div>
      <div class="cat"><?= $fetch_products['category']; ?></div>
      <div class="details"><?= $fetch_products['details']; ?></div>
      <div class="flex-btn">
         <a href="admin_update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">update</a>
         <a href="admin_products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('delete this product?');">delete</a>
      </div>
   </div>
   <?php
      }
   }else{
      echo '<p class="empty">now products added yet!</p>';
   }
   ?>

   </div>

</section>











<script src="js/script.js"></script>

</body>
</html>