<!-- Footer -->
<footer class="footer">
    <div class="footer-content">
        <div class="copyright">
            © {{ date('Y') }} GearUp Auto Parts Shop. All rights reserved.
        </div>
        <div class="developers">
            Developed by: 
            <span class="developer-name">Dumangcas</span> | 
            <span class="developer-name">Palen</span> |
            <span class="developer-name">Farinas</span> | 
 
        </div>
    </div>
</footer>

<style>
    .footer {
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        background: #111111;
        backdrop-filter: blur(10px);
        padding: 1rem 0;
        z-index: 1000;
        border-top: 1px solid rgba(255, 228, 92, 0.2);
    }

    .footer-content {
        text-align: center;
        color: white;
        font-size: 0.9rem;
    }

    .copyright {
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: rgba(255, 255, 255, 0.9);
    }

    .developers {
        font-size: 0.85rem;
        opacity: 0.9;
        color: rgba(255, 255, 255, 0.7);
    }

    .developer-name {
        color: #FFE45C;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .developer-name:hover {
        color: #FFF200;
    }

    @media (max-width: 768px) {
        .footer-content {
            font-size: 0.8rem;
        }
        
        .developers {
            font-size: 0.75rem;
        }
    }
</style> 