

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- ===== CSS ===== -->
    <link rel="stylesheet" href="<?php echo e(asset('loginpage/assets/css/styles.css')); ?>">

    <!-- ===== BOX ICONS ===== -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css" rel="stylesheet">

    <title>Sistem Absensi</title>
</head>
<body>
    <div class="login">
        <div class="login__content">
            <div class="login__img">
                <img src="<?php echo e(asset('loginpage/assets/img/login.png')); ?>" alt="Login Image">
            </div>

            <div class="login__forms">
                <!-- Login Form -->
                <form action="<?php echo e(route('login')); ?>" method="POST" class="login__registre" id="login-in">
                    <?php echo csrf_field(); ?>
                    <h1 class="login__title">Login</h1>

                    <div class="login__box">
                        <i class='bx bx-user login__icon'></i>
                        <input type="text" name="email" placeholder="Email" class="login__input" required>
                    </div>

                    <div class="login__box">
                        <i class='bx bx-lock-alt login__icon'></i>
                        <input type="password" name="password" placeholder="Password" class="login__input" required>
                    </div>

                    <a href="<?php echo e(route('password.request')); ?>" class="login__forgot">Forgot password?</a>

                    <button type="submit" class="login__button"><?php echo e(__('Login')); ?></button>

                    <div>
                        <span class="login__account">Don't have an Account?</span>
                        <span class="login__signin" id="sign-up">Sign Up</span>
                    </div>
                </form>

                <!-- Sign Up Form -->
                <form action="<?php echo e(route('register')); ?>" method="POST" class="login__create none" id="login-up">
                    <?php echo csrf_field(); ?>
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
    <script src="<?php echo e(asset('loginpage/assets/js/main.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\absen-fiks\absensi\resources\views/auth/login.blade.php ENDPATH**/ ?>