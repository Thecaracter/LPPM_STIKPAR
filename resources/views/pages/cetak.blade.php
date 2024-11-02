<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Penilaian</title>
    <style>
        @media print {
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                color-adjust: exact !important;
            }
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 15px;
            font-size: 12px;
        }

        .header {
            text-align: left;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }

        .header img {
            width: 100px;
            height: 100px;
            margin-right: 15px;
        }

        .header-text {
            flex: 1;
        }

        .header-text h2 {
            font-size: 12px;
            font-weight: bold;
            margin: 0;
            text-align: center;
            margin-bottom: 3px;
        }

        .header-text p {
            font-size: 10px;
            margin: 0;
            text-align: center;
            line-height: 1.3;
        }

        .section {
            margin-bottom: 15px;
        }

        .yellow-bg {
            background-color: #ffeb3b !important;
            -webkit-print-color-adjust: exact;
            color: #000;
            font-weight: bold;
            padding: 3px;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 11px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px 6px;
        }

        .info-table td:first-child {
            width: 150px;
        }

        .nilai-table th {
            background-color: #ffeb3b !important;
            -webkit-print-color-adjust: exact;
            font-weight: bold;
            text-align: center;
        }

        .nilai-table td {
            vertical-align: top;
        }

        .nilai-table td:nth-child(1),
        .nilai-table td:nth-child(3),
        .nilai-table td:nth-child(4),
        .nilai-table td:nth-child(5) {
            text-align: center;
        }

        .footer {
            text-align: right;
            margin-top: 20px;
            font-size: 11px;
        }

        .stamp {
            position: relative;
            margin: 30px 0;
            height: 80px;
        }

        .stamp img {
            position: absolute;
            right: 40px;
            top: -20px;
            width: 250px;
            height: 150px;
            opacity: 0.9;
        }

        .footer p {
            margin: 2px 0;
        }

        .catatan {
            margin-top: 10px;
            font-size: 11px;
        }

        .passing-grade {
            text-align: center;
            font-size: 11px;
            margin-top: 10px;
            font-style: italic;
        }

        .total-row td {
            font-weight: bold;
        }

        .anggota-list {
            margin: 0;
            padding-left: 20px;
        }

        @page {
            size: A4;
            margin: 15mm;
        }
    </style>
</head>

<body>
    <div class="header">
        <img src="{{ asset('custom/assetsFoto/logo.png') }}" alt="Logo">
        <div class="header-text">
            <h2>SEKOLAH TINGGI KATEKETIK DAN PASTORAL RANTEPAO</h2>
            <h2>(STIKPAR) TORAJA</h2>
            <p>LEMBAGA PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT</p>
            <p>Jl. Abdul Gani 3, Kotak Pos 51, Rantepao 91831, Toraja Utara - Sulawesi Selatan</p>
            <p>Telp. 0423-21414, HP: 082194089890, e-mail: admin@stikpar.org.ac.id/lppm@stikpar-rtpao.ac.id</p>
        </div>
    </div>

    <div class="section">
        <div class="yellow-bg">IDENTITAS PENELITI</div>
        <table class="info-table">
            <tr>
                <td>Nama Peneliti</td>
                <td>{{ $dokumen->user->name }}</td>
            </tr>
            <tr>
                <td>NIDN</td>
                <td>{{ $dokumen->user->nidn_nuptk }}</td>
            </tr>
            <tr>
                <td>Bidang Keahlian</td>
                <td>{{ $dokumen->user->bidang_keahlian }}</td>
            </tr>
            <tr>
                <td>Prodi</td>
                <td>{{ $dokumen->user->program_studi }}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{ $dokumen->user->email }}</td>
            </tr>
            <tr>
                <td>Anggota Tim</td>
                <td>
                    @if ($dokumen->user->anggotaTim->count() > 0)
                        <ol class="anggota-list">
                            @foreach ($dokumen->user->anggotaTim as $anggota)
                                <li>{{ $anggota->nama }}</li>
                            @endforeach
                        </ol>
                    @else
                        -
                    @endif
                </td>
            </tr>
            <tr>
                <td>Judul Penelitian</td>
                <td>{{ $dokumen->judul_penelitian }}</td>
            </tr>
            <tr>
                <td>Lokasi Penelitian</td>
                <td>{{ $dokumen->lokasi_penelitian }}</td>
            </tr>
            <tr>
                <td>Waktu Pelaksanaan</td>
                <td>{{ $dokumen->waktu_mulai?->format('d/m/Y') }} - {{ $dokumen->waktu_selesai?->format('d/m/Y') }}
                </td>
            </tr>
            <tr>
                <td>Spesifikasi outcome penelitian</td>
                <td>{{ $dokumen->spesifikasi_outcome }}</td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="yellow-bg">PENILAIAN PROPOSAL PENELITIAN</div>
        <table class="nilai-table">
            <tr>
                <th style="width: 30px;">No</th>
                <th>Kriteria Penilaian</th>
                <th style="width: 60px;">Bobot</th>
                <th style="width: 50px;">Skor</th>
                <th style="width: 50px;">Nilai</th>
                <th>Justifikasi Penilaian</th>
            </tr>
            @php $total = 0; @endphp
            @foreach ($dokumen->penilaian as $index => $nilai)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $nilai->kriteria->nama_kriteria }}</td>
                    <td>{{ $nilai->kriteria->bobot }}</td>
                    <td>{{ $nilai->skor }}</td>
                    <td>{{ $nilai->nilai }}</td>
                    <td>{{ $nilai->justifikasi }}</td>
                </tr>
                @php $total += $nilai->nilai; @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="4" style="text-align: right;">Jumlah</td>
                <td>{{ $total }}</td>
                <td></td>
            </tr>
        </table>
    </div>

    @if ($dokumen->catatan_reviewer)
        <div class="catatan">
            <strong>Catatan:</strong>
            {{ $dokumen->catatan_reviewer }}
        </div>
    @endif

    <div class="footer">
        <p>Rantepao, {{ $dokumen->tanggal_review?->isoFormat('D MMMM Y') }}</p>
        <div class="stamp">
            <img src="{{ asset('custom/assetsFoto/ttd.png') }}" alt="Stempel">
        </div>
    </div>

    <div class="passing-grade">
        Passing grade: 600
    </div>

    <script>
        window.onload = function() {
            // Menunda print selama 500ms untuk memastikan semua konten terload
            setTimeout(function() {
                window.print();
            }, 500);
        }

        // Menangani kasus ketika print dibatalkan
        window.onafterprint = function() {
            // Optional: redirect atau tutup window setelah print
            // window.close();
        }

        // Prevent default zoom behavior on mobile
        document.addEventListener('gesturestart', function(e) {
            e.preventDefault();
        });
    </script>
</body>

</html>
