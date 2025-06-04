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
$sql = "SELECT id, name, calories, price, image_url FROM foods";
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
      height: 60px;
      object-fit: cover;
      border-radius: 8px;
      margin-right: 15px;
      border: 1px solid #ccc;
      flex-shrink: 0;
    }

    .remove-btn {
      font-size: 1.2rem;
      padding: 0 8px;
      line-height: 1;
    }

    .offcanvas-body ul.list-group {
      max-height: 200px;
      overflow-y: auto;
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
          <button class="btn btn-outline-secondary d-flex align-items-center px-3 py-2" type="button"
            data-bs-toggle="offcanvas" data-bs-target="#userOffcanvas" aria-controls="userOffcanvas">
            <i class="bi bi-person-circle me-2"></i>
            <span class="fw-bold">Hi, <?php echo htmlspecialchars($name); ?>!</span>
          </button>
        </div>
      </div>
    </div>
  </nav>

  <div class="d-flex justify-content-between align-items-center ulam-header px-3">
    <!-- yung top text na may ULAM   -->
    <div class="d-flex align-items-baseline">
      <span class="ulam-title px-3">ULAM</span>
      <span class="ulam-menu-text">Today's Menu</span>
    </div>

    <!-- allowance -->
    <div class="d-flex align-items-center allowance-info">
      <i class="bi bi-wallet2 me-2 px-2"></i>
      Allowance: <?php echo number_format($allowance, 2); ?>
    </div>
  </div>

  <div class="container mt-4">
    <div class="row g-4">
      <!-- Cards will go here -->
      <?php foreach ($foods as $food): ?>
        <div class="col-md-4">
          <div class="card ulam-card shadow-sm">
            <img src="<?php echo htmlspecialchars($food['image_url']); ?>"
              alt="<?php echo htmlspecialchars($food['name']); ?>" class="card-img-top" />
            <div class="card-body">
              <h5 class="card-title"><?php echo htmlspecialchars($food['name']); ?></h5>
              <p class="card-text"><?php echo $food['calories']; ?> Kcal</p>
              <p class="card-text fw-bold">₱<?php echo number_format($food['price'], 2); ?></p>
              <button class="btn btn-custom w-100 add-to-cart" data-id="<?php echo $food['id']; ?>"
                data-name="<?php echo htmlspecialchars($food['name']); ?>"
                data-calories="<?php echo $food['calories']; ?>" data-price="<?php echo $food['price']; ?>"
                data-image="<?php echo htmlspecialchars($food['image_url']); ?>">
                Add to Cart
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

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
      <div class="cart-items"></div>
      <p id="empty-cart-msg">Your cart is empty.</p>
      <hr />
      <div class="d-flex justify-content-between">
        <strong>Total:</strong>
        <span id="cart-total">₱0.00</span>
      </div>
      <button id="checkout-btn" class="btn btn-success w-100 mt-3">Checkout</button>
    </div>
  </div>

  <!-- User Profile Offcanvas -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="userOffcanvas" aria-labelledby="userOffcanvasLabel">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="userOffcanvasLabel">User Profile</h5>
      <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
      <p class="mb-2">Name: <strong><?php echo htmlspecialchars($name); ?></strong></p>
      <p class="mb-4">Allowance: <strong>₱<?php echo number_format($allowance, 2); ?></strong></p>

      <hr>
      <h6 class="text-muted mb-3">Notifications</h6>
      <ul id="notifications-list" class="list-group mb-4">
        <!-- Notifications will load here via JavaScript -->
      </ul>

      <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const cartContainer = document.querySelector(".cart-items");
      const cartTotal = document.getElementById("cart-total");
      const emptyCartMsg = document.getElementById("empty-cart-msg");
      const checkoutBtn = document.getElementById("checkout-btn");

      let cart = [];
      let total = 0;

      function updateCartDisplay() {
        cartContainer.innerHTML = "";
        total = 0;

        if (cart.length === 0) {
          emptyCartMsg.style.display = "block";
          cartTotal.textContent = "₱0.00";
          return;
        }

        emptyCartMsg.style.display = "none";

        cart.forEach((food, index) => {
          total += parseFloat(food.price); // Ensure recalculation

          const item = document.createElement("div");
          item.className = "cart-item d-flex align-items-center mb-3";

          item.innerHTML = `
      <img src="${food.image_url}" alt="${food.name}" 
        style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;" />
      <div class="cart-item-details flex-grow-1">
        <h6 class="mb-0">${food.name}</h6>
        <small>${food.calories} Kcal</small>
      </div>
      <span class="fw-bold me-2">₱${parseFloat(food.price).toFixed(2)}</span>
      <button class="btn btn-sm btn-danger remove-btn" data-index="${index}">&times;</button>
    `;

          cartContainer.appendChild(item);
        });

        cartTotal.textContent = `₱${total.toFixed(2)}`;
      }

      function loadNotifications() {
        fetch('get_notifications.php')
          .then(response => response.json())
          .then(notifications => {
            const container = document.getElementById('notifications-list');
            container.innerHTML = '';

            notifications.forEach(notif => {
              const date = new Date(notif.created_at);
              const formattedDate = date.toLocaleDateString('en-US', {
                month: 'short',
                day: 'numeric'
              });

              const li = document.createElement('li');
              li.className = 'list-group-item d-flex justify-content-between align-items-center';
              li.innerHTML = `
                    Your Order #${notif.order_id} is currently ${notif.status}
                    <span class="badge bg-secondary rounded-pill">${formattedDate}</span>
                `;
              container.appendChild(li);
            });

            if (notifications.length === 0) {
              container.innerHTML = '<li class="list-group-item">No notifications</li>';
            }
          });
      }

      // Call when page loads and when opening profile
      document.addEventListener('DOMContentLoaded', loadNotifications);
      document.getElementById('userOffcanvas').addEventListener('show.bs.offcanvas', loadNotifications);

      function addToCart(food) {
        cart.push(food);
        total += food.price;
        updateCartDisplay();
      }

      // Remove item handler
      cartContainer.addEventListener("click", function (e) {
        if (e.target.classList.contains("remove-btn")) {
          const index = parseInt(e.target.getAttribute("data-index"));
          total -= cart[index].price;
          cart.splice(index, 1);
          updateCartDisplay();
        }
      });

      // Checkout
      checkoutBtn.addEventListener("click", async function () {
        if (cart.length === 0) {
          alert("Your cart is empty.");
          return;
        }

        // Check if user has enough allowance
        const currentAllowance = <?php echo $allowance; ?>;
        if (total > currentAllowance) {
          alert("Insufficient allowance for this order.");
          return;
        }

        try {
          const response = await fetch("place_order.php", {
            method: "POST",
            headers: {
              "Content-Type": "application/json",
            },
            body: JSON.stringify({
              student_id: <?php echo $_SESSION['student_id']; ?>,
              total_amount: total,
              items: cart,
            }),
          });

          // Always attempt to parse as JSON first, as your PHP always returns JSON.
          const data = await response.json(); // Read the body ONCE as JSON.

          if (response.ok) { // Check if the HTTP response status was successful
            if (data.success) {
              alert(`Order #${data.order_id} placed successfully!`);
              cart = [];
              total = 0;
              updateCartDisplay();

              // Update allowance display
              document.querySelector('.allowance-info').innerHTML = `
                  <i class="bi bi-wallet2 me-2 px-2"></i>
                  Allowance: ₱${data.new_allowance.toFixed(2)}
              `;
            } else {
              // If response.ok is true but data.success is false, it's an application-level error
              throw new Error(data.message || 'Order failed');
            }
          } else {
            // If response.ok is false, it's a server error, and 'data' should contain the error message from PHP
            throw new Error(data.message || `Server error: ${response.status} - ${response.statusText}`);
          }
        } catch (err) {
          console.error('Checkout error:', err);
          alert(`Error placing order: ${err.message}`);
        }
      });

      // Load food items
      fetch("get_foods.php")
        .then((res) => res.json())
        .then((foods) => {
          const row = document.querySelector(".row.g-4");
          row.innerHTML = "";

          foods.forEach((food) => {
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
        .catch((err) => console.error("Error fetching foods:", err));
    });
  </script>

  <!-- Code injected by live-server -->
  <script>
    // <![CDATA[  <-- For SVG support
    if ('WebSocket' in window) {
      (function () {
        function refreshCSS() {
          var sheets = [].slice.call(document.getElementsByTagName("link"));
          var head = document.getElementsByTagName("head")[0];
          for (var i = 0; i < sheets.length; ++i) {
            var elem = sheets[i];
            var parent = elem.parentElement || head;
            parent.removeChild(elem);
            var rel = elem.rel;
            if (elem.href && typeof rel != "string" || rel.length == 0 || rel.toLowerCase() == "stylesheet") {
              var url = elem.href.replace(/(&|\?)_cacheOverride=\d+/, '');
              elem.href = url + (url.indexOf('?') >= 0 ? '&' : '?') + '_cacheOverride=' + (new Date().valueOf());
            }
            parent.appendChild(elem);
          }
        }
        var protocol = window.location.protocol === 'http:' ? 'ws://' : 'wss://';
        var address = protocol + window.location.host + window.location.pathname + '/ws';
        var socket = new WebSocket(address);
        socket.onmessage = function (msg) {
          if (msg.data == 'reload') window.location.reload();
          else if (msg.data == 'refreshcss') refreshCSS();
        };
        if (sessionStorage && !sessionStorage.getItem('IsThisFirstTime_Log_From_LiveServer')) {
          console.log('Live reload enabled.');
          sessionStorage.setItem('IsThisFirstTime_Log_From_LiveServer', true);
        }
      })();
    }
    else {
      console.error('Upgrade your browser. This Browser is NOT supported WebSocket for Live-Reloading.');
    }
    // ]]>
  </script>
</body>

</html>