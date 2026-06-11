<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login — MAKO Magang Dinkominfo</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    min-height: 100vh;
    display: flex;
    background: #f0f4ff;
}

/* ─── LEFT PANEL ─── */
.left-panel {
    width: 55%;
    background: linear-gradient(145deg, #0b1e5b 0%, #1340b0 50%, #1a73e8 100%);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 48px 56px;
    position: relative;
    overflow: hidden;
}

/* Decorative circles */
.left-panel::before {
    content: '';
    position: absolute;
    width: 420px;
    height: 420px;
    border-radius: 50%;
    background: rgba(255,255,255,0.05);
    top: -120px;
    right: -100px;
}
.left-panel::after {
    content: '';
    position: absolute;
    width: 280px;
    height: 280px;
    border-radius: 50%;
    background: rgba(255,255,255,0.06);
    bottom: -80px;
    left: -60px;
}

.logo-wrapper {
    position: relative;
    z-index: 1;
}

.logo-card {
    background: white;
    display: inline-flex;
    align-items: center;
    padding: 14px 24px;
    border-radius: 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
}

.logo-card img {
    height: 52px;
    width: auto;
    display: block;
}

.hero-content {
    position: relative;
    z-index: 1;
}

.hero-content h1 {
    font-size: 36px;
    font-weight: 800;
    color: #ffffff;
    line-height: 1.25;
    margin-bottom: 16px;
    letter-spacing: -0.5px;
}

.hero-content p {
    font-size: 15px;
    color: rgba(255,255,255,0.75);
    line-height: 1.7;
    max-width: 380px;
}

.feature-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-top: 32px;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 12px;
    color: rgba(255,255,255,0.9);
    font-size: 14px;
    font-weight: 500;
}

.feature-item .icon-wrap {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: rgba(255,255,255,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 18px;
}

.left-footer {
    position: relative;
    z-index: 1;
    color: rgba(255,255,255,0.5);
    font-size: 12.5px;
}

/* ─── RIGHT PANEL ─── */
.right-panel {
    width: 45%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 48px 40px;
    background: #f0f4ff;
}

.form-card {
    width: 100%;
    max-width: 380px;
    background: #ffffff;
    border-radius: 24px;
    padding: 44px 40px;
    box-shadow: 0 4px 40px rgba(11, 30, 91, 0.10);
    animation: slideUp 0.5s ease;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(24px); }
    to   { opacity: 1; transform: translateY(0); }
}

.form-header {
    text-align: center;
    margin-bottom: 32px;
}

.form-header .icon-circle {
    width: 58px;
    height: 58px;
    background: linear-gradient(135deg, #1340b0, #1a73e8);
    border-radius: 16px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 16px;
    font-size: 26px;
    color: white;
}

.form-header h2 {
    font-size: 22px;
    font-weight: 700;
    color: #0b1e5b;
    margin-bottom: 4px;
}

.form-header p {
    font-size: 13.5px;
    color: #8895b0;
}

/* INPUT */
.form-group {
    margin-bottom: 18px;
}

.form-group label {
    display: block;
    font-size: 13px;
    font-weight: 600;
    color: #4a5578;
    margin-bottom: 7px;
}

.input-wrap {
    position: relative;
}

.input-wrap i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    font-size: 19px;
    color: #a0aec0;
}

.input-wrap input {
    width: 100%;
    padding: 11px 14px 11px 42px;
    border: 1.8px solid #e2e8f0;
    border-radius: 12px;
    font-size: 14px;
    color: #1a202c;
    background: #f8faff;
    outline: none;
    transition: all 0.25s;
    font-family: 'Poppins', sans-serif;
}

.input-wrap input:focus {
    border-color: #1a73e8;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(26, 115, 232, 0.1);
}

.input-wrap input::placeholder {
    color: #b2bfd0;
}

/* REMEMBER + FORGOT */
.form-options {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
    font-size: 13px;
}

.remember-me {
    display: flex;
    align-items: center;
    gap: 7px;
    color: #5a6782;
    cursor: pointer;
}

.remember-me input[type="checkbox"] {
    width: 15px;
    height: 15px;
    accent-color: #1a73e8;
    cursor: pointer;
}

.forgot-link {
    color: #1a73e8;
    text-decoration: none;
    font-weight: 500;
    font-size: 13px;
}
.forgot-link:hover {
    text-decoration: underline;
}

