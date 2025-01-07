@extends('layouts.presensi')
@section('header')
    {{-- app header --}}
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Form Izin</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="row" style="margin-top:70px">
        <div class="col">
            <form action="/presensi/storeizin" method="POST">
                @csrf
                <div class="form-group">
                    <input type="date" id="tgl_izin" name="tgl_izin" class="form-control" placeholder="Masukan tanggal"
                        required>
                </div>
                <div class="form-group">
                    <select name="status" id="status" class="form-control" required>
                        <option value="">--Izin/Sakit--</option>
                        <option value="i">Izin</option>
                        <option value="s">Sakit</option>
                    </select>
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
        $(document).ready(function() {
            $("#tgl_izin").change(function(e) {
                var tgl_izin = $(this).val();
                $.ajax({
                    type: 'POST',
                    url: '/presensi/cekpengajuanizin',
                    data: {
                        _token: "{{ csrf_token() }}",
                        tgl_izin: tgl_izin
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond == 1) {
                            Swal.fire({
                                title: 'Oops!',
                                text: 'Data sudah ada di tanggal yang sama',
                                icon: 'warning'
                            }).then((result) => {
                                $('#tgl_izin').val("");
                            });
                        }

                    }
                });
            });
        });
    </script>
@endpush
