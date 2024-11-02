@extends('layouts.app')
@section('title', 'User')
@section('content')
    <div class="container">
        <div class="page-inner">
            <!-- Reviewer Section -->
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 class="card-title mb-0">Reviewer</h4>
                        <button class="btn btn-primary btn-round rounded-pill" data-toggle="modal"
                            data-target="#addReviewerModal">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Reviewer
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="reviewerTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>EMAIL</th>
                                    <th>NIK</th>
                                    <th>NIDN/NUPTK</th>
                                    <th>JABATAN</th>
                                    <th>PROGRAM STUDI</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users->where('role', 'reviewer') as $reviewer)
                                    <tr>
                                        <td>{{ $reviewer->email }}</td>
                                        <td>{{ $reviewer->nik }}</td>
                                        <td>{{ $reviewer->nidn_nuptk }}</td>
                                        <td>{{ $reviewer->jabatan_akademik }}</td>
                                        <td>{{ $reviewer->program_studi }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $reviewer->id }}, 'reviewer')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $reviewer->id }}"
                                                action="{{ route('user.destroy', $reviewer->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- User Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">User</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="userTable" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>EMAIL</th>
                                    <th>NIK</th>
                                    <th>PROGRAM STUDI</th>
                                    <th>JABATAN</th>
                                    <th>AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users->where('role', 'user') as $user)
                                    <tr>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->nik }}</td>
                                        <td>{{ $user->program_studi }}</td>
                                        <td>{{ $user->jabatan_akademik }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $user->id }}, 'user')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="delete-form-{{ $user->id }}"
                                                action="{{ route('user.destroy', $user->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Reviewer Modal -->
    <div class="modal fade" id="addReviewerModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Reviewer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('user.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="form-group">
                            <label>Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="form-group">
                            <label>Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>NIK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nik" required maxlength="16">
                        </div>
                        <div class="form-group">
                            <label>NIDN/NUPTK <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="nidn_nuptk" required>
                        </div>
                        <div class="form-group">
                            <label>Program Studi <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="program_studi" required>
                        </div>
                        <div class="form-group">
                            <label>Jabatan Akademik <span class="text-danger">*</span></label>
                            <select class="form-control" name="jabatan_akademik" required>
                                <option value="">Pilih Jabatan</option>
                                @foreach ($jabatanOptions as $jabatan)
                                    <option value="{{ $jabatan }}">{{ $jabatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Bidang Keahlian <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="bidang_keahlian" required>
                        </div>
                        <div class="form-group">
                            <label>Tempat Lahir <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="tempat_lahir" required>
                        </div>
                        <div class="form-group">
                            <label>Tanggal Lahir <span class="text-danger">*</span></label>
                            <input type="date" class="form-control" name="tanggal_lahir" required>
                        </div>
                        <div class="form-group">
                            <label>Alamat Domisili <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="alamat_domisili" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>Nomor HP <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="no_hp" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function confirmDelete(id, type) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: `Anda akan menghapus ${type} ini!`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }

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

            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    html: '@foreach ($errors->all() as $error){{ $error }}<br>@endforeach',
                });
            @endif
        </script>
    @endpush
@endsection
