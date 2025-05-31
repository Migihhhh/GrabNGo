<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['student_id'])) {
  header("Location: login.html");
  exit();
}

// Load from session
$name = $_SESSION['name'] ?? 'Student';
$allowance = $_SESSION['allowance'] ?? 0;

// Database connection settings
$serverName = "localhost";
$connectionOptions = [
  "Database" => "GrabNGoDB",
  "Uid" => "",   // your DB username if any
  "PWD" => ""    // your DB password if any
];

// Connect to SQL Server
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
  die("Connection failed: " . print_r(sqlsrv_errors(), true));
}

// Fetch food items from database
$sql = "SELECT id, name, calories, price FROM foods";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
  die("Query failed: " . print_r(sqlsrv_errors(), true));
}

$foods = [];
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
  $foods[] = $row;
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
  <title>Meals - GrabNGo</title>
  <style>
    * {
      @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Signika:wght@300..700&display=swap');

    }

    body {
      background-color: #f6eddd;
    }

    .btn-custom {
      background-color: #95a472;
    }

    .btn-custom:hover {
      background-color: #3986c5
    }

    .nav-custom {
      background-color: #95a472;
    }

    .nav-link {
      font-weight: bold;
      font-size: 1.2rem;
      /* slightly larger */
    }

    .navbar-nav .nav-link:hover {
      color: white !important;
    }

    .ulam-header {
      margin-top: 30px;
    }

    .ulam-title {
      color: #95a472;
      font-weight: bold;
      font-size: 55px;
    }

    .ulam-menu-text {
      color: #95a472;
      font-size: 35px;
      margin-left: 10px;
    }

    .allowance-info {
      color: #95a472;
      font-size: 25px;
      font-weight: 500;
    }

    .ulam-card img {
      height: 150px;
      object-fit: cover;
    }

    .ulam-card {
      height: 100%;
      border-radius: 16px;
      overflow: hidden;
    }

    .ulam-card img {
      height: 250px;
      object-fit: cover;
      width: 100%;
    }

    .offcanvas {
      background-color: #f6eddd;
    }

    .offcanvas-header {
      border-bottom: 4px solid #95a472;
    }

    .cart-item img {
      width: 60px;
      /* fixed width */
      height: 60px;
      /* fixed height */
      object-fit: cover;
      /* keeps aspect ratio, crops if needed */
      border-radius: 8px;
      margin-right: 15px;
      border: 1px solid #ccc;
      flex-shrink: 0;
      /* prevents shrinking */
    }
  </style>
</head>

<body>
  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-lg px-3 nav-custom nav-custom">
    <div class="container-fluid">
      <div class="d-flex align-items-center">
        <a class="navbar-brand me-3" href="meals.php">
          <img src="/assets/logo.png" alt="Logo" width="100%" height="66px">
        </a>
        <ul class="navbar-nav flex-row px-4">
          <li class="nav-item me-3">
            <a class="nav-link" href="#">ULAM</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link" href="#">SNACKS</a>
          </li>
          <li class="nav-item me-3">
            <a class="nav-link" href="#">DRINKS</a>
          </li>
        </ul>
      </div>

      <div class="collapse navbar-collapse justify-content-end" id="rightItems">
        <div class="d-flex align-items-center">
          <button class="btn btn-outline-secondary d-flex align-items-center px-3 py-2" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
            <i class="bi bi-cart3 me-2"></i>
          </button>
        </div>

        <div class="d-flex align-items-center px-3">
          <button class="btn btn-outline-secondary d-flex align-items-center px-3 py-2">
            <span class="fw-bold">Hi, <?php echo htmlspecialchars($name); ?>!</span>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <div class="d-flex justify-content-between align-items-center ulam-header px-3">
    <!-- Left -->
    <div class="d-flex align-items-baseline">
      <span class="ulam-title px-3">ULAM</span>
      <span class="ulam-menu-text">Today's Menu</span>
    </div>

    <!-- Right -->
    <div class="d-flex align-items-center allowance-info">
      <i class="bi bi-wallet2 me-2 px-2"></i>
      Allowance: <?php echo number_format($allowance, 2); ?>
    </div>
  </div>

  <div class="container mt-4">
    <div class="row g-4">
      <!-- Cards will go here -->
    </div>
  </div>

  <!-- Cart -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="staticBackdropLabel">Your Cart</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        function addToCart(food) {
          const cart = document.querySelector(".offcanvas-body");

          const item = document.createElement("div");
          item.className = "cart-item d-flex align-items-center mb-3";
          item.innerHTML = `
            <img src="${food.image_url}" alt="${food.name}" />
            <div class="cart-item-details">
              <h6 class="mb-0">${food.name}</h6>
              <small>${food.calories} Kcal</small>
            </div>
            <span class="ms-auto fw-bold">₱${food.price}</span>
          `;
          cart.appendChild(item);
        }


        fetch("get_foods.php")
          .then((res) => res.json())
          .then((foods) => {
            const row = document.querySelector(".row.g-4");
            row.innerHTML = ""; // Clear any existing cards

            foods.forEach(food => {
              const col = document.createElement("div");
              col.className = "col-6 col-md-4 col-lg-3";
              col.innerHTML = `
              <div class="card ulam-card text-start shadow-sm">
                <img src="${food.image_url}" class="card-img-top" alt="${food.name}">
                <div class="card-body">
                  <h5 class="card-title">${food.name}</h5>
                  <p>Kcal: ${food.calories}</p>
                  <button class="btn btn-custom" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop">
                    ₱${food.price}
                  </button>
                </div>
              </div>
            `;
              const button = col.querySelector("button");
              button.addEventListener("click", () => {
                addToCart(food);
              });
              row.appendChild(col);
            });
          })
          .catch(err => console.error("Error fetching foods:", err));
      });
    </script>
</body>

</html>