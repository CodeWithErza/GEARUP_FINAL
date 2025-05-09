<x-dashboard-layout :title="'Supplier Management'" :icon="'fa-solid fa-truck'">
    <div class="container-fluid">
        <!-- Action Buttons -->
        <div class="row mb-4">
            <div class="col-md-6">
                <button type="button" class="btn btn-accent" data-bs-toggle="modal" data-bs-target="#addSupplierModal">
                    <i class="fas fa-plus"></i> Add New Supplier
                </button>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control search-input" placeholder="Search suppliers...">
                    <button class="btn btn-outline-secondary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Suppliers Table -->
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Suppliers Directory</h5>
                        <div>
                            <select class="form-select form-select-sm sort-select">
                                <option value="name_asc">Name (A-Z)</option>
                                <option value="name_desc">Name (Z-A)</option>
                                <option value="recent">Most Recent</option>
                                <option value="products">Most Products</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle data-table">
                                <thead>
                                    <tr>
                                        <th scope="col">Supplier</th>
                                        <th scope="col">Contact Person</th>
                                        <th scope="col">Phone</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Products</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($suppliers as $supplier)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div>
                                                    <strong>{{ $supplier->name }}</strong>
                                                    <div class="small text-muted">Since {{ $supplier->created_at->format('M Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $supplier->contact_person }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>0</td>
                                        <td><span class="badge bg-{{ $supplier->status === 'active' ? 'success' : ($supplier->status === 'on_hold' ? 'warning' : 'secondary') }}">{{ $supplier->status === 'on_hold' ? 'On Hold' : ucfirst($supplier->status) }}</span></td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <button class="btn btn-sm btn-outline-secondary view-supplier" title="View Details" data-bs-toggle="modal" data-bs-target="#viewSupplierModal" data-supplier-id="{{ $supplier->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-primary edit-supplier" title="Edit Supplier" data-bs-toggle="modal" data-bs-target="#editSupplierModal" data-supplier-id="{{ $supplier->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger delete-supplier" title="Delete Supplier" data-supplier-id="{{ $supplier->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Showing {{ $suppliers->firstItem() ?? 0 }} to {{ $suppliers->lastItem() ?? 0 }} of {{ $suppliers->total() ?? 0 }} suppliers</span>
                            {{ $suppliers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Supplier Modal -->
    <div class="modal fade" id="addSupplierModal" tabindex="-1" aria-labelledby="addSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-light">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="addSupplierModalLabel"><i class="fas fa-truck-loading me-2" style="color: #ffc107;"></i>Add New Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <form id="addSupplierForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label mb-1 text-dark">Supplier Name</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="supplier_code" class="form-label mb-1 text-dark">Supplier Code</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="supplier_code" name="supplier_code" placeholder="Auto-generated if left blank">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="contact_person" class="form-label mb-1 text-dark">Contact Person</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="contact_person" name="contact_person" required>
                            </div>
                            <div class="col-md-6">
                                <label for="position" class="form-label mb-1 text-dark">Position</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="position" name="position">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label mb-1 text-dark">Phone</label>
                                <input type="tel" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="phone" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label mb-1 text-dark">Email</label>
                                <input type="email" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="address" class="form-label mb-1 text-dark">Address</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="address" name="address" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="payment_terms" class="form-label mb-1 text-dark">Payment Terms</label>
                                <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" id="payment_terms" name="payment_terms">
                                    <option value="cod">Cash on Delivery</option>
                                    <option value="15days">15 Days</option>
                                    <option value="30days">30 Days</option>
                                    <option value="60days">60 Days</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label mb-1 text-dark">Status</label>
                                <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" id="status" name="status">
                                    <option value="active" selected>Active</option>
                                    <option value="on_hold">On Hold</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label mb-1 text-dark">Notes</label>
                            <textarea class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addSupplierForm" class="btn btn-accent">Save Supplier</button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Supplier Modal -->
    <div class="modal fade" id="viewSupplierModal" tabindex="-1" aria-labelledby="viewSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-light">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="viewSupplierModalLabel"><i class="fas fa-info-circle me-2" style="color: #ffc107;"></i>Supplier Details</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="fas fa-building fa-lg" style="color: #6c757d;"></i>
                            </div>
                                <div>
                                    <h4 class="mb-0">ABC Auto Parts</h4>
                                    <p class="text-muted mb-0">Supplier of quality automotive parts since 2020</p>
                            </div>
                            </div>
                            <hr>
                            </div>
                            
                        <div class="col-md-6">
                            <div class="card bg-white border border-secondary-subtle shadow-sm mb-3">
                                <div class="card-header bg-white py-2">
                                    <h6 class="card-title mb-0 fw-bold" style="color: #f8f9fa !important;"><i class="fas fa-id-card me-2" style="color: #ffc107;"></i>Contact Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <div class="text-muted small">Contact Person</div>
                                        <div class="fw-medium text-dark">John Smith (General Manager)</div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small">Phone</div>
                                        <div class="fw-medium text-dark">+63 912 345 6789</div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small">Email</div>
                                        <div class="fw-medium text-dark">johnsmith@abcautoparts.com</div>
                            </div>
                                    <div>
                                        <div class="text-muted small">Address</div>
                                        <div class="fw-medium text-dark">123 Main Street, Makati City, Metro Manila, Philippines</div>
                            </div>
                            </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-white border border-secondary-subtle shadow-sm mb-3">
                                <div class="card-header bg-white py-2">
                                    <h6 class="card-title mb-0 fw-bold" style="color: #f8f9fa !important;"><i class="fas fa-briefcase me-2" style="color: #ffc107;"></i>Business Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <div class="text-muted small">Supplier Code</div>
                                        <div class="fw-medium text-dark">SUP-001</div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small">Payment Terms</div>
                                        <div class="fw-medium text-dark">30 Days</div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small">Status</div>
                                        <div><span class="badge bg-success">Active</span></div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            
                        <div class="col-md-12">
                            <div class="card bg-white border border-secondary-subtle shadow-sm">
                                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                    <h6 class="card-title mb-0 fw-bold" style="color: #f8f9fa !important;"><i class="fas fa-clipboard-list me-2" style="color: #ffc107;"></i>Additional Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-0">
                                                <div class="text-muted small mb-1">Notes</div>
                                                <div class="fw-medium text-dark">Reliable supplier with consistent quality and on-time deliveries.</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-accent" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editSupplierModal">
                        <i class="fas fa-edit me-1"></i> Edit Supplier
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Supplier Modal (Same fields as Add Supplier Modal but pre-filled) -->
    <div class="modal fade" id="editSupplierModal" tabindex="-1" aria-labelledby="editSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-light">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="editSupplierModalLabel"><i class="fas fa-edit me-2" style="color: #ffc107;"></i>Edit Supplier</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <form id="editSupplierForm">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="supplierName" class="form-label mb-1 text-dark">Supplier Name</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="supplierName" required>
                            </div>
                            <div class="col-md-6">
                                <label for="supplierCode" class="form-label mb-1 text-dark">Supplier Code</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="supplierCode" placeholder="Auto-generated if left blank">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="contactPerson" class="form-label mb-1 text-dark">Contact Person</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="contactPerson" required>
                            </div>
                            <div class="col-md-6">
                                <label for="position" class="form-label mb-1 text-dark">Position</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="position">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="phone" class="form-label mb-1 text-dark">Phone</label>
                                <input type="tel" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label mb-1 text-dark">Email</label>
                                <input type="email" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="email" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="address" class="form-label mb-1 text-dark">Address</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="address" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="paymentTerms" class="form-label mb-1 text-dark">Payment Terms</label>
                                <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" id="paymentTerms">
                                    <option value="cod">Cash on Delivery</option>
                                    <option value="15days">15 Days</option>
                                    <option value="30days">30 Days</option>
                                    <option value="60days">60 Days</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="status" class="form-label mb-1 text-dark">Status</label>
                                <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" id="status">
                                    <option value="active" selected>Active</option>
                                    <option value="on_hold">On Hold</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label mb-1 text-dark">Notes</label>
                            <textarea class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-accent" id="updateSupplierBtn">Update Supplier</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize supplier management functionality
            const addSupplierForm = document.getElementById('addSupplierForm');
            const searchInput = document.querySelector('.search-input');
            
            // Add Supplier Form Submit
            addSupplierForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const formData = new FormData(this);
                    
                    const response = await fetch('/suppliers', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: formData
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        // Close the modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('addSupplierModal'));
                        modal.hide();
                        
                        // Show success message
                        alert(result.message);
                        
                        // Reload the page to show new data
                        location.reload();
                    } else {
                        const errors = Object.values(result.errors).flat().join('\n');
                        alert('Error: ' + errors);
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while saving the supplier. Please try again.');
                }
            });
            
            // Edit Supplier
            document.querySelectorAll('.edit-supplier').forEach(button => {
                button.addEventListener('click', async function() {
                    const supplierId = this.dataset.supplierId;
                    
                    try {
                        const response = await fetch(`/suppliers/${supplierId}`);
                        const supplier = await response.json();
                        
                        // Fill the edit form with supplier data
                        const editForm = document.getElementById('editSupplierForm');
                        for (const [key, value] of Object.entries(supplier)) {
                            const input = editForm.querySelector(`[name="${key}"]`);
                            if (input) input.value = value;
                        }
                        
                        editForm.dataset.supplierId = supplierId;
                    } catch (error) {
                        alert('Error loading supplier data');
                    }
                });
            });
            
            // Update Supplier
            document.getElementById('editSupplierForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const supplierId = this.dataset.supplierId;
                const formData = new FormData(this);
                const data = Object.fromEntries(formData.entries());
                
                try {
                    const response = await fetch(`/suppliers/${supplierId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify(data)
                    });
                    
                    const result = await response.json();
                    
                    if (response.ok) {
                        alert(result.message);
                        location.reload();
                    } else {
                        const errors = Object.values(result.errors).flat().join('\n');
                        alert('Error: ' + errors);
                    }
                } catch (error) {
                    alert('An error occurred. Please try again.');
                }
            });
            
            // Delete Supplier
            document.querySelectorAll('.delete-supplier').forEach(button => {
                button.addEventListener('click', async function() {
                    if (!confirm('Are you sure you want to delete this supplier? This action cannot be undone.')) {
                        return;
                    }
                    
                    const supplierId = this.dataset.supplierId;
                    
                    try {
                        const response = await fetch(`/suppliers/${supplierId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            }
                        });
                        
                        const result = await response.json();
                        
                        if (response.ok) {
                            alert(result.message);
                            location.reload();
                        } else {
                            alert('Error deleting supplier');
                        }
                    } catch (error) {
                        alert('An error occurred. Please try again.');
                    }
                });
            });
            
            // Search Functionality
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(async () => {
                    const query = this.value;
                    
                    try {
                        const response = await fetch(`/suppliers/search?query=${encodeURIComponent(query)}`);
                        const suppliers = await response.json();
                        
                        // Update the table with search results
                        const tbody = document.querySelector('.data-table tbody');
                        tbody.innerHTML = suppliers.map(supplier => `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong>${supplier.name}</strong>
                                            <div class="small text-muted">Since ${new Date(supplier.created_at).toLocaleDateString('en-US', { month: 'short', year: 'numeric' })}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>${supplier.contact_person}</td>
                                <td>${supplier.phone}</td>
                                <td>${supplier.email}</td>
                                <td>0</td>
                                <td><span class="badge bg-${supplier.status === 'active' ? 'success' : (supplier.status === 'on_hold' ? 'warning' : 'secondary')}">${supplier.status === 'on_hold' ? 'On Hold' : supplier.status.charAt(0).toUpperCase() + supplier.status.slice(1)}</span></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-sm btn-outline-secondary view-supplier" title="View Details" data-bs-toggle="modal" data-bs-target="#viewSupplierModal" data-supplier-id="${supplier.id}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary edit-supplier" title="Edit Supplier" data-bs-toggle="modal" data-bs-target="#editSupplierModal" data-supplier-id="${supplier.id}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-supplier" title="Delete Supplier" data-supplier-id="${supplier.id}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `).join('');
                        
                        // Reattach event listeners to new buttons
                        attachEventListeners();
                    } catch (error) {
                        console.error('Error searching suppliers:', error);
                    }
                }, 300);
            });
            
            function attachEventListeners() {
                // Reattach event listeners for view, edit, and delete buttons
                document.querySelectorAll('.view-supplier').forEach(button => {
                    button.addEventListener('click', loadSupplierDetails);
                });
                
                document.querySelectorAll('.edit-supplier').forEach(button => {
                    button.addEventListener('click', loadSupplierForEdit);
                });
                
                document.querySelectorAll('.delete-supplier').forEach(button => {
                    button.addEventListener('click', deleteSupplier);
                });
            }
            
            // Initial attachment of event listeners
            attachEventListeners();
        });
    </script>
</x-dashboard-layout> 