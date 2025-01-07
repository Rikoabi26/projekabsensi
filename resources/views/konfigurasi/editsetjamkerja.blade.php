@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        EDIT SET JAM KERJA
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="row">
                <div class="col-6">
                    <table class="table">
                        <tr>
                            <th>Email</th>
                            <td>{{ $karyawan->email }}</td>
                        </tr>
                        <tr>
                            <th>Nama Karyawan</th>
                            <td>{{ $karyawan->nama_lengkap }}</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <form action="/konfigurasi/updatesetjamkerja" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $karyawan->email }}">
                        <table class="table">
                            <div class="row mt-3">
                                <div class="col-6">
                                    <div class="form-goup">
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Bulan</option>
                                            @for ($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }}>
                                                    {{ $namabulan[$i] }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6 mb-2">
                                    <div class="form-goup">
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Tahun</option>
                                            @php
                                                $tahunmulai = 2023;
                                                $tahunskrg = date('Y');
                                                $tahundatang = $tahunskrg + 1;
                                            @endphp
                                            @for ($tahun = $tahunmulai; $tahun <= $tahundatang; $tahun++)
                                                <option value="{{ $tahun }}"
                                                    {{ date('Y') == $tahun ? 'selected' : '' }}>
                                                    {{ $tahun }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <thead>
                                <tr>
                                    <td>Set Jadwal</td>
                                    <td>Jam Kerja</td>
                                </tr>
                            </thead>
                            <tbody id="setjadwal">
                                @php
                                    $currentMonth = date('m'); // Bulan saat ini
                                    $currentYear = date('Y'); // Tahun saat ini
                                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear); // Hitung jumlah hari dalam bulan

                                @endphp
                                @for ($day = 1; $day <= $daysInMonth; $day++)
                                    @php
                                        $formattedDate = date('Y-m-d', strtotime("$currentYear-$currentMonth-$day"));
                                        // Cek jam kerja sebelumnya untuk tanggal ini
                                        $jamKerjaSebelumnya = $setjamkerja->where('tanggal', $formattedDate)->first();

                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="date" name="tanggal[]" class="form-control"
                                                value="{{ $formattedDate }}">

                                        </td>
                                        <td>
                                            <select name="kode_jam_kerja[]" class="form-select">
                                                <option value="">Pilih Jam Kerja</option>
                                                @foreach ($jamkerja as $d)
                                                    <option value="{{ $d->kode_jam_kerja }}"
                                                        @if ($jamKerjaSebelumnya && $jamKerjaSebelumnya->kode_jam_kerja == $d->kode_jam_kerja) selected @endif>
                                                        {{ $d->nama_jam_kerja }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                        <button class="btn btn-primary w-100" type="submit">Update</button>
                    </form>
                </div>
                <div class="col-6">
                    <table class="table">
                        <thead>
                            <tr>
                                <th colspan="6">Master Jam Kerja</th>
                            </tr>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Awal Masuk</th>
                                <th>Jam Masuk</th>
                                <th>Toleransi Waktu</th>
                                <th>Jam Pulang</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($jamkerja as $d)
                                <tr>
                                    <td>{{ $d->kode_jam_kerja }}</td>
                                    <td>{{ $d->nama_jam_kerja }}</td>
                                    <td>{{ $d->awal_jam_masuk }}</td>
                                    <td>{{ $d->jam_masuk }}</td>
                                    <td>{{ $d->akhir_jam_masuk }}</td>
                                    <td>{{ $d->jam_pulang }}</td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        $(function() {
            // Event handler untuk perubahan bulan atau tahun
            $('#bulan, #tahun').change(function() {
                var bulan = $('#bulan').val();
                var tahun = $('#tahun').val();

                if (bulan && tahun) {
                    generateJadwal(bulan, tahun);
                }
            });

            function generateJadwal(bulan, tahun) {
                var email = $('input[name="email"]').val();

                // Hitung jumlah hari dalam bulan yang dipilih
                var daysInMonth = new Date(tahun, bulan, 0).getDate();

                // Kosongkan tbody
                $('#setjadwal').empty();

                // Ambil data jadwal yang sudah ada
                $.ajax({
                    type: 'POST',
                    url: '/konfigurasi/getjadwal',
                    data: {
                        _token:"{{csrf_token();}}" ,
                        bulan: bulan,
                        tahun: tahun,
                        email: email
                    },
                    success: function(response) {
                        // Generate row untuk setiap tanggal
                        for (let i = 1; i <= daysInMonth; i++) {
                            // Format tanggal YYYY-MM-DD
                            var currentDate = tahun + '-' +
                                bulan.toString().padStart(2, '0') + '-' +
                                i.toString().padStart(2, '0');

                            // Cari data yang sudah ada untuk tanggal ini
                            var jadwalHariIni = response.data ? response.data.find(item => item
                                .tanggal === currentDate) : null;

                            var row = `
                        <tr>
                            <td>
                                <input type="date" name="tanggal[]" class="form-control" 
                                    value="${currentDate}" readonly>
                            </td>
                            <td>
                                <select name="kode_jam_kerja[]" class="form-select">
                                    <option value="">Pilih Jam Kerja</option>`;

                            // Tambahkan opsi jam kerja
                            $('.table:last tbody tr').each(function() {
                                var kodeJK = $(this).find('td:eq(0)').text().trim();
                                var namaJK = $(this).find('td:eq(1)').text().trim();

                                if (kodeJK && namaJK) {
                                    var selected = jadwalHariIni && jadwalHariIni
                                        .kode_jam_kerja === kodeJK ? 'selected' : '';
                                    row +=
                                        `<option value="${kodeJK}" ${selected}>${namaJK}</option>`;
                                }
                            });

                            row += `
                                </select>
                            </td>
                        </tr>`;

                            $('#setjadwal').append(row);
                        }
                    },
                    error: function(xhr, status, error) {
                        // Tetap generate tanggal meskipun terjadi error
                        for (let i = 1; i <= daysInMonth; i++) {
                            var currentDate = tahun + '-' +
                                bulan.toString().padStart(2, '0') + '-' +
                                i.toString().padStart(2, '0');

                            var row = `
                        <tr>
                            <td>
                                <input type="date" name="tanggal[]" class="form-control" 
                                    value="${currentDate}" readonly>
                            </td>
                            <td>
                                <select name="kode_jam_kerja[]" class="form-select">
                                    <option value="">Pilih Jam Kerja</option>`;

                            // Tambahkan opsi jam kerja
                            $('.table:last tbody tr').each(function() {
                                var kodeJK = $(this).find('td:eq(0)').text().trim();
                                var namaJK = $(this).find('td:eq(1)').text().trim();

                                if (kodeJK && namaJK) {
                                    row += `<option value="${kodeJK}">${namaJK}</option>`;
                                }
                            });

                            row += `
                                </select>
                            </td>
                        </tr>`;

                            $('#setjadwal').append(row);
                        }
                        console.error('Ajax Error:', error);
                    }
                });
            }

            
        });
    </script>
@endpush
