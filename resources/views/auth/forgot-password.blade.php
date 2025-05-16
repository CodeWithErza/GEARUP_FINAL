<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Forgot Password - {{ config('app.name', 'Laravel') }}</title>
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('images/app_logo.png') }}">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <style>
      :root {
        --primary: #111111;
        --accent: #FFE45C;
        --text: #FFFFFF;
      }

      body {
        background-color: var(--primary);
        min-height: 100vh;
        display: flex;
        align-items: center;
      }

      .login-container {
        background: rgba(26, 26, 26, 0.9);
        border-radius: 10px;
        padding: 2.5rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 228, 92, 0.2);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1),
                    0 0 20px rgba(255, 228, 92, 0.05),
                    inset 0 0 20px rgba(255, 228, 92, 0.05);
      }

      .login-title {
        color: var(--accent);
        font-size: 1.75rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        text-align: center;
      }

      .login-subtitle {
        color: var(--text);
        opacity: 0.8;
        text-align: center;
        margin-bottom: 2rem;
      }

      .form-control {
        background-color: transparent;
        border: none;
        border-bottom: 1px solid rgba(255, 228, 92, 0.2);
        border-radius: 0;
        color: var(--text);
        padding: 0.75rem 2rem 0.75rem 2rem;
        height: auto;
        transition: all 0.3s ease;
      }

      .form-control:focus {
        background-color: transparent;
        border-color: var(--accent);
        color: var(--text);
        box-shadow: none;
      }

      .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5);
      }

      .input-group {
        position: relative;
        margin-bottom: 1.5rem;
      }

      .input-icon {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        color: var(--accent);
        z-index: 4;
      }

      .btn-primary {
        background-color: var(--accent);
        border: none;
        color: var(--primary);
        padding: 0.75rem;
        font-weight: 600;
        width: 100%;
        border-radius: 5px;
        margin-top: 1rem;
      }

      .btn-primary:hover {
        background-color: #fff200;
        color: var(--primary);
      }

      .back-link {
        color: var(--accent);
        text-decoration: none;
        display: block;
        text-align: center;
        margin-top: 1.5rem;
      }

      .back-link:hover {
        color: #fff200;
        text-decoration: underline;
      }

      .logo-container {
        margin-bottom: 2rem;
        text-align: center;
      }

      .logo-container img {
        max-width: 140px;
        height: auto;
      }

      .alert {
        background-color: rgba(40, 167, 69, 0.2);
        border: 1px solid rgba(40, 167, 69, 0.3);
        color: #98ff98;
      }

      .alert-danger {
        background-color: rgba(220, 53, 69, 0.2);
        border: 1px solid rgba(220, 53, 69, 0.3);
        color: #ffb3b3;
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-5 col-lg-4">
          <div class="login-container">
            <div class="logo-container">
              <img src="{{ asset('images/logo.png') }}" alt="GearUp Logo">
            </div>
            <h1 class="login-title">Forgot Password</h1>
            <p class="login-subtitle">Enter your email to receive a password reset link</p>
            
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif
            
            <form method="POST" action="{{ route('password.email') }}">
              @csrf
              <div class="input-group">
                <i class="fas fa-envelope input-icon"></i>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email') }}" required autofocus>
              </div>
              
              @error('email')
                <div class="alert alert-danger mb-3">
                    {{ $message }}
                </div>
              @enderror
              
              <button type="submit" class="btn btn-primary">
                Email Password Reset Link
              </button>
            </form>
            
            <a href="{{ route('login') }}" class="back-link">
              <i class="fas fa-arrow-left me-1"></i> Back to Login
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
