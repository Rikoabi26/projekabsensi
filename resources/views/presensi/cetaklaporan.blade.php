<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }

        h3 {
            font-family: Arial, Helvetica, sans-serif;
            margin-bottom: 0;
        }

        .tabeldatakaryawan {
            margin-top: 40px;
        }

        .tabeldatakaryawan tr td {
            padding: 5px
        }

        .tabelpresensi {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
        }

        .tabelpresensi tr th {
            border: 1px solid #000;
            padding: 8px;
            background-color: #dbdbdb
        }

        .tabelpresensi tr td {
            border: 1px solid #000;
            padding: 5px;
            font-size: 12px;

        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">
    @php
        function selisih($jam_masuk, $jam_keluar)
        {
            [$h, $m, $s] = explode(':', $jam_masuk);
            $dtAwal = mktime($h, $m, $s, '1', '1', '1');
            [$h, $m, $s] = explode(':', $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode('.', $totalmenit / 60);
            $sisamenit = $totalmenit / 60 - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ':' . round($sisamenit2);
        }
    @endphp
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <table style="width: 100%">
            <tr>
                <td style="width: 30px">
                    <img src="{{ asset('assets/img/Logo Lisna.png') }}" alt="">
                </td>
                <td>
                    <h3>
                        LAPORAN PRESENSI KARYAWAN <br>
                        PERIODE {{ $namabulan[$bulan] }} {{ $tahun }}<br>
                        PT.Lisna Syifa Prima <br>
                    </h3>
                    <span><i>Jl. Trunojoyo No.135, Melawai, Kebayoran Baru Kota Administrasi Jakarta Selatan DKI
                            Jakarta</i></span>
                </td>
            </tr>
        </table>
        <table class="tabeldatakaryawan">
            <tr>
                <td rowspan="5">
                    @php
                        $path = Storage::url('uploads/karyawan/' . $karyawan->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="" width="120px" height="150px">
                </td>
            </tr>
            <tr>
                <td>Email</td>
                <td>:</td>
                <td>{{ $karyawan->email }}</td>
            </tr>
            <tr>
                <td>Nama Lengkap</td>
                <td>:</td>
                <td>{{ $karyawan->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $karyawan->jabatan }}</td>
            </tr>
            <tr>
                <td>No.Hp</td>
                <td>:</td>
                <td>{{ $karyawan->no_hp }}</td>
            </tr>
        </table>
        <table class="tabelpresensi" style="text-align: center">
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto</th>
                <th>Jam Pulang</th>
                <th>Foto</th>
                <th>Status</th>
                <th>Keterangan</th>
                <th>Jam Kerja</th>
            </tr>
            @foreach ($presensi as $d)
                @if ($d->status == 'h')
                    @php
                        $path_in = Storage::url('uploads/absensi/' . $d->foto_in);
                        $path_out = Storage::url('uploads/absensi/' . $d->foto_out);
                        $jamterlambat = selisih($d->jam_masuk, $d->jam_in);
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                        <td>{{ $d->jam_in }}</td>
                        <td><img src="{{ url($path_in) }}" alt="" width="50px" height="50px"></td>
                        <td>{{ $d->jam_out != null ? $d->jam_out : 'belum absen' }}</td>
                        <td>
                            @if ($d->jam_out != null)
                                <img src="{{ url($path_out) }}" alt="" width="50px" height="50px">
                            @else
                                No Photo
                            @endif

                        </td>
                        <td>{{$d->status}}</td>
                        <td>
                            @if ($d->jam_in > $d->jam_masuk)
                                Terlambat {{ $jamterlambat }}
                            @else
                                tepat waktu
                            @endif
                        </td>
                        {{-- menghitung jam kerja, jika tidak absen pulang dianggap tidak kerja --}}
                        <td>
                            @if ($d->jam_out != null)
                                @php
                                    $jmljamkerja = selisih($d->jam_in, $d->jam_out);
                                @endphp
                            @else
                                @php
                                    $jmljamkerja = 0;
                                @endphp
                            @endif
                            {{ $jmljamkerja }}
                        </td>
                    </tr>
                @else
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>{{$d->status}}</td>
                        <td>{{$d->keterangan}}</td>
                        <td></td>
                    </tr>
                @endif
            @endforeach
        </table>
        <table width="100%" style="margin-top: 100px;">
            <tr>
                <td style="text-align: center; vertical-align: bottom;" height="150px">
                    <b>Manager</b> <!-- Judul -->
                    <br><br><br><br><br> <!-- Tambahkan spasi untuk tanda tangan -->
                    <u>Ismaini</u> <!-- Nama -->
                </td>
                <td style="text-align: center; vertical-align: bottom;" height="150px">
                    <b>Direktur</b> <!-- Judul -->
                    <br><br><br><br><br> <!-- Tambahkan spasi untuk tanda tangan -->
                    <u>Edy Safputra</u> <!-- Nama -->
                </td>
            </tr>
        </table>


    </section>

</body>

</html>
