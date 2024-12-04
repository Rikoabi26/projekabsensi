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
                            <thead>
                                <tr>
                                    <td>Tanggal</td>
                                    <td>Jam Kerja</td>
                                </tr>
                            </thead>
                            <tbody>
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
