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
        body.A4.landscape .sheet{
            
            height: auto !important;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4 landscape">
    {{-- @php
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
    @endphp --}}
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
                    <th rowspan="2">A</th>

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
                        $color = "";
                        for ($i=1; $i <= $jmlhari ; $i++) { 
                                    $tgl = "tgl_".$i;
                                   $datapresensi = explode("|", $r->$tgl);
                                   if ($r->$tgl != NULL) {
                                    # code...
                                    $status = $datapresensi[2];
                                   }else{
                                    $status = "";
                                   }
                                   
                                   if($status == "h"){
                                    $jml_hadir += 1;
                                    $color ="white";
                                   }
                                   if($status == "i"){
                                    $jml_izin += 1;
                                   }
                                   if($status == "s"){
                                    $jml_sakit += 1;
                                    $color = "green";
                                   }
                                   if($status == "c"){
                                    $jml_cuti += 1;
                                   }
                                   
                                   if(empty($status)){
                                    $jml_alpa += 1;
                                    $color = "red";
                                   }
                        ?>
                        <td style="background-color: {{$color}}">
                            {{ $status }}
                        </td>
                        <?php
                                
                                }
                            ?>
                        <td>{{ !empty($jml_hadir) ? $jml_hadir : ' ' }}</td>
                        <td>{{ !empty($jml_izin) ? $jml_izin : ' ' }}</td>
                        <td>{{ !empty($jml_sakit) ? $jml_sakit : ' ' }}</td>
                        <td>{{ !empty($jml_cuti) ? $jml_cuti : ' ' }}</td>
                        <td>{{ !empty($jml_alpa) ? $jml_alpa : ' ' }}</td>
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