/* BUTTON */
.btn-login {
    width: 100%;
    padding: 13px;
    background: linear-gradient(135deg, #1340b0, #1a73e8);
    border: none;
    border-radius: 12px;
    color: white;
    font-size: 15px;
    font-weight: 600;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    transition: all 0.3s;
    letter-spacing: 0.3px;
    box-shadow: 0 4px 16px rgba(26, 115, 232, 0.35);
}

.btn-login:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(26, 115, 232, 0.45);
}

.btn-login:active {
    transform: translateY(0);
}

/* DIVIDER */
.divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 22px 0 16px;
    color: #c4cdd8;
    font-size: 12px;
}
.divider::before, .divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #e8edf5;
}

/* REGISTER */
.register-text {
    text-align: center;
    font-size: 13px;
    color: #8895b0;
}
.register-text a {
    color: #1340b0;
    font-weight: 600;
    text-decoration: none;
}
.register-text a:hover {
    text-decoration: underline;
}

/* ALERT */
.alert-success {
    background: #e8f5e9;
    color: #2e7d32;
    border-radius: 10px;
    padding: 10px 14px;
    font-size: 13px;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.error-msg {
    color: #e53e3e;
    font-size: 12px;
    margin-top: 5px;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* ─── RESPONSIVE ─── */
@media (max-width: 820px) {
    body { flex-direction: column; }
    .left-panel {
        width: 100%;
        padding: 36px 28px 32px;
        min-height: unset;
    }
    .left-panel::before { width: 250px; height: 250px; top: -80px; right: -60px; }
    .hero-content h1 { font-size: 26px; }
    .feature-list { display: none; }
    .right-panel {
        width: 100%;
        padding: 32px 20px 40px;
    }
    .form-card { padding: 32px 24px; }
}
</style>
</head>
<body>

<!-- ═══════════ LEFT PANEL ═══════════ -->
<div class="left-panel">

    <div class="logo-wrapper">
        <div class="logo-card">
            <img src="{{ asset('images/logo-mako.png') }}" alt="MAKO — Magang Dinkominfo">
        </div>
    </div>

    <div class="hero-content">
        <h1>Platform Manajemen<br>Magang Terpadu</h1>
        <p>Pantau absensi, laporan harian, tugas, dan penilaian peserta magang Dinkominfo dalam satu platform yang mudah digunakan.</p>

        <div class="feature-list">
            <div class="feature-item">
                <div class="icon-wrap"><i class='bx bx-calendar-check'></i></div>
                Manajemen Absensi Real-time
            </div>
            <div class="feature-item">
                <div class="icon-wrap"><i class='bx bx-file'></i></div>
                Laporan Harian Terstruktur
            </div>
            <div class="feature-item">
                <div class="icon-wrap"><i class='bx bx-bar-chart-alt-2'></i></div>
                Penilaian & Evaluasi Otomatis
            </div>
        </div>
    </div>

    <div class="left-footer">
        © {{ date('Y') }} MAKO — Dinas Komunikasi dan Informatika
    </div>

</div>

<!-- ═══════════ RIGHT PANEL ═══════════ -->
<div class="right-panel">
    <div class="form-card">

        <div class="form-header">
            <div class="icon-circle">
                <i class='bx bx-lock-open-alt'></i>
            </div>
            <h2>Selamat Datang</h2>
            <p>Masuk ke akun MAKO Anda</p>
        </div>

        @if (session('status'))
            <div class="alert-success">
                <i class='bx bx-check-circle'></i>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- EMAIL -->
            <div class="form-group">
                <label>Email</label>
                <div class="input-wrap">
                    <i class='bx bx-envelope'></i>
                    <input type="email" name="email"
                           value="{{ old('email') }}"
                           placeholder="contoh@email.com"
                           required>
                </div>
                @error('email')
                    <div class="error-msg"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class='bx bx-lock-alt'></i>
                    <input type="password" name="password"
                           placeholder="Masukkan password"
                           required>
                </div>
                @error('password')
                    <div class="error-msg"><i class='bx bx-error-circle'></i> {{ $message }}</div>
                @enderror
            </div>

            <!-- OPTIONS -->
            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" name="remember">
                    Ingat saya
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="forgot-link">Lupa password?</a>
                @endif
            </div>

            <!-- SUBMIT -->
            <button type="submit" class="btn-login">
                <i class='bx bx-log-in-circle' style="margin-right:6px; font-size:17px; vertical-align:middle;"></i>
                Masuk Sekarang
            </button>

        </form>
    </div>
</div>

</body>
</html>