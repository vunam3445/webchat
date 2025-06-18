<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Chat Realtime</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .auth-container {
            width: 100%;
            max-width: 450px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .auth-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                transform: translateX(-100%) translateY(-100%) rotate(45deg);
            }

            100% {
                transform: translateX(100%) translateY(100%) rotate(45deg);
            }
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
            position: relative;
            z-index: 1;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            color: white;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .auth-title {
            color: white;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .auth-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 16px;
        }

        .auth-tabs {
            display: flex;
            margin-bottom: 30px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 4px;
            position: relative;
            z-index: 1;
        }

        .auth-tab {
            flex: 1;
            padding: 12px 0;
            text-align: center;
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.3s ease;
            position: relative;
        }

        .auth-tab.active {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .auth-form {
            position: relative;
            z-index: 1;
            display: none;
        }

        .auth-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 12px;
            color: white;
            font-size: 16px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }

        .form-input:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.4);
            box-shadow: 0 0 20px rgba(102, 126, 234, 0.2);
            transform: translateY(-2px);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: rgba(255, 255, 255, 0.6);
            cursor: pointer;
            font-size: 18px;
            padding: 5px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .password-toggle:hover {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            font-size: 14px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255, 255, 255, 0.8);
            cursor: pointer;
        }

        .remember-me input {
            width: 16px;
            height: 16px;
            accent-color: #667eea;
        }

        .forgot-password {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .forgot-password:hover {
            color: white;
            text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .submit-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .submit-btn:hover::before {
            left: 100%;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .social-login {
            margin-top: 30px;
            text-align: center;
        }

        .social-divider {
            color: rgba(255, 255, 255, 0.6);
            font-size: 14px;
            margin-bottom: 20px;
            position: relative;
        }

        .social-divider::before,
        .social-divider::after {
            content: '';
            position: absolute;
            top: 50%;
            width: 30%;
            height: 1px;
            background: rgba(255, 255, 255, 0.3);
        }

        .social-divider::before {
            left: 0;
        }

        .social-divider::after {
            right: 0;
        }

        .social-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
        }

        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: white;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .social-btn.google {
            background: linear-gradient(135deg, #dd4b39, #c23321);
        }

        .social-btn.facebook {
            background: linear-gradient(135deg, #3b5998, #2d4373);
        }

        .social-btn.github {
            background: linear-gradient(135deg, #333, #24292e);
        }

        .social-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }

        .error-message {
            background: rgba(244, 67, 54, 0.2);
            border: 1px solid rgba(244, 67, 54, 0.3);
            color: #ff8a80;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        .success-message {
            background: rgba(76, 175, 80, 0.2);
            border: 1px solid rgba(76, 175, 80, 0.3);
            color: #a5d6a7;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
            display: none;
        }

        @media (max-width: 480px) {
            .auth-container {
                padding: 30px 25px;
                margin: 10px;
            }

            .auth-title {
                font-size: 24px;
            }

            .form-input {
                padding: 12px 16px;
            }

            .social-buttons {
                gap: 10px;
            }

            .social-btn {
                width: 45px;
                height: 45px;
                font-size: 18px;
            }
        }

        .loading-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            display: none;
            margin-right: 10px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .btn-content {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


</head>

<body>
    <div class="auth-container">
        <div class="auth-header">
            <div class="logo">💬</div>
            <h1 class="auth-title">Chat Realtime</h1>
            <p class="auth-subtitle">Kết nối và trò chuyện với bạn bè</p>
        </div>

        <div class="auth-tabs">
            <button class="auth-tab active" data-tab="login">Đăng nhập</button>
            <button class="auth-tab" data-tab="register">Đăng ký</button>
        </div>

        <div class="error-message" id="errorMessage"></div>
        <div class="success-message" id="successMessage"></div>

        <!-- Login Form -->
        <form class="auth-form active" id="loginForm" action ="{{ route('login.post') }}" method="POST">
            @csrf
            @method('POST')
            <div class="form-group">
                <label class="form-label">Email hoặc Số điện thoại</label>
                <input type="text" name="identifier" class="form-input" placeholder="Nhập email hoặc số điện thoại"
                    required id="loginIdentifier">
            </div>
            <div class="form-group">
                <label class="form-label">Mật khẩu</label>
                <div style="position: relative;">
                    <input type="password" name="password" class="form-input" placeholder="Nhập mật khẩu" required
                        id="loginPassword">
                    <button type="button" class="password-toggle" onclick="togglePassword('loginPassword')">
                        👁️
                    </button>
                </div>
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" id="rememberMe">
                    <span>Ghi nhớ tôi</span>
                </label>
                <a href="#" class="forgot-password" onclick="showForgotPassword()">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="submit-btn">
                <div class="btn-content">
                    <div class="loading-spinner" id="loginSpinner"></div>
                    <span id="loginBtnText">Đăng nhập</span>
                </div>
            </button>
        </form>


        <!-- Register Form -->
        <form class="auth-form" id="registerForm" method="POST" action="{{ route('register.post') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">Họ và tên</label>
                <input type="text" class="form-input" placeholder="Nhập họ và tên" required name="name"
                    id="name">
            </div>

            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" class="form-input" placeholder="Nhập địa chỉ email" required name="email"
                    id="email">
            </div>

            <div class="form-group">
                <label class="form-label">Số điện thoại</label>
                <input type="tel" class="form-input" placeholder="Nhập số điện thoại" required name="phone"
                    id="phone">
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu</label>
                <div style="position: relative;">
                    <input type="password" class="form-input" placeholder="Nhập mật khẩu (ít nhất 6 ký tự)" required
                        minlength="6" name="password" id="registerPassword">
                    <button type="button" class="password-toggle" onclick="togglePassword('registerPassword')">
                        👁️
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Xác nhận mật khẩu</label>
                <div style="position: relative;">
                    <input type="password" class="form-input" placeholder="Nhập lại mật khẩu" required
                        name="password_confirmation" id="confirmPassword">
                    <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                        👁️
                    </button>
                </div>
            </div>

            <div class="form-options">
                <label class="remember-me">
                    <input type="checkbox" required id="agreeTerms">
                    <span>Tôi đồng ý với <a href="#" style="color: rgba(255,255,255,0.9);">Điều khoản sử
                            dụng</a></span>
                </label>
            </div>

            <button type="submit" class="submit-btn">
                <div class="btn-content">
                    <div class="loading-spinner" id="registerSpinner"></div>
                    <span id="registerBtnText">Đăng ký</span>
                </div>
            </button>
        </form>

        <div class="social-login">
            <div class="social-divider">Hoặc tiếp tục với</div>
            <div class="social-buttons">
                <button class="social-btn google" onclick="socialLogin('google')">G</button>
                <button class="social-btn facebook" onclick="socialLogin('facebook')">f</button>
                <button class="social-btn github" onclick="socialLogin('github')">⚡</button>
            </div>
        </div>
    </div>
    @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Thành công!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <script>
        // Elements
        const authTabs = document.querySelectorAll('.auth-tab');
        const login = document.getElementById('loginForm');
        const registerForm = document.getElementById('registerForm');
        const errorMessage = document.getElementById('errorMessage');
        const successMessage = document.getElementById('successMessage');

        // Tab switching - Sửa lại logic này
        authTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                authTabs.forEach(t => t.classList.remove('active'));
                // Add active class to clicked tab
                tab.classList.add('active');

                // Hide all forms first
                login.classList.remove('active');
                registerForm.classList.remove('active');

                // Show the appropriate form
                const tabType = tab.dataset.tab;
                if (tabType === 'login') {
                    loginForm.classList.add('active');
                } else if (tabType === 'register') {
                    registerForm.classList.add('active');
                }

                hideMessages();
            });
        });

        // Toggle password visibility
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const toggle = input.nextElementSibling;

            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = '🙈';
            } else {
                input.type = 'password';
                toggle.textContent = '👁️';
            }
        }

        // Show/hide messages
        function showError(message) {
            errorMessage.textContent = message;
            errorMessage.style.display = 'block';
            successMessage.style.display = 'none';
        }

        function showSuccess(message) {
            successMessage.textContent = message;
            successMessage.style.display = 'block';
            errorMessage.style.display = 'none';
        }

        function hideMessages() {
            errorMessage.style.display = 'none';
            successMessage.style.display = 'none';
        }

        // Login form submission
        // loginForm.addEventListener('submit', async (e) => {
        //     e.preventDefault();

        //     const identifier = document.getElementById('loginIdentifier').value;
        //     const password = document.getElementById('loginPassword').value;
        //     const rememberMe = document.getElementById('rememberMe').checked;

        //     // Show loading
        //     const spinner = document.getElementById('loginSpinner');
        //     const btnText = document.getElementById('loginBtnText');
        //     spinner.style.display = 'block';
        //     btnText.textContent = 'Đang đăng nhập...';

        //     // Simulate API call
        //     try {
        //         await simulateLogin(identifier, password, rememberMe);

        //         setTimeout(() => {
        //             // Redirect to chat interface (thay thế bằng URL thực tế)
        //             window.location.href = '#chat';
        //         }, 1500);

        //     } catch (error) {
        //         showError(error.message);
        //     } finally {
        //         // Hide loading
        //         spinner.style.display = 'none';
        //         btnText.textContent = 'Đăng nhập';
        //     }
        // });

        // Register form submission



        // Simulate login API



        // Social login
        function socialLogin(provider) {
            showSuccess(`Đang kết nối với ${provider.charAt(0).toUpperCase() + provider.slice(1)}...`);

            setTimeout(() => {
                showSuccess(`Đăng nhập ${provider} thành công! Đang chuyển hướng...`);
                setTimeout(() => {
                    window.location.href = '#chat';
                }, 1500);
            }, 2000);
        }

        // Forgot password
        function showForgotPassword() {
            const email = prompt('Nhập email của bạn để khôi phục mật khẩu:');
            if (email) {
                showSuccess('Link khôi phục mật khẩu đã được gửi đến email của bạn!');
            }
        }

        // Check if user is already logged in
        window.addEventListener('load', () => {
            const savedUser = localStorage.getItem('chatUser');
            if (savedUser) {
                const userData = JSON.parse(savedUser);
                document.getElementById('loginIdentifier').value = userData.identifier;
                document.getElementById('rememberMe').checked = true;
            }
        });

        // Phone number formatting
        document.getElementById('phone').addEventListener('input', (e) => {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 10) {
                value = value.slice(0, 10);
            }
            e.target.value = value;
        });

        // Real-time password strength indicator (simple)
        document.getElementById('registerPassword').addEventListener('input', (e) => {
            const password = e.target.value;
            const strength = getPasswordStrength(password);

            // You can add visual feedback here
            e.target.style.borderColor = strength >= 3 ? 'rgba(76, 175, 80, 0.5)' :
                strength >= 2 ? 'rgba(255, 193, 7, 0.5)' :
                'rgba(244, 67, 54, 0.5)';
        });

        function getPasswordStrength(password) {
            let strength = 0;
            if (password.length >= 6) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;
            return strength;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
