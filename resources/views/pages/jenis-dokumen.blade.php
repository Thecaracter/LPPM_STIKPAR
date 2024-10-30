@extends('layouts.app')
@section('title', 'Jenis Dokumen')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Daftar Jenis Dokumen</h4>
                            <button type="button" class="btn btn-success" data-toggle="modal"
                                data-target="#createJenisDokumenModal">
                                <i class="fa fa-plus"></i> Tambah Jenis Dokumen
                            </button>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th class="px-4 py-2">No</th>
                                            <th class="px-4 py-2">Nama Jenis Dokumen</th>
                                            <th class="px-4 py-2">Kriteria Penilaian</th>
                                            <th class="px-4 py-2">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($jenisDokumen as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->nama }}</td>
                                                <td><a href="{{ route('kriteria-penilaian.index', $item->id) }}"
                                                        class="btn btn-sm btn-info me-2 mr-2">
                                                        <i class="fas fa-list"></i> Detail Kriteria
                                                    </a></td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-warning me-2 mr-2"
                                                            data-toggle="modal"
                                                            data-target="#editJenisDokumenModal{{ $item->id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete('{{ $item->id }}')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('jenis-dokumen.destroy', $item->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="text-center">Tidak ada data jenis dokumen</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Jenis Dokumen Modal -->
    <div class="modal fade" id="createJenisDokumenModal" tabindex="-1" role="dialog"
        aria-labelledby="createJenisDokumenModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('jenis-dokumen.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createJenisDokumenModalLabel">Tambah Jenis Dokumen Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama">Nama Jenis Dokumen</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama"
                                name="nama" required value="{{ old('nama') }}">
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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

    @foreach ($jenisDokumen as $item)
        <!-- Edit Jenis Dokumen Modal -->
        <div class="modal fade" id="editJenisDokumenModal{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editJenisDokumenModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('jenis-dokumen.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editJenisDokumenModalLabel{{ $item->id }}">Edit Jenis Dokumen
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="nama{{ $item->id }}">Nama Jenis Dokumen</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror"
                                    id="nama{{ $item->id }}" name="nama" value="{{ old('nama', $item->nama) }}"
                                    required>
                                @error('nama')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection
@push('scripts')
    <script>
        // Fungsi untuk konfirmasi delete
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data jenis dokumen ini akan dihapus permanen!",
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

        // Notifikasi Success
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Notifikasi Error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Notifikasi Validasi Error
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ implode('\n', $errors->all()) }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
    </script>
@endpush
