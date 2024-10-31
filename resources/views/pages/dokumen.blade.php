@extends('layouts.app')
@section('title', 'Dokumen')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Daftar Dokumen</h4>
                            @if (auth()->user()->role !== 'reviewer')
                                <button type="button" class="btn btn-success" data-toggle="modal"
                                    data-target="#createDokumenModal">
                                    <i class="fa fa-plus"></i> Tambah Dokumen
                                </button>
                            @endif
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul Penelitian</th>
                                            <th>Jenis Dokumen</th>
                                            <th>Status</th>
                                            <th>Total Anggaran</th>
                                            <th>Tanggal Submit</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dokumen as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->judul_penelitian }}</td>
                                                <td>{{ $item->jenisDokumen->nama }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $item->getStatusColor() }}">
                                                        {{ $item->getStatusLabel() }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->getFormattedTotalAnggaran() }}</td>
                                                <td>{{ $item->tanggal_submit?->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <button type="button" class="btn btn-sm btn-info me-2 mr-2"
                                                            data-toggle="modal"
                                                            data-target="#showDokumenModal{{ $item->id }}">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-warning me-2 mr-2"
                                                            data-toggle="modal"
                                                            data-target="#editDokumenModal{{ $item->id }}">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            onclick="confirmDelete('{{ $item->id }}')">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                        <form id="delete-form-{{ $item->id }}"
                                                            action="{{ route('dokumen.destroy', $item->id) }}"
                                                            method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Tidak ada data dokumen</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $dokumen->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createDokumenModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="dokumen-form" method="POST" action="{{ route('dokumen.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Dokumen Baru</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Jenis Dokumen</label>
                            <select name="jenis_dokumen_id"
                                class="form-control @error('jenis_dokumen_id') is-invalid @enderror" required>
                                <option value="">Pilih Jenis Dokumen</option>
                                @foreach ($jenisDokumen as $jenis)
                                    <option value="{{ $jenis->id }}">{{ $jenis->nama }}</option>
                                @endforeach
                            </select>
                            @error('jenis_dokumen_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Judul Penelitian</label>
                            <input type="text" name="judul_penelitian"
                                class="form-control @error('judul_penelitian') is-invalid @enderror" required
                                value="{{ old('judul_penelitian') }}">
                            @error('judul_penelitian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Abstrak Penelitian</label>
                            <textarea name="abstrak_penelitian" rows="3"
                                class="form-control @error('abstrak_penelitian') is-invalid @enderror" required>{{ old('abstrak_penelitian') }}</textarea>
                            @error('abstrak_penelitian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Metode Penelitian</label>
                            <textarea name="metode_penelitian" rows="3" class="form-control @error('metode_penelitian') is-invalid @enderror"
                                required>{{ old('metode_penelitian') }}</textarea>
                            @error('metode_penelitian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Total Anggaran</label>
                                    <input type="text" name="total_anggaran"
                                        class="form-control @error('total_anggaran') is-invalid @enderror" required
                                        value="{{ old('total_anggaran') }}" placeholder="Masukkan total anggaran">
                                    @error('total_anggaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Sumber Dana</label>
                                    <input type="text" name="sumber_dana"
                                        class="form-control @error('sumber_dana') is-invalid @enderror" required
                                        value="{{ old('sumber_dana') }}">
                                    @error('sumber_dana')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Lokasi Penelitian</label>
                            <input type="text" name="lokasi_penelitian"
                                class="form-control @error('lokasi_penelitian') is-invalid @enderror" required
                                value="{{ old('lokasi_penelitian') }}">
                            @error('lokasi_penelitian')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Waktu Mulai</label>
                                    <input type="date" name="waktu_mulai"
                                        class="form-control @error('waktu_mulai') is-invalid @enderror" required
                                        value="{{ old('waktu_mulai') }}">
                                    @error('waktu_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Waktu Selesai</label>
                                    <input type="date" name="waktu_selesai"
                                        class="form-control @error('waktu_selesai') is-invalid @enderror" required
                                        value="{{ old('waktu_selesai') }}">
                                    @error('waktu_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Spesifikasi Outcome</label>
                            <textarea name="spesifikasi_outcome" rows="3"
                                class="form-control @error('spesifikasi_outcome') is-invalid @enderror" required>{{ old('spesifikasi_outcome') }}</textarea>
                            @error('spesifikasi_outcome')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>File Proposal (PDF)</label>
                            <input type="file" name="file_proposal_pdf"
                                class="form-control @error('file_proposal_pdf') is-invalid @enderror" required
                                accept=".pdf">
                            @error('file_proposal_pdf')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>File Proposal (Word)</label>
                            <input type="file" name="file_proposal_word"
                                class="form-control @error('file_proposal_word') is-invalid @enderror" required
                                accept=".doc,.docx">
                            @error('file_proposal_word')
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

    <!-- Edit Modals -->
    @foreach ($dokumen as $item)
        <div class="modal fade" id="editDokumenModal{{ $item->id }}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form class="dokumen-form" method="POST" action="{{ route('dokumen.update', $item->id) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Dokumen</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Jenis Dokumen</label>
                                <select name="jenis_dokumen_id"
                                    class="form-control @error('jenis_dokumen_id') is-invalid @enderror" required>
                                    @foreach ($jenisDokumen as $jenis)
                                        <option value="{{ $jenis->id }}"
                                            {{ $item->jenis_dokumen_id == $jenis->id ? 'selected' : '' }}>
                                            {{ $jenis->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Judul Penelitian</label>
                                <input type="text" name="judul_penelitian" class="form-control" required
                                    value="{{ old('judul_penelitian', $item->judul_penelitian) }}">
                            </div>

                            <div class="form-group">
                                <label>Abstrak Penelitian</label>
                                <textarea name="abstrak_penelitian" rows="3" class="form-control" required>{{ old('abstrak_penelitian', $item->abstrak_penelitian) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>Metode Penelitian</label>
                                <textarea name="metode_penelitian" rows="3" class="form-control" required>{{ old('metode_penelitian', $item->metode_penelitian) }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total Anggaran</label>
                                        <input type="text" name="total_anggaran" class="form-control" required
                                            value="{{ old('total_anggaran', number_format($item->total_anggaran, 0, ',', '.')) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sumber Dana</label>
                                        <input type="text" name="sumber_dana" class="form-control" required
                                            value="{{ old('sumber_dana', $item->sumber_dana) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Lokasi Penelitian</label>
                                <input type="text" name="lokasi_penelitian" class="form-control" required
                                    value="{{ old('lokasi_penelitian', $item->lokasi_penelitian) }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Waktu Mulai</label>
                                        <input type="date" name="waktu_mulai" class="form-control" required
                                            value="{{ old('waktu_mulai', $item->waktu_mulai?->format('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Waktu Selesai</label>
                                        <input type="date" name="waktu_selesai" class="form-control" required
                                            value="{{ old('waktu_selesai', $item->waktu_selesai?->format('Y-m-d')) }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Spesifikasi Outcome</label>
                                <textarea name="spesifikasi_outcome" rows="3" class="form-control" required>{{ old('spesifikasi_outcome', $item->spesifikasi_outcome) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label>File Proposal (PDF)</label>
                                <input type="file" name="file_proposal_pdf" class="form-control" accept=".pdf">
                                @if ($item->file_proposal_pdf)
                                    <small class="form-text text-muted">
                                        File saat ini: {{ $item->file_proposal_pdf }}
                                        <a href="{{ asset('PdfDokumen/' . $item->file_proposal_pdf) }}"
                                            target="_blank">[Lihat]</a>
                                    </small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label>File Proposal (Word)</label>
                                <input type="file" name="file_proposal_word" class="form-control"
                                    accept=".doc,.docx">
                                @if ($item->file_proposal_word)
                                    <small class="form-text text-muted">
                                        File saat ini: {{ $item->file_proposal_word }}
                                        <a href="{{ asset('WordDokumen/' . $item->file_proposal_word) }}"
                                            target="_blank">[Unduh]</a>
                                    </small>
                                @endif
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

        <!-- Show Modal -->
        <div class="modal fade" id="showDokumenModal{{ $item->id }}" tabindex="-1" role="dialog"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Detail Dokumen</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Jenis Dokumen</th>
                                    <td>{{ $item->jenisDokumen->nama }}</td>
                                </tr>
                                <tr>
                                    <th>Judul Penelitian</th>
                                    <td>{{ $item->judul_penelitian }}</td>
                                </tr>
                                <tr>
                                    <th>Abstrak Penelitian</th>
                                    <td>{{ $item->abstrak_penelitian }}</td>
                                </tr>
                                <tr>
                                    <th>Metode Penelitian</th>
                                    <td>{{ $item->metode_penelitian }}</td>
                                </tr>
                                <tr>
                                    <th>Total Anggaran</th>
                                    <td>{{ $item->getFormattedTotalAnggaran() }}</td>
                                </tr>
                                <tr>
                                    <th>Sumber Dana</th>
                                    <td>{{ $item->sumber_dana }}</td>
                                </tr>
                                <tr>
                                    <th>Lokasi Penelitian</th>
                                    <td>{{ $item->lokasi_penelitian }}</td>
                                </tr>
                                <tr>
                                    <th>Waktu Penelitian</th>
                                    <td>{{ $item->waktu_mulai?->format('d/m/Y') }} -
                                        {{ $item->waktu_selesai?->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Spesifikasi Outcome</th>
                                    <td>{{ $item->spesifikasi_outcome }}</td>
                                </tr>
                                <tr>
                                    <th>File Proposal</th>
                                    <td>
                                        @if ($item->file_proposal_pdf)
                                            <div class="mb-2">
                                                <a href="{{ asset('PdfDokumen/' . $item->file_proposal_pdf) }}"
                                                    class="btn btn-sm btn-info" target="_blank">
                                                    <i class="fas fa-file-pdf"></i> Lihat PDF
                                                </a>
                                            </div>
                                        @endif
                                        @if ($item->file_proposal_word)
                                            <div>
                                                <a href="{{ asset('WordDokumen/' . $item->file_proposal_word) }}"
                                                    class="btn btn-sm btn-info">
                                                    <i class="fas fa-file-word"></i> Unduh Word
                                                </a>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span class="badge badge-{{ $item->getStatusColor() }}">
                                            {{ $item->getStatusLabel() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Tanggal Submit</th>
                                    <td>{{ $item->tanggal_submit?->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Catatan Reviewer</th>
                                    <td>{{ $item->catatan_reviewer }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Review</th>
                                    <td>{{ $item->tanggal_review }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @push('scripts')
        <script>
            // Format number with dots
            function formatNumber(number) {
                return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            }

            // Remove dots from number
            function unformatNumber(number) {
                return number.replace(/\./g, "");
            }

            // Handle input total anggaran
            $(document).on('keyup', 'input[name="total_anggaran"]', function() {
                let value = unformatNumber($(this).val());
                if (value !== "") {
                    value = parseInt(value);
                    if (!isNaN(value)) {
                        $(this).val(formatNumber(value));
                    }
                }
            });

            // Format initial values
            $('input[name="total_anggaran"]').each(function() {
                let value = $(this).val();
                if (value) {
                    $(this).val(formatNumber(value));
                }
            });

            // Handle form submissions
            $('.dokumen-form').on('submit', function(e) {
                e.preventDefault();

                let form = $(this);
                let formData = new FormData(this);

                // Remove dots from total_anggaran before submitting
                let totalAnggaranInput = form.find('input[name="total_anggaran"]');
                let unformattedValue = unformatNumber(totalAnggaranInput.val());
                formData.set('total_anggaran', unformattedValue);

                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(function() {
                                $('#createDokumenModal').modal('hide');
                                $('#editDokumenModal').modal('hide');
                                location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        let message = 'Terjadi kesalahan';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: message
                        });
                    }
                });
            });

            // Handle delete
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data dokumen ini akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(`delete-form-${id}`).submit();
                    }
                });
            }

            // Display session messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    timer: 1500,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}",
                    showConfirmButton: true
                });
            @endif
        </script>
    @endpush
@endsection
