<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SPPG Terpadu</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" crossorigin="anonymous">
    <!-- Required Plugin(AdminLTE) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@4.0.0-rc7/dist/css/adminlte.min.css">
</head>
<body class="d-flex align-items-center justify-content-center vh-100 bg-light">

<div class="login-box" style="width: 400px; max-width: 90%;">
    <div class="card card-outline card-primary shadow">
        <div class="card-header text-center py-4">
            <h3 class="fw-bold mb-1"><i class="bi bi-shield-check me-2"></i>SPPG Terpadu</h3>
            <p class="text-muted mb-0 small">Sistem Pemenuhan Gizi Masyarakat</p>
        </div>
        <div class="card-body p-4">
            <p class="login-box-msg text-center mb-4">Masuk untuk memulai sesi Anda</p>

            <!-- Tampilkan Pesan Sukses / Info / Error dari Session -->
            @if(session('error'))
                <div class="alert alert-danger text-center mb-3">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="alert alert-info text-center mb-3">
                    <i class="bi bi-info-circle-fill me-1"></i> {{ session('info') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success text-center mb-3">
                    <i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('login.proses') }}" method="post">
                @csrf
                
                <!-- Input Identitas (Username/Email/NIK/NISN) -->
                <div class="mb-3">
                    <label for="identitas" class="form-label fw-bold">Identitas Pengguna</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="identitas" id="identitas" 
                               class="form-control @error('identitas') is-invalid @enderror" 
                               placeholder="Username / Email / NIK / NISN" 
                               value="{{ old('identitas') }}" required autofocus>
                    </div>
                    @error('identitas')
                        <div class="invalid-feedback d-block mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Input Password / PIN -->
                <div class="mb-4">
                    <label for="password" class="form-label fw-bold">Password / PIN</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" id="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Masukkan password atau PIN" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <!-- Tombol Submit -->
                <div class="d-grid gap-2 mb-3">
                    <button type="submit" class="btn btn-primary btn-block py-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i> MASUK
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <a href="{{ route('landing') }}" class="text-decoration-none small">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Beranda
                </a>
            </div>
        </div>
        <div class="card-footer text-center text-muted small py-3">
            <strong>Copyright &copy; 2026.</strong> SPPG Terpadu.
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const toggleIcon = document.querySelector('#toggleIcon');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            // toggle the eye / eye slash icon
            toggleIcon.classList.toggle('bi-eye');
            toggleIcon.classList.toggle('bi-eye-slash');
        });
    });
</script>
</body>
</html>
