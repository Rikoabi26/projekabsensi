@extends('layouts.presensi')
@section('header')
    {{-- app header --}}
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Form Izin Sakit</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="row" style="margin-top:70px">
        <div class="col">
            <form action="/izinabsen/store" method="POST">
                @csrf
                <div class="form-group">
                    <input type="tetx" id="tgl_izin_dari" name="tgl_izin_dari" class="form-control datepicker" placeholder="Dari"
                        required>
                </div>
                <div class="form-group">
                    <input type="text" id="tgl_izin_sampai" name="tgl_izin_sampai" class="form-control datepicker"
                        placeholder="Sampai" required>
                </div>
                <div class="form-group">
                    <input type="text" id="jml_hari" name="jml_hari" class="form-control" placeholder="Jumlah hari" readonly
                        required>
                </div>
                <div class="form-group">
                    <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-group" placeholder="--Keterangan"
                        required></textarea>
                </div>
                <div class="form-group">
                    <button class="btn btn-primary w-100">Kirim</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('myscript')
    <script>
        var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
            $('.datepicker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true, // Menutup datepicker setelah memilih tanggal
                todayHighlight: true // Sorot tanggal hari ini
            });

            function loadjumlahhari() {
                var dari = $('#tgl_izin_dari').val();
                var sampai = $("#tgl_izin_sampai").val();
                var date1 = new Date(dari);
                var date2 = new Date(sampai);


                //to calculate the time difference of two dates
                var difference_In_Time = date2.getTime() - date1.getTime();

                //To Calculate the no. of days between two dates
                var difference_In_Days = difference_In_Time / (1000 * 3600 * 24);

                if (dari == "" || sampai == "") {
                    var jmlhari = 0;
                } else {
                    var jmlhari = difference_In_Days + 1;

                }
                //To Display the final no. of days(result)
                $("#jml_hari").val(jmlhari + " Hari");
            }
            $("#tgl_izin_dari, #tgl_izin_sampai").change(function(e) {
                loadjumlahhari();
            })

        });
    </script>
@endpush
