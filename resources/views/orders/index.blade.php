<x-dashboard-layout :title="'Orders'" :icon="'fa-solid fa-cart-shopping'">
    @push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @endpush
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Left Column - Order Items -->
            <div class="col-lg-8">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shopping-cart me-2" style="color: var(--accent);"></i>
                            Product Catalog
                            <i class="fas fa-info-circle ms-2" data-bs-toggle="tooltip" title="Select products to add to the order"></i>
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Barcode Scanner Section -->
                        <div class="barcode-container mb-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-barcode"></i>
                                        </span>
                                        <input type="text" class="form-control" id="barcodeInput" placeholder="Scan or enter barcode">
                                        <button class="btn btn-accent" type="button" id="searchBarcode" data-bs-toggle="tooltip" title="Search for product by barcode">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <button class="btn btn-accent" type="button" id="toggleScanner" data-bs-toggle="tooltip" title="Use camera to scan barcodes">
                                        <i class="fas fa-camera me-1"></i>
                                        Toggle Barcode Scanner
                                    </button>
                                </div>
                            </div>
                            <!-- Camera view for barcode scanning -->
                            <div id="scanner" class="mt-3" style="display: none;">
                                <div id="interactive" class="viewport"></div>
                                <div class="last-scanned mt-2">
                                    Last scanned: <span class="scanned-code">None</span>
                                </div>
                            </div>
                        </div>

                        <!-- Product Catalog Section -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold mb-0">Browse Products</h6>
                                <div class="d-flex gap-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="productSearch" placeholder="Search products...">
                                        <button class="btn btn-accent" type="button" data-bs-toggle="tooltip" title="Search products by name or description">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                    <select class="form-select" id="categoryFilter" data-bs-toggle="tooltip" title="Filter products by category">
                                        <option value="">All Categories</option>
                                        <option value="interior">Interior</option>
                                        <option value="exterior">Exterior</option>
                                        <option value="engine">Engine</option>
                                        <option value="under_chassis">Under Chassis</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row g-2" id="productGrid">
                                <!-- Product grid will be populated dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Order Details -->
            <div class="col-lg-4">
                <div class="card shadow-sm h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-invoice me-2" style="color: var(--accent);"></i>
                            Order Summary
                            <i class="fas fa-info-circle ms-2" data-bs-toggle="tooltip" title="Current order details and total"></i>
                        </h5>
                        <button class="btn btn-sm btn-accent" id="clearOrder" data-bs-toggle="tooltip" title="Remove all items from order">
                            <i class="fas fa-trash me-1"></i>Clear
                        </button>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <!-- Order Items Table -->
                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center"></th>
                                    </tr>
                                </thead>
                                <tbody id="orderItemsList">
                                    <tr id="emptyOrderMessage">
                                        <td colspan="5" class="text-center py-3">
                                            <div class="no-data-message">
                                                <i class="fas fa-shopping-cart fa-2x mb-2" style="color: var(--accent);"></i>
                                                <p class="small mb-0">No items in order</p>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Customer Information -->
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h6 class="fw-bold mb-0">Customer Information</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#viewCustomersModal" data-bs-toggle="tooltip" title="View and select from all customers">
                                    <i class="fas fa-users me-1"></i>View All
                                </button>
                            </div>
                            <div class="mb-2 position-relative">
                                <input type="text" class="form-control form-control-sm" id="customerName" placeholder="Customer Name" autocomplete="off">
                                <div class="customer-suggestions position-absolute w-100 start-0 bg-white border rounded-bottom shadow-sm">
                                    <!-- Suggestions will be populated here -->
                                </div>
                            </div>
                            <div class="mb-2">
                                <input type="tel" class="form-control form-control-sm" id="customerPhone" placeholder="Phone Number">
                            </div>
                            <div class="mb-2">
                                <input type="email" class="form-control form-control-sm" id="customerEmail" placeholder="Email Address">
                            </div>
                        </div>

                        <!-- View All Customers Modal -->
                        <div class="modal fade" id="viewCustomersModal" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content bg-light">
                                    <div class="modal-header bg-dark text-white">
                                        <h5 class="modal-title">
                                            <i class="fas fa-users me-2" style="color: var(--accent);"></i>Customer List
                                            <i class="fas fa-info-circle ms-2" data-bs-toggle="tooltip" title="Select a customer for this order"></i>
                                        </h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body p-3">
                                        <!-- Search Section -->
                                        <div class="d-flex justify-content-end mb-3">
                                            <div class="input-group" style="width: auto;">
                                                <input type="text" class="form-control bg-white border border-secondary shadow-sm" id="customerSearch" placeholder="Search customers..." style="color: #000; font-weight: normal; border-width: 2px !important; outline: none; box-shadow: 0 0 0 0.25rem rgba(108, 117, 125, 0.15);">
                                                <button class="btn btn-accent" type="button" id="searchCustomersBtn">
                                                    <i class="fas fa-search"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Customer Table -->
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead class="bg-dark text-white">
                                                    <tr>
                                                        <th>Name</th>
                                                        <th>Phone</th>
                                                        <th>Email</th>
                                                        <th class="text-end">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="customerTableBody">
                                                    <tr>
                                                        <td colspan="4" class="text-center">Loading customers...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer bg-light">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Summary -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-2">Payment Details</h6>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="subtotalAmount">₱0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (12%):</span>
                                <span id="taxAmount">₱0</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Discount:</span>
                                <div class="d-flex align-items-center">
                                    <input type="number" class="form-control form-control-sm me-2" id="discountInput" value="0" style="width: 70px;">
                                    <select class="form-select form-select-sm" id="discountType" style="width: 60px;">
                                        <option value="amount">₱</option>
                                        <option value="percent">%</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="fw-bold">Total:</span>
                                <span class="fw-bold text-success fs-5" id="totalAmount">₱0</span>
                            </div>
                            <div class="d-grid">
                                <button type="button" id="completeOrderBtn" class="btn btn-accent" data-bs-toggle="tooltip" title="Process and save this order">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Complete Order
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('styles')
    <!-- QuaggaJS for barcode scanning -->
    <script src="https://cdn.jsdelivr.net/npm/quagga@0.12.1/dist/quagga.min.js"></script>
    <style>
        /* Custom column width for 5 cards per row */
        .col-md-20 {
            width: 20%;
            padding-right: 5px;
            padding-left: 5px;
        }
        
        @media (max-width: 992px) {
            .col-md-20 {
                width: 33.333%;
            }
        }
        
        @media (max-width: 768px) {
            .col-md-20 {
                width: 50%;
            }
        }
        
        @media (max-width: 576px) {
            .col-md-20 {
                width: 100%;
            }
        }
        
        /* Product card hover effect */
        .product-card {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            margin-bottom: 10px;
        }
        
        .product-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        
        /* Make spacing tighter in product grid */
        #productGrid {
            margin-left: -5px;
            margin-right: -5px;
            row-gap: 10px !important;
        }
        
        /* Order summary styles */
        .table-responsive {
            max-height: 200px;
            overflow-y: auto;
        }
        
        #orderItemsList .btn {
            line-height: 1;
        }
        
        #orderItemsList td {
            padding-top: 4px;
            padding-bottom: 4px;
            vertical-align: middle;
        }

        /* Customer card styles */
        .customer-card {
            transition: transform 0.2s, box-shadow 0.2s;
            cursor: pointer;
            margin-bottom: 10px;
            border: 1px solid rgba(0,0,0,0.1);
        }

        .customer-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }

        .customer-avatar {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(255, 228, 92, 0.1);
        }

        .customer-details i {
            width: 16px;
            color: var(--accent);
        }

        #customerGrid {
            max-height: 60vh;
            overflow-y: auto;
        }

        /* Customer suggestions styling */
        .customer-suggestions {
            display: none;
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 0 0 4px 4px;
        }
        
        .suggestion-item {
            padding: 0.5rem 0.75rem;
            cursor: pointer;
            color: #212529;
            font-weight: normal;
            transition: background-color 0.2s;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .suggestion-item:last-child {
            border-bottom: none;
        }
        
        .suggestion-item:hover {
            background-color: rgba(255, 228, 92, 0.1);
        }
        
        .suggestion-item small {
            opacity: 0.7;
        }
    </style>
    @endpush
    
    @push('scripts')
    <!-- SweetAlert2 for better alerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Global variables
            let orderItems = [];
            let customers = [];
            
            // Load all customers when the page loads
            fetchCustomers();
            
            // Function to fetch all customers
            async function fetchCustomers() {
                try {
                    const response = await fetch('/api/customers');
                    customers = await response.json();
                    populateCustomerTable(customers);
                } catch (error) {
                    console.error('Error fetching customers:', error);
                }
            }
            
            // Function to populate customer table
            function populateCustomerTable(customers) {
                const tableBody = document.getElementById('customerTableBody');
                
                if (customers.length === 0) {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="4" class="text-center">No customers found</td>
                        </tr>
                    `;
                    return;
                }
                
                tableBody.innerHTML = customers.map(customer => `
                    <tr>
                        <td>${customer.name}</td>
                        <td>${customer.phone || '-'}</td>
                        <td>${customer.email || '-'}</td>
                        <td class="text-end">
                            <button class="btn btn-accent btn-sm select-customer" 
                                data-name="${customer.name}" 
                                data-phone="${customer.phone || ''}" 
                                data-email="${customer.email || ''}">
                                Select Customer
                            </button>
                        </td>
                    </tr>
                `).join('');
                
                // Add event listeners to select buttons
                document.querySelectorAll('.select-customer').forEach(button => {
                    button.addEventListener('click', function() {
                        const name = this.dataset.name;
                        const phone = this.dataset.phone;
                        const email = this.dataset.email;
                        
                        document.getElementById('customerName').value = name;
                        document.getElementById('customerPhone').value = phone;
                        document.getElementById('customerEmail').value = email;
                        
                        bootstrap.Modal.getInstance(document.getElementById('viewCustomersModal')).hide();
                    });
                });
            }
            
            // Customer name input for autocomplete
            const customerNameInput = document.getElementById('customerName');
            const customerSuggestions = document.querySelector('.customer-suggestions');
            
            // Customer search in modal
            const customerSearchInput = document.getElementById('customerSearch');
            const searchCustomersBtn = document.getElementById('searchCustomersBtn');
            
            // Handle customer search in modal
            searchCustomersBtn.addEventListener('click', searchCustomers);
            customerSearchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    searchCustomers();
                }
            });
            
            // Function to search customers
            async function searchCustomers() {
                const query = customerSearchInput.value.trim();
                
                if (!query) {
                    fetchCustomers(); // If empty, load all customers
                    return;
                }
                
                try {
                    const response = await fetch(`/api/customers/search?q=${encodeURIComponent(query)}`);
                    const results = await response.json();
                    populateCustomerTable(results);
                } catch (error) {
                    console.error('Error searching customers:', error);
                }
            }
            
            // Handle customer name input for autocomplete
            customerNameInput.addEventListener('input', async function() {
                const value = this.value.trim();
                
                if (value.length < 2) {
                    customerSuggestions.style.display = 'none';
                    return;
                }
                
                try {
                    const response = await fetch(`/api/customers/search?q=${encodeURIComponent(value)}`);
                    const results = await response.json();
                    
                    if (results.length > 0) {
                        customerSuggestions.innerHTML = results.map(customer => `
                            <div class="suggestion-item" 
                                data-name="${customer.name}"
                                data-phone="${customer.phone || ''}" 
                                data-email="${customer.email || ''}">
                                ${customer.name}
                                ${customer.phone ? `<small class="text-muted ms-2">${customer.phone}</small>` : ''}
                            </div>
                        `).join('');
                        
                        customerSuggestions.style.display = 'block';
                        
                        // Add click event to suggestions
                        document.querySelectorAll('.suggestion-item').forEach(item => {
                            item.addEventListener('click', function() {
                                customerNameInput.value = this.dataset.name;
                                document.getElementById('customerPhone').value = this.dataset.phone;
                                document.getElementById('customerEmail').value = this.dataset.email;
                                customerSuggestions.style.display = 'none';
                                
                                // Add visual feedback for customer selection
                                customerNameInput.classList.add('border-success');
                                setTimeout(() => {
                                    customerNameInput.classList.remove('border-success');
                                }, 1500);
                            });
                        });
                    } else {
                        customerSuggestions.style.display = 'none';
                    }
                } catch (error) {
                    console.error('Error fetching customer suggestions:', error);
                    customerSuggestions.style.display = 'none';
                }
            });
            
            // Hide suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!customerNameInput.contains(e.target) && !customerSuggestions.contains(e.target)) {
                    customerSuggestions.style.display = 'none';
                }
            });
            
            // Check if URL has #viewCustomers hash and open the modal if it does
            if (window.location.hash === '#viewCustomers') {
                const customersModal = new bootstrap.Modal(document.getElementById('viewCustomersModal'));
                customersModal.show();
                // Remove the hash from URL after opening modal to avoid reopening on page refresh
                history.replaceState(null, null, ' ');
            }
            
            let isScanning = false;
            let products = [];

            // Fetch products on page load
            fetchProducts();
            
            // Set up event listeners
            document.getElementById('productSearch').addEventListener('input', filterProducts);
            document.getElementById('categoryFilter').addEventListener('change', filterProducts);
            
            // Barcode scanner
            document.getElementById('searchBarcode').addEventListener('click', searchByBarcode);
            document.getElementById('barcodeInput').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    searchByBarcode();
                }
            });
            
            // Toggle barcode scanner
            document.getElementById('toggleScanner').addEventListener('click', toggleBarcodeScanner);
            
            // Payment method change
            document.getElementById('paymentMethod').addEventListener('change', handlePaymentMethodChange);
            
            // Order actions
            document.getElementById('amountReceived').addEventListener('input', calculateChange);
            document.getElementById('ewalletAmount').addEventListener('input', updateOrderSummary);
            document.getElementById('discountInput').addEventListener('input', updateOrderSummary);
            document.getElementById('discountType').addEventListener('change', updateOrderSummary);
            document.getElementById('clearOrder').addEventListener('click', clearOrder);
            document.getElementById('completeOrder').addEventListener('click', completeOrder);

            // Fetch products from the server
            async function fetchProducts() {
                console.log("Fetching products...");
                
                try {
                    const response = await fetch('{{ route("orders.products") }}');
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    console.log("Products fetched:", data.length);
                    
                    // Store products globally
                    products = data;
                    
                    // Render the products
                    renderProducts(products);
                } catch (error) {
                    console.error('Error fetching products:', error);
                    document.getElementById('productGrid').innerHTML = `
                        <div class="col-12 text-center py-4">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Error loading products: ${error.message}
                                <p>Please try refreshing the page.</p>
                            </div>
                        </div>
                    `;
                }
            }

            // Render products in the grid
            function renderProducts(productsToRender) {
                console.log("Rendering products:", productsToRender);
                const productGrid = document.getElementById('productGrid');
                if (!productGrid) {
                    console.error("Product grid element not found");
                    return;
                }
                
                productGrid.innerHTML = '';

                if (!productsToRender || productsToRender.length === 0) {
                    console.log("No products to render");
                    productGrid.innerHTML = `
                        <div class="col-12 text-center py-4">
                            <div class="no-data-message">
                                <i class="fas fa-search fa-3x mb-3" style="color: var(--accent);"></i>
                                <h6 class="fw-normal">No products found</h6>
                                <p class="small mb-0" style="color: rgba(255, 255, 255, 0.6);">
                                    Try adjusting your search criteria
                                </p>
                            </div>
                        </div>
                    `;
                    return;
                }

                try {
                    productsToRender.forEach(product => {
                        const productCard = document.createElement('div');
                        productCard.className = 'col-md-20'; // Use custom width for 5 cards per row
                    productCard.innerHTML = `
                            <div class="card h-100 rounded-4 product-card" data-product-id="${product.id}">
                                <div class="position-relative">
                                    <img src="${product.image}" class="card-img-top rounded-top-4" alt="${product.name}" 
                                        style="height: 120px; object-fit: cover;">
                                    <span class="badge ${product.stock > 0 ? 'bg-success' : 'bg-danger'} position-absolute top-0 end-0 m-1">
                                        ${product.stock > 0 ? 'In Stock' : 'Out Stock'}
                                    </span>
                            </div>
                                <div class="card-body p-2">
                                    <h6 class="card-title mb-0 text-truncate">${product.name}</h6>
                                    ${product.brand ? `<p class="card-text small text-secondary-subtle mb-1">${product.brand}</p>` : ''}
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">₱${Math.round(product.price)}</span>
                                        <span class="badge bg-secondary-subtle text-dark">${product.category}</span>
                                    </div>
                                    <button class="btn btn-accent btn-sm w-100 mt-1 add-to-order" data-product-id="${product.id}">
                                        + Add to Order
                                </button>
                            </div>
                        </div>
                    `;
                    productGrid.appendChild(productCard);
                });

                    console.log("Products rendered, adding event listeners");
                    
                    // Add event listeners to the Add to Order buttons
                document.querySelectorAll('.add-to-order').forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const productId = this.dataset.productId;
                            console.log("Add button clicked for product:", productId);
                            const product = products.find(p => p.id == productId);
                            if (product) {
                                addProductToOrder(product);
                            } else {
                                console.error("Product not found:", productId);
                            }
                        });
                    });
                    
                    // Add event listeners to the cards for quick add
                    document.querySelectorAll('.product-card').forEach(card => {
                        card.addEventListener('click', function() {
                            const productId = this.dataset.productId;
                            console.log("Card clicked for product:", productId);
                            const product = products.find(p => p.id == productId);
                            if (product) {
                                addProductToOrder(product);
                            } else {
                                console.error("Product not found:", productId);
                            }
                        });
                    });
                } catch (error) {
                    console.error("Error rendering products:", error);
                    productGrid.innerHTML = `
                        <div class="col-12 text-center py-4">
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Error rendering products. Please try refreshing the page.
                            </div>
                        </div>
                    `;
                }
            }

            // Filter products based on search and category
            function filterProducts() {
                const searchTerm = document.getElementById('productSearch').value.toLowerCase();
                const categoryFilter = document.getElementById('categoryFilter').value.toLowerCase();
                
                const filteredProducts = products.filter(product => {
                    const matchesSearch = product.name.toLowerCase().includes(searchTerm) || 
                                         product.sku.toLowerCase().includes(searchTerm) ||
                                         (product.brand && product.brand.toLowerCase().includes(searchTerm));
                    
                    const matchesCategory = categoryFilter === '' || 
                                           product.category.toLowerCase() === categoryFilter;
                    
                    return matchesSearch && matchesCategory;
                });
                
                renderProducts(filteredProducts);
            }
            
            // Search by barcode
            function searchByBarcode() {
                const barcode = document.getElementById('barcodeInput').value.trim();
                if (!barcode) return;
                
                fetch(`{{ route("orders.search-barcode") }}?barcode=${barcode}`)
                    .then(response => response.json())
                    .then(data => {
                    if (data.success) {
                            addProductToOrder({
                                id: data.product.id,
                                name: data.product.name,
                                price: data.product.price,
                                stock: data.product.stock,
                                sku: data.product.sku,
                                image: products.find(p => p.id == data.product.id)?.image || 'images/product_placeholder.jpg'
                            });
                            document.getElementById('barcodeInput').value = '';
                        } else {
                            alert('Product not found');
                        }
                    })
                    .catch(error => {
                    console.error('Error searching barcode:', error);
                    alert('Error searching product. Please try again.');
                    });
            }
            
            // Toggle barcode scanner
            function toggleBarcodeScanner() {
                const scanner = document.getElementById('scanner');
                if (!isScanning) {
                    scanner.style.display = 'block';
                    initBarcodeScanner();
                    this.innerHTML = '<i class="fas fa-camera-slash me-1"></i>Stop Scanner';
                } else {
                    scanner.style.display = 'none';
                    stopBarcodeScanner();
                    this.innerHTML = '<i class="fas fa-camera me-1"></i>Toggle Barcode Scanner';
                }
                isScanning = !isScanning;
            }
            
            // Initialize barcode scanner
            function initBarcodeScanner() {
                if (typeof Quagga !== 'undefined') {
                Quagga.init({
                    inputStream: {
                        name: "Live",
                        type: "LiveStream",
                        target: document.querySelector("#interactive"),
                        constraints: {
                            facingMode: "environment"
                        },
                    },
                    decoder: {
                        readers: [
                            "ean_reader",
                            "ean_8_reader",
                            "code_128_reader",
                            "code_39_reader",
                            "upc_reader"
                        ]
                    }
                }, function(err) {
                    if (err) {
                        console.error(err);
                        alert("Error starting scanner: " + err);
                        return;
                    }
                    Quagga.start();
                });
                
                Quagga.onDetected(function(result) {
                    const code = result.codeResult.code;
                    document.querySelector('.scanned-code').textContent = code;
                    document.getElementById('barcodeInput').value = code;
                        searchByBarcode();
                    });
                }
            }
            
            // Stop barcode scanner
            function stopBarcodeScanner() {
                if (typeof Quagga !== 'undefined') {
                    Quagga.stop();
                }
            }
            
            // Handle payment method change
            function handlePaymentMethodChange() {
                const paymentMethod = this.value;
                const cashDetails = document.getElementById('cashPaymentDetails');
                const ewalletDetails = document.getElementById('ewalletPaymentDetails');
                
                cashDetails.style.display = 'none';
                ewalletDetails.style.display = 'none';
                
                if (paymentMethod === 'cash') {
                    cashDetails.style.display = 'block';
                        } else {
                    ewalletDetails.style.display = 'block';
                        }
                        
                        updateOrderSummary();
            }
            
            // Calculate change
            function calculateChange() {
                const total = parseFloat(document.getElementById('totalAmount').textContent.replace('₱', '')) || 0;
                const received = parseFloat(this.value) || 0;
                const change = received - total;
                
                const changeAmount = document.getElementById('changeAmount');
                changeAmount.textContent = `₱${Math.round(change)}`;
                
                // Visual indication if amount is sufficient
                if (change >= 0) {
                    changeAmount.classList.remove('text-danger');
                    changeAmount.classList.add('text-success');
                    } else {
                    changeAmount.classList.remove('text-success');
                    changeAmount.classList.add('text-danger');
                }
                
                updateOrderSummary();
            }

            // Update order summary
            function updateOrderSummary() {
                const subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                const tax = subtotal * 0.12; // 12% tax
                
                // Get discount values
                const discountValue = parseFloat(document.getElementById('discountInput').value) || 0;
                const discountType = document.getElementById('discountType').value;
                
                // Calculate discount amount
                let discountAmount = 0;
                if (discountType === 'amount') {
                    discountAmount = discountValue;
                } else {
                    discountAmount = subtotal * (discountValue / 100);
                }
                
                // Calculate total
                const total = Math.max(0, subtotal + tax - discountAmount);
                
                // Update displayed values
                document.getElementById('subtotalAmount').textContent = `₱${Math.round(subtotal)}`;
                document.getElementById('taxAmount').textContent = `₱${Math.round(tax)}`;
                document.getElementById('totalAmount').textContent = `₱${Math.round(total)}`;
                
                // Validate payment and enable/disable complete order button
                const paymentMethod = document.getElementById('paymentMethod').value;
                const completeOrderBtn = document.getElementById('completeOrder');
                
                // First check if cart has items
                if (orderItems.length === 0) {
                    completeOrderBtn.disabled = true;
                    return;
                }

                if (paymentMethod === 'cash') {
                    const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
                    completeOrderBtn.disabled = amountReceived < Math.round(total);
                } else {
                    const ewalletAmount = parseFloat(document.getElementById('ewalletAmount').value) || 0;
                    const ewalletReference = document.getElementById('ewalletReference').value.trim();
                    completeOrderBtn.disabled = ewalletAmount < Math.round(total) || !ewalletReference;
                }
            }

            // Clear order
            function clearOrder() {
                // Clear the order items
                orderItems = [];
                document.getElementById('orderItemsList').innerHTML = `
                    <tr id="emptyOrderMessage">
                        <td colspan="5" class="text-center py-3">
                            <div class="no-data-message">
                                <i class="fas fa-shopping-cart fa-2x mb-2" style="color: var(--accent);"></i>
                                <p class="small mb-0">No items in order</p>
                            </div>
                        </td>
                    </tr>
                `;
                updateOrderSummary();
            }
            
            // Complete order
            function completeOrder() {
                // Validate customer information first
                const customerName = document.getElementById('customerName').value.trim();
                if (!customerName) {
                    alert('Please enter customer name.');
                    document.getElementById('customerName').focus();
                    return;
                }

                // Prepare order data
                const customerData = {
                    name: customerName,
                    phone: document.getElementById('customerPhone').value.trim(),
                    email: document.getElementById('customerEmail').value.trim()
                };
                
                const paymentMethod = document.getElementById('paymentMethod').value;
                const total = parseFloat(document.getElementById('totalAmount').textContent.replace('₱', ''));
                const subtotal = parseFloat(document.getElementById('subtotalAmount').textContent.replace('₱', ''));
                const tax = parseFloat(document.getElementById('taxAmount').textContent.replace('₱', ''));

                // Validate payment
                let paymentData = {};
                if (paymentMethod === 'cash') {
                    const amountReceived = parseFloat(document.getElementById('amountReceived').value) || 0;
                    if (amountReceived < total) {
                        alert('Amount received is less than the total amount.');
                        document.getElementById('amountReceived').focus();
                        return;
                    }
                    paymentData = {
                        method: 'cash',
                        amount_received: amountReceived,
                        reference: null
                    };
                } else {
                    const ewalletRef = document.getElementById('ewalletReference').value.trim();
                    const ewalletAmount = parseFloat(document.getElementById('ewalletAmount').value) || 0;
                    
                    if (!ewalletRef) {
                        alert('Please enter a reference number for e-wallet payment.');
                        document.getElementById('ewalletReference').focus();
                        return;
                    }
                    
                    if (ewalletAmount < total) {
                        alert('E-wallet amount is less than the total amount.');
                        document.getElementById('ewalletAmount').focus();
                        return;
                    }
                    
                    paymentData = {
                        method: paymentMethod,
                        amount_received: ewalletAmount,
                        reference: ewalletRef
                    };
                }
                
                // Prepare order data
                const orderData = {
                    customer: customerData,
                    items: orderItems.map(item => ({
                        product_id: item.id,
                        quantity: item.quantity,
                        price: item.price,
                        subtotal: item.price * item.quantity
                    })),
                    payment: paymentData,
                    summary: {
                        subtotal: subtotal,
                        tax: tax,
                        discount_type: document.getElementById('discountType').value,
                        discount_value: parseFloat(document.getElementById('discountInput').value) || 0,
                        total: total
                    },
                    notes: document.getElementById('orderNotes').value.trim()
                };

                // Proceed with order submission
                submitOrder(orderData);
            }
            
            // Function to submit the order data to the server
            function submitOrder(orderData) {
                // Disable the button to prevent multiple submissions
                const completeOrderBtn = document.getElementById('completeOrder');
                completeOrderBtn.disabled = true;
                
                // Submit order
                fetch('{{ route("orders.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(orderData)
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        // Show success message with print option
                        const orderNumber = result.order.order_number;
                        const orderId = result.order.id;
                        
                        // Create print receipt button
                        const printReceiptUrl = `/orders/${orderId}/receipt`;
                        
                        // Refresh the customer list to include any new customers
                        fetchCustomers();
                        
                        // Success message with options - with fallback if SweetAlert is not loaded
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Order Completed!',
                                html: `
                                    <p>Order #${orderNumber} has been successfully processed.</p>
                                    <div class="d-flex justify-content-center mt-3">
                                        <a href="${printReceiptUrl}" target="_blank" class="btn btn-success mx-2">
                                            <i class="fas fa-print me-1"></i> Print Receipt
                                        </a>
                                        <a href="{{ route('orders.history') }}?order_id=${orderId}" class="btn btn-secondary mx-2">
                                            <i class="fas fa-eye me-1"></i> View Order
                                        </a>
                                    </div>
                                `,
                                icon: 'success',
                                showConfirmButton: false,
                                showCloseButton: true
                            });
                        } else {
                            // Fallback for browsers where SweetAlert might be blocked
                            alert(`Order #${orderNumber} has been completed successfully!`);
                            
                            // Open receipt in new window
                            window.open(printReceiptUrl, '_blank');
                            
                            // Redirect to order history page after a short delay
                            setTimeout(function() {
                                window.location.href = `{{ route('orders.history') }}?order_id=${orderId}`;
                            }, 1000);
                        }
                        
                        // Clear the current order
                        orderItems = [];
                        document.getElementById('orderItemsList').innerHTML = `
                            <tr id="emptyOrderMessage">
                                <td colspan="5" class="text-center py-3">
                                    <div class="no-data-message">
                                        <i class="fas fa-shopping-cart fa-2x mb-2" style="color: var(--accent);"></i>
                                        <p class="small mb-0">No items in order</p>
                                    </div>
                                </td>
                            </tr>
                        `;
                        updateOrderSummary();
                        
                        // Reset form fields
                        document.getElementById('customerName').value = '';
                        document.getElementById('customerPhone').value = '';
                        document.getElementById('customerEmail').value = '';
                        document.getElementById('amountReceived').value = '';
                        document.getElementById('discountInput').value = '0';
                        document.getElementById('orderNotes').value = '';
                    } else {
                        throw new Error(result.message || 'Failed to complete order');
                    }
                })
                .catch(error => {
                    console.error('Error completing order:', error);
                    alert('Failed to complete order: ' + error.message);
                    
                    // Re-enable the button on error
                    completeOrderBtn.disabled = false;
                });
            }

            // Add product to order
            function addProductToOrder(product) {
                if (!product) return;
                
                // Check if product is in stock
                if (product.stock <= 0) {
                    alert('This product is out of stock.');
                    return;
                }

                // Hide empty order message
                const emptyMessage = document.getElementById('emptyOrderMessage');
                if (emptyMessage) {
                    emptyMessage.style.display = 'none';
                }

                // Check if product is already in order
                const existingItem = orderItems.find(item => item.id === product.id);
                
                if (existingItem) {
                    // Check if adding more would exceed stock
                    if (existingItem.quantity >= product.stock) {
                        alert('Cannot add more of this product. Maximum stock reached.');
                        return;
                    }
                    
                    existingItem.quantity += 1;
                    updateOrderItemRow(existingItem);
                } else {
                    const newItem = {
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        quantity: 1,
                        stock: product.stock,
                        sku: product.sku,
                        image: product.image
                    };
                    orderItems.push(newItem);
                    addOrderItemRow(newItem);
                }
                
                updateOrderSummary();
            }

            // Handle quantity change (plus/minus buttons)
            function handleQuantityChange() {
                const productId = this.dataset.id;
                const item = orderItems.find(item => item.id == productId);
                
                if (!item) return;
                
                if (this.classList.contains('plus')) {
                    // Don't exceed stock
                    if (item.quantity >= item.stock) {
                        alert('Cannot add more of this product. Maximum stock reached.');
                        return;
                    }
                    item.quantity += 1;
                } else if (this.classList.contains('minus')) {
                    if (item.quantity > 1) {
                        item.quantity -= 1;
                    }
                }
                
                updateOrderItemRow(item);
                updateOrderSummary();
            }
            
            // Handle remove item button
            function handleRemoveItem() {
                const productId = this.dataset.id;
                orderItems = orderItems.filter(item => item.id != productId);
                
                // Remove the row
                this.closest('tr').remove();
                
                // Show empty message if no items remain
                if (orderItems.length === 0) {
                    document.getElementById('emptyOrderMessage').style.display = '';
                }
                
                updateOrderSummary();
            }

            // Add a new row to the order items table
            function addOrderItemRow(item) {
                const orderItemsList = document.getElementById('orderItemsList');
                const tr = document.createElement('tr');
                tr.dataset.id = item.id;
                tr.innerHTML = `
                    <td>
                        <div class="d-flex align-items-center">
                            <img src="${item.image}" alt="${item.name}" 
                                class="img-thumbnail me-1" style="width: 30px; height: 30px; object-fit: cover;">
                            <div class="small fw-bold text-truncate" style="max-width: 100px;">${item.name}</div>
                        </div>
                    </td>
                    <td>₱${Math.round(item.price)}</td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-sm btn-outline-secondary px-1 py-0 quantity-btn minus" data-id="${item.id}">-</button>
                            <span class="px-1 quantity-display">${item.quantity}</span>
                            <button class="btn btn-sm btn-outline-secondary px-1 py-0 quantity-btn plus" data-id="${item.id}">+</button>
                        </div>
                    </td>
                    <td class="text-end item-subtotal">₱${Math.round(item.price * item.quantity)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger p-0 px-1 remove-item" data-id="${item.id}">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                `;
                orderItemsList.appendChild(tr);
                
                // Add event listeners to the quantity control buttons
                tr.querySelector('.minus').addEventListener('click', handleQuantityChange);
                tr.querySelector('.plus').addEventListener('click', handleQuantityChange);
                tr.querySelector('.remove-item').addEventListener('click', handleRemoveItem);
            }

            // Update an existing order item row
            function updateOrderItemRow(item) {
                const row = document.querySelector(`tr[data-id="${item.id}"]`);
                if (!row) return;
                
                row.querySelector('.quantity-display').textContent = item.quantity;
                row.querySelector('.item-subtotal').textContent = `₱${Math.round(item.price * item.quantity)}`;
            }

            // Add a direct click handler for Add to Order when the document is ready
            document.addEventListener('DOMContentLoaded', function() {
                // Delegate event listener for add-to-order buttons
                document.body.addEventListener('click', function(e) {
                    // Check if clicked element or its parent is an add-to-order button
                    const button = e.target.closest('.add-to-order');
                    if (button) {
                        e.preventDefault();
                        e.stopPropagation();
                        const productId = button.dataset.productId;
                        console.log("Add button clicked via delegation for product:", productId);
                        const product = products.find(p => p.id == productId);
                        if (product) {
                            addProductToOrder(product);
                        } else {
                            console.error("Product not found:", productId);
                        }
                    }
                    
                    // Check if clicked element or its parent is a product card
                    const card = e.target.closest('.product-card:not(.add-to-order)');
                    if (card && !e.target.closest('.add-to-order')) {
                        e.preventDefault();
                        const productId = card.dataset.productId;
                        console.log("Card clicked via delegation for product:", productId);
                        const product = products.find(p => p.id == productId);
                        if (product) {
                            addProductToOrder(product);
                } else {
                            console.error("Product not found:", productId);
                        }
                    }
                });
            });

            // Initialize all tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            });
            
            // Reinitialize tooltips when modal is shown
            document.getElementById('viewCustomersModal').addEventListener('shown.bs.modal', function () {
                var modalTooltipTriggerList = [].slice.call(document.querySelectorAll('#viewCustomersModal [data-bs-toggle="tooltip"]'))
                var modalTooltipList = modalTooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });
            });
        });
    </script>
    @endpush
</x-dashboard-layout> 