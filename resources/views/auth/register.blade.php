@extends('layouts.auth')

@section('content')
    <section class="section">
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <!-- Logo -->
                    <div class="text-center mb-5">
                        <img src="{{ asset('custom/assetsFoto/logo.png') }}" alt="Logo" class="img-fluid"
                            style="width: 180px">
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h4 class="mb-4">Register</h4>

                            <form method="POST" action="{{ route('register') }}" class="needs-validation" novalidate="">
                                @csrf

                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama Lengkap Beserta gerlar</label>
                                    <div class="input-group">
                                        <div class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-user text-muted"></i>
                                        </div>
                                        <input type="text" class="form-control border-start-0" id="name"
                                            name="name" value="{{ old('name') }}" required
                                            placeholder="Contoh: John Doe S.kom">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <div class="input-group">
                                        <div class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-envelope text-muted"></i>
                                        </div>
                                        <input type="email" class="form-control border-start-0" id="email"
                                            name="email" value="{{ old('email') }}" required
                                            placeholder="Contoh: john.doe@email.com">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="nik" class="form-label">NIK</label>
                                    <div class="input-group">
                                        <div class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-id-card text-muted"></i>
                                        </div>
                                        <input type="text" class="form-control border-start-0" id="nik"
                                            name="nik" value="{{ old('nik') }}" required
                                            placeholder="Contoh: 3517xxxxxxxx">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="nidn_nuptk" class="form-label">NIDN/NUPTK</label>
                                    <div class="input-group">
                                        <div class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-id-badge text-muted"></i>
                                        </div>
                                        <input type="text" class="form-control border-start-0" id="nidn_nuptk"
                                            name="nidn_nuptk" value="{{ old('nidn_nuptk') }}"
                                            placeholder="Contoh: 0012345678">
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                        <div class="input-group">
                                            <div class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-map-marker-alt text-muted"></i>
                                            </div>
                                            <input type="text" class="form-control border-start-0" id="tempat_lahir"
                                                name="tempat_lahir" value="{{ old('tempat_lahir') }}" required
                                                placeholder="Contoh: Jakarta">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                        <div class="input-group">
                                            <div class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-calendar text-muted"></i>
                                            </div>
                                            <input type="date" class="form-control border-start-0" id="tanggal_lahir"
                                                name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="jabatan_akademik" class="form-label">Jabatan Akademik</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-user-graduate text-muted"></i>
                                        </span>
                                        <select class="form-select form-control border-start-0" id="jabatan_akademik"
                                            name="jabatan_akademik" required>
                                            <option value="">Pilih Jabatan Akademik</option>
                                            @foreach (['Asisten Ahli', 'Lektor', 'Lektor Kepala', 'Guru Besar', 'Belum Memiliki Jabatan Akademik'] as $jabatan)
                                                <option value="{{ $jabatan }}"
                                                    {{ old('jabatan_akademik') == $jabatan ? 'selected' : '' }}>
                                                    {{ $jabatan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="bidang_keahlian" class="form-label">Bidang Keahlian</label>
                                        <div class="input-group">
                                            <div class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-brain text-muted"></i>
                                            </div>
                                            <input type="text" class="form-control border-start-0"
                                                id="bidang_keahlian" name="bidang_keahlian"
                                                value="{{ old('bidang_keahlian') }}" required
                                                placeholder="Contoh: Teknologi Informasi">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="program_studi" class="form-label">Program Studi</label>
                                        <div class="input-group">
                                            <div class="input-group-text bg-white border-end-0">
                                                <i class="fas fa-graduation-cap text-muted"></i>
                                            </div>
                                            <input type="text" class="form-control border-start-0" id="program_studi"
                                                name="program_studi" value="{{ old('program_studi') }}" required
                                                placeholder="Contoh: Teknik Informatika">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="alamat_domisili" class="form-label">Alamat Domisili</label>
                                    <div class="input-group">
                                        <textarea class="form-control border-start-0" id="alamat_domisili" name="alamat_domisili" rows="2" required
                                            placeholder="Contoh: Jl. Contoh No. 123, RT 01/RW 02, Kelurahan Contoh, Kecamatan Contoh, Kota Contoh">{{ old('alamat_domisili') }}</textarea>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="no_hp" class="form-label">Nomor HP</label>
                                    <div class="input-group">
                                        <div class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-phone text-muted"></i>
                                        </div>
                                        <input type="text" class="form-control border-start-0" id="no_hp"
                                            name="no_hp" value="{{ old('no_hp') }}" required
                                            placeholder="Contoh: 08123456789">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">
                                        <div class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </div>
                                        <input type="password" class="form-control border-start-0 border-end-0"
                                            id="password" name="password" required placeholder="Masukkan password">
                                        <button type="button" class="input-group-text bg-white border-start-0"
                                            id="togglePassword">
                                            <i class="fas fa-eye text-muted"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                    <div class="input-group">
                                        <div class="input-group-text bg-white border-end-0">
                                            <i class="fas fa-lock text-muted"></i>
                                        </div>
                                        <input type="password" class="form-control border-start-0 border-end-0"
                                            id="password_confirmation" name="password_confirmation" required
                                            placeholder="Konfirmasi password">
                                        <button type="button" class="input-group-text bg-white border-start-0"
                                            id="toggleConfirmPassword">
                                            <i class="fas fa-eye text-muted"></i>
                                        </button>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                                    Register
                                </button>

                                <div class="text-center">
                                    <p class="mb-0">Sudah punya akun? <a href="{{ route('login') }}"
                                            class="text-decoration-none">Login</a></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @if (session('alert'))
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const alert = @json(session('alert'));
                if (alert) {
                    Swal.fire({
                        icon: alert.type,
                        title: alert.type.charAt(0).toUpperCase() + alert.type.slice(1),
                        text: alert.message,
                        confirmButtonText: 'Okay'
                    });
                }
            });
        </script>
    @endif
    <script src="{{ asset('script/auth.js') }}"></script>

    <style>
        .form-control:focus,
        .form-select:focus {
            border-color: #dee2e6 !important;
            box-shadow: none !important;
            outline: 0 !important;
        }

        .input-group-text {
            background-color: transparent;
        }

        .card {
            border-radius: 10px;
        }

        .btn-primary {
            border-radius: 5px;
        }

        .form-select {
            border-left: 0 !important;
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: none !important;
        }

        .input-group .form-select {
            padding-left: 0;
        }
    </style>
@endsection
