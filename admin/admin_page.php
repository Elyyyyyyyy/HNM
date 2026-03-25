<?php
$message = [];
@include '../db.php';

if (isset($_POST['add_product'])) {

   $product_name = $_POST['product_name'];
   $product_price = $_POST['product_price'];
   $product_stocks = $_POST['product_stocks'];
   $product_image = $_FILES['product_image']['name'];
   $product_image_tmp_name = $_FILES['product_image']['tmp_name'];
   $product_image_folder = '../prod_images/' . $product_image;

   if (empty($product_name) || empty($product_price) || empty($product_image) || $product_stocks === '') {
      $message[] = 'Please fill out all fields';
   } else if ($product_price <= 0 || $product_stocks < 0) {
      $message[] = 'Invalid price or stock';
   } else {
      $insert = "INSERT INTO products(p_name, p_price, p_stocks, p_image) VALUES('$product_name', '$product_price', '$product_stocks', '$product_image')";
      $upload = mysqli_query($conn, $insert);
      if ($upload) {
         move_uploaded_file($product_image_tmp_name, $product_image_folder);
         $message[] = 'New product added successfully';
      } else {
         $message[] = 'Could not add the product';
      }
   }
}

if (isset($_GET['delete'])) {
   $id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM products WHERE p_id = $id");
   header('location:admin_page.php');
   exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Page</title>

   <link rel="stylesheet" href="../css/bootstrap-icons.min.css">
   <link rel="stylesheet" href="../css/bootstrap.min.css">
   <script defer src="js/bootstrap.bundle.min.js"></script>
   <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
</head>

<body class="d-flex flex-column min-vh-100 font-poppins">

   <!-- Messages -->
   <div class="container">
      <?php if (!empty($message)) {
         foreach ($message as $msg) { ?>
            <div class="alert alert-light text-center fs-5"><?= $msg ?></div>
      <?php }
      } ?>
   </div>

   <!-- Add Product Form -->
   <div class="container mt-4">
      <div class="row justify-content-center">
         <div class="col-md-6">
            <div class="card shadow">
               <div class="card-header text-center fw-bold">
                  Add New Product
               </div>
               <div class="card-body">
                  <form method="post" enctype="multipart/form-data">
                     <input type="text" name="product_name" class="form-control mb-3" placeholder="Product name" required>
                     <input type="number" name="product_price" class="form-control mb-3" placeholder="Product price" min="0" step="0.01" required>
                     <input type="number" name="product_stocks" class="form-control mb-3" placeholder="Product stocks" min="0" required>
                     <input type="file" name="product_image" class="form-control mb-3" required>
                     <button name="add_product" class="btn btn-success w-100">
                        <i class="bi bi-plus-circle"></i> Add Product
                     </button>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>

   <!-- Product Table -->
   <div class="container my-4 overflow-auto">
      <?php
      $select = mysqli_query($conn, "SELECT * FROM products");
      ?>
      <table class="table table-bordered text-center align-middle">
         <thead class="table-light">
            <tr>
               <th>Product Image</th>
               <th>Product Name</th>
               <th>Price</th>
               <th>Stocks</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php while ($row = mysqli_fetch_assoc($select)) { ?>
               <tr>
                  <td>
                     <img src="../prod_images/<?= $row['p_image'] ?>" style="height:100px; width:100px; object-fit:cover;" alt="Product Image">
                  </td>
                  <td><?= $row['p_name'] ?></td>
                  <td>₱<?= $row['p_price'] ?></td>
                  <td><?= $row['p_stocks'] ?></td>
                  <td class="d-flex justify-content-center gap-2">
                     <a class="btn btn-warning" href="admin_edit.php?id=<?= $row['p_id'] ?>"><i class="bi bi-pencil-square"></i> Edit</a>
                     <a class="btn btn-danger" href="admin_page.php?delete=<?= $row['p_id'] ?>"><i class="bi bi-trash"></i> Delete</a>
                  </td>
               </tr>
            <?php } ?>
         </tbody>
      </table>
   </div>

   <footer style="background-color: rgb(240, 240, 240);" class="text-dark mt-auto">
      <div class="container py-4">
         <div class="row">
            <div class="col-md-4 mb-3">
               <h5>H&M</h5>
               <p class="small">Tindahan ng mga naaagnas na damit.</p>
            </div>
            <div class="col-md-4 mb-3">
               <h5>Quick Links</h5>
               <ul class="list-unstyled">
                  <li><a href="html/home.html" class="text-dark text-decoration-none">Home</a></li>
                  <li><a href="products.php" class="text-dark text-decoration-none">Products</a></li>
                  <li><a href="html/about.html" class="text-dark text-decoration-none">About Us</a></li>
                  <li><a href="html/contact.html" class="text-dark text-decoration-none">Contact</a></li>
               </ul>
            </div>
            <div class="col-md-4 mb-3">
               <h5>Contact</h5>
               <p class="small mb-1">Lorem Ipsum</p>
               <p class="small mb-1">Lorem Ipsum</p>
               <p class="small">Lorem Ipsum</p>
            </div>
         </div>
         <hr class="border-secondary">
         <div class="text-center small">© 2026 Bahay. All rights reserved.</div>
      </div>
   </footer>

</body>

</html>