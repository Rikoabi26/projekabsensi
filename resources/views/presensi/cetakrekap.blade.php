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
                    <th colspan="31">Tanggal</th>
                    <th rowspan="2">TH</th>
                    <th rowspan="2">TT</th>
                </tr>
                <tr>
                    <?php
                        for($i=1; $i<=31; $i++){
                    ?>
                    <th>{{ $i }}</th>
                    <?php
                        }
                    ?>
                </tr>
                @foreach ($rekap as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $d->nama_lengkap }}</td>
                        <?php
                        $totalhadir = 0;
                        $totalterlambat = 0;
                        for($i=1; $i<=31; $i++){
                            $tgl = "tgl_".$i;
                            if(empty($d->$tgl)){
                                $hadir = ['',''];
                                $totalhadir +=0;
                            }else{
                                $hadir = explode("-", $d->$tgl); 
                                $totalhadir += 1;
                                if($hadir[0] > $d->jam_masuk){
                                    $totalterlambat +=1;
                                }
                            }
                    ?>
                        <td>
                            <span style="color:{{ $hadir[0] > $d->jam_masuk ? 'red' : '' }}">{{!empty($hadir[0]) ? $hadir[0] : '-'}}</span><br>
                            <span style="color:{{ $hadir[1] < $d->jam_pulang ? 'red' : '' }}">{{!empty($hadir[1]) ? $hadir[1] : '-'}}</span>
                        </td>
                        
                        <?php
                        }
                    ?>
                    <td>{{$totalhadir}}</td>
                    <td>{{$totalterlambat}}</td>
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
