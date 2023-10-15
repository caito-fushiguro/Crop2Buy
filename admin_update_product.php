<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_product'])){

   $pid = $_POST['pid'];

   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $productName = $_POST['productName'];
   $productName = filter_var($productName, FILTER_SANITIZE_STRING);

   $sellerName = $_POST['sellerName'];
   $sellerName = filter_var($sellerName, FILTER_SANITIZE_STRING);

   $unit = $_POST['unit'];
   $unit = filter_var($unit, FILTER_SANITIZE_STRING);

   $productQuantity = $_POST['productQuantity'];
   $productQuantity = filter_var($productQuantity, FILTER_SANITIZE_STRING);

   $productPrice = $_POST['productPrice'];
   $productPrice = filter_var($productPrice, FILTER_SANITIZE_STRING);

   $shippingCharge = $_POST['shippingCharge'];
   $shippingCharge = filter_var($shippingCharge, FILTER_SANITIZE_STRING);

   $productAvailability = $_POST['productAvailability'];
   $productAvailability = filter_var($productAvailability, FILTER_SANITIZE_STRING);

   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);
/*image1  */
   $image1 = $_FILES['image1']['name'];
   $image1 = filter_var($image1, FILTER_SANITIZE_STRING);
   $image1_size = $_FILES['image1']['size'];
   $image1_tmp_name = $_FILES['image1']['tmp_name'];
   $image1_folder = 'uploaded_img/'.$image1;
   $old_image1 = $_POST['old_image1'];
/*image2  */
   $image2 = $_FILES['image2']['name'];
   $image2 = filter_var($image2, FILTER_SANITIZE_STRING);
   $image2_size = $_FILES['image2']['size'];
   $image2_tmp_name = $_FILES['image2']['tmp_name'];
   $image2_folder = 'uploaded_img/'.$image2;
  /* $old_image2 = $_POST['old_image2'];*/
/* image3 */
   $image3 = $_FILES['image3']['name'];
   $image3 = filter_var($image3, FILTER_SANITIZE_STRING);
   $image3_size = $_FILES['image3']['size'];
   $image3_tmp_name = $_FILES['image3']['tmp_name'];
   $image3_folder = 'uploaded_img/'.$image3;
  /* $old_image3 = $_POST['old_image3'];*/

   $update_product = $conn->prepare("UPDATE `products` SET   category = ?, product_name = ?, seller_name = ?, unit = ?, quantity = ?, price = ?, shipping_charge = ?, product_availability = ?, image1 = ?, image2 = ?, image3 = ?, details = ? WHERE id = ?");
   $update_product->execute([$category, $productName, $sellerName, $unit, $productQuantity, $productPrice, $shippingCharge, $productAvailability, $image1, $image2, $image3, $details, $pid]);
   
   $message[] = 'product updated successfully!';

   if(!empty($image1)){
      if($image1_size > 2000000){
         $message[] = 'image size is too large!';
      }else{

         $update_image1 = $conn->prepare("UPDATE `products` SET image1 = ? WHERE id = ?");
         $update_image1->execute([$image1, $pid]);

         if($update_image1){
            move_uploaded_file($image1_tmp_name, $image1_folder);
            unlink('uploaded_img/'.$old_image1);
            $message[] = 'image updated successfully!';
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="update-product">

   <h1 class="title">update product</h1>   

   <?php
      $update_id = $_GET['update'];
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $select_products->execute([$update_id]);
      if($select_products->rowCount() > 0){
         while($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image1" value="<?= $fetch_products['image1']; ?>">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <img src="uploaded_img1/<?= $fetch_products['image1']; ?>" alt="">

      <select name="category" class="box" required>
         <option selected><?= $fetch_products['category']; ?></option>
         <option value="Fruits">Fruits</option>
         <option value="Vegetables">Vegetables</option>
         <option value="Grains">Grains</option>
      </select>

      <input type="text" name="productName" placeholder="enter product name" required class="box" value="<?= $fetch_products['product_name']; ?>">
      <input type="text" name="sellerName" placeholder="enter seller name" required class="box" value="<?= $fetch_products['seller_name']; ?>">
      
      <select name="unit" class="box" required>
         <option selected><?= $fetch_products['unit']; ?></option>
         <option value="Per Sack">Per Sack</option>
         <option value="Per Piece">Per Piece</option>
         <option value="Per Kilo">Per Kilo</option>
      </select>

      <input type="number" name="productQuantity" min="0" placeholder="enter product quantity" required class="box" value="<?= $fetch_products['quantity']; ?>">
      <input type="number" name="productPrice" min="0" placeholder="enter product price" required class="box" value="<?= $fetch_products['price']; ?>">
      <input type="number" name="shippingCharge" min="0" placeholder="enter shipping charge" required class="box" value="<?= $fetch_products['shipping_charge']; ?>">

      <select name="productAvailability" class="box" required>
         <option selected><?= $fetch_products['unit']; ?></option>
         <option value="In Stock">In Stock</option>
         <option value="Out of stock">Out of stock</option>
      </select>

      <input type="file" name="image1" class="box" accept="image/jpg, image/jpeg, image/png">
      <input type="file" name="image2" class="box" accept="image/jpg, image/jpeg, image/png">
      <input type="file" name="image3" class="box" accept="image/jpg, image/jpeg, image/png">

      <textarea name="details" required placeholder="enter product details" class="box" cols="30" rows="10"><?= $fetch_products['details']; ?></textarea>
     


      <div class="flex-btn">
         <input type="submit" class="btn" value="update product" name="update_product">
         <a href="admin_products.php" class="option-btn">go back</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no products found!</p>';
      }
   ?>

</section>













<script src="js/script.js"></script>

</body>
</html>