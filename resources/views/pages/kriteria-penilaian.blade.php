@extends('layouts.app')
@section('title', 'Kriteria Penilaian')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <h4 class="card-title">Kriteria Penilaian - {{ $jenisDokumen->nama }}</h4>
                                <p class="text-muted">Total bobot saat ini:
                                    {{ number_format($kriteriaPenilaian->sum('bobot'), 2) }}</p>
                            </div>
                            <div>
                                <a href="{{ route('jenis-dokumen.index') }}" class="btn btn-primary me-2 mr-2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#createKriteriaModal">
                                    <i class="fa fa-plus"></i> Tambah Kriteria
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="50%">Nama Kriteria</th>
                                            <th width="15%">Bobot</th>
                                            <th width="30%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($kriteriaPenilaian as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->nama_kriteria }}</td>
                                                <td>{{ number_format($item->bobot, 2) }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-warning me-2 mr-2"
                                                            data-toggle="modal"
                                                            data-target="#editKriteriaModal{{ $item->id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete('{{ $item->id }}')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </div>
                                                    <form id="delete-form-{{ $item->id }}"
                                                        action="{{ route('kriteria-penilaian.destroy', $item->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center">Tidak ada data kriteria penilaian
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="2" class="text-right">Total Bobot:</th>
                                            <th>{{ number_format($kriteriaPenilaian->sum('bobot'), 2) }}</th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Kriteria Modal -->
    <div class="modal fade" id="createKriteriaModal" tabindex="-1" role="dialog"
        aria-labelledby="createKriteriaModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('kriteria-penilaian.store') }}">
                    @csrf
                    <input type="hidden" name="jenis_dokumen_id" value="{{ $jenisDokumen->id }}">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createKriteriaModalLabel">Tambah Kriteria Penilaian</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="nama_kriteria">Nama Kriteria</label>
                            <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror"
                                id="nama_kriteria" name="nama_kriteria" required value="{{ old('nama_kriteria') }}">
                            @error('nama_kriteria')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="bobot">Bobot</label>
                            <input type="number" step="0.01" class="form-control @error('bobot') is-invalid @enderror"
                                id="bobot" name="bobot" required value="{{ old('bobot') }}" min="0">
                            @error('bobot')
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

    @foreach ($kriteriaPenilaian as $item)
        <!-- Edit Kriteria Modal -->
        <div class="modal fade" id="editKriteriaModal{{ $item->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editKriteriaModalLabel{{ $item->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" action="{{ route('kriteria-penilaian.update', $item->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="editKriteriaModalLabel{{ $item->id }}">Edit Kriteria
                                Penilaian</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group mb-3">
                                <label for="nama_kriteria{{ $item->id }}">Nama Kriteria</label>
                                <input type="text" class="form-control @error('nama_kriteria') is-invalid @enderror"
                                    id="nama_kriteria{{ $item->id }}" name="nama_kriteria"
                                    value="{{ old('nama_kriteria', $item->nama_kriteria) }}" required>
                                @error('nama_kriteria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="bobot{{ $item->id }}">Bobot</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('bobot') is-invalid @enderror"
                                    id="bobot{{ $item->id }}" name="bobot"
                                    value="{{ old('bobot', $item->bobot) }}" required min="0">
                                @error('bobot')
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
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data kriteria penilaian ini akan dihapus permanen!",
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
