<x-dashboard-layout :title="'Sales Reports'" :icon="'fa-solid fa-chart-line'">
    <div class="container-fluid">
        <!-- Filters and Export Options -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3" method="GET">
                            <div class="col-md-4">
                                <label for="date-range" class="form-label">Date Range</label>
                                <select class="form-select" id="date-range" name="date_range">
                                    <option value="today">Today</option>
                                    <option value="yesterday">Yesterday</option>
                                    <option value="this_week">This Week</option>
                                    <option value="last_week">Last Week</option>
                                    <option value="this_month" selected>This Month</option>
                                    <option value="last_month">Last Month</option>
                                    <option value="this_year">This Year</option>
                                    <option value="custom">Custom Range</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="product-category" class="form-label">Product Category</label>
                                <select class="form-select" id="product-category" name="product_category">
                                    <option value="all" selected>All Categories</option>
                                    <option value="interior">Interior</option>
                                    <option value="exterior">Exterior</option>
                                    <option value="engine">Engine</option>
                                    <option value="under_chassis">Under Chassis</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-accent w-100">Apply Filters</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <label class="form-label">Export Options</label>
                        <div class="d-flex gap-2">
                            <a href="#" id="export-pdf" class="btn btn-danger flex-grow-1">
                                <i class="fas fa-file-pdf me-1"></i> PDF
                            </a>
                            <a href="#" id="export-excel" class="btn btn-success flex-grow-1">
                                <i class="fas fa-file-excel me-1"></i> Excel
                            </a>
                            <a href="#" onclick="window.print();" class="btn btn-secondary flex-grow-1">
                                <i class="fas fa-print me-1"></i> Print
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card text-bg-primary">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Sales</h5>
                        <h2 class="card-text fw-bold text-white" id="total-sales-card">₱0.00</h2>
                        <p class="card-text text-white" id="total-sales-change">
                            <i class="fas fa-arrow-up"></i> 0% from last period
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-success">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Orders</h5>
                        <h2 class="card-text fw-bold text-white" id="total-orders-card">0</h2>
                        <p class="card-text text-white" id="total-orders-change">
                            <i class="fas fa-arrow-up"></i> 0% from last period
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-warning">
                    <div class="card-body">
                        <h5 class="card-title text-white">Average Order Value</h5>
                        <h2 class="card-text fw-bold text-white" id="avg-order-value-card">₱0.00</h2>
                        <p class="card-text text-white" id="avg-order-value-change">
                            <i class="fas fa-arrow-up"></i> 0% from last period
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-bg-info">
                    <div class="card-body">
                        <h5 class="card-title text-white">Total Products Sold</h5>
                        <h2 class="card-text fw-bold text-white" id="total-products-card">0</h2>
                        <p class="card-text text-white" id="total-products-change">
                            <i class="fas fa-arrow-up"></i> 0% from last period
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Data Tables Section -->
        <div class="row mb-4">
            <!-- Total Sales Table -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Total Sales by Category</h5>
                        <div class="dropdown">
                            <button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" style="background-color: #FFE45C; color: #212529; font-weight: 500;">
                                This Month
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark">
                                <li><a class="dropdown-item text-white" href="#" data-range="today">Today</a></li>
                                <li><a class="dropdown-item text-white" href="#" data-range="this_week">This Week</a></li>
                                <li><a class="dropdown-item text-white" href="#" data-range="this_month">This Month</a></li>
                                <li><a class="dropdown-item text-white" href="#" data-range="this_year">This Year</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="categorySalesTable">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th class="text-end">Orders</th>
                                        <th class="text-end">Quantity</th>
                                        <th class="text-end">Total Sales</th>
                                        <th class="text-end">% of Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="5" class="text-center">Loading data...</td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td class="text-end"><strong id="totalOrders">0</strong></td>
                                        <td class="text-end"><strong id="totalQuantity">0</strong></td>
                                        <td class="text-end"><strong id="totalSales">₱0.00</strong></td>
                                        <td class="text-end"><strong>100%</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Sales Table -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Recent Sales</h5>
                        <div>
                            <button class="btn btn-sm btn-outline-secondary me-2" onclick="loadRecentSales()">
                                <i class="fas fa-sync-alt"></i> Refresh
                            </button>
                            <a href="{{ route('orders.history') }}" class="btn btn-sm btn-accent">View All</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover table-sm">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th class="text-end">Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="recent-sales-body">
                                    <tr>
                                        <td colspan="5" class="text-center">Loading recent sales...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row mb-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Sales Trend</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="sales-trend-chart" height="300"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-white">
                        <h5 class="card-title mb-0">Sales by Category</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="category-pie-chart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales Table -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Sales Details</h5>
                        <div class="d-flex gap-2">
                            <select class="form-select form-select-sm" id="sales-date-range" style="background-color: #FFE45C; color: #212529; border-color: #FFE45C; font-weight: 500; width: auto;">
                                <option value="today">Today</option>
                                <option value="yesterday">Yesterday</option>
                                <option value="this_week">This Week</option>
                                <option value="last_week">Last Week</option>
                                <option value="this_month" selected>This Month</option>
                                <option value="last_month">Last Month</option>
                                <option value="this_year">This Year</option>
                            </select>
                            <form method="GET" class="mb-0 d-flex gap-2">
                                <input type="search" class="form-control form-control-sm" id="sales-search" name="search" placeholder="Search products or categories...">
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="sales-details-table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Category</th>
                                        <th class="text-end">Qty Sold</th>
                                        <th class="text-end">Unit Price</th>
                                        <th class="text-end">Total Sales</th>
                                        <th>Last Sale</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data will be loaded dynamically -->
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td class="text-end"><strong id="totalQtySold">0</strong></td>
                                        <td></td>
                                        <td class="text-end"><strong id="totalSalesAmount">₱0.00</strong></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <nav>
                            <ul class="pagination justify-content-center" id="sales-details-pagination">
                                <!-- Pagination will be dynamically generated -->
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <!-- Print-specific styles -->
    <style media="print">
        .nav-tabs, .card-header, .pagination, .btn, .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .card-body {
            padding: 0 !important;
        }
        @page {
            size: landscape;
            margin: 1cm;
        }
        body {
            font-size: 12pt;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 5px;
        }
    </style>
    <!-- Simplified navigation styles -->
    <style>
        .nav-underline {
            border-bottom: 1px solid #dee2e6;
            gap: 2rem;
        }
        .nav-underline .nav-link {
            color: #6c757d;
            border: none;
            padding: 0.5rem 0;
            position: relative;
            margin-right: 1rem;
            background: none !important;
        }
        .nav-underline .nav-link:hover {
            color: #000;
        }
        .nav-underline .nav-link.active {
            color: #000;
            font-weight: 500;
        }
        .nav-underline .nav-link::after {
            content: '';
            position: absolute;
            bottom: -1px;
            left: 0;
            width: 100%;
            height: 2px;
            background-color: #000;
            transform: scaleX(0);
            transition: transform 0.2s ease;
        }
        .nav-underline .nav-link:hover::after {
            transform: scaleX(0.5);
        }
        .nav-underline .nav-link.active::after {
            transform: scaleX(1);
        }
        /* Remove focus outline and background */
        .nav-underline .nav-link:focus {
            outline: none;
            box-shadow: none;
        }
        .nav-underline .nav-link:focus-visible {
            outline: none;
            box-shadow: none;
        }
    </style>
    @endpush

    @push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.3.0/dist/chart.umd.min.js"></script>
    
    <!-- Inline Chart.js initialization -->
    <script>
        // Declare chart variables globally
        let trendChart = null;
        let categoryPieChart = null;

        // Function to format currency
        function formatCurrency(amount) {
            return new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP'
            }).format(amount);
        }

        // Function to format date
        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-PH', {
                year: 'numeric',
                month: 'short',
                day: '2-digit'
            });
        }

        // Function to load sales details
        function loadSalesDetails(page = 1) {
                const dateRange = document.getElementById('sales-date-range').value;
                const search = document.getElementById('sales-search').value;
                
                const tbody = document.querySelector('#sales-details-table tbody');
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center">
                            <div class="spinner-border spinner-border-sm text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Loading sales details...
                        </td>
                    </tr>
                `;

                fetch(`/reports/sales-details?date_range=${dateRange}&search=${search}&page=${page}`)
                    .then(response => response.json())
                    .then(data => {
                        tbody.innerHTML = '';
                        
                        if (data.sales_details.data.length === 0) {
                            tbody.innerHTML = `
                                <tr>
                                    <td colspan="6" class="text-center text-muted">
                                        <i class="fas fa-inbox me-2"></i>
                                        No sales data found
                                    </td>
                                </tr>
                            `;
                            return;
                        }

                        data.sales_details.data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.product_name}</td>
                                <td>${item.category_name}</td>
                                <td class="text-end">${item.total_quantity}</td>
                                <td class="text-end">${formatCurrency(item.unit_price)}</td>
                                <td class="text-end">${formatCurrency(item.total_sales)}</td>
                                <td>${formatDate(item.last_sale_date)}</td>
                            `;
                            tbody.appendChild(row);
                        });

                        // Calculate and display totals
                        const totalQtySold = data.sales_details.data.reduce((sum, item) => sum + item.total_quantity, 0);
                        const totalSalesAmount = data.sales_details.data.reduce((sum, item) => sum + item.total_sales, 0);
                        
                        document.getElementById('totalQtySold').textContent = totalQtySold.toLocaleString();
                        document.getElementById('totalSalesAmount').textContent = formatCurrency(totalSalesAmount);

                        // Update pagination
                        const pagination = document.getElementById('sales-details-pagination');
                        let paginationHtml = '';
                        
                        // Previous page link
                        paginationHtml += `
                            <li class="page-item ${data.sales_details.current_page === 1 ? 'disabled' : ''}">
                                <a class="page-link" href="#" data-page="${data.sales_details.current_page - 1}">Previous</a>
                            </li>
                        `;

                        // Page numbers
                        for (let i = 1; i <= data.sales_details.last_page; i++) {
                            paginationHtml += `
                                <li class="page-item ${i === data.sales_details.current_page ? 'active' : ''}">
                                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                                </li>
                            `;
                        }

                        // Next page link
                        paginationHtml += `
                            <li class="page-item ${data.sales_details.current_page === data.sales_details.last_page ? 'disabled' : ''}">
                                <a class="page-link" href="#" data-page="${data.sales_details.current_page + 1}">Next</a>
                            </li>
                        `;

                        pagination.innerHTML = paginationHtml;

                        // Add click event listeners to pagination links
                        pagination.querySelectorAll('.page-link').forEach(link => {
                            link.addEventListener('click', function(e) {
                                e.preventDefault();
                                loadSalesDetails(this.dataset.page);
                            });
                        });
                    })
                    .catch(error => {
                        console.error('Error loading sales details:', error);
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="6" class="text-center text-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    Error loading sales details
                                </td>
                            </tr>
                        `;
                    });
            }

            // Event listeners for filters
            document.getElementById('sales-date-range').addEventListener('change', () => loadSalesDetails(1));
            
            let searchTimeout;
            document.getElementById('sales-search').addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => loadSalesDetails(1), 300);
            });

            // Load initial sales details
            loadSalesDetails();

        // Function to show order items in a modal
        function showOrderItems(items) {
            const modalHtml = `
                <div class="modal fade" id="orderItemsModal" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Order Items</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th class="text-end">Qty</th>
                                                <th class="text-end">Price</th>
                                                <th class="text-end">Subtotal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${items.map(item => `
                                                <tr>
                                                    <td>${item.product_name}</td>
                                                    <td class="text-end">${item.quantity}</td>
                                                    <td class="text-end">₱${parseFloat(item.price).toLocaleString('en-PH', {minimumFractionDigits: 2})}</td>
                                                    <td class="text-end">₱${parseFloat(item.subtotal).toLocaleString('en-PH', {minimumFractionDigits: 2})}</td>
                                                </tr>
                                            `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;

            // Remove existing modal if any
            const existingModal = document.getElementById('orderItemsModal');
            if (existingModal) {
                existingModal.remove();
            }

            // Add new modal to the document
            document.body.insertAdjacentHTML('beforeend', modalHtml);

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('orderItemsModal'));
            modal.show();
        }

        // Function to load recent sales
        function loadRecentSales() {
            const tbody = document.getElementById('recent-sales-body');
            if (!tbody) {
                console.error('Could not find recent-sales-body element');
                return;
            }

            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Loading recent sales...</td></tr>';

            fetch('{{ route("reports.recent-sales") }}')
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to load recent sales');
                    }

                    if (!data.recent_sales || !Array.isArray(data.recent_sales)) {
                        throw new Error('Invalid data format received from server');
                    }

                    if (data.recent_sales.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">No recent sales found</td></tr>';
                        return;
                    }

                    tbody.innerHTML = data.recent_sales.map(sale => {
                        // Format date to MM/DD/YYYY
                        const dateParts = sale.date.split('-');
                        const formattedDate = dateParts.length === 3 ? 
                            `${dateParts[1]}/${dateParts[2]}/${dateParts[0]}` : sale.date;
                        
                        return `
                            <tr class="sale-row" style="cursor:pointer" onclick='showOrderItems(${JSON.stringify(sale.items)})'>
                                <td class="small">${sale.order_id}</td>
                                <td class="small">${sale.customer_name}</td>
                                <td class="small">${formattedDate}</td>
                                <td class="text-end small">₱${parseFloat(sale.total_amount).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                                <td>
                                    <span class="badge text-bg-${sale.status === 'completed' ? 'success' : sale.status === 'cancelled' ? 'danger' : 'info'} small">
                                        ${sale.status.charAt(0).toUpperCase() + sale.status.slice(1)}
                                    </span>
                                </td>
                            </tr>
                        `;
                    }).join('');
                })
                .catch(error => {
                    console.error('Error loading recent sales:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Error loading recent sales data: ${error.message}
                            </td>
                        </tr>`;
                });
        }

        // Function to load sales by category data
        function loadSalesByCategory(dateRange = 'this_month') {
            const tbody = document.querySelector('#categorySalesTable tbody');
            tbody.innerHTML = '<tr><td colspan="5" class="text-center">Loading data...</td></tr>';

            fetch(`{{ route('reports.sales-by-category') }}?date_range=${dateRange}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to load sales data');
                    }

                    tbody.innerHTML = '';
                    
                    if (data.sales_by_category.length === 0) {
                        tbody.innerHTML = '<tr><td colspan="5" class="text-center">No sales data found</td></tr>';
                        return;
                    }

                    // Update the table body with category data
                    data.sales_by_category.forEach(item => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${item.category_name}</td>
                            <td class="text-end">${item.total_orders.toLocaleString()}</td>
                            <td class="text-end">${item.total_quantity.toLocaleString()}</td>
                            <td class="text-end">₱${parseFloat(item.total_sales).toLocaleString('en-PH', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            })}</td>
                            <td class="text-end">${item.percentage}%</td>
                        `;
                        tbody.appendChild(row);
                    });

                    // Update summary totals
                    document.getElementById('totalOrders').textContent = data.summary.total_orders.toLocaleString();
                    document.getElementById('totalQuantity').textContent = data.summary.total_quantity.toLocaleString();
                    document.getElementById('totalSales').textContent = `₱${parseFloat(data.summary.total_sales).toLocaleString('en-PH', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    })}`;

                    // Update the dropdown button text
                    const dropdownButton = document.querySelector('.dropdown-toggle');
                    const selectedOption = document.querySelector(`.dropdown-item[data-range="${dateRange}"]`);
                    if (selectedOption) {
                        dropdownButton.textContent = selectedOption.textContent;
                    }
                })
                .catch(error => {
                    console.error('Error loading sales data:', error);
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center text-danger">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                Error loading sales data: ${error.message}
                            </td>
                        </tr>`;
                });
        }

        // Function to initialize the static sales trend chart
        function initSalesTrendChart() {
            const ctx = document.getElementById('sales-trend-chart').getContext('2d');
            
            // Static data - fixed labels and values that won't change
            const labels = ['Apr 1', 'Apr 2', 'Apr 3', 'Apr 4', 'Apr 5', 'Apr 6', 'Apr 7'];
            
            // Fixed sales data values
            const data = [15000, 18500, 14200, 21500, 25600, 19800, 22300];
            
            if (trendChart) {
                trendChart.destroy();
            }
            
            trendChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Daily Sales (₱)',
                        data: data,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.05)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#4e73df',
                        pointBorderColor: '#fff',
                        pointHoverRadius: 6,
                        pointHoverBackgroundColor: '#2e59d9',
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return formatCurrency(context.parsed.y);
                                }
                            }
                        },
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }

        // Function to load and initialize the category pie chart
        function loadCategoryPieChart(dateRange = 'this_month') {
            fetch(`{{ route('reports.sales-by-category') }}?date_range=${dateRange}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Failed to load sales data');
                    }
                    
                    if (data.sales_by_category.length === 0) {
                        document.getElementById('category-pie-chart').parentNode.innerHTML = 
                            '<div class="text-center p-4 text-muted">No data available</div>';
                        return;
                    }
                    
                    // Prepare data for pie chart
                    const labels = [];
                    const values = [];
                    const backgroundColors = [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', 
                        '#6f42c1', '#5a5c69', '#2e59d9', '#17a673', '#2c9faf'
                    ];
                    
                    data.sales_by_category.forEach((item, index) => {
                        labels.push(item.category_name);
                        values.push(item.total_sales);
                    });
                    
                    const ctx = document.getElementById('category-pie-chart').getContext('2d');
                    
                    if (categoryPieChart) {
                        categoryPieChart.destroy();
                    }
                    
                    categoryPieChart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                data: values,
                                backgroundColor: backgroundColors.slice(0, labels.length),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        boxWidth: 12,
                                        font: {
                                            size: 11
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = Math.round((value / total) * 100);
                                            return `${label}: ${formatCurrency(value)} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error loading category data for chart:', error);
                    document.getElementById('category-pie-chart').parentNode.innerHTML = 
                        `<div class="text-center p-4 text-danger">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            Error loading chart data: ${error.message}
                        </div>`;
                });
        }

        // Function to load and update sales summary cards
        function loadSalesSummary() {
            const dateRange = document.getElementById('date-range').value;
            const productCategory = document.getElementById('product-category').value;

            fetch(`/reports/sales-summary?date_range=${dateRange}&product_category=${productCategory}`)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) return;

                    document.getElementById('total-sales-card').textContent = `₱${parseFloat(data.total_sales).toLocaleString('en-PH', {minimumFractionDigits: 2})}`;
                    document.getElementById('total-orders-card').textContent = data.total_orders.toLocaleString();
                    document.getElementById('avg-order-value-card').textContent = `₱${parseFloat(data.average_order_value).toLocaleString('en-PH', {minimumFractionDigits: 2})}`;
                    document.getElementById('total-products-card').textContent = data.total_products_sold.toLocaleString();

                    // Optionally update percentage changes
                    document.getElementById('total-sales-change').innerHTML = `<i class="fas fa-arrow-up"></i> ${data.sales_percentage_change}% from last period`;
                    document.getElementById('total-orders-change').innerHTML = `<i class="fas fa-arrow-up"></i> ${data.orders_percentage_change}% from last period`;
                    document.getElementById('avg-order-value-change').innerHTML = `<i class="fas fa-arrow-up"></i> ${data.average_order_value_percentage_change}% from last period`;
                    document.getElementById('total-products-change').innerHTML = `<i class="fas fa-arrow-up"></i> ${data.products_sold_percentage_change}% from last period`;
                });
        }

        // Initialize everything when the DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded, initializing charts and data...');
            
            // Initialize charts
            initSalesTrendChart();
            loadCategoryPieChart();
            
            // Check for date_range parameter in URL and set dropdown value
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('date_range')) {
                const dateRange = urlParams.get('date_range');
                const dateRangeSelect = document.getElementById('date-range');
                if (dateRangeSelect && dateRangeSelect.querySelector(`option[value="${dateRange}"]`)) {
                    dateRangeSelect.value = dateRange;
                    
                    // Trigger the change event to load data with this filter
                    const changeEvent = new Event('change');
                    dateRangeSelect.dispatchEvent(changeEvent);
                    
                    // Also update sales details with this date range
                    const salesDateRange = document.getElementById('sales-date-range');
                    if (salesDateRange) {
                        salesDateRange.value = dateRange;
                        loadSalesDetails(1);
                    }
                    
                    // And update sales by category with this date range
                    loadSalesByCategory(dateRange);
                    // Update the category pie chart with this date range
                    loadCategoryPieChart(dateRange);
                }
            }
            
            // Load initial data
            loadRecentSales();
            
            // Set up refresh button event listener
            const refreshButton = document.querySelector('button[onclick="loadRecentSales()"]');
            if (refreshButton) {
                refreshButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    loadRecentSales();
                });
            }

            // Refresh data every 5 minutes
            setInterval(loadRecentSales, 300000);

            // Load initial data if no URL params
            if (!urlParams.has('date_range')) {
                loadSalesByCategory();
            }

            // Set up dropdown event listeners
            document.querySelectorAll('.dropdown-item').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const dateRange = this.dataset.range;
                    loadSalesByCategory(dateRange);
                    // Also update the category pie chart when changing date range
                    loadCategoryPieChart(dateRange);
                });
            });

            loadSalesSummary();
            document.getElementById('date-range').addEventListener('change', loadSalesSummary);
            document.getElementById('product-category').addEventListener('change', loadSalesSummary);
        });
    </script>

    <!-- PDF Export Handler -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('export-pdf').addEventListener('click', function(e) {
            e.preventDefault();
            // Get current filter values
            const dateRange = document.getElementById('date-range').value;
            const productCategory = document.getElementById('product-category').value;
            // Build the export URL (corrected route)
            const url = `/reports/export/pdf?date_range=${encodeURIComponent(dateRange)}&product_category=${encodeURIComponent(productCategory)}`;
            window.open(url, '_blank');
        });
    });
    </script>

    <!-- Excel Export Handler -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('export-excel').addEventListener('click', function(e) {
            e.preventDefault();
            // Get current filter values
            const dateRange = document.getElementById('date-range').value;
            const productCategory = document.getElementById('product-category').value;
            // Build the export URL (corrected route)
            const url = `/reports/export/excel?date_range=${encodeURIComponent(dateRange)}&product_category=${encodeURIComponent(productCategory)}`;
            window.open(url, '_blank');
        });
    });
    </script>
    @endpush
</x-dashboard-layout> 