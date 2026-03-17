<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #c9cdd4;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            background: #ffffff;
            border-radius: 20px;
            padding: 48px 40px 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 4px 30px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Avatar / Logo */
        .avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background-color: #1a5c54;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 24px;
            box-shadow: 0 4px 14px rgba(26,92,84,0.35);
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .avatar-icon {
            font-size: 36px;
            line-height: 1;
        }

        /* Heading */
        .title {
            font-size: 26px;
            font-weight: 800;
            color: #111827;
            margin-bottom: 6px;
            letter-spacing: -0.3px;
        }

        .subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 32px;
            font-weight: 400;
        }

        /* Form */
        .form {
            width: 100%;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #374151;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 14px;
            color: #9ca3af;
            display: flex;
            align-items: center;
            pointer-events: none;
        }

        .form-input {
            width: 100%;
            padding: 13px 14px 13px 42px;
            border: none;
            border-radius: 10px;
            background-color: #d1d5db;
            font-family: 'Nunito', sans-serif;
            font-size: 14px;
            color: #111827;
            outline: none;
            transition: background 0.2s, box-shadow 0.2s;
        }

        .form-input::placeholder {
            color: #6b7280;
        }

        .form-input:focus {
            background-color: #c5cdd8;
            box-shadow: 0 0 0 2px #1a5c5440;
        }

        .toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            cursor: pointer;
            color: #9ca3af;
            display: flex;
            align-items: center;
            padding: 0;
            transition: color 0.2s;
        }

        .toggle-password:hover {
            color: #1a5c54;
        }

        /* Error messages */
        .error-msg {
            color: #ef4444;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        /* Submit button */
        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #1a5c54;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-family: 'Nunito', sans-serif;
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            cursor: pointer;
            margin-top: 8px;
            transition: background 0.2s, transform 0.1s, box-shadow 0.2s;
        }

        .btn-submit:hover {
            background-color: #154d47;
            box-shadow: 0 4px 14px rgba(26,92,84,0.3);
        }

        .btn-submit:active {
            transform: scale(0.99);
        }

        /* Forgot password */
        .forgot-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: #1a5c54;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .forgot-link:hover {
            color: #154d47;
            text-decoration: underline;
        }

        /* Alert errors from Laravel */
        .alert-error {
            width: 100%;
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #b91c1c;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="card">

        {{-- Logo / Avatar --}}
        <div class="avatar">
            <img src="{{ asset('images/colegios.jpg') }}" alt="Logo">
        </div>

        <h1 class="title">Bienvenido</h1>
        <p class="subtitle">Ingresá tus credenciales para continuar</p>

        {{-- Session errors --}}
        @if ($errors->any())
            <div class="alert-error">
                {{ $errors->first() }}
            </div>
        @endif

        <form class="form" method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Usuario --}}
            <div class="form-group">
                <label class="form-label" for="username">Usuario</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    <input
                        id="username"
                        type="text"
                        name="username"
                        class="form-input"
                        placeholder="Ingresá tu usuario"
                        value="{{ old('username') }}"
                        required
                        autofocus
                        autocomplete="username"
                    >
                </div>
                @error('username')
                <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- Clave --}}
            <div class="form-group">
                <label class="form-label" for="password">Clave</label>
                <div class="input-wrapper">
                    <span class="input-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0 1 10 0v4"/>
                        </svg>
                    </span>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        class="form-input"
                        placeholder="Ingresá tu clave"
                        required
                        autocomplete="current-password"
                    >
                    <button type="button" class="toggle-password" onclick="togglePassword()" id="toggleBtn" aria-label="Mostrar clave">
                        <svg id="eye-icon" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- Botón --}}
            <button type="submit" class="btn-submit">Entrar</button>

        </form>

        {{-- Olvidó su clave --}}
        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" class="forgot-link">¿Olvidó su clave?</a>
        @endif

    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';

            icon.innerHTML = isHidden
                ? `<line x1="1" y1="1" x2="23" y2="23"/>
                   <path stroke-linecap="round" stroke-linejoin="round" d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                   <path stroke-linecap="round" stroke-linejoin="round" d="M6.53 6.53A10 10 0 0 0 1 12s4 8 11 8a9.98 9.98 0 0 0 5.46-1.61"/>
                   <path stroke-linecap="round" stroke-linejoin="round" d="M14.12 14.12A3 3 0 1 1 9.88 9.88"/>`
                : `<path stroke-linecap="round" stroke-linejoin="round" d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                   <circle cx="12" cy="12" r="3"/>`;
        }
    </script>

</body>
</html>