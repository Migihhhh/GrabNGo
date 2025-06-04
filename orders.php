<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - GrabNGo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f6eddd;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background-color: #95a472;
        }

        .nav-link {
            font-weight: bold;
            font-size: 1.2rem;
            color: #333 !important;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: white !important;
        }

        .nav-link.active {
            color: white !important;
            text-decoration: underline;
            text-underline-offset: 5px;
        }

        .page-title {
            color: #95a472;
            font-weight: bold;
            font-size: 2.5rem;
            margin: 20px 0;
        }

        .table-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }

        .table-custom th {
            background-color: #95a472;
            color: white;
            font-weight: 600;
            vertical-align: middle;
        }

        .table-custom tbody tr {
            transition: background-color 0.2s;
        }

        .table-custom tbody tr:hover {
            background-color: rgba(149, 164, 114, 0.1);
        }

        .btn-edit {
            background-color: #4a6da7;
            color: white;
            padding: 5px 10px;
        }

        .btn-delete {
            background-color: #dc3545;
            color: white;
            padding: 5px 10px;
        }

        .btn-custom {
            background-color: #95a472;
            color: white;
            font-weight: 500;
            padding: 8px 20px;
        }

        .btn-custom:hover {
            background-color: #7d8a5e;
            color: white;
        }

        .search-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: linear-gradient(135deg, #95a472, #7d8a5e);
            color: white;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h5 {
            font-size: 1.1rem;
            margin-bottom: 10px;
        }

        .stat-card p {
            font-size: 1.8rem;
            font-weight: bold;
            margin: 0;
        }

        .offcanvas-header {
            background-color: #95a472;
            color: white;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-weight: 500;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-processing {
            background-color: #cce5ff;
            color: #004085;
        }

        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        .order-id {
            font-weight: bold;
            color: #95a472;
        }

        .highlight {
            background-color: #fff3cd;
        }

        .no-orders {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
        }

        .search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a472;
        }

        .filter-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #95a472;
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper input {
            padding-left: 40px;
        }

        .filter-wrapper {
            position: relative;
        }

        .filter-wrapper select {
            padding-left: 40px;
        }

        .status-count {
            font-size: 0.8rem;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 2px 8px;
            margin-left: 5px;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #95a472;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
            display: none;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: none;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-custom py-2">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMjAiIGhlaWdodD0iNDAiPjx0ZXh0IHg9IjAiIHk9IjMwIiBmb250LXNpemU9IjIwIiBmb250LWZhbWlseT0iQXJpYWwiIGZpbGw9IiNmZmYiPkdyYWJOIEdvPC90ZXh0Pjwvc3ZnPg=="
                    alt="GrabNGo Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="admin_dashboard.html">DASHBOARD</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link active" href="orders.php">ORDERS</a>
                    </li>
                    <li class="nav-item mx-2">
                        <a class="nav-link" href="menu.html">MENU</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <button class="btn btn-outline-light d-flex align-items-center" type="button"
                        data-bs-toggle="offcanvas" data-bs-target="#userOffcanvas">
                        <i class="bi bi-person-circle me-2"></i>
                        <span class="fw-bold">Hi, Admin!</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <?php

    $serverName = "localhost";
    $connectionOptions = [
        "Database" => "GrabNGoDB",
        "Uid" => "",
        "PWD" => ""
    ];
    $conn = sqlsrv_connect($serverName, $connectionOptions);

    if (!$conn) {
        die("Connection failed: " . print_r(sqlsrv_errors(), true));
    }

    function getStatusClass($status)
    {
        switch ($status) {
            case 'Pending':
                return 'status-pending';
            case 'Processing':
                return 'status-processing';
            case 'Completed':
                return 'status-completed';
            case 'Cancelled':
                return 'status-cancelled';
            default:
                return '';
        }
    }

    // Fetch orders from database
    $sql = "SELECT 
                o.id AS order_id,
                o.student_id,
                f.name AS food_name,
                oi.quantity,
                f.price AS item_price,
                (oi.quantity * f.price) AS total_price,
                o.status
            FROM orders o
            INNER JOIN order_items oi ON o.id = oi.order_id
            INNER JOIN foods f ON oi.food_id = f.id
            ORDER BY o.order_time DESC";

    $stmt = sqlsrv_query($conn, $sql);
    $orders = [];
    if ($stmt !== false) {
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $orders[] = $row;
        }
    }
    ?>


    <!-- Notification Toast -->
    <div class="notification toast align-items-center text-white bg-success border-0" role="alert" aria-live="assertive"
        aria-atomic="true">
        <div class="d-flex">
            <div class="toast-body">
                Operation completed successfully!
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                aria-label="Close"></button>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container py-4">
        <!-- Page Title -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title">ORDERS MANAGEMENT</h1>
            <button id="refreshBtn" class="btn btn-custom">
                <i class="bi bi-arrow-clockwise me-2"></i>Refresh Data
            </button>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>TOTAL ORDERS</h5>
                    <p id="total-orders">0</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>COMPLETED</h5>
                    <p id="completed-orders">0</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>PENDING</h5>
                    <p id="pending-orders">0</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>TODAY'S REVENUE</h5>
                    <p id="todays-revenue">₱0.00</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="search-container">
            <div class="row">
                <div class="col-md-8 mb-3 mb-md-0">
                    <div class="search-wrapper">
                        <i class="bi bi-search search-icon"></i>
                        <input type="text" class="form-control" id="searchInput" placeholder="Search orders...">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="filter-wrapper">
                        <i class="bi bi-funnel filter-icon"></i>
                        <select class="form-select" id="statusFilter">
                            <option value="all">All Status</option>
                            <option value="Pending">Pending</option>
                            <option value="Processing">Processing</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover table-custom align-middle">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Student ID</th>
                            <th>Food Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Function</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody">
                        <?php if (count($orders) > 0): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td class="order-id">#<?= $order['order_id'] ?></td>
                                    <td><?= $order['student_id'] ?></td>
                                    <td><?= $order['food_name'] ?></td>
                                    <td><?= $order['quantity'] ?></td>
                                    <td>₱<?= number_format($order['total_price'], 2) ?></td>
                                    <td>
                                        <span class="status-badge <?= getStatusClass($order['status']) ?>">
                                            <?= $order['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-edit me-2" data-order-id="<?= $order['order_id'] ?>"
                                            data-student-id="<?= $order['student_id'] ?>"
                                            data-food-name="<?= htmlspecialchars($order['food_name']) ?>"
                                            data-quantity="<?= $order['quantity'] ?>" data-price="<?= $order['item_price'] ?>"
                                            data-status="<?= $order['status'] ?>">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>
                                        <button class="btn btn-delete" data-order-id="<?= $order['order_id'] ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="bi bi-exclamation-circle" style="font-size: 3rem;"></i>
                                    <h4 class="mt-3">No orders found</h4>
                                    <p>Try adjusting your search or filter criteria</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Order Modal -->
        <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOrderModalLabel">Edit Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editOrderForm">
                            <input type="hidden" id="modalOrderId">
                            <input type="hidden" id="modalFoodId">

                            <div class="mb-3">
                                <label for="modalStudentId" class="form-label">Student ID</label>
                                <input type="text" class="form-control" id="modalStudentId" required>
                            </div>

                            <div class="mb-3">
                                <label for="modalFoodName" class="form-label">Food Name</label>
                                <select class="form-select" id="modalFoodName" required>
                                    <!-- Food options will be populated dynamically -->
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="modalQuantity" class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="modalQuantity" min="1" required>
                            </div>

                            <div class="mb-3">
                                <label for="modalPrice" class="form-label">Price (₱)</label>
                                <input type="text" class="form-control" id="modalPrice" readonly>
                            </div>

                            <div class="mb-3">
                                <label for="modalStatus" class="form-label">Status</label>
                                <select class="form-select" id="modalStatus" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Processing">Processing</option>
                                    <option value="Completed">Completed</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-custom" id="saveEditButton">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Profile Offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="userOffcanvas">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title">Admin Profile</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"
                    aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <div class="text-center mb-4">
                    <i class="bi bi-person-circle" style="font-size: 5rem; color: #95a472;"></i>
                    <h4 class="mt-2">Administrator</h4>
                    <p class="text-muted">admin@grabngo.com</p>
                </div>

                <div class="list-group">
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="bi bi-gear me-2"></i> Account Settings
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="bi bi-shield-lock me-2"></i> Security
                    </a>
                    <a href="#" class="list-group-item list-group-item-action">
                        <i class="bi bi-question-circle me-2"></i> Help Center
                    </a>
                </div>

                <hr class="my-4">

                <div class="d-grid">
                    <a href="login.html" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                    </a>
                </div>
            </div>
        </div>



        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Edit button functionality
                document.querySelectorAll('.btn-edit').forEach(button => {
                    button.addEventListener('click', function () {
                        const orderId = this.dataset.orderId;
                        const studentId = this.dataset.studentId;
                        const foodName = this.dataset.foodName;
                        const quantity = this.dataset.quantity;
                        const price = this.dataset.price;
                        const status = this.dataset.status;

                        // Populate modal fields
                        document.getElementById('modalOrderId').value = orderId;
                        document.getElementById('modalStudentId').value = studentId;
                        document.getElementById('modalQuantity').value = quantity;
                        document.getElementById('modalPrice').value = price;
                        document.getElementById('modalStatus').value = status;

                        // Set food name in dropdown
                        const foodSelect = document.getElementById('modalFoodName');
                        Array.from(foodSelect.options).forEach(option => {
                            if (option.text === foodName) {
                                option.selected = true;
                            }
                        });
                    });
                });

                // Delete button functionality
                document.querySelectorAll('.btn-delete').forEach(button => {
                    button.addEventListener('click', function () {
                        if (confirm('Are you sure you want to delete this order?')) {
                            const orderId = this.dataset.orderId;
                            // AJAX call to delete order would go here
                            alert(`Order #${orderId} would be deleted in a real implementation`);
                        }
                    });
                });

                // Save edit functionality
                document.getElementById('saveEditButton').addEventListener('click', function () {
                    const orderId = document.getElementById('modalOrderId').value;
                    const studentId = document.getElementById('modalStudentId').value;
                    const foodId = document.getElementById('modalFoodName').value;
                    const quantity = document.getElementById('modalQuantity').value;
                    const status = document.getElementById('modalStatus').value;

                    // AJAX call to update order would go here
                    alert(`Order #${orderId} would be updated in a real implementation`);

                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('editOrderModal')).hide();
                });
            });
        </script>
</body>

</html>