@extends('layouts.admin.tabler')

@section('content')
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h2 class="page-title">
                        SET JAM KERJA
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
                    {{-- <form action="/konfigurasi/storesetjamkerja" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $karyawan->email }}">
                        <table class="table">
                            <thead>
                                <tr>
                                    <td>Hari</td>
                                    <td>Jam Kerja</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        Senin
                                        <input type="hidden" name="hari[]" value="senin">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                                <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Selasa
                                        <input type="hidden" name="hari[]" value="selasa">

                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                                <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Rabu
                                        <input type="hidden" name="hari[]" value="rabu">

                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                                <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Kamis
                                        <input type="hidden" name="hari[]" value="kamis">

                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                                <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Jumat
                                        <input type="hidden" name="hari[]" value="jumat">

                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                                <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Sabtu
                                        <input type="hidden" name="hari[]" value="sabtu">

                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                                <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Minggu
                                        <input type="hidden" name="hari[]" value="minggu">
                                    </td>
                                    <td>
                                        <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                            <option value="">Pilih Jam Kerja</option>
                                            @foreach ($jamkerja as $d)
                                                <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <button class="btn btn-primary w-100" type="submit">Simpan</button>
                    </form> --}}
                    <form action="/konfigurasi/storesetjamkerja" method="POST">
                        @csrf
                        <input type="hidden" name="email" value="{{ $karyawan->email }}">

                        <table class="table">
                            
                            <thead>
                                <tr>
                                    <td>Set Jadwal</td>
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
                                                    <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                        <button class="btn btn-primary w-100" type="submit">Simpan</button>
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
