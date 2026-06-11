<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>



<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', sans-serif;
}

body {
    background: #eef2ff;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 100vh;
    padding: 16px;
}

/* CONTAINER */
.container {
    width: 100%;
    max-width: 850px;
    display: flex;
    background: #fff;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* LEFT */
.left {
    width: 50%;
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: white;
    padding: 30px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.left h1 {
    font-size: 24px;
    line-height: 1.4;
}

/* RIGHT */
.right {
    width: 50%;
    padding: 30px;
}

/* FORM BOX */
.form-box {
    width: 100%;
    max-width: 320px;
    margin: auto;
}

.right h2 {
    margin-bottom: 20px;
    text-align: center;
}

/* FORM */
.form-group {
    margin-bottom: 14px;
}

.form-group label {
    font-size: 13px;
    color: #555;
}

.form-group input {
    width: 100%;
    padding: 10px 12px;
    margin-top: 5px;
    border-radius: 10px;
    border: 1px solid #ddd;
    outline: none;
}

.form-group input:focus {
    border-color: #6366f1;
}

/* BUTTON */
.btn {
    width: 100%;
    padding: 10px;
    background: #6366f1;
    border: none;
    color: white;
    border-radius: 20px;
    font-weight: bold;
    cursor: pointer;
}

.btn:hover {
    background: #4f46e5;
}

/* TEXT */
.text-center {
    text-align: center;
    margin-top: 12px;
    font-size: 13px;
}

.text-center a {
    color: #6366f1;
    text-decoration: none;
}

.error {
    color: red;
    font-size: 12px;
}

/* ================= MOBILE ================= */
@media (max-width: 768px) {

    body {
        align-items: flex-start;
    }

    .container {
        flex-direction: column;
        max-width: 320px;
        margin: 20px auto;
        border-radius: 14px;
    }

    .left {
        width: 100%;
        padding: 18px;
        text-align: center;
    }

    .left h1 {
        font-size: 18px;
    }

    .right {
        width: 100%;
        padding: 18px;
    }

    .form-box {
        max-width: 100%;
    }
}

/* EXTRA SMALL */
@media (max-width: 480px) {
    .container {
        max-width: 280px;
    }
}

</style>
</head>

<body>

<div class="container">

    <!-- LEFT -->
    <div class="left">
        <div>
            <h3>PKL System</h3>
            <p>Sistem Manajemen PKL</p>
        </div>

        <h1>Daftar & Mulai PKL Sekarang 🚀</h1>

        <small>© {{ date('Y') }}</small>
    </div>

    <!-- RIGHT -->
    <div class="right">

        <div class="form-box">
            <h2>Create Account</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- NAME -->
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>

                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- EMAIL -->
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required>

                    @error('email')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- PASSWORD -->
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>

                    @error('password')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <!-- CONFIRM PASSWORD -->
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" required>
                </div>

                <button class="btn">Sign Up</button>

                <div class="text-center">
                    Sudah punya akun?
                    <a href="{{ route('login') }}">Login</a>
                </div>
            </form>
        </div>

    </div>

</div>

</body>
</html>