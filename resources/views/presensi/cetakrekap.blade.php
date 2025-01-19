<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <style>
        h3 {
            font-family: Arial, Helvetica, sans-serif;
            margin-bottom: 0;
        }

        .tabeldatakaryawan {
            margin-top: 40px;
        }

        .tabeldatakaryawan tr td {
            padding: 3px;
            font-size: 10px;
        }

        .tabelpresensi {
            width: 100%;
            margin-top: 10px;
            border-collapse: collapse;
            font-size: 10px
        }

        .tabelpresensi tr th {
            border: 1px solid #000;
            padding: 5px;
            background-color: #dbdbdb
        }

        .tabelpresensi tr td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
        }
    </style>
</head>

<body class="A4 landscape">

    <section class="sheet padding-10mm">

        <table style="width: 100%">
            <tr>
                <td style="width: 30px">
                    <img src="{{ asset('assets/img/Logo Lisna.png') }}" alt="">
                </td>
                <td>
                    <h3>
                        REKAP PRESENSI KARYAWAN <br>
                        PERIODE {{ $namabulan[$bulan] }} {{ $tahun }}<br>
                        PT.Lisna Syifa Prima <br>
                    </h3>
                    <span><i>Jl. Trunojoyo No.135, Melawai, Kebayoran Baru Kota Administrasi Jakarta Selatan DKI
                            Jakarta</i></span>
                </td>
            </tr>
        </table>
        <div style="overflow-x: auto;  width: 100%;">
            <table class="tabelpresensi">
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Lengkap</th>
                    <th colspan="{{ $jmlhari }}">Bulan {{ $namabulan[$bulan] }} {{ $tahun }}</th>
                    <th rowspan="2">H</th>
                    <th rowspan="2">I</th>
                    <th rowspan="2">S</th>
                    <th rowspan="2">C</th>
                    <th rowspan="2">Denda</th>

                </tr>
                <tr>
                    @foreach ($rangetanggal as $d)
                        @if ($d != null)
                            <th>{{ date('d', strtotime($d)) }}</th>
                        @endif
                    @endforeach

                </tr>
                @foreach ($rekap as $r)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $r->nama_lengkap }}</td>
                        <?php 
                        $jml_hadir = 0;
                        $jml_izin = 0;
                        $jml_sakit = 0;
                        $jml_cuti = 0;
                        $jml_alpa =0;
                        $total_denda = 0;
                        $color = "";
                       
                        for ($i=1; $i <= $jmlhari ; $i++) { 
                                    $tgl = "tgl_".$i;
                                   $datapresensi = explode("|", $r->$tgl);
                                   if ($r->$tgl != NULL) {
                                    # code...
                                    $status = $datapresensi[2];
                                    $jam_in = $datapresensi[0] != "NA" ? date("H:i", strtotime($datapresensi[0]))  : 'NoAbsen';
                                    $jam_out = $datapresensi[1] != "NA" ? date("H:i", strtotime($datapresensi[1]))  : 'NoAbsen';
                                    $jam_masuk = $datapresensi[4] != "NA" ? date("H:i", strtotime($datapresensi[4]))  : '';
                                    $jam_pulang = $datapresensi[5] != "NA" ? date("H:i", strtotime($datapresensi[5]))  : '';
                                    $nama_jam_kerja = $datapresensi[3] != "NA" ? $datapresensi[3] : '';

                                    $terlambat = hitungjamterlambat($jam_masuk, $jam_in);

                                    if ($status == "h" && $terlambat > "00:00") {
                                    $denda_hari_ini = hitungdenda($terlambat);
                                    $total_denda += $denda_hari_ini; // Akumulasi denda
                                   }
                                    
                                   }else {
                                    $status = "";
                                    $jam_in = "";
                                    $jam_out = "";
                                    $jam_masuk = "";
                                    $jam_pulang = "";
                                    $nama_jam_kerja = "";
                                   }
                                   
                                   if($status == "h"){
                                    $jml_hadir += 1;
                                    $color ="gray";
                                    
                                   }
                                   if($status == "i"){
                                    $jml_izin += 1;
                                    $color = "blue";
                                   }
                                   if($status == "s"){
                                    $jml_sakit += 1;
                                    $color = "green";
                                   }
                                   if($status == "c"){
                                    $jml_cuti += 1;
                                    $color = "yellow";
                                   }
                                   
                                   if(empty($status)){
                                    $jml_alpa += 1;
                                    $color = "white";
                                   }
                        ?>
                        <td style="background-color: {{ $color }}">
                            {{ $status }}
                            @if ($status == 'h' && $terlambat > 0)
                                <span style="font-weight: bold">

                                    {{ $nama_jam_kerja }}
                                </span>
                                <br>
                                <span style="color: green">

                                    Jam Kerja: {{ $jam_masuk }}-{{ $jam_pulang }}
                                </span>
                                <br>
                                <span>
                                    {{ $jam_in }} - {{ $jam_out }}
                                </span>
                                <br>
                                @if ($terlambat > 0)
                                    <span style="color: red">
                                        Terlambat: {{ $terlambat }}
                                        <br>
                                        Denda: Rp {{ number_format(hitungdenda($terlambat), 0, ',', '.') }}
                                    </span>
                                @endif
                            @endif

                        </td>
                        <?php
                                
                                }
                            ?>
                        <td>{{ !empty($jml_hadir) ? $jml_hadir : ' ' }}</td>
                        <td>{{ !empty($jml_izin) ? $jml_izin : ' ' }}</td>
                        <td>{{ !empty($jml_sakit) ? $jml_sakit : ' ' }}</td>
                        <td>{{ !empty($jml_cuti) ? $jml_cuti : ' ' }}</td>

                        <td style="text-align: right">
                            @if ($total_denda > 0)
                                Rp {{ number_format($total_denda, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>


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
