<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Dashboard Custom CSS -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
</head>
<body>
    <x-dashboard-layout title="Dashboard Overview" icon="fa-solid fa-gauge-high">
        <!-- Welcome Section -->
        <div class="welcome-section mb-4">
            <div class="row">
                <div class="col-12">
                    <h2 class="welcome-title mb-2">Welcome back, {{ Auth::user()->name }}! 👋</h2>
                    <p class="welcome-subtitle">Here's what's happening with your auto parts shop today.</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row g-4 mb-4">
            <!-- Sales Today -->
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('reports.sales') }}?date_range=today" class="text-decoration-none" data-bs-toggle="tooltip" title="View detailed sales report for today">
                    <div class="stats-card" style="background-color: #2a2a2a; border: 2px solid #FFE45C; border-left: 4px solid #FFE45C;">
                        <div class="stats-icon" style="background-color: rgba(255, 228, 92, 0.2);">
                            <i class="fa-solid fa-money-bill-wave fa-lg"></i>
                        </div>
                        <div class="stats-title" style="font-size: 1.2rem; color: #FFE45C; font-weight: 600;">Sales Today</div>
                        <div class="stats-value" id="today-sales">₱0.00</div>
                        <div class="stats-change positive text-secondary small" style="margin-bottom: 0;">
                            <i class="fas fa-chart-line me-1"></i>
                            Daily Revenue
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Orders Today -->
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('orders.history') }}?date_range=today" class="text-decoration-none" data-bs-toggle="tooltip" title="View orders processed today">
                    <div class="stats-card" style="background-color: #2a2a2a; border: 2px solid #FFE45C; border-left: 4px solid #FFE45C;">
                        <div class="stats-icon" style="background-color: rgba(255, 228, 92, 0.2);">
                            <i class="fa-solid fa-cart-shopping fa-lg"></i>
                        </div>
                        <div class="stats-title" style="font-size: 1.2rem; color: #FFE45C; font-weight: 600;">Orders Today</div>
                        <div class="stats-value" id="today-orders">0</div>
                        <div class="stats-change status-indicator positive text-secondary small" style="margin-bottom: 0;">
                            <i class="fas fa-clipboard-list me-1"></i>
                            Orders Processed
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Customers -->
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('orders.index') }}#viewCustomers" class="text-decoration-none" data-bs-toggle="tooltip" title="View and manage customer information">
                    <div class="stats-card" style="background-color: #2a2a2a; border: 2px solid #FFE45C; border-left: 4px solid #FFE45C;">
                        <div class="stats-icon" style="background-color: rgba(255, 228, 92, 0.2);">
                            <i class="fa-solid fa-users fa-lg"></i>
                        </div>
                        <div class="stats-title" style="font-size: 1.2rem; color: #FFE45C; font-weight: 600;">Total Customers</div>
                        <div class="stats-value">{{ $totalCustomers ?? 0 }}</div>
                        <div class="stats-change status-indicator neutral text-secondary small" style="margin-bottom: 0;">
                            <i class="fas fa-user-plus me-1"></i>
                            Customer Base
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Products -->
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('products.index') }}" class="text-decoration-none" data-bs-toggle="tooltip" title="Manage your product inventory">
                    <div class="stats-card" style="background-color: #2a2a2a; border: 2px solid #FFE45C; border-left: 4px solid #FFE45C;">
                        <div class="stats-icon" style="background-color: rgba(255, 228, 92, 0.2);">
                            <i class="fa-solid fa-boxes-stacked fa-lg"></i>
                        </div>
                        <div class="stats-title" style="font-size: 1.2rem; color: #FFE45C; font-weight: 600;">Total Products</div>
                        <div class="stats-value">{{ $totalProducts ?? 0 }}</div>
                        <div class="stats-change status-indicator {{ count($lowStockProducts ?? []) > 0 ? 'warning' : 'positive' }} text-secondary small" style="margin-bottom: 0;">
                            <i class="fas fa-box-open me-1"></i>
                            {{ count($lowStockProducts ?? []) }} Low Stock Items
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions mb-4">
            <div class="row g-3">
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('orders.index') }}" class="quick-action-card" style="background-color: #2a2a2a; border: 2px solid #FFE45C;" data-bs-toggle="tooltip" title="Create a new sales order">
                        <i class="fa-solid fa-plus"></i>
                        <span>New Order</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('products.index') }}" class="quick-action-card" style="background-color: #2a2a2a; border: 2px solid #FFE45C;" data-bs-toggle="tooltip" title="Add a new product to inventory">
                        <i class="fa-solid fa-box"></i>
                        <span>Add Product</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="#" class="quick-action-card" style="background-color: #2a2a2a; border: 2px solid #FFE45C;" data-bs-toggle="tooltip" title="Track order status and deliveries">
                        <i class="fa-solid fa-truck"></i>
                        <span>Track Orders</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('reports.sales') }}" class="quick-action-card" style="background-color: #2a2a2a; border: 2px solid #FFE45C;" data-bs-toggle="tooltip" title="View sales and inventory reports">
                        <i class="fa-solid fa-chart-simple"></i>
                        <span>View Reports</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row mb-4">
            <!-- Recent Orders Table -->
            <div class="col-md-8">
                <div class="card h-100" style="background-color: #2a2a2a; border: 1px solid #444;">
                    <div class="card-header d-flex justify-content-between align-items-center py-2" style="background-color: #2a2a2a; border-bottom: 1px solid #444;">
                        <h5 class="card-title mb-0" style="color: #ffffff; font-weight: 600;">
                            Recent Orders
                            <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Latest orders processed in the system"></i>
                        </h5>
                        <a href="{{ route('orders.history') }}" class="btn btn-sm" style="background-color: #444; color: #ffffff; font-weight: 500;" data-bs-toggle="tooltip" title="View complete order history">
                            <i class="fas fa-history me-1"></i> Order History
                        </a>
                    </div>
                    <div class="card-body p-0 text-white">
                        <div class="table-responsive" style="max-height: 220px;">
                            <table class="table table-hover mb-0 text-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 text-white">Order #</th>
                                        <th class="py-2 text-white">Date</th>
                                        <th class="py-2 text-white">Items</th>
                                        <th class="text-end py-2 text-white">Amount</th>
                                        <th class="py-2 text-white">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-orders-body">
                                    <!-- Orders will be loaded dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100" style="background-color: #2a2a2a; border: 1px solid #444;">
                    <div class="card-header py-2" style="background-color: #2a2a2a; border-bottom: 1px solid #444;">
                        <h5 class="card-title mb-0" style="color: #ffffff; font-weight: 600;">
                            Top Selling Products
                            <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Products with the highest sales volume"></i>
                        </h5>
                    </div>
                    <div class="card-body p-0 text-white">
                        <div class="table-responsive" style="max-height: 220px;">
                            <table class="table table-hover align-middle mb-0 text-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 text-white">Product</th>
                                        <th class="text-end py-2 text-white">Sales</th>
                                    </tr>
                                </thead>
                                <tbody id="top-products-body">
                                    <tr>
                                        <td colspan="2" class="text-center">Loading top products...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </x-dashboard-layout>

    <!-- Script for date and time -->
    <script>
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const timeOptions = { hour: '2-digit', minute: '2-digit' };
            
            document.getElementById('current-date').textContent = now.toLocaleDateString('en-US', dateOptions);
            document.getElementById('current-time').textContent = now.toLocaleTimeString('en-US', timeOptions);
        }
        
        // Update time immediately and then every second
        updateDateTime();
        setInterval(updateDateTime, 60000);

        // Fetch and update dashboard data
        function updateDashboardData() {
            fetch('{{ route("orders.dashboard.data") }}')
                .then(response => response.json())
                .then(data => {
                    // Update sales and orders count
                    document.getElementById('today-sales').textContent = `₱${data.today_sales}`;
                    document.getElementById('today-orders').textContent = data.today_orders;

                    // Update recent orders table
                    const tbody = document.getElementById('recent-orders-body');
                    tbody.innerHTML = data.recent_orders.map(order => `
                        <tr>
                            <td class="py-1">${order.order_number}</td>
                            <td class="py-1">${order.date}</td>
                            <td class="py-1">${order.items_count}</td>
                            <td class="text-end py-1">₱${order.total}</td>
                            <td class="py-1">
                                <span class="badge text-bg-${order.status === 'completed' ? 'success' : order.status === 'cancelled' ? 'danger' : 'info'}">
                                    ${order.status}
                                </span>
                            </td>
                        </tr>
                    `).join('');
                    
                    // Update top products table
                    updateTopProductsTable(data.top_products);
                })
                .catch(error => console.error('Error fetching dashboard data:', error));
        }
        
        // Function to update the top products table
        function updateTopProductsTable(topProducts) {
            const tbody = document.getElementById('top-products-body');
            
            if (!topProducts || topProducts.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="2" class="text-center py-3">
                            <div class="text-muted">
                                <i class="fas fa-shopping-cart fa-2x mb-2"></i>
                                <p>No sales data available</p>
                            </div>
                        </td>
                    </tr>
                `;
                return;
            }
            
            tbody.innerHTML = topProducts.map(product => `
                <tr>
                    <td class="py-1">
                        <div class="d-flex align-items-center">
                            <img src="${product.image}" alt="${product.name}" class="rounded me-2" width="40" height="40" style="object-fit: cover;">
                            <div class="product-name text-truncate" style="max-width: 150px;">${product.name}</div>
                        </div>
                    </td>
                    <td class="text-end py-1 fw-bold">₱${product.total_sales}</td>
                </tr>
            `).join('');
        }

        // Update dashboard data immediately and then every 5 minutes
        document.addEventListener('DOMContentLoaded', function() {
            updateDashboardData();
            setInterval(updateDashboardData, 300000);
            
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
        });
    </script>
</body>
</html>
