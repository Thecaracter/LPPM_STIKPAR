@extends('layouts.app')
@section('title', 'Review Dokumen')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Review Dokumen</h4>
                <ul class="breadcrumbs">
                    <li class="nav-home">
                        <a href="#">
                            <i class="fas fa-home"></i>
                        </a>
                    </li>
                    <li class="separator">
                        <i class="fas fa-chevron-right"></i>
                    </li>
                    <li class="nav-item">
                        <a href="#">Review Dokumen</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Daftar Dokumen Yang Perlu Direview</h4>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="basic-datatables" class="display table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul Penelitian</th>
                                            <th>Pengusul</th>
                                            <th>Jenis Dokumen</th>
                                            <th>Status</th>
                                            <th>Total Anggaran</th>
                                            <th>Tanggal Submit</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($dokumen as $key => $item)
                                            <tr>
                                                <td>{{ $dokumen->firstItem() + $key }}</td>
                                                <td>{{ $item->judul_penelitian }}</td>
                                                <td>{{ $item->user->name }}</td>
                                                <td>{{ $item->jenisDokumen->nama }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $item->getStatusColor() }}">
                                                        {{ $item->getStatusLabel() }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->getFormattedTotalAnggaran() }}</td>
                                                <td>{{ $item->tanggal_submit?->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm mr-2"
                                                            data-toggle="modal"
                                                            data-target="#detailModal{{ $item->id }}">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            onclick="openReviewModal('{{ $item->id }}')">
                                                            <i class="fas fa-edit"></i> Review
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">Tidak ada dokumen yang perlu direview
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $dokumen->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Modal -->
    @foreach ($dokumen as $item)
        <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    <th width="200">Judul Penelitian</th>
                                    <td>{{ $item->judul_penelitian }}</td>
                                </tr>
                                <tr>
                                    <th>Pengusul</th>
                                    <td>{{ $item->user->name }}</td>
                                </tr>
                                <tr>
                                    <th>Jenis Dokumen</th>
                                    <td>{{ $item->jenisDokumen->nama }}</td>
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
                                    <th>Periode Penelitian</th>
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
                                        <div class="mb-2">
                                            <a href="{{ asset('PdfDokumen/' . $item->file_proposal_pdf) }}"
                                                class="btn btn-info btn-sm" target="_blank">
                                                <i class="fas fa-file-pdf"></i> Lihat PDF
                                            </a>
                                        </div>
                                        <div>
                                            <a href="{{ asset('WordDokumen/' . $item->file_proposal_word) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-file-word"></i> Unduh Word
                                            </a>
                                        </div>
                                    </td>
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

        <!-- Review Modal -->
        <div class="modal fade" id="reviewModal{{ $item->id }}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id="formReview{{ $item->id }}" class="review-form">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Review Dokumen</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Status Review <span class="text-danger">*</span></label>
                                <select name="status" class="form-control" required>
                                    <option value="">Pilih Status</option>
                                    <option value="revisi">Revisi</option>
                                    <option value="ditolak">Ditolak</option>
                                    <option value="berhasil">Berhasil</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Catatan Review <span class="text-danger">*</span></label>
                                <textarea name="catatan_reviewer" class="form-control" rows="4" required></textarea>
                            </div>

                            <div id="kriteriaPenilaian{{ $item->id }}" class="kriteria-section">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                            <button type="submit" class="btn btn-primary">Simpan Review</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#basic-datatables').DataTable({
                "ordering": false,
                "searching": true,
                "paging": false,
                "info": false,
                "language": {
                    "search": "Cari:",
                    "zeroRecords": "Tidak ditemukan data yang sesuai",
                    "emptyTable": "Tidak ada data yang tersedia"
                }
            });
        });

        function openReviewModal(dokumenId) {
            $(`#formReview${dokumenId}`)[0].reset();
            $(`#kriteriaPenilaian${dokumenId}`).html('');

            $(`#formReview${dokumenId} select[name="status"]`).off('change').on('change', function() {
                if ($(this).val() === 'berhasil') {
                    loadKriteria(dokumenId);
                } else {
                    $(`#kriteriaPenilaian${dokumenId}`).html('');
                }
            });

            $(`#reviewModal${dokumenId}`).modal('show');
        }

        function loadKriteria(dokumenId) {
            $(`#kriteriaPenilaian${dokumenId}`).html('<div class="text-center">Loading kriteria penilaian...</div>');

            $.ajax({
                url: `/review/kriteria/${dokumenId}`,
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    console.log("Response:", response);

                    if (response.status === 'success' && response.data.length > 0) {
                        let html = '<h5 class="mb-3">Kriteria Penilaian</h5>';
                        response.data.forEach(function(kriteria) {
                            html += `
                                    <div class="form-group">
                                        <label>${kriteria.nama_kriteria} (Bobot: ${kriteria.bobot}%) <span class="text-danger">*</span></label>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <input type="number" step="0.1" name="penilaian[${kriteria.id}][skor]"
                                                       class="form-control" placeholder="Skor (0-10)"
                                                       required min="0" max="10"
                                                       value="${kriteria.penilaian && kriteria.penilaian[0] ? kriteria.penilaian[0].skor : ''}">
                                                <input type="hidden" name="penilaian[${kriteria.id}][kriteria_id]"
                                                       value="${kriteria.id}">
                                                <small class="form-text text-muted">Masukkan nilai 0-10</small>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" name="penilaian[${kriteria.id}][justifikasi]"
                                                       class="form-control" placeholder="Justifikasi penilaian" required
                                                       value="${kriteria.penilaian && kriteria.penilaian[0] ? kriteria.penilaian[0].justifikasi : ''}">
                                            </div>
                                        </div>
                                    </div>
                                `;
                        });
                        $(`#kriteriaPenilaian${dokumenId}`).html(html);
                    } else {
                        $(`#kriteriaPenilaian${dokumenId}`).html(
                            '<div class="alert alert-warning">Belum ada kriteria penilaian untuk jenis dokumen ini.</div>'
                        );
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error:", error);
                    $(`#kriteriaPenilaian${dokumenId}`).html(
                        '<div class="alert alert-danger">Gagal memuat kriteria penilaian: ' + error +
                        '</div>'
                    );
                }
            });
        }

        $('.review-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const dokumenId = form.attr('id').replace('formReview', '');
            const status = form.find('select[name="status"]').val();

            if (!form[0].checkValidity()) {
                form[0].reportValidity();
                return;
            }

            if (status === 'berhasil' && form.find('input[name^="penilaian"]').length === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Penilaian harus diisi untuk status Berhasil'
                });
                return;
            }

            form.find('button[type="submit"]').prop('disabled', true);

            Swal.fire({
                title: 'Konfirmasi',
                text: "Apakah Anda yakin akan menyimpan review ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Simpan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/review/${dokumenId}`,
                        method: 'POST',
                        data: new FormData(form[0]),
                        processData: false,
                        contentType: false,
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil',
                                    text: response.message,
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    location.reload();
                                });
                            }
                        },
                        error: function(xhr) {
                            let message = 'Terjadi kesalahan';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                message = xhr.responseJSON.message;
                            } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                                message = Object.values(xhr.responseJSON.errors).flat().join(
                                    '\n');
                            }
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: message
                            });
                        },
                        complete: function() {
                            form.find('button[type="submit"]').prop('disabled', false);
                        }
                    });
                } else {
                    form.find('button[type="submit"]').prop('disabled', false);
                }
            });
        });

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
                text: "{{ session('error') }}"
            });
        @endif
    </script>
@endpush
