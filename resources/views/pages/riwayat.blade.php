{{-- resources/views/pages/riwayat.blade.php --}}
@extends('layouts.app')
@section('title', 'Riwayat Dokumen')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="page-header">
                <h4 class="page-title">Riwayat Dokumen</h4>
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
                        <a href="#">Riwayat Dokumen</a>
                    </li>
                </ul>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <h4 class="card-title">Daftar Riwayat Dokumen</h4>
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
                                            <th>Reviewer</th>
                                            <th>Tanggal Review</th>
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
                                                <td>{{ $item->reviewer?->name ?? '-' }}</td>
                                                <td>{{ $item->tanggal_review?->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-info btn-sm mr-2"
                                                            data-toggle="modal"
                                                            data-target="#detailModal{{ $item->id }}">
                                                            <i class="fas fa-eye"></i> Detail
                                                        </button>
                                                        @if ($item->status === 'berhasil')
                                                            <a href="{{ route('cetak-penilaian.show', $item->id) }}"
                                                                class="btn btn-success btn-sm" target="_blank">
                                                                <i class="fas fa-print"></i> Cetak
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">Tidak ada riwayat dokumen</td>
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
                        <h5 class="modal-title">Detail Riwayat Dokumen</h5>
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
                                    <th>Reviewer</th>
                                    <td>{{ $item->reviewer?->name ?? '-' }}</td>
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
                                    <th>Catatan Reviewer</th>
                                    <td>{{ $item->catatan_reviewer ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Review</th>
                                    <td>{{ $item->tanggal_review?->format('d/m/Y H:i') }}</td>
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
    </script>
@endpush
