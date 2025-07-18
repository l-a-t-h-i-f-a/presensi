{{--<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="{{ asset('loginpage/assets/css/styles.css') }}">

    <!-- ===== BOX ICONS ===== -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css" rel="stylesheet">

    <title>Sistem Absensi</title>
</head>
<body>
    <div class="login">
        <div class="login__content">
            <div class="login__img">
                <img src="{{ asset('loginpage/assets/img/login.png') }}" alt="Login Image">
            </div>

            <div class="login__forms">
                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" class="login__registre" id="login-in">
                    @csrf
                    <h1 class="login__title">Login</h1>

                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="email" placeholder="Email" class="login__input" required>
                    </div>

                    <div class="login__box">
                        <i class='bx bx-lock-alt login__icon'></i>
                        <input type="password" name="password" placeholder="Password" class="login__input" required>
                    </div>

                    <a href="{{ route('password.request') }}" class="login__forgot">Forgot password?</a>

                    <button type="submit" class="login__button">{{ __('Login') }}</button>

                    <div>
                        <span class="login__account">Don't have an Account?</span>
                        <span class="login__signin" id="sign-up">Sign Up</span>
                    </div>
                </form>

                <!-- Sign Up Form -->
                <form action="{{ route('register') }}" method="POST" class="login__create none" id="login-up">
                    @csrf
                    <h1 class="login__title">Create Account</h1>

                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="name" placeholder="Username" class="login__input" required>
                    </div>

                    <div class="login__box">
                        <i class='bx bx-at login__icon'></i>
                        <input type="email" name="email" placeholder="Email" class="login__input" required>
                    </div>

                    <div class="login__box">
                        <i class='bx bx-lock-alt login__icon'></i>
                        <input type="password" name="password" placeholder="Password" class="login__input" required>
                    </div>

                    <div class="login__box">
                        <i class='bx bx-lock-alt login__icon'></i>
                        <input type="password" name="password_confirmation" placeholder="Password" class="login__input" required>
                    </div>

                    <button type="submit" class="login__button">Sign Up</button>

                    <div>
                        <span class="login__account">Already have an Account?</span>
                        <span class="login__signup" id="sign-in">Login</span>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!--===== MAIN JS =====-->
    <script src="{{ asset('loginpage/assets/js/main.js') }}"></script>
</body>
</html>
