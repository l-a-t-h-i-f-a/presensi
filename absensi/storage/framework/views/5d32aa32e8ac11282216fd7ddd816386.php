<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sistem Absensi</title>
    <link href="https://fonts.googleapis.com/css?family=Heebo:400,700|IBM+Plex+Sans:600" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo e(asset('welcomepage')); ?>/dist/css/style.css">
    <script src="https://unpkg.com/scrollreveal@4.0.0/dist/scrollreveal.min.js"></script>
    <link rel="shortcut icon" href="<?php echo e(asset('adminpage')); ?>/template/images/logo.png" />
    
</head>
<body class="is-boxed has-animations">
    <div class="body-wrap boxed-container">
        <header class="site-header">
            <div class="container">
                <div class="site-header-inner">
                    <div class="brand header-brand">
                        <h1 class="m-0">
							<img class="header-logo-image asset-light" src="<?php echo e(asset('adminpage')); ?>/template/images/logo.png" alt="Logo">
                        </h1>
                    </div>
                </div>
            </div>
        </header>

        <main>
            <section class="hero">
                <div class="container">
                    <div class="hero-inner">
						<div class="hero-copy">
	                        <h1 class="hero-title mt-0">Sistem Absensi UMPKU Surakarta</h1>
	                        <p class="hero-paragraph">Sistem Absensi UMPKU Surakarta adalah platform digital yang dirancang untuk mempermudah pencatatan kehadiran dosen dan staf secara real-time.</p>
	                        <div class="hero-cta">
								<a class="button button-primary" href="<?php echo e(route('login')); ?>">Get Started</a>
								<div class="lights-toggle">
									<input id="lights-toggle" type="checkbox" name="lights-toggle" class="switch" checked="checked">
								</div>
							</div>
						</div>
						<div class="hero-media">
							<div class="header-illustration">
								<img class="header-illustration-image asset-light" src="<?php echo e(asset('welcomepage')); ?>/dist/images/header-illustration-light.svg" alt="Header illustration">
							</div>
							<div class="hero-media-illustration">
								<img class="hero-media-illustration-image asset-light" src="<?php echo e(asset('welcomepage')); ?>/dist/images/hero-media-illustration-light.svg" alt="Hero media illustration">
							</div>
							<div class="hero-media-container">
								<img class="hero-media-image asset-light" src="<?php echo e(asset('welcomepage')); ?>/dist/images/hero-media-light.svg" alt="Hero media">
							</div>
						</div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script src="<?php echo e(asset('welcomepage')); ?>/dist/js/main.min.js"></script>
</body>
</html>
<?php /**PATH C:\laragon\www\absen-fiks\absensi\resources\views/welcomepage.blade.php ENDPATH**/ ?>