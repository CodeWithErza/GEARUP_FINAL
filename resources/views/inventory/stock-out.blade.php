<x-dashboard-layout :title="'Stock Out'" :icon="'fa-solid fa-arrow-down'">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('inventory') }}" class="text-decoration-none">
                        <i class="fa-solid fa-warehouse"></i> Inventory
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Stock Out</li>
            </ol>
        </nav>

        <div class="row">
            <!-- Left column - Stock Out Form -->
            <div class="col-lg-8">
                <!-- Stock Out Form -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-arrow-down me-2"></i>
                            Stock Out Details
                            <i class="fas fa-info-circle ms-2" data-bs-toggle="tooltip" title="Record products leaving inventory"></i>
                        </h5>
                    </div>
                    <div class="card-body">
                    <form id="stockOutForm" method="POST" action="{{ route('stockout.store') }}">
                            @csrf
                            <!-- Reference Number and Date -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Reference Number</label>
                                    <input type="text" class="form-control" id="referenceNumber" name="reference_number" value="{{ $nextRefNumber }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date</label>
                                    <input type="text" class="form-control datepicker" id="stockOutDate" name="date" value="{{ date('Y-m-d') }}" placeholder="Select date">
                                </div>
                            </div>

                            <!-- Product Search -->
                            <div class="mb-4">
                                <label class="form-label">Search Product</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="productSearch" placeholder="Search product...">
                                    <button class="btn btn-accent" type="button" id="openProductModal" data-bs-toggle="tooltip" title="Browse all available products">
                                        <i class="fas fa-search me-1"></i>
                                        Browse
                                    </button>
                                </div>
                                <ul class="list-group mt-1" id="searchResults" style="position: absolute; z-index: 999;"></ul>
                            </div>

                            <!-- Stock Out Items Table -->
                            <div class="table-responsive mb-4">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="sortable">Product <span class="sort-icon"></span></th>
                                            <th class="sortable">SKU <span class="sort-icon"></span></th>
                                            <th class="sortable">Current Stock <span class="sort-icon"></span></th>
                                            <th class="sortable">Quantity <span class="sort-icon"></span></th>
                                            <th class="sortable">Reason <span class="sort-icon"></span></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="stockOutItems">
                                        <tr id="noItemsMessage">
                                            <td colspan="6" class="text-center py-4">
                                                <div class="no-data-message">
                                                    <i class="fas fa-box-open fa-3x mb-3" style="color: var(--accent);"></i>
                                                    <h6 class="fw-normal">No items added</h6>
                                                    <p class="small mb-0">
                                                        Search for products to add
                                                    </p>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Notes (Collapsible) -->
                            <div class="mb-4">
                                <a class="d-flex align-items-center collapsed text-decoration-none" data-bs-toggle="collapse" href="#notesCollapse" role="button" aria-expanded="false" aria-controls="notesCollapse">
                                    <i class="fas fa-caret-right me-2 notes-caret"></i>
                                    <label class="form-label mb-0">Add Notes (Optional)</label>
                                </a>
                                <div class="collapse mt-2" id="notesCollapse">
                                    <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Add any additional notes..."></textarea>
                                </div>
                            </div>

                            <!-- Processed By -->
                            <div class="mb-4">
                                <label class="form-label">Processed By</label>
                                <input type="text" class="form-control" value="{{ Auth::user()->name }}" readonly>
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn btn-accent" id="processStockOut" data-bs-toggle="tooltip" title="Record this stock removal transaction">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Process Stock Out
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right column - Info cards -->
            <div class="col-lg-4 d-flex flex-column">
                <!-- Recent Transactions -->
                <div class="card shadow-sm mb-4 flex-grow-1">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>
                            Recent Transactions
                            <i class="fas fa-info-circle ms-2" data-bs-toggle="tooltip" title="Recently processed stock removals"></i>
                        </h5>
                    </div>
                    <div class="card-body p-0 bg-white" style="overflow-y: auto; height: 250px;">
                        <div class="list-group list-group-flush h-100 bg-white">
                            @forelse($recentTransactions ?? [] as $transaction)
                            <div class="list-group-item border-bottom">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1 text-dark">{{ $transaction->reference_number }}</h6>
                                    <small class="text-muted">{{ $transaction->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1 text-dark">Stock Out - {{ $transaction->items->count() }} item(s) removed</p>
                                <small class="text-secondary">Processed by {{ $transaction->user->name ?? 'Unknown' }}</small>
                            </div>
                            @empty
                            <div class="list-group-item border-bottom text-center py-3">
                                <p class="mb-0 text-muted">No recent transactions</p>
                            </div>
                            @endforelse
                            <div class="flex-grow-1 bg-white"></div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Guidelines -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-dark text-white">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            Transaction Guidelines
                            <i class="fas fa-info-circle ms-2" data-bs-toggle="tooltip" title="Important rules for stock removal"></i>
                        </h5>
                    </div>
                    <div class="card-body bg-white">
                        <ul class="mb-0 ps-3 text-dark">
                            <li class="mb-2">Always verify product details before processing</li>
                            <li class="mb-2">Ensure quantities are correct and available</li>
                            <li class="mb-2">Document all reasons for stock removals</li>
                            <li class="mb-2">Large adjustments require supervisor approval</li>
                            <li>Keep related documents for reference</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Selection Modal -->
    <div class="modal fade" id="productSelectionModal" tabindex="-1" aria-labelledby="productSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productSelectionModalLabel">
                        Select Products
                        <i class="fas fa-info-circle ms-2" data-bs-toggle="tooltip" title="Choose products to remove from inventory"></i>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" class="form-control" id="modalProductSearch" placeholder="Search products...">
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th class="sortable">PRODUCT <span class="sort-icon"></span></th>
                                    <th class="sortable">SKU <span class="sort-icon"></span></th>
                                    <th class="sortable">CATEGORY <span class="sort-icon"></span></th>
                                    <th class="sortable">CURRENT STOCK <span class="sort-icon"></span></th>
                                    <th>ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody id="productModalResults">
                                <!-- Products will be populated here via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stockOutForm = document.getElementById('stockOutForm');
            const productSearch = document.getElementById('productSearch');
            const searchResults = document.getElementById('searchResults');
            const stockOutItems = document.getElementById('stockOutItems');
            const noItemsMessage = document.getElementById('noItemsMessage');
            const openProductModal = document.getElementById('openProductModal');
            const modalProductSearch = document.getElementById('modalProductSearch');
            const productModalResults = document.getElementById('productModalResults');
            const productModal = new bootstrap.Modal(document.getElementById('productSelectionModal'));

            // Table sorting functionality
            document.querySelectorAll('th.sortable').forEach(headerCell => {
                headerCell.addEventListener('click', function() {
                    const table = this.closest('table');
                    const tbody = table.querySelector('tbody');
                    const rows = Array.from(tbody.querySelectorAll('tr:not(#noItemsMessage)'));
                    const headerIndex = Array.from(this.parentNode.children).indexOf(this);
                    
                    // Get current sort direction (default is ascending)
                    const currentDirection = this.classList.contains('asc') ? 'desc' : 'asc';
                    
                    // Reset all header classes
                    table.querySelectorAll('th.sortable').forEach(th => {
                        th.classList.remove('asc', 'desc');
                    });
                    
                    // Set new direction class
                    this.classList.add(currentDirection);
                    
                    // Sort rows
                    const sortedRows = rows.sort((a, b) => {
                        // Skip if no cells in row
                        if (!a.children[headerIndex] || !b.children[headerIndex]) return 0;
                        
                        let aValue = a.children[headerIndex].textContent.trim();
                        let bValue = b.children[headerIndex].textContent.trim();
                        
                        // Special handling for quantity inputs and current stock
                        if (headerIndex === 3) { // Quantity column
                            aValue = parseFloat(a.querySelector('.new-count')?.value || 0);
                            bValue = parseFloat(b.querySelector('.new-count')?.value || 0);
                        } else if (headerIndex === 2) { // Current Stock column - convert to number
                            aValue = parseFloat(aValue.replace(/[^\d.-]/g, '') || 0);
                            bValue = parseFloat(bValue.replace(/[^\d.-]/g, '') || 0);
                        }
                        
                        // If numeric values, compare as numbers
                        if (!isNaN(aValue) && !isNaN(bValue)) {
                            return currentDirection === 'asc' ? aValue - bValue : bValue - aValue;
                        }
                        
                        // Otherwise compare as strings
                        return currentDirection === 'asc' 
                            ? aValue.localeCompare(bValue) 
                            : bValue.localeCompare(aValue);
                    });
                    
                    // Remove all rows and append in sorted order
                    rows.forEach(row => row.remove());
                    
                    // Add back no items message if necessary
                    if (sortedRows.length === 0 && noItemsMessage) {
                        noItemsMessage.style.display = '';
                    } else if (noItemsMessage) {
                        noItemsMessage.style.display = 'none';
                    }
                    
                    sortedRows.forEach(row => tbody.appendChild(row));
                });
            });
            
            // Initialize flatpickr for date inputs
            flatpickr(".datepicker", {
                altInput: true,
                altFormat: "F j, Y",
                dateFormat: "Y-m-d",
                defaultDate: "{{ date('Y-m-d') }}",
                theme: "dark"
            });

            // Handle form submission
            stockOutForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = {
                    _token: document.querySelector('input[name="_token"]').value,
                    reference_number: document.getElementById('referenceNumber').value,
                    date: document.getElementById('stockOutDate').value,
                    notes: document.getElementById('notes').value,
                    items: []
                };

                // Gather items data
                const rows = stockOutItems.querySelectorAll('tr:not(#noItemsMessage)');
                if (rows.length === 0) {
                    alert('Please add at least one item to process.');
                    return;
                }

                rows.forEach(row => {
                    const productId = row.querySelector('input[name$="[product_id]"]').value;
                    const newCount = parseInt(row.querySelector('input[name$="[new_count]"]').value);
                    const reason = row.querySelector('select[name$="[reason]"]').value;

                    formData.items.push({
                        product_id: productId,
                        new_count: newCount,
                        reason: reason
                    });
                });

                try {
                    const response = await fetch(stockOutForm.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': formData._token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(formData)
                    });

                    // Check for error responses
                    if (!response.ok) {
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            // Handle JSON error response
                            const result = await response.json();
                            throw new Error(result.message || 'Error processing stock out');
                        } else {
                            // Handle non-JSON error response
                            const text = await response.text();
                            throw new Error('Server error occurred. Please try again.');
                        }
                    }

                    // Handle successful response
                    const result = await response.json();
                    alert(result.message || 'Stock out processed successfully');
                    window.location.href = '{{ route('inventory.stock-out') }}';
                    
                } catch (error) {
                    console.error('Error:', error);
                    alert(error.message || 'An error occurred while processing the stock out');
                }
            });

            // Open Product Modal
            openProductModal.addEventListener('click', function() {
                loadAllProducts();
                productModal.show();
            });

            // Load all products into modal
            async function loadAllProducts() {
                try {
                    const response = await fetch('/api/products');
                    const products = await response.json();
                    
                    renderProductsInModal(products);
                } catch (error) {
                    console.error('Error loading products:', error);
                }
            }

            // Modal search functionality
            let modalDebounceTimer;
            modalProductSearch.addEventListener('input', function() {
                clearTimeout(modalDebounceTimer);
                const query = this.value.trim();

                if (query.length < 2 && query.length > 0) {
                    return;
                }

                modalDebounceTimer = setTimeout(async () => {
                    try {
                        const url = query.length === 0 
                            ? '/api/products' 
                            : `/api/products/search?q=${encodeURIComponent(query)}`;
                            
                        const response = await fetch(url);
                        const products = await response.json();
                        
                        renderProductsInModal(products);
                    } catch (error) {
                        console.error('Error searching products:', error);
                    }
                }, 300);
            });

            // Render products in modal table
            function renderProductsInModal(products) {
                productModalResults.innerHTML = '';
                
                if (products.length === 0) {
                    productModalResults.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-3">No products found</td>
                        </tr>
                    `;
                    return;
                }
                
                products.forEach(product => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="product-icon me-2">
                                    <i class="fas fa-box"></i>
                                </div>
                                <span>${product.name}</span>
                            </div>
                        </td>
                        <td>${product.sku}</td>
                        <td>${product.category}</td>
                        <td>${product.stock}</td>
                        <td>
                            <button type="button" class="btn btn-sm btn-warning select-product">
                                Select
                            </button>
                        </td>
                    `;
                    
                    row.querySelector('.select-product').addEventListener('click', function() {
                        addStockOutItem(product);
                        productModal.hide();
                    });
                    
                    productModalResults.appendChild(row);
                });
            }

            // Inline product search functionality
            let debounceTimer;
            productSearch.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                const query = this.value.trim();

                if (query.length < 2) {
                    searchResults.innerHTML = '';
                    return;
                }

                debounceTimer = setTimeout(async () => {
                    try {
                        const response = await fetch(`/api/products/search?q=${encodeURIComponent(query)}`);
                        const products = await response.json();

                        searchResults.innerHTML = '';
                        if (products.length > 0) {
                            products.forEach(product => {
                                const item = document.createElement('li');
                                item.classList.add('list-group-item', 'list-group-item-action');
                                item.textContent = `${product.name} (${product.sku})`;
                                item.style.cursor = 'pointer';
                                item.onclick = () => {
                                    addStockOutItem(product);
                                    productSearch.value = '';
                                    searchResults.innerHTML = '';
                                };
                                searchResults.appendChild(item);
                            });
                        } else {
                            const item = document.createElement('li');
                            item.classList.add('list-group-item');
                            item.textContent = 'No results found';
                            searchResults.appendChild(item);
                        }
                    } catch (error) {
                        console.error('Error searching products:', error);
                    }
                }, 300);
            });

            // Function to add item to list
            let itemIndex = 0;
            function addStockOutItem(product) {
                noItemsMessage.style.display = 'none';

                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${product.name}</td>
                    <td>${product.sku}</td>
                    <td>${product.stock}</td>
                    <td>
                        <input type="hidden" name="items[${itemIndex}][product_id]" value="${product.id}">
                        <input type="number" name="items[${itemIndex}][new_count]" class="form-control form-control-sm new-count" 
                            value="1" 
                            min="1" max="${product.stock}">
                    </td>
                    <td>
                        <select name="items[${itemIndex}][reason]" class="form-select form-select-sm">
                            <option value="damaged">Damaged</option>
                            <option value="expired">Expired</option>
                            <option value="lost">Lost/Missing</option>
                            <option value="other">Other</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-item">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                `;

                // Add event listeners
                const quantityInput = row.querySelector('.new-count');

                quantityInput.addEventListener('input', function() {
                    const quantity = parseInt(this.value) || 0;
                    
                    // Ensure quantity does not exceed available stock
                    if (quantity > product.stock) {
                        this.value = product.stock;
                    }
                });

                row.querySelector('.remove-item').addEventListener('click', function() {
                    row.remove();
                    if (stockOutItems.querySelectorAll('tr:not(#noItemsMessage)').length === 0) {
                        noItemsMessage.style.display = '';
                    }
                });

                stockOutItems.appendChild(row);
                itemIndex++;
            }

            // Initialize all tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Reinitialize tooltips when modal is shown
            document.getElementById('productSelectionModal').addEventListener('shown.bs.modal', function () {
                var modalTooltipTriggerList = [].slice.call(document.querySelectorAll('#productSelectionModal [data-bs-toggle="tooltip"]'))
                var modalTooltipList = modalTooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            });
        });
    </script>
    @endpush

    @push('styles')
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
    <style>
        /* Custom styling for the flatpickr calendar */
        .flatpickr-calendar {
            background: #2a2a2a !important;
            color: #ffffff !important;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.4) !important;
            border: 1px solid #393939 !important;
        }
        .flatpickr-day {
            color: #e0e0e0 !important;
        }
        .flatpickr-day.selected {
            background: #FFE45C !important;
            border-color: #FFE45C !important;
            color: #111111 !important;
        }
        .flatpickr-day:hover {
            background: rgba(255, 228, 92, 0.2) !important;
        }
        .flatpickr-day.today {
            border-color: #FFE45C !important;
        }
        .flatpickr-months .flatpickr-month {
            color: #e0e0e0 !important;
        }
        .flatpickr-current-month .flatpickr-monthDropdown-months {
            color: #e0e0e0 !important;
        }
        .flatpickr-time input, .flatpickr-time .flatpickr-time-separator, .flatpickr-time .flatpickr-am-pm {
            color: #e0e0e0 !important;
        }
        
        /* Sortable Table Headers */
        th.sortable {
            cursor: pointer;
            position: relative;
            padding-right: 25px; /* Increased padding for sort icons */
            padding-top: 12px;   /* Increased top padding */
            padding-bottom: 12px; /* Increased bottom padding */
            height: 50px;       /* Set a fixed height for consistent header size */
            vertical-align: middle;
        }
        
        .sort-icon {
            font-size: 12px;    /* Increased font size for better visibility */
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            display: flex;
            flex-direction: column;
            line-height: 0.7;   /* Reduced line height to create proper spacing */
        }
        
        .sort-icon:before {
            content: "▲";       /* Up triangle */
            display: block;
            margin-bottom: 3px; /* Add space between triangles */
        }
        
        .sort-icon:after {
            content: "▼";       /* Down triangle */
            display: block;
        }
        
        th.sortable:hover .sort-icon {
            color: #FFE45C;
        }
        
        th.sortable.asc .sort-icon:before {
            color: #FFE45C;    /* Highlight up triangle when sorting ascending */
        }
        
        th.sortable.asc .sort-icon:after {
            color: #999;       /* Keep down triangle muted when sorting ascending */
        }
        
        th.sortable.desc .sort-icon:before {
            color: #999;       /* Keep up triangle muted when sorting descending */
        }
        
        th.sortable.desc .sort-icon:after {
            color: #FFE45C;    /* Highlight down triangle when sorting descending */
        }
        
        /* Product Selection Modal Styles */
        #productSelectionModal .modal-content {
            background-color: #f8f9fa;
            border: none;
            border-radius: 8px;
        }
        
        #productSelectionModal .modal-header {
            border-bottom: 1px solid #dee2e6;
            padding: 15px 20px;
        }
        
        #productSelectionModal .modal-title {
            font-weight: 600;
            color: #333;
        }
        
        #productSelectionModal .modal-body {
            padding: 20px;
        }
        
        #productSelectionModal table {
            border-collapse: separate;
            border-spacing: 0;
        }
        
        #productSelectionModal table thead {
            background-color: #343a40;
            color: white;
        }
        
        #productSelectionModal table th {
            font-weight: 600;
            padding: 12px 15px;
            border: none;
        }
        
        #productSelectionModal table tbody tr {
            border-bottom: 1px solid #dee2e6;
        }
        
        #productSelectionModal table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        
        #productSelectionModal .select-product {
            background-color: #ffc107;
            border-color: #ffc107;
            color: #212529;
            font-weight: 500;
            padding: 5px 15px;
            border-radius: 4px;
        }
        
        #productSelectionModal .select-product:hover {
            background-color: #ffb400;
            border-color: #ffb400;
        }
        
        #modalProductSearch {
            padding: 10px 15px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            box-shadow: none;
        }
    </style>
    @endpush
</x-dashboard-layout> 