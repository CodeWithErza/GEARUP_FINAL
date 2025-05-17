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
                <div class="card" style="background-color: #2a2a2a; border-color: #393939;">
                    <div class="card-body" style="color: #ffffff;">
                        <form action="{{ route('users.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" class="form-control" name="search" placeholder="Search users..." value="{{ request('search') }}" style="background-color: #333333; color: #ffffff; border-color: #444444;">
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="role" style="background-color: #333333; color: #ffffff; border-color: #444444;">
                                    <option value="">All Roles</option>
                                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="staff" {{ request('role') === 'staff' ? 'selected' : '' }}>Staff</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn" style="background-color: #FFE45C; color: #222222; font-weight: 500;">
                                    <i class="fas fa-search me-2"></i>Search
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary" style="color: #999999; border-color: #555555;">
                                    <i class="fas fa-sync-alt me-2"></i>Reset
                                </a>
                            </div>
                            <div class="col-md-2 text-end">
                                <button type="button" class="btn" style="background-color: #FFE45C; color: #222222; font-weight: 500;" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                    <i class="fas fa-plus me-2"></i>Add User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card" style="background-color: #2a2a2a; border-color: #393939;">
            <div class="card-body" style="color: #ffffff;">
                <div class="table-responsive">
                    <table class="table table-hover" style="color: #e0e0e0;">
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
                                            {{ \DateTime::createFromFormat('m-d-Y', $user->birthdate)->format('F d, Y') }}
                                        @else
                                            Not set
                                        @endif
                                    </td>
                                    <td>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</td>
                                    <td>{{ $user->created_at->format('F d, Y') }}</td>
                                    <td>
                                        <button class="btn btn-sm" style="background-color: #FFE45C; color: #222222;" title="Edit" onclick="editUser({{ $user->id }})">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" title="Delete" onclick="deleteUser({{ $user->id }})">
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
                <div class="modal-content" style="background-color: #2a2a2a; color: #ffffff;">
                    <div class="modal-header" style="background-color: #2a2a2a; border-bottom-color: #393939;">
                        <h5 class="modal-title" style="color: #FFE45C;"><i class="fas fa-user-plus me-2"></i>Add New User</h5>
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
                                    <label class="form-label mb-1" style="color: #e0e0e0;">Name</label>
                                    <input type="text" class="form-control" name="name" required style="background-color: #333333; color: #ffffff; border-color: #444444;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1" style="color: #e0e0e0;">Email</label>
                                    <input type="email" class="form-control" name="email" required style="background-color: #333333; color: #ffffff; border-color: #444444;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1" style="color: #e0e0e0;">Password</label>
                                    <input type="password" 
                                           class="form-control" 
                                           name="password" 
                                           id="password" 
                                           required
                                           oninput="validatePassword(this)"
                                           style="background-color: #333333; color: #ffffff; border-color: #444444;">
                                    <small class="form-text text-muted" id="password_help" style="display: none;">Leave blank to keep current password when editing.</small>
                                    <small class="form-text text-danger" id="password_error" style="display: none;">Password must be at least 8 characters</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1" style="color: #e0e0e0;">Confirm Password</label>
                                    <input type="password" 
                                           class="form-control" 
                                           name="password_confirmation" 
                                           id="password_confirmation"
                                           required
                                           oninput="validatePasswordMatch()"
                                           style="background-color: #333333; color: #ffffff; border-color: #444444;">
                                    <small class="form-text text-danger" id="confirm_error" style="display: none;">Passwords don't match</small>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1" style="color: #e0e0e0;">Role</label>
                                    <select class="form-select" name="role" required style="background-color: #333333; color: #ffffff; border-color: #444444;">
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1" style="color: #e0e0e0;">Status</label>
                                    <select class="form-select" name="is_active" style="background-color: #333333; color: #ffffff; border-color: #444444;">
                                        <option value="1" selected>Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label mb-1" style="color: #e0e0e0;">Birthdate</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control datepicker" 
                                               name="birthdate" 
                                               placeholder="MM/DD/YYYY"
                                               autocomplete="off"
                                               style="background-color: #333333; color: #ffffff; border-color: #444444;">
                                        <span class="input-group-text" style="background-color: #444444; color: #ffffff; border-color: #444444;">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer" style="background-color: #2a2a2a; border-top-color: #393939;">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" style="color: #999999; border-color: #555555;">Cancel</button>
                        <button type="submit" form="addUserForm" class="btn" style="background-color: #FFE45C; color: #222222; font-weight: 500;">Save User</button>
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
                altFormat: "F d, Y",
                altInput: true,
                altInputClass: "form-control bg-white text-dark border border-secondary-subtle shadow-sm",
                maxDate: "today",
                yearRange: "1900:2024",
                placeholder: "Month DD, YYYY"
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
                    
                    // Set birthdate - ensure proper formatting
                    const birthdateInput = form.querySelector('input[name="birthdate"]');
                    if (data.birthdate) {
                        // Update the flatpickr instance with the new date
                        const fp = birthdateInput._flatpickr;
                        if (fp) {
                            // Convert to date object for consistent formatting
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