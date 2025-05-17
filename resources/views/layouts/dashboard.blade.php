<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'GEARUP') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/app_logo.png') }}">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Dashboard Custom CSS -->
    <link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
    
    <!-- Additional Styles -->
    @stack('styles')
</head>
<body>
    <!-- Sidebar Component -->
    <x-sidebar />
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header Component with optional custom title and icon -->
        <x-header :title="$title ?? null" :icon="$icon ?? null" />
        
        <!-- Page Content -->
        {{ $slot }}
    </div>

    <!-- Profile View Modal -->
    <div class="modal fade" id="profileViewModal" tabindex="-1" aria-labelledby="profileViewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-light">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="profileViewModalLabel">
                        <i class="fas fa-user-circle me-2" style="color: var(--accent);"></i>
                        User Profile
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-4">
                        <div class="avatar-container mx-auto mb-3" style="width: 100px; height: 100px; background-color: var(--accent); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-fill" style="font-size: 3rem; color: white;"></i>
                        </div>
                        <h4 class="fw-bold mb-0 text-dark" id="profile-name">{{ Auth::user()->name }}</h4>
                        <p class="text-muted" id="profile-email">{{ Auth::user()->email }}</p>
                    </div>
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="card bg-white border border-secondary-subtle shadow-sm mb-3">
                                <div class="card-header bg-secondary text-white py-2">
                                    <h6 class="card-title mb-0 fw-bold"><i class="fas fa-info-circle me-2" style="color: #fff;"></i>Account Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <div class="text-muted small">Full Name</div>
                                        <div class="fw-medium text-dark" id="profile-fullname">{{ Auth::user()->name }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="text-muted small">Email Address</div>
                                        <div class="fw-medium text-dark" id="profile-email-details">{{ Auth::user()->email }}</div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="text-muted small">Role</div>
                                        <div class="fw-medium text-dark" id="profile-role">
                                            {{ ucfirst(Auth::user()->role) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="text-muted small">Joined</div>
                                        <div class="fw-medium text-dark" id="profile-joined">{{ Auth::user()->created_at->format('F d, Y') }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-accent" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Date and Time JS -->
    <script>
        function updateDateTime() {
            const now = new Date();
            
            // Format date: June 10, 2024
            const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
            const month = monthNames[now.getMonth()];
            const day = now.getDate();
            const year = now.getFullYear();
            const formattedDate = `${month} ${day}, ${year}`;
            
            // Format time: 9:41 AM
            let hours = now.getHours();
            const minutes = now.getMinutes().toString().padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';
            hours = hours % 12;
            hours = hours ? hours : 12; // the hour '0' should be '12'
            const formattedTime = `${hours}:${minutes} ${ampm}`;
            
            // Update the DOM
            document.getElementById('current-date').textContent = formattedDate;
            document.getElementById('current-time').textContent = formattedTime;
            
            // Update every minute
            setTimeout(updateDateTime, 60000);
        }
        
        document.addEventListener('DOMContentLoaded', updateDateTime);
    </script>
    
    <!-- Additional Scripts -->
    @stack('scripts')
    
    <!-- Add this script before the closing body tag -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fetch user data when profile modal is about to be shown
            const profileModal = document.getElementById('profileViewModal');
            if (profileModal) {
                profileModal.addEventListener('show.bs.modal', function() {
                    fetchUserProfile();
                });
            }
            
            // Function to fetch user profile data
            function fetchUserProfile() {
                fetch('/api/user/profile', {
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Update the profile modal with fetched data
                    document.getElementById('profile-name').textContent = data.name;
                    document.getElementById('profile-email').textContent = data.email;
                    document.getElementById('profile-fullname').textContent = data.name;
                    document.getElementById('profile-email-details').textContent = data.email;
                    document.getElementById('profile-role').textContent = data.role ? data.role.charAt(0).toUpperCase() + data.role.slice(1) : 'Staff';
                    
                    // Format the date
                    const joinedDate = new Date(data.created_at);
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };
                    document.getElementById('profile-joined').textContent = joinedDate.toLocaleDateString('en-US', options);
                })
                .catch(error => {
                    console.error('Error fetching user profile:', error);
                    // Fallback to Auth data if fetch fails
                });
            }
        });
    </script>
</body>
</html> 