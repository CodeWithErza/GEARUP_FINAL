<x-dashboard-layout :title="'Order History'" :icon="'fa-solid fa-receipt'">
    <div class="container-fluid">
        <!-- Orders Section (Two-column layout) -->
        <div class="row">
            <!-- Order History Table (Left Column) -->
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order History</h5>
                        
                        <div class="mt-3">
                            <!-- Moved Filters -->
                            <form class="row g-2 mb-3" id="report-filter-form" method="GET">
                                <div class="col-md-4">
                                    <select class="form-select form-select-sm" id="date-range" name="date_range">
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
                                    <select class="form-select form-select-sm" id="order-status" name="order_status">
                                        <option value="all" selected>All Statuses</option>
                                        <option value="completed">Completed</option>
                                        <option value="updated">Updated</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-sm btn-primary">Apply</button>
                                        <a href="{{ route('orders.history.export', ['format' => 'excel']) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-file-excel me-1"></i> Excel
                                        </a>
                                        <a href="{{ route('orders.history.export', ['format' => 'pdf']) }}" class="btn btn-sm btn-danger">
                                            <i class="fas fa-file-pdf me-1"></i> PDF
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="input-group input-group-sm" style="max-width: 300px;">
                                <input type="search" class="form-control" id="order-search" name="search" placeholder="Search Orders" value="{{ request('search') }}">
                                <button class="btn btn-outline-primary" type="button"><i class="fas fa-search"></i></button>
                            </div>
                            
                            <div class="d-flex align-items-center">
                                <span class="me-2 text-white" style="white-space: nowrap;">Sort by:</span>
                                <select class="form-select form-select-sm" id="sort-by" name="sort_by">
                                    <option value="order_number" selected>Order #</option>
                                    <option value="date_newest">Date (newest)</option>
                                    <option value="date_oldest">Date (oldest)</option>
                                    <option value="amount_high">Amount (high-low)</option>
                                    <option value="amount_low">Amount (low-high)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="card-body order-history-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="orders-table">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="orders-table-body">
                                    <!-- Orders will be loaded dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer" id="orders-pagination">
                        <!-- Pagination will be loaded dynamically -->
                    </div>
                </div>
            </div>

            <!-- Order Details (Right Column) -->
            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Details</h5>
                    </div>
                    <div class="card-body order-details-body">
                        <div id="order-details-content">
                            <div class="text-center py-5 order-details-placeholder">
                                <i class="fas fa-receipt fa-3x mb-3 text-secondary"></i>
                                <p class="text-secondary">Select an order from the left to view details</p>
                            </div>
                            
                            <div id="order-details-info" class="d-none">
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="fw-bold mb-0 fs-6">Order #<span id="detail-order-id">ORD-2025-011</span></h6>
                                        <span id="detail-status" class="badge text-bg-success">Completed</span>
                                    </div>
                                    <p class="mb-1 text-muted small">Date: <span id="detail-date">Apr 17, 2025</span></p>
                                </div>
                                
                                <div class="mb-3">
                                    <div class="table-responsive">
                                        <table class="table table-sm small">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Product</th>
                                                    <th class="text-end">Price</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-end">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody id="ordered-items">
                                                <tr>
                                                    <td>Premium Engine Oil</td>
                                                    <td class="text-end">₱1,500.00</td>
                                                    <td class="text-center">1</td>
                                                    <td class="text-end">₱1,500.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Spark Plug</td>
                                                    <td class="text-end">₱350.00</td>
                                                    <td class="text-center">1</td>
                                                    <td class="text-end">₱350.00</td>
                                                </tr>
                                            </tbody>
                                            <tfoot class="table-light">
                                                <tr>
                                                    <td colspan="3" class="text-end">Subtotal:</td>
                                                    <td class="text-end fw-bold" id="subtotal-amount">₱1,850.00</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-end">Tax (12%):</td>
                                                    <td class="text-end" id="detail-vat">₱222.00</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" class="text-end fw-bold">Total:</td>
                                                    <td class="text-end fw-bold" id="detail-total">₱2,072.00</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="row mb-2 g-2">
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-header py-1 bg-light">
                                                <h6 class="mb-0 fw-bold small">Customer Info</h6>
                                            </div>
                                            <div class="card-body p-2 small">
                                                <p class="mb-1"><small><strong>Name:</strong> <span id="detail-customer-name">Era Garupa Dumangcas</span></small></p>
                                                <p class="mb-1"><small><strong>Phone:</strong> <span id="detail-customer-phone">09511459613</span></small></p>
                                                <p class="mb-0"><small><strong>Email:</strong> <span id="detail-customer-email">eradumangcas7@gmail.com</span></small></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card h-100">
                                            <div class="card-header py-1 bg-light">
                                                <h6 class="mb-0 fw-bold small">Payment Info</h6>
                                            </div>
                                            <div class="card-body p-2 small">
                                                <p class="mb-1"><small><strong>Method:</strong> <span id="detail-payment-method">Cash</span></small></p>
                                                <p class="mb-1"><small><strong>Received:</strong> <span id="amount-received">₱20,000.00</span></small></p>
                                                <p class="mb-0"><small><strong>Change:</strong> <span id="change-amount">₱17,928.00</span></small></p>
                                                <p class="mb-0 update-timestamp d-none mt-2"><small class="text-muted"><i class="fas fa-clock me-1"></i> Last Updated: <span id="detail-update-time">Apr 18, 2025 09:45 AM</span></small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-grid gap-2">
                                    <button class="btn btn-sm btn-primary" id="update-order-btn">
                                        <i class="fas fa-edit me-1"></i> Update Order
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" id="cancel-order-btn">
                                        <i class="fas fa-times-circle me-1"></i> Cancel Order
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary" id="print-order-btn">
                                        <i class="fas fa-print me-1"></i> Print
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Confirmation Modal -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1" aria-labelledby="cancelOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="cancelOrderModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirm Order Cancellation
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to cancel order <strong id="cancel-order-number">ORD-0000</strong>?</p>
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>What happens when you cancel an order:</strong>
                        <ul class="mb-0 mt-1">
                            <li>The order status will be changed to "cancelled"</li>
                            <li>All product quantities in this order will be returned to inventory</li>
                            <li>The order cannot be modified after cancellation</li>
                        </ul>
                    </div>
                    <p class="text-danger"><small><strong>Note:</strong> This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, Keep Order</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelOrderBtn">Yes, Cancel Order</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification Container -->
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
        <div id="orderToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto" id="orderToastTitle">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="orderToastMessage"></div>
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
    <style>
        .order-history-body {
            flex: 1;
            padding: 0;
            overflow: hidden;
            min-height: 0;
        }
        .order-row {
            cursor: pointer;
        }
        .order-row:hover {
            background-color: rgba(255, 255, 255, 0.05);
        }
        .order-row.selected {
            background-color: rgba(255, 255, 255, 0.08);
        }
        
        /* Pagination styling - copied from products management */
        .pagination {
            margin: 0;
        }
        
        #orders-pagination {
            background-color: #2a2a2a;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            min-height: 60px; /* Ensure consistent height with or without pagination */
        }
        
        #orders-pagination .pagination {
            justify-content: flex-end;
            margin: 0;
        }
        
        .card-header, .card-footer {
            color: #fff;
        }
        
        .card-header {
            background-color: #2a2a2a !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Make pagination elements stand out better against dark background */
        .pagination .page-link {
            background-color: #333;
            border-color: #444;
            color: #fff;
        }
        
        .pagination .page-item.active .page-link {
            background-color: var(--accent, #FFE45C);
            border-color: var(--accent, #FFE45C);
            color: #333;
        }
        
        .pagination .page-item.disabled .page-link {
            background-color: #222;
            border-color: #444;
            color: #777;
        }

        .table-responsive {
            height: 100%;
            max-height: none;
        }

        /* Keep header visible */
        thead th {
            position: sticky;
            top: 0;
            background-color: #2a2a2a;
            z-index: 1;
            color: #fff;
        }

        /* Custom scrollbar styles */
        .table-responsive::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #333;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #666;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #888;
        }

        /* New styles for full-height layout */
        .container-fluid {
            min-height: calc(100vh - 100px);
            display: flex;
            flex-direction: column;
            padding-bottom: 1.5rem;
        }

        .row:not(.g-2):not(.mb-3) {
            flex: 1;
            min-height: 0; /* Important for Firefox */
            margin-bottom: 1rem;
        }

        .card.h-100 {
            display: flex;
            flex-direction: column;
        }

        .card-body {
            flex: 1;
            padding: 0;
            overflow: hidden;
            min-height: 0;
        }

        .order-details-body {
            flex: 1;
            overflow-y: auto;
            padding: 1rem;
        }
        
        .order-details-body .table-responsive {
            max-height: 250px;
            overflow-y: auto;
        }

        /* Table styling */
        .table {
            color: #e0e0e0;
            margin-bottom: 0;
        }
        
        .table > :not(caption) > * > * {
            background-color: transparent;
            border-bottom-color: rgba(255, 255, 255, 0.1);
        }
        
        .table > thead > tr > th {
            border-bottom-width: 1px;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        // Get CSRF token from meta tag
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        
        let currentPage = 1;
        let currentFilters = {
            date_range: 'this_month',
            order_status: 'all',
            search: '',
            sort_by: 'order_number'
        };

        // Function to show notifications
        function showOrderNotification(message, type = 'success') {
            const toast = document.getElementById('orderToast');
            const toastTitle = document.getElementById('orderToastTitle');
            const toastMessage = document.getElementById('orderToastMessage');
            
            // Set toast content
            toastMessage.textContent = message;
            
            // Set appropriate styling based on type
            if (type === 'success') {
                toast.classList.remove('bg-danger', 'text-white');
                toast.classList.add('bg-success', 'text-white');
                toastTitle.textContent = 'Success';
            } else if (type === 'error') {
                toast.classList.remove('bg-success', 'text-white');
                toast.classList.add('bg-danger', 'text-white');
                toastTitle.textContent = 'Error';
            }
            
            // Create Bootstrap toast instance and show it
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }

        // Function to handle order updates
        function updateOrder(orderId) {
            // Redirect to the order edit page
            window.location.href = `/orders/${orderId}/edit`;
        }

        // Function to handle order cancellation
        function cancelOrder(orderId) {
            // Get the order details to show in the confirmation modal
            fetch(`/orders/${orderId}/details`)
                .then(response => response.json())
                .then(data => {
                    const order = data.order;
                    
                    // Set the order number in the modal
                    document.getElementById('cancel-order-number').textContent = order.order_number;
                    
                    // Set up the confirmation button
                    const confirmButton = document.getElementById('confirmCancelOrderBtn');
                    confirmButton.dataset.orderId = orderId;
                    
                    // Show the modal
                    const modal = new bootstrap.Modal(document.getElementById('cancelOrderModal'));
                    modal.show();
                })
                .catch(error => {
                    console.error('Error fetching order details:', error);
                    showOrderNotification('Failed to load order details for cancellation', 'error');
                });
        }
        
        // Handle confirmed cancellation
        document.getElementById('confirmCancelOrderBtn').addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const modal = bootstrap.Modal.getInstance(document.getElementById('cancelOrderModal'));
            
            // Send cancellation request to the server
            fetch(`/orders/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Hide the modal
                modal.hide();
                
                if (data.success) {
                    showOrderNotification('Order cancelled successfully. Product quantities have been returned to inventory.');
                    // Reload the orders list
                    loadOrders(currentPage);
                    // Reload the order details
                    loadOrderDetails(orderId);
                } else {
                    showOrderNotification('Failed to cancel order: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error cancelling order:', error);
                modal.hide();
                showOrderNotification('An error occurred while cancelling the order', 'error');
            });
        });

        // Function to print order receipt
        function printOrderReceipt(orderId) {
            // Open the receipt in a new window/tab
            window.open(`/orders/${orderId}/receipt`, '_blank');
        }

        function loadOrderDetails(orderId) {
            fetch(`/orders/${orderId}/details`)
                .then(response => response.json())
                .then(data => {
                    const order = data.order;
                    
                    // Update order header information
                    document.getElementById('detail-order-id').textContent = order.order_number;
                    document.getElementById('detail-date').textContent = new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    document.getElementById('detail-status').textContent = order.status;
                    document.getElementById('detail-status').className = `badge text-bg-${order.status === 'completed' ? 'success' : order.status === 'cancelled' ? 'danger' : 'info'}`;

                    // Update ordered items
                    const orderedItems = document.getElementById('ordered-items');
                    orderedItems.innerHTML = order.items.map(item => `
                        <tr>
                            <td>${item.product.name}</td>
                            <td class="text-end">₱${parseFloat(item.price).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                            <td class="text-center">${item.quantity}</td>
                            <td class="text-end">₱${parseFloat(item.subtotal).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                        </tr>
                    `).join('');

                    // Update totals
                    document.getElementById('subtotal-amount').textContent = `₱${parseFloat(order.subtotal).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    document.getElementById('detail-vat').textContent = `₱${parseFloat(order.tax).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    document.getElementById('detail-total').textContent = `₱${parseFloat(order.total).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

                    // Update customer info
                    document.getElementById('detail-customer-name').textContent = order.customer.name;
                    document.getElementById('detail-customer-phone').textContent = order.customer.phone || 'N/A';
                    document.getElementById('detail-customer-email').textContent = order.customer.email || 'N/A';

                    // Update payment info
                    document.getElementById('detail-payment-method').textContent = order.payment_method;
                    document.getElementById('amount-received').textContent = `₱${parseFloat(order.amount_received).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
                    document.getElementById('change-amount').textContent = `₱${parseFloat(order.change_amount).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;

                    if (order.updated_at !== order.created_at) {
                        document.querySelector('.update-timestamp').classList.remove('d-none');
                        document.getElementById('detail-update-time').textContent = new Date(order.updated_at).toLocaleString('en-US', {
                            month: 'short',
                            day: 'numeric',
                            year: 'numeric',
                            hour: 'numeric',
                            minute: '2-digit',
                            hour12: true
                        });
                    } else {
                        document.querySelector('.update-timestamp').classList.add('d-none');
                    }

                    // Show order details panel
                    document.querySelector('.order-details-placeholder').classList.add('d-none');
                    document.getElementById('order-details-info').classList.remove('d-none');

                    // Update button states based on order status
                    const updateBtn = document.getElementById('update-order-btn');
                    const cancelBtn = document.getElementById('cancel-order-btn');
                    const printBtn = document.getElementById('print-order-btn');
                    
                    if (order.status === 'cancelled') {
                        updateBtn.disabled = true;
                        cancelBtn.disabled = true;
                    } else {
                        updateBtn.disabled = false;
                        cancelBtn.disabled = false;
                    }

                    // Update button event listeners
                    updateBtn.onclick = function() {
                        updateOrder(order.id);
                    };
                    
                    cancelBtn.onclick = function() {
                        cancelOrder(order.id);
                    };
                    
                    printBtn.onclick = function() {
                        printOrderReceipt(order.id);
                    };
                })
                .catch(error => {
                    console.error('Error fetching order details:', error);
                    alert('Failed to load order details');
                });
        }

        function loadOrders(page = 1) {
            const params = new URLSearchParams({
                ...currentFilters,
                page: page
            });

            fetch(`{{ route('orders.history.data') }}?${params}`)
                .then(response => response.json())
                .then(data => {
                    // Update orders table
                    const tbody = document.getElementById('orders-table-body');
                    tbody.innerHTML = data.data.map(order => `
                        <tr class="order-row" 
                            data-order-id="${order.id}">
                            <td>${order.order_number}</td>
                            <td>${order.customer.name}</td>
                            <td>${new Date(order.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</td>
                            <td>₱${parseFloat(order.total).toLocaleString('en-PH', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
                            <td>
                                <span class="badge text-bg-${order.status === 'completed' ? 'success' : order.status === 'cancelled' ? 'danger' : 'info'}">
                                    ${order.status}
                                </span>
                            </td>
                        </tr>
                    `).join('');

                    // Update pagination
                    const pagination = document.getElementById('orders-pagination');
                    
                    // Create a div for the product count display
                    let paginationHtml = `
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="orders-count">Showing ${data.from || 0} to ${data.to || 0} of ${data.total || 0} orders</span>
                            <nav aria-label="Page navigation">
                                <ul class="pagination">
                                    <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                                        <a class="page-link" href="#" onclick="event.preventDefault(); loadOrders(${data.current_page - 1})">Previous</a>
                                    </li>`;
                    
                    // Generate the page numbers
                    for (let i = 1; i <= data.last_page; i++) {
                        paginationHtml += `
                            <li class="page-item ${i === data.current_page ? 'active' : ''}">
                                <a class="page-link" href="#" onclick="event.preventDefault(); loadOrders(${i})">${i}</a>
                            </li>`;
                    }
                    
                    paginationHtml += `
                                    <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                                        <a class="page-link" href="#" onclick="event.preventDefault(); loadOrders(${data.current_page + 1})">Next</a>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    `;
                    
                    pagination.innerHTML = paginationHtml;

                    // Add click handlers for order rows
                    document.querySelectorAll('.order-row').forEach(row => {
                        row.addEventListener('click', function() {
                            // Remove selected class from all rows
                            document.querySelectorAll('.order-row').forEach(r => r.classList.remove('selected'));
                            // Add selected class to clicked row
                            this.classList.add('selected');
                            
                            // Load order details
                            loadOrderDetails(this.dataset.orderId);
                        });
                    });
                })
                .catch(error => console.error('Error fetching orders:', error));
        }

        // Load orders on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check for URL parameters and set initial filters
            const urlParams = new URLSearchParams(window.location.search);
            
            // Set date range from URL parameter if present
            if (urlParams.has('date_range')) {
                const dateRange = urlParams.get('date_range');
                const dateRangeSelect = document.getElementById('date-range');
                if (dateRangeSelect && dateRangeSelect.querySelector(`option[value="${dateRange}"]`)) {
                    dateRangeSelect.value = dateRange;
                    currentFilters.date_range = dateRange;
                }
            }
            
            // Set order status from URL parameter if present
            if (urlParams.has('order_status')) {
                const orderStatus = urlParams.get('order_status');
                const statusSelect = document.getElementById('order-status');
                if (statusSelect && statusSelect.querySelector(`option[value="${orderStatus}"]`)) {
                    statusSelect.value = orderStatus;
                    currentFilters.order_status = orderStatus;
                }
            }
            
            // Set search from URL parameter if present
            if (urlParams.has('search')) {
                const search = urlParams.get('search');
                const searchInput = document.getElementById('order-search');
                if (searchInput) {
                    searchInput.value = search;
                    currentFilters.search = search;
                }
            }
            
            // Set sort from URL parameter if present
            if (urlParams.has('sort_by')) {
                const sortBy = urlParams.get('sort_by');
                const sortSelect = document.getElementById('sort-by');
                if (sortSelect && sortSelect.querySelector(`option[value="${sortBy}"]`)) {
                    sortSelect.value = sortBy;
                    currentFilters.sort_by = sortBy;
                }
            }
            
            // Load orders with the filters (either defaults or from URL)
            loadOrders();

            // Check if specific order should be displayed
            if (urlParams.has('order_id')) {
                const orderId = urlParams.get('order_id');
                // Load the order details after a short delay to ensure orders are loaded
                setTimeout(() => {
                    loadOrderDetails(orderId);
                    
                    // Find and highlight the order row
                    const orderRow = document.querySelector(`tr[data-order-id="${orderId}"]`);
                    if (orderRow) {
                        orderRow.classList.add('selected');
                        orderRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }, 500);
            }

            // Add event listeners for filters
            document.getElementById('date-range').addEventListener('change', function() {
                currentFilters.date_range = this.value;
                loadOrders(1);
            });

            document.getElementById('order-status').addEventListener('change', function() {
                currentFilters.order_status = this.value;
                loadOrders(1);
            });

            document.getElementById('order-search').addEventListener('input', function() {
                currentFilters.search = this.value;
                loadOrders(1);
            });
            
            // Add event listener for sort dropdown
            document.getElementById('sort-by').addEventListener('change', function() {
                currentFilters.sort_by = this.value;
                loadOrders(1);
            });
        });
    </script>
    @endpush
</x-dashboard-layout> 