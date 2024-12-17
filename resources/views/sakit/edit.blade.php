@extends('layouts.presensi')
@section('header')
    {{-- app header --}}
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Edit Izin Sakit</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="row" style="margin-top:70px">
        <div class="col">
            <form action="/izinsakit/{{ $dataizin->kode_izin }}/update" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <input type="tetx" id="tgl_izin_dari" value="{{ $dataizin->tgl_izin_dari }}" name="tgl_izin_dari"
                        class="form-control datepicker" placeholder="Dari" required>
                </div>
                <div class="form-group">
                    <input type="text" id="tgl_izin_sampai" value="{{ $dataizin->tgl_izin_sampai }}"
                        name="tgl_izin_sampai" class="form-control datepicker" placeholder="Sampai" required>
                </div>
                <div class="form-group">
                    <input type="text" id="jml_hari" name="jml_hari" class="form-control" placeholder="Jumlah hari"
                        readonly required>
                </div>
                <div class="form-group">
                    <input type="text" name="keterangan" value="{{ $dataizin->keterangan }}" id="keterangan"
                        class="form-control" autocomplete="off" placeholder="Keterangan" required></input>
                </div>

                @if ($dataizin->doc_sid != null)
                    <div class="row">
                        <div class="col-12">
                            @php
                                $docsid = Storage::url('/uploads/sid/' . $dataizin->doc_sid);
                            @endphp
                            <img src="{{ url($docsid) }}" alt="" width="100px">
                        </div>
                    </div>
                @endif
                <div class="custom-file-upload" id="fileUpload1" style="height: 100px !important">
                    <input type="file" name="sid" id="fileuploadInput" accept=".png, .jpg, .jpeg">
                    <label for="fileuploadInput">
                        <span>
                            <strong>
                                <ion-icon name="cloud-upload-outline" role="img" class="md hydrated"
                                    aria-label="cloud upload outline"></ion-icon>
                                <i>Tap to Upload SID</i>
                                <small>maukan file foto</small>
                            </strong>
                        </span>
                    </label>
                </div>
                <div class="form-group mt-3">
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
            loadjumlahhari();
            $("#tgl_izin_dari, #tgl_izin_sampai").change(function(e) {
                loadjumlahhari();
            })

        });
    </script>
@endpush
