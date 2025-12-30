<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Servis Mobil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%);
        }
        .register-card {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(26, 29, 41, 0.08);
        }
        .form-control, .form-select {
            border-radius: 10px;
            padding: 12px 16px;
            border: 1px solid #cbd5e1;
            font-size: 0.95rem;
        }
        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        .btn-primary {
            background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%);
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1rem;
        }
        .btn-primary:hover {
            background: linear-gradient(179.1deg, rgba(0,98,133,1) -1.9%, rgba(0,165,198,1) 91.8%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-md-5">
                <div class="card register-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h2 class="fw-bold mb-2" style="color: #1a1d29; font-size: 2rem;">Daftar</h2>
                            <p style="color: #6b7280; font-size: 0.95rem;">Buat akun baru Anda</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('register') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold" style="color: #1a1d29;">Nama Lengkap</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold" style="color: #1a1d29;">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="nama@email.com" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label fw-semibold" style="color: #1a1d29;">No. Telepon</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="08xxxxxxxxxx" required>
                            </div>

                            <div class="mb-3">
                                <label for="address" class="form-label fw-semibold" style="color: #1a1d29;">Alamat</label>
                                <textarea class="form-control" id="address" name="address" rows="2" placeholder="Masukkan alamat lengkap" required>{{ old('address') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold" style="color: #1a1d29;">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Minimal 8 karakter" required>
                            </div>

                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold" style="color: #1a1d29;">Konfirmasi Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 mb-4">
                                Daftar
                            </button>
                        </form>

                        <div class="text-center">
                            <p style="color: #6b7280; font-size: 0.95rem;">
                                Sudah punya akun? <a href="{{ route('login') }}" style="color: #3b82f6; font-weight: 600; text-decoration: none;">Masuk</a>
                            </p>
                            <a href="/" style="color: #6b7280; font-size: 0.9rem; text-decoration: none;">
                                ← Kembali ke beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
