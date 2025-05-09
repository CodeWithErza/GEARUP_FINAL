<x-dashboard-layout :title="'Edit Order #' . $order->order_number" :icon="'fa-solid fa-edit'">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Edit Order #{{ $order->order_number }}</h5>
                        <a href="{{ route('orders.history') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Order History
                        </a>
                    </div>
                    <div class="card-body">
                        <form id="edit-order-form" method="POST" action="{{ route('orders.update', $order->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Order Information</h6>
                                    <div class="mb-3">
                                        <label for="order_status" class="form-label">Order Status</label>
                                        <select class="form-select" id="order_status" name="status">
                                            <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">Payment Method</label>
                                        <select class="form-select" id="payment_method" name="payment_method">
                                            <option value="cash" {{ $order->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="card" {{ $order->payment_method === 'card' ? 'selected' : '' }}>Card</option>
                                            <option value="gcash" {{ $order->payment_method === 'gcash' ? 'selected' : '' }}>GCash</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="fw-bold">Customer Information</h6>
                                    <div class="mb-3">
                                        <label for="customer_name" class="form-label">Customer Name</label>
                                        <input type="text" class="form-control" id="customer_name" name="customer_name" value="{{ $order->customer->name }}" readonly>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="customer_phone" class="form-label">Customer Phone</label>
                                        <input type="text" class="form-control" id="customer_phone" name="customer_phone" value="{{ $order->customer->phone }}" readonly>
                                    </div>
                                </div>
                            </div>
                            
                            <h6 class="fw-bold mb-3">Order Items</h6>
                            <div class="table-responsive mb-4">
                                <table class="table table-bordered" id="order-items-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Product</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                            <td class="text-center">
                                                <input type="number" class="form-control form-control-sm text-center item-quantity" 
                                                    name="items[{{ $item->id }}][quantity]" value="{{ $item->quantity }}" min="1" 
                                                    data-price="{{ $item->price }}" data-item-id="{{ $item->id }}">
                                            </td>
                                            <td class="text-end item-subtotal">₱{{ number_format($item->subtotal, 2) }}</td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-item" data-item-id="{{ $item->id }}">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="3" class="text-end">Subtotal:</td>
                                            <td class="text-end" id="subtotal">₱{{ number_format($order->subtotal, 2) }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">Tax (12%):</td>
                                            <td class="text-end" id="tax">₱{{ number_format($order->tax, 2) }}</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end fw-bold">Total:</td>
                                            <td class="text-end fw-bold" id="total">₱{{ number_format($order->total, 2) }}</td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary" id="add-item-btn">
                                    <i class="fas fa-plus me-1"></i> Add Item
                                </button>
                                <div>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Calculate subtotals when quantity changes
            document.querySelectorAll('.item-quantity').forEach(input => {
                input.addEventListener('change', function() {
                    const price = parseFloat(this.dataset.price);
                    const quantity = parseInt(this.value);
                    const subtotal = price * quantity;
                    
                    // Update the subtotal cell
                    const row = this.closest('tr');
                    row.querySelector('.item-subtotal').textContent = '₱' + subtotal.toFixed(2);
                    
                    // Recalculate order totals
                    calculateTotals();
                });
            });
            
            // Remove item button
            document.querySelectorAll('.remove-item').forEach(button => {
                button.addEventListener('click', function() {
                    if (confirm('Are you sure you want to remove this item?')) {
                        const itemId = this.dataset.itemId;
                        const row = this.closest('tr');
                        
                        // Add a hidden input to mark this item for deletion
                        const deleteInput = document.createElement('input');
                        deleteInput.type = 'hidden';
                        deleteInput.name = `items[${itemId}][_delete]`;
                        deleteInput.value = '1';
                        document.getElementById('edit-order-form').appendChild(deleteInput);
                        
                        // Hide the row
                        row.style.display = 'none';
                        
                        // Recalculate totals
                        calculateTotals();
                    }
                });
            });
            
            // Calculate order totals
            function calculateTotals() {
                let subtotal = 0;
                
                // Sum up visible rows
                document.querySelectorAll('#order-items-table tbody tr:not([style*="display: none"])').forEach(row => {
                    const quantity = parseInt(row.querySelector('.item-quantity').value);
                    const price = parseFloat(row.querySelector('.item-quantity').dataset.price);
                    subtotal += quantity * price;
                });
                
                // Calculate tax and total
                const tax = subtotal * 0.12;
                const total = subtotal + tax;
                
                // Update the totals
                document.getElementById('subtotal').textContent = '₱' + subtotal.toFixed(2);
                document.getElementById('tax').textContent = '₱' + tax.toFixed(2);
                document.getElementById('total').textContent = '₱' + total.toFixed(2);
            }
            
            // Add item button (placeholder for future implementation)
            document.getElementById('add-item-btn').addEventListener('click', function() {
                alert('Adding new items to an existing order will be implemented in a future update.');
            });
        });
    </script>
    @endpush
</x-dashboard-layout> 