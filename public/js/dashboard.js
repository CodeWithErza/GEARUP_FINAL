/**
 * Dashboard Sales Order System
 * JavaScript functionality for barcode scanning, product selection, and order processing
 */

document.addEventListener('DOMContentLoaded', function() {
    // Update current date and time
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Initialize sales order functionality if on orders page
    if (document.getElementById('barcodeInput')) {
        initSalesOrderSystem();
    }
});

/**
 * Updates the current date and time display
 */
function updateDateTime() {
    const now = new Date();
    const dateTimeElement = document.getElementById('currentDateTime');
    
    if (dateTimeElement) {
        const options = { 
            weekday: 'long', 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        };
        
        dateTimeElement.textContent = now.toLocaleDateString('en-US', options);
    }
}

/**
 * Initializes the sales order system functionality
 */
function initSalesOrderSystem() {
    // Product catalog - simulated data
    // In a real application, this would come from an API/database
    const productCatalog = [
        { id: 'P001', name: 'Engine Oil', price: 350, icon: 'oil-can', category: 'engine' },
        { id: 'P002', name: 'Battery', price: 2450, icon: 'car-battery', category: 'electrical' },
        { id: 'P003', name: 'Air Filter', price: 250, icon: 'filter', category: 'filters' },
        { id: 'P004', name: 'Brake Pads', price: 750, icon: 'cogs', category: 'brakes' },
        { id: 'P005', name: 'Spark Plugs', price: 120, icon: 'bolt', category: 'electrical' },
        { id: 'P006', name: 'Coolant', price: 280, icon: 'tint', category: 'engine' },
        { id: 'P007', name: 'Wiper Blades', price: 350, icon: 'brush', category: 'exterior' },
        { id: 'P008', name: 'Headlight Bulb', price: 180, icon: 'lightbulb', category: 'electrical' },
    ];
    
    // Barcode to product mapping (simulated)
    const barcodeMap = {
        '1234567890': 'P001',
        '2345678901': 'P002',
        '3456789012': 'P003',
        '4567890123': 'P004',
        '5678901234': 'P005',
        '6789012345': 'P006',
        '7890123456': 'P007',
        '8901234567': 'P008',
    };

    // Current order items
    let orderItems = [];
    
    // Event listeners for "Add to Order" buttons on product items
    document.querySelectorAll('.add-to-order').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.getAttribute('data-id');
            addProductToOrder(productId);
        });
    });
    
    // Process the barcode after scanning or manual entry
    function searchBarcode(barcode) {
        const productId = barcodeMap[barcode];
        
        if (productId) {
            addProductToOrder(productId);
            return true;
        } else {
            // Show alert for unrecognized barcode
            alert('Product not found for barcode: ' + barcode);
            return false;
        }
    }
    
    // Manual barcode search button
    const searchBarcodeBtn = document.getElementById('searchBarcode');
    if (searchBarcodeBtn) {
        searchBarcodeBtn.addEventListener('click', function() {
            const barcodeInput = document.getElementById('barcodeInput');
            if (barcodeInput.value) {
                searchBarcode(barcodeInput.value);
            }
        });
    }
    
    // Barcode input keypress handling
    const barcodeInput = document.getElementById('barcodeInput');
    if (barcodeInput) {
        barcodeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                if (this.value) {
                    searchBarcode(this.value);
                }
            }
        });
    }
    
    // Function to add product to order
    function addProductToOrder(productId) {
        const product = productCatalog.find(p => p.id === productId);
        
        if (!product) {
            alert('Product not found!');
            return;
        }
        
        // Check if product already exists in order
        const existingItem = orderItems.find(item => item.id === productId);
        
        if (existingItem) {
            // Increment quantity if already in order
            existingItem.quantity++;
            existingItem.total = existingItem.quantity * existingItem.price;
            
            // Update the existing row
            const row = document.querySelector(`tr[data-product-id="${productId}"]`);
            if (row) {
                row.querySelector('.quantity-input').value = existingItem.quantity;
                row.querySelector('td:nth-child(4)').textContent = `₱${existingItem.total.toFixed(2)}`;
            }
        } else {
            // Add new item to order
            const newItem = {
                id: product.id,
                name: product.name,
                price: product.price,
                quantity: 1,
                total: product.price,
                icon: product.icon
            };
            
            orderItems.push(newItem);
            
            // Add new row to order items table
            addItemRow(newItem);
        }
        
        // Hide empty order message if visible
        document.getElementById('emptyOrderMessage').style.display = 'none';
        
        // Update order totals
        updateOrderTotals();
        
        // Clear barcode input
        if (barcodeInput) {
            barcodeInput.value = '';
            barcodeInput.focus();
        }
    }
    
    // Function to add item row to the table
    function addItemRow(item) {
        const tbody = document.getElementById('orderItemsList');
        
        // Create new row
        const row = document.createElement('tr');
        row.setAttribute('data-product-id', item.id);
        
        // Row content
        row.innerHTML = `
            <td class="ps-3">
                <div class="product-name">
                    <div class="product-img">
                        <i class="fas fa-${item.icon}" style="color: var(--accent);"></i>
                    </div>
                    <span>${item.name}</span>
                </div>
            </td>
            <td>₱${item.price.toFixed(2)}</td>
            <td>
                <div class="quantity-control d-flex">
                    <button class="btn btn-sm btn-dark px-2 quantity-btn" data-action="decrease">-</button>
                    <input type="number" class="form-control form-control-sm mx-2 text-center quantity-input" value="${item.quantity}" min="1">
                    <button class="btn btn-sm btn-dark px-2 quantity-btn" data-action="increase">+</button>
                </div>
            </td>
            <td class="text-end">₱${item.total.toFixed(2)}</td>
            <td class="text-center">
                <button class="btn btn-sm text-danger remove-item">
                    <i class="fas fa-times"></i>
                </button>
            </td>
        `;
        
        // Add event listeners for the new row
        row.querySelector('.quantity-btn[data-action="increase"]').addEventListener('click', function() {
            updateItemQuantity(item.id, 1);
        });
        
        row.querySelector('.quantity-btn[data-action="decrease"]').addEventListener('click', function() {
            updateItemQuantity(item.id, -1);
        });
        
        row.querySelector('.quantity-input').addEventListener('change', function() {
            updateItemQuantityDirect(item.id, parseInt(this.value));
        });
        
        row.querySelector('.remove-item').addEventListener('click', function() {
            removeItemFromOrder(item.id);
        });
        
        // Add row to table before the empty message row
        const emptyMessage = document.getElementById('emptyOrderMessage');
        tbody.insertBefore(row, emptyMessage);
    }
    
    // Function to update item quantity
    function updateItemQuantity(productId, change) {
        const item = orderItems.find(item => item.id === productId);
        if (item) {
            const newQuantity = item.quantity + change;
            if (newQuantity > 0) {
                updateItemQuantityDirect(productId, newQuantity);
            }
        }
    }
    
    // Update quantity directly with a specific value
    function updateItemQuantityDirect(productId, newQuantity) {
        if (newQuantity < 1) newQuantity = 1;
        
        const item = orderItems.find(item => item.id === productId);
        if (item) {
            item.quantity = newQuantity;
            item.total = item.price * newQuantity;
            
            // Update row display
            const row = document.querySelector(`tr[data-product-id="${productId}"]`);
            if (row) {
                row.querySelector('.quantity-input').value = newQuantity;
                row.querySelector('td:nth-child(4)').textContent = `₱${item.total.toFixed(2)}`;
            }
            
            updateOrderTotals();
        }
    }
    
    // Remove item from order
    function removeItemFromOrder(productId) {
        // Remove from array
        orderItems = orderItems.filter(item => item.id !== productId);
        
        // Remove row from table
        const row = document.querySelector(`tr[data-product-id="${productId}"]`);
        if (row) {
            row.remove();
        }
        
        // Check if order is now empty
        if (orderItems.length === 0) {
            document.getElementById('emptyOrderMessage').style.display = 'table-row';
        }
        
        updateOrderTotals();
    }
    
    // Update order totals
    function updateOrderTotals() {
        // Calculate subtotal
        const subtotal = orderItems.reduce((sum, item) => sum + item.total, 0);
        
        // Calculate tax
        const taxRate = 0.12; // 12% tax rate
        const tax = subtotal * taxRate;
        
        // Get discount
        const discountInput = document.getElementById('discountInput');
        const discountType = document.getElementById('discountType');
        let discount = 0;
        
        if (discountInput && discountInput.value) {
            if (discountType.value === 'percent') {
                discount = subtotal * (parseInt(discountInput.value) / 100);
            } else {
                discount = parseInt(discountInput.value);
            }
        }
        
        // Calculate total
        const total = subtotal + tax - discount;
        
        // Update display
        document.getElementById('subtotalAmount').textContent = `₱${subtotal.toFixed(2)}`;
        document.getElementById('taxAmount').textContent = `₱${tax.toFixed(2)}`;
        document.getElementById('totalAmount').textContent = `₱${total.toFixed(2)}`;
    }
    
    // Clear order button
    const clearOrderBtn = document.getElementById('clearOrder');
    if (clearOrderBtn) {
        clearOrderBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear the current order?')) {
                orderItems = [];
                
                // Remove all rows except empty message
                const tbody = document.getElementById('orderItemsList');
                Array.from(tbody.children).forEach(child => {
                    if (child.id !== 'emptyOrderMessage') {
                        child.remove();
                    }
                });
                
                // Show empty message
                document.getElementById('emptyOrderMessage').style.display = 'table-row';
                
                // Reset totals
                updateOrderTotals();
                
                // Reset discount
                if (document.getElementById('discountInput')) {
                    document.getElementById('discountInput').value = 0;
                }
            }
        });
    }
    
    // Discount input changes
    const discountInput = document.getElementById('discountInput');
    const discountType = document.getElementById('discountType');
    
    if (discountInput && discountType) {
        discountInput.addEventListener('input', updateOrderTotals);
        discountType.addEventListener('change', updateOrderTotals);
    }
    
    // Process order button
    const processOrderBtn = document.getElementById('processOrder');
    if (processOrderBtn) {
        processOrderBtn.addEventListener('click', function() {
            if (orderItems.length === 0) {
                alert('Please add items to the order first.');
                return;
            }
            
            // Here you could navigate to a checkout page or process the order
            alert('Processing order...');
        });
    }
    
    // Complete order button
    const completeOrderBtn = document.getElementById('completeOrder');
    if (completeOrderBtn) {
        completeOrderBtn.addEventListener('click', function() {
            if (orderItems.length === 0) {
                alert('Please add items to the order first.');
                return;
            }
            
            const customerName = document.getElementById('customerName').value;
            if (!customerName) {
                alert('Please enter customer information.');
                document.getElementById('customerName').focus();
                return;
            }
            
            // In a real application, this would submit the order to the server
            alert(`Order completed for ${customerName}!`);
            
            // Reset the form
            clearOrder();
            document.getElementById('customerName').value = '';
            document.getElementById('customerPhone').value = '';
            document.getElementById('customerEmail').value = '';
            document.getElementById('vehicleMake').value = '';
            document.getElementById('vehicleYear').value = '';
            document.getElementById('licensePlate').value = '';
            document.getElementById('orderNotes').value = '';
            document.getElementById('discountInput').value = 0;
        });
    }
    
    // Clear whole order
    function clearOrder() {
        orderItems = [];
        
        // Remove all rows except empty message
        const tbody = document.getElementById('orderItemsList');
        Array.from(tbody.children).forEach(child => {
            if (child.id !== 'emptyOrderMessage') {
                child.remove();
            }
        });
        
        // Show empty message
        document.getElementById('emptyOrderMessage').style.display = 'table-row';
        
        // Reset totals
        updateOrderTotals();
    }
} 