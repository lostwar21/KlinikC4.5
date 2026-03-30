<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link rel="icon" type="image/png" href="<?php echo base_url('assets/img/logo.png'); ?>">
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #0d9488; /* Teal 600 */
            --secondary-color: #06b6d4; /* Cyan 500 */
            --bg-gradient: linear-gradient(135deg, #f0fdfa 0%, #ccfbf1 100%);
            --glass-bg: rgba(255, 255, 255, 0.85);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-gradient);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }

        .login-container {
            width: 100%;
            max-width: 440px;
            padding: 20px;
        }

        .login-card {
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            box-shadow: 0 20px 40px rgba(13, 148, 136, 0.1);
            padding: 40px;
            text-align: center;
            transition: transform 0.3s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .brand-logo {
            width: 80px;
            height: 80px;
            margin-bottom: 20px;
            object-fit: contain;
        }

        .header-text h1 {
            font-size: 1.25rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .header-text p {
            font-size: 0.9rem;
            color: #64748b;
            margin-bottom: 32px;
        }

        .form-label {
            font-weight: 600;
            color: #334155;
            font-size: 0.85rem;
            display: block;
            text-align: left;
            margin-bottom: 8px;
        }

        .input-group {
            background: white;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            transition: all 0.2s;
            margin-bottom: 20px;
        }

        .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.1);
        }

        .input-group-text {
            background: transparent;
            border: none;
            color: #94a3b8;
            padding-left: 16px;
        }

        .form-control {
            border: none;
            padding: 12px 16px;
            font-size: 0.95rem;
        }

        .form-control:focus {
            box-shadow: none;
        }

        .btn-login {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 12px;
            padding: 14px;
            width: 100%;
            color: white;
            font-weight: 700;
            font-size: 1rem;
            margin-top: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(13, 148, 136, 0.2);
        }

        .btn-login:hover {
            opacity: 0.9;
            transform: scale(1.02);
            box-shadow: 0 6px 16px rgba(13, 148, 136, 0.3);
        }

        .footer-links {
            margin-top: 24px;
            font-size: 0.85rem;
        }

        .footer-links a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .footer-links a:hover {
            text-decoration: underline;
        }

        .copyright {
            margin-top: 40px;
            font-size: 0.75rem;
            color: #94a3b8;
            line-height: 1.6;
        }

        /* Float animation for background elements */
        .blob {
            position: absolute;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(45, 212, 191, 0.2) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            z-index: -1;
            filter: blur(40px);
        }

        .blob-1 { top: -100px; left: -100px; }
        .blob-2 { bottom: -100px; right: -100px; }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>

    <div class="login-container">
        <div class="login-card">
            <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="Logo Klinik" class="brand-logo">
            
            <div class="header-text">
                <h1>Sistem Klasifikasi</h1>
                <p class="fw-bold mb-0" style="color:var(--primary-color)">PRAKTIK GIGI MANDIRI ESENSIIL</p>
                <p class="small text-muted mb-4">Modern Dental Care</p>
            </div>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="alert alert-danger py-2 px-3 rounded-3 mb-3" style="font-size: 0.85rem;">
                    <i class="fas fa-exclamation-circle me-1"></i> <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <form action="<?php echo site_url('auth/login_process'); ?>" method="POST">
                <div class="text-start">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username" required autocomplete="off">
                    </div>

                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>

                <button type="submit" class="btn btn-login">
                    LOGIN <i class="fas fa-sign-in-alt ms-2"></i>
                </button>
            </form>

            <div class="footer-links">
                <a href="#">Lupa Password? Hubungi Admin</a>
            </div>

            <div class="copyright">
                &copy; <?php echo date('Y'); ?> - Proyek Perangkat Lunak<br>
                Sistem Klasifikasi Rekam Medis (C4.5)
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
