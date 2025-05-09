<x-dashboard-layout :title="'Users Management'" :icon="'fa-solid fa-users'">
    <div class="container-fluid">
        <!-- Display Validation Errors -->
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Display Success Message -->
        @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <!-- Display Error Message -->
        @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
        @endif

        <!-- Header with Search and Add User Button -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" placeholder="Search users..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="role">
                                    <option value="">All Roles</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Search
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-sync-alt me-2"></i>Reset
                                </a>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-plus me-2"></i>Add User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Birthdate</th>
                                <th>Last Login</th>
                                <th>Created Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->isAdmin() ? 'primary' : 'secondary' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->is_active ? 'success' : 'danger' }}">
                                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($user->birthdate)
                                            {{ str_replace('-', '/', $user->birthdate) }}
                                        @else
                                            Not set
                                        @endif
                                    </td>
                                    <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-info me-2" title="Edit" onclick="editUser({{ $user->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger" title="Delete" onclick="deleteUser({{ $user->id }})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No users found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>

        <!-- Add User Modal -->
        <div class="modal fade" id="addUserModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content bg-light">
                    <div class="modal-header bg-dark text-white">
                        <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <!-- Add Flatpickr CSS in the header -->
                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
                    <div class="modal-body p-3">
                        <form id="addUserForm" action="{{ route('users.store') }}" method="POST">
                            @csrf
                            <div id="method_field_container">
                                <!-- PUT method field goes here when editing -->
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label mb-1 text-dark">Name</label>
                                    <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" name="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1 text-dark">Email</label>
                                    <input type="email" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" name="email" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1 text-dark">Password</label>
                                    <input type="password" 
                                           class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" 
                                           name="password" 
                                           id="password" 
                                           required
                                           oninput="validatePassword(this)">
                                    <small class="form-text text-muted" id="password_help" style="display: none;">Leave blank to keep current password when editing.</small>
                                    <small class="form-text text-danger" id="password_error" style="display: none;">Password must be at least 8 characters</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1 text-dark">Confirm Password</label>
                                    <input type="password" 
                                           class="form-control bg-white text-dark border border-secondary-subtle shadow-sm" 
                                           name="password_confirmation" 
                                           id="password_confirmation"
                                           required
                                           oninput="validatePasswordMatch()">
                                    <small class="form-text text-danger" id="confirm_error" style="display: none;">Passwords don't match</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1 text-dark">Role</label>
                                    <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" name="role" required>
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1 text-dark">Status</label>
                                    <select class="form-select bg-white text-dark border border-secondary-subtle shadow-sm" name="is_active">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1 text-dark">Birthdate</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-white text-dark border border-secondary-subtle shadow-sm datepicker" 
                                               name="birthdate" 
                                               placeholder="MM/DD/YYYY"
                                               autocomplete="off">
                                        <span class="input-group-text bg-white border border-secondary-subtle">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" form="addUserForm" class="btn btn-primary">Save User</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <!-- Add Flatpickr JS before your scripts -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        // Initialize date picker
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr
            const dateConfig = {
                dateFormat: "m/d/Y",
                allowInput: true,
                altFormat: "m/d/Y",
                altInput: true,
                altInputClass: "form-control bg-white text-dark border border-secondary-subtle shadow-sm",
                maxDate: "today",
                yearRange: "1900:2024",
                placeholder: "MM/DD/YYYY"
            };
            
            // Initialize the date picker
            const datePicker = flatpickr(".datepicker", dateConfig);
            
            // Add click handler to calendar icon
            document.querySelector('.input-group-text').addEventListener('click', function() {
                datePicker.toggle();
            });
        });

        function editUser(userId) {
            fetch(`/users/${userId}/edit`)
                .then(response => response.json())
                .then(data => {
                    // Get the form and update its action
                    const form = document.getElementById('addUserForm');
                    form.action = `/users/${userId}`;
                    
                    // Add PUT method field
                    document.getElementById('method_field_container').innerHTML = 
                        `<input type="hidden" name="_method" value="PUT">`;
                    
                    // Set basic fields
                    form.querySelector('input[name="name"]').value = data.name;
                    form.querySelector('input[name="email"]').value = data.email;
                    
                    // Set role - make sure it's selected
                    const roleSelect = form.querySelector('select[name="role"]');
                    roleSelect.value = data.role;
                    
                    // Set status
                    form.querySelector('select[name="is_active"]').value = data.is_active ? '1' : '0';
                    
                    // Set birthdate - convert from MM-DD-YYYY to MM/DD/YYYY
                    const birthdateInput = form.querySelector('input[name="birthdate"]');
                    if (data.birthdate) {
                        // Update the flatpickr instance with the new date
                        const fp = birthdateInput._flatpickr;
                        if (fp) {
                            fp.setDate(data.birthdate.replace(/-/g, '/'), true);
                        }
                    } else {
                        const fp = birthdateInput._flatpickr;
                        if (fp) {
                            fp.clear();
                        }
                    }
                    
                    // Handle password fields
                    const passwordInput = form.querySelector('#password');
                    const passwordConfirmInput = form.querySelector('#password_confirmation');
                    
                    // Make passwords optional and show help text
                    passwordInput.required = false;
                    passwordConfirmInput.required = false;
                    passwordInput.value = '';
                    passwordConfirmInput.value = '';
                    document.getElementById('password_help').style.display = 'block';
                    
                    // Update modal title
                    document.querySelector('#addUserModal .modal-title').innerHTML = 
                        '<i class="fas fa-user-edit me-2"></i>Edit User: ' + data.name;
                    
                    // Show modal
                    new bootstrap.Modal(document.querySelector('#addUserModal')).show();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading user data');
                });
        }

        function deleteUser(userId) {
            if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/users/${userId}`;
                
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                
                const tokenInput = document.createElement('input');
                tokenInput.type = 'hidden';
                tokenInput.name = '_token';
                tokenInput.value = '{{ csrf_token() }}';
                
                form.appendChild(methodInput);
                form.appendChild(tokenInput);
                document.body.appendChild(form);
                
                form.submit();
            }
        }

        // Reset form when modal is closed
        document.querySelector('#addUserModal').addEventListener('hidden.bs.modal', function () {
            const form = document.querySelector('#addUserForm');
            
            // Reset form and action
            form.reset();
            form.action = '{{ route("users.store") }}';
            
            // Reset title
            document.querySelector('#addUserModal .modal-title').innerHTML = 
                '<i class="fas fa-user-plus me-2"></i>Add New User';
            
            // Clear method field
            document.getElementById('method_field_container').innerHTML = '';
            
            // Reset password fields
            const passwordInput = form.querySelector('#password');
            const passwordConfirmInput = form.querySelector('#password_confirmation');
            
            // Reset date picker
            const birthdateInput = form.querySelector('input[name="birthdate"]');
            const fp = birthdateInput._flatpickr;
            if (fp) {
                fp.clear();
            }
            
            passwordInput.required = true;
            passwordConfirmInput.required = true;
            passwordInput.value = '';
            passwordConfirmInput.value = '';
            document.getElementById('password_help').style.display = 'none';
        });

        // Show validation errors in modal if any
        @if($errors->any())
            new bootstrap.Modal(document.querySelector('#addUserModal')).show();
        @endif

        // Show success message if any
        @if(session('success'))
            // You can use any notification library here
            alert('{{ session("success") }}');
        @endif

        // Show error message if any
        @if(session('error'))
            alert('{{ session("error") }}');
        @endif

        // Simple password validation
        function validatePassword(input) {
            const error = document.getElementById('password_error');
            if (input.value.length > 0 && input.value.length < 8) {
                error.style.display = 'block';
                return false;
            }
            error.style.display = 'none';
            validatePasswordMatch();
            return true;
        }

        function validatePasswordMatch() {
            const password = document.getElementById('password');
            const confirm = document.getElementById('password_confirmation');
            const error = document.getElementById('confirm_error');
            
            if (confirm.value && password.value !== confirm.value) {
                error.style.display = 'block';
                return false;
            }
            error.style.display = 'none';
            return true;
        }

        // Form validation
        document.getElementById('addUserForm').addEventListener('submit', function(event) {
            const password = document.getElementById('password');
            
            // Only validate if it's a new user or password is being changed
            if (password.required || password.value) {
                if (!validatePassword(password) || !validatePasswordMatch()) {
                    event.preventDefault();
                }
            }
        });
    </script>
    @endpush
</x-dashboard-layout> 