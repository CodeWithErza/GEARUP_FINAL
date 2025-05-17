    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600&display=swap" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&display=swap" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

            <style>
            :root {
                --primary-color: #111111;
                --accent-color: #FFE45C;
                --highlight-color: #FFE45C;
                --text-color: #FFFFFF;
                --gradient-start: #111111;
                --gradient-end: #1A1A1A;
            }
            
            body {
                margin: 0;
                padding: 0;
                background: var(--primary-color);
                font-family: 'Barlow', sans-serif;
                color: var(--text-color);
                overflow-x: hidden;
            }
            
            .hero-section {
                position: relative;
                min-height: 100vh;
                display: flex;
                align-items: center;
                padding: 2rem;
                overflow: hidden;
                background: var(--primary-color);
            }
            
            .hero-content {
                position: relative;
                z-index: 2;
                max-width: 1200px;
                margin: 0 auto;
                padding: 3rem;
                background: rgba(26, 26, 26, 0.6);
                backdrop-filter: blur(10px);
                -webkit-backdrop-filter: blur(10px);
                border-radius: 20px;
                border: 1px solid rgba(255, 228, 92, 0.2);
                box-shadow: 0 8px 32px rgba(255, 228, 92, 0.2), 0 4px 24px rgba(0, 0, 0, 0.4), inset 0 2px 20px rgba(255, 228, 92, 0.05);
            }
            
            .hero-title {
                font-size: 4.5rem;
                font-weight: 700;
                margin-bottom: 2rem;
                opacity: 0;
                transform: translateY(30px);
                animation: fadeInUp 0.8s ease forwards;
                color: rgba(255, 255, 255, 0.85);
                font-family: 'Rajdhani', sans-serif;
                letter-spacing: 1px;
                line-height: 1.1;
                text-align: center;
            }
            
            .hero-title .brand-name {
                font-size: 6rem;
                font-weight: 800;
                display: block;
                margin: 0.3rem 0 0.6rem;
                letter-spacing: 3px;
                animation: glow 3s infinite;
                position: relative;
                z-index: 1;
            }
            
            .hero-title .highlighted {
                color: var(--highlight-color);
                position: relative;
                display: inline-block;
            }
            
            .hero-title .sub-title {
                font-size: 2.8rem;
                color: rgba(255, 255, 255, 0.75);
                font-weight: 700;
                margin-top: 0.2rem;
                font-family: 'Rajdhani', sans-serif;
                letter-spacing: 1px;
            }
            
            .hero-subtitle {
                font-size: 1.5rem;
                margin-bottom: 2rem;
                opacity: 0;
                transform: translateY(30px);
                animation: fadeInUp 0.8s ease forwards 0.2s;
                color: rgba(255, 255, 255, 0.95);
                font-family: 'Barlow', sans-serif;
                font-weight: 500;
                text-shadow: 0 2px 10px rgba(0, 0, 0, 0.7);
            }
            
            .hero-buttons {
                opacity: 0;
                transform: translateY(30px);
                animation: fadeInUp 0.8s ease forwards 0.4s;
            }
            
            .hero-btn {
                padding: 1rem 2rem;
                font-size: 1.1rem;
                border-radius: 8px;
                text-decoration: none;
                transition: all 0.3s ease;
                margin-right: 1rem;
                position: relative;
                box-shadow: 0 4px 15px rgba(255, 228, 92, 0.2);
                font-weight: 600;
            }
            
            .hero-btn.primary {
                background: var(--accent-color);
                color: var(--primary-color);
                border: none;
            }
            
            .hero-btn.secondary {
                background: transparent;
                color: var(--text-color);
                border: 2px solid var(--accent-color);
            }
            
            .hero-btn:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 25px rgba(255, 228, 92, 0.3);
            }
            
            .hero-btn.primary:hover {
                background: #FFF200;
                color: var(--primary-color);
            }
            
            .hero-btn.secondary:hover {
                background: var(--accent-color);
                color: var(--primary-color);
            }
            
            .hero-btn i {
                transition: transform 0.3s ease;
            }
            
            .hero-btn:hover i {
                transform: translateX(3px);
            }
            
            .floating-gears {
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                pointer-events: none;
            }
            
            .gear {
                position: absolute;
                opacity: 0.1;
                animation: rotate 20s linear infinite;
                filter: drop-shadow(0 0 10px rgba(255, 228, 92, 0.3));
                color: var(--accent-color);
            }
            
            .gear:nth-child(1) { top: 10%; left: 10%; font-size: 4rem; animation-duration: 30s; }
            .gear:nth-child(2) { top: 20%; right: 20%; font-size: 6rem; animation-duration: 25s; }
            .gear:nth-child(3) { bottom: 15%; left: 15%; font-size: 5rem; animation-duration: 35s; }
            .gear:nth-child(4) { bottom: 25%; right: 10%; font-size: 3rem; animation-duration: 40s; }
            
            @keyframes rotate {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
            
            @keyframes fadeInUp {
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            .nav-links {
                position: absolute;
                top: 2rem;
                right: 2rem;
                z-index: 3;
                opacity: 0;
                animation: fadeIn 0.8s ease forwards 0.6s;
            }
            
            @keyframes fadeIn {
                to {
                    opacity: 1;
                }
            }
            
            @media (max-width: 768px) {
                .hero-title {
                    font-size: 3.5rem;
                }
                
                .hero-title .brand-name {
                    font-size: 4rem;
                }
                
                .hero-title .sub-title {
                    font-size: 2.5rem;
                }
                
                .hero-subtitle {
                    font-size: 1.2rem;
                }
                
                .hero-buttons {
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }
                
                .hero-btn {
                    margin-right: 0;
                    text-align: center;
                }
            }
            
            @media (max-width: 576px) {
                .hero-title {
                    font-size: 2.5rem;
                }
                
                .hero-title .brand-name {
                    font-size: 3rem;
                }
                
                .hero-title .sub-title {
                    font-size: 2rem;
                }
            }
            
            /* Particle effect */
            .particles {
                position: absolute;
                width: 100%;
                height: 100%;
                top: 0;
                left: 0;
                pointer-events: none;
                overflow: hidden;
            }
            
            .particle {
                position: absolute;
                width: 3px;
                height: 3px;
                background: var(--accent-color);
                border-radius: 50%;
                animation: float 20s linear infinite;
                opacity: 0.5;
                box-shadow: 0 0 10px 2px rgba(255, 228, 92, 0.3);
            }
            
            @keyframes float {
                0% {
                    transform: translateY(100vh) scale(0);
                    opacity: 0;
                }
                50% {
                    opacity: 0.7;
                }
                100% {
                    transform: translateY(-100px) scale(1);
                    opacity: 0;
                }
            }
            
            /* Add hover effect for title */
            .hero-title span {
                display: inline-block;
                transition: transform 0.3s ease;
                color: var(--accent-color);
                font-weight: 700;
            }
            
            .hero-title span:hover {
                transform: scale(1.05);
                text-shadow: 0 0 20px var(--accent-color);
            }

            /* Add neon effect to gears */
            .gear::after {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                width: 100%;
                height: 100%;
                background: radial-gradient(circle, rgba(255, 228, 92, 0.2) 0%, transparent 70%);
                filter: blur(5px);
                animation: pulse 2s infinite;
            }
            
            @keyframes pulse {
                0% { opacity: 0.5; }
                50% { opacity: 1; }
                100% { opacity: 0.5; }
            }

            /* Main title animations and effects */
            @keyframes glow {
                0% { text-shadow: 0 0 15px rgba(255, 228, 92, 0.6), 0 0 5px rgba(0, 0, 0, 0.8); }
                50% { text-shadow: 0 0 35px rgba(255, 228, 92, 0.9), 0 0 50px rgba(255, 228, 92, 0.5), 0 0 5px rgba(0, 0, 0, 0.8); }
                100% { text-shadow: 0 0 15px rgba(255, 228, 92, 0.6), 0 0 5px rgba(0, 0, 0, 0.8); }
            }

            /* Add glass morphism effect for better transparency appearance */
            @supports (backdrop-filter: blur(10px)) or (-webkit-backdrop-filter: blur(10px)) {
                .hero-content {
                    background: rgba(17, 17, 17, 0.4);
                }
            }

            /* Category Section Styles */
            .category-section {
                background: linear-gradient(to bottom, var(--primary-color), var(--gradient-end) 80%, var(--gradient-end));
                position: relative;
                z-index: 1;
                margin-bottom: 0;
                padding-bottom: 6rem;
            }

            .section-title {
                font-family: 'Rajdhani', sans-serif;
                font-weight: 700;
                color: var(--text-color);
                opacity: 0;
                transform: translateY(30px);
                animation: fadeInUp 0.8s ease forwards;
            }

            .section-subtitle {
                color: rgba(255, 255, 255, 0.8);
                opacity: 0;
                transform: translateY(30px);
                animation: fadeInUp 0.8s ease forwards 0.2s;
            }

            .category-card {
                position: relative;
                height: 400px;
                border-radius: 15px;
                overflow: hidden;
                cursor: pointer;
                transition: all 0.5s ease;
                opacity: 0;
                transform: translateY(30px);
                animation: fadeInUp 0.8s ease forwards;
                z-index: 2;
            }

            .category-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 10px 30px rgba(255, 228, 92, 0.2);
            }

            .category-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to bottom, rgba(0,0,0,0.5), rgba(0,0,0,0.9));
                z-index: 1;
                transition: all 0.5s ease;
            }

            .category-card:hover .category-overlay {
                background: linear-gradient(to bottom, rgba(0,0,0,0.7), rgba(0,0,0,0.95));
            }

            .category-img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.5s ease;
            }

            .category-card:hover .category-img {
                transform: scale(1.1);
            }

            .category-content {
                position: absolute;
                inset: 0;
                padding: 2rem;
                z-index: 2;
                transition: all 0.5s ease;
                display: flex;
                flex-direction: column;
                justify-content: flex-end;
            }

            .category-title {
                color: var(--accent-color);
                font-family: 'Rajdhani', sans-serif;
                font-size: 2rem;
                font-weight: 700;
                text-align: center;
                transition: all 0.5s ease;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            }

            .category-card:hover .category-title {
                font-size: 1.5rem;
                text-align: left;
            }

            .category-list {
                list-style: none;
                padding: 0;
                margin: 0;
                color: var(--text-color);
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.5s ease;
                visibility: hidden;
            }

            .category-card:hover .category-list {
                opacity: 1;
                transform: translateY(0);
                visibility: visible;
            }

            .category-list li {
                position: relative;
                padding-left: 1.2rem;
            }

            .category-list li:before {
                content: '•';
                color: var(--accent-color);
                position: absolute;
                left: 0;
                top: 0;
            }

            /* Animation delay for cards */
            .col-md-3:nth-child(1) .category-card { animation-delay: 0.2s; }
            .col-md-3:nth-child(2) .category-card { animation-delay: 0.4s; }
            .col-md-3:nth-child(3) .category-card { animation-delay: 0.6s; }
            .col-md-3:nth-child(4) .category-card { animation-delay: 0.8s; }

            /* About Section Styles */
            .about-section {
                background: linear-gradient(to bottom, var(--gradient-end), var(--primary-color) 80%);
                position: relative;
                overflow: hidden;
                margin-top: -1px;
            }

            .about-content {
                opacity: 0;
                transform: translateY(30px);
                animation: fadeInUp 0.8s ease forwards;
            }

            .about-text {
                color: var(--text-color);
                opacity: 0.9;
                line-height: 1.8;
            }

            .milestone-title {
                color: var(--accent-color);
                font-family: 'Rajdhani', sans-serif;
                font-weight: 600;
                opacity: 0;
                transform: translateY(20px);
                animation: fadeInUp 0.8s ease forwards 0.2s;
            }

            .milestone-card {
                background: rgba(26, 26, 26, 0.6);
                border-radius: 15px;
                padding: 1.5rem;
                height: 100%;
                border: 1px solid rgba(255, 228, 92, 0.1);
                transition: all 0.3s ease;
                opacity: 0;
                transform: translateY(20px);
                animation: fadeInUp 0.8s ease forwards;
            }

            .milestone-card:hover {
                transform: translateY(-5px);
                border-color: rgba(255, 228, 92, 0.3);
                box-shadow: 0 10px 30px rgba(255, 228, 92, 0.1);
            }

            .milestone-icon {
                color: var(--accent-color);
                font-size: 2rem;
                opacity: 0.9;
                margin-bottom: 0.75rem;
            }

            .milestone-heading {
                color: var(--text-color);
                font-family: 'Rajdhani', sans-serif;
                font-weight: 600;
                font-size: 1.2rem;
                margin-bottom: 0.5rem;
            }

            .milestone-text {
                color: var(--text-color);
                opacity: 0.8;
                font-size: 0.9rem;
                line-height: 1.4;
                margin-bottom: 0;
            }

            .tagline {
                color: var(--accent-color);
                font-size: 1.4rem;
                font-weight: 600;
                font-family: 'Rajdhani', sans-serif;
                text-align: center;
                margin-top: 2rem;
                margin-bottom: 2rem;
                opacity: 0;
                transform: translateY(20px);
                animation: fadeInUp 0.8s ease forwards 0.4s;
            }

            /* Animation delays for milestone cards */
            .milestone-card:nth-child(1) { animation-delay: 0.3s; }
            .milestone-card:nth-child(2) { animation-delay: 0.5s; }
            .milestone-card:nth-child(3) { animation-delay: 0.7s; }
        </style>
    </head>
    <body>
        <div class="hero-section">


            <!-- Floating Gears Background -->
            <div class="floating-gears">
                <i class="fas fa-cog gear"></i>
                <i class="fas fa-cog gear"></i>
                <i class="fas fa-cog gear"></i>
                <i class="fas fa-cog gear"></i>
                <i class="fas fa-wrench gear" style="top: 40%; left: 30%; font-size: 3rem; animation-duration: 45s;"></i>
                <i class="fas fa-tools gear" style="top: 60%; right: 25%; font-size: 3.5rem; animation-duration: 38s;"></i>
            </div>

            <!-- Navigation Links -->
            @if (Route::has('login'))
                <div class="nav-links">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="hero-btn secondary">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hero-btn primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Log in
                        </a>
                    @endauth
                </div>
            @endif
            
            <!-- Main Content -->
            <div class="hero-content">
                <h1 class="hero-title">
                    WELCOME TO
                    <span class="brand-name highlighted">GEARUP</span>
                    <div class="sub-title">MOTOR & VEHICLE PARTS SHOP</div>
                </h1>
                <p class="hero-subtitle">
                    Your one-stop destination for quality automotive parts and exceptional service.
                    <br>Experience the future of automotive maintenance with our cutting-edge platform.
                </p>
                <div class="hero-buttons">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="hero-btn primary">
                            <i class="fas fa-tachometer-alt me-2"></i>Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="hero-btn primary">
                            <i class="fas fa-sign-in-alt me-2"></i>Get Started
                        </a>
                        <a href="#about" class="hero-btn secondary">
                            <i class="fas fa-info-circle me-2"></i>About Us
                        </a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Category Cards Section -->
        <div class="category-section pt-3">
            <div class="container mb-5">
                <div class="text-center mb-4">
                    <h2 class="section-title display-4 mb-2">Our Product Categories</h2>
                    <p class="section-subtitle fs-5 text-light-emphasis mb-4">Explore our wide range of automotive parts and accessories</p>
                </div>
                <div class="row g-4">
                    <!-- Engine Parts -->
                    <div class="col-md-3 col-sm-6">
                        <div class="category-card shadow">
                            <div class="category-overlay"></div>
                            <img src="{{ asset('images/products/Engine/Fuel Injection System.jpg') }}" alt="Engine Parts" class="category-img">
                            <div class="category-content">
                                <h4 class="category-title mb-3">ENGINE PARTS</h4>
                                <ul class="category-list small">
                                    <li class="mb-2">Air Intake Systems</li>
                                    <li class="mb-2">Engine Components</li>
                                    <li class="mb-2">Exhaust Systems</li>
                                    <li class="mb-2">Performance Chips</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Under Chassis -->
                    <div class="col-md-3 col-sm-6">
                        <div class="category-card shadow">
                            <div class="category-overlay"></div>
                            <img src="{{ asset('images/products/Under Chassis/Automotive_disc_brake_rotor_with_a_red_caliper.jpg') }}" alt="Under Chassis" class="category-img">
                            <div class="category-content">
                                <h4 class="category-title mb-3">UNDER CHASSIS</h4>
                                <ul class="category-list small">
                                    <li class="mb-2">Brake Parts</li>
                                    <li class="mb-2">Suspension</li>
                                    <li class="mb-2">Steering</li>
                                    <li class="mb-2">Transmission</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Interior Parts -->
                    <div class="col-md-3 col-sm-6">
                        <div class="category-card shadow">
                            <div class="category-overlay"></div>
                            <img src="{{ asset('images/products/Interior/Black_Steering_Wheel.jpg') }}" alt="Interior Parts" class="category-img">
                            <div class="category-content">
                                <h4 class="category-title mb-3">INTERIOR PARTS</h4>
                                <ul class="category-list small">
                                    <li class="mb-2">Dashboard</li>
                                    <li class="mb-2">Seats & Covers</li>
                                    <li class="mb-2">Electronics</li>
                                    <li class="mb-2">Accessories</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Exterior Parts -->
                    <div class="col-md-3 col-sm-6">
                        <div class="category-card shadow">
                            <div class="category-overlay"></div>
                            <img src="{{ asset('images/products/Exterior/Car_Headlights.jpg') }}" alt="Exterior Parts" class="category-img">
                            <div class="category-content">
                                <h4 class="category-title mb-3">EXTERIOR PARTS</h4>
                                <ul class="category-list small">
                                    <li class="mb-2">Body Parts</li>
                                    <li class="mb-2">Lighting</li>
                                    <li class="mb-2">Wheels & Tires</li>
                                    <li class="mb-2">Accessories</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- About Us Section -->
        <div class="about-section py-4 pb-5" id="about">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10">
                        <div class="about-content text-center mb-4">
                            <h2 class="section-title display-4 mb-3">About Us</h2>
                            <div class="about-text">
                                <p class="lead mb-3">
                                    GEARUP was founded in 2025 with a simple but powerful mission: to fuel the journeys of drivers 
                                    and automotive enthusiasts with high-quality, reliable parts at competitive prices. Built on a 
                                    passion for performance and a commitment to excellence, GEAR UP quickly became a trusted name 
                                    in the automotive retailing industry.
                                </p>
                            </div>
                        </div>

                        <!-- Milestones -->
                        <div class="milestones mb-4">
                            <h3 class="milestone-title text-center mb-3">Our Milestones</h3>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="milestone-card">
                                        <div class="milestone-icon mb-3">
                                            <i class="fas fa-trophy"></i>
                                        </div>
                                        <h4 class="milestone-heading">Top Emerging Retailer</h4>
                                        <p class="milestone-text">Recognized as a Top Emerging Automotive Retailer of 2026 by AutoTrade PH Magazine.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="milestone-card">
                                        <div class="milestone-icon mb-3">
                                            <i class="fas fa-handshake"></i>
                                        </div>
                                        <h4 class="milestone-heading">50+ Partners</h4>
                                        <p class="milestone-text">Expanded to 50+ partner brands and suppliers within our first year.</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="milestone-card">
                                        <div class="milestone-icon mb-3">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <h4 class="milestone-heading">98% Satisfaction</h4>
                                        <p class="milestone-text">Achieved a 98% customer satisfaction rating based on post-purchase surveys.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="about-text mb-5 pb-4">
                            <p class="mb-4">
                                At GEARUP, we don't just sell parts — we drive passion. Our team is made up of automotive experts 
                                and enthusiasts who are committed to helping you find the right fit for your ride. Whether you're 
                                upgrading, repairing, or customizing, GEARUP is your trusted partner on the road ahead.
                            </p>
                            <p class="tagline mb-4">
                                Gear up for the drive of your life — with GEAR UP.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Component -->
        <x-footer />

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
 