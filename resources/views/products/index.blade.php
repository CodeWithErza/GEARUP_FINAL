<x-dashboard-layout :title="'Product Management'" :icon="'fa-solid fa-boxes-stacked'">
    <div class="container-fluid">
        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-md-6">
                <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addProductModal" data-bs-toggle="tooltip" title="Add a new product to your inventory">
                    <i class="fas fa-plus"></i> Add New Product
                </button>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <select class="form-select category-filter" id="categoryFilter" style="max-width: 200px;" data-bs-toggle="tooltip" title="Filter products by category">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <input type="text" class="form-control search-input" id="searchInput" placeholder="Search products...">
                    <button class="btn btn-outline-secondary" type="button" id="searchButton" data-bs-toggle="tooltip" title="Search for products by name, SKU, or brand">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            Products Directory
                            <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Complete list of all products in your inventory"></i>
                        </h5>
                        <div>
                            <select class="form-select form-select-sm sort-select" id="sortSelect" data-bs-toggle="tooltip" title="Change how products are sorted in the list">
                                <option value="status_name_asc">Status & Name (A-Z)</option>
                                <option value="status_name_desc">Status & Name (Z-A)</option>
                                <option value="status_stock_low">Status & Low Stock First</option>
                                <option value="status_stock_high">Status & High Stock First</option>
                                <option value="status_price_low">Status & Price (Low to High)</option>
                                <option value="status_price_high">Status & Price (High to Low)</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle data-table" id="productsTable">
                                <thead>
                                    <tr>
                                        <th scope="col" width="25%">Product</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">SKU</th>
                                        <th scope="col">Price</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Reorder</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($products as $product)
                                    <tr class="product-row" 
                                        data-name="{{ strtolower($product->name) }}" 
                                        data-category="{{ $product->category_id }}"
                                        data-sku="{{ strtolower($product->sku) }}"
                                        data-brand="{{ strtolower($product->brand ?? '') }}"
                                        data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock }}"
                                        data-active="{{ $product->is_active ? '1' : '0' }}">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="product-image-container me-3" style="width: 40px; height: 40px;">
                                                    @if($product->image && file_exists(public_path($product->image)))
                                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" 
                                                            class="img-thumbnail" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-box text-primary"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <strong>{{ $product->name }}</strong>
                                                    @if($product->brand)
                                                    <div class="small text-secondary-subtle">{{ $product->brand }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $product->category->name }}</td>
                                        <td><span class="badge bg-secondary">{{ $product->sku }}</span></td>
                                        <td>₱{{ number_format($product->price, 2) }}</td>
                                        <td>
                                            <span class="badge {{ $product->stock == 0 ? 'bg-danger text-white' : ($product->stock <= $product->reorder_level ? 'bg-warning text-white' : 'bg-success text-white') }} fs-6">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                        <td>{{ $product->reorder_level }}</td>
                                        <td>
                                            <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-sm btn-outline-primary edit-product" title="Edit Product" data-bs-toggle="modal" data-bs-target="#editProductModal" data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                @if($product->is_active)
                                                <button class="btn btn-sm btn-outline-danger delete-product" title="Mark as Inactive" data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                                @else
                                                <button class="btn btn-sm btn-outline-success activate-product" title="Activate Product" data-product-id="{{ $product->id }}">
                                                    <i class="fas fa-redo"></i>
                                                </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr id="no-data-row">
                                        <td colspan="8" class="text-center py-5">
                                            <div class="no-data-message">
                                                <i class="fas fa-box-open fa-3x mb-3" style="color: var(--accent);"></i>
                                                <h6 class="fw-normal">No products found</h6>
                                                <p class="small mb-0" style="color: rgba(255, 255, 255, 0.6);">Add your first product to get started</p>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <span id="product-count">Showing {{ $products->firstItem() ?? 0 }} to {{ $products->lastItem() ?? 0 }} of {{ $products->total() ?? 0 }} products</span>
                            {{ $products->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-light">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="addProductModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>
                        Add New Product
                        <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Add a new product to your inventory system"></i>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <form id="addProductForm" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-2">
                            <!-- Image Column -->
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <img id="preview-image" src="{{ asset('images/product_placeholder.jpg') }}" 
                                        alt="Product Image" class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover; margin-bottom: 8px;">
                                    <input type="file" class="form-control form-control-sm border border-secondary-subtle shadow-sm" id="image" name="image" accept="image/*">
                                </div>
                            </div>
                            
                            <!-- Basic Info Column -->
                            <div class="col-md-9">
                                <div class="row g-2">
                                    <!-- Product Name & Category Row -->
                            <div class="col-md-8">
                                        <label class="form-label mb-1 fw-bold text-dark">Product Name</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="name" name="name" required>
                            </div>
                            <div class="col-md-4">
                                        <label class="form-label mb-1 fw-bold text-dark">Category</label>
                                        <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" id="category_id" name="category_id" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                                    
                                    <!-- SKU & Price Row -->
                                    <div class="col-md-6">
                                        <label class="form-label mb-1 fw-bold text-dark">SKU/Barcode</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="sku" name="sku" placeholder="Auto-generated if blank">
                        </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-1 fw-bold text-dark">Price</label>
                                <div class="input-group shadow-sm">
                                    <span class="input-group-text border border-secondary-subtle">₱</span>
                                            <input type="number" class="form-control bg-white text-dark border border-secondary-subtle" id="price" name="price" step="0.01" required>
                                </div>
                            </div>
                                    
                                    <!-- Stock Row -->
                                    <div class="col-md-6">
                                        <label class="form-label mb-1 fw-bold text-dark">Initial Stock</label>
                                        <input type="number" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="stock" name="stock" required min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-1 fw-bold text-dark">Reorder Level</label>
                                        <input type="number" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="reorder_level" name="reorder_level" value="10" required min="0">
                            </div>
                        </div>
                            </div>
                            
                            <!-- Vehicle Part Details Section -->
                            <div class="col-12">
                                <hr class="my-2">
                                <h6 class="text-dark fw-bold mb-2">Part Details</h6>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 text-dark">Brand</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="brand" name="brand">
                            </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 text-dark">Model</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="model" name="model">
                            </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 text-dark">Unit</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="unit" name="unit" value="piece" required>
                        </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 text-dark">Manufacturer</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="manufacturer" name="manufacturer">
                            </div>
                                    <div class="col-md-9">
                                        <label class="form-label mb-1 text-dark">Description <small class="text-muted">(max 200 chars)</small></label>
                                        <textarea class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="description" name="description" rows="1" maxlength="200"></textarea>
                            </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 text-dark">Status</label>
                                        <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" id="is_active" name="is_active">
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addProductForm" class="btn btn-primary">Save Product</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-light">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editProductModalLabel"><i class="fas fa-edit me-2"></i>Edit Product</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <form id="editProductForm" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" id="edit_product_id" name="product_id">
                        <div class="row g-2">
                            <!-- Image Column -->
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <img id="edit-preview-image" src="{{ asset('images/product_placeholder.jpg') }}" 
                                        alt="Product Image" class="img-thumbnail" style="width: 120px; height: 120px; object-fit: cover; margin-bottom: 8px;">
                                    <input type="file" class="form-control form-control-sm border border-secondary-subtle shadow-sm" id="edit_image" name="image" accept="image/*">
                </div>
            </div>
                            
                            <!-- Basic Info Column -->
                            <div class="col-md-9">
                                <div class="row g-2">
                                    <!-- Product Name & Category Row -->
                                    <div class="col-md-8">
                                        <label class="form-label mb-1 fw-bold text-dark">Product Name</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_name" name="name" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label mb-1 fw-bold text-dark">Category</label>
                                        <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_category_id" name="category_id" required>
                                            <option value="">Select Category</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    
                                    <!-- SKU & Price Row -->
                                    <div class="col-md-6">
                                        <label class="form-label mb-1 fw-bold text-dark">SKU/Barcode</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_sku" name="sku">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-1 fw-bold text-dark">Price</label>
                                        <div class="input-group shadow-sm">
                                            <span class="input-group-text border border-secondary-subtle">₱</span>
                                            <input type="number" class="form-control bg-white text-dark border border-secondary-subtle" id="edit_price" name="price" step="0.01" required>
        </div>
    </div>

                                    <!-- Stock Row -->
                                    <div class="col-md-6">
                                        <label class="form-label mb-1 fw-bold text-dark">Stock</label>
                                        <input type="number" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_stock" name="stock" required min="0">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label mb-1 fw-bold text-dark">Reorder Level</label>
                                        <input type="number" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_reorder_level" name="reorder_level" required min="0">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Vehicle Part Details Section -->
                            <div class="col-12">
                                <hr class="my-2">
                                <h6 class="text-dark fw-bold mb-2">Part Details</h6>
                                <div class="row g-2">
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 text-dark">Brand</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_brand" name="brand">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 text-dark">Model</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_model" name="model">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 text-dark">Unit</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_unit" name="unit" required>
                </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 text-dark">Manufacturer</label>
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_manufacturer" name="manufacturer">
                        </div>
                                    <div class="col-md-9">
                                        <label class="form-label mb-1 text-dark">Description <small class="text-muted">(max 200 chars)</small></label>
                                        <textarea class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_description" name="description" rows="1" maxlength="200"></textarea>
                        </div>
                                    <div class="col-md-3">
                                        <label class="form-label mb-1 text-dark">Status</label>
                                        <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_is_active" name="is_active">
                                            <option value="1">Active</option>
                                            <option value="0">Inactive</option>
                            </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editProductForm" class="btn btn-primary">Update Product</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            
            // Re-initialize tooltips after dynamic content is loaded
            const addProductModal = document.getElementById('addProductModal');
            if (addProductModal) {
                addProductModal.addEventListener('shown.bs.modal', function() {
                    const modalTooltips = addProductModal.querySelectorAll('[data-bs-toggle="tooltip"]');
                    [...modalTooltips].forEach(tooltipTrigger => new bootstrap.Tooltip(tooltipTrigger));
                });
            }
            
            const editProductModal = document.getElementById('editProductModal');
            if (editProductModal) {
                editProductModal.addEventListener('shown.bs.modal', function() {
                    const modalTooltips = editProductModal.querySelectorAll('[data-bs-toggle="tooltip"]');
                    [...modalTooltips].forEach(tooltipTrigger => new bootstrap.Tooltip(tooltipTrigger));
                });
            }
            
            // Initialize forms and inputs
            const addProductForm = document.getElementById('addProductForm');
            const editProductForm = document.getElementById('editProductForm');
            
            // Search, Filter and Sort functionality
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const sortSelect = document.getElementById('sortSelect');
            const searchButton = document.getElementById('searchButton');
            const productRows = document.querySelectorAll('.product-row');
            const productsTable = document.getElementById('productsTable');
            let noDataRow = document.getElementById('no-data-row');
            const productCount = document.getElementById('product-count');
            
            // Log initial state for debugging
            console.log('Initial setup:', {
                productRowCount: productRows.length,
                categoryOptions: categoryFilter ? categoryFilter.options.length : 0,
                noDataRow: noDataRow ? 'exists' : 'not found'
            });
            
            // Function to filter and sort products
            function filterAndSortProducts() {
                const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
                const categoryId = categoryFilter ? categoryFilter.value : '';
                const sortOption = sortSelect ? sortSelect.value : 'status_name_asc';
                
                // Log filter criteria for debugging
                console.log('Filtering with:', {
                    searchTerm,
                    categoryId,
                    sortOption
                });
                
                let visibleCount = 0;
                let allRows = Array.from(productRows);
                
                // Filter rows based on search term and category
                allRows.forEach(row => {
                    const name = row.dataset.name || '';
                    const sku = row.dataset.sku || '';
                    const brand = row.dataset.brand || '';
                    const category = row.dataset.category || '';
                    
                    const matchesSearch = searchTerm === '' || 
                                         name.includes(searchTerm) || 
                                         sku.includes(searchTerm) ||
                                         brand.includes(searchTerm);
                    
                    // Fix: Convert both values to strings for proper comparison
                    const matchesCategory = categoryId === '' || String(category) === String(categoryId);
                    
                    if (matchesSearch && matchesCategory) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Log results for debugging
                console.log('Filter results:', {
                    totalRows: allRows.length,
                    visibleCount: visibleCount
                });
                
                // Sort visible rows
                if (allRows.length > 0) {
                    const visibleRows = allRows.filter(row => row.style.display !== 'none');
                    
                    visibleRows.sort((a, b) => {
                        // First compare by active status
                        const aActive = a.dataset.active === '1';
                        const bActive = b.dataset.active === '1';
                        if (aActive !== bActive) {
                            return bActive ? 1 : -1;
                        }

                        // Then sort by the selected criteria
                        switch (sortOption) {
                            case 'status_name_asc':
                                return a.dataset.name.localeCompare(b.dataset.name);
                            case 'status_name_desc':
                                return b.dataset.name.localeCompare(a.dataset.name);
                            case 'status_stock_low':
                                return parseInt(a.dataset.stock) - parseInt(b.dataset.stock);
                            case 'status_stock_high':
                                return parseInt(b.dataset.stock) - parseInt(a.dataset.stock);
                            case 'status_price_low':
                                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
                            case 'status_price_high':
                                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
                            default:
                                return 0;
                        }
                    });
                    
                    // Reorder rows in the table
                    const tbody = productsTable.querySelector('tbody');
                    visibleRows.forEach(row => tbody.appendChild(row));
                }
                
                // Refresh reference to no-data row (might have been added dynamically)
                noDataRow = document.getElementById('no-data-row');
                
                // Show/hide no data message
                if (visibleCount === 0) {
                    if (!noDataRow && productsTable) {
                        const tbody = productsTable.querySelector('tbody');
                        if (tbody) {
                            const newNoDataRow = document.createElement('tr');
                            newNoDataRow.id = 'no-data-row';
                            newNoDataRow.innerHTML = `
                                <td colspan="8" class="text-center py-5">
                                    <div class="no-data-message">
                                        <i class="fas fa-search fa-3x mb-3" style="color: var(--accent);"></i>
                                        <h6 class="fw-normal">No matching products found</h6>
                                        <p class="small mb-0">Try adjusting your search criteria</p>
                                    </div>
                                </td>
                            `;
                            tbody.appendChild(newNoDataRow);
                            noDataRow = newNoDataRow;
                        }
                    } else if (noDataRow) {
                        noDataRow.style.display = '';
                    }
                } else if (noDataRow) {
                    noDataRow.style.display = 'none';
                }
                
                // Update product count
                if (productCount) {
                    productCount.textContent = `Showing ${visibleCount} of ${productRows.length} products`;
                }
                
                // Hide pagination when filtering is active (since we're showing/hiding rows client-side)
                document.querySelectorAll('.pagination').forEach(container => {
                    container.style.display = (searchTerm || categoryId) ? 'none' : '';
                });
            }
            
            // Add event listeners
            if (searchInput) {
                searchInput.addEventListener('input', filterAndSortProducts);
            }
            
            if (searchButton) {
                searchButton.addEventListener('click', filterAndSortProducts);
            }
            
            if (categoryFilter) {
                categoryFilter.addEventListener('change', filterAndSortProducts);
            }
            
            if (sortSelect) {
                sortSelect.addEventListener('change', filterAndSortProducts);
            }
            
            // Image Preview - Add Form
            document.getElementById('image').addEventListener('change', function() {
                previewImage(this, 'preview-image');
            });
            
            // Image Preview - Edit Form
            document.getElementById('edit_image').addEventListener('change', function() {
                previewImage(this, 'edit-preview-image');
            });
            
            // Image preview function
            function previewImage(input, previewId) {
                const preview = document.getElementById(previewId);
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = e => preview.src = e.target.result;
                    reader.readAsDataURL(input.files[0]);
                } else {
                    preview.src = '{{ asset('images/product_placeholder.jpg') }}';
                }
            }
            
            // Generic form submission function
            async function submitForm(form, url, successMessage, modalId) {
                try {
                    const formData = new FormData(form);
                    // Laravel requires CSRF token for security
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                    
                    const response = await fetch(url, { 
                        method: 'POST',
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        bootstrap.Modal.getInstance(document.getElementById(modalId)).hide();
                        alert(successMessage || result.message);
                        location.reload();
                    } else {
                        const errors = result.errors ? Object.values(result.errors).flat().join('\n') : 'Error processing request';
                        alert('Error: ' + errors);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred. Please try again.');
                }
            }
            
            // Add Product Form Submit
            addProductForm.addEventListener('submit', function(e) {
                e.preventDefault();
                submitForm(this, '/products', 'Product added successfully', 'addProductModal');
            });
            
            // Edit Product Form Submit
            editProductForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const productId = document.getElementById('edit_product_id').value;
                const formData = new FormData(this);
                formData.append('_method', 'PUT'); // Laravel requires this for PUT requests
                
                submitForm(this, `/products/${productId}`, 'Product updated successfully', 'editProductModal');
            });
            
            // Load Product for Edit
            document.querySelectorAll('.edit-product').forEach(button => {
                button.addEventListener('click', async function() {
                    try {
                        const product = await fetchProduct(this.dataset.productId);
                        
                        // Set product ID and image
                        document.getElementById('edit_product_id').value = product.id;
                        document.getElementById('edit-preview-image').src = product.image 
                            ? `{{ asset('') }}${product.image}` 
                            : '{{ asset('images/product_placeholder.jpg') }}';
                        
                        // Fill form fields
                        ['name', 'sku', 'category_id', 'price', 'stock', 'reorder_level', 
                         'unit', 'brand', 'model', 'manufacturer', 'description'].forEach(field => {
                            const input = document.getElementById(`edit_${field}`);
                            if (input) input.value = product[field] || '';
                        });
                        
                        // Set status dropdown
                        document.getElementById('edit_is_active').value = product.is_active ? '1' : '0';
                    } catch (error) {
                        alert('Error loading product data');
                    }
                });
            });
            
            // Helper function to fetch product data
            async function fetchProduct(id) {
                const response = await fetch(`/products/${id}`);
                if (!response.ok) throw new Error('Failed to load product');
                return await response.json();
            }
            
            // Delete Product (Mark as Inactive)
            document.querySelectorAll('.delete-product').forEach(button => {
                button.addEventListener('click', async function() {
                    const productId = this.dataset.productId;
                    
                    if (!confirm('Are you sure you want to mark this product as inactive?')) {
                        return;
                    }
                    
                    try {
                        const response = await fetch(`/products/${productId}/toggle-active`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                is_active: false
                            })
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok) {
                            alert(result.message || 'Product marked as inactive');
                            // Update the UI without reloading the page
                            const row = this.closest('tr');
                            const statusCell = row.querySelector('td:nth-child(7)');
                            if (statusCell) {
                                const statusBadge = statusCell.querySelector('.badge');
                                if (statusBadge) {
                                    statusBadge.className = 'badge bg-secondary';
                                    statusBadge.textContent = 'Inactive';
                                }
                            }
                            
                            // Replace the delete button with an activate button
                            const btnGroup = this.closest('.btn-group');
                            if (btnGroup) {
                                this.outerHTML = `
                                    <button class="btn btn-sm btn-outline-success activate-product" title="Activate Product" data-product-id="${productId}">
                                        <i class="fas fa-redo"></i>
                                    </button>
                                `;
                                // Add event listener to the new button
                                btnGroup.querySelector('.activate-product').addEventListener('click', activateProduct);
                            }
                        } else {
                            alert('Error: ' + (result.error || 'Failed to mark product as inactive'));
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred while updating the product.');
                    }
                });
            });
            
            // Activate Product
            function activateProduct() {
                const productId = this.dataset.productId;
                
                if (!confirm('Are you sure you want to activate this product?')) {
                    return;
                }
                
                fetch(`/products/${productId}/toggle-active`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        is_active: true
                    })
                })
                .then(response => response.json())
                .then(result => {
                    if (result.success) {
                        alert(result.message || 'Product activated successfully');
                        
                        // Update the UI without reloading the page
                        const row = this.closest('tr');
                        const statusCell = row.querySelector('td:nth-child(7)');
                        if (statusCell) {
                            const statusBadge = statusCell.querySelector('.badge');
                            if (statusBadge) {
                                statusBadge.className = 'badge bg-success';
                                statusBadge.textContent = 'Active';
                            }
                        }
                        
                        // Replace the activate button with a delete button
                        const btnGroup = this.closest('.btn-group');
                        if (btnGroup) {
                            this.outerHTML = `
                                <button class="btn btn-sm btn-outline-danger delete-product" title="Mark as Inactive" data-product-id="${productId}">
                                    <i class="fas fa-ban"></i>
                                </button>
                            `;
                            // Add event listener to the new button
                            btnGroup.querySelector('.delete-product').addEventListener('click', document.querySelectorAll('.delete-product')[0].onclick);
                        }
                    } else {
                        alert('Error: ' + (result.error || 'Failed to activate product'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while activating the product.');
                });
            }
            
            // Add event listeners to activate buttons
            document.querySelectorAll('.activate-product').forEach(button => {
                button.addEventListener('click', activateProduct);
            });
        });
    </script>

    <!-- Add this to your existing styles section or create a new one -->
    @push('styles')
    <style>
        /* Remove category card styles */
        .product-image-container {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            overflow: hidden;
        }

        .product-row:hover {
            background-color: var(--hover-bg);
        }

        .sort-select {
            width: auto;
            min-width: 200px;
        }

        .no-data-message {
            text-align: center;
            padding: 2rem;
        }

        /* Image preview styles */
        .image-preview {
            width: 100%;
            height: 200px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px dashed var(--border-color);
        }

        .image-preview-container {
            position: relative;
        }

        .image-preview-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
            cursor: pointer;
            border-radius: 8px;
        }

        .image-preview-container:hover .image-preview-overlay {
            opacity: 1;
        }

        /* New styles for full-height layout */
        .container-fluid {
            height: calc(100vh - 100px); /* Increased from 80px to 100px to add more bottom margin */
            display: flex;
            flex-direction: column;
            padding-bottom: 1.5rem; /* Increased padding at bottom */
        }

        .row:not(.mb-4) {
            flex: 1;
            min-height: 0; /* Important for Firefox */
            margin-bottom: 1rem; /* Added margin to bottom of rows */
        }

        .card.mb-4 {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .card-body {
            flex: 1;
            padding: 0;
            overflow: hidden;
            min-height: 0; /* Ensures proper flex behavior */
        }

        .table-responsive {
            height: 100%;
            max-height: none;
        }

        /* Custom scrollbar styles */
        .table-responsive::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .table-responsive::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .table-responsive::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        /* Keep header visible */
        thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 1;
        }
    </style>
    @endpush
</x-dashboard-layout> 