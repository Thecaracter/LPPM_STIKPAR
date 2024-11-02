@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Profile</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name', $user->name) }}" required>
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Email <span class="text-danger">*</span></label>
                                            <input type="email" class="form-control" name="email"
                                                value="{{ old('email', $user->email) }}" required>
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>NIK <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nik"
                                                value="{{ old('nik', $user->nik) }}" required maxlength="16">
                                            @error('nik')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>NIDN/NUPTK <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="nidn_nuptk"
                                                value="{{ old('nidn_nuptk', $user->nidn_nuptk) }}" required>
                                            @error('nidn_nuptk')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Tempat Lahir <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="tempat_lahir"
                                                value="{{ old('tempat_lahir', $user->tempat_lahir) }}" required>
                                            @error('tempat_lahir')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Tanggal Lahir <span class="text-danger">*</span></label>
                                            <input type="date" class="form-control" name="tanggal_lahir"
                                                value="{{ old('tanggal_lahir', $user->tanggal_lahir?->format('Y-m-d')) }}"
                                                required>
                                            @error('tanggal_lahir')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Jabatan Akademik <span class="text-danger">*</span></label>
                                            <select class="form-control" name="jabatan_akademik" required>
                                                <option value="">Pilih Jabatan</option>
                                                @foreach ($jabatanOptions as $jabatan)
                                                    <option value="{{ $jabatan }}"
                                                        {{ old('jabatan_akademik', $user->jabatan_akademik) == $jabatan ? 'selected' : '' }}>
                                                        {{ $jabatan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('jabatan_akademik')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Program Studi <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="program_studi"
                                                value="{{ old('program_studi', $user->program_studi) }}" required>
                                            @error('program_studi')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Bidang Keahlian <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="bidang_keahlian"
                                                value="{{ old('bidang_keahlian', $user->bidang_keahlian) }}" required>
                                            @error('bidang_keahlian')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Nomor HP <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="no_hp"
                                                value="{{ old('no_hp', $user->no_hp) }}" required>
                                            @error('no_hp')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Alamat Domisili <span class="text-danger">*</span></label>
                                            <textarea class="form-control" name="alamat_domisili" rows="3" required>{{ old('alamat_domisili', $user->alamat_domisili) }}</textarea>
                                            @error('alamat_domisili')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h4 class="card-title">Ubah Password</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>Password Saat Ini</label>
                                            <input type="password" class="form-control" name="current_password">
                                            @error('current_password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Password Baru</label>
                                            <input type="password" class="form-control" name="password">
                                            @error('password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label>Konfirmasi Password Baru</label>
                                            <input type="password" class="form-control" name="password_confirmation">
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right mt-4">
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: '{{ session('error') }}',
                });
            @endif
        </script>
    @endpush
@endsection
