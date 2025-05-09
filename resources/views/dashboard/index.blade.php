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
                <a href="{{ route('reports.sales') }}?date_range=today" class="text-decoration-none">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fa-solid fa-money-bill-wave fa-lg"></i>
                        </div>
                        <div class="stats-title">Sales Today</div>
                        <div class="stats-value" id="today-sales">₱0.00</div>
                        <div class="stats-change positive">
                            <i class="fas fa-chart-line me-1"></i>
                            Daily Revenue
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Orders Today -->
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('orders.history') }}?date_range=today" class="text-decoration-none">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fa-solid fa-cart-shopping fa-lg"></i>
                        </div>
                        <div class="stats-title">Orders Today</div>
                        <div class="stats-value" id="today-orders">0</div>
                        <div class="stats-change status-indicator positive">
                            <i class="fas fa-clipboard-list me-1"></i>
                            Orders Processed
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Customers -->
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('orders.index') }}#viewCustomers" class="text-decoration-none">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fa-solid fa-users fa-lg"></i>
                        </div>
                        <div class="stats-title">Total Customers</div>
                        <div class="stats-value">{{ $totalCustomers ?? 0 }}</div>
                        <div class="stats-change status-indicator neutral">
                            <i class="fas fa-user-plus me-1"></i>
                            Customer Base
                        </div>
                    </div>
                </a>
            </div>
            
            <!-- Products -->
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('products.index') }}" class="text-decoration-none">
                    <div class="stats-card">
                        <div class="stats-icon">
                            <i class="fa-solid fa-boxes-stacked fa-lg"></i>
                        </div>
                        <div class="stats-title">Total Products</div>
                        <div class="stats-value">{{ $totalProducts ?? 0 }}</div>
                        <div class="stats-change status-indicator {{ count($lowStockProducts ?? []) > 0 ? 'warning' : 'positive' }}">
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
                    <a href="{{ route('orders.index') }}" class="quick-action-card">
                        <i class="fa-solid fa-plus"></i>
                        <span>New Order</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('products.index') }}" class="quick-action-card">
                        <i class="fa-solid fa-box"></i>
                        <span>Add Product</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="#" class="quick-action-card">
                        <i class="fa-solid fa-truck"></i>
                        <span>Track Orders</span>
                    </a>
                </div>
                <div class="col-sm-6 col-md-3">
                    <a href="{{ route('reports.sales') }}" class="quick-action-card">
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
                <div class="card h-100">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                        <h5 class="card-title mb-0">Recent Orders</h5>
                        <a href="{{ route('orders.history') }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-history me-1"></i> Order History
                        </a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 220px;">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th class="py-2">Order #</th>
                                        <th class="py-2">Date</th>
                                        <th class="py-2">Items</th>
                                        <th class="text-end py-2">Amount</th>
                                        <th class="py-2">Status</th>
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
                <div class="card h-100">
                    <div class="card-header bg-white py-2">
                        <h5 class="card-title mb-0">Top Selling Products</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive" style="max-height: 220px;">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th class="py-2">Product</th>
                                        <th class="text-end py-2">Sales</th>
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
        });
    </script>
</body>
</html>
