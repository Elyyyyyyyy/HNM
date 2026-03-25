<?php
@include '../db.php';

if (!isset($_GET['id'])) {
    header('Location: admin_page.php');
    exit;
}

$id = $_GET['id'];

// Fetch product from database
$result = mysqli_query($conn, "SELECT * FROM products WHERE p_id = '$id'");
$product = mysqli_fetch_assoc($result);

if (!$product) {
    header('Location: admin_page.php');
    exit;
}

// Handle form submission
if (isset($_POST['update_product'])) {
    $name = $_POST['product_name'];
    $price = $_POST['product_price'];
    $stocks = $_POST['product_stocks'];

    // Handle image upload
    $image = $product['p_image']; // default to existing
    if (!empty($_FILES['product_image']['name'])) {
        $image = $_FILES['product_image']['name'];
        $tmp_name = $_FILES['product_image']['tmp_name'];
        move_uploaded_file($tmp_name, 'prod_images/' . $image);
    }

    $update = "UPDATE products SET 
                p_name = '$name', 
                p_price = '$price', 
                p_stocks = '$stocks', 
                p_image = '$image' 
               WHERE p_id = '$id'";

    if (mysqli_query($conn, $update)) {
        $message = "Product updated successfully";
        header("Location: admin_page.php");
        exit;
    } else {
        $message = "Failed to update product";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Edit Product</title>
   <link rel="stylesheet" href="../css/bootstrap.min.css">
   <script defer src="../js/bootstrap.bundle.min.js"></script>
</head>
<body class="container py-5">

<?php if(isset($message)) echo "<div class='alert alert-info'>$message</div>"; ?>

<h2>Edit Product</h2>
<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label">Product Name</label>
        <input type="text" name="product_name" class="form-control" value="<?php echo $product['p_name']; ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Price</label>
        <input type="number" name="product_price" class="form-control" value="<?php echo $product['p_price']; ?>" min="0" step="0.01" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Stocks</label>
        <input type="number" name="product_stocks" class="form-control" value="<?php echo $product['p_stocks']; ?>" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Product Image</label>
        <input type="file" name="product_image" class="form-control">
        <small>Current: <?php echo $product['p_image']; ?></small>
    </div>

    <button type="submit" name="update_product" class="btn btn-success">Update Product</button>
    <a href="admin_page.php" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>
