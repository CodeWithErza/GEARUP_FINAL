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
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="tooltip" title="Search for suppliers by name, contact person, or code">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Suppliers Table -->
        <div class="row">
            <div class="col-md-12">
                <!-- Notification container -->
                <div class="position-fixed top-0 end-0 p-3" style="z-index: 9999">
                    <div id="notificationToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header">
                            <strong class="me-auto" id="toastTitle">Notification</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body" id="toastMessage">
                            
                        </div>
                    </div>
                </div>
                
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            Suppliers Directory
                            <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="List of all suppliers with contact information and status"></i>
                        </h5>
                        <div>
                            <select class="form-select form-select-sm sort-select" data-bs-toggle="tooltip" title="Change how suppliers are sorted in the list">
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
                                                    <div class="small text-muted">Since {{ $supplier->created_at->format('F Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $supplier->contact_person }}</td>
                                        <td>{{ $supplier->phone }}</td>
                                        <td>{{ $supplier->email }}</td>
                                        <td>{{ number_format($supplier->product_count ?? 0) }}</td>
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
                    <h5 class="modal-title" id="addSupplierModalLabel">
                        <i class="fas fa-truck-loading me-2" style="color: #ffc107;"></i>
                        Add New Supplier
                        <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Add a new supplier to your inventory system"></i>
                    </h5>
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
                    <h5 class="modal-title" id="viewSupplierModalLabel">
                        <i class="fas fa-info-circle me-2" style="color: #ffc107;"></i>
                        Supplier Details
                        <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="View complete information about this supplier"></i>
                    </h5>
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
                                    <h4 class="mb-0"><!-- Supplier name will be loaded dynamically --></h4>
                                    <p class="text-muted mb-0"><!-- Supplier code will be loaded dynamically --></p>
                            </div>
                            </div>
                            <hr>
                            </div>
                            
                        <div class="col-md-6">
                            <div class="card bg-white border border-secondary-subtle shadow-sm mb-3">
                                <div class="card-header bg-white py-2">
                                    <h6 class="card-title mb-0 fw-bold" style="color: #f8f9fa !important;">
                                        <i class="fas fa-id-card me-2" style="color: #ffc107;"></i>
                                        Contact Information
                                        <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Contact details for this supplier"></i>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <div class="text-muted small">Contact Person</div>
                                        <div class="fw-medium text-dark"><!-- Contact person will be loaded dynamically --></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small">Phone</div>
                                        <div class="fw-medium text-dark"><!-- Phone will be loaded dynamically --></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small">Email</div>
                                        <div class="fw-medium text-dark"><!-- Email will be loaded dynamically --></div>
                            </div>
                                    <div>
                                        <div class="text-muted small">Address</div>
                                        <div class="fw-medium text-dark"><!-- Address will be loaded dynamically --></div>
                            </div>
                            </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-white border border-secondary-subtle shadow-sm mb-3">
                                <div class="card-header bg-white py-2">
                                    <h6 class="card-title mb-0 fw-bold" style="color: #f8f9fa !important;">
                                        <i class="fas fa-briefcase me-2" style="color: #ffc107;"></i>
                                        Business Information
                                        <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Business details and account status"></i>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <div class="text-muted small">Supplier Code</div>
                                        <div class="fw-medium text-dark"><!-- Supplier code will be loaded dynamically --></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small">Payment Terms</div>
                                        <div class="fw-medium text-dark"><!-- Payment terms will be loaded dynamically --></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small">Status</div>
                                        <div><span class="badge"><!-- Status will be loaded dynamically --></span></div>
                                    </div>
                                    <div class="mb-2">
                                        <div class="text-muted small">Products Supplied</div>
                                        <div class="fw-medium text-dark"><!-- Product count will be loaded dynamically --></div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            
                        <div class="col-md-12">
                            <div class="card bg-white border border-secondary-subtle shadow-sm">
                                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                                    <h6 class="card-title mb-0 fw-bold" style="color: #f8f9fa !important;">
                                        <i class="fas fa-clipboard-list me-2" style="color: #ffc107;"></i>
                                        Additional Information
                                        <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Notes and additional details about this supplier"></i>
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-0">
                                                <div class="text-muted small mb-1">Notes</div>
                                                <div class="fw-medium text-dark"><!-- Notes will be loaded dynamically --></div>
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
                    <button type="button" class="btn btn-accent" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#editSupplierModal" data-bs-toggle="tooltip" title="Edit this supplier's information">
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
                    <h5 class="modal-title" id="editSupplierModalLabel">
                        <i class="fas fa-edit me-2" style="color: #ffc107;"></i>
                        Edit Supplier
                        <i class="fas fa-info-circle ms-1" data-bs-toggle="tooltip" title="Update supplier information"></i>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <form id="editSupplierForm">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_name" class="form-label mb-1 text-dark">Supplier Name</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_supplier_code" class="form-label mb-1 text-dark">Supplier Code</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_supplier_code" name="supplier_code" placeholder="Auto-generated if left blank">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_contact_person" class="form-label mb-1 text-dark">Contact Person</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_contact_person" name="contact_person" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_position" class="form-label mb-1 text-dark">Position</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_position" name="position">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_phone" class="form-label mb-1 text-dark">Phone</label>
                                <input type="tel" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_phone" name="phone" required>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_email" class="form-label mb-1 text-dark">Email</label>
                                <input type="email" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_email" name="email" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label for="edit_address" class="form-label mb-1 text-dark">Address</label>
                                <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_address" name="address" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_payment_terms" class="form-label mb-1 text-dark">Payment Terms</label>
                                <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_payment_terms" name="payment_terms">
                                    <option value="cod">Cash on Delivery</option>
                                    <option value="15days">15 Days</option>
                                    <option value="30days">30 Days</option>
                                    <option value="60days">60 Days</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_status" class="form-label mb-1 text-dark">Status</label>
                                <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_status" name="status">
                                    <option value="active">Active</option>
                                    <option value="on_hold">On Hold</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_notes" class="form-label mb-1 text-dark">Notes</label>
                            <textarea class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" id="edit_notes" name="notes" rows="3"></textarea>
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

    <!-- Delete Supplier Confirmation Modal -->
    <div class="modal fade" id="deleteSupplierModal" tabindex="-1" aria-labelledby="deleteSupplierModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content bg-light">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="deleteSupplierModalLabel">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Confirm Deletion
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this supplier?</p>
                    <p class="text-danger"><small><strong>Note:</strong> This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteSupplierBtn">Delete Supplier</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize supplier management functionality
            const addSupplierForm = document.getElementById('addSupplierForm');
            const searchInput = document.querySelector('.search-input');
            
            // Function to show notifications
            function showNotification(message, type = 'success') {
                const toast = document.getElementById('notificationToast');
                const toastTitle = document.getElementById('toastTitle');
                const toastMessage = document.getElementById('toastMessage');
                
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
                        showNotification(result.message);
                        
                        // Reload the page to show new data
                        location.reload();
                    } else {
                        const errors = Object.values(result.errors).flat().join('\n');
                        showNotification(errors, 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('An error occurred while saving the supplier. Please try again.', 'error');
                }
            });
            
            // Edit Supplier
            document.querySelectorAll('.edit-supplier').forEach(button => {
                button.addEventListener('click', function() {
                    // Clear previous data before loading new data
                    resetEditSupplierForm();
                    // Then load the supplier for editing
                    loadSupplierForEdit.call(this);
                });
            });
            
            // Update Supplier
            document.getElementById('updateSupplierBtn').addEventListener('click', function() {
                document.getElementById('editSupplierForm').dispatchEvent(new Event('submit'));
            });
            
            document.getElementById('editSupplierForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const supplierId = this.dataset.supplierId;
                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                
                try {
                    const response = await fetch(`/suppliers/${supplierId}`, {
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
                        const modal = bootstrap.Modal.getInstance(document.getElementById('editSupplierModal'));
                        modal.hide();
                        
                        // Show success message
                        showNotification(result.message);
                        
                        // Reload the page to show updated data
                        location.reload();
                    } else {
                        const errors = Object.values(result.errors).flat().join('\n');
                        showNotification(errors, 'error');
                    }
                } catch (error) {
                    console.error('Error updating supplier:', error);
                    showNotification('An error occurred while updating the supplier. Please try again.', 'error');
                }
            });
            
            // Delete Supplier
            document.querySelectorAll('.delete-supplier').forEach(button => {
                button.addEventListener('click', function() {
                    const supplierId = this.dataset.supplierId;
                    const supplierName = this.closest('tr').querySelector('strong').textContent;
                    
                    // Show the delete modal with supplier information
                    const modal = document.getElementById('deleteSupplierModal');
                    const modalBody = modal.querySelector('.modal-body');
                    
                    // Update modal message with supplier name
                    modalBody.innerHTML = `
                        <p>Are you sure you want to delete <strong>${supplierName}</strong>?</p>
                        <p class="text-danger"><small><strong>Note:</strong> This action cannot be undone.</small></p>
                    `;
                    
                    // Set the supplier ID for the confirm delete button
                    document.getElementById('confirmDeleteSupplierBtn').dataset.supplierId = supplierId;
                    
                    // Show the modal
                    const deleteModal = new bootstrap.Modal(modal);
                    deleteModal.show();
                });
            });
            
            // Handle delete confirmation
            document.getElementById('confirmDeleteSupplierBtn').addEventListener('click', async function() {
                const supplierId = this.dataset.supplierId;
                const modal = bootstrap.Modal.getInstance(document.getElementById('deleteSupplierModal'));
                
                try {
                    const response = await fetch(`/suppliers/${supplierId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    
                    const result = await response.json();
                    
                    // Hide the modal
                    modal.hide();
                    
                    if (response.ok) {
                        showNotification(result.message);
                        
                        // Fade out and remove the deleted supplier row
                        const supplierRow = document.querySelector(`button[data-supplier-id="${supplierId}"]`).closest('tr');
                        supplierRow.style.transition = 'opacity 0.5s';
                        supplierRow.style.opacity = '0';
                        
                        // After fade out, remove the row and reload page
                        setTimeout(() => {
                            supplierRow.remove();
                            location.reload();
                        }, 500);
                    } else {
                        showNotification('Error deleting supplier', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting supplier:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                    modal.hide();
                }
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
                                            <div class="small text-muted">Since ${new Date(supplier.created_at).toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>${supplier.contact_person}</td>
                                <td>${supplier.phone}</td>
                                <td>${supplier.email}</td>
                                <td>${(supplier.product_count || 0).toLocaleString()}</td>
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
                    button.addEventListener('click', function() {
                        // Clear previous data before loading new data
                        resetSupplierDetailsModal();
                        // Then load the new supplier details
                        loadSupplierDetails.call(this);
                    });
                });
                
                document.querySelectorAll('.edit-supplier').forEach(button => {
                    button.addEventListener('click', function() {
                        // Clear previous data before loading new data
                        resetEditSupplierForm();
                        // Then load the supplier for editing
                        loadSupplierForEdit.call(this);
                    });
                });
                
                document.querySelectorAll('.delete-supplier').forEach(button => {
                    button.addEventListener('click', function() {
                        const supplierId = this.dataset.supplierId;
                        const supplierName = this.closest('tr').querySelector('strong').textContent;
                        
                        // Show the delete modal with supplier information
                        const modal = document.getElementById('deleteSupplierModal');
                        const modalBody = modal.querySelector('.modal-body');
                        
                        // Update modal message with supplier name
                        modalBody.innerHTML = `
                            <p>Are you sure you want to delete <strong>${supplierName}</strong>?</p>
                            <p class="text-danger"><small><strong>Note:</strong> This action cannot be undone.</small></p>
                        `;
                        
                        // Set the supplier ID for the confirm delete button
                        document.getElementById('confirmDeleteSupplierBtn').dataset.supplierId = supplierId;
                        
                        // Show the modal
                        const deleteModal = new bootstrap.Modal(modal);
                        deleteModal.show();
                    });
                });
            }
            
            // Initial attachment of event listeners
            attachEventListeners();
            
            // Function to reset the edit supplier form
            function resetEditSupplierForm() {
                const form = document.getElementById('editSupplierForm');
                form.reset();
                
                // Add loading indicators for key fields
                document.getElementById('edit_name').value = 'Loading...';
                document.getElementById('edit_supplier_code').value = 'Loading...';
                document.getElementById('edit_contact_person').value = 'Loading...';
                document.getElementById('edit_phone').value = 'Loading...';
                document.getElementById('edit_email').value = 'Loading...';
            }
            
            // Function to reset the supplier details modal
            function resetSupplierDetailsModal() {
                const modal = document.getElementById('viewSupplierModal');
                
                // Reset supplier name and description
                modal.querySelector('h4').textContent = 'Loading...';
                modal.querySelector('p.text-muted').textContent = 'Loading supplier information';
                
                // Reset contact information
                const contactInfo = modal.querySelectorAll('.card-body .fw-medium.text-dark');
                for (let i = 0; i < 4; i++) {
                    if (contactInfo[i]) contactInfo[i].textContent = 'Loading...';
                }
                
                // Reset business information
                const businessInfo = modal.querySelectorAll('.card-body .fw-medium.text-dark, .card-body .badge');
                if (businessInfo[4]) businessInfo[4].textContent = 'Loading...';
                if (businessInfo[5]) businessInfo[5].textContent = 'Loading...';
                
                // Reset status badge
                const statusBadge = businessInfo[6];
                if (statusBadge) {
                    statusBadge.className = 'badge bg-secondary';
                    statusBadge.textContent = 'Loading...';
                }
                
                // Reset product count
                if (businessInfo[7]) businessInfo[7].textContent = 'Loading...';
                
                // Reset notes
                const notesElement = modal.querySelector('.col-md-12 .card:last-child .card-body .fw-medium.text-dark');
                if (notesElement) notesElement.textContent = 'Loading...';
            }
            
            // Function to load supplier details
            async function loadSupplierDetails() {
                const supplierId = this.dataset.supplierId;
                
                try {
                    const response = await fetch(`/suppliers/${supplierId}`);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const supplier = await response.json();
                    
                    // Log the supplier data to debug
                    console.log('Supplier data:', supplier);
                    
                    // Update the supplier details modal with actual data
                    const modal = document.getElementById('viewSupplierModal');
                    
                    // Update supplier name and description
                    modal.querySelector('h4').textContent = supplier.name;
                    modal.querySelector('p.text-muted').textContent = `Supplier code: ${supplier.supplier_code}`;
                    
                    // Update contact information
                    const contactInfo = modal.querySelectorAll('.card-body .fw-medium.text-dark');
                    contactInfo[0].textContent = supplier.contact_person + (supplier.position ? ` (${supplier.position})` : '');
                    contactInfo[1].textContent = supplier.phone;
                    contactInfo[2].textContent = supplier.email;
                    contactInfo[3].textContent = supplier.address;
                    
                    // Update business information
                    const businessInfo = modal.querySelectorAll('.card-body .fw-medium.text-dark, .card-body .badge');
                    businessInfo[4].textContent = supplier.supplier_code;
                    
                    // Format payment terms for display
                    let paymentTermsText = '';
                    switch(supplier.payment_terms) {
                        case 'cod': paymentTermsText = 'Cash on Delivery'; break;
                        case '15days': paymentTermsText = '15 Days'; break;
                        case '30days': paymentTermsText = '30 Days'; break;
                        case '60days': paymentTermsText = '60 Days'; break;
                        default: paymentTermsText = supplier.payment_terms;
                    }
                    businessInfo[5].textContent = paymentTermsText;
                    
                    // Update status badge
                    const statusBadge = businessInfo[6];
                    statusBadge.className = `badge bg-${supplier.status === 'active' ? 'success' : (supplier.status === 'on_hold' ? 'warning' : 'secondary')}`;
                    statusBadge.textContent = supplier.status === 'on_hold' ? 'On Hold' : supplier.status.charAt(0).toUpperCase() + supplier.status.slice(1);
                    
                    // Update product count
                    if (businessInfo[7]) {
                        businessInfo[7].textContent = (supplier.product_count || 0).toLocaleString();
                    }
                    
                    // Update notes
                    const notesElement = modal.querySelector('.col-md-12 .card:last-child .card-body .fw-medium.text-dark');
                    console.log('Notes element:', notesElement);
                    console.log('Supplier notes:', supplier.notes);
                    notesElement.textContent = supplier.notes || 'No notes available';
                    
                    // Set the supplier ID for the edit button
                    const editBtn = modal.querySelector('.modal-footer .btn-accent');
                    editBtn.dataset.supplierId = supplier.id;
                    
                } catch (error) {
                    console.error('Error loading supplier details:', error);
                    showNotification('Failed to load supplier details. Please try again.', 'error');
                    
                    // Keep the loading state in case of error
                    const modal = document.getElementById('viewSupplierModal');
                    modal.querySelector('h4').textContent = 'Error loading data';
                    modal.querySelector('p.text-muted').textContent = 'Please try again';
                }
            }
            
            // Function to load supplier for editing
            async function loadSupplierForEdit() {
                const supplierId = this.dataset.supplierId;
                
                try {
                    const response = await fetch(`/suppliers/${supplierId}`);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    
                    const supplier = await response.json();
                    
                    // Fill the edit form with supplier data
                    document.getElementById('edit_name').value = supplier.name;
                    document.getElementById('edit_supplier_code').value = supplier.supplier_code;
                    document.getElementById('edit_contact_person').value = supplier.contact_person;
                    document.getElementById('edit_position').value = supplier.position || '';
                    document.getElementById('edit_phone').value = supplier.phone;
                    document.getElementById('edit_email').value = supplier.email;
                    document.getElementById('edit_address').value = supplier.address;
                    document.getElementById('edit_payment_terms').value = supplier.payment_terms;
                    document.getElementById('edit_status').value = supplier.status;
                    document.getElementById('edit_notes').value = supplier.notes || '';
                    
                    // Store supplier ID for update
                    document.getElementById('editSupplierForm').dataset.supplierId = supplierId;
                } catch (error) {
                    console.error('Error loading supplier data:', error);
                    showNotification('Error loading supplier data. Please try again.', 'error');
                    
                    // Reset the form completely if there was an error
                    const form = document.getElementById('editSupplierForm');
                    form.reset();
                }
            }
            
            // Function to delete supplier
            async function deleteSupplier() {
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
                        showNotification(result.message);
                        location.reload();
                    } else {
                        showNotification('Error deleting supplier', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting supplier:', error);
                    showNotification('An error occurred. Please try again.', 'error');
                }
            }
            
            // Initialize tooltips
            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
            
            // Add event listener for the sort dropdown
            const sortSelect = document.querySelector('.sort-select');
            if (sortSelect) {
                sortSelect.addEventListener('change', async function() {
                    const sortValue = this.value;
                    let suppliers = [];
                    
                    try {
                        // First, fetch all suppliers
                        const response = await fetch('/suppliers-data/all');
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        
                        const data = await response.json();
                        suppliers = data;
                        
                        // Sort suppliers based on the selected option
                        if (sortValue === 'name_asc') {
                            suppliers.sort((a, b) => a.name.localeCompare(b.name));
                        } else if (sortValue === 'name_desc') {
                            suppliers.sort((a, b) => b.name.localeCompare(a.name));
                        } else if (sortValue === 'recent') {
                            suppliers.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                        } else if (sortValue === 'products') {
                            suppliers.sort((a, b) => (b.product_count || 0) - (a.product_count || 0));
                        }
                        
                        // Update the table with sorted suppliers
                        const tbody = document.querySelector('.data-table tbody');
                        tbody.innerHTML = suppliers.map(supplier => `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div>
                                            <strong>${supplier.name}</strong>
                                            <div class="small text-muted">Since ${new Date(supplier.created_at).toLocaleDateString('en-US', { month: 'long', year: 'numeric' })}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>${supplier.contact_person}</td>
                                <td>${supplier.phone}</td>
                                <td>${supplier.email}</td>
                                <td>${(supplier.product_count || 0).toLocaleString()}</td>
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
                        console.error('Error sorting suppliers:', error);
                        showNotification('Error sorting suppliers. Please try again.', 'error');
                    }
                });
            }
            
            // Re-initialize tooltips after dynamic content is loaded
            const viewSupplierModal = document.getElementById('viewSupplierModal');
            viewSupplierModal.addEventListener('shown.bs.modal', function() {
                const modalTooltips = viewSupplierModal.querySelectorAll('[data-bs-toggle="tooltip"]');
                [...modalTooltips].forEach(tooltipTrigger => new bootstrap.Tooltip(tooltipTrigger));
            });
            
            const editSupplierModal = document.getElementById('editSupplierModal');
            editSupplierModal.addEventListener('shown.bs.modal', function() {
                const modalTooltips = editSupplierModal.querySelectorAll('[data-bs-toggle="tooltip"]');
                [...modalTooltips].forEach(tooltipTrigger => new bootstrap.Tooltip(tooltipTrigger));
            });
        });
    </script>
</x-dashboard-layout> 