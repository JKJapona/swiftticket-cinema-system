<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authentication</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="auth-wrapper">
        <div class="auth-card-container">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const forms = [
                { id: 'loginForm', btn: 'loginBtn', spinner: 'loginSpinner', text: 'loginText', msg: 'Signing in...' },
                { id: 'registerForm', btn: 'registerBtn', spinner: 'registerSpinner', text: 'registerText', msg: 'Creating account...' }
            ];

            forms.forEach(formSetup => {
                const formEl = document.getElementById(formSetup.id);
                if (formEl) {
                    formEl.addEventListener('submit', function() {
                        const btn = document.getElementById(formSetup.btn);
                        const spinner = document.getElementById(formSetup.spinner);
                        const text = document.getElementById(formSetup.text);

                        btn.disabled = true;
                        btn.style.opacity = '0.8';

                        spinner.classList.remove('d-none');
                        text.textContent = formSetup.msg;
                    });
                }
            });
        });
    </script>
</body>
</html>