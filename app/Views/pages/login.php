<?php
$hide_navbar = true; // Hide global navbar on login page

// If admin is already logged in, redirect to admin dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php?page=admin_dashboard');
    exit;
}

// Capture error/success messages
$error = $_GET['error'] ?? null;
$success = $_GET['success'] ?? null;
?>
<?php include BASE_PATH . '/app/Views/layouts/header.php'; ?>

<style>
    .login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #8a9a6d;
        padding: 2rem;
    }

    .login-card {
        max-width: 820px;
        width: 100%;
        background: #f5f5f0;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 25px 50px rgba(0,0,0,0.25);
    }

    /* Header Bar */
    .login-header {
        background-color: #3d4a2e;
        padding: 1rem 2rem;
    }
    .login-header h2 {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: 1.4rem;
        font-weight: 700;
        margin: 0;
        letter-spacing: -0.02em;
    }

    /* Body */
    .login-body {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    /* Left Form */
    .login-form-section {
        padding: 2.5rem 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    .login-form-section h1 {
        font-family: 'Playfair Display', serif;
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0 0 0.4rem;
    }
    .login-form-section .subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 0.8rem;
        color: #7a7a6e;
        margin: 0 0 2rem;
    }
    .login-form-section label {
        display: block;
        font-family: 'Inter', sans-serif;
        font-size: 0.8rem;
        font-weight: 500;
        color: #333;
        margin-bottom: 0.5rem;
    }
    .login-form-section input[type="text"],
    .login-form-section input[type="password"] {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1.5px solid #c5c5b8;
        border-radius: 0.5rem;
        background: #fff;
        font-family: 'Inter', sans-serif;
        font-size: 0.9rem;
        color: #333;
        margin-bottom: 1.25rem;
        transition: border-color 0.2s, box-shadow 0.2s;
        box-sizing: border-box;
    }
    .login-form-section input[type="text"]:focus,
    .login-form-section input[type="password"]:focus {
        outline: none;
        border-color: #6b7f4a;
        box-shadow: 0 0 0 3px rgba(107, 127, 74, 0.15);
    }
    .login-btn {
        width: 100%;
        padding: 0.8rem;
        background-color: #6b7f4a;
        color: #fff;
        border: none;
        border-radius: 0.5rem;
        font-family: 'Inter', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.15s;
        margin-top: 0.5rem;
    }
    .login-btn:hover {
        background-color: #5a6c3e;
    }
    .login-btn:active {
        transform: scale(0.98);
    }

    /* Right Image */
    .login-image-section {
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        min-height: 400px;
    }
    .login-image-section img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .login-image-caption {
        position: relative;
        z-index: 1;
        background: rgba(255,255,255,0.88);
        padding: 1rem 1.5rem;
        margin: 1rem;
        border-radius: 0.5rem;
    }
    .login-image-caption p {
        font-family: 'Playfair Display', serif;
        font-size: 1.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin: 0;
        line-height: 1.2;
    }

    /* Alert Messages */
    .login-alert {
        padding: 0.65rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.8rem;
        font-family: 'Inter', sans-serif;
        margin-bottom: 1.25rem;
    }
    .login-alert-error {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }
    .login-alert-success {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    /* Responsive */
    @media (max-width: 700px) {
        .login-body {
            grid-template-columns: 1fr;
        }
        .login-image-section {
            min-height: 220px;
            order: -1;
        }
    }
</style>

<div class="login-wrapper">
    <div class="login-card">
        <!-- Header Bar -->
        <div class="login-header">
            <h2>CrystalWash</h2>
        </div>

        <!-- Body -->
        <div class="login-body">
            <!-- Left: Form -->
            <div class="login-form-section">
                <h1>Welcome to CrystalWash</h1>
                <p class="subtitle">Please enter your username and password</p>

                <?php if ($error === 'invalid'): ?>
                    <div class="login-alert login-alert-error">Username atau password salah.</div>
                <?php elseif ($error === 'empty'): ?>
                    <div class="login-alert login-alert-error">Mohon isi username dan password.</div>
                <?php endif; ?>

                <?php if ($success === 'logout'): ?>
                    <div class="login-alert login-alert-success">Anda telah berhasil logout.</div>
                <?php endif; ?>

                <form action="index.php?action=admin_login" method="POST" id="loginForm">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" autocomplete="username" required>

                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" autocomplete="current-password" required>

                    <button type="submit" class="login-btn">Login</button>
                </form>
            </div>

            <!-- Right: Image -->
            <div class="login-image-section">
                <img src="assets/img/login_car_wash.png" alt="Car Wash">
                <div class="login-image-caption">
                    <p>Clean Car, Happy Life</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/Views/layouts/footer.php'; ?>
