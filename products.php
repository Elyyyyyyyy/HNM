<!doctype html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/bootstrap.min.css" />
  <link rel="stylesheet" href="css/bootstrap-icons.min.css">
  <link rel="stylesheet" href="css/univ.css" />
  <title>Products</title>
</head>

<body class="d-flex flex-column min-vh-100">
  <header>
    <nav class="navbar navbar-expand-lg fixed-top px-4 text-dark">
      <a class="navbar-brand" href="index.php">
        <img src="images/logo.png" alt="Logo" height="50" />
      </a>

      <ul class="navbar-nav nav-underline ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="html/home.html">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active" href="products.php">Products</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="html/about.html">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="html/contact.html">Contact Us</a>
        </li>
      </ul>
    </nav>
  </header>

  <section id="products" class="container my-5">
    <?php
    include 'db.php';
    $sql = "SELECT * FROM products";
    $result = $conn->query($sql);
    ?>

    <div class="row g-5">
      <?php
      if ($result->num_rows > 0) {
        while ($product = $result->fetch_assoc()) {
      ?>

          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card">
              <img src="images/<?php echo $product['p_image'] ?>" class="card-img-top" alt="Product Image" />
              <div class="card-body">
                <h5 class="card-title"> <?php echo $product['p_name'] ?> </h5>
                <p class="ms-1 mb-1 fw-normal">₱<?php echo $product['p_price'] ?></p>

                <a
                  class="btn btn-success"
                  data-bs-toggle="modal"
                  data-bs-target="#modal"
                  onclick="buyNow (<?php echo $product['p_id']; ?>,
                '<?php echo addslashes($product['p_name']); ?>',
                <?php echo $product['p_price']; ?>,
                <?php echo $product['p_stocks']; ?>,
                '<?php echo $product['p_image']; ?>')"> Buy Now </a>

                <button type="button" class="btn btn-outline-secondary"
                  onclick="addToCart(<?php echo $product['p_id'] ?>)"> <i class="bi bi-cart-plus"></i> </button>

                <p class="mb-0 mt-1 ms-1 fw-light">Stocks: <?php echo $product['p_stocks'] ?></p>
              </div>
            </div>
          </div>

      <?php
        }
      } else {
        echo "<p>No Products Available</p>";
      }
      $conn->close();
      ?>
    </div>
  </section>

  <div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">

        <form id="buyNowForm">
          <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Confirm Purchase</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="p_id" id="modalp_id">

            <div class="row">
              <!-- Product Image -->
              <div class="col-md-5 text-center mb-3 mb-md-0">
                <img id="modalp_image" src="" alt="" class="img-fluid rounded" />
              </div>

              <!-- Product Details -->
              <div class="col-md-7">
                <p><strong>Product:</strong> <span id="modalp_name"></span></p>
                <p><strong>Price:</strong> ₱<span id="modalp_price"></span></p>
                <p><strong>Stocks:</strong> <span id="modalp_stocks"></span></p>

                <div class="mt-3">
                  <label for="modalp_quantity" class="form-label"><strong>Quantity:</strong></label>
                  <input
                    type="number"
                    name="quantity"
                    id="modalp_quantity"
                    class="form-control w-50"
                    value="1"
                    min="1"
                    required>
                  <div class="text-danger small mt-1" id="quantityError"></div>
                </div>

                <p class="mt-3">
                  <strong>Total Amount:</strong> ₱<span id="modalTotalAmount">0.00</span>
                </p>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success" id="confirmBtn">Confirm</button>
          </div>
        </form>

      </div>
    </div>
  </div>


  <footer class="text-dark mt-auto">
    <div class="container py-4">
      <div class="row">
        <div class="col-md-4 mb-3">
          <h5>H&M</h5>
          <p class="small">Tindahan ng mga naaagnas na damit.</p>
        </div>

        <div class="col-md-4 mb-3">
          <h5>Quick Links</h5>
          <ul class="list-unstyled">
            <li>
              <a href="html/home.html" class="text-dark text-decoration-none">Home</a>
            </li>
            <li>
              <a href="products.php" class="text-dark text-decoration-none">Products</a>
            </li>
            <li>
              <a href="html/about.html" class="text-dark text-decoration-none">About Us</a>
            </li>
            <li>
              <a href="html/contact.html" class="text-dark text-decoration-none">Contact</a>
            </li>
          </ul>
        </div>

        <div class="col-md-4 mb-3">
          <h5>Contact</h5>
          <p class="small mb-1">Lorem Ipsum</p>
          <p class="small mb-1">Lorem Ipsum</p>
          <p class="small">Lorem Ipsum</p>
        </div>
      </div>

      <hr class="border-secondary" />

      <div class="text-center small">© 2026 Bahay. All rights reserved.</div>
    </div>
  </footer>

  <script src="js/bootstrap.bundle.min.js"></script>

  <script>
    let currentPrice = 0;

    const modalEl = document.getElementById('modal');
    const modalInstance = new bootstrap.Modal(modalEl);

    // Function to open the modal and populate data
    function buyNow(id, name, price, stocks, image) {
      currentPrice = price;

      document.getElementById("modalp_id").value = id;
      document.getElementById("modalp_name").innerText = name;
      document.getElementById("modalp_price").innerText = price.toFixed(2);
      document.getElementById("modalp_stocks").innerText = stocks;
      document.getElementById("modalp_image").src = "images/" + image;
      document.getElementById("modalp_image").alt = name;

      let qtyInput = document.getElementById("modalp_quantity");
      qtyInput.value = 1;
      qtyInput.max = stocks;

      document.getElementById("quantityError").innerText = "";
      document.getElementById("confirmBtn").disabled = false;

      updateTotal();
    }

    // Update total amount in modal
    function updateTotal() {
      let qtyInput = document.getElementById("modalp_quantity");
      let qty = parseInt(qtyInput.value);
      let stocks = parseInt(document.getElementById("modalp_stocks").innerText);
      let errorEl = document.getElementById("quantityError");
      let confirmBtn = document.getElementById("confirmBtn");

      // Reseterror
      errorEl.innerText = "";

      if (isNaN(qty) || qty <= 0) {
        errorEl.innerText = "Please enter a valid quantity.";
        confirmBtn.disabled = true;
        return;
      }

      if (qty > stocks) {
        errorEl.innerText = "Quantity exceeds available stock.";
        confirmBtn.disabled = true;
        return;
      }

      confirmBtn.disabled = false;

      let total = currentPrice * qty;
      document.getElementById("modalTotalAmount").innerText = total.toFixed(2);
    }

    // Handle confirm purchase with AJAX
    document.getElementById('buyNowForm').addEventListener('submit', function(e) {
      e.preventDefault();

      let p_id = document.getElementById('modalp_id').value;
      let quantity = document.getElementById('modalp_quantity').value;

      let xhr = new XMLHttpRequest();
      xhr.open('POST', 'purchase.php', true);
      xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

      xhr.onload = function() {
        let response = JSON.parse(this.responseText);

        if (response.status === 'success') {
          alert(response.message); // Show success message

          // Update stocks in the modal
          document.getElementById("modalp_stocks").innerText = response.new_stock;

          // Update stock on the product card dynamically
          let cardStock = document.querySelector(
            `[onclick*="buyNow(${p_id},"]`).parentElement.querySelector('p.mb-0');
          if (cardStock) cardStock.innerText = "Stocks: " + response.new_stock;

          // Close the modal
          let modalEl = document.getElementById('modal');
          let modal = bootstrap.Modal.getInstance(modalEl);
          modal.hide();
        } else {
          alert(response.message); // Show error if any
        }
      };

      xhr.send('p_id=' + p_id + '&quantity=' + quantity);
    });
  </script>

</body>

</html>